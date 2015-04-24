<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/inventory/custom/pages/main/extra_actions.php
//

// This file contains the extra actions added to the maintain inventory module, it is executed
// before the standard switch statement

switch ($action) {
// Begin - Upload operation added by PhreeSoft to upload products to OpenCart
  case 'upload_oc':
	$id = db_prepare_input($_POST['rowSeq']);
	require_once(DIR_FS_MODULES . 'opencart/functions/opencart.php');
	require_once(DIR_FS_MODULES . 'opencart/classes/opencart.php');
	$upXML = new opencart();
	$upXML->submitXML($id, 'product_ul');
	$action = '';
	break;
// End - Upload operation added by PhreeSoft
  default:
}
?>