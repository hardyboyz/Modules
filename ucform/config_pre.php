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
//  Path: /modules/ucform/config.php
//
// Release History
// 3.0 => 2011-01-15 - Converted from stand-alone UcBooks release
// 3.1 => 2011-04-15 - Bug fixes, moved custom operation to modules
// 3.2 => 2011-08-01 - Bug fixes
// 3.3 => 2011-11-15 - Bug fixes, themeroller changes
// 3.4 => 2012-02-15 - bug fixes, added dynamic images, dynamic bar codes to forms
// 3.5 => 2012-10-01 - bug fixes
// Module software version information
define('MODULE_UCFORM_VERSION', '3.5');
// Menu Sort Positions
// Menu Security id's (refer to master doc to avoid security setting overlap)
define('SECURITY_ID_UCFORM', 3); // same as SECURITY_ID_REPORTS
// New Database Tables
define('TABLE_UCFORM', DB_PREFIX . 'ucform');

if (defined('MODULE_UCFORM_STATUS')) {
    // Set the title menu
    // Set the menus
    $menu[] = array(
        //'text' => TEXT_REPORTS,
        'text' => "",
        'menu_select' => 'tools',
        'sub_menu_select' => 'tools_reports',
        'heading' => MENU_HEADING_TOOLS,
        
        'rank' => 25,
        'security_id' => SECURITY_ID_UCFORM,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucform&amp;page=main', 'SSL'),
    );
    if (defined('MODULE_CONTACTS_STATUS')) { // add reports menus
        $menu[] = array(
            'text' => TEXT_REPORTS,
            'menu_select' => 'customers',
            'sub_menu_select' => 'customers_reports',
            'heading' => MENU_HEADING_CUSTOMERS,
            'rank' => 99,
            'hidden' => true,
            'security_id' => SECURITY_ID_UCFORM,
            'link' => html_href_link(FILENAME_DEFAULT, 'module=ucform&amp;page=main&amp;tab=cust', 'SSL'),
        );
        $menu[] = array(
            'text' => TEXT_REPORTS,
            'menu_select' => 'employees',
            'sub_menu_select' => 'employees_reports',
            'heading' => MENU_HEADING_EMPLOYEES,
            'rank' => 99,
            'hidden' => true,
            'security_id' => SECURITY_ID_UCFORM,
            'link' => html_href_link(FILENAME_DEFAULT, 'module=ucform&amp;page=main&amp;tab=hr', 'SSL'),
        );
        $menu[] = array(
            'text' => TEXT_REPORTS,
            'menu_select' => 'vendors',
            'sub_menu_select' => 'vendors_reports',
            'hidden' => true,
            'heading' => MENU_HEADING_VENDORS,
            'rank' => 99,
            'security_id' => SECURITY_ID_UCFORM,
            'link' => html_href_link(FILENAME_DEFAULT, 'module=ucform&amp;page=main&amp;tab=vend', 'SSL'),
        );
    }
    if (defined('MODULE_INVENTORY_STATUS')) {
        $menu[] = array(
            'text' => TEXT_REPORTS,
            'menu_select' => 'inventory',
            'sub_menu_select' => 'inventory_reports',
            'hidden' => true,
            'heading' => MENU_HEADING_INVENTORY,
            'rank' => 99,
            'security_id' => SECURITY_ID_UCFORM,
            'link' => html_href_link(FILENAME_DEFAULT, 'module=ucform&amp;page=main&amp;tab=inv', 'SSL'),
        );
    }
    if (defined('MODULE_UCBOOKS_STATUS')) {
        $menu[] = array(
            'text' => TEXT_REPORTS,
            'menu_select' => 'banking',
            'sub_menu_select' => 'banking_reports',
            'hidden' => true,
            'heading' => MENU_HEADING_BANKING,
            'rank' => 99,
            'security_id' => SECURITY_ID_UCFORM,
            'link' => html_href_link(FILENAME_DEFAULT, 'module=ucform&amp;page=main&amp;tab=bnk', 'SSL'),
        );
        $menu[] = array(
            'text' => TEXT_REPORTS,
            'menu_select' => 'general_ledger',
            'sub_menu_select' => 'general_ledger_reports',
            'hidden' => true,
            'heading' => MENU_HEADING_GL,
            'rank' => 99,
            'security_id' => SECURITY_ID_UCFORM,
            'link' => html_href_link(FILENAME_DEFAULT, 'module=ucform&amp;page=main&amp;tab=gl', 'SSL'),
        );
    }
}
?>