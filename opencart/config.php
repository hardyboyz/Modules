<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/config.php
//
// Module software version information
define('MODULE_OPENCART_VERSION',      '1.2');
define('SECURITY_ID_OPENCART_INTERFACE', 201);
if (defined('MODULE_OPENCART_STATUS')) {
	$mainmenu["tools"]['submenu']["opencart"] = array(
		'order'		  => 32,
		'text'        => BOX_OPENCART_MODULE, 
		'security_id' => SECURITY_ID_OPENCART_INTERFACE, 
		'link'        => html_href_link(FILENAME_DEFAULT, 'module=opencart&amp;page=main', 'SSL'),
		'show_in_users_settings' => true,
		'params'      => '',
	);
}

if(isset($_SESSION['admin_security'][SECURITY_ID_CONFIGURATION]) && $_SESSION['admin_security'][SECURITY_ID_CONFIGURATION] > 0){
  gen_pull_language('opencart', 'admin');
  $mainmenu["company"]['submenu']["configuration"]['submenu']["opencart"] = array(
	'order'	      => 32,
	'text'        => BOX_OPENCART_MODULE,
	'security_id' => SECURITY_ID_CONFIGURATION, 
	'link'        => html_href_link(FILENAME_DEFAULT, 'module=opencart&amp;page=admin', 'SSL'),
    'show_in_users_settings' => false,
	'params'      => '',
  );
}

?>