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
//  Path: /modules/ucpos/pages/pos_mgr/pre_process.php
//
//this is for menu select coding by 4aixz(apu) date:16_04_2013
$menu_select = 'banking';
$sub_menu_select = 'pos_pop_manager';


$security_level = validate_user(SECURITY_ID_POS_MGR);
define('JOURNAL_ID', '19');
/* * ************  include page specific files    ******************** */
require_once(DIR_FS_MODULES . 'ucbooks/classes/gen_ledger.php');
require_once(DIR_FS_WORKING . 'classes/ucpos.php');
/* * ************   page specific initialization  ************************ */
define('POPUP_FORM_TYPE', 'pos:rcpt');
$error = false;
$acct_period = ($_POST['search_period']) ? $_POST['search_period'] : CURRENT_ACCOUNTING_PERIOD;
$search_text = ($_POST['search_text']) ? db_input($_POST['search_text']) : db_input($_GET['search_text']);
if ($search_text == TEXT_SEARCH)
    $search_text = '';
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
if (!$action && $search_text <> '')
    $action = 'search'; // if enter key pressed and search not blank
/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/pos_mgr/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
/* * *************   Act on the action request   ************************ */
switch ($action) {
    case 'delete':
        $id = db_prepare_input($_POST['rowSeq']);
        if ($id) {
            $delOrd = new ucpos();
            $delOrd->journal($id); // load the posted record based on the id submitted
            if ($_SESSION['admin_prefs']['restrict_period'] && $delOrd->period <> CURRENT_ACCOUNTING_PERIOD) {
                $error = $messageStack->add(ORD_ERROR_DEL_NOT_CUR_PERIOD, 'error');
                break;
            }
            // verify no item rows have been acted upon (accounts reconciliation)
            $result = $db->Execute("select closed from " . TABLE_JOURNAL_MAIN . " where id = " . $id);
            if ($result->fields['closed'] == '1')
                $error = $delOrd->fail_message(constant('GENERAL_JOURNAL_' . $delOrd->journal_id . '_ERROR_6'));
            if (!$error) {
                // *************** START TRANSACTION *************************
                $db->transStart();
                if (!$delOrd->unPost('delete')) {
                    $error = $messageStack->add(GL_ERROR_NO_POST, 'error');
                    $db->transRollback();
                    break;
                } else { // delete the payments
                    $payment_modules = load_all_methods('payment');
                    foreach ($delOrd->journal_rows as $value) {
                        if ($value['gl_type'] <> 'ttl')
                            continue;
                        $pmt_fields = explode(':', $value['description']);
                        $pmt_method = $pmt_fields[1]; // payment method
                        $pmt_field_0 = $pmt_fields[2]; // cardholder name/reference
                        $pmt_field_1 = $pmt_fields[3]; // card number
                        $pmt_field_2 = $pmt_fields[4]; // exp month
                        $pmt_field_3 = $pmt_fields[5]; // exp year
                        $pmt_field_4 = $pmt_fields[6]; // cvv2
                        if (method_exists($$pmt_method, 'refund')) {
                            $result = $$pmt_method->refund($value['debit_amount'], $reference, $pmt_field_0, $pmt_field_1);
                        } else {
                            $messageStack->add(sprintf('The payment method (%s) was not refunded with the processor. The refund in the amount of %s needs to be credited with the processor manually.', $pmt_method, $currencies->format_full($value['debit_amount'])), 'caution');
                        }
                    }
                    $db->transCommit();
                }
                // *************** END TRANSACTION *************************
            }
            if (DEBUG)
                $messageStack->write_debug();
            if (!$error) {
                gen_add_audit_log(TEXT_JID_ENTRY, JOURNAL_ID == 19 ? BOX_CUSTOMER_DEPOSITS : BOX_VENDOR_DEPOSITS . ' - ' . TEXT_DELETE, $delOrd->purchase_invoice_id, $delOrd->total_amount);
                gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
            }
        } else {
            $messageStack->add(GL_ERROR_NEVER_POSTED, 'error');
        }
        break;
    case 'go_first': $_GET['list'] = 1;
        break;
    case 'go_previous': $_GET['list']--;
        break;
    case 'go_next': $_GET['list']++;
        break;
    case 'go_last': $_GET['list'] = 99999;
        break;
    case 'search':
    case 'search_reset':
    case 'go_page':
    default:
}
/* * ***************   prepare to display templates  ************************ */
define('PAGE_TITLE', BOX_POS_MGR);
$include_header = true;
$include_footer = true;
$include_template = 'template_main.php';
$ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=ucpos&page=ajax&op=load_pos_pop_manager', 'SSL');





// build the list header
//if (!isset($_GET['list_order']))
//    $_GET['list_order'] = 'post_date-desc'; // default to descending by invoice number
//$heading_array = array(
//    'post_date' => TEXT_DATE,
//    'purchase_invoice_id' => TEXT_INVOICE,
//    'total_amount' => TEXT_AMOUNT,
//    'bill_primary_name' => GEN_PRIMARY_NAME,
//);
//$result = html_heading_bar($heading_array, $_GET['list_order'], array(TEXT_ACTION));
//$list_header = $result['html_code'];
//$disp_order = $result['disp_order'];
//// build the list for the page selected
//$period_filter = ($acct_period == 'all') ? '' : (' and period = ' . $acct_period);
//if (isset($search_text) && gen_not_null($search_text)) {
//    $search_fields = array('bill_primary_name', 'purchase_invoice_id', 'purch_order_id', 'bill_postal_code', 'ship_primary_name', 'total_amount');
//    // hook for inserting new search fields to the query criteria.
//    if (is_array($extra_search_fields))
//        $search_fields = array_merge($search_fields, $extra_search_fields);
//    $search = ' and (' . implode(' like \'%' . $search_text . '%\' or ', $search_fields) . ' like \'%' . $search_text . '%\')';
//} else {
//    $search = '';
//}
//$field_list = array('id', 'post_date', 'shipper_code', 'purchase_invoice_id', 'total_amount', 'bill_primary_name', 'journal_id');
//// hook to add new fields to the query return results
//if (is_array($extra_query_list_fields) > 0)
//    $field_list = array_merge($field_list, $extra_query_list_fields);
//$query_raw = "select " . implode(', ', $field_list) . " from " . TABLE_JOURNAL_MAIN . " 
//		where journal_id in (19,21) " . $period_filter . $search . " order by $disp_order, purchase_invoice_id DESC";
//$query_split = new splitPageResults($_GET['list'], MAX_DISPLAY_SEARCH_RESULTS, $query_raw, $query_numrows);
//$query_result = $db->Execute($query_raw);
//
//$include_header = true;
//$include_footer = true;
//$include_tabs = false;
//$include_calendar = true;
//$include_template = 'template_main.php';
//define('PAGE_TITLE', BOX_POS_MGR);
