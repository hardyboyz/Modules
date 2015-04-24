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
//  Path: /modules/ucform/ajax/load_email_msg.php
//

/**************   Check user security   *****************************/
$security_level = validate_ajax_user(SECURITY_ID_UCFORM);
/**************  include page specific files    *********************/
require_once(DIR_FS_MODULES . 'ucform/defaults.php');
require_once(DIR_FS_MODULES . 'ucform/functions/ucform.php');

/**************   page specific initialization  *************************/
$rID = $_GET['rID'];
if (!$rID) die;

$result  = $db->Execute("select doc_title from " . TABLE_UCFORM . " where id = '" . $rID . "'");
$subject = $result->fields['doc_title'] . ' ' . TEXT_FROM . ' ' . COMPANY_NAME;
$report  = get_report_details($rID);

if (!$report->emailmessage) {
  $text = sprintf(UCFORM_EMAIL_BODY, $result->fields['doc_title'], COMPANY_NAME);
} else {
  $text = TextReplace($report->emailmessage);
}

$xml  = '';
$xml .= "\t" . xmlEntry("subject", $subject);
$xml .= "\t" . xmlEntry("text",    $text);

// error check

echo createXmlHeader() . $xml . createXmlFooter();
die;
?>