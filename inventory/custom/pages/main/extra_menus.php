<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/inventory/custom/pages/main/extra_menus.php
//
// This file contains the extra defines that can be used for customizing you output and 
// adding functionality to PhreeBooks
// Modified Language defines, used to over-ride the standard language for customization. These
// values are loaded prior to the standard language defines and take priority.

// Additional Action bar buttons (DYNAMIC AS IT IS SET BASED ON EVERY LINE!!!)
@include_once(DIR_FS_MODULES . 'opencart/config.php'); // pull the current OpenCart config info, if it is there
@include_once(DIR_FS_MODULES . 'opencart/language/' . $_SESSION['language'] . '/language.php');
function add_extra_action_bar_buttons($query_fields) {
  $output = '';
  if (defined('OPENCART_URL') && $_SESSION['admin_security'][SECURITY_ID_MAINTAIN_INVENTORY] > 1 && $query_fields['catalog'] == '1') {
    $output .= html_icon('../../../../' . 'modules/opencart/images/opencarticon.png', OPENCART_IVENTORY_UPLOAD, 'small', 'onclick="submitSeq(' . $query_fields['id'] . ', \'upload_oc\')"', '16', '16') . chr(10);
  }
  return $output;
}
// defines to use to retrieve more fields from sql for custom processing in list generation operations
$extra_fields = array();
// for the OpenCart upload mod, the catalog field should be in the table
if (defined('OPENCART_URL')) $extra_fields[] = 'catalog';
if (count($extra_fields) > 0) $extra_query_list_fields = $extra_fields;

?>