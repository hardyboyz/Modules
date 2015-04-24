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
//  Path: /modules/import_bank/classes/install.php
//

class import_bank_admin {
  function import_bank_admin() {
	$this->notes = array(); // placeholder for any operational notes
	$this->prerequisites = array( // modules required and rev level for this module to work properly
	  'ucounting'   => '3.0',
	  'payment'    => '3.0',
	  'ucbooks' => '3.0',
	  'contacts'   => '3.1',
	);
	// Load configuration constants for this module, must match entries in admin tabs
    $this->keys = array(
	  'QUESTION_POSTS'               => '2200',
      'DEBIT_CREDIT_DESCRIPTION'	 => 'crediting',
	);
	// add new directories to store images and data
	$this->dirlist = array(
	);
	// Load tables
	$this->tables = array(
    );
  }

  function install($module) {
    global $db, $messageStack;
	$error = false;
	if (!db_field_exists(TABLE_CONTACTS, 'bank_account')) { 
		$sql = "select id from " . TABLE_EXTRA_FIELDS . " where module_id = 'contacts' and field_name = 'bank_account'";
		$result = $db->Execute($sql);
		if ( $result->RecordCount() == 0 ){
			$result = $db->Execute("select id from " . TABLE_EXTRA_TABS . " where module_id='contacts' and tab_name = 'import_banking'");
			if ( $result->RecordCount() == 0 ){
				$entry = array(	'module_id'	=> 'contacts',
				 				'tab_name'	=> 'import_banking',
								'sort_order'=> '100' );
				db_perform(TABLE_EXTRA_TABS, $entry, 'insert');
				$tab_id = $db->insert_ID();
			}else {	
				$tab_id = $result->fields['id'];
			}
			$entry = array(	'module_id'	  => 'contacts',
				 			'tab_id'	  => $tab_id,
							'entry_type'  => 'text',
							'field_name'  => 'bank_account',
							'description' => 'Bank Account',
							'params'	  => 'a:4:{s:4:"type";s:4:"text";s:12:"contact_type";s:16:"customer:vendor:";s:6:"length";i:32;s:7:"default";s:0:"";}');
			db_perform(TABLE_EXTRA_FIELDS, $entry, 'insert');
			//$db->Execute("INSERT INTO " . TABLE_EXTRA_FIELDS . " VALUES ('', 'contacts', ". $tab_id .",'text', 'bank_account', 'Bank Account','c:v:', );");
			$db->Execute("ALTER TABLE ".TABLE_CONTACTS." ADD bank_account varchar(32) default NULL");
		}
	}
    return $error;
  }

  function initialize($module) {
  }

  function update($module) {
  	global $db;
  	$sql = "select id from " . TABLE_EXTRA_FIELDS . " where module_id = 'contacts' and field_name = 'bank_account'";
	$result = $db->Execute($sql);
	if ( $result->RecordCount() == 0 ){
		$result = $db->Execute("select id from " . TABLE_EXTRA_TABS . " where module_id='contacts' and tab_name = 'import_banking'");
		if ( $result->RecordCount() == 0 ){
			$db->Execute("INSERT INTO " . TABLE_EXTRA_TABS . " VALUES('', 'contacts','import_banking','100')");
			$tab_id = $db->insert_ID();
		}else {	
			$tab_id = $result->fields['id'];
		}
		$db->Execute("INSERT INTO " . TABLE_EXTRA_FIELDS . " VALUES ('', 'contacts', ". $tab_id .",'text', 'bank_account', 'Bank Account','c:v:', 'a:4:{s:4:'type';s:4:'text';s:12:'contact_type';s:16:'customer:vendor:';s:6:'length';i:32;s:7:'default';s:0:'';}' );");
	}
	write_configure('MODULE_' . strtoupper($module) . '_STATUS', constant('MODULE_' . strtoupper($module) . '_VERSION'));
  }

  function remove($module) {
  }

  function load_reports($module) {
  }

  function load_demo() {
  }

}
?>