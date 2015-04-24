<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/pages/admin/pre_process.php
//
$security_level = validate_user(SECURITY_ID_CONFIGURATION);
/**************  include page specific files    *********************/
gen_pull_language($module, 'admin');
require_once(DIR_FS_WORKING . 'functions/opencart.php');
require_once(DIR_FS_WORKING . 'classes/install.php');
/**************   page specific initialization  *************************/
$error   = false; 
$install = new opencart_admin();
/***************   Act on the action request   *************************/
switch ($_REQUEST['action']) {
  case 'save':
	validate_security($security_level, 3);
	// save general tab
	foreach ($install->keys as $key => $default) {
	  $field = strtolower($key);
      if (isset($_POST[$field])) write_configure($key, $_POST[$field]);
    }
	gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
	$messageStack->add(OPENCART_CONFIG_SAVED, 'success');
    break;
  default:
}
/*****************   prepare to display templates  *************************/
$sel_yes_no = array( // build some general pull down arrays
 array('id' => '0', 'text' => TEXT_NO),
 array('id' => '1', 'text' => TEXT_YES),
);
$include_header   = true;
$include_footer   = true;
$include_template = 'template_main.php';
define('PAGE_TITLE', BOX_OPENCART_ADMIN);
?>