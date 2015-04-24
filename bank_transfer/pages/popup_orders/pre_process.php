<?php
// +-----------------------------------------------------------------+
// |                   bank_transfer Open Source ERP                    |
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
//  Path: /modules/bank_transfer/pages/popup_orders/pre_process.php
//
$security_level = validate_user(0, true);
/**************  include page specific files  *********************/
gen_pull_language('contacts');
require(DIR_FS_WORKING . 'functions/bank_transfer.php');

/**************   page specific initialization  *************************/
define('JOURNAL_ID',(int)$_GET['jID']);

switch (JOURNAL_ID) {
	case  3:	// Purchase Quote Journal
	case  4:	// Purchase Order Journal
		define('GL_TYPE','poo');
		break;
	case  6:	// Purchase Journal
	case  7:	// Vendor Credit Memo Journal
	case 21:	// Point of Purchase Journal
		define('GL_TYPE','por');
		break;
	case  9:	// Sales Quote Journal
	case 10:	// Sales Order Journal
		define('GL_TYPE','soo');
		break;
	case 12:	// Sales/Invoice Journal
	case 13:	// Custoemr Credit Memo Journal
	case 19:	// Point of Sale (receipts)
		define('GL_TYPE','sos');
		break;
	case 18:	// Cash Receipts Journal
		define('GL_TYPE','swr');	// sale with receipt
		break;
	case 20:	// Purchases (direct pay)
		define('GL_TYPE','pwp');	// purchase with payment
		break;
	default:
		die('No valid journal id found (filename: modules/bank_transfer/popup.php), Journal ID needs to be passed to this script to identify the correct procedure.');
}

$acct_period   = ($_GET['search_period']) ? $_GET['search_period'] : $_POST['search_period'];
if (!$acct_period) $acct_period = CURRENT_ACCOUNTING_PERIOD;
$period_filter = ($acct_period == 'all') ? '' : (' and period = ' . $acct_period);
$search_text   = ($_POST['search_text']) ? db_input($_POST['search_text']) : db_input($_GET['search_text']);
if ($search_text == TEXT_SEARCH) $search_text = '';
$action        = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
if (!$action && $search_text <> '') $action = 'search'; // if enter key pressed and search not blank
// load the sort fields
$_GET['sf'] = $_POST['sort_field'] ? $_POST['sort_field'] : $_GET['sf'];
$_GET['so'] = $_POST['sort_order'] ? $_POST['sort_order'] : $_GET['so'];
/***************   hook for custom actions  ***************************/
$custom_path = DIR_FS_WORKING . 'custom/pages/popup_orders/extra_actions.php';
if (file_exists($custom_path)) { include($custom_path); }

/***************   Act on the action request   *************************/
switch ($action) {
  case 'go_first':    $_GET['list'] = 1;     break;
  case 'go_previous': $_GET['list']--;       break;
  case 'go_next':     $_GET['list']++;       break;
  case 'go_last':     $_GET['list'] = 99999; break;
  case 'search':
  case 'search_reset':
  case 'go_page':
  default:
}

/*****************   prepare to display templates  *************************/
// build the list header
$heading_array['post_date'] = TEXT_DATE;
$heading_array['purchase_invoice_id'] = constant('ORD_HEADING_NUMBER_' . JOURNAL_ID);
switch (JOURNAL_ID) {
  case  6:
  case  7: 
	$heading_array['so_po_ref_id']   = ORD_HEADING_NUMBER_4;
	$heading_array['waiting']        = ORD_WAITING;
	break;
  case 12:
  case 13: 
	$heading_array['so_po_ref_id']   = ORD_HEADING_NUMBER_10;
	$heading_array['closed']         = TEXT_PAID;
	break;
  case 19:
	$heading_array['so_po_ref_id']   = ORD_HEADING_NUMBER_10;
	$heading_array['closed']         = TEXT_CLOSED; break;
  default: 
	$heading_array['closed']         = TEXT_CLOSED;
}
$heading_array['bill_primary_name'] = in_array(JOURNAL_ID, array(12,13)) ? ORD_CUSTOMER_NAME : ORD_VENDOR_NAME;
$heading_array['total_amount']      = TEXT_AMOUNT;
$result      = html_heading_bar($heading_array, $_GET['sf'], $_GET['so'], array());
$list_header = $result['html_code'];
$disp_order  = $result['disp_order'];
if ($disp_order == 'post_date') $disp_order .= ', purchase_invoice_id';

// build the list for the page selected
if (isset($search_text) && $search_text <> '') {
  $search_fields = array('bill_primary_name', 'purchase_invoice_id', 'purch_order_id', 'store_id');
  // hook for inserting new search fields to the query criteria.
  if (is_array($extra_search_fields)) $search_fields = array_merge($search_fields, $extra_search_fields);
  $search = ' and (' . implode(' like \'%' . $search_text . '%\' or ', $search_fields) . ' like \'%' . $search_text . '%\')';
} else {
  $search = '';
}

$field_list = array('id', 'journal_id', 'post_date', 'purchase_invoice_id', 'purch_order_id', 'so_po_ref_id', 
  'store_id', 'closed', 'waiting', 'bill_primary_name', 'total_amount', 'currencies_code', 'currencies_value');
		
// hook to add new fields to the query return results
if (is_array($extra_query_list_fields) > 0) $field_list = array_merge($field_list, $extra_query_list_fields);

$query_raw = "select " . implode(', ', $field_list) . " from " . TABLE_JOURNAL_MAIN . " 
  where journal_id = " . JOURNAL_ID . $period_filter . $search . " order by $disp_order";

$query_split  = new splitPageResults($_GET['list'], MAX_DISPLAY_SEARCH_RESULTS, $query_raw, $query_numrows);
$query_result = $db->Execute($query_raw);

$include_header   = false;
$include_footer   = false;
$include_tabs     = false;
$include_calendar = false;
$include_template = 'template_main.php';
define('PAGE_TITLE', GEN_HEADING_PLEASE_SELECT);

?>