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
//  Path: /modules/uchelp/classes/install.php
//

class uchelp_admin {
  function __construct() {
	$this->notes = array(); // placeholder for any operational notes
	$this->prerequisites = array( // modules required and rev level for this module to work properly
	  'ucounting' => '3.4',
	);
	// Load configuration constants for this module, must match entries in admin tabs
    $this->keys = array(
	  'UCHELP_FORCE_RELOAD' => '1',
	);
	// add new directories to store images and data
	$this->dirlist = array(
	);
	// Load tables
	$this->tables = array(
	  TABLE_UCHELP => "CREATE TABLE " . TABLE_UCHELP . " (
		  id int(10) unsigned NOT NULL auto_increment,
		  parent_id int(11) NOT NULL default '0',
		  doc_type enum('0','d') collate utf8_unicode_ci NOT NULL default 'd',
		  doc_lang char(5) collate utf8_unicode_ci default 'en_us',
		  doc_pos varchar(64) collate utf8_unicode_ci default NULL,
		  doc_url varchar(255) collate utf8_unicode_ci default NULL,
		  doc_index varchar(255) collate utf8_unicode_ci default NULL,
		  doc_title varchar(255) collate utf8_unicode_ci default NULL,
		  doc_text text collate utf8_unicode_ci,
		  PRIMARY KEY (id),
		  FULLTEXT KEY doc_title (doc_title, doc_text)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    );
  }

  function install($module) {
  }

  function initialize($module) {
  }

  function update($module) {
    global $db, $messageStack;
    $error = false;
	if (MODULE_UCHELP_STATUS < '3.0') {
	  foreach ($this->tables as $table => $create_table_sql) {
		if (!db_table_exists($table)) if (!$db->Execute($create_table_sql)) $error = true;
	  }
	}
	write_configure(UCHELP_FORCE_RELOAD, '1');
	if (!$error) {
	  write_configure('MODULE_' . strtoupper($module) . '_STATUS', constant('MODULE_' . strtoupper($module) . '_VERSION'));
   	  $messageStack->add(sprintf(GEN_MODULE_UPDATE_SUCCESS, $module, constant('MODULE_' . strtoupper($module) . '_VERSION')), 'success');
	}
	return $error;
  }

  function remove($module) {
  }

  function load_reports($module) {
  }

  function load_demo() {
  }

}
?>