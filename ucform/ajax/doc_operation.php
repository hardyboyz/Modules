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
//  Path: /modules/ucform/ajax/doc_operation.php
//
/**************   Check user security   *****************************/
$xml = NULL;
$security_level = validate_ajax_user(SECURITY_ID_UCFORM);
/**************  include page specific files    *********************/
require_once(DIR_FS_MODULES . 'ucform/functions/ucform.php');

/**************   page specific initialization  *************************/
$id     = (int)$_GET['id'];
$action = $_GET['action'];

if (!isset($_GET['id'])) die;
$doc_details = $db->Execute("select * from " . TABLE_UCFORM . " where id = '" . $id . "'");
switch ($action) {
  case 'bookmark':
	$sql_array = array(
	  'admin_id' => $_SESSION['admin_id'],
	  'doc_id'   => $id,
	  'type'     => 'b', // bookmark
	  'params'   => '1', // true
	);
	db_perform(TABLE_DC_PROPERTIES, $sql_array, 'insert');
	$ajax_text  = DOC_CTL_JS_BOOKMARK_SET;
    break;
  case 'del_bookmark':
	$result = $db->Execute("delete from " . TABLE_DC_PROPERTIES . " 
		where doc_id = '" . $id . "' and type = 'b' and admin_id = '" . $_SESSION['admin_id'] . "'");
	$ajax_text = DOC_CTL_JS_BOOKMARK_REMOVE;
    break;
  case 'lock':
	$sql_array = array(
	  'admin_id' => $_SESSION['admin_id'],
	  'doc_id'   => $id,
	  'type'     => 'l', // Lock (lower case L)
	  'params'   => '1', // true
	);
	db_perform(TABLE_DC_PROPERTIES, $sql_array, 'insert');
	$ajax_text = DOC_CTL_JS_DOC_LOCKED;
    break;
  case 'del_lock':
	$db->Execute("delete from " . TABLE_DC_PROPERTIES . " where doc_id = '" . $id . "' and type = 'l'");
	$ajax_text  = DOC_CTL_JS_DOC_UNLOCKED;
    break;
  case 'delete':
	$file_path = PF_DIR_MY_REPORTS . 'pf_' . $id;
	$db->Execute("delete from " . TABLE_UCFORM . " where id = '" . $id . "'");
	if (file_exists($file_path)) unlink($file_path);
	$id = $doc_details->fields['parent_id']; // set the id to the parent to display refreshed page
	$ajax_text = UCFORM_JS_RPT_DELETED;
	break;
  default: die;
}
// put the output together
$xml .= "\t" . xmlEntry("docID", $id);
$xml .= "\t" . xmlEntry("msg",   $ajax_text);

echo createXmlHeader() . $xml . createXmlFooter();
die;
?>