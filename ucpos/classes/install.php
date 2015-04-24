<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011 UcSoft, LLC             |
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
//  Path: /modules/ucpos/classes/install.php
//
class ucpos_admin {
  function ucpos_admin() {
    $this->notes;
	$this->prerequisites = array( // modules required and rev level for this module to work properly
	  'contacts'  => '3.1',
	  'inventory' => '3.1',
	  'ucbooks'=> '3.1',
	  'ucounting'  => '3.1',
	  'payment'   => '3.1',
	  'ucform' => '3.1',
	);
	// Load configuration constants for this module, must match entries in admin tabs
    $this->keys = array(
	  'UCPOS_REQUIRE_ADDRESS'              => '0',
	  'UCPOS_RECEIPT_PRINTER_NAME'         => '', // i.e. Epson
      'UCPOS_RECEIPT_PRINTER_STARTING_LINE'=> '', // code that should be placed in the header
      'UCPOS_RECEIPT_PRINTER_CLOSING_LINE' => '', // code for opening the drawer or cutting of the paper.
	);
	// add new directories to store images and data
	$this->dirlist = array(
	);
	// Load tables
	$this->tables = array(
    );
  }

  function install($module, $demo = false) {
    global $db;
	$error = false;
//	$this->notes[] = MODULE_UCPOS_NOTES_1;
    return $error;
  }

  function initialize($module) {
  }

  function update($module) {
    global $db, $messageStack;
	$error = false;
	if (MODULE_UCPOS_STATUS < '3.2') {
	  write_configure('UCPOS_RECEIPT_PRINTER_STARTING_LINE', '');
	  write_configure('UCPOS_RECEIPT_PRINTER_CLOSING_LINE', '');
	}
	if (!$error) {
	  write_configure('MODULE_' . strtoupper($module) . '_STATUS', constant('MODULE_' . strtoupper($module) . '_VERSION'));
   	  $messageStack->add(sprintf(GEN_MODULE_UPDATE_SUCCESS, $module, constant('MODULE_' . strtoupper($module) . '_VERSION')), 'success');
	}
	return $error;
  }

  function remove($module) {
    global $db;
	$error = false;

    return $error;
  }

  function load_reports($module) {
	$error = false;
	$id = admin_add_report_heading(MENU_HEADING_UCPOS, 'pos');
	if (admin_add_report_folder($id, TEXT_REPORTS,        'pos',      'fr')) $error = true;
	if (admin_add_report_folder($id, TEXT_RECEIPTS,       'pos:rcpt', 'ff')) $error = true;
	return $error;
  }

  function load_demo() {
    global $db;
	$error = false;

	return $error;
  }

}
?>