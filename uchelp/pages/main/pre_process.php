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
//  Path: /modules/uchelp/pages/main/pre_process.php
//
$security_level = validate_user(0, true);
/**************  include page specific files    *********************/
require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_WORKING . 'functions/uchelp.php');
/**************   page specific initialization  *************************/
if (UCHELP_FORCE_RELOAD == '1') { // load/reload db tables if forced to
  synchronize();
  write_configure('UCHELP_FORCE_RELOAD', '0');
}
$frame_id    = isset($_GET['fID'])          ? $_GET['fID']          : 'main';
$context_ref = isset($_GET['idx'])          ? $_GET['idx']          : '';
$search_text = isset($_POST['search_text']) ? $_POST['search_text'] : '';
$result = false;
$start_page = DOC_ROOT_URL;
if ($context_ref) {
  $result = $db->Execute("select doc_url from " . TABLE_UCHELP . " where doc_pos = '" . $context_ref . "'");
  if ($result->RecordCount() > 0) $start_page = $result->fields['doc_url'];
}
$frame_url = 'index.php?module=uchelp&amp;page=main';
/***************   hook for custom actions  ***************************/
$custom_path = DIR_FS_WORKING . 'custom/pages/main/extra_actions.php';
if (file_exists($custom_path)) { include($custom_path); }
/***************   Act on the action request   *************************/

/*****************   prepare to display templates  *************************/
$include_header   = false;
$include_footer   = false;
$include_tabs     = false;
$include_calendar = false;
$custom_html      = true;

switch ($frame_id) {
  default:
  case 'main': $include_template = 'template_main.php'; break;
  case 'top':  $include_template = 'template_top.php';  break;
  case 'left': 
    $include_template = 'template_left.php';
	$include_tabs     = true;
    break;
}
define('PAGE_TITLE', HEADING_TITLE);

?>