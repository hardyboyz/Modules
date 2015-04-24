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
//  Path: /modules/ucbooks/config.php
//
// Release History
// 3.0 => 2011-01-15 - Converted from stand-alone UcBooks release
// 3.1 => 2011-04-15 - Bug fixes, managers, code enhancement
// 3.2 => 2011-08-01 - Bug fixes
// 3.3 => 2011-11-15 - Bug fixes, themeroller changes
// 3.4 => 2012-02-15 - Bug fixes
// 3.5 => 2012-10-01 - bug fixes
// Module software version information
define('MODULE_UCBOOKS_VERSION', '3.5');
// Menu Sort Positions

define('MENU_HEADING_CUSTOMERS_ORDER', 10);
define('MENU_HEADING_VENDORS_ORDER', 20);
define('MENU_HEADING_BANKING_ORDER', 40);
define('MENU_HEADING_GL_ORDER', 50);
define('MENU_HEADING_EMPLOYEES_ORDER', 60);
define('MENU_HEADING_TOOLS_ORDER', 70);
define('MENU_HEADING_QUALITY_ORDER', 80);
define('MENU_HEADING_COMPANY_ORDER', 90);

// Menu Security id's (refer to master doc to avoid security setting overlap)

define('SECURITY_ID_GEN_ADMIN_TOOLS', 19);
define('SECURITY_ID_SALES_ORDER', 28);
define('SECURITY_ID_DELIVERY_ORDER', 125);
define('SECURITY_ID_SALES_QUOTE', 29);
define('SECURITY_ID_SALES_INVOICE', 30);
define('SECURITY_ID_SALES_CREDIT', 31);
define('SECURITY_ID_SALES_DEBIT', 1030); // this is security id for sales debit memo added by 4axiz as we don't know 32 may collide with any other security id so we put 1032 

define('SECURITY_ID_POINT_OF_SALE', 33);



define('SECURITY_ID_PURCHASE_ORDER', 53);
define('SECURITY_ID_PURCHASE_QUOTE', 54);
define('SECURITY_ID_PURCHASE_INVENTORY', 55);
define('SECURITY_ID_EXPENSES', 948);
define('SECURITY_ID_POINT_OF_PURCHASE', 56);
define('SECURITY_ID_PURCHASE_CREDIT', 57);
define('SECURITY_ID_PURCHASE_DEBIT', 1031);




define('SECURITY_ID_SELECT_PAYMENT', 101);
define('SECURITY_ID_CUSTOMER_RECEIPTS', 102);
define('SECURITY_ID_PAY_BILLS', 103);
define('SECURITY_ID_ACCT_RECONCILIATION', 104);
define('SECURITY_ID_ACCT_REGISTER', 105);
define('SECURITY_ID_VOID_CHECKS', 106);
define('SECURITY_ID_CUSTOMER_PAYMENTS', 107);
define('SECURITY_ID_VENDOR_RECEIPTS', 108);
define('SECURITY_ID_RECEIPTS_STATUS', 111);
define('SECURITY_ID_PAYMENTS_STATUS', 112);
define('SECURITY_ID_JOURNAL_ENTRY', 126);
define('SECURITY_ID_GL_BUDGET', 129);
define('SECURITY_ID_JOURNAL_STATUS', 130);
// New Database Tables
define('TABLE_ACCOUNTING_PERIODS', DB_PREFIX . 'accounting_periods');
define('TABLE_ACCOUNTS_HISTORY', DB_PREFIX . 'accounts_history');
define('TABLE_CHART_OF_ACCOUNTS', DB_PREFIX . 'chart_of_accounts');
define('TABLE_CHART_OF_ACCOUNTS_HISTORY', DB_PREFIX . 'chart_of_accounts_history');
define('TABLE_JOURNAL_ITEM', DB_PREFIX . 'journal_item');
define('TABLE_JOURNAL_MAIN', DB_PREFIX . 'journal_main');
define('TABLE_TAX_AUTH', DB_PREFIX . 'tax_authorities');
define('TABLE_TAX_RATES', DB_PREFIX . 'tax_rates');
define('TABLE_RECONCILIATION', DB_PREFIX . 'reconciliation');

if (defined('MODULE_UCBOOKS_STATUS')) {
    // Set the title menu
    $pb_headings[MENU_HEADING_BANKING_ORDER] = array(
        'text' => MENU_HEADING_BANKING,
        'menu_select' => 'banking',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=status&jID=18&list=1', 'SSL'),
    );
    $pb_headings[MENU_HEADING_GL_ORDER] = array(
        'text' => MENU_HEADING_GL,
        'menu_select' => 'general_ledger',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=status&jID=2&list=1', 'SSL'),
    );
    $pb_headings[MENU_HEADING_TOOLS_ORDER] = array(
        'text' => MENU_HEADING_TOOLS,
        'menu_select' => 'tools',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucform&amp;page=main', 'SSL'),
            //'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;mID=cat_rep', 'SSL'),
    );

    $pb_headings[MENU_HEADING_EMPLOYEES_ORDER] = array(
        'text' => MENU_HEADING_EMPLOYEES,
        'menu_select' => 'employees',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=contacts&page=main&type=e&list=1', 'SSL'),
    );

    $pb_headings[MENU_HEADING_COMPANY_ORDER] = array(
        'text' => MENU_HEADING_COMPANY,
        'menu_select' => 'company',
        'hidden_for_users' => true,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=users&list=1', 'SSL'),
    );

    $pb_headings[MENU_HEADING_QUALITY_ORDER] = array(
        'text' => MENU_HEADING_QUALITY,
        'menu_select' => 'quality',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=import_export', 'SSL'),
    );

    // Set the menus
//       $menu[] = array(
//        'text' => 'Banking Dashboard',
//        'menu_select' => 'banking',
//        'sub_menu_select' => 'Banking_Dashboard',
//        'heading' => MENU_HEADING_BANKING,
//        'rank' => 5,
//        'security_id' => SECURITY_ID_CUSTOMER_RECEIPTS,
//        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_bnk', 'SSL'),
//    );

    $menu[] = array(
        'text' => ORD_TEXT_18_C_WINDOW_TITLE,
        'menu_select' => 'banking',
        'sub_menu_select' => 'customer_receipts',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 5,
        'hidden' => true,
        'security_id' => SECURITY_ID_CUSTOMER_RECEIPTS,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;jID=18&amp;type=c', 'SSL'),
    );
    $menu[] = array(
        //'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_18_C_WINDOW_TITLE),
        'text' => ORD_TEXT_18_C_WINDOW_TITLE,
        'menu_select' => 'banking',
        'sub_menu_select' => 'customer_receipts_manager',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 10,
        'security_id' => SECURITY_ID_RECEIPTS_STATUS,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=18&amp;list=1', 'SSL'),
    );
    $menu[] = array(
        'text' => ORD_TEXT_20_V_WINDOW_TITLE,
        'menu_select' => 'banking',
        'sub_menu_select' => 'pay_bills',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 15,
        'hidden' => true,
        'security_id' => SECURITY_ID_PAY_BILLS,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;jID=20&amp;type=v', 'SSL'),
    );

    $menu[] = array(
        'text' => Purchases_Payment_Vouchers, //sprintf(BOX_STATUS_MGR, ORD_TEXT_20_V_WINDOW_TITLE),
        'menu_select' => 'banking',
        'sub_menu_select' => 'pay_bills_manager',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 25,
        'security_id' => SECURITY_ID_PAYMENTS_STATUS,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=20&amp;list=1', 'SSL'),
    );
    $menu[] = array(
        'text' => BOX_BANKING_BANK_ACCOUNT_REGISTER,
        'menu_select' => 'banking',
        'sub_menu_select' => 'bank_account_register',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 30,
        'security_id' => SECURITY_ID_ACCT_REGISTER,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=register', 'SSL'),
    );
    $menu[] = array(
        'text' => BOX_BANKING_ACCOUNT_RECONCILIATION,
        'menu_select' => 'banking',
        'sub_menu_select' => 'account_reconciliation',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 35,
        'security_id' => SECURITY_ID_ACCT_RECONCILIATION,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=reconciliation', 'SSL'),
    );
    /*
      $menu[] = array(
      'text'        => BOX_BANKING_VOID_CHECKS,
      'heading'     => MENU_HEADING_BANKING,
      'rank'        => 35,
      'security_id' => SECURITY_ID_VOID_CHECKS,
      'link'        => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=', 'SSL'),
      );
     */
    $menu[] = array(
        'text' => ORD_TEXT_20_C_WINDOW_TITLE,
        'menu_select' => 'banking',
        'sub_menu_select' => 'customer_refunds',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 40,
        'security_id' => SECURITY_ID_CUSTOMER_PAYMENTS,
        'set_hidden' => true,
        'hidden' => $_SESSION['admin_security'][SECURITY_ID_GEN_ADMIN_TOOLS] > 3 ? false : true,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;jID=20&amp;type=c', 'SSL'),
    );
    $menu[] = array(
        'text' => ORD_TEXT_18_V_WINDOW_TITLE,
        'menu_select' => 'banking',
        'sub_menu_select' => 'vendor_refunds',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 45,
        'security_id' => SECURITY_ID_VENDOR_RECEIPTS,
        'set_hidden' => true,
        'hidden' => $_SESSION['admin_security'][SECURITY_ID_GEN_ADMIN_TOOLS] > 3 ? false : true,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;jID=18&amp;type=v', 'SSL'),
    );

    $menu[] = array(
        'text' => ORD_TEXT_9_WINDOW_TITLE,
        'menu_select' => 'customers',
        'sub_menu_select' => 'sales_quotes',
        'heading' => MENU_HEADING_CUSTOMERS,
        'rank' => 20,
        'hidden' => true,
        'security_id' => SECURITY_ID_SALES_QUOTE,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=9', 'SSL'),
    );

    $menu[] = array(
        'text' => ORD_TEXT_10_WINDOW_TITLE,
        'menu_select' => 'customers',
        'sub_menu_select' => 'sales_orders',
        'heading' => MENU_HEADING_CUSTOMERS,
        'rank' => 20,
        'hidden' => true,
        'security_id' => SECURITY_ID_SALES_ORDER,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=10', 'SSL'),
    );
 
    $menu[] = array(
        'text' => ORD_TEXT_13_WINDOW_TITLE,
        'menu_select' => 'customers',
        'sub_menu_select' => 'customer_credit_memos',
        'heading' => MENU_HEADING_CUSTOMERS,
        'rank' => 55,
        'hidden' => true,
        'security_id' => SECURITY_ID_SALES_CREDIT,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=13', 'SSL'),
    );
    $menu[] = array(
        'text' => ORD_TEXT_13_WINDOW_TITLE,
        'menu_select' => 'customers',
        'sub_menu_select' => 'customer_debit_memos',
        'heading' => MENU_HEADING_CUSTOMERS,
        'rank' => 55,
        'hidden' => true,
        'security_id' => SECURITY_ID_SALES_DEBIT,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=30', 'SSL'),
    );

    $menu[] = array(
        'text' => ORD_TEXT_12_WINDOW_TITLE,
        'menu_select' => 'customers',
        'sub_menu_select' => 'sales_invoices',
        'heading' => MENU_HEADING_CUSTOMERS,
        'rank' => 40,
        'hidden' => true,
        'security_id' => SECURITY_ID_SALES_INVOICE,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=12', 'SSL'),
    );
    




    $menu[] = array(
        'text' => ORD_TEXT_3_WINDOW_TITLE,
        'menu_select' => 'vendors',
        'sub_menu_select' => 'request_for_quotes',
        'heading' => MENU_HEADING_VENDORS,
        'rank' => 20,
        'hidden' => true,
        'security_id' => SECURITY_ID_PURCHASE_QUOTE,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=3', 'SSL'),
    );

    $menu[] = array(
        'text' => ORD_TEXT_4_WINDOW_TITLE,
        'menu_select' => 'vendors',
        'sub_menu_select' => 'purchase_orders',
        'heading' => MENU_HEADING_VENDORS,
        'rank' => 30,
        'hidden' => true,
        'security_id' => SECURITY_ID_PURCHASE_ORDER,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=4', 'SSL'),
    );

    $menu[] = array(
        'text' => ORD_TEXT_6_WINDOW_TITLE,
        'menu_select' => 'vendors',
        'sub_menu_select' => 'purchase_receive_inventory',
        'heading' => MENU_HEADING_VENDORS,
        'rank' => 40,
        'hidden' => true,
        'security_id' => SECURITY_ID_PURCHASE_INVENTORY,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=6', 'SSL'),
    );

    $menu[] = array(
        'text' => ORD_TEXT_7_WINDOW_TITLE,
        'menu_select' => 'vendors',
        'sub_menu_select' => 'vendor_credit_memos',
        'heading' => MENU_HEADING_VENDORS,
        'rank' => 50,
        'hidden' => true,
        'security_id' => SECURITY_ID_PURCHASE_CREDIT,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=7', 'SSL'),
    );

    $menu[] = array(
        'text' => ORD_TEXT_31_WINDOW_TITLE,
        'menu_select' => 'vendors',
        'sub_menu_select' => 'vendor_debit_memos',
        'heading' => MENU_HEADING_VENDORS,
        'rank' => 60,
        'hidden' => true,
        'security_id' => SECURITY_ID_PURCHASE_DEBIT,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=31', 'SSL'),
    );

//      $menu[] = array(
//        'text' => 'General Ledger Dashboard',
//        'menu_select' => 'general_ledger',
//        'sub_menu_select' => 'gl_dashboard',
//        'heading' => MENU_HEADING_GL,
//        'rank' => 5,
//        'security_id' => SECURITY_ID_JOURNAL_ENTRY,
//        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_gl', 'SSL'),
//    );

    $menu[] = array(
        'text' => ORD_TEXT_2_WINDOW_TITLE,
        'menu_select' => 'general_ledger',
        'sub_menu_select' => 'general_journal',
        'heading' => MENU_HEADING_GL,
        'rank' => 5,
        'hidden' => true,
        'security_id' => SECURITY_ID_JOURNAL_ENTRY,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=journal', 'SSL'),
    );
    $menu[] = array(
        'text' => sprintf(BOX_STATUS_MGR, ORD_TEXT_2_WINDOW_TITLE),
        'menu_select' => 'general_ledger',
        'sub_menu_select' => 'general_journal_manager',
        'heading' => MENU_HEADING_GL,
        'rank' => 10,
        'security_id' => SECURITY_ID_JOURNAL_STATUS,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;jID=2&amp;list=1', 'SSL'),
    );

    $menu[] = array(
        'text' => BOX_GL_BUDGET,
        'menu_select' => 'general_ledger',
        'sub_menu_select' => 'budgeting',
        'heading' => MENU_HEADING_GL,
        'rank' => 50,
        'security_id' => SECURITY_ID_GL_BUDGET,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=budget', 'SSL'),
    );
    $menu[] = array(
        'text' => BOX_HEADING_ADMIN_TOOLS,
        'menu_select' => 'general_ledger',
        'sub_menu_select' => 'administrative_tools',
        'heading' => MENU_HEADING_GL,
        'rank' => 70,
       // 'set_hidden' => true,
        'security_id' => SECURITY_ID_GEN_ADMIN_TOOLS,
       // 'hidden' => $_SESSION['admin_security'][SECURITY_ID_GEN_ADMIN_TOOLS] > 3 ? false : true,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=admin_tools', 'SSL'),
    );
}
?>