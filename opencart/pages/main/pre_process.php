<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/pages/main/pre_process.php
//
$security_level = validate_user(SECURITY_ID_OPENCART_INTERFACE);
if ($_GET['module'] == "opencart" && $_GET['page']=='main') {
    $menu_select = "company";
    $sub_menu_select = "cart_interface";
}
/**************  include page specific files    *********************/
gen_pull_language('shipping');
require_once(DIR_FS_MODULES . 'inventory/defaults.php'); 
require_once(DIR_FS_MODULES . 'shipping/defaults.php'); 
require_once(DIR_FS_WORKING . 'functions/opencart.php'); 
require_once(DIR_FS_MODULES . 'inventory/functions/inventory.php'); 
require_once(DIR_FS_WORKING . 'classes/opencart.php'); 
/**************   page specific initialization  *************************/
$error     = false;
$ship_date = $_POST['ship_date']    ? gen_db_date($_POST['ship_date']) : date('Y-m-d');
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
$upXML     = new opencart();
/***************   hook for custom actions  ***************************/
$custom_path = DIR_FS_MODULES . 'opencart/custom/pages/main/extra_actions.php';
if (file_exists($custom_path)) { include_once($custom_path); }
/***************   Act on the action request   *************************/
switch ($action) {
  case 'upload':
	$id    = db_prepare_input($_POST['rowSeq']);
	if ($upXML->submitXML($id, 'product_ul')) gen_add_audit_log(OPENCART_UPLOAD_PRODUCT, $upXML->sku);
	break;
  case 'sync':
    $upXML->delete_opencart = $_POST['delete_opencart'] ? true : false;
    if ($upXML->submitXML(0, 'product_sync')) gen_add_audit_log(OPENCART_PRODUCT_SYNC);
	break;
  case 'confirm':
	$upXML->post_date = $ship_date;
	if ($upXML->submitXML(0, 'confirm')) gen_add_audit_log(OPENCART_SHIP_CONFIRM, $ship_date);
    break;
  default:
}
/*****************   prepare to display templates  *************************/
$cal_zc = array(
  'name'      => 'shipDate',
  'form'      => 'opencart',
  'fieldname' => 'ship_date',
  'imagename' => 'btn_date_1',
  'default'   => gen_locale_date($ship_date),
  'params'    => array('align' => 'left'),
);
$include_header   = true;
$include_footer   = true;
$include_template = 'template_main.php';
define('PAGE_TITLE', BOX_OPENCART_MODULE);
?>