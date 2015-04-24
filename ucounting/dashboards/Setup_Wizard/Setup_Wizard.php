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
//  Path: /modules/ucbooks/dashboards/open_inv/open_inv.php
//
// Revision history
// 2011-07-01 - Added version number for revision control
// 2011-12-20 - Updated to show invoice balance, was total invoice amount 

// add by shaheen for setup wizard permission
define('DASHBOARD_OPEN_INV_VERSION','3.3');

require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');

class setup_wizard extends ctl_panel {
  function __construct() {
    $this->max_length = 20;
  }

  function Install($column_id = 1, $row_id = 0) {
	global $db;
	if (!$row_id) $row_id = $this->get_next_row();
	$params['num_rows'] = '';	// defaults to unlimited rows
	$result = $db->Execute("insert into " . TABLE_USERS_PROFILES . " set 
	  user_id = "       . $_SESSION['admin_id'] . ", 
	  menu_id = '"      . $this->menu_id . "', 
	  module_id = '"    . $this->module_id . "', 
	  dashboard_id = '" . $this->dashboard_id . "', 
	  column_id = "     . $column_id . ", 
	  row_id = "        . $row_id . ", 
	  params = '"       . serialize($params) . "'");
  }

  function Remove() {
	global $db;
	$result = $db->Execute("delete from " . TABLE_USERS_PROFILES . " 
	  where user_id = " . $_SESSION['admin_id'] . " and menu_id = '" . $this->menu_id . "' 
	    and dashboard_id = '" . $this->dashboard_id . "'");
  }

  function Output($params) {
	global $db, $currencies;
	$list_length = array();
	$control  = '<div class="row">';
	$control .= '</div></div>';
        $contents = '<div style="width:1070px; border: 1px solid #cccccc; padding: 5px 0 5px 5px;">
        <div style="float: left">
            <h3>Top 5 items Sold in terms of Total Value</h3>
            <script type="text/javascript">
                var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
                //hasRightVersion = false;
                if(hasRightVersion){
                    document.write('."'".'<iframe src="/charts/sales_by_sku_total.html" width="530" height="400"></iframe>'."'".'); 
                }else{
                    document.write('."'".'<div id="sku_total" style="margin-top:7px; width:520px; height: 382px;"></div>'."'".');
                }
            </script>
    <!--<iframe src="/charts/sales_by_sku_total.html" width="530" height="400"></iframe>-->

        </div>
        <div style="float: left; margin-left: 2px;">
            <h3>Top 5 Items Sold in terms of Total Quantity</h3>
            <script type="text/javascript">
                var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
                //hasRightVersion = false;
                if(hasRightVersion){
                    document.write('."'".'<iframe src="/charts/sales_by_sku_qty.html" width="530" height="400"></iframe>'."'".'); 
                }else{
                    document.write('."'".'<div id="sku_qty" style="margin-top:7px; width:520px; height: 382px;"></div>'."'".');
                }
            </script>
    <!--<iframe src="/charts/sales_by_sku_qty.html" width="530" height="400"></iframe>-->
        </div>
        <div style="clear: both"></div>
    </div>';
	return $this->build_div('Sales Dashboard', $contents, $control);
  }

  function Update() {
	global $db;
	$params['num_rows'] = db_prepare_input($_POST['open_inv_field_0']);
	$db->Execute("update " . TABLE_USERS_PROFILES . " set params = '" . serialize($params) . "' 
	  where user_id = " . $_SESSION['admin_id'] . " and menu_id = '" . $this->menu_id . "' 
	    and dashboard_id = '" . $this->dashboard_id . "'");
  }

}
?>