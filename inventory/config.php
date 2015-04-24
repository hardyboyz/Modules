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
//  Path: /modules/inventory/config.php
//
// Release History
// 3.0 => 2011-01-15 - Converted from stand-alone UcBooks release
// 3.1 => 2011-04-15 - Bug fixes
// 3.2 => 2011-08-01 - added vendor price seets, bug fixes
// 3.3 => 2011-11-15 - bug fixes, themeroller changes
// 3.4 => 2012-02-15 - bug fixes
// 3.5 => 2012-10-01 - bug fixes
// Module software version information
define('MODULE_INVENTORY_VERSION', '3.5');
// Menu Sort Positions
define('MENU_HEADING_INVENTORY_ORDER', 30);
// Menu Security id's (refer to master doc to avoid security setting overlap)
define('SECURITY_ID_PRICE_SHEET_MANAGER', 88);
define('SECURITY_ID_VEND_PRICE_SHEET_MGR', 89);
define('SECURITY_ID_ADJUST_INVENTORY', 152);
define('SECURITY_ID_ASSEMBLE_INVENTORY', 153);
define('SECURITY_ID_MAINTAIN_INVENTORY', 151);
define('SECURITY_ID_TRANSFER_INVENTORY', 156);
define('SECURITY_ID_CATEGORY_MANAGER', 949);
// New Database Tables
define('TABLE_INVENTORY', DB_PREFIX . 'inventory');
define('TABLE_LABEL', DB_PREFIX . 'label');
define('TABLE_PRODUCT_CATEGORY', DB_PREFIX . 'product_category');
define('TABLE_INVENTORY_ASSY_LIST', DB_PREFIX . 'inventory_assy_list');
define('TABLE_INVENTORY_COGS_OWED', DB_PREFIX . 'inventory_cogs_owed');
define('TABLE_INVENTORY_COGS_USAGE', DB_PREFIX . 'inventory_cogs_usage');
define('TABLE_INVENTORY_HISTORY', DB_PREFIX . 'inventory_history');
define('TABLE_INVENTORY_MS_LIST', DB_PREFIX . 'inventory_ms_list');
define('TABLE_INVENTORY_SPECIAL_PRICES', DB_PREFIX . 'inventory_special_prices');
define('TABLE_PRICE_SHEETS', DB_PREFIX . 'price_sheets');
// Set the title menu
$pb_headings[MENU_HEADING_INVENTORY_ORDER] = array(
    'text' => MENU_HEADING_INVENTORY,
    'menu_select' => 'inventory',
    'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&page=main&list=1', 'SSL'),
);
// Set the menus
$menu[] = array(
    'text' => BOX_INV_NEW,
    'menu_select' => 'inventory',
    'sub_menu_select' => 'new_inventory',
    'heading' => MENU_HEADING_INVENTORY,
    'rank' => 1,
    'hide' => true,
    'security_id' => SECURITY_ID_MAINTAIN_INVENTORY,
//  'hidden'      => $_SESSION['admin_security'][SECURITY_ID_MAINTAIN_INVENTORY] > 1 ? false : true,
    'hidden' => true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=main&amp;action=new', 'SSL'),
);
//$menu[] = array(
//    'text' => 'Inventory Dashboard',
//    'menu_select' => 'inventory',
//    'sub_menu_select' => 'Inventory_Dashboard',
//    'heading' => MENU_HEADING_INVENTORY,
//    'rank' => 5,
//    'security_id' => SECURITY_ID_MAINTAIN_INVENTORY,
//    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_inv', 'SSL'),
//);
$menu[] = array(
    'text' => BOX_INV_MAINTAIN,
    'menu_select' => 'inventory',
    'sub_menu_select' => 'inventory_manager',
    'heading' => MENU_HEADING_INVENTORY,
    'rank' => 5,
    'security_id' => SECURITY_ID_MAINTAIN_INVENTORY,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=main&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => ORD_TEXT_16_WINDOW_TITLE,
    'menu_select' => 'inventory',
    'sub_menu_select' => 'adjustments',
    'heading' => MENU_HEADING_INVENTORY,
    'rank' => 15,
    'security_id' => SECURITY_ID_ADJUST_INVENTORY,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=adjustments&jID=16&list=1', 'SSL'),
);
$menu[] = array(
    'text' => ORD_TEXT_14_WINDOW_TITLE,
    'menu_select' => 'inventory',
    'sub_menu_select' => 'assemblies',
    'heading' => MENU_HEADING_INVENTORY,
    'rank' => 20,
    'security_id' => SECURITY_ID_ASSEMBLE_INVENTORY,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=assemblies', 'SSL'),
);
if (ENABLE_MULTI_BRANCH)
    $menu[] = array(
        'text' => BOX_INV_TRANSFER,
        'menu_select' => 'inventory',
        'sub_menu_select' => 'transfer_inventory',
        'heading' => MENU_HEADING_INVENTORY,
        'rank' => 80,
        'security_id' => SECURITY_ID_TRANSFER_INVENTORY,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=transfer&jID=16&sub_jID=1&list=1', 'SSL'),
    );
$menu[] = array(
    'text' => BOX_SALES_PRICE_SHEETS,
    'menu_select' => 'customers',
    'sub_menu_select' => 'customer_price_sheets',
    'heading' => MENU_HEADING_CUSTOMERS,
    'rank' => 65,
    'security_id' => SECURITY_ID_PRICE_SHEET_MANAGER,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=price_sheets&amp;type=c&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => BOX_PURCHASE_PRICE_SHEETS,
    'menu_select' => 'vendors',
    'sub_menu_select' => 'vendor_price_sheets',
    'heading' => MENU_HEADING_VENDORS,
    'rank' => 65,
    'security_id' => SECURITY_ID_VEND_PRICE_SHEET_MGR,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=price_sheets&amp;type=v&amp;list=1', 'SSL'),
);
$menu[] = array(
    'text' => 'Category Manager',
    'menu_select' => 'inventory',
    'sub_menu_select' => 'cat_manager',
    'heading' => MENU_HEADING_INVENTORY,
    'rank' => 20,
    'security_id' => SECURITY_ID_CATEGORY_MANAGER,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=cat_manager', 'SSL'),
);
?>