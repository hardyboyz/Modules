<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011 UcSoft, LLC             |
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
//  Path: /modules/ucpos/ajax/save_pos.php
//
$security_level = validate_user(SECURITY_ID_UCPOS);
define('JOURNAL_ID','19');
/**************  include page specific files    *********************/
gen_pull_language('contacts');
gen_pull_language('ucbooks');
gen_pull_language('ucform');
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
require_once(DIR_FS_MODULES . 'ucform/defaults.php');
require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');
require_once(DIR_FS_MODULES . 'ucform/functions/ucform.php');
require_once(DIR_FS_MODULES . 'ucbooks/classes/gen_ledger.php');
require_once(DIR_FS_MODULES . 'ucpos/classes/ucpos.php');
/**************   page specific initialization  *************************/
define('ORD_ACCT_ID',GEN_CUSTOMER_ID);
define('GL_TYPE','sos');
define('DEF_INV_GL_ACCT',AR_DEF_GL_SALES_ACCT);
define('DEF_GL_ACCT',AR_DEFAULT_GL_ACCT);
define('DEF_GL_ACCT_TITLE',ORD_AR_ACCOUNT);
define('POPUP_FORM_TYPE','pos:rcpt');
$error        = false;
$auto_print   = false;
$account_type = 'c';
$post_success = false;
$order        = new ucpos();
$action       = (isset($_GET['action']) ? $_GET['action'] : $_POST['todo']);
$payment_modules = load_all_methods('payment');
/***************   hook for custom actions  ***************************/
$custom_path = DIR_FS_MODULES . 'ucpos/custom/pages/main/extra_actions.php';
if (file_exists($custom_path)) { include($custom_path); }
/***************   Act on the action request   *************************/

	if ($security_level < 2) {
		$error = true ;
	  $error_massage .= ERROR_NO_PERMISSION;
	}
	// load bill to and ship to information
	$order->short_name          = db_prepare_input(($_POST['search'] <> TEXT_SEARCH) ? $_POST['search'] : '');
	$order->bill_add_update     = isset($_POST['bill_add_update']) ? $_POST['bill_add_update'] : 0;
	$order->account_type        = $account_type;
	$order->bill_acct_id        = db_prepare_input($_POST['bill_acct_id']);
	$order->bill_address_id     = db_prepare_input($_POST['bill_address_id']);
	$order->bill_primary_name   = db_prepare_input(($_POST['bill_primary_name']   <> GEN_PRIMARY_NAME)   ? $_POST['bill_primary_name'] : '', true);
	$order->bill_contact        = db_prepare_input(($_POST['bill_contact']        <> GEN_CONTACT)        ? $_POST['bill_contact'] : '', ADDRESS_BOOK_CONTACT_REQUIRED);
	$order->bill_address1       = db_prepare_input(($_POST['bill_address1']       <> GEN_ADDRESS1)       ? $_POST['bill_address1'] : '', ADDRESS_BOOK_ADDRESS1_REQUIRED);
	$order->bill_address2       = db_prepare_input(($_POST['bill_address2']       <> GEN_ADDRESS2)       ? $_POST['bill_address2'] : '', ADDRESS_BOOK_ADDRESS2_REQUIRED);
	$order->bill_city_town      = db_prepare_input(($_POST['bill_city_town']      <> GEN_CITY_TOWN)      ? $_POST['bill_city_town'] : '', ADDRESS_BOOK_CITY_TOWN_REQUIRED);
	$order->bill_state_province = db_prepare_input(($_POST['bill_state_province'] <> GEN_STATE_PROVINCE) ? $_POST['bill_state_province'] : '', ADDRESS_BOOK_STATE_PROVINCE_REQUIRED);
	$order->bill_postal_code    = db_prepare_input(($_POST['bill_postal_code']    <> GEN_POSTAL_CODE)    ? $_POST['bill_postal_code'] : '', ADDRESS_BOOK_POSTAL_CODE_REQUIRED);
	$order->bill_country_code   = db_prepare_input($_POST['bill_country_code']);
	$order->bill_telephone1     = db_prepare_input(($_POST['bill_telephone1']     <> GEN_TELEPHONE1)     ? $_POST['bill_telephone1'] : '', ADDRESS_BOOK_TELEPHONE1_REQUIRED);
	$order->bill_email          = db_prepare_input(($_POST['bill_email'] <> GEN_EMAIL) ? $_POST['bill_email'] : '', ADDRESS_BOOK_EMAIL_REQUIRED);
	// load journal main data
	$order->id                  = ''; // all POS are new
	$order->journal_id          = JOURNAL_ID;
	$order->post_date           = date('Y-m-d');
	$order->period              = CURRENT_ACCOUNTING_PERIOD;
	$order->save_payment        = '1'; // save payment (if encryption enabled)
	$order->purchase_invoice_id = '';  // Assume new POS
	$order->store_id            = db_prepare_input($_POST['store_id']);
	if ($order->store_id == '') $order->store_id = 0;
	$order->description         = MENU_HEADING_UCPOS;
	$order->admin_id            = $_SESSION['admin_id'];
	$order->rep_id              = db_prepare_input($_POST['rep_id']);
	$order->gl_acct_id          = db_prepare_input($_POST['gl_acct_id']);
	$order->item_count          = db_prepare_input($_POST['item_count']);
	// currency values (convert to DEFAULT_CURRENCY to store in db)
	$order->currencies_code     = db_prepare_input($_POST['currencies_code']);
	$order->currencies_value    = db_prepare_input($_POST['currencies_value']);
	$order->subtotal            = $currencies->clean_value(db_prepare_input($_POST['subtotal']),  $order->currencies_code) / $order->currencies_value; // don't need unless for verification
	$order->disc_gl_acct_id     = db_prepare_input($_POST['disc_gl_acct_id']);
	$order->discount            = $currencies->clean_value(db_prepare_input($_POST['discount']),  $order->currencies_code) / $order->currencies_value;
	$order->disc_percent        = ($order->subtotal) ? (1-(($order->subtotal-$order->discount)/$order->subtotal)) : 0;
	$order->sales_tax           = $currencies->clean_value(db_prepare_input($_POST['sales_tax']), $order->currencies_code) / $order->currencies_value;
	$order->total_amount        = $currencies->clean_value(db_prepare_input($_POST['total']),     $order->currencies_code) / $order->currencies_value;
	$order->pmt_recvd           = $currencies->clean_value(db_prepare_input($_POST['pmt_recvd']), $order->currencies_code) / $order->currencies_value;
	$order->bal_due             = $currencies->clean_value(db_prepare_input($_POST['bal_due']),   $order->currencies_code) / $order->currencies_value;
	// load item row data
	$x = 1;
	while (isset($_POST['pstd_' . $x])) { // while there are item rows to read in
	  if (!$_POST['pstd_' . $x]) {
	    $x++;
	    continue;
	  }
	  $full_price = $currencies->clean_value(db_prepare_input($_POST['full_' . $x]), $order->currencies_code) / $order->currencies_value;
	  // Error check some input fields
	  if ($_POST['acct_' . $x] == "") {
	  	$error 					= true;
	  	$error_massage .= GEN_ERRMSG_NO_DATA . TEXT_GL_ACCOUNT;
	  }
	  if (!$error) {
	    $order->item_rows[] = array(
		  'id'        => db_prepare_input($_POST['id_' . $x]),
		  'gl_type'   => GL_TYPE,
		  'pstd'      => $currencies->clean_value(db_prepare_input($_POST['pstd_' . $x]), $order->currencies_code),
		  'sku'       => ($_POST['sku_' . $x] == TEXT_SEARCH) ? '' : db_prepare_input($_POST['sku_' . $x]),
		  'desc'      => db_prepare_input($_POST['desc_' . $x]),
		  'price'     => $currencies->clean_value(db_prepare_input($_POST['price_' . $x]), $order->currencies_code) / $order->currencies_value,
		  'full'      => $full_price,
		  'acct'      => db_prepare_input($_POST['acct_' . $x]),
		  'tax'       => db_prepare_input($_POST['tax_' . $x]),
		  'total'     => $currencies->clean_value(db_prepare_input($_POST['total_' . $x]), $order->currencies_code) / $order->currencies_value,
		  'weight'    => db_prepare_input($_POST['weight_' . $x]),
		  'serial'    => db_prepare_input($_POST['serial_' . $x]),
		  'disc'      => db_prepare_input($_POST['diac_' . $x]),
		  'stock'     => db_prepare_input($_POST['stock_' . $x]),
		  'inactive'  => db_prepare_input($_POST['inactive_' . $x]),
		  'lead_time' => db_prepare_input($_POST['lead_' . $x]),
	    );
	  }
	  $x++;
	}
	// load the payments
	$x   = 1;
	$tot_paid = 0;
	while (isset($_POST['meth_' . $x])) { // while there are item rows to read in
	  if (!$_POST['meth_' . $x]) {
	    $x++;
		continue;
	  }
	  $pmt_meth = $_POST['meth_' . $x];
	  $pmt_amt  = $currencies->clean_value(db_prepare_input($_POST['pmt_' . $x]), $order->currencies_code) / $order->currencies_value;
	  $tot_paid += $pmt_amt;
	  $order->pmt_rows[] = array(
		'meth' => db_prepare_input($_POST['meth_' . $x]),
		'pmt'  => $pmt_amt,
		'f0'   => db_prepare_input($_POST['f0_' . $x]),
		'f1'   => db_prepare_input($_POST['f1_' . $x]),
		'f2'   => db_prepare_input($_POST['f2_' . $x]),
		'f3'   => db_prepare_input($_POST['f3_' . $x]),
		'f4'   => db_prepare_input($_POST['f4_' . $x]),
	  );
	  // initialize payment methods
	  // preset some post variables to fake out the payment methods
	  $_POST[$pmt_meth . '_field_0'] = $_POST['f0_' . $x];
	  $_POST[$pmt_meth . '_field_1'] = $_POST['f1_' . $x];
	  $_POST[$pmt_meth . '_field_2'] = $_POST['f2_' . $x];
	  $_POST[$pmt_meth . '_field_3'] = $_POST['f3_' . $x];
	  $_POST[$pmt_meth . '_field_4'] = $_POST['f4_' . $x];
	  $x++;
	}
	$order->shipper_code = $pmt_meth;  // store last payment method in shipper_code field
	// check for errors (address fields)
	if (UCPOS_REQUIRE_ADDRESS) {
	  if (!$order->bill_acct_id && !$order->bill_add_update) {
			$error 					= true;
			$error_massage .= POS_ERROR_CONTACT_REQUIRED;
	  } else {
	    if ($order->bill_primary_name   === false) $error = $messageStack->add(GEN_ERRMSG_NO_DATA . GEN_PRIMARY_NAME,   'error');
	    if ($order->bill_contact        === false) $error = $messageStack->add(GEN_ERRMSG_NO_DATA . GEN_CONTACT,        'error');
	    if ($order->bill_address1       === false) $error = $messageStack->add(GEN_ERRMSG_NO_DATA . GEN_ADDRESS1,       'error');
	    if ($order->bill_address2       === false) $error = $messageStack->add(GEN_ERRMSG_NO_DATA . GEN_ADDRESS2,       'error');
	    if ($order->bill_city_town      === false) $error = $messageStack->add(GEN_ERRMSG_NO_DATA . GEN_CITY_TOWN,      'error');
	    if ($order->bill_state_province === false) $error = $messageStack->add(GEN_ERRMSG_NO_DATA . GEN_STATE_PROVINCE, 'error');
	    if ($order->bill_postal_code    === false) $error = $messageStack->add(GEN_ERRMSG_NO_DATA . GEN_POSTAL_CODE,    'error');
	  }
	}
	// Item row errors
	if (!$order->item_rows) {
		$error 					= true;
		$error_massage .= GL_ERROR_NO_ITEMS;
	}
	// Payment errors
	if ($tot_paid < $order->total_amount) {
	  $error          = true; 
	  $error_massage .= 'The total payment was not greater than or equal to the order!';
	}
	// End of error checking, process the order
	if (!$error) { // Post the order
	  if ($post_success = $order->post_ordr($action)) {	// Post the order class to the db
		gen_add_audit_log(MENU_HEADING_UCPOS . ' - ' . ($_POST['id'] ? TEXT_EDIT : TEXT_ADD), $order->purchase_invoice_id, $order->total_amount);
	  } else { // reset the id because the post failed (ID could have been set inside of Post)
		$error = true;
		$order->purchase_invoice_id = '';	// reset order num to submitted value (may have been set if payment failed)
		$order->id = ''; // will be null unless opening an existing purchase/receive
	  }
	} else { // there was a post error, reset id and re-display form
	  $error_massage .= GL_ERROR_NO_POST;
	}
	
	//print
    if (!$error && defined('UCPOS_RECEIPT_PRINTER_NAME') && UCPOS_RECEIPT_PRINTER_NAME <> '') {
	  $result = $db->Execute("select id from " . TABLE_UCFORM . " 
		where doc_group = '" . POPUP_FORM_TYPE . "' and doc_ext = 'frm'");
	  if ($result->RecordCount() > 1) {
	    $error_massage .= 'More than one form was found for this type ('.POPUP_FORM_TYPE.'). Using the first form found.';
	  }
	  $rID    = $result->fields['id']; // only one form available, use it
	  $report = get_report_details($rID);
	  $title  = $report->title;
	  $report->datedefault = 'a';
	  $report->xfilterlist[0]->fieldname = 'journal_main.id';
	  $report->xfilterlist[0]->default   = 'EQUAL';
	  $report->xfilterlist[0]->min_val   = $order->id;
	  $output = BuildForm($report, $delivery_method = 'S'); // force return with report
	  if ($output === true) {
		$error = true;
	  } else { // fetch the receipt and prepare to print
	  	$receipt_data = str_replace("\r", "", addslashes($output)); // for javascript multi-line
	  	foreach (explode("\n",$receipt_data) as $value){
	  		$xml .= "<receipt_data>\n";
        $xml .= "\t" . xmlEntry("line", $value);
	    	$xml .= "</receipt_data>\n";
	  	}
	  }
	}
$xml .= "\t" . xmlEntry("action",$action);
if (!$error)			 $xml .= "\t" . xmlEntry("order_id", $order->id);
if ($error)  			 $xml .= "\t" . xmlEntry("error", $error);
if ($error_massage)  	 $xml .= "\t" . xmlEntry("error_massage", $error_massage);
echo createXmlHeader() . $xml . createXmlFooter();
die;	
?>