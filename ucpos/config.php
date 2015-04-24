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
//  Path: /modules/ucpos/config.php
//
// Release History
// 1.0 => 2011-04-15 - Initial Release
// 1.1 => rene added starting and closing line (admin main/js_include and language)
//        bugg fix added InventoryProp and processSkuProp to js_include, replaced ORD_TEXT_19_WINDOW_TITLE with MENU_HEADING_UCPOS
// 1.2 => see change_log.txt  
// 1.4 => bug fixes
// Module software version information
define('MODULE_UCPOS_VERSION', '1.4');
// Menu Sort Positions
//define('MENU_HEADING_UCPOS_ORDER', 40);
// Menu Security id's (refer to master doc to avoid security setting overlap)

define('SECURITY_ID_POS_MGR', 39);
define('SECURITY_ID_CUSTOMER_DEPOSITS', 109);
define('SECURITY_ID_VENDOR_DEPOSITS', 110);
define('SECURITY_ID_IMPORT_BANK', 980);
// New Database Tables
// None
if (defined('MODULE_UCPOS_STATUS')) {
    /*
      // Set the title menu
      $pb_headings[MENU_HEADING_UCPOS_ORDER] = array(
      'text' => MENU_HEADING_UCPOS,
      'link' => html_href_link(FILENAME_DEFAULT, 'module=ucpos&amp;page=main&amp;mID=cat_pos', 'SSL'),
      );
     */
    // Set the menus

    $menu[] = array(
        'text' => BOX_POS_MGR,
        'menu_select' => 'banking',
        'sub_menu_select' => 'pos_pop_manager',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 53,
        'security_id' => SECURITY_ID_POS_MGR,
        'set_hidden' => true,
        'hidden' => $_SESSION['admin_security'][SECURITY_ID_GEN_ADMIN_TOOLS] > 3 ? false : true,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucpos&amp;page=pos_mgr&amp;list=1', 'SSL'),
    );
    $menu[] = array(
        'text' => BOX_CUSTOMER_DEPOSITS,
        'menu_select' => 'banking',
        'sub_menu_select' => 'customer_deposits',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 10,
        'security_id' => SECURITY_ID_CUSTOMER_DEPOSITS,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucpos&amp;page=deposit&amp;type=c', 'SSL'),
    );
    $menu[] = array(
        'text' => BOX_VENDOR_DEPOSITS,
        'menu_select' => 'banking',
        'sub_menu_select' => 'vendor_deposits',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 50,
        'security_id' => SECURITY_ID_VENDOR_DEPOSITS,
        'set_hidden' => true,
        'hidden' => $_SESSION['admin_security'][SECURITY_ID_GEN_ADMIN_TOOLS] > 3 ? false : true,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucpos&amp;page=deposit&amp;type=v', 'SSL'),
    );
    $menu[] = array(
        'text' => 'Bank Transfer',
        'menu_select' => 'banking',
        'sub_menu_select' => 'bank_transfer',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 10,
        'security_id' => '132',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=status&amp;jID=2&sub_jID=1 &amp;list=1', 'SSL'),
    );

    if (defined('MODULE_IMPORT_BANK_STATUS')) {
        $menu[] = array(
            'text' => BOX_IMPORT_BANK_MODULE,
            'menu_select' => 'banking',
            'sub_menu_select' => 'import_bank_statement',
            'heading' => MENU_HEADING_BANKING,
            'rank' => 55,
            'security_id' => SECURITY_ID_IMPORT_BANK,
            'set_hidden' => true,
            'hidden' => $_SESSION['admin_security'][SECURITY_ID_GEN_ADMIN_TOOLS] > 3 ? false : true,
            'link' => html_href_link(FILENAME_DEFAULT, 'module=import_bank&amp;page=main', 'SSL'),
        );
    }

    $menu[] = array(
        'text' => BOX_BANKING_SELECT_FOR_PAYMENT,
        'menu_select' => 'banking',
        'sub_menu_select' => 'select_for_payment',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 20,
        'security_id' => SECURITY_ID_SELECT_PAYMENT,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bulk_bills', 'SSL'),
    );
    $menu[] = array(
        'text' => 'Received money',
        'menu_select' => 'banking',
        'sub_menu_select' => 'Received_money',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 20,
        'security_id' => '131',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=status&amp;jID=2&sub_jID=2&amp;list=1', 'SSL'),
    );
    $menu[] = array(
        'text' => 'Spend money',
        'menu_select' => 'banking',
        'sub_menu_select' => 'Spend_money',
        'heading' => MENU_HEADING_BANKING,
        'rank' => 20,
        'security_id' => '127',
        'link' => html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=status&amp;jID=2&sub_jID=3&amp;list=1', 'SSL'),
    );
    
}
?>