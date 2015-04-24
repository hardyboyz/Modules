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
//  Path: /modules/ucform/ajax/field_load.php
//
/**************   Check user security   *****************************/
$xml = NULL;
$security_level = validate_ajax_user(SECURITY_ID_UCFORM);
/**************  include page specific files    *********************/
require_once(DIR_FS_MODULES . 'ucform/functions/ucform.php');

/**************   page specific initialization  *************************/
$report         = new objectInfo();
$report->tables = array();
$i              = 1;
$runaway        = 0; // just in case
while(true) {
  if (isset($_GET['t' . $i])) $report->tables[] = new objectInfo(array('tablename' => $_GET['t' . $i]));
    else break;
  $i++;
  if ($runaway++ > 100) die;
}
if (sizeof($report->tables) < 1) {
  echo createXmlHeader() . xmlEntry('error', UCFORM_NO_TABLES_PASSED) . createXmlFooter();
  die;
}
$report->special_class = $_GET['sp'] ? $_GET['sp'] : false;

$kFields = CreateSpecialDropDown($report);
if (sizeof($kFields) > 0) foreach ($kFields as $value) {
  $xml .= "<option>\n";
  $xml .= "\t" . xmlEntry('id',   $value['id']);
  $xml .= "\t" . xmlEntry('text', $value['text']);
  $xml .= "</option>\n";
}
$xml .= xmlEntry("message", 'Success select length = ' . sizeof($kFields));

echo createXmlHeader() . $xml . createXmlFooter();
die;
?>