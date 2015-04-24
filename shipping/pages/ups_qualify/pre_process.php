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
//  Path: /modules/shipping/methods/ups/ups_qualify/pre_process.php
//

function write_file($file_name, $data) {
  global $messageStack;
  if (!$handle = @fopen($file_name, 'w')) { 
	$messageStack->add('Cannot open file (' . $file_name . ')','error');
	return false;
  }
  if (@fwrite($handle, $data) === false) {
	$messageStack->add('Cannot write to file (' . $file_name . ')','error');
	return false;
  }
  fclose($handle);
  return true;
}

/**************   Check user security   *****************************/
$security_level = '4';

/**************  include page specific files    *********************/
$shipping_module = 'ups';
load_method_language(DEFAULT_MOD_DIR, $shipping_module);
require(DIR_FS_WORKING . 'functions/shipping.php');
require(DIR_FS_WORKING . 'classes/shipping.php');
require(DIR_FS_WORKING . 'methods/' . $shipping_module . '/' . $shipping_module . '.php');
require(DIR_FS_WORKING . 'pages/ups_qualify/data.php');

/**************   page specific initialization  *************************/
$error = false;
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];

/***************   Act on the action request   *************************/
switch ($action) {
  case 'go':
	validate_security($security_level, 3);
  	// retrieve the sample ship to addresses and query UPS
	$file_path = DIR_FS_MY_FILES . $_SESSION['company'] . '/temp/ups_cal/';
	validate_path($file_path);
	$count = 1;
	foreach ($shipto as $pkg) {
	  $sInfo = new shipment();	// load defaults
	  // overwrite the defaults with data from the file
	  while (list($key, $value) = each($pkg)) $sInfo->$key = db_prepare_input($value);
	  $sInfo->ship_date = date('Y-m-d', strtotime($sInfo->ship_date));
	  // load package information
	  $sInfo->package = array();
	  foreach ($pkg['package'] as $item) {
		$sInfo->package[] = array(
			'weight' => $item['weight'],
			'length' => $item['length'],
			'width'  => $item['width'],
			'height' => $item['height'],
			'value'  => $item['value'],
		);
	  }
	  if (count($sInfo->package) > 0) {
		$shipment = new ups();
		if (!$result = $shipment->retrieveLabel($sInfo)) $error = true;
	  }
	  // Save the results in the temp file
	  if (!write_file($file_path . 'ShipRequest_'   . $count . '.txt', $shipment->labelRequest))       break;
	  if (!write_file($file_path . 'ShipResponse_'  . $count . '.txt', $shipment->labelResponse))      break;
	  if (!write_file($file_path . 'LabelRequest_'  . $count . '.txt', $shipment->labelFetchRequest))  break;
	  if (!write_file($file_path . 'LabelResponse_' . $count . '.txt', $shipment->labelFetchReturned)) break;
	  // fetch label
	  if (MODULE_SHIPPING_UPS_PRINTER_TYPE == 'Thermal') {
		// keep the thermal label encoded for now
		$output_label = base64_decode($label['graphic_image']);
		$file_ext = '.lpt'; // thermal printer
	  } else {
		$output_label = base64_decode($label['graphic_image']);
		$file_ext = '.gif'; // plain paper
	  }
	  @rename($shipment->labelFilePath, $file_path . 'LabelImage_' . $count . $file_ext);
	  $count++;
	}
	// generate the delete requests and save
	$count = 1;
	foreach ($deleteID as $tracking_number) {
	  $shipment = new ups();
	  $shipment->tracking_number = $tracking_number; // override id with hard coded tracking number
	  $shipment->deleteLabel(-1);
	  if (!write_file($file_path . 'DeleteRequest_'   . $count . '.txt', $shipment->labelDelRequest))  break;
	  if (!write_file($file_path . 'DeleteResponse_'  . $count . '.txt', $shipment->labelDelResponse)) break;
	  $count++;
	}
	// zip the results and download
	
	$messageStack->add('Successfully created UPS validation files! Disregard error messages from delete operation, they are expected. The files can be found in: ' . $file_path,'success');
	break;

  default:
}

/*****************   prepare to display templates  *************************/

$include_header   = true;
$include_footer   = true;
$include_tabs     = false;
$include_calendar = false;
$include_template = 'template_main.php';
define('PAGE_TITLE', 'UPS Label Certification Script');

?>