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
//  Path: /modules/ucounting/config.php
//
// Release History
// 3.0 => 2011-01-15 - Converted from stand-alone UcBooks release
// 3.1 => 2011-04-15 - Bug fixes
// 3.2 => 2011-08-01 - Bug fixes, added roles
// 3.3 => 2011-11-15 - Bug fixes, theme re-design, jqueryUI integration
// 3.4 => 2012-02-15 - bug fixes, Google Chart support
// 3.5 => 2012-10-01 - bug fixes
// Module software version information
define('MODULE_UCOUNTING_VERSION', '3.5');
// Menu Sort Positions

// Menu Security id's (refer to master doc to avoid security setting overlap)
define('SECURITY_ID_USERS', 1);

define('SECURITY_ID_ROLES', 5);
define('SECURITY_ID_HELP', 6);
define('SECURITY_ID_MY_PROFILE', 7);
define('SECURITY_ID_CONFIGURATION', 11); // admin for all modules
define('Sales_Dashboard', 121); // add by shaheen for set sequrity to salse dashboard
define('Purchases_Dashboard', 122); // add by shaheen for set sequrity to purchase dashboard
define('home_dashboard', 123); // add by shaheen for set sequrity to Home dashboard

define('SECURITY_ID_ENCRYPTION', 20);
// New Database Tables
define('TABLE_AUDIT_LOG', DB_PREFIX . 'audit_log');
define('TABLE_CONFIGURATION', DB_PREFIX . 'configuration');
define('TABLE_CURRENCIES', DB_PREFIX . 'currencies');
define('TABLE_CURRENT_STATUS', DB_PREFIX . 'current_status');
define('TABLE_DATA_SECURITY', DB_PREFIX . 'data_security');
define('TABLE_EXTRA_FIELDS', DB_PREFIX . 'xtra_fields');
define('TABLE_EXTRA_TABS', DB_PREFIX . 'xtra_tabs');
define('TABLE_USERS', DB_PREFIX . 'users');
define('TABLE_USERS_PROFILES', DB_PREFIX . 'users_profiles');
// TBD Tables no longer in use, but need to verify conversion before delete
define('TABLE_IMPORT_EXPORT', DB_PREFIX . 'import_export');
define('TABLE_REPORTS', DB_PREFIX . 'reports');
define('TABLE_REPORT_FIELDS', DB_PREFIX . 'report_fields');
define('TABLE_PROJECT_VERSION', DB_PREFIX . 'project_version');
// Set the title menu
$pb_headings[0] = array(
    'text' => TEXT_HOME,
    'menu_select' => 'home',
    'link' => "index.php",
//  'link' => html_href_link(FILENAME_DEFAULT),
    'icon' => html_icon('actions/go-home.png', TEXT_HOME, 'small'),
);


$menu[] = array(
    'text' => 'Dashboard',
    'menu_select' => 'home',
    'sub_menu_select' => 'home_dashboard',
    'heading' => TEXT_HOME,
    'rank' => 1,
    'security_id' => 123,
    'link' => 'index.php',
    'params' => 'target="_blank"',
);

$menu[] = array(
    'text' => 'Setup Wizard',
    'menu_select' => 'home',
    'sub_menu_select' => 'setup_wizard',
    'heading' => TEXT_HOME,
    'rank' => 1,
    'security_id' => 124,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=first_dashboard', 'SSL'),
    'params' => 'target="_blank"',
);




$pb_headings[999] = array(
    'text' => TEXT_LOGOUT,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;action=logout', 'SSL'),
    'icon' => html_icon('actions/system-log-out.png', TEXT_LOGOUT, 'small'),
);
// Set the menus
$menu[] = array(
    'text' => TEXT_HELP,
    'menu_select' => 'company',
    'sub_menu_select' => 'help',
    'heading' => MENU_HEADING_COMPANY,
    'rank' => 1,
    'set_hidden' => true,
    'security_id' => SECURITY_ID_HELP,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=uchelp&amp;page=main', 'SSL'),
    'params' => 'target="_blank"',
);
//$menu[] = array(
//  'text'        => BOX_HEADING_PROFILE,
//  'menu_select' => 'company',
//  'sub_menu_select' => 'my_profile',
//  'heading'     => MENU_HEADING_COMPANY,
//  'rank'        => 5,
//  'security_id' => SECURITY_ID_MY_PROFILE,
//  'link'        => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=profile', 'SSL'),
//);


//$menu[] = array(
//    'text' => 'Setup Dashboard',
//    'menu_select' => 'company',
//    'sub_menu_select' => 'Setup_Dashboard',
//    'heading' => MENU_HEADING_COMPANY,
//    'rank' => 90,
//    'security_id' => SECURITY_ID_USERS,
//    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_company', 'SSL'),
//);

$menu[] = array(
    'text' => BOX_HEADING_CONFIGURATION,
    'heading' => MENU_HEADING_COMPANY,
    'menu_select' => 'company',
    'sub_menu_select' => 'module_administration',
    'rank' => 10,
    'set_hidden' => true,
    'security_id' => SECURITY_ID_CONFIGURATION,
    'hidden' => $_SESSION['admin_security'][SECURITY_ID_CONFIGURATION] > 0 ? false : true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin', 'SSL'),
);
if (DEBUG)
    $menu[] = array(
        'text' => BOX_HEADING_DEBUG_DL,
        'heading' => MENU_HEADING_TOOLS,
        'rank' => 0,
        'security_id' => SECURITY_ID_CONFIGURATION,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;action=debug', 'SSL'),
    );
if (ENABLE_ENCRYPTION)
    $menu[] = array(
        'text' => BOX_HEADING_ENCRYPTION,
        'heading' => MENU_HEADING_TOOLS,
        'rank' => 1,
        'security_id' => SECURITY_ID_ENCRYPTION,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=encryption', 'SSL'),
    );






$menu[] = array(
    'text' => BOX_HEADING_USERS,
    'menu_select' => 'company',
    'sub_menu_select' => 'users',
    'heading' => MENU_HEADING_COMPANY,
    'rank' => 90,
    'security_id' => SECURITY_ID_USERS,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=users&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => BOX_HEADING_ROLES,
    'menu_select' => 'company',
    'sub_menu_select' => 'roles',
    'heading' => MENU_HEADING_COMPANY,
    'rank' => 85,
    'security_id' => SECURITY_ID_ROLES,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=roles&amp;list=1', 'SSL'),
);

if(UOM == 1){
$menu[] = array(
    'text' => 'UOM Manager',
    'menu_select' => 'company',
    'sub_menu_select' => 'uom_manager',
    'heading' => MENU_HEADING_COMPANY,
    'rank' => 171,
    'security_id' => 6,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=uom', 'SSL'),
);
}

$menu[] = array(
    'text' => 'Select Print Template',
    'menu_select' => 'company',
    'sub_menu_select' => 'select_print_templet',
    'heading' => MENU_HEADING_COMPANY,
    'rank' => 172,
    'security_id' => 8,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=print_template', 'SSL'),
);
?>