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
//  Path: /modules/doc_ctl/config.php
//

define('NAME_TRIM_LENGTH', '24'); // TBD - needs to move to admin constant
// 0.1 => 2010-09-01 - Converted from stand-alone UcBooks release
// 1.0 => 2011-11-15 - Initial module release, themeroller compatible
// Module software version information
define('MODULE_DOC_CTL_VERSION', '1.0');
// Menu Sort Positions
define('BOX_DOC_CTL_ORDER', 10);
// Security id's
define('SECURITY_ID_DOC_CONTROL', 210);
define('SECURITY_ID_IMPORT_EXPORT', 2);
define('SECURITY_ID_BACKUP', 18);
define('SECURITY_ID_SEARCH', 4);
// New Database Tables
define('TABLE_DC_DOCUMENT', DB_PREFIX . 'doc_ctl');
// Set the title menu
// Menu Locations
//$menu[] = array(
//    'text' => 'Tools Dashboard',
//    'menu_select' => 'quality',
//    'sub_menu_select' => 'Tools_Dashboard',
//    'heading' => MENU_HEADING_QUALITY,
//    'rank' => 5,
//    'security_id' => SECURITY_ID_CUSTOMER_RECEIPTS,
//    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_manage', 'SSL'),
//);
$menu[] = array(
    'text' => BOX_DOC_CTL_MODULE,
    'menu_select' => 'quality',
    'sub_menu_select' => 'document_control',
    'heading' => MENU_HEADING_QUALITY,
    'rank' => BOX_DOC_CTL_ORDER,
    'security_id' => SECURITY_ID_DOC_CONTROL,
    'set_hidden' => true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=doc_ctl&amp;page=main', 'SSL'),
);
$menu[] = array(
    'text' => BOX_IMPORT_EXPORT,
    'heading' => MENU_HEADING_QUALITY,
    'menu_select' => 'quality',
    'sub_menu_select' => 'import_export',
    'rank' => 50,
    'security_id' => SECURITY_ID_IMPORT_EXPORT,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=import_export', 'SSL'),
);

$menu[] = array(
    'text' => BOX_HEADING_BACKUP,
    'heading' => MENU_HEADING_QUALITY,
    'menu_select' => 'quality',
    'sub_menu_select' => 'company_backup',
    'rank' => 95,
    'set_hidden' => true,
    'security_id' => SECURITY_ID_BACKUP,
    'hidden' => $_SESSION['admin_security'][SECURITY_ID_BACKUP] > 3 ? false : true,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=backup', 'SSL'),
);

$menu[] = array(
    'text' => TEXT_SEARCH,
    'menu_select' => 'quality',
    'sub_menu_select' => 'search',
    'heading' => MENU_HEADING_QUALITY,
    'rank' => 15,
    'security_id' => SECURITY_ID_SEARCH,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=search&amp;journal_id=-1', 'SSL'),
);

//Menu for tax
$menu[] = array(
    'text' => 'Sales Tax Authorities',
    'heading' => MENU_HEADING_QUALITY,
    'menu_select' => 'quality',
    'sub_menu_select' => 'sales_tax_authorities',
    'rank' => 50,
    'security_id' => SECURITY_ID_IMPORT_EXPORT,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=admin&action_type=sales_tax_authorities', 'SSL'),
);
$menu[] = array(
    'text' => 'Purchase Tax Authorities',
    'heading' => MENU_HEADING_QUALITY,
    'menu_select' => 'quality',
    'sub_menu_select' => 'purchase_tax_authorities',
    'rank' => 50,
    'security_id' => SECURITY_ID_IMPORT_EXPORT,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=admin&action_type=purchase_tax_authorities', 'SSL'),
);
$menu[] = array(
    'text' => 'Sales Tax Rates',
    'heading' => MENU_HEADING_QUALITY,
    'menu_select' => 'quality',
    'sub_menu_select' => 'sales_tax_rates',
    'rank' => 50,
    'security_id' => SECURITY_ID_IMPORT_EXPORT,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=admin&action_type=sales_tax_rates', 'SSL'),
);
$menu[] = array(
    'text' => 'Purchase Tax Rates',
    'heading' => MENU_HEADING_QUALITY,
    'menu_select' => 'quality',
    'sub_menu_select' => 'purchase_tax_rates',
    'rank' => 50,
    'security_id' => SECURITY_ID_IMPORT_EXPORT,
    'link' => html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=admin&action_type=purchase_tax_rates', 'SSL'),
);
?>