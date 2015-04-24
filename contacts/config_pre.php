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
//  Path: /modules/contacts/config.php
//
// Release History
// 3.0 => 2011-01-15 - Converted from stand-alone UcBooks release
// 3.1 => released by Rene on the forum
// 3.2 => Release by Rene on the forum
// 3.3 => 2011-04-15 - CRM additions, bug fixes
// 3.4 => 2011-08-01 - bug fixes
// 3.5 => 2011-11-15 - bug fixes, attachments, themeroller changes
// 3.6 => 2012-02-15 - bug fixes, improved CRM, clean up forms
// 3.7 => 2012-10-01 - bug fixes, redesign of the classes/methods
// Module software version information
define('MODULE_CONTACTS_VERSION', '3.7');
// Menu Sort Positions
// Menu Security id's (refer to master doc to avoid security setting overlap)
define('SECURITY_ID_MAINTAIN_BRANCH', 15);
define('SECURITY_ID_MAINTAIN_CUSTOMERS', 26);
define('SECURITY_ID_MAINTAIN_EMPLOYEES', 76);
define('SECURITY_ID_MAINTAIN_PROJECTS', 16);
define('SECURITY_ID_PROJECT_PHASES', 36);
define('SECURITY_ID_PROJECT_COSTS', 37);
define('SECURITY_ID_UCCRM', 49);
define('SECURITY_ID_MAINTAIN_VENDORS', 51);
define('SECURITY_ID_QUOTE_STATUS', 35);
define('SECURITY_ID_SALES_STATUS', 32);
define('SECURITY_ID_INVOICE_MGR', 34);
define('SECURITY_ID_CUST_CREDIT_STATUS', 40);
define('SECURITY_ID_CUST_DEBIT_STATUS', 41);
define('SECURITY_RMA_MGT', 180);
define('SECURITY_ID_UCPOS', 38);
define('SECURITY_ID_RFQ_STATUS', 59);
define('SECURITY_ID_PURCHASE_STATUS', 58);
define('SECURITY_ID_PURCH_INV_STATUS', 61);
define('SECURITY_ID_EXPENSES_INV_STATUS', 948);
define('SECURITY_ID_VCM_STATUS', 60);
define('SECURITY_ID_VDM_STATUS', 62);
// New Database Tables
define('TABLE_ADDRESS_BOOK', DB_PREFIX . 'address_book');
define('TABLE_CONTACTS', DB_PREFIX . 'contacts');
define('TABLE_CONTACTS_LOG', DB_PREFIX . 'contacts_log');
define('TABLE_DEPARTMENTS', DB_PREFIX . 'departments');
define('TABLE_DEPT_TYPES', DB_PREFIX . 'departments_types');
define('TABLE_PROJECTS_COSTS', DB_PREFIX . 'projects_costs');
define('TABLE_PROJECTS_PHASES', DB_PREFIX . 'projects_phases');
// Set the title menu


$pb_headings[MENU_HEADING_CUSTOMERS_ORDER] = array(
    'text' => MENU_HEADING_CUSTOMERS,
    'menu_select' => 'customers',
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;mID=cat_ar', 'SSL'),
);
$pb_headings[MENU_HEADING_VENDORS_ORDER] = array(
    'text' => MENU_HEADING_VENDORS,
    'menu_select' => 'vendors',
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;mID=cat_ap', 'SSL'),
);
// Set the menus

//$customers_array = array(121, SECURITY_ID_MAINTAIN_CUSTOMERS, SECURITY_ID_QUOTE_STATUS, SECURITY_ID_SALES_STATUS, SECURITY_ID_INVOICE_MGR, SECURITY_ID_SALES_INVOICE, SECURITY_ID_CUST_CREDIT_STATUS, SECURITY_ID_CUST_DEBIT_STATUS, SECURITY_RMA_MGT, SECURITY_ID_MAINTAIN_PROJECTS, SECURITY_ID_UCPOS, SECURITY_ID_POS_MGR, SECURITY_ID_UCCRM);

$menu[] = array(
    'text' => BOX_CONTACTS_Sales_Dashboard,
    'menu_select' => 'customers',
    'sub_menu_select' => 'Sales_Dashboard',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 5,
    'security_id' => 121,
//    'hidden' => $_SESSION['admin_security'][SECURITY_ID_MAINTAIN_CUSTOMERS] > 1 ? false : true,

    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_ar', 'SSL'),
);
$menu[] = array(
    'text' => BOX_CONTACTS_NEW_CUSTOMER,
    'menu_select' => 'customers',
    'sub_menu_select' => 'new_customer',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 5,
    'hide' => true,
    'security_id' => SECURITY_ID_MAINTAIN_CUSTOMERS,
//    'hidden' => $_SESSION['admin_security'][SECURITY_ID_MAINTAIN_CUSTOMERS] > 1 ? false : true,
    'hidden' => true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;action=new&amp;type=c', 'SSL'),
);
$menu[] = array(
    'text' => BOX_CONTACTS_MAINTAIN_CUSTOMERS,
    'menu_select' => 'customers',
    'sub_menu_select' => 'customer_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 10,
    'security_id' => SECURITY_ID_MAINTAIN_CUSTOMERS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;type=c&amp;list=1', 'SSL'),
);
$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_9_WINDOW_TITLE),
    'text' => QUOTATIONS,
    'menu_select' => 'customers',
    'sub_menu_select' => 'sales_quotes_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 25,
    'security_id' => SECURITY_ID_QUOTE_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=9&amp;list=1', 'SSL'),
);
$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_10_WINDOW_TITLE),
    'text' => ORDERS,
    'menu_select' => 'customers',
    'sub_menu_select' => 'sales_orders_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 35,
    'security_id' => SECURITY_ID_SALES_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=10&amp;list=1', 'SSL'),
);
$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_10_WINDOW_TITLE),
    'text' => 'Delivery Orders',
    'menu_select' => 'customers',
    'sub_menu_select' => 'delivery_orders_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 35,
    'security_id' => 125,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=32&amp;list=1', 'SSL'),
);
$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_12_WINDOW_TITLE),
    'text' => SALES_INVOICES,
    'menu_select' => 'customers',
    'sub_menu_select' => 'sales_invoices_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 50,
    'security_id' => SECURITY_ID_INVOICE_MGR,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=12&amp;list=1', 'SSL'),
);
$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_12_WINDOW_TITLE),
    'text' => 'POS',
    'menu_select' => 'customers',
    'sub_menu_select' => 'sales_pos',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 50,
    'security_id' => '950',
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=12&sub_jID=1&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => 'Recurring Invoice',
    'menu_select' => 'customers',
    'sub_menu_select' => 'recurring_invoice',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 40,
    'hidden' => false,
    'security_id' => SECURITY_ID_SALES_INVOICE,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=recurring_invoice_list', 'SSL'),
);
$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_13_WINDOW_TITLE),
    'text' => CREDIT_MEMOS,
    'menu_select' => 'customers',
    'sub_menu_select' => 'customer_credit_memos_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 60,
    'security_id' => SECURITY_ID_CUST_CREDIT_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=13&amp;list=1', 'SSL'),
);
$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_13_WINDOW_TITLE),
    'text' => DEBIT_MEMOS,
    'menu_select' => 'customers',
    'sub_menu_select' => 'customer_dedit_memos_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 70,
    'security_id' => SECURITY_ID_CUST_DEBIT_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=12&sub_jID=2&amp;list=1', 'SSL'),
);

$menu[] = array(
    'text' => BOX_RMA_MODULE,
    'menu_select' => 'customers',
    'sub_menu_select' => 'rma',
    'heading' => MENU_HEADING_CUSTOMERS, // MENU_HEADING_RMA
    'rank' => BOX_RMA_MODULE_ORDER,
    'security_id' => SECURITY_RMA_MGT,
    'set_hidden' => true,
    'hidden' => $_SESSION['admin_security'][SECURITY_ID_CONFIGURATION] > 0 ? false : true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=rma&amp;page=main', 'SSL'),
);



$menu[] = array(
    'text' => BOX_CONTACTS_MAINTAIN_PROJECTS,
    'menu_select' => 'customers',
    'sub_menu_select' => 'project_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 60,
    'security_id' => SECURITY_ID_MAINTAIN_PROJECTS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;type=j&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => BOX_UCPOS,
    'menu_select' => 'customers',
    'sub_menu_select' => 'point_of_sale',
    'heading' => MENU_HEADING_CUSTOMERS, // MENU_HEADING_UCPOS
    'rank' => 50,
    'security_id' => SECURITY_ID_UCPOS,
    'set_hidden' => true,
    'hidden' => $_SESSION['admin_security'][SECURITY_ID_CONFIGURATION] > 0 ? false : true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucpos&amp;page=main', 'SSL'),
);

$menu[] = array(
    'text' => BOX_POS_MGR,
    'menu_select' => 'customers',
    'sub_menu_select' => 'pos_pop_manager',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 53,
    'security_id' => SECURITY_ID_POS_MGR,
    'set_hidden' => true,
    'hidden' => $_SESSION['admin_security'][SECURITY_ID_CONFIGURATION] > 0 ? false : true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucpos&amp;page=pos_mgr&amp;list=1', 'SSL'),
);

$menu[] = array(
    'text' => BOX_UCCRM_MODULE,
    'menu_select' => 'customers',
    'sub_menu_select' => 'uccrm',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 15,
    'security_id' => SECURITY_ID_UCCRM,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;type=i&amp;list=1', 'SSL'),
);
$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_13_WINDOW_TITLE),
    'text' => SALES_CUSTOMER_DEPOSIT,
    'menu_select' => 'customers',
    'sub_menu_select' => 'sales_customer_deposit',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 60,
    'security_id' => SECURITY_ID_CUST_CREDIT_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=13&sub_jID=1&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => BOX_CONTACTS_NEW_VENDOR,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'new_vendor',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 5,
    'hide' => true,
    'security_id' => SECURITY_ID_MAINTAIN_VENDORS,
//    'hidden' => $_SESSION['admin_security'][SECURITY_ID_MAINTAIN_VENDORS] > 1 ? false : true,
    'hidden' => true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;action=new&amp;type=v', 'SSL'),
);

$menu[] = array(
    'text' => 'Purchases Dashboard',
    'menu_select' => 'vendors',
    'sub_menu_select' => 'Purchases_Dashboard',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 10,
    'security_id' => 122,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_ap', 'SSL'),
);

$menu[] = array(
    'text' => BOX_CONTACTS_MAINTAIN_VENDORS,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'vendor_manager',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 10,
    'security_id' => SECURITY_ID_MAINTAIN_VENDORS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;type=v&amp;list=1', 'SSL'),
);

$menu[] = array(
    'text' => REQUEST_FOR_QUOTES,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'request_for_quotes_manager',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 25,
    'security_id' => SECURITY_ID_RFQ_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=3&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => PURCHASE_ORDERS,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'purchase_orders_manager',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 35,
    'security_id' => SECURITY_ID_PURCHASE_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=4&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => PURCHASE,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'purchase_receive_inventory_manager',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 45,
    'security_id' => SECURITY_ID_PURCH_INV_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=6&amp;list=1', 'SSL'),
);

$menu[] = array(
    'text' => EXPENSES,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'expenses',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 50,
    'security_id' => SECURITY_ID_EXPENSES_INV_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=6&sub_jID=1&amp;list=1', 'SSL'),
);

$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_7_WINDOW_TITLE),
    'text' => CREDIT_MEMOS,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'vendor_credit_memos_manager',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 55,
    'security_id' => SECURITY_ID_VCM_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=7&amp;list=1', 'SSL'),
);

$menu[] = array(
    //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_7_WINDOW_TITLE),
    'text' => DEBIT_MEMOS,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'vendor_debit_memos_manager',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 65,
    'security_id' => SECURITY_ID_VDM_STATUS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=31&amp;list=1', 'SSL'),
);


$menu[] = array(
    'text' => BOX_CONTACTS_NEW_EMPLOYEE,
    'menu_select' => 'employees',
    'sub_menu_select' => 'new_employee',
    'heading' => MENU_HEADING_EMPLOYEES,
    'rank' => 5,
    'hide' => true,
    'security_id' => SECURITY_ID_MAINTAIN_EMPLOYEES,
//    'hidden' => $_SESSION['admin_security'][SECURITY_ID_MAINTAIN_EMPLOYEES] > 1 ? false : true,
    'hidden' => true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;action=new&amp;type=e', 'SSL'),
);
//$menu[] = array(
//    'text' => 'Employee Dashboard',
//    'menu_select' => 'employees',
//    'sub_menu_select' => 'Employee_Dashboard',
//    'heading' => MENU_HEADING_EMPLOYEES,
//    'rank' => 10,
//    'security_id' => SECURITY_ID_MAINTAIN_EMPLOYEES,
//    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_hr', 'SSL'),
//);  

$menu[] = array(
    'text' => BOX_CONTACTS_MAINTAIN_EMPLOYEES,
    'menu_select' => 'employees',
    'sub_menu_select' => 'employee_manager',
    'heading' => MENU_HEADING_EMPLOYEES,
    'rank' => 10,
    'security_id' => SECURITY_ID_MAINTAIN_EMPLOYEES,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;type=e&amp;list=1', 'SSL'),
);
if (ENABLE_MULTI_BRANCH) { // don't show menu if multi-branch is disabled
    $menu[] = array(
        'text' => BOX_CONTACTS_NEW_BRANCH,
        'heading' => MENU_HEADING_COMPANY,
        'menu_select' => 'company',
        'sub_menu_select' => 'new_branch',
        'rank' => 55,
        'hide' => true,
        'set_hidden' => true,
        'security_id' => SECURITY_ID_MAINTAIN_BRANCH,
        'hidden' => $_SESSION['admin_security'][SECURITY_ID_MAINTAIN_BRANCH] > 1 ? false : true,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;action=new&amp;type=b', 'SSL'),
    );
    $menu[] = array(
        'text' => BOX_CONTACTS_MAINTAIN_BRANCHES,
        'heading' => MENU_HEADING_COMPANY,
        'menu_select' => 'company',
        'sub_menu_select' => 'branch_manager',
        'rank' => 56,
        'security_id' => '133',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&amp;page=main&amp;type=b&amp;list=1', 'SSL'),
    );
} // end disable if not looking at branches

$menu[] = array(
    'text' => CHART_OF_ACCOUNTS,
    'heading' => MENU_HEADING_COMPANY,
    'menu_select' => 'company',
    'sub_menu_select' => 'chart_of_account',
    'rank' => 56,
    'security_id' => '134',
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts', 'SSL'),
);
$menu[] = array(
    'text' => TRANSACTION_SEQUENCE,
    'heading' => MENU_HEADING_COMPANY,
    'menu_select' => 'company',
    'sub_menu_select' => 'transaction_sequence',
    'rank' => 56,
    'security_id' => '135',
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=tools', 'SSL'),
);

$menu[] = array(
    'text' => ENTER_BEGINNING_BALANCE,
    'heading' => MENU_HEADING_COMPANY,
    'menu_select' => 'company',
    'sub_menu_select' => 'enter_beginning_balances',
    'rank' => 56,
    'security_id' => SECURITY_ID_MAINTAIN_BRANCH,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=import_export&action=beg_balances', 'SSL'),
);
//foreach ($menu as $each_menu) {
//    if ($each_menu == '"customers"') {
//        $lebel = $_SESSION['admin_security'][121];
//        if ($lebel != 0 || $lebel != NULL) {
//            $pb_headings[MENU_HEADING_CUSTOMERS_ORDER] = array(
//                'text' => MENU_HEADING_CUSTOMERS,
//                'menu_select' => 'customers',
//                'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;mID=cat_ar', 'SSL'),
//            );
//            break;
//        }else{
//            $lebel = $_SESSION['admin_security'][$each_menu['security_id']];
//          //  $if
//        }
//    }
//}
?>