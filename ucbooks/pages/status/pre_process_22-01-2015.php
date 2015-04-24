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
//  Path: /modules/ucbooks/pages/status/pre_process.php
//
/* * ************   Check user security   **************************** */


//this is for menu select coding by 4aixz(apu) date:16_04_2013
if ($_GET['page'] == "status" && $_GET['jID'] == "9") {
    $menu_select = 'customers';
    $sub_menu_select = 'sales_quotes_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "10") {
    $menu_select = 'customers';
    $sub_menu_select = 'sales_orders_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "32") {
    $menu_select = 'customers';
    $sub_menu_select = 'delivery_orders_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "12" && (!isset($_GET['sub_jID']) || (isset($_GET['sub_jID']) && $_GET['sub_jID'] == 0))) {
    $menu_select = 'customers';
    $sub_menu_select = 'sales_invoices_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "36" || ($_GET['jID'] == "12" && $_GET['sub_jID'] == '1')) {
    $menu_select = 'customers';
    $sub_menu_select = 'sales_pos';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "13") {
    $menu_select = 'customers';
    $sub_menu_select = 'customer_credit_memos_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "30" || ($_GET['jID'] == "12" && $_GET['sub_jID'] == '2')) {
    $menu_select = 'customers';
    $sub_menu_select = 'customer_dedit_memos_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "31") {
    $menu_select = 'vendors';
    $sub_menu_select = 'vendor_debit_memos_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "3") {
    $menu_select = 'vendors';
    $sub_menu_select = 'request_for_quotes_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "4") {
    $menu_select = 'vendors';
    $sub_menu_select = 'purchase_orders_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "6" && (!isset($_GET['sub_jID']) || (isset($_GET['sub_jID']) && $_GET['sub_jID'] == 0))) {
    $menu_select = 'vendors';
    $sub_menu_select = 'purchase_receive_inventory_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "6" && (isset($_GET['sub_jID']) && $_GET['sub_jID'] == 1)) {
    $menu_select = 'vendors';
    $sub_menu_select = 'expenses';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "7") {
    $menu_select = 'vendors';
    $sub_menu_select = 'vendor_credit_memos_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "31") {
    $menu_select = 'vendors';
    $sub_menu_select = 'vendor_debit_memos_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "18") {
    $menu_select = 'banking';
    $sub_menu_select = 'customer_receipts_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "20") {
    $menu_select = 'banking';
    $sub_menu_select = 'pay_bills_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "2" && empty($_GET['goto'])) {
    $menu_select = 'general_ledger';
    $sub_menu_select = 'general_journal_manager';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "2" && $_GET['goto'] == 'receivedMoney') {
    $menu_select = 'banking';
    $sub_menu_select = 'Received_money';
} else if ($_GET['page'] == "status" && $_GET['jID'] == "2" && $_GET['goto'] == 'spendMoney') {
    $menu_select = 'banking';
    $sub_menu_select = 'Spend_money';
}


gen_pull_language('contacts');

require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
require_once(DIR_FS_WORKING . 'classes/gen_ledger.php');
require_once(DIR_FS_WORKING . 'classes/orders.php');

define('JOURNAL_ID', $_GET['jID']);
define('SUB_JOURNAL_ID', empty($_GET['sub_jID']) ? 0 : $_GET['sub_jID']);
switch (JOURNAL_ID) {
    case 2: $security_token = SECURITY_ID_JOURNAL_ENTRY;
        break;
    case 3: $security_token = SECURITY_ID_PURCHASE_QUOTE;
        break;
    case 4: $security_token = SECURITY_ID_PURCHASE_ORDER;
        break;
    case 6:
        switch (SUB_JOURNAL_ID) {
            case 0 :
                $security_token = SECURITY_ID_PURCHASE_INVENTORY;
                break;
            case 1 :
                $security_token = SECURITY_ID_EXPENSES;
                break;
        }
        break;
    case 7: $security_token = SECURITY_ID_PURCHASE_CREDIT;
        break;
    case 9: $security_token = SECURITY_ID_SALES_QUOTE;
        break;
    case 10: $security_token = SECURITY_ID_SALES_ORDER;
        break;
    case 32: $security_token = SECURITY_ID_DELIVERY__ORDER;
        break;
    case 12: $security_token = SECURITY_ID_SALES_INVOICE;
        break;
    case 36: $security_token = SECURITY_ID_SALES_INVOICE;
        break;
    case 13: $security_token = SECURITY_ID_SALES_CREDIT;
        break;

    case 18: $security_token = SECURITY_ID_CUSTOMER_RECEIPTS;
        break;
    case 20: $security_token = SECURITY_ID_PAY_BILLS;
        break;
    case 30: $security_token = SECURITY_ID_SALES_DEBIT;
        break;
    case 31: $security_token = SECURITY_ID_PURCHASE_DEBIT;
        break;
    default:
        die('Bad or missing journal id found (filename: modules/ucbooks/status.php), Journal_ID needs to be passed to this script to identify the correct procedure.');
}
$security_level = validate_user($security_token);
/* * ************  include page specific files    ******************** */
require(DIR_FS_WORKING . 'defaults.php');
require(DIR_FS_WORKING . 'functions/ucbooks.php');
/* * ************   page specific initialization  ************************ */

// ******************** codeing by shaheen for auto post delivery to sales invoice **********************//
//if ($_GET['page'] == "status" && $_GET['jID'] == "32") {
//    $check_auto = $db->Execute('SELECT configuration_value from configuration where configuration_key = "AUTOMATICALLY_DO_POST"');
//    if ($check_auto->fields['configuration_value'] != 1) {
//        $switch_sql = " SELECT post_date, purchase_invoice_id, bill_primary_name, product_desc, closed, total_amount, currencies_code, currencies_value, short_name, id
//		FROM   v_sales_invoice
//		WHERE journal_id='32' 
//		ORDER BY  post_date";
//        $switch_array = $db->Execute($switch_sql);
//        while (!$switch_array->EOF) {
//            $order = new orders();
//            $posting_date = strtotime($switch_array->fields['post_date']);
//            $current_date = strtotime(date('Y-m-d'));
//            $switch_day = ($current_date - $posting_date) / (60 * 60 * 24);
//            if ($switch_day >= 21) {
//
//                insertdata(1, $order);
//            }
//            $switch_array->MoveNext();
//        }
//    }
//}


if (isset($_GET['action']) && $_GET['action'] == "delete" && $_GET['jID'] == 12) {
    $sql = "DELETE FROM journal_main WHERE id=" . $_GET['oID'];
    $res = mysql_query($sql);
    if ($res) {
        $sql = "DELETE FROM journal_item WHERE ref_id=" . $_GET['oID'];
        $result = mysql_query($sql);
        if ($result) {

            $sql = "select * from invoice_recurring WHERE parent_inv_id =" . $_GET['oID'];
            $check_recurring = mysql_query($sql);
            $check_recurring = mysql_num_rows($check_recurring);

            if ($check_recurring) {
                $sql = "DELETE FROM invoice_recurring WHERE parent_inv_id=" . $_GET['oID'];
                $delete_rinvoice = mysql_query($sql);
                if ($delete_rinvoice) {
                    $sql = "DELETE FROM recurring_to_invoice WHERE invoice_id=" . $_GET['oID'];
                    $delete_rinvoice_res = mysql_query($sql);
                    if ($delete_rinvoice_res) {
                        gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=' . $_GET['jID'] . '&amp;list=1', 'SSL'));
                    }
                }
            } else {
                gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=' . $_GET['jID'] . '&amp;list=1', 'SSL'));
            }
        }
    }
} else if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $sql = "DELETE FROM journal_main WHERE id=" . $_GET['oID'];
    $res = mysql_query($sql);
    if ($res) {
        $sql = "DELETE FROM journal_item WHERE ref_id=" . $_GET['oID'];
        $result = mysql_query($sql);
        if ($result) {
            gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=' . $_GET['jID'] . '&amp;list=1', 'SSL'));
        }
    }
}


// load the sort fields
$_GET['sf'] = $_POST['sort_field'] ? $_POST['sort_field'] : ($_GET['sf'] ? $_GET['sf'] : TEXT_DATE);
$_GET['so'] = $_POST['sort_order'] ? $_POST['sort_order'] : ($_GET['so'] ? $_GET['so'] : 'desc');
$acct_period = $_GET['search_period'] ? $_GET['search_period'] : CURRENT_ACCOUNTING_PERIOD;
$search_text = $_POST['search_text'] ? db_input($_POST['search_text']) : db_input($_GET['search_text']);
if (isset($_POST['search_text']))
    $_GET['search_text'] = $_POST['search_text']; // save the value for get redirects 
if ($search_text == TEXT_SEARCH)
    $search_text = '';
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
if (!$action && $search_text <> '')
    $action = 'search'; // if enter key pressed and search not blank
$date_today = date('Y-m-d');
/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/status/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
/* * *************   Act on the action request   ************************ */
switch ($action) {
    case 'toggle':
        $id = db_prepare_input($_POST['rowSeq']);
        $result = $db->Execute("select waiting from " . TABLE_JOURNAL_MAIN . " where id = '" . $id . "'");
        $toggle = $result->fields['waiting'] ? '0' : '1';
        $db->Execute("update " . TABLE_JOURNAL_MAIN . " set waiting = '" . $toggle . "' where id = '" . $id . "'");
        break;
    case 'dn_attach':
        $oID = db_prepare_input($_POST['rowSeq']);
        if (file_exists(UCBOOKS_DIR_MY_ORDERS . 'order_' . $oID . '.zip')) {
            require_once(DIR_FS_MODULES . 'ucounting/classes/backup.php');
            $backup = new backup();
            $backup->download(UCBOOKS_DIR_MY_ORDERS, 'order_' . $oID . '.zip', true);
        }
        die;
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
    case 'download_inv':
        $oID = db_prepare_input($_GET['oID']);
        require_once(DIR_FS_MODULES . 'opencart/functions/opencart.php');
        require_once(DIR_FS_MODULES . 'opencart/classes/opencart.php');
        $upXML = new opencart();
        $upXML->submitXML($oID, 'download_inv');
        //$action = '';
        break;
    default:
}

/* * ***************   prepare to display templates  ************************ */
// Set up some default lists
$heading_array = array(
    'post_date' => TEXT_DATE,
    'purchase_invoice_id' => constant('ORD_HEADING_NUMBER_' . JOURNAL_ID),
    'bill_primary_name' => in_array(JOURNAL_ID, array(9, 10, 32, 12, 13, 14, 19)) ? ORD_CUSTOMER_NAME : ORD_VENDOR_NAME,
    'purch_order_id' => TEXT_REFERENCE,
    'closed' => TEXT_CLOSED,
    'total_amount' => TEXT_AMOUNT,
);
$extras = array(TEXT_ACTION);
$field_list = array('id', 'journal_id', 'post_date', 'purchase_invoice_id', 'purch_order_id', 'store_id', 'closed', 'waiting',
    'bill_primary_name', 'total_amount', 'currencies_code', 'currencies_value', 'shipper_code');
$search_fields = array('bill_primary_name', 'purchase_invoice_id', 'purch_order_id', 'store_id', 'bill_postal_code', 'ship_primary_name', 'total_amount');

switch (JOURNAL_ID) {
    case 2: // Purchase Quote Journal
        define('POPUP_FORM_TYPE', '');
        $heading_array['bill_primary_name'] = TEXT_DESCRIPTION;
        $page_title = ORD_TEXT_2_WINDOW_TITLE;
        break;
    case 3: // Purchase Quote Journal
        define('POPUP_FORM_TYPE', 'vend:quot');
        $page_title = ORD_TEXT_3_WINDOW_TITLE;
        break;
    case 4: // Purchase Order Journal
        define('POPUP_FORM_TYPE', 'vend:po');
        $page_title = ORD_TEXT_4_WINDOW_TITLE;
        break;
    case 6: // Purchase Journal
        switch (SUB_JOURNAL_ID) {
            case 0 :
                define('POPUP_FORM_TYPE', '');
                $heading_array['closed'] = ORD_WAITING;
                $page_title = ORD_TEXT_6_WINDOW_TITLE;
                break;
            case 1 :
                define('POPUP_FORM_TYPE', '');
                $heading_array['closed'] = ORD_WAITING;
                $page_title = ORD_TEXT_6_1_WINDOW_TITLE;
                break;
        }
        break;
    case 7: // Vendor Credit Memo Journal
        define('POPUP_FORM_TYPE', 'vend:cm');
        $heading_array['closed'] = ORD_WAITING;
        $page_title = ORD_TEXT_7_WINDOW_TITLE;
        break;
    case 9: // Sales Quote Journal
        define('POPUP_FORM_TYPE', 'cust:quot');
        $page_title = ORD_TEXT_9_WINDOW_TITLE;
        break;
    case 10: // Sales Order Journal
        define('POPUP_FORM_TYPE', 'cust:so');
        $page_title = ORD_TEXT_10_WINDOW_TITLE;
        break;
    case 32: // Sales Order Journal
        define('POPUP_FORM_TYPE', 'cust:do');
        $page_title = ORD_TEXT_32_WINDOW_TITLE;
        break;
    case 12: // Invoice Journal
        switch (SUB_JOURNAL_ID) {
            case 0 :
                if (defined('MODULE_SHIPPING_STATUS')) {
                    $shipping_modules = load_all_methods('shipping');
                    $heading_array['shipper_code'] = TEXT_CARRIER;
                }
                define('POPUP_FORM_TYPE', 'cust:inv');
                $heading_array['closed'] = TEXT_PAID;
                $page_title = ORD_TEXT_12_WINDOW_TITLE;
                break;
            case 1 : // For POS
                if (defined('MODULE_SHIPPING_STATUS')) {
                    $shipping_modules = load_all_methods('shipping');
                    $heading_array['shipper_code'] = TEXT_CARRIER;
                }
                define('POPUP_FORM_TYPE', 'cust:inv');
                $heading_array['closed'] = TEXT_PAID;
                $page_title = ORD_TEXT_36_WINDOW_TITLE;
                break;
            case 2 : // Customer Debit Memo Journal
                define('POPUP_FORM_TYPE', 'cust:dm');
                $heading_array['closed'] = TEXT_PAID;
                $page_title = ORD_TEXT_30_WINDOW_TITLE;
                break;
        }
        break;

    case 36: //Before POS Journal
        if (defined('MODULE_SHIPPING_STATUS')) {
            $shipping_modules = load_all_methods('shipping');
            $heading_array['shipper_code'] = TEXT_CARRIER;
        }
        define('POPUP_FORM_TYPE', 'cust:inv');
        $heading_array['closed'] = TEXT_PAID;
        $page_title = ORD_TEXT_36_WINDOW_TITLE;
        break;
    case 13: // Customer Credit Memo Journal
        define('POPUP_FORM_TYPE', 'cust:cm');
        $heading_array['closed'] = TEXT_PAID;
        $page_title = ORD_TEXT_13_WINDOW_TITLE;
        break;
    case 18: // Cash Receipts Journal
        define('POPUP_FORM_TYPE', 'bnk:rcpt');
        $page_title = ORD_TEXT_18_C_WINDOW_TITLE;
        break;
    case 20: // Cash Distribution Journal
        define('POPUP_FORM_TYPE', 'bnk:chk');
        $page_title = ORD_TEXT_20_V_WINDOW_TITLE;
        break;
    case 30: // Customer Debit Memo Journal
        define('POPUP_FORM_TYPE', 'cust:dm');
        $heading_array['closed'] = TEXT_PAID;
        $page_title = ORD_TEXT_30_WINDOW_TITLE;
        break;
    case 31: // Vendor Debit Memo Journal
        define('POPUP_FORM_TYPE', 'vend:dm');
        $heading_array['closed'] = ORD_WAITING;
        $page_title = ORD_TEXT_31_WINDOW_TITLE;
        break;
    case 34: // Receibved Money
        $page_title = 'Received Money';
        break;
    case 35: // Spend money 
        $page_title = 'Spend Money';
        break;
    default:
}


define('PAGE_TITLE', sprintf(BOX_STATUS_MGR, $page_title));

if (isset($_GET['auto_post'])) {
    if ($_GET['auto_post'] == 1) {
        $check_auto = $db->Execute('UPDATE ' . TABLE_CONFIGURATION . ' set configuration_value = 1 where configuration_key = "AUTOMATICALLY_DO_POST"');
    } else {
        $check_auto = $db->Execute('UPDATE ' . TABLE_CONFIGURATION . ' set configuration_value = 0 where configuration_key = "AUTOMATICALLY_DO_POST"');
    }
}

$include_header = true;
$include_footer = true;
$include_template = 'template_main.php';
if (isset($_GET['category'])) {
    $dateArray = explode('-', $_GET['category']);
    $month = $dateArray[0];
    $year = '20'.$dateArray[1];
    $strTime = strtotime($month.'-'.$year);
    $post_date = date('Y-m', $strTime);
    if (isset($_GET['series'])) {
        if ($_GET['series'] == 'Total amount to unpaid Invoice' || $_GET['series'] == 'Total amount to unpaid purchase') {
            $status = 0;
        } else if ($_GET['series'] == 'Total amount to paid Invoice' || $_GET['series'] == 'Total amount to paid purchase') {
            $status = 1;
        }
    }
    $ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_sales_quotes_manager&jID=' . $_GET['jID'] . '&sub_jID=' . SUB_JOURNAL_ID . '&post_date=' . $post_date . '&status=' . $status, 'SSL');
} else {

    $ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_sales_quotes_manager&jID=' . $_GET['jID'] . '&sub_jID=' . SUB_JOURNAL_ID, 'SSL');
}

//$ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_sales_quotes_manager&jID='.$_GET['jID'], 'SSL');

