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
//  Path: /modules/ucbooks/ajax/load_order.php
//
/* * ************   Check user security   **************************** */
$xml = NULL;
$debug = NULL;
$security_level = validate_ajax_user();
/* * ************  include page specific files    ******************** */
gen_pull_language('contacts');
require_once(DIR_FS_MODULES . 'ucbooks/defaults.php');
require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');
/* * ************   page specific initialization  ************************ */
$cID = db_prepare_input($_GET['cID']);
$oID = db_prepare_input($_GET['oID']);
$jID = db_prepare_input($_GET['jID']);
$so_po = 1; //db_prepare_input($_GET['so_po']); // pull from a so/po for invoice/receipt
$just_ship = db_prepare_input($_GET['ship_only']);
define('JOURNAL_ID', $jID);
define('SUB_JOURNAL_ID', 0);
$cog_types = explode(',', COG_ITEM_TYPES);
$error = false;
$sID = $cID; // set ship contact ID equal to bill contact ID
switch (JOURNAL_ID) {
    case 3:
    case 4: define('GL_TYPE', 'poo');
        break;
    case 6:
    case 31:
    case 7: define('GL_TYPE', 'por');
        break;
    case 9:
    case 10: define('GL_TYPE', 'soo');
        break;
    case 32: define('GL_TYPE', 'doo');
        break;
    case 12:
    case 36:
    case 13: define('GL_TYPE', 'sos');
        break;
    case 18: define('GL_TYPE', 'swr');
        break;
    case 20: define('GL_TYPE', 'pwp');
        break;
    case 30: define('GL_TYPE', 'sos');
        break;
    default:
}

if ($cID) {
    $order = $db->Execute("select * from " . TABLE_JOURNAL_MAIN . " where id = '" . $cID . "'");
    if ($order->fields['bill_acct_id'])
        $cID = $order->fields['bill_acct_id']; // replace cID with bill contact ID from order
    if ($order->fields['ship_acct_id'])
        $sID = $order->fields['ship_acct_id']; // replace sID with ship contact ID from order
    $currencies_code = $order->fields['currencies_code'];
    $currencies_value = $order->fields['currencies_value'];
    if ($_GET['action'] != 'edit')
        if ($order->fields['message'])
            $order->fields['message'] = constant('MESSAGE_' . JOURNAL_ID . '_' . SUB_JOURNAL_ID);
} else {
    $order = new objectInfo();
}
// select the customer and build the contact record
if ($sID) {
    $sContact = $db->Execute("select * from " . TABLE_CONTACTS . " where id = '" . $cID . "'");
    $type = $sContact->fields['type'];
    $ship_add = $db->Execute("select * from " . TABLE_ADDRESS_BOOK . " 
    where ref_id = '" . $cID . "' and type in ('" . $type . "m', '" . $type . "s') ORDER BY address_id DESC");
}
if ($cID && !$just_ship) { // build the contact data
    $contact = $db->Execute("select * from " . TABLE_CONTACTS . " where id = '" . $cID . "'");
    $type = $contact->fields['type'];
    $terms_type = ($type == 'v') ? 'AP' : 'AR';
    $contact->fields['terms_text'] = gen_terms_to_language($contact->fields['special_terms'], true, $terms_type);
    $contact->fields['ship_gl_acct_id'] = ($type == 'v') ? AP_DEF_FREIGHT_ACCT : AR_DEF_FREIGHT_ACCT;
    $invoices = fill_paid_invoice_array(0, $cID, $type);
    $terms = explode(':', $contact->fields['special_terms']);
    $contact->fields['credit_limit'] = $terms[4] ? $terms[4] : ($type == 'v' ? AP_CREDIT_LIMIT_AMOUNT : AR_CREDIT_LIMIT_AMOUNT);
    $contact->fields['credit_remaining'] = $contact->fields['credit_limit'] - $invoices['balance'] + $order->fields['total_amount'];
    $bill_add = $db->Execute("select * from " . TABLE_ADDRESS_BOOK . " 
    where ref_id = '" . $cID . "' and type in ('" . $type . "m', '" . $type . "b') ORDER BY address_id DESC");
    //fix some special fields
    if (!$contact->fields['dept_rep_id'])
        unset($contact->fields['dept_rep_id']); // clear the rep field if not set to a contact
}
// Now fill the order, if it is requested
if (sizeof($order->fields) > 0) {
    // correct check boxes since changing the values will not affect the check status but change the value behind it
    $order->fields['cb_closed'] = ($order->fields['closed'] == '1') ? 1 : 0;
    $order->fields['cb_waiting'] = ($order->fields['waiting'] == '1') ? 1 : 0;
    $order->fields['cb_drop_ship'] = ($order->fields['drop_ship'] == '1') ? 1 : 0;
    //unset($order->fields['closed']);
    unset($order->fields['waiting']);
    unset($order->fields['drop_ship']);
    // some adjustments based on what we are doing
    $order->fields['search'] = $contact->fields['short_name'];
    $order->fields['post_date'] = gen_locale_date($order->fields['post_date']);
    $order->fields['terms_text'] = gen_terms_to_language($order->fields['terms'], true, $terms_type);
    $order->fields['disc_percent'] = '0';
    if ($order->fields['terminal_date'] == '000-00-00' || $order->fields['terminal_date'] == '') {
        unset($order->fields['terminal_date']);
    } else {
        $order->fields['terminal_date'] = gen_locale_date($order->fields['terminal_date']);
    }
    if (!$order->fields['rep_id'])
        unset($order->fields['rep_id']);
    $ship_level = explode(':', $order->fields['shipper_code']);
    $order->fields['ship_carrier'] = $ship_level[0];
    $order->fields['ship_service'] = $ship_level[1];
    $order->fields['attach_exist'] = file_exists(UCBOOKS_DIR_MY_ORDERS . 'order_' . $cID . '.zip') ? 1 : 0;
    if ($so_po) { // opening a SO/PO for Invoice/Receive
        $id = 0;
        $so_po_ref_id = $order->fields['id'];
        $order->fields['so_po_ref_id'] = 0; //$so_po_ref_id;
        $order->fields['cb_closed'] = 0;
        $order->fields['cb_waiting'] = 0;
        if (JOURNAL_ID == 6)
            $order->fields['purch_order_id'] = $order->fields['purchase_invoice_id'];
        if (JOURNAL_ID == 12 || JOURNAL_ID == 36)
            $order->fields['sales_order_num'] = $order->fields['purchase_invoice_id'];

        if (JOURNAL_ID == 30 || JOURNAL_ID == 31 || JOURNAL_ID == 7 || JOURNAL_ID == 13) {      // if this journal id belongs to credit memo then purchase_invoice_id is ssaving into purch_order_id field
            $order->fields['purch_order_id'] = $order->fields['purchase_invoice_id'] . " (" . $order->fields['post_date'] . ")";
        }
        unset($order->fields['id']);
        unset($order->fields['purchase_invoice_id']);
        unset($order->fields['id']);
        unset($order->fields['post_date']);
        unset($order->fields['recur_id']);
        unset($order->fields['recur_frequency']);
    } else {
        $id = 0; //$order->fields['id'];
        $so_po_ref_id = 0; //$order->fields['so_po_ref_id'];
    }

    // fetch the line items
    $item_list = array();
    $sku = array();
    $unitPrice = array();
    $subtotal = 0;
    $duplicate_item = false;
    if ($so_po_ref_id) { // then there is a purchase order/sales order to load first
        if (JOURNAL_ID == 12 || JOURNAL_ID == 36) { // fetch the sales order number
            $result = $db->Execute("select purchase_invoice_id from " . TABLE_JOURNAL_MAIN . " where id = " . $so_po_ref_id);
            $order->fields['sales_order_num'] = $result->fields['purchase_invoice_id'];
        }
        // fetch the so/po line items per the original order
        $ordr_items = $db->Execute("select * from " . TABLE_JOURNAL_ITEM . " where ref_id in( " . $oID . " ) order by item_cnt, id");
        while (!$ordr_items->EOF) {
            $total = $ordr_items->fields['credit_amount'] + $ordr_items->fields['debit_amount'];
            if (in_array($ordr_items->fields['gl_type'], array('poo', 'soo', 'doo', 'por', 'sos'))) {
                $subtotal += $total;
                $sku[$ordr_items->fields['id']] = $ordr_items->fields['sku'];
                $inv_details = $db->Execute("select inventory_type, inactive, item_weight, quantity_on_hand, lead_time 
		    from " . TABLE_INVENTORY . " where sku = '" . $ordr_items->fields['sku'] . "'");
                if (!in_array($inv_details->fields['inventory_type'], $cog_types))
                    $inv_details->fields['quantity_on_hand'] = 'NA';
                if (UOM_QTY == 1) {//this is for get unit price with uom qty
                    $unit_price = $ordr_items->fields['qty'] ? ($total / $ordr_items->fields['qty']) : '0';
                    $unit_price = $unit_price / ($ordr_items->fields['uom_qty'] ? $ordr_items->fields['uom_qty'] : 1);
                } else {
                    $unit_price = $ordr_items->fields['qty'] ? ($total / $ordr_items->fields['qty']) : '0';
                }

                if (in_array($ordr_items->fields['sku'], $sku)) {
                    if (in_array($unit_price, $unitPrice)) {
                        foreach ($unitPrice as $key => $u_price) {
                            if ($unit_price == $u_price) {
                                foreach ($item_list as $key1 => $item) {
                                    if ($item['so_po_item_ref_id'] == $key) {
                                        $item_list[$key1]['qty'] += $ordr_items->fields['qty'];
                                        $item_list[$key1]['uom'] += $ordr_items->fields['uom'];
                                        $item_list[$key1]['uom_qty'] += $ordr_items->fields['uom_qty'];
                                        $item_list[$key1]['so_po_item_ref_id'] = $item_list[$key1]['so_po_item_ref_id'] . ',' . $ordr_items->fields['id'];

                                        $item_list[$key1]['total'] += $currencies->format($total, true, $currencies_code, $currencies_value);
                                        $item_list[$key1]['full_price'] += $currencies->format($ordr_items->fields['full_price'], true, $currencies_code, $currencies_value);
                                        $item_list[$key1]['stock'] += $inv_details->fields['quantity_on_hand'];

                                        $duplicate_item = true;
                                    }
                                }
                            }
                        }
                    }
                    if ($duplicate_item == false) {
                        $item_list[] = array(
                            'item_cnt' => $ordr_items->fields['item_cnt'],
                            'so_po_item_ref_id' => $ordr_items->fields['id'],
                            'qty' => $ordr_items->fields['qty'],
                            'sku' => $ordr_items->fields['sku'],
                            'gl_type' => $ordr_items->fields['gl_type'],
                            'description' => $ordr_items->fields['description'],
                            'description_hide' => $ordr_items->fields['description'],
                            'gl_account' => $ordr_items->fields['gl_account'],
                            'taxable' => $ordr_items->fields['taxable'],
                            'serialize' => $ordr_items->fields['serialize_number'],
                            'proj_id' => $ordr_items->fields['project_idd'],
                            'uom' => $ordr_items->fields['uom'],
                            'uom_qty' => $ordr_items->fields['uom_qty'],
                            'unit_price' => $currencies->precise($unit_price, true, $currencies_code, $currencies_value),
                            'total' => $currencies->format($total, true, $currencies_code, $currencies_value),
                            'full_price' => $currencies->format($ordr_items->fields['full_price'], true, $currencies_code, $currencies_value),
                            'inactive' => $inv_details->fields['inactive'],
                            'weight' => $inv_details->fields['item_weight'],
                            'stock' => $inv_details->fields['quantity_on_hand'],
                            'lead' => $inv_details->fields['lead_time'],
                        );
                    }
                }
                $duplicate_item = false;
                $unitPrice[$ordr_items->fields['id']] = $unit_price;
            } else if ($ordr_items->fields['gl_type'] == 'dsc') {
                $discount = $ordr_items->fields['credit_amount1'] + $ordr_items->fields['debit_amount1'];
            } else {
                $inv_details = new objectInfo();
            }
            $ordr_items->MoveNext();
        }
        // calculate remaining qty levels not including this order
        $sql = "select sum(i.qty) as qty, i.sku, i.so_po_item_ref_id
			from " . TABLE_JOURNAL_MAIN . " m left join " . TABLE_JOURNAL_ITEM . " i on m.id = i.ref_id 
			where i.so_po_item_ref_id in( SELECT id FROM " . TABLE_JOURNAL_ITEM . " WHERE ref_id in(" . $oID . ") and gl_type = 'doo') and m.id <> " . $id . " GROUP BY i.sku";
        $posted_items = $db->Execute($sql);
        while (!$posted_items->EOF) {
            for ($i = 0; $i < count($item_list); $i++) {
                if ($item_list[$i]['so_po_item_ref_id'] == $posted_items->fields['so_po_item_ref_id']) {
                    $item_list[$i]['qty'] -= $posted_items->fields['qty'];
                    $item_list[$i]['qty'] = max(0, $item_list[$i]['qty']); // don't let it go negative
                    break;
                }
            }
            $posted_items->MoveNext();
        }
        $order->fields['disc_percent'] = ($subtotal <> 0) ? 100 * (1 - (($subtotal - $discount) / $subtotal)) : '0';
    }
    if ($oID = false) {
        // retrieve item information
        $subtotal = 0;
        $sql = "select *,SUM(credit_amount) as credit_amount1, SUM(debit_amount) AS debit_amount1 from  " . TABLE_JOURNAL_ITEM . " where ref_id in(" . $oID . ") GROUP BY sku  order by item_cnt, id";
        //$ordr_items = $db->Execute("select * from " . TABLE_JOURNAL_ITEM . " where ref_id in(" . $oID . ") order by item_cnt, id");
        $ordr_items = $db->Execute($sql);
        switch (JOURNAL_ID) { // determine where to put value, qty or pstd
            case 3:
            case 4:
            case 9:
            case 10:
            case 32: $qty_pstd = 'qty';
                break;
            case 6:
            case 7:
            case 12:
            case 36:
            case 13:
            case 31:
            case 30:$qty_pstd = 'pstd';
                break;
            default:
        }
        while (!$ordr_items->EOF) {
            $found = false;
            $total = $ordr_items->fields['credit_amount'] + $ordr_items->fields['debit_amount'];
            if (in_array($ordr_items->fields['gl_type'], array('poo', 'soo', 'doo', 'por', 'sos'))) {
                $subtotal += $total;
                $inv_details = $db->Execute("select inactive, inventory_type, item_weight, quantity_on_hand, lead_time 
		    from " . TABLE_INVENTORY . " where sku = '" . $ordr_items->fields['sku'] . "'");
                $inv_details->fields['quantity_on_hand'] = $ordr_items->fields['qty'] + $inv_details->fields['quantity_on_hand'];
                if (!in_array($inv_details->fields['inventory_type'], $cog_types))
                    $inv_details->fields['quantity_on_hand'] = 'NA';
            } else if ($ordr_items->fields['gl_type'] == 'dsc') {
                $discount = $ordr_items->fields['credit_amount'] + $ordr_items->fields['debit_amount'];
            } else {
                $inv_details = new objectInfo();
            }
//$debug .= ' processing quantity_on_hand = ' . $inv_details->fields['quantity_on_hand'] . ' and total = ' . $total . chr(10);
            if ($so_po_ref_id) {
                for ($i = 0; $i < count($item_list); $i++) {

                    if (UOM_QTY == 1) {//this is for get unit price with uom qty
                        $unit_price = $ordr_items->fields['qty'] ? ($total / $ordr_items->fields['qty']) : '0';
                        $unit_price = $unit_price / ($ordr_items->fields['uom_qty'] ? $ordr_items->fields['uom_qty'] : 1);
                    } else {
                        $unit_price = $ordr_items->fields['qty'] ? ($total / $ordr_items->fields['qty']) : '0';
                    }
                    if ($ordr_items->fields['so_po_item_ref_id'] && $item_list[$i]['so_po_item_ref_id'] == $ordr_items->fields['so_po_item_ref_id']) {
                        $item_list[$i]['id'] = $ordr_items->fields['id'];
                        $item_list[$i]['item_cnt'] = $ordr_items->fields['item_cnt'];
                        $item_list[$i]['gl_type'] = $ordr_items->fields['gl_type'];
                        $item_list[$i][$qty_pstd] = $ordr_items->fields['qty'];
                        $item_list[$i]['description'] = $ordr_items->fields['description'];

                        $item_list[$i]['gl_account'] = $ordr_items->fields['gl_account'];
                        $item_list[$i]['taxable'] = $ordr_items->fields['taxable'];
                        $item_list[$i]['serialize'] = $ordr_items->fields['serialize_number'];
                        $item_list[$i]['proj_id'] = $ordr_items->fields['project_id'];
                        $item_list[$i]['uom'] = $ordr_items->fields['uom'];
                        $item_list[$i]['uom_qty'] = $ordr_items->fields['uom_qty'];

                        $item_list[$i]['unit_price'] = $currencies->precise($unit_price, true, $currencies_code, $currencies_value);
                        $item_list[$i]['total'] = $currencies->format($total, true, $currencies_code, $currencies_value);
                        $item_list[$i]['full_price'] = $currencies->format($ordr_items->fields['full_price'], true, $currencies_code, $currencies_value);
                        $item_list[$i]['inactive'] = $inv_details->fields['inactive'];
                        $item_list[$i]['weight'] = $inv_details->fields['item_weight'];
                        $item_list[$i]['stock'] = $inv_details->fields['quantity_on_hand'];
                        $item_list[$i]['lead'] = $inv_details->fields['lead_time'];
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) { // it's an addition to the po/so entered at the purchase/sales window
                if (UOM_QTY == 1) {//this is for get unit price with uom qty
                    $unit_price = $ordr_items->fields['qty'] ? ($total / $ordr_items->fields['qty']) : '0';
                    $unit_price = $unit_price / ($ordr_items->fields['uom_qty'] ? $ordr_items->fields['uom_qty'] : 1);
                } else {
                    $unit_price = $ordr_items->fields['qty'] ? ($total / $ordr_items->fields['qty']) : '0';
                }
                $item_list[] = array(
                    'id' => $ordr_items->fields['id'],
                    'item_cnt' => $ordr_items->fields['item_cnt'],
                    'gl_type' => $ordr_items->fields['gl_type'],
                    $qty_pstd => $ordr_items->fields['qty'],
                    'sku' => $ordr_items->fields['sku'],
                    //'description' => $ordr_items->fields['description'],
                    'description' => html_entity_decode(stripslashes(strip_tags($ordr_items->fields['description'])), ENT_QUOTES),
                    'description_hide' => $ordr_items->fields['description'],
                    'gl_account' => $ordr_items->fields['gl_account'],
                    'taxable' => $ordr_items->fields['taxable'],
                    'serialize' => $ordr_items->fields['serialize_number'],
                    'proj_id' => $ordr_items->fields['project_id'],
                    'uom' => $ordr_items->fields['uom'],
                    'uom_qty' => $ordr_items->fields['uom_qty'],
                    'date_1' => substr($ordr_items->fields['date_1'], 0, 10),
                    'unit_price' => $currencies->precise($unit_price, true, $currencies_code, $currencies_value),
                    'total' => $currencies->format($total, true, $currencies_code, $currencies_value),
                    'full_price' => $currencies->format($ordr_items->fields['full_price'], true, $currencies_code, $currencies_value),
                    'inactive' => $inv_details->fields['inactive'],
                    'weight' => $inv_details->fields['item_weight'],
                    'stock' => $inv_details->fields['quantity_on_hand'],
                    'lead' => $inv_details->fields['lead_time'],
                );
            }
            $ordr_items->MoveNext();
        }
        $order->fields['disc_percent'] = ($subtotal <> 0) ? 100 * (1 - (($subtotal - $discount) / $subtotal)) : '0';
    }
    // calculate received/sales levels (SO and PO)
    if (JOURNAL_ID == 4 || JOURNAL_ID == 10) {
        $sql = "select i.qty, i.sku, i.so_po_item_ref_id 
		  from " . TABLE_JOURNAL_MAIN . " m left join " . TABLE_JOURNAL_ITEM . " i on m.id = i.ref_id
		  where m.so_po_ref_id = " . $id;
        $posted_items = $db->Execute($sql);
        while (!$posted_items->EOF) {
            for ($i = 0; $i < count($item_list); $i++) {
                if ($item_list[$i]['id'] == $posted_items->fields['so_po_item_ref_id']) {
                    $item_list[$i]['pstd'] += $posted_items->fields['qty'];
                    break;
                }
            }
            $posted_items->MoveNext();
        }
    }
}

// build the form data
if (sizeof($contact->fields) > 0) {
    $xml .= "<BillContact>\n";
    foreach ($contact->fields as $key => $value)
        $xml .= "\t" . xmlEntry($key, $value);
    if ($bill_add->fields)
        while (!$bill_add->EOF) {
            $xml .= "\t<Address>\n";
            foreach ($bill_add->fields as $key => $value)
                $xml .= "\t\t" . xmlEntry($key, $value);
            $xml .= "\t</Address>\n";
            $bill_add->MoveNext();
        }
    $xml .= "</BillContact>\n";
}
if (defined('MODULE_SHIPPING_STATUS') && sizeof($sContact->fields) > 0) { // there was a drop ship address
    $xml .= "<ShipContact>\n";
    foreach ($sContact->fields as $key => $value)
        $xml .= "\t" . xmlEntry($key, $value);
    if ($ship_add->fields)
        while (!$ship_add->EOF) {
            $xml .= "\t<Address>\n";
            foreach ($ship_add->fields as $key => $value)
                $xml .= "\t\t" . xmlEntry($key, $value);
            $xml .= "\t</Address>\n";
            $ship_add->MoveNext();
        }
    $xml .= "</ShipContact>\n";
}
if (sizeof($order->fields) > 0) { // there was an order to open
    $xml .= "<OrderData>\n";
    foreach ($order->fields as $key => $value)
        $xml .= "\t" . xmlEntry($key, strval($value));
    foreach ($item_list as $item) {
        $xml .= "\t<Item>\n";
        foreach ($item as $key => $value)
            $xml .= "\t\t" . xmlEntry($key, strval($value));
        $xml .= "\t</Item>\n";
    }
    $xml .= "</OrderData>\n";
}

if ($debug)
    $xml .= xmlEntry('debug', $debug);
echo createXmlHeader() . $xml . createXmlFooter();
die;
?>