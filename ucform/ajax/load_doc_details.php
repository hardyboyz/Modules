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
//  Path: /modules/ucform/ajax/load_doc_details.php
//
/**************   Check user security   *****************************/
$xml = NULL;
$security_level = validate_ajax_user(SECURITY_ID_UCFORM);
/**************  include page specific files    *********************/
require_once(DIR_FS_MODULES . 'ucform/defaults.php');
require_once(DIR_FS_MODULES . 'ucform/functions/ucform.php');
/**************   page specific initialization  *************************/
// load the sort fields
$_GET['sf'] = $_POST['sort_field'] ? $_POST['sort_field'] : $_GET['sf'];
$_GET['so'] = $_POST['sort_order'] ? $_POST['sort_order'] : $_GET['so'];
$fieldset_content = 'NULL';
$id = (int)$_GET['id'];
if (!isset($_GET['id'])) die;
$doc_details = $db->Execute("select * from " . TABLE_UCFORM . " where id = '" . $id . "'");
if ($id == 0 || $doc_details->fields['doc_type'] == '0') { // folder
  $dir_path     = TEXT_PATH . ': /' . build_dir_path($id);
  $result       = html_heading_bar(array(), $_GET['sf'], $_GET['so'], array(' ', $dir_path, TEXT_ACTION));
  $list_header  = $result['html_code'];
  $field_list   = array('id', 'doc_type', 'doc_title','doc_ext', 'security');
  //$query_raw    = "select " . implode(', ', $field_list)  . " from " . TABLE_UCFORM . " where parent_id = '" . $id . "'";
  $query_raw    = "select " . implode(', ', $field_list)  . " from " . TABLE_UCFORM . " where doc_group LIKE '%" .$doc_details->fields['doc_group']. "%' and doc_type != '0' ";
  $query_result = $db->Execute($query_raw);
  $query_split  = new splitPageResults($_GET['list'], MAX_DISPLAY_SEARCH_RESULTS, $query_raw, $query_numrows);
  include (DIR_FS_MODULES . 'ucform/pages/main/tab_folder.php');
} else { // load document details
  include (DIR_FS_MODULES . 'ucform/pages/main/tab_report.php');
}
$html  = "<div>";
$html .= $fieldset_content;
$html .= "</div>";

$xml  .= "\t" . xmlEntry("htmlContents", $html);
echo createXmlHeader() . $xml . createXmlFooter();
die;


function get_reports(){
    
}