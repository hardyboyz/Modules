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
//  Path: /modules/rma/config.php
//
// Release History
// 3.0 - Converted from UcBooks module
// 3.3 => 2011-11-15 - Bug fixes, themeroller changes
// Module software version information
define('MODULE_RMA_VERSION', '3.3');
// Menu Sort Positions
define('BOX_RMA_MODULE_ORDER', 70);
// Menu Security id's

// New Database Tables
define('TABLE_RMA',      DB_PREFIX . 'rma_module');

if (defined('MODULE_RMA_STATUS')) {
/*
  // Set the title menu
  define('MENU_HEADING_RMA_ORDER',  77);
  $pb_headings[MENU_HEADING_RMA_ORDER] = array(
    'text' => MENU_HEADING_RMA, 
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=index&amp;mID=cat_rma', 'SSL'),
  );
*/
  // Set the menu
  
}

?>