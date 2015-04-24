<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/ajax/opencart.php
//
/**************   Check user security   *****************************/
$security_level = validate_ajax_user();
/**************  include page specific files    *********************/
require_once(DIR_FS_MODULES . 'inventory/defaults.php'); 
require_once(DIR_FS_MODULES . 'shipping/defaults.php'); 
require_once(DIR_FS_MODULES . 'inventory/functions/inventory.php');
require_once(DIR_FS_MODULES . 'opencart/functions/opencart.php');
require_once(DIR_FS_MODULES . 'opencart/classes/opencart.php');
/***************   hook for custom actions  ***************************/
$custom_path = DIR_FS_MODULES.'opencart/custom/pages/main/extra_actions.php';
if (file_exists($custom_path)) { include_once($custom_path); }
/**************   page specific initialization  *************************/
$xml     = NULL;
$message = array();
$error   = array();
$runaway = 0;
switch ($_GET['action']) {
  case 'product_cnt':
	$result = $db->Execute("select id from ".TABLE_INVENTORY." where catalog = '1' and inactive = '0'");
	while (!$result->EOF) {
	  $xml .= '<Product>'.chr(10);
	  $xml .= xmlEntry('iID', $result->fields['id']);
	  $xml .= '</Product>'.chr(10);
	  $result->MoveNext();
	}
	break;

  case 'product_upload':
	$id    = $_GET['iID'];
	$image = $_GET['image'] == '1' ? true : false;
	$cart  = new opencart();
	if (!$cart->submitXML($id, 'product_ul', true, $image)) {
	  foreach ($messageStack->errors as $value) {
	  	$msg     = strip_tags($value['text']);
	  	$msg     = str_replace('&nbsp;', '', $msg);
	  	$error[] = $msg;
	  }
	  $xml .= xmlEntry('error', implode("\n", $error));
	} else {
	  $xml .= xmlEntry('result', 'success');
	}
	break;

  default: die;
}

if (sizeof($message) > 0) $xml .= xmlEntry('message', implode("\n",$message));
echo createXmlHeader() . $xml . createXmlFooter();
die;
?>