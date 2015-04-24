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
//  Path: /modules/work_orders/config.php
//
// Release History
// 3.0 => 2011-01-14 - Converted from stand-alone UcBooks release
// 3.1 => 2011-04-15 - Added qty_on_allocation support, assembly support
// 3.3 => 2011-11-15 - Bug fixes, themeroller changes
// Module software version information
define('MODULE_WORK_ORDERS_VERSION',     '3.3');
// Menu Sort Positions
define('MENU_HEADING_WORK_ORDERS_ORDER',    79);
define('BOX_WORK_ORDERS_MODULE_ORDER',      50);
define('BOX_WORK_ORDERS_BUILDER_ORDER',     51);
define('BOX_WORK_ORDERS_MODULE_TASK_ORDER', 52);
// Menu Security id's
define('SECURITY_WORK_ORDERS',             193);
define('SECURITY_WORK_ORDERS_BUILDER',     190);
define('SECURITY_WORK_ORDERS_TASK',        191);
// New Database Tables
define('TABLE_WO_JOURNAL_MAIN', DB_PREFIX . 'wo_journal_main'); // work order main execution journal
define('TABLE_WO_JOURNAL_ITEM', DB_PREFIX . 'wo_journal_item'); // work order detail execution journal
define('TABLE_WO_MAIN',         DB_PREFIX . 'wo_main');         // stores work order template main records
define('TABLE_WO_STEPS',        DB_PREFIX . 'wo_steps');        // template detail records
define('TABLE_WO_TASK',         DB_PREFIX . 'wo_task');         // work order tasks
// Set the title menu
/*
$pb_headings[MENU_HEADING_WORK_ORDER_ORDER] = array(
  'text' => MENU_HEADING_WORK_ORDERS, 
  'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=index&amp;mID=cat_wo', 'SSL'),
);
*/
if (defined('MODULE_WORK_ORDERS_STATUS')) {
  // Set the menus
  $menu[] = array(
    'text'        => BOX_WORK_ORDERS_BUILDER,
     'menu_select' => 'inventory',
        'sub_menu_select' => 'work_order_builder',
    'heading'     => MENU_HEADING_INVENTORY, // MENU_HEADING_WORK_ORDERS
    'rank'        => BOX_WORK_ORDERS_BUILDER_ORDER, 
    'security_id' => SECURITY_WORK_ORDERS_BUILDER, 
    'link'        => html_href_link(FILENAME_DEFAULT, 'module=work_orders&amp;page=builder', 'SSL'),
  );
  $menu[] = array(
    'text'        => BOX_WORK_ORDERS_MODULE_TASK,
     'menu_select' => 'inventory',
        'sub_menu_select' => 'work_order_tasks',
    'heading'     => MENU_HEADING_INVENTORY, // MENU_HEADING_WORK_ORDERS
    'rank'        => BOX_WORK_ORDERS_MODULE_TASK_ORDER, 
    'security_id' => SECURITY_WORK_ORDERS_TASK, 
    'link'        => html_href_link(FILENAME_DEFAULT, 'module=work_orders&amp;page=tasks', 'SSL'),
  );
  $menu[] = array(
    'text'        => BOX_WORK_ORDERS_MODULE,
     'menu_select' => 'inventory',
        'sub_menu_select' => 'work_order_manager',
    'heading'     => MENU_HEADING_INVENTORY, // MENU_HEADING_WORK_ORDERS
    'rank'        => BOX_WORK_ORDERS_MODULE_ORDER, 
    'security_id' => SECURITY_WORK_ORDERS, 
    'link'        => html_href_link(FILENAME_DEFAULT, 'module=work_orders&amp;page=main', 'SSL'),
  );
}

?>