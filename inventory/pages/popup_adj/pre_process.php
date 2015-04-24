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
//  Path: /modules/inventory/pages/popup_adj/pre_process.php
//
$security_level = validate_user(0, true);
/**************  include page specific files    *********************/
/**************   page specific initialization  *************************/
$filters = array();
$acct_period   = ($_GET['search_period']) ? $_GET['search_period'] : $_POST['search_period'];
if (!$acct_period) $acct_period = CURRENT_ACCOUNTING_PERIOD;
if ($acct_period <> 'all') $filters[] = 'm.period = ' . $acct_period;
$search_text   = ($_POST['search_text']) ? db_input($_POST['search_text']) : db_input($_GET['search_text']);
if ($search_text == TEXT_SEARCH) $search_text = '';
$action        = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
if (!$action && $search_text <> '') $action = 'search'; // if enter key pressed and search not blank
$adj_type      = isset($_GET['adj_type']) ? $_GET['adj_type'] : 'adj'; // types are xfr or adj
// load the sort fields
$_GET['sf'] = $_POST['sort_field'] ? $_POST['sort_field'] : $_GET['sf'];
$_GET['so'] = $_POST['sort_order'] ? $_POST['sort_order'] : $_GET['so'];
/***************   hook for custom actions  ***************************/
$custom_path = DIR_FS_WORKING . 'custom/pages/popup_adj/module/extra_actions.php';
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
$heading_array = array(
  'm.post_date'         => TEXT_DATE,
  'purchase_invoice_id' => TEXT_REFERENCE,
  'qty'                 => TEXT_QUANTITY,
  'sku'                 => TEXT_SKU,
  'description'         => TEXT_DESCRIPTION,
);
if (ENABLE_MULTI_BRANCH && $adj_type == 'xfr') {
  $extras = array(TEXT_FROM_BRANCH, TEXT_DEST_BRANCH);
} elseif (ENABLE_MULTI_BRANCH) {
  $extras = array(TEXT_BRANCH);
} else {
  $extras = array();
}
$result      = html_heading_bar($heading_array, $_GET['sf'], $_GET['so'], $extras);
$list_header = $result['html_code'];
$disp_order  = $result['disp_order'];
// build the list for the page selected
if (isset($search_text) && $search_text <> '') {
  $search_fields = array('i.sku', 'm.purchase_invoice_id', 'i.debit_amount', 'i.credit_amount', 'i.description', 'i.gl_account');
  // hook for inserting new search fields to the query criteria.
  if (is_array($extra_search_fields)) $search_fields = array_merge($search_fields, $extra_search_fields);
  $filters[] = '(' . implode(' like \'%' . $search_text . '%\' or ', $search_fields) . ' like \'%' . $search_text . '%\')';
}
$field_list = array('m.id', 'm.purchase_invoice_id', 'm.post_date', 'm.store_id', 'm.bill_acct_id', 'sum(i.qty) as qty', 
	'i.sku', 'count(i.sku) as sku_cnt', 'i.description');
// hook to add new fields to the query return results
if (is_array($extra_query_list_fields) > 0) $field_list = array_merge($field_list, $extra_query_list_fields);
$filters[] = 'i.gl_type = \'adj\'';
$filters[] = 'm.journal_id = 16';
$filters[] = ($adj_type == 'xfr') ? 'm.so_po_ref_id > 0' : 'm.so_po_ref_id = 0';
if ($adj_type == 'xfr') $filters[] = 'm.bill_acct_id > 0'; // only pull the first record
$query_raw    = "select distinct " . implode(', ', $field_list) . " 
	from " . TABLE_JOURNAL_MAIN . " m left join " . TABLE_JOURNAL_ITEM . " i on m.id = i.ref_id 
	where " . implode(' and ', $filters) . " group by m.id order by $disp_order, m.id";
$query_split  = new splitPageResults($_GET['list'], MAX_DISPLAY_SEARCH_RESULTS, $query_raw, $query_numrows);
$query_result = $db->Execute($query_raw);

$include_header   = false;
$include_footer   = false;
$include_tabs     = false;
$include_calendar = false;
$include_template = 'template_main.php';
define('PAGE_TITLE', GEN_HEADING_PLEASE_SELECT);
?>