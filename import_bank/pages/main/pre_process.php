<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010 UcSoft, LLC                   |
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
// |                                                                 |
// | The license that is bundled with this package is located in the |
// | file: /doc/manual/ch01-Introduction/license.html.               |
// | If not, see http://www.gnu.org/licenses/                        |
// +-----------------------------------------------------------------+
//  Path: /modules/import_bank/pages/main/pre_process.php
//


//this is for menu select coding by 4aixz(apu) date:16_04_2013
$menu_select = 'banking';
$sub_menu_select = 'import_bank_statement';




/**************   Check user security   *****************************/
$security_level = validate_user(SECURITY_ID_IMPORT_BANK);
/**************  include page specific files    *********************/
gen_pull_language($module);
gen_pull_language('ucbooks');
gen_pull_language('ucbooks', 'admin');
gen_pull_language('contacts');
require_once(DIR_FS_MODULES . 'ucbooks/classes/gen_ledger.php');
require_once(DIR_FS_MODULES . 'ucounting/functions/ucounting.php');
require_once(DIR_FS_WORKING . 'functions/import_bank.php');

/**************   page specific initialization  *************************/
$error     = false; 
$action    = (isset($_GET['action'])    ? $_GET['action']    : $_POST['todo']);
$bank_acct = (isset($_GET['bank_acct']) ? $_GET['bank_acct'] : $_POST['bank_acct']);
$page_list = array();

	  $page_list[$file] = array(
	    'title'     => constant('MODULE_IMPORT_BANK_TITLE'),
		'structure' => load_module_xml('import_bank/file'),
	  );
	

/***************   hook for custom actions  ***************************/
$custom_path = DIR_FS_MODULES . 'import_bank/custom/pages/main/extra_actions.php';
if (file_exists($custom_path)) { include($custom_path); }

/***************   Act on the action request   *************************/
switch ($action) {
	case'import_csv':
		if (!validate_upload('file_name', 'text', 'csv')) break;
		$result = bank_import_csv($page_list[$subject]['structure'], 'file_name', $bank_acct);
		break;
	case 'sample_csv':
	  	$output = build_sample_csv($page_list[$subject]['structure'], 'bank_import');
		header("Content-type: application/csv");
		header("Content-disposition: attachment; filename=sample_$db_table.$type; size=" . strlen($output));
		header('Pragma: cache');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Connection: close');
		header('Expires: ' . date('r', time()+3600));
		header('Last-Modified: ' . date('r'));
		print $output;
		exit();  
  default:
}

/*****************   prepare to display templates  *************************/
$cash_chart = gen_coa_pull_down(2, false, true, false, $restrict_types = array(0));    // cash types only

$include_header   = true;
$include_footer   = true;
$include_tabs     = false;
$include_calendar = false;

$include_template = 'template_main.php';
define('PAGE_TITLE', HEADING_MODULE_IMPORT_BANK);

?>