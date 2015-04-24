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
//  Path: /modules/ucbooks/pages/orders/pre_process.php
//
/* * ************   Check user security   **************************** */


//this is for menu select coding by 4aixz(apu) date:16_04_2013
if ($_GET['page'] == "orders" && $_GET['jID'] == "9") {
    $menu_select = 'customers';
    $sub_menu_select = 'sales_quotes_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "10") {
    $menu_select = 'customers';
    $sub_menu_select = 'sales_orders_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "32") {
    $menu_select = 'customers';
    $sub_menu_select = 'delivery_orders_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "12" && (!isset($_GET['sub_jID']) || (isset($_GET['sub_jID']) && $_GET['sub_jID'] == 0))) {
    $menu_select = 'customers';
    $sub_menu_select = 'sales_invoices_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "36" || ($_GET['jID'] == "12" && $_GET['sub_jID'] == '1')) {
    $menu_select = 'customers';
    $sub_menu_select = 'sales_pos';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "13") {
    $menu_select = 'customers';
    $sub_menu_select = 'customer_credit_memos_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "3") {
    $menu_select = 'vendors';
    $sub_menu_select = 'request_for_quotes_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "4") {
    $menu_select = 'vendors';
    $sub_menu_select = 'purchase_orders_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "6" && (!isset($_GET['sub_jID']) || (isset($_GET['sub_jID']) && $_GET['sub_jID'] == 0))) {
    $menu_select = 'vendors';
    $sub_menu_select = 'purchase_receive_inventory_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "6" && (isset($_GET['sub_jID']) && $_GET['sub_jID'] == 1)) {
    $menu_select = 'vendors';
    $sub_menu_select = 'expenses';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "7") {
    $menu_select = 'vendors';
    $sub_menu_select = 'vendor_credit_memos_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "30" || ($_GET['jID'] == "12" && $_GET['sub_jID'] == '2')) {
    $menu_select = 'customers';
    $sub_menu_select = 'customer_dedit_memos_manager';
} else if ($_GET['page'] == "orders" && $_GET['jID'] == "31") {
    $menu_select = 'vendors';
    $sub_menu_select = 'vendor_debit_memos_manager';
}



define('JOURNAL_ID', $_GET['jID']);
define('SUB_JOURNAL_ID', empty($_GET['sub_jID']) ? 0 : $_GET['sub_jID']);

switch (JOURNAL_ID) {
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
    case 32: $security_token = SECURITY_ID_DELIVERY_ORDER;
        break;
    case 12:
        switch (SUB_JOURNAL_ID) {
            case 0 :
                $security_token = SECURITY_ID_SALES_INVOICE;
                break;
            case 1 :
                $security_token = SECURITY_ID_SALES_INVOICE;
                break;
            case 2 :
                $security_token = SECURITY_ID_SALES_DEBIT;
                break;
        }
        break;
    case 36: $security_token = SECURITY_ID_SALES_INVOICE;
        break;
    case 13: $security_token = SECURITY_ID_SALES_CREDIT;
        break;
    case 30: $security_token = SECURITY_ID_SALES_DEBIT;
        break;
    case 31: $security_token = SECURITY_ID_PURCHASE_DEBIT;
        break;
    default:
        die('No valid journal id found (filename: modules/orders.php), Journal ID needs to be passed to this script to identify the action required.');
}
$security_level = validate_user($security_token);
/* * ************  include page specific files    ******************** */
gen_pull_language('contacts');

require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
require_once(DIR_FS_WORKING . 'functions/ucbooks.php');
require_once(DIR_FS_WORKING . 'classes/gen_ledger.php');
require_once(DIR_FS_WORKING . 'classes/orders.php');
if (defined('MODULE_SHIPPING_STATUS')) {
    require_once(DIR_FS_MODULES . 'shipping/functions/shipping.php');
    require_once(DIR_FS_MODULES . 'shipping/defaults.php');
}
/* * ************   page specific initialization  ************************ */
switch (JOURNAL_ID) {
    case 3:  // Vendor Quote Journal
        define('ORD_ACCT_ID', GEN_VENDOR_ID);
        define('GL_TYPE', 'poo');    // code to use for journal rows
        define('DEF_INV_GL_ACCT', AP_DEFAULT_INVENTORY_ACCOUNT); // default account to use for item rows
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ap_acct'] ? $_SESSION['admin_prefs']['def_ap_acct'] : AP_DEFAULT_PURCHASE_ACCOUNT);
        define('DEF_GL_ACCT_TITLE', ORD_AP_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_QUANTITY);
        define('TEXT_COLUMN_2_TITLE', TEXT_RECEIVED);
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_CLOSE);
        $item_col_1_enable = true;    // allow/disallow entry of item columns
        $item_col_2_enable = false;
        define('POPUP_FORM_TYPE', 'vend:quot'); // form type to use for printing
        $account_type = 'v';     // choices are v - vendor or c - customer
        break;
    case 4:  // Purchase Order Journal
        define('ORD_ACCT_ID', GEN_VENDOR_ID);
        define('GL_TYPE', 'poo');    // code to use for journal rows
        define('DEF_INV_GL_ACCT', AP_DEFAULT_INVENTORY_ACCOUNT); // default account to use for item rows
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ap_acct'] ? $_SESSION['admin_prefs']['def_ap_acct'] : AP_DEFAULT_PURCHASE_ACCOUNT);
        define('DEF_GL_ACCT_TITLE', ORD_AP_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_QUANTITY);
        define('TEXT_COLUMN_2_TITLE', TEXT_RECEIVED);
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_CLOSE);
        $item_col_1_enable = true;    // allow/disallow entry of item columns
        $item_col_2_enable = false;
        define('POPUP_FORM_TYPE', 'vend:po');  // form type to use for printing
        $account_type = 'v';     // choices are v - vendor or c - customer
        break;
    case 6:  // Purchase Journal (accounts payable - pay later)
        define('ORD_ACCT_ID', GEN_VENDOR_ID);
        define('GL_TYPE', 'por');
        define('DEF_INV_GL_ACCT', AP_DEFAULT_INVENTORY_ACCOUNT);
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ap_acct'] ? $_SESSION['admin_prefs']['def_ap_acct'] : AP_DEFAULT_PURCHASE_ACCOUNT);
        define('DEF_GL_ACCT_TITLE', ORD_AP_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_PO_BAL);
        define('TEXT_COLUMN_2_TITLE', TEXT_RECEIVED);
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_INVOICE_PAID);
        $item_col_1_enable = false;
        $item_col_2_enable = true;
        define('POPUP_FORM_TYPE', '');
        $account_type = 'v';
        break;
    case 7:  // Vendor Credit Memo Journal (unpaid invoice returned product to vendor)
        define('ORD_ACCT_ID', GEN_VENDOR_ID);
        define('GL_TYPE', 'por');
        define('DEF_INV_GL_ACCT', AP_DEFAULT_INVENTORY_ACCOUNT);
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ap_acct'] ? $_SESSION['admin_prefs']['def_ap_acct'] : AP_DEFAULT_PURCHASE_ACCOUNT);
        define('DEF_GL_ACCT_TITLE', ORD_AP_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_RECEIVED);
        define('TEXT_COLUMN_2_TITLE', TEXT_RETURNED);
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_CREDIT_TAKEN);
        $item_col_1_enable = false;
        $item_col_2_enable = true;
        define('POPUP_FORM_TYPE', 'vend:cm');
        $account_type = 'v';
        break;
    case 9:  // Customer Quote Journal
        define('ORD_ACCT_ID', GEN_CUSTOMER_ID);
        define('GL_TYPE', 'soo');    // code to use for journal rows
        define('DEF_INV_GL_ACCT', AR_DEF_GL_SALES_ACCT); // default account to use for item rows
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ar_acct'] ? $_SESSION['admin_prefs']['def_ar_acct'] : AR_DEFAULT_GL_ACCT);
        define('DEF_GL_ACCT_TITLE', ORD_AR_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_QUANTITY);
        //define('TEXT_COLUMN_2_TITLE', TEXT_INVOICED);
        define('TEXT_COLUMN_2_TITLE', "Posted");
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_CLOSE);
        $item_col_1_enable = true;    // allow/disallow entry of item columns
        $item_col_2_enable = false;
        define('POPUP_FORM_TYPE', 'cust:quot'); // form type to use for printing
        $account_type = 'c';     // choices are v - vendor or c - customer
        break;
    case 10: // Sales Order Journal
        define('ORD_ACCT_ID', GEN_CUSTOMER_ID);
        define('GL_TYPE', 'soo');
        define('DEF_INV_GL_ACCT', AR_DEF_GL_SALES_ACCT);
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ar_acct'] ? $_SESSION['admin_prefs']['def_ar_acct'] : AR_DEFAULT_GL_ACCT);
        define('DEF_GL_ACCT_TITLE', ORD_AR_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_QUANTITY);
        //define('TEXT_COLUMN_2_TITLE', TEXT_INVOICED);
        define('TEXT_COLUMN_2_TITLE', "Posted");
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_CLOSE);
        $item_col_1_enable = true;
        $item_col_2_enable = false;
        define('POPUP_FORM_TYPE', 'cust:so');
        $account_type = 'c';
        break;
    case 32: // Delivery Order Journal
        define('ORD_ACCT_ID', GEN_CUSTOMER_ID);
        define('GL_TYPE', 'doo');
        define('DEF_INV_GL_ACCT', AR_DEF_GL_SALES_ACCT);
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ar_acct'] ? $_SESSION['admin_prefs']['def_ar_acct'] : AR_DEFAULT_GL_ACCT);
        define('DEF_GL_ACCT_TITLE', ORD_AR_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_QUANTITY);
        //define('TEXT_COLUMN_2_TITLE', TEXT_INVOICED);
        define('TEXT_COLUMN_2_TITLE', "Posted");
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_CLOSE);
        $item_col_1_enable = true;
        $item_col_2_enable = false;
        define('POPUP_FORM_TYPE', 'cust:do');
        $account_type = 'c';
        break;
    case 12:
        switch (SUB_JOURNAL_ID) {
            case '0': // Customer Invoice Journal (unpaid invoice returned product from customer)
            case '1':// Customer POS Journal (unpaid invoice returned product from customer)
                define('ORD_ACCT_ID', GEN_CUSTOMER_ID);
                define('GL_TYPE', 'sos');
                define('DEF_INV_GL_ACCT', AR_DEF_GL_SALES_ACCT);
                define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ar_acct'] ? $_SESSION['admin_prefs']['def_ar_acct'] : AR_DEFAULT_GL_ACCT);
                define('DEF_GL_ACCT_TITLE', ORD_AR_ACCOUNT);
                define('TEXT_COLUMN_1_TITLE', TEXT_SO_BAL);
                define('TEXT_COLUMN_2_TITLE', TEXT_QUANTITY);
                define('TEXT_ORDER_CLOSED_FIELD', TEXT_PAID_IN_FULL);
                $item_col_1_enable = false;
                $item_col_2_enable = true;
                define('POPUP_FORM_TYPE', 'cust:inv');
                $account_type = 'c';
                break;
            case '2' : // Customer Debit Memo Journal (unpaid invoice returned product from customer)
                define('ORD_ACCT_ID', GEN_CUSTOMER_ID);
                define('GL_TYPE', 'sos');
                define('DEF_INV_GL_ACCT', AR_DEF_GL_SALES_ACCT);
                define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ar_acct'] ? $_SESSION['admin_prefs']['def_ar_acct'] : AR_DEFAULT_GL_ACCT);
                define('DEF_GL_ACCT_TITLE', ORD_AR_ACCOUNT);
                define('TEXT_COLUMN_1_TITLE', TEXT_SHIPPED);
                define('TEXT_COLUMN_2_TITLE', TEXT_QUANTITY);
                define('TEXT_ORDER_CLOSED_FIELD', TEXT_DEBIT_PAID);
                $item_col_1_enable = false;
                $item_col_2_enable = true;
                define('POPUP_FORM_TYPE', 'cust:cm');    // We need to change cust:cm to cust:dm later when we make this functional for the set up process.
                $account_type = 'c';
                break;
        }
        break;
    case 36:
        define('ORD_ACCT_ID', GEN_CUSTOMER_ID);
        define('GL_TYPE', 'sos');
        define('DEF_INV_GL_ACCT', AR_DEF_GL_SALES_ACCT);
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ar_acct'] ? $_SESSION['admin_prefs']['def_ar_acct'] : AR_DEFAULT_GL_ACCT);
        define('DEF_GL_ACCT_TITLE', ORD_AR_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_SO_BAL);
        define('TEXT_COLUMN_2_TITLE', TEXT_QUANTITY);
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_PAID_IN_FULL);
        $item_col_1_enable = false;
        $item_col_2_enable = true;
        define('POPUP_FORM_TYPE', 'cust:inv');
        $account_type = 'c';
        break;
    case 13: // Customer Credit Memo Journal (unpaid invoice returned product from customer)
        define('ORD_ACCT_ID', GEN_CUSTOMER_ID);
        define('GL_TYPE', 'sos');
        define('DEF_INV_GL_ACCT', AR_DEF_GL_SALES_ACCT);
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ar_acct'] ? $_SESSION['admin_prefs']['def_ar_acct'] : AR_DEFAULT_GL_ACCT);
        define('DEF_GL_ACCT_TITLE', ORD_AR_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_SHIPPED);
        define('TEXT_COLUMN_2_TITLE', TEXT_RETURNED);
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_CREDIT_PAID);
        $item_col_1_enable = false;
        $item_col_2_enable = true;
        define('POPUP_FORM_TYPE', 'cust:cm');
        $account_type = 'c';
        break;
    case 30: // Customer Debit Memo Journal (unpaid invoice returned product from customer)
        define('ORD_ACCT_ID', GEN_CUSTOMER_ID);
        define('GL_TYPE', 'sos');
        define('DEF_INV_GL_ACCT', AR_DEF_GL_SALES_ACCT);
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ar_acct'] ? $_SESSION['admin_prefs']['def_ar_acct'] : AR_DEFAULT_GL_ACCT);
        define('DEF_GL_ACCT_TITLE', ORD_AR_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_SHIPPED);
        define('TEXT_COLUMN_2_TITLE', TEXT_QUANTITY);
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_DEBIT_PAID);
        $item_col_1_enable = false;
        $item_col_2_enable = true;
        define('POPUP_FORM_TYPE', 'cust:cm');    // We need to change cust:cm to cust:dm later when we make this functional for the set up process.
        $account_type = 'c';
        break;
    case 31: // Vendor Debit Memo Journal (unpaid invoice returned product to vendor)
        define('ORD_ACCT_ID', GEN_VENDOR_ID);
        define('GL_TYPE', 'por');
        define('DEF_INV_GL_ACCT', AP_DEFAULT_INVENTORY_ACCOUNT);
        define('DEF_GL_ACCT', $_SESSION['admin_prefs']['def_ap_acct'] ? $_SESSION['admin_prefs']['def_ap_acct'] : AP_DEFAULT_PURCHASE_ACCOUNT);
        define('DEF_GL_ACCT_TITLE', ORD_AP_ACCOUNT);
        define('TEXT_COLUMN_1_TITLE', TEXT_RECEIVED);
        define('TEXT_COLUMN_2_TITLE', TEXT_QUANTITY);
        define('TEXT_ORDER_CLOSED_FIELD', TEXT_DEBIT_TAKEN);
        $item_col_1_enable = false;
        $item_col_2_enable = true;
        define('POPUP_FORM_TYPE', 'vend:cm');     // We need to change vend:cm to vend:dm later when we make this functional for the set up process.
        $account_type = 'v';
        break;
    default:
}

$error = false;
$post_success = false;
$order = new orders();
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/orders/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
/* * *************   Act on the action request   ************************ */
switch ($action) {
    case 'save':
    case 'email':
    case 'print':
    case 'payment':
    case 'post_previous':
    case 'post_next':

        validate_security($security_level, 2);
        if (!isset($_POST['total'])) { // check for truncated post vars
            $messageStack->add('The total field was not set, this means the form was not submitted in full and the order cannot be posted properly. The most common solution to this problem is to set the max_input_vars above the standard 1000 in your php.ini configuration file.', 'error');
            break;
        }
// currency values (convert to DEFAULT_CURRENCY to store in db)
        $order->currencies_code = db_prepare_input($_POST['currencies_code']);
        $order->currencies_value = db_prepare_input($_POST['currencies_value']);
// load bill to and ship to information
        $order->short_name = db_prepare_input(($_POST['search'] <> TEXT_SEARCH) ? $_POST['search'] : '');
        $order->bill_add_update = isset($_POST['bill_add_update']) ? $_POST['bill_add_update'] : 0;
        $order->account_type = $account_type;
        $order->bill_acct_id = db_prepare_input($_POST['bill_acct_id']);
        $order->bill_address_id = db_prepare_input($_POST['bill_address_id']);
        $order->bill_primary_name = db_prepare_input(($_POST['bill_primary_name'] <> GEN_PRIMARY_NAME) ? $_POST['bill_primary_name'] : '', true);
        $order->bill_contact = db_prepare_input(($_POST['bill_contact'] <> GEN_CONTACT) ? $_POST['bill_contact'] : '', ADDRESS_BOOK_CONTACT_REQUIRED);
        $order->bill_address1 = db_prepare_input(($_POST['bill_address1'] <> GEN_ADDRESS1) ? $_POST['bill_address1'] : '', ADDRESS_BOOK_ADDRESS1_REQUIRED);
        $order->bill_address2 = db_prepare_input(($_POST['bill_address2'] <> GEN_ADDRESS2) ? $_POST['bill_address2'] : '', ADDRESS_BOOK_ADDRESS2_REQUIRED);
        $order->bill_city_town = db_prepare_input(($_POST['bill_city_town'] <> GEN_CITY_TOWN) ? $_POST['bill_city_town'] : '', ADDRESS_BOOK_CITY_TOWN_REQUIRED);
        $order->bill_state_province = db_prepare_input(($_POST['bill_state_province'] <> GEN_STATE_PROVINCE) ? $_POST['bill_state_province'] : '', ADDRESS_BOOK_STATE_PROVINCE_REQUIRED);
        $order->bill_postal_code = db_prepare_input(($_POST['bill_postal_code'] <> GEN_POSTAL_CODE) ? $_POST['bill_postal_code'] : '', ADDRESS_BOOK_POSTAL_CODE_REQUIRED);
        $order->bill_country_code = db_prepare_input($_POST['bill_country_code']);
        $order->bill_telephone1 = db_prepare_input(($_POST['bill_telephone1'] <> GEN_TELEPHONE1) ? $_POST['bill_telephone1'] : '', ADDRESS_BOOK_TELEPHONE1_REQUIRED);
        $order->bill_email = db_prepare_input(($_POST['bill_email'] <> GEN_EMAIL) ? $_POST['bill_email'] : '', ADDRESS_BOOK_EMAIL_REQUIRED);
        $order->message = db_prepare_input($_POST['message']);
        $order->product_desc = db_prepare_input($_POST['product_desc']);
        $order->note = db_prepare_input($_POST['note']);
        if (defined('MODULE_SHIPPING_STATUS')) {
            $order->ship_short_name = db_prepare_input($_POST['ship_search']);
            $order->ship_add_update = isset($_POST['ship_add_update']) ? $_POST['ship_add_update'] : 0;
            $order->ship_acct_id = db_prepare_input($_POST['ship_acct_id']);
            $order->ship_address_id = db_prepare_input($_POST['ship_address_id']);
            $order->ship_primary_name = db_prepare_input(($_POST['ship_primary_name'] <> GEN_PRIMARY_NAME) ? $_POST['ship_primary_name'] : '', true);
            $order->ship_contact = db_prepare_input(($_POST['ship_contact'] <> GEN_CONTACT) ? $_POST['ship_contact'] : '', ADDRESS_BOOK_SHIP_CONTACT_REQ);
            $order->ship_address1 = db_prepare_input(($_POST['ship_address1'] <> GEN_ADDRESS1) ? $_POST['ship_address1'] : '', ADDRESS_BOOK_SHIP_ADD1_REQ);
            $order->ship_address2 = db_prepare_input(($_POST['ship_address2'] <> GEN_ADDRESS2) ? $_POST['ship_address2'] : '', ADDRESS_BOOK_SHIP_ADD2_REQ);
            $order->ship_city_town = db_prepare_input(($_POST['ship_city_town'] <> GEN_CITY_TOWN) ? $_POST['ship_city_town'] : '', ADDRESS_BOOK_SHIP_CITY_REQ);
            $order->ship_state_province = db_prepare_input(($_POST['ship_state_province'] <> GEN_STATE_PROVINCE) ? $_POST['ship_state_province'] : '', ADDRESS_BOOK_SHIP_STATE_REQ);
            $order->ship_postal_code = db_prepare_input(($_POST['ship_postal_code'] <> GEN_POSTAL_CODE) ? $_POST['ship_postal_code'] : '', ADDRESS_BOOK_SHIP_POSTAL_CODE_REQ);
            $order->ship_country_code = db_prepare_input($_POST['ship_country_code']);
            $order->ship_telephone1 = db_prepare_input(($_POST['ship_telephone1'] <> GEN_TELEPHONE1) ? $_POST['ship_telephone1'] : '', ADDRESS_BOOK_TELEPHONE1_REQUIRED);
            $order->ship_email = db_prepare_input(($_POST['ship_email'] <> GEN_EMAIL) ? $_POST['ship_email'] : '', ADDRESS_BOOK_EMAIL_REQUIRED);
            $order->shipper_code = implode(':', array(db_prepare_input($_POST['ship_carrier']), db_prepare_input($_POST['ship_service'])));
            $order->drop_ship = isset($_POST['drop_ship']) ? $_POST['drop_ship'] : 0;
            $order->freight = $currencies->clean_value(db_prepare_input($_POST['freight']), $order->currencies_code) / $order->currencies_value;
        }
// load journal main data
        $order->id = ($_POST['id'] <> '') ? $_POST['id'] : ''; // will be null unless opening an existing purchase/receive
        $order->journal_id = JOURNAL_ID;
        $order->sub_journal_id = SUB_JOURNAL_ID;
        $order->post_date = gen_db_date($_POST['post_date']);
        $order->period = gen_calculate_period($order->post_date);
        if (!$order->period)
            break; // bad post_date was submitted
        if ($_SESSION['admin_prefs']['restrict_period'] && $order->period <> CURRENT_ACCOUNTING_PERIOD) {
            $error = $messageStack->add(ORD_ERROR_NOT_CUR_PERIOD, 'error');
            break;
        }
        $order->so_po_ref_id = db_prepare_input($_POST['so_po_ref_id']); // Internal link to reference po/so record
        $order->purchase_invoice_id = db_prepare_input($_POST['purchase_invoice_id']); // UcBooks order/invoice ID



        $order->purch_order_id = db_prepare_input($_POST['purch_order_id']);  // customer PO/Ref number
        $order->store_id = db_prepare_input($_POST['store_id']);
        if ($order->store_id == '')
            $order->store_id = 0;
        if (isset($_POST['discount_check']) && $_POST['discount_check'] == 1) {
            if (JOURNAL_ID == 13) {
                $order->description = 'Customer Credit Memos Entry Discount';
            } else if (JOURNAL_ID == 7) {
                $order->description = 'Vendor Credit Memos Entry Discount';
            } else if (JOURNAL_ID == 30) {
                $order->description = 'Customer Debit Memos Entry Discount';
            } else if (JOURNAL_ID == 31) {
                $order->description = 'Vendor Debit Memos Entry Discount';
            }
        } else {
            $order->description = sprintf(TEXT_JID_ENTRY, constant('ORD_TEXT_' . JOURNAL_ID . '_WINDOW_TITLE'));
        }

        $order->recur_id = db_prepare_input($_POST['recur_id']);
        $order->recur_frequency = db_prepare_input($_POST['recur_frequency']);
//	$order->sales_tax_auths     = db_prepare_input($_POST['sales_tax_auths']);
        $order->admin_id = $_SESSION['admin_id'];
        $order->rep_id = db_prepare_input($_POST['rep_id']);
        $order->gl_acct_id = db_prepare_input($_POST['gl_acct_id']);
        $order->terms = db_prepare_input($_POST['terms']);
        $order->waiting = (JOURNAL_ID == 6 || JOURNAL_ID == 7) ? (isset($_POST['waiting']) ? 1 : 0) : ($_POST['waiting'] ? 1 : 0);
        $order->closed = ($_POST['closed'] == "1") ? 1 : 0;
        $order->terminal_date = gen_db_date($_POST['terminal_date']);
        $order->item_count = db_prepare_input($_POST['item_count']);
        $order->weight = db_prepare_input($_POST['weight']);
        $order->printed = db_prepare_input($_POST['printed']);
        $order->subtotal = $currencies->clean_value(db_prepare_input($_POST['subtotal']), $order->currencies_code) / $order->currencies_value; // don't need unless for verification
        $order->disc_gl_acct_id = db_prepare_input($_POST['disc_gl_acct_id']);
        $order->discount = $currencies->clean_value(db_prepare_input($_POST['discount']), $order->currencies_code) / $order->currencies_value;
        $order->disc_percent = ($order->subtotal) ? (1 - (($order->subtotal - $order->discount) / $order->subtotal)) : 0;
        $order->ship_gl_acct_id = db_prepare_input($_POST['ship_gl_acct_id']);
        $order->rm_attach = isset($_POST['rm_attach']) ? true : false;
        $order->sales_tax = $currencies->clean_value(db_prepare_input($_POST['sales_tax']), $order->currencies_code) / $order->currencies_value;
        $order->total_amount = $currencies->clean_value(db_prepare_input($_POST['total']), $order->currencies_code) / $order->currencies_value;
        $order->message = db_prepare_input($_POST['message']);
        $order->product_desc = db_prepare_input($_POST['product_desc']);
        $order->note = db_prepare_input($_POST['note']);
        if (($_GET['jID'] == 7 || $_GET['jID'] == 13 || $_GET['jID'] == 30 || $_GET['jID'] == 31 ) && (isset($_POST['is_discount']) && $_POST['is_discount'] == 1)) {//we need to make item qty 0 for credait memo discount we assign distinct value
            $order->is_discount = 1;
        }
// load item row data
        $x = 1;
        $so_po_ref_id_arr = array();
        while (isset($_POST['qty_' . $x])) { // while there are item rows to read in
            if (!$_POST['qty_' . $x] && !$_POST['pstd_' . $x]) {
                $x++;
                continue; // skip item line
            }
            $so_po_ref_id = 0;

// Error check some input fields
//if ($_POST['pstd_' . $x] == "") $error = $messageStack->add(GEN_ERRMSG_NO_DATA . "Qty", 'error');	  
            if ($_POST['acct_' . $x] == "")
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . TEXT_GL_ACCOUNT, 'error');
//if ($_POST['price_' . $x] == "") $error = $messageStack->add(GEN_ERRMSG_NO_DATA . "Price", 'error'); //need to fix bugs.
            $order->item_rows[] = array(
                'id' => db_prepare_input($_POST['id_' . $x]),
                'so_po_item_ref_id' => db_prepare_input($_POST['so_po_item_ref_id_' . $x]),
                'item_cnt' => db_prepare_input($_POST['item_cnt_' . $x]),
                'gl_type' => GL_TYPE,
                'qty' => $currencies->clean_value(db_prepare_input($_POST['qty_' . $x]), $order->currencies_code),
                'uom' => db_prepare_input($_POST['uom_' . $x]),
                'uom_qty' => $currencies->clean_value(db_prepare_input($_POST['uom_qty_' . $x]), $order->currencies_code),
                'pstd' => $currencies->clean_value(db_prepare_input($_POST['pstd_' . $x]), $order->currencies_code),
                'sku' => ($_POST['sku_' . $x] == TEXT_SEARCH) ? '' : db_prepare_input($_POST['sku_' . $x]),
                //'desc' => db_prepare_input($_POST['desc_' . $x]),
                'desc' => db_prepare_input($_POST['descHidden_' . $x]),
                'proj' => db_prepare_input($_POST['proj_' . $x]),
                'date_1' => db_prepare_input($_POST['date_1_' . $x]),
                'price' => $currencies->clean_value(db_prepare_input($_POST['price_' . $x]), $order->currencies_code) / $order->currencies_value,
                'full' => $currencies->clean_value(db_prepare_input($_POST['full_' . $x]), $order->currencies_code) / $order->currencies_value,
                'acct' => db_prepare_input($_POST['acct_' . $x]),
                'tax' => db_prepare_input($_POST['tax_' . $x]),
                'total' => $currencies->clean_value(db_prepare_input($_POST['total_' . $x]), $order->currencies_code) / $order->currencies_value,
                'weight' => db_prepare_input($_POST['weight_' . $x]),
                'serial' => db_prepare_input($_POST['serial_' . $x]),
                'stock' => db_prepare_input($_POST['stock_' . $x]),
                'inactive' => db_prepare_input($_POST['inactive_' . $x]),
                'lead_time' => db_prepare_input($_POST['lead_' . $x]),
            );
            $so_po_ref_id = (db_prepare_input($_POST['so_po_item_ref_id_' . $x]) == '') ? $so_po_ref_id : db_prepare_input($_POST['so_po_item_ref_id_' . $x]);
            $so_po_ref_id_arr[] = $so_po_ref_id;
            $x++;
        }
        if ($order->so_po_ref_id == 0) {
            $get_gl_type = $db->Execute('SELECT ref_id,gl_type from ' . TABLE_JOURNAL_ITEM . ' where id in(' . $so_po_ref_id . ') and gl_type="doo"');
        } else {
            $get_gl_type = $db->Execute('SELECT gl_type from ' . TABLE_JOURNAL_ITEM . ' where ref_id=' . $order->so_po_ref_id . ' and gl_type="doo"');
        }
        if ($get_gl_type->RecordCount() > 0) {
            $order->gl_do_type = $get_gl_type->fields['gl_type'];
            $order->do_ref_id = $get_gl_type->fields['ref_id'];
        } else {
            $order->gl_do_type = '';
        }
// check for errors (address fields)
        if (!$order->bill_acct_id && !$order->bill_add_update) {
            $contact_type = $account_type == 'c' ? TEXT_LC_CUSTOMER : TEXT_LC_VENDOR;
            $messageStack->add(sprintf(ERROR_NO_CONTACT_SELECTED, $contact_type, $contact_type, ORD_ADD_UPDATE), 'error');
            break; // go no further
        }
        $base_msg = in_array(JOURNAL_ID, array(3, 4, 6, 7)) ? TEXT_REMIT_TO : TEXT_BILL_TO;
        if ($order->bill_primary_name === false)
            $error = $messageStack->add(GEN_ERRMSG_NO_DATA . $base_msg . ' / ' . GEN_PRIMARY_NAME, 'error');
        if ($order->bill_contact === false)
            $error = $messageStack->add(GEN_ERRMSG_NO_DATA . $base_msg . ' / ' . GEN_CONTACT, 'error');
        if ($order->bill_address1 === false)
            $error = $messageStack->add(GEN_ERRMSG_NO_DATA . $base_msg . ' / ' . GEN_ADDRESS1, 'error');
        if ($order->bill_address2 === false)
            $error = $messageStack->add(GEN_ERRMSG_NO_DATA . $base_msg . ' / ' . GEN_ADDRESS2, 'error');
        if ($order->bill_city_town === false)
            $error = $messageStack->add(GEN_ERRMSG_NO_DATA . $base_msg . ' / ' . GEN_CITY_TOWN, 'error');
        if ($order->bill_state_province === false)
            $error = $messageStack->add(GEN_ERRMSG_NO_DATA . $base_msg . ' / ' . GEN_STATE_PROVINCE, 'error');
        if ($order->bill_postal_code === false)
            $error = $messageStack->add(GEN_ERRMSG_NO_DATA . $base_msg . ' / ' . GEN_POSTAL_CODE, 'error');
        if (ENABLE_SHIPPING_FUNCTIONS) {
            if ($order->ship_primary_name === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_PRIMARY_NAME, 'error');
            if ($order->ship_contact === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_CONTACT, 'error');
            if ($order->ship_address1 === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_ADDRESS1, 'error');
            if ($order->ship_address2 === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_ADDRESS2, 'error');
            if ($order->ship_city_town === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_CITY_TOWN, 'error');
            if ($order->ship_state_province === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_STATE_PROVINCE, 'error');
            if ($order->ship_postal_code === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_POSTAL_CODE, 'error');
            if ($order->ship_telephone1 === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_TELEPHONE1, 'error');
            if ($order->ship_email === false)
                $error = $messageStack->add(GEN_ERRMSG_NO_DATA . ORD_SHIP_TO . ' / ' . GEN_EMAIL, 'error');
        }

        foreach ($so_po_ref_id_arr as $so_po_ref_id) {
            $check_doo = $db->Execute('SELECT sum(qty) as qty FROM ' . TABLE_JOURNAL_ITEM . ' where gl_type="doo" and id in (' . $so_po_ref_id . ') group by sku');
            $i = 0;
            while (!$check_doo->EOF) {

                if ($order->item_rows[$i]['qty'] <= 0) {
                    $error = $messageStack->add('Entered bad item quantity !!', 'error');
                } else if ($check_doo->fields['qty'] < $order->item_rows[$i]['pstd']) {
                    $error = $messageStack->add('Entered bad item quantity !!', 'error');
                }
                $i++;
                $check_doo->MoveNext();
            }
        }
// Item row errors
        if (!$order->item_rows)
            $error = $messageStack->add(GL_ERROR_NO_ITEMS, 'error');
// End of error checking, check for attachments and process the order
        if (!$error) { // Post the order
            if ($post_success = $order->post_ordr($action)) { // Post the order class to the db
                if ($order->rm_attach)
                    @unlink(UCBOOKS_DIR_MY_ORDERS . 'order_' . $order->id . '.zip');
                if (is_uploaded_file($_FILES['file_name']['tmp_name']))
                    saveUploadZip('file_name', UCBOOKS_DIR_MY_ORDERS, 'order_' . $order->id . '.zip');
                gen_add_audit_log(constant('ORD_TEXT_' . JOURNAL_ID . '_WINDOW_TITLE') . ' - ' . ($_POST['id'] ? TEXT_EDIT : TEXT_ADD), $order->purchase_invoice_id, $order->total_amount);
                if (DEBUG)
                    $messageStack->write_debug();


// this is for recurring invoice coding by 4axiz(apu) date:16-july-2013
                if (!empty($_POST['is_recurring']) && intval($_POST['recurring_interval']) > 0) {

                    $recurring_data = mysql_escape_string(serialize($order));
                    $recurring_info['recurring_interval'] = intval($_POST['recurring_interval']);
                    $recurring_info['create_date'] = date("Y-m-d g:i:s");
                    $recurring_info['sender_name'] = $_POST['sender_name'];
                    $recurring_info['sender_email'] = $_POST['sender_email'];
                    $recurring_info['receiver_name'] = $_POST['recep_name'];
                    $recurring_info['receiver_email'] = $_POST['recep_email'];
                    $recurring_info['cc'] = $_POST['cc'];
                    $recurring_info['cc_email'] = $_POST['cc_email'];
                    $recurring_info['email_subject'] = $_POST['mail_subject'];
                    $recurring_info['email_body'] = $_POST['mail_body'];

                    $query1 = "SELECT * FROM journal_main WHERE id=$order->id";
                    $res1 = mysql_query($query1);
                    $recurring_info['inv_info'] = mysql_fetch_assoc($res1);
                    $query2 = "SELECT * FROM journal_item WHERE ref_id=$order->id";
                    $res2 = mysql_query($query2);

                    $recurring_info['inv_detail_info'] = array();
                    while ($detail = mysql_fetch_assoc($res2)) {
                        $recurring_info['inv_detail_info'][] = $detail;
                    }
                    $recurring_info['parent_inv_id'] = $order->id;
                    $recurring_info['inv_type'] = 'invoice';
                    $recurring_info['inv_date'] = $order->post_date;
//                    $recurring_info['inv_info'] = mysql_escape_string(serialize($recurring_info['inv_info']));
//                    $recurring_info['inv_detail_info'] = mysql_escape_string(serialize($recurring_info['inv_detail_info']));

                    $query = "SELECT * FROM recurring_to_invoice WHERE invoice_id=" . $order->id;
                    $res = mysql_query($query);
                    $recurring_info_is_set = mysql_fetch_assoc($res);


                    if (!$recurring_info_is_set) {

                        $query = "INSERT INTO invoice_recurring(journal_id, parent_inv_id ,inv_info,inv_date,create_date,recurring_interval,sender_name,sender_email,receiver_name,receiver_email,cc,cc_email,email_subject,email_body) VALUES (" . $_GET[jID] . "," . $order->id . ",'" . $recurring_data . "','" . $recurring_info['inv_date'] . "','" . $recurring_info['create_date'] . "'," . $recurring_info['recurring_interval'] . ",'" . $recurring_info['sender_name'] . "','" . $recurring_info['sender_email'] . "','" . $recurring_info['receiver_name'] . "','" . $recurring_info['receiver_email'] . "','" . $recurring_info['cc'] . "','" . $recurring_info['cc_email'] . "','" . $recurring_info['email_subject'] . "','" . $recurring_info['email_body'] . "')";


                        if (mysql_query($query)) {
                            $recurring_id = mysql_insert_id();
                            if ($recurring_id) {
                                $query = "INSERT INTO recurring_to_invoice (recurring_id,invoice_id) VALUES (" . $recurring_id . "," . $order->id . ")";
                                mysql_query($query);
                            }
                        }
                    } else {
                        if (!empty($_POST['update_recurring_info'])) {

                            $query = "UPDATE invoice_recurring SET inv_info = '" . $recurring_data . "' ,  update_date = '" . date("Y-m-d g:i:s") . "' , recurring_interval = " . $recurring_info['recurring_interval'] . ",  sender_name = '" . $_POST['sender_name'] . "',  sender_email = '" . $_POST['sender_email'] . "',  receiver_name = '" . $_POST['recep_name'] . "',  receiver_email = '" . $_POST['recep_email'] . "',  cc = '" . $_POST['cc'] . "',  cc_email = '" . $_POST['cc_email'] . "',  email_subject = '" . $_POST['mail_subject'] . "',  email_body = '" . $_POST['mail_body'] . "' WHERE parent_inv_id=" . $order->id;
                            mysql_query($query);
                        } else {

                            $query = "UPDATE invoice_recurring SET recurring_interval = " . $recurring_info['recurring_interval'] . ",  sender_name = '" . $_POST['sender_name'] . "',  sender_email = '" . $_POST['sender_email'] . "',  receiver_name = '" . $_POST['recep_name'] . "',  receiver_email = '" . $_POST['recep_email'] . "',  cc = '" . $_POST['cc'] . "',  cc_email = '" . $_POST['cc_email'] . "',  email_subject = '" . $_POST['mail_subject'] . "',  email_body = '" . $_POST['mail_body'] . "' WHERE parent_inv_id=" . $order->id;
                            mysql_query($query);
                        }
                    }
                } else {
                    if (isset($_POST['is_recurring']) && empty($_POST['is_recurring'])) {
                        $query = "DELETE FROM invoice_recurring WHERE parent_inv_id  = " . $order->id;
                        if (mysql_query($query)) {
                            $query = "DELETE FROM recurring_to_invoice WHERE invoice_id  = " . $order->id;
                            mysql_query($query);
                        }
                    }
                }

                if (isset($_GET['rec_id']) && !empty($_GET['rec_id'])) {
                    if (empty($_POST['is_recurring'])) {
                        $query = "DELETE FROM invoice_recurring WHERE parent_inv_id  = " . $order->id;
                        if (mysql_query($query)) {
                            $query = "DELETE FROM recurring_to_invoice WHERE invoice_id  = " . $order->id;
                            mysql_query($query);
                        }
                    }
                }
// this is for insert value in journal_main for recurring invoice coding by 4axiz(apu) date:16-july-2013 
//                $query = "SELECT * FROM invoice_recurring WHERE parent_inv_id=" . $order->id;
//                $res = mysql_query($query);
//                
//                $recurring_info_is_get = mysql_fetch_assoc($res);
//                
//                $invoice_info = unserialize($recurring_info_is_get['inv_info']);
//                $invoice_item_info = unserialize($recurring_info_is_get['inv_detail_info']);
//                
//                $query =  $order->create_insert_query("journal_main", $invoice_info);
//                mysql_query($query);
//                $invoice_id = mysql_insert_id();
//                
//                foreach($invoice_item_info as $invoice_item){
//                    $invoice_item['ref_id'] = $invoice_id;
//                    $query =  $order->create_insert_query("journal_item", $invoice_item);
//                    mysql_query($query);
//recurring invoice end is here coding by 4axiz(apu) date:16-july-2013


                if ($action == 'save') {
                    if (isset($_GET['doto']) && $_GET['doto'] == 'post') {
                        return;
                    } else {
//gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));                    
                        gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;oID=' . $order->id . '&amp;jID=' . $_GET['jID'] . '&amp;sub_jID=' . SUB_JOURNAL_ID . '&amp;action=edit', 'SSL'));
                    }
                } elseif ($action == 'payment') {
                    switch (JOURNAL_ID) {
                        case 6: $jID = 20;
                            break; // payments
                        case 12:
                        case 36: $jID = 18;
                            break; // cash receipts
                        default: $jID = 0; // error
                    }
                    gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;jID=' . $jID . '&amp;sub_jID=' . SUB_JOURNAL_ID . '&amp;type=' . $account_type . '&amp;oID=' . $order->id . '&amp;action=pmt', 'SSL'));
                } // else print or print_update, fall through and load javascript to call form_popup and clear form
            } else { // reset the id because the post failed (ID could have been set inside of Post)
                $error = true;
                $order->purchase_invoice_id = db_prepare_input($_POST['purchase_invoice_id']); // reset order num to submitted value (may have been set if payment failed)
                $order->id = ($_POST['id'] <> '') ? $_POST['id'] : ''; // will be null unless opening an existing purchase/receive
            }
        } else { // there was a post error, reset id and re-display form
            $messageStack->add(GL_ERROR_NO_POST, 'error');
        }




        if ($action == 'post_previous') {
            $result = $db->Execute("select id from " . TABLE_JOURNAL_MAIN . " 
	    where journal_id = '12' and purchase_invoice_id < '" . $order->purchase_invoice_id . "' 
	    order by purchase_invoice_id DESC limit 1");
            if ($result->RecordCount() > 0) {
                $oID = $result->fields['id'];
                $action = 'edit'; // force page to reload with the new order to edit
                $order = new orders();
            } else { // at the beginning
                gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
            }
        }
        if ($action == 'post_next') {
            $result = $db->Execute("select id from " . TABLE_JOURNAL_MAIN . " 
	    where journal_id = '12' and purchase_invoice_id > '" . $order->purchase_invoice_id . "' 
	    order by purchase_invoice_id limit 1");
            if ($result->RecordCount() > 0) {
                $oID = $result->fields['id'];
                $action = 'edit'; // force page to reload with the new order to edit
                $order = new orders();
            } else { // at the end
                gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
            }
        }
        if (isset($_GET['doto']) && $_GET['doto'] == 'post') {
            return true;
        }
        if (DEBUG)
            $messageStack->write_debug();
        break;

    case 'delete':
        validate_security($security_level, 4);
        $id = ($_POST['id'] <> '') ? $_POST['id'] : ''; // will be null unless opening an existing purchase/receive
        if ($id) {
            $delOrd = new orders();
            $delOrd->journal($id); // load the posted record based on the id submitted
            if ($delOrd->closed == 0) {
                if ($_SESSION['admin_prefs']['restrict_period'] && $delOrd->period <> CURRENT_ACCOUNTING_PERIOD) {
                    $error = $messageStack->add(ORD_ERROR_DEL_NOT_CUR_PERIOD, 'error');
                    break;
                }
                $delOrd->recur_frequency = db_prepare_input($_POST['recur_frequency']);
                if ($delOrd->delete_ordr()) {
                    if (DEBUG)
                        $messageStack->write_debug();
                    gen_add_audit_log(constant('ORD_TEXT_' . JOURNAL_ID . '_WINDOW_TITLE') . ' - Delete', $delOrd->purchase_invoice_id, $delOrd->total_amount);
                    gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
                    break;
                }
            }else {

                $messageStack->add('You can not deleted this entry', 'error');
                gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=orders&oID=' . $_GET['oID'] . '&jID=' . $_GET['jID'] . '&amp;sub_jID=' . SUB_JOURNAL_ID . '&action=edit', 'SSL'));
            }
        } else {
            $messageStack->add(GL_ERROR_NEVER_POSTED, 'error');
        }
        $messageStack->add(GL_ERROR_NO_DELETE, 'error');
        if (DEBUG)
            $messageStack->write_debug();
        break;

    case 'edit':
    case 'prc_so':
        $oID = db_prepare_input($_GET['oID']);
        if (!$oID) {
            $messageStack->add('Bad order ID passed to edit order.', 'error'); // this should never happen
            $action = '';
        }
        break;
    case 'prc_doo':
        $oID = db_prepare_input($_GET['oID']);
        if (!$oID) {
            $messageStack->add('Bad order ID passed to edit order.', 'error'); // this should never happen
            $action = '';
        }
        break;
    case 'prc_do':
        // $oID = explode(',', db_prepare_input($_POST['idarray']));
        // $oID = $oID[1];
        $oID = db_prepare_input($_POST['idarray']) ? db_prepare_input($_POST['idarray']) : db_prepare_input($_GET['oID']);
        $contactID = db_prepare_input($_POST['customer']) ? db_prepare_input($_POST['customer']) : db_prepare_input($_GET['oID']);
        if (!$oID) {
            $messageStack->add('Bad order ID passed to edit order.', 'error'); // this should never happen
            $action = '';
        }
        break;
    case 'dn_attach':
        $oID = db_prepare_input($_POST['id']);
        if (file_exists(UCBOOKS_DIR_MY_ORDERS . 'order_' . $oID . '.zip')) {
            require_once(DIR_FS_MODULES . 'ucounting/classes/backup.php');
            $backup = new backup();
            $backup->download(UCBOOKS_DIR_MY_ORDERS, 'order_' . $oID . '.zip', true);
        }
        die;

    default:
}



/* * ***************   prepare to display templates  ************************ */


//this is for check credit memo discount entry or not coding by 4axiz(apu) date - 22_feb_2014
if ($oID != '') {
    $sql = "SELECT description from journal_main WHERE id = " . $oID;
    $result = mysql_query($sql);
    $res = mysql_fetch_assoc($result);

    if ($res['description'] == "Customer Credit Memos Entry Discount" || $res['description'] == "Vendor Credit Memos Entry Discount" || $res['description'] == "Customer Debit Memos Entry Discount" || $res['description'] == "Vendor Debit Memos Entry Discount") {
        $ajax_order_type = 'var ajax_order_type = 1;';
    } else {
        $ajax_order_type = 'var ajax_order_type = 0;';
    }
}

// generate address arrays for javascript
$js_arrays = gen_build_company_arrays();
$fill = isset($_GET['fill']) ? $_GET['fill'] : 'bill';
// load gl accounts
$gl_array_list = gen_coa_pull_down();
// generate the list of gl accounts and fill js arrays for dynamic pull downs
$js_gl_array = 'var js_gl_array = new Array(' . count($gl_array_list) . ');' . chr(10);
for ($i = 0; $i < count($gl_array_list); $i++) {
    $js_gl_array .= 'js_gl_array[' . $i . '] = new dropDownData("' . $gl_array_list[$i]['id'] . '", "' . $gl_array_list[$i]['text'] . '");' . chr(10);
}
// load the tax rates
$tax_rates = ord_calculate_tax_drop_down($account_type);
//uom list
$uom_droup_down = uom_droup_down();
// generate a rate array parallel to the drop down for the javascript total calculator
$js_tax_rates = 'var tax_rates = new Array(' . count($tax_rates) . ');' . chr(10);
for ($i = 0; $i < count($tax_rates); $i++) {
    $js_tax_rates .= 'tax_rates[' . $i . '] = new salesTaxes("' . $tax_rates[$i]['id'] . '", "' . $tax_rates[$i]['text'] . '", "' . $tax_rates[$i]['rate'] . '");' . chr(10);
}
//generate a drop down for UOM
$js_uom_list = 'var uom_list = new Array(' . count($uom_droup_down) . ');' . chr(10);
for ($i = 0; $i < count($uom_droup_down); $i++) {
    $js_uom_list .= 'uom_list[' . $i . '] = new uom_option("' . $uom_droup_down[$i]['id'] . '", "' . $uom_droup_down[$i]['text'] . '");' . chr(10);
}

// load projects
$proj_list = ord_get_projects();
// generate a project list array parallel to the drop down for the javascript add line item function
$js_proj_list = 'var proj_list = new Array(' . count($proj_list) . ');' . chr(10);
for ($i = 0; $i < count($proj_list); $i++) {
    $js_proj_list .= 'proj_list[' . $i . '] = new dropDownData("' . $proj_list[$i]['id'] . '", "' . $proj_list[$i]['text'] . '");' . chr(10);
}
// see if current user points to a employee for sales rep default
$result = $db->Execute("select account_id from " . TABLE_USERS . " where admin_id = " . $_SESSION['admin_id']);
$default_sales_rep = $result->fields['account_id'] ? $result->fields['account_id'] : '0';

// Load shipping methods
if (defined('MODULE_SHIPPING_STATUS')) {
    $methods = load_all_methods('shipping', true, true);
    $shipping_methods = build_js_methods($methods);
} else {
    $shipping_methods = 'var freightLevels   = new Array(); ' . chr(10);
    $shipping_methods .= 'var freightCarriers = new Array(); ' . chr(10);
    $shipping_methods .= 'var freightDetails  = new Array(); ' . chr(10);
}

// load calendar parameters
$cal_order = array(
    'name' => 'dateOrdered',
    'form' => 'orders',
    'fieldname' => 'post_date',
    'imagename' => 'btn_date_1',
    'default' => isset($order->post_date) ? gen_locale_date($order->post_date) : date(DATE_FORMAT),
    'params' => array('align' => 'left'),
);

$cal_terminal = array(
    'name' => 'dateRequired',
    'form' => 'orders',
    'fieldname' => 'terminal_date',
    'imagename' => 'btn_date_2',
    'default' => isset($order->terminal_date) ? gen_locale_date($order->terminal_date) : date(DATE_FORMAT),
    'params' => array('align' => 'left'),
);
// build the display options based on JOURNAL_ID
$template_options = array();
switch (JOURNAL_ID) {
    case 3:
    case 4:
        $req_date = gen_locale_date(gen_specific_date('', 0, 1, 0));
        $template_options['terminal_date'] = true;
        $template_options['terms'] = true;
        $template_options['closed'] = array(
            'title' => TEXT_CLOSE,
            'field' => html_checkbox_field('closed', '1', ($order->closed) ? true : false, '', ''));
        break;
    case 6:
        $req_date = gen_locale_date(gen_specific_date('', 0, 1, 0));
        $template_options['terms'] = true;
        $template_options['waiting'] = array(
            'title' => ORD_WAITING_FOR_INVOICE,
            'field' => html_checkbox_field('waiting', '1', ($order->waiting) ? true : false, '', ''));
        break;
    case 7:
    case 31:
        $req_date = date(DATE_FORMAT);
        $template_options['terms'] = true;
        $template_options['waiting'] = array(
            'title' => ORD_WAITING_FOR_INVOICE,
            'field' => html_checkbox_field('waiting', '1', ($order->waiting) ? true : false, '', ''));
        break;
    case 9:
    case 10:
    case 32:
        $template_options['closed'] = array(
            'title' => TEXT_CLOSE,
            'field' => html_checkbox_field('closed', '1', ($order->closed) ? true : false, '', 'tabindex="5"'));
    case 12:
        switch (SUB_JOURNAL_ID) {
            case '0' :
            case '1' :
                $req_date = date(DATE_FORMAT);
                $template_options['terminal_date'] = true;
                $template_options['terms'] = true;
                break;
            case '2' :
                $req_date = date(DATE_FORMAT);
                break;
        }
        break;
    case 36:
        $req_date = date(DATE_FORMAT);
        $template_options['terminal_date'] = true;
        $template_options['terms'] = true;
        break;
    case 13:
    case 30:
        $req_date = date(DATE_FORMAT);
        break;
    default:
}

$include_header = true;
$include_footer = true;
$include_tabs = false;
$include_calendar = true;
$include_template = 'template_main.php'; // include display template (required)

define('PAGE_TITLE', constant('ORD_TEXT_' . JOURNAL_ID . '_WINDOW_TITLE'));
?>
