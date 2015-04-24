<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2007-2008 UcSoft, LLC                          |
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
//  Path: /modules/assets/pages/admin/pre_process.php
//

$menu_select = 'company';
$sub_menu_select = 'module_administration';



$security_level = validate_user(SECURITY_ID_CONFIGURATION);
/**************  include page specific files    *********************/
gen_pull_language($module, 'admin');
gen_pull_language('ucounting', 'admin');
require_once(DIR_FS_MODULES . 'ucounting/functions/ucounting.php');
require_once(DIR_FS_WORKING . 'classes/install.php');
require_once(DIR_FS_WORKING . 'classes/tabs.php');
require_once(DIR_FS_WORKING . 'classes/fields.php');
/**************   page specific initialization  *************************/
$error  = false; 
$action = (isset($_GET['action']) ? $_GET['action'] : $_POST['todo']);
$install= new assets_admin();
$tabs   = new tabs();
$fields = new fields();
/***************   Act on the action request   *************************/
switch ($action) {
  case 'insert':      $subject_module->btn_insert(); break;
  case 'save':        $subject_module->btn_save();   break;
  case 'delete':
	if ($security_level < 4) {
	  $messageStack->add_session(ERROR_NO_PERMISSION,'error');
	  break;
	}
    $subject = $_POST['subject'];
    $id      = $_POST['rowSeq'];
	if (!$subject || !$id) break;
    $$subject->btn_delete($id);
	break;
  case 'update':      $subject_module->btn_update(); break;
  case 'go_first':    $_GET['list'] = 1;             break;
  case 'go_previous': $_GET['list']--;               break;
  case 'go_next':     $_GET['list']++;               break;
  case 'go_last':     $_GET['list'] = 99999;         break;
  case 'search':
  case 'search_reset':
  case 'go_page':
  default:
}

/*****************   prepare to display templates  *************************/
// build some general pull down arrays
$sel_yes_no = array(
 array('id' => '0', 'text' => TEXT_NO),
 array('id' => '1', 'text' => TEXT_YES),
);

$include_header   = true;
$include_footer   = true;
$include_tabs     = true;
$include_calendar = false;
$include_template = 'template_main.php';
define('PAGE_TITLE', BOX_ASSETS_ADMIN);

?>