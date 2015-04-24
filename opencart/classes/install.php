<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/classes/install.php
//

class opencart_admin {
  function __construct() {
	$this->notes = array(); // placeholder for any operational notes
	$this->prerequisites = array( // modules required and rev level for this module to work properly
	  'ucounting'   => '3.4',
	  'contacts'   => '3.6',
	  'inventory'  => '3.4',
	  'payment'    => '3.3',
	  'ucbooks' => '3.3',
	  'shipping'   => '3.3',
	);
	// Load configuration constants for this module, must match entries in admin tabs
    $this->keys = array(
	  'OPENCART_URL'               => 'http://www.yourstore.com/admin/soap/phreebooks.php',
	  'OPENCART_USERNAME'          => '',
	  'OPENCART_PASSWORD'          => '',
	  'OPENCART_PRODUCT_TAX_CLASS' => '',
	  'OPENCART_USE_PRICE_SHEETS'  => '0',
	  'OPENCART_PRICE_SHEET'       => '',
	  'OPENCART_STATUS_CONFIRM_ID' => '3',
	  'OPENCART_STATUS_PARTIAL_ID' => '3',
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
	if (!db_field_exists(TABLE_INVENTORY, 'catalog')) { // setup new tab in table inventory
	  $sql_data_array = array(
	    'module_id'   => 'inventory',
	    'tab_name'    => 'POS',
	    'description' => 'POS Catalog',
	    'sort_order'  => '49',
	  );
	 // db_perform(TABLE_EXTRA_TABS, $sql_data_array);
	  $tab_id = 0;//db_insert_id();
	  gen_add_audit_log(OPENCART_LOG_TABS . TEXT_ADD, 'OpenCart');
	  // setup extra fields for inventory
	  $sql_data_array = array(
	    'module_id'   => 'inventory',
	    'tab_id'      => '',
	    'entry_type'  => 'check_box',
	    'field_name'  => 'catalog',
	    'description' => OPENCART_CATALOG_ADD,
	  	'sort_order'  => 10,
	  	'use_in_inventory_filter'=>'1',
	    'params'      => serialize(array('type'=>'check_box', 'select'=>'0', 'inventory_type'=>'ai:ci:ds:sf:ma:ia:lb:mb:ms:mi:ns:sa:sr:sv:si:')),
	  );
	  db_perform(TABLE_EXTRA_FIELDS, $sql_data_array);
	  $db->Execute("alter table " . TABLE_INVENTORY . " add column `catalog` enum('0','1') default '0'");
	  $sql_data_array = array(
	    'module_id'   => 'inventory',
	    'tab_id'      => $tab_id,
	    'entry_type'  => 'text',
	    'field_name'  => 'category_id',
	    'description' => OPENCART_CATALOG_CATEGORY_ID,
	  	'sort_order'  => 20,
	  	'use_in_inventory_filter'=>'1',
	  	'params'      => serialize(array('type'=>'text', 'length'=>'64', 'default'=>'', 'inventory_type'=>'ai:ci:ds:sf:ma:ia:lb:mb:ms:mi:ns:sa:sr:sv:si:')),
	  );
	  db_perform(TABLE_EXTRA_FIELDS, $sql_data_array);
	  $db->Execute("alter table " . TABLE_INVENTORY . " add column `category_id` varchar(64) default ''");
	  $sql_data_array = array(
	    'module_id'   => 'inventory',
	    'tab_id'      => $tab_id,
	    'entry_type'  => 'text',
	    'field_name'  => 'manufacturer',
	    'description' => OPENCART_CATALOG_MANUFACTURER,
	  	'sort_order'  => 30,
	  	'use_in_inventory_filter'=>'1',
	  	'params'      => serialize(array('type'=>'text', 'length'=>'64', 'default'=>'', 'inventory_type'=>'ai:ci:ds:sf:ma:ia:lb:mb:ms:mi:ns:sa:sr:sv:si:')),
	  );
	  db_perform(TABLE_EXTRA_FIELDS, $sql_data_array);
	  $db->Execute("alter table " . TABLE_INVENTORY . " add column `manufacturer` varchar(64) default ''");
	  gen_add_audit_log(OPENCART_LOG_FIELDS . TEXT_NEW, 'OpenCart - catalog');
	}
    return $error;
  }

  function initialize($module) {
  }

  function update($module) {
    global $db, $messageStack;
	$error = false;
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