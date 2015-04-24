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
//  Path: /modules/payment/pages/admin/pre_process.php
//

    $menu_select = 'company';
    $sub_menu_select = 'module_administration';

$security_level = validate_user(SECURITY_ID_CONFIGURATION);
/**************  include page specific files    *********************/
gen_pull_language($module, 'admin');
require_once(DIR_FS_WORKING . 'classes/install.php');

/**************   page specific initialization  *************************/
$error      = false; 
$method_dir = DIR_FS_WORKING . 'methods/';
$action     = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
$install    = new payment_admin();

// see if installing or removing a method
if (substr($action, 0, 8) == 'install_') {
  $method = substr($action, 8);
  $action = 'install';
} elseif (substr($action, 0, 7) == 'remove_') {
  $method = substr($action, 7);
  $action = 'remove';
}

// load the available methods
$methods = array();
if ($dir = @dir($method_dir)) {
  while ($choice = $dir->read()) {
	if (file_exists($method_dir . $choice . '/' . $choice . '.php') && $choice <> '.' && $choice <> '..') {
	  load_method_language($method_dir, $choice);
	  $methods[] = $choice;
	}
  }
  $dir->close();
}
sort($methods);
/***************   Act on the action request   *************************/
switch ($action) {
  case 'install':
	validate_security($security_level, 4);
  	require_once($method_dir . $method . '/' . $method . '.php');
	$properties = new $method();
	write_configure('MODULE_PAYMENT_' . strtoupper($method) . '_STATUS', '1');
	foreach ($properties->keys() as $key) write_configure($key['key'], $key['default']);
	if (method_exists($properties, 'install')) $properties->install(); // handle special case install, db, files, etc
	gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
	break;
  case 'remove';
	validate_security($security_level, 4);
  	require_once($method_dir . $method . '/' . $method . '.php');
	$properties = new $method();
	if (method_exists($properties, 'remove')) $properties->remove(); // handle special case removal, db, files, etc
	foreach ($properties->keys() as $key) { // remove all of the keys from the configuration table
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key = '" . $key['key'] . "'");
	}
	remove_configure('MODULE_PAYMENT_' . strtoupper($method) . '_STATUS');
	gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
	break;
  case 'save':
	validate_security($security_level, 3);
  	// foreach method if enabled, save info
	if (sizeof($methods) > 0) foreach ($methods as $shipper) {
	  require_once($method_dir . $shipper . '/' . $shipper . '.php');
	  if (defined('MODULE_PAYMENT_' . strtoupper($shipper) . '_STATUS')) {
	    $properties = new $shipper();
	    $properties->update();
	  }
	}
	// save general tab
	foreach ($install->keys as $key => $default) {
	  $field = strtolower($key);
      if (isset($_POST[$field])) write_configure($key, $_POST[$field]);
    }
	gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
    break;
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
define('PAGE_TITLE', MENU_HEADING_UCPAY);

?>