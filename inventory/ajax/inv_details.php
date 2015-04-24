<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011, 2012 UcSoft, LLC       |
// | http://www.UcSoft.com                                        |
// +-----------------------------------------------------------------+
// | This program is free software: you can redistribute it and/or   |
// | modify it under the terms of the GNU General Public License as  |
// | published by the Free Software Foundation, either version 3 of  |
// | the License, or any later version.                              |
// |                                                                 |
// | This program is distributed in the hope that it will be useful, |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of  |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the   |
// | GNU General Public License for more details.                    |
// +-----------------------------------------------------------------+
//  Path: /modules/inventory/ajax/inv_details.php
//
$security_level = validate_ajax_user();
/**************  include page specific files    *********************/
gen_pull_language('ucbooks');
require(DIR_FS_MODULES . 'inventory/defaults.php');
require(DIR_FS_MODULES . 'inventory/functions/inventory.php');
/**************   page specific initialization  *************************/
// One of the following identifers is required.
$sku    = db_prepare_input($_GET['sku']); // specifies the sku, could be a search field as well
$UPC    = db_prepare_input($_GET['upc']); // specifies the upc code
$iID    = db_prepare_input($_GET['iID']); // specifies the item database id
// optional for more detailed operation
$bID    = db_prepare_input($_GET['bID']); // specifies the branch ID
$cID    = db_prepare_input($_GET['cID']); // specifies the contact ID
$jID    = db_prepare_input($_GET['jID']); // specifies the journal ID
$rID    = db_prepare_input($_GET['rID']); // specifies the row to update
$qty    = db_prepare_input($_GET['qty']); // specifes the quantity (for pricing)
$strict = $_GET['strict'] ? true : false; // specifes strict match of sku value
// some error checking
if (!$sku && !$UPC && !$iID) {
  echo createXmlHeader() . xmlEntry('error', AJAX_INV_NO_INFO) . createXmlFooter();
  die;
}
if (!$qty) $qty = 1; // assume that one is required, will set to 1 on the form
if (!$bID) $bID = 0; // assume only one branch or main branch if not specified
// Load the sku information
if ($iID) {
  $search = " where id = '" . $iID . "'";
} else if ($UPC) {
  $search = " where upc_code = '" . $UPC . "'";
} else if ($strict){ // exact search for sku match
  $search = " where sku = '" . $sku . "'";
} else { // broad search for a match
  $search_fields = array('sku', 'upc_code', 'description_short', 'description_sales', 'description_purchase');
  $search = ' where ' . implode(' = "' . $sku . '" or ', $search_fields) . ' = "' . $sku.'"';
}

$vendor_search = false;
$vendor        = in_array($jID, array(3,4,6,7)) ? true : false;
if ($vendor) { // just search for products from that vendor for purchases
  $first_search  = $search . " and vendor_id = '" . $cID . "'";
  $inventory     = $db->Execute("select * from " . TABLE_INVENTORY . $first_search);
  $vendor_search = $inventory->recordCount() ? true : false;
}
if (!$vendor || !$vendor_search) {
  $inventory = $db->Execute("select * from " . TABLE_INVENTORY . $search);
}
$count = $inventory->RecordCount();
if ($UPC && $inventory->RecordCount() <> 1) { // for UPC codes submitted only, send an error
  echo createXmlHeader() . xmlEntry('error', ORD_JS_SKU_NOT_UNIQUE) . createXmlFooter();
  die;
} else if ($inventory->RecordCount() <> 1) { // need to return something to avoid error in FireFox
  echo createXmlHeader() . xmlEntry('result', 'Not enough or too many hits, exiting!') . createXmlFooter();  
  die;
}
$iID = $inventory->fields['id']; // set the item id (just in case UPC or sku was the only identifying parameter)
$sku = $inventory->fields['sku'];
// fix some values for special cases
$cog_types = explode(',', COG_ITEM_TYPES);
if (!in_array($inventory->fields['inventory_type'], $cog_types)) $inventory->fields['quantity_on_hand'] = 'NA';
// load branch stock ( must be before BOM loading )
$inventory->fields['branch_qty_in_stock'] = (strpos(COG_ITEM_TYPES, $inventory->fields['inventory_type']) === false) ? 'NA' : strval(load_store_stock($sku, $bID));
//$debug .= 'qty in stock = ' . $inventory->fields['quantity_on_hand'] . ' and branch qty = ' . $inventory->fields['branch_qty_in_stock'];
// Load the assembly information
$assy_cost = 0;
if ($inventory->fields['inventory_type'] == 'as' || $inventory->fields['inventory_type'] == 'sa') {
  $result = $db->Execute("select sku, qty from " . TABLE_INVENTORY_ASSY_LIST . " where ref_id = '" . $iID . "'");
  $bom    = array();
  while (!$result->EOF) {
	$sql = "select description_short, inventory_type, item_cost, quantity_on_hand from " . TABLE_INVENTORY . " where sku = '" . $result->fields['sku'] . "'";
	$sku_cost = $db->Execute($sql);
	$assy_cost += $result->fields['qty'] * $sku_cost->fields['item_cost'];
	if (in_array($sku_cost->fields['inventory_type'], $cog_types)) {
	  $qty_in_stock = strval(load_store_stock($result->fields['sku'], $bID));
	} else {
	  $qty_in_stock = 'NA';
	}
	$bom[] = array(
	  'qty'               => $result->fields['qty'],
	  'sku'               => $result->fields['sku'],
	  'description_short' => $sku_cost->fields['description_short'],
	  'item_cost'         => $sku_cost->fields['item_cost'],
	  'quantity_on_hand'  => $qty_in_stock,
	);
	$result->MoveNext();
  }
} else {
  $assy_cost = $inventory->fields['item_cost'];
}
// load where used
$result = $db->Execute("select ref_id, qty from " . TABLE_INVENTORY_ASSY_LIST . " where sku = '" . $sku . "'");
if ($result->RecordCount() == 0) {
  $sku_usage = array(JS_INV_TEXT_USAGE_NONE);
} else {
  $sku_usage = array(JS_INV_TEXT_USAGE);
  while (!$result->EOF) {
    $stock = $db->Execute("select sku, description_short from " . TABLE_INVENTORY . " where id = '" . $result->fields['ref_id'] . "'");
    $sku_usage[] =  TEXT_QUANTITY . ' ' . $result->fields['qty'] . ' ' . TEXT_SKU . ': ' . $stock->fields['sku'] . ' - ' . $stock->fields['description_short'];
    $result->MoveNext();
  }
}
// load prices, tax
$prices = inv_calculate_sales_price(abs($qty), $iID, $cID, $vendor ? 'v' : 'c');
$sales_price = strval($prices['price']);
$inventory->fields['item_taxable']  = strval($prices['sales_tax']);
$inventory->fields['purch_taxable'] = strval($prices['purch_tax']);
//$debug .= 'sales_tax = ' . $prices['sales_tax'] . ' and purch tax = ' . $prices['purch_tax'] . ' and price = ' . $sales_price . chr(10);
// Load default tax to use
if ($cID) {
	
}
// load sku stock status and open orders
$stock_note = array();
switch($jID) {
  case  '3':
  case  '4':
  case  '6':
  case '21':
	break;
  case  '9':
  case '10':
  case '12':
  case '19':
	// check for stock available for SO, Customer Quote and Sales
	if ($qty > $inventory->fields['branch_qty_in_stock'] && strpos(COG_ITEM_TYPES, $inventory->fields['inventory_type']) !== false) {
	  $stock_note   = array(ORD_INV_STOCK_LOW);
	  $stock_note[] = ORD_INV_STOCK_BAL . $inventory->fields['branch_qty_in_stock'];
	  // fetch open orders
	  $sku_history = gather_history($inventory->fields['sku']);
	  if (is_array($sku_history['open_po'])) {
	    $stock_note[] = ORD_INV_OPEN_POS;
	    foreach ($sku_history['open_po'] as $value) {
		  $store = $value['store_id'] ? gen_get_contact_name($value['store_id']) : COMPANY_NAME; // get name from id
		  $stock_note[] = sprintf(ORD_INV_STOCK_STATUS, $store, $value['purchase_invoice_id'], $value['qty'], gen_locale_date($value['date_1']));
	    }
	  }
	}
	break;
  case  '7':
  case '13':
  default:
}

//put it all together
          $xml .= xmlEntry("qty", $qty);
if ($cID) $xml .= xmlEntry("cID", $cID);
if ($jID) $xml .= xmlEntry("jID", $jID);
if ($rID) $xml .= xmlEntry("rID", $rID);
// build the core inventory data
foreach ($inventory->fields as $key => $value) $xml .= "\t" . xmlEntry($key, $value);
// build the assembly information
if (sizeof($bom) > 0) foreach ($bom as $value) {
  $xml .= "<bom>\n";
  $xml .= "\t" . xmlEntry("bom_qty",               $value['qty']);
  $xml .= "\t" . xmlEntry("bom_sku",               $value['sku']);
  $xml .= "\t" . xmlEntry("bom_description_short", $value['description_short']);
  $xml .= "\t" . xmlEntry("bom_item_cost",         $value['item_cost']);
  $xml .= "\t" . xmlEntry("bom_quantity_on_hand",  $value['quantity_on_hand']);
  $xml .= "</bom>\n";
}
$xml .= xmlEntry("assy_cost", $assy_cost);
// build where used
foreach ($sku_usage as $value) {
  $xml .= "<sku_usage>\n";
  $xml .= "\t" . xmlEntry("text_line", $value);
  $xml .= "</sku_usage>\n";
}
// build the sales price
$xml .= xmlEntry("sales_price", $sales_price);
// build the stock status
if (sizeof($stock_note) > 0) {
  foreach ($stock_note as $value) {
    $xml .= "<stock_note>\n";
    $xml .= "\t" . xmlEntry("text_line", $value);
    $xml .= "</stock_note>\n";
  }
}
if ($debug) $xml .= xmlEntry('debug', $debug);
echo createXmlHeader() . $xml . createXmlFooter();
die;
?>