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
//  Path: /modules/contacts/defaults.php
//
// default directory for contact attachments
define('CONTACTS_DIR_ATTACHMENTS',  DIR_FS_MY_FILES . $_SESSION['company'] . '/contacts/main/');
// set the maximum number of subitems to import if importing in csv format
define('MAX_IMPORT_CSV_ITEMS',5);

$employee_types = array(
  'e' => TEXT_EMPLOYEE,
  's' => TEXT_SALES_REP,
  'b' => TEXT_BUYER,
);

$project_cost_types = array(
 'LBR' => COST_TYPE_LBR,
 'MAT' => COST_TYPE_MAT,
 'CNT' => COST_TYPE_CNT,
 'EQT' => COST_TYPE_EQT,
 'OTH' => COST_TYPE_OTH,
);

$crm_actions = array(
  ''     => TEXT_NONE,
  'new'  => TEXT_NEW_CALL,
  'ret'  => TEXT_RETURN_CALL,
  'flw'  => TEXT_FOLLOW_UP_CALL,
  'inac' => TEXT_INACTIVE,
  'lead' => TEXT_NEW_LEAD,
);

?>
