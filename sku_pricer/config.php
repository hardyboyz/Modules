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
//  Path: /modules/sku_pricer/config.php
//
// Release History
// 1.0 => 2011-05-26 - Initial Release
// Module software version information
define('MODULE_SKU_PRICER_VERSION', '1.0');
// Menu Sort Positions
// Security id's
define('SECURITY_ID_SKU_PRICER', 999);

// New Database Tables
// Menu Locations
if (defined('MODULE_SKU_PRICER_STATUS')) {
    $menu[] = array(
        'text' => BOX_SKU_PRICER_TITLE,
        'menu_select' => 'quality',
        'sub_menu_select' => 'sku_Pricer',
        'heading' => MENU_HEADING_QUALITY,
        'rank' => 159,
        'security_id' => SECURITY_ID_SKU_PRICER,
        'link' => html_href_link(FILENAME_DEFAULT, 'module=sku_pricer&amp;page=main', 'SSL'),
    );
    
}


?>