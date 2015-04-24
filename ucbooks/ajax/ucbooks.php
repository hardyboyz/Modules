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
//  Path: /modules/contacts/ajax/contacts.php
//
/**************   Check user security   *****************************/
$security_level = validate_ajax_user();
/**************  include page specific files    *********************/
require_once(DIR_FS_MODULES . 'ucbooks/defaults.php');
/**************   page specific initialization  *************************/
$xml     = NULL;
$message = array();
$action  = $_GET['action'];
 
switch ($action) {

	default: die;
}

if (sizeof($message) > 0) $xml .= xmlEntry('message', implode("\n",$message));
echo createXmlHeader() . $xml . createXmlFooter();
die;
?>