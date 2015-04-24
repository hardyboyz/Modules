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
//  Path: /modules/shipping/config.php
//
// Release History
// 3.0 => 2011-01-15 - Converted from stand-alone UcBooks release
// 3.1 => 2011-04-15 - Bug fixes, java label printing, improved xml
// 3.2 => 2011-08-01 - Bug fixes
// 3.3 => 2011-11-15 - Bug fixes, themeroller changes
// 3.4 => 2012-02-15 - bug fixes
// 3.5 => 2012-10-01 - bug fixes
// Module software version information
define('MODULE_SHIPPING_VERSION',   '3.5');
// Menu Sort Positions
// Menu Security id's (refer to master doc to avoid security setting overlap)
define('SECURITY_ID_SHIPPING_MANAGER', 13);
// New Database Tables
define('TABLE_SHIPPING_LOG', DB_PREFIX . 'shipping_log');
// Set the title menu
// Set the menus
if (defined('MODULE_SHIPPING_STATUS')) {
  $menu[] = array(
    'text'        => BOX_SHIPPING_MANAGER,
     'menu_select' => 'quality',
     'sub_menu_select' => 'shipping_manager',
    'heading'     => MENU_HEADING_QUALITY, 
    'rank'        => 5, 
    'security_id' => SECURITY_ID_SHIPPING_MANAGER, 
    'link'        => html_href_link(FILENAME_DEFAULT, 'module=shipping&amp;page=ship_mgr', 'SSL'),
  );
}

?>