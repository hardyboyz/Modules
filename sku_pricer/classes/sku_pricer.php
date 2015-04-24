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
//  Path: /modules/sku_pricer/classes/sku_pricer.php
//
class sku_pricer {
  function sku_pricer() {
  }

  function processCSV($lines_array = '') {
	global $db, $messageStack;
	if (!$this->cyberParse($lines_array)) return false;  // parse the submitted string, check for errors
	$count = 0;
	foreach ($this->records as $row) {
	  if ($row['sku']) {
		$cost = $row['cost'] ? $row['cost'] : 0;
	    $full = $row['full'] ? $row['full'] : 0;
		$result = $db->Execute("update " . TABLE_INVENTORY . " set full_price = " . $full . ", item_cost = " . $cost . " 
		  where sku = '" . $row['sku'] . "'");
		if ($result->AffectedRows() > 0) $count++;
	  }
	}
	$messageStack->add("successfully imported " . $count . " SKU prices.", "success");
	return;
  }

  function cyberParse($lines) {
	if(!$lines) return false;
	$title_line = trim(array_shift($lines)); // pull header and remove extra white space characters
	$titles     = explode(",", $title_line); // assume titles don't contain double quotes
	$records    = array();
	foreach ($lines as $line_num => $line) {    
	  $subject      = trim($line);
	  $parsed_array = $this->csv_string_to_array($subject);
	  $fields       = array();
	  for ($field_num = 0; $field_num < count($titles); $field_num++) {
		$fields[$titles[$field_num]] = $parsed_array[$field_num];
	  }
	  $records[] = $fields;
	}
	$records[] = '';	// terminator line
	$this->records = $records;
	return true;
  }

  function csv_string_to_array($str) {
	$results = preg_split("/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/", trim($str));
	return preg_replace("/^\"(.*)\"$/", "$1", $results);
  }
}

?>