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
//  Path: /modules/ucounting/dashboards/company_links/company_links.php
//
// Revision history
// 2011-07-01 - Added version number for revision control
define('DASHBOARD_COMPANY_LINKS_VERSION','3.2');

class company_links extends ctl_panel {
  function __construct() {
  }

  function Install($column_id = 1, $row_id = 0) {
	global $db;
	if (!$row_id) $row_id = $this->get_next_row();
	// fetch the pages params to copy to new install
	$result = $db->Execute("select params from ".TABLE_USERS_PROFILES."
	  where menu_id = '".$this->menu_id."' and dashboard_id = '".$this->dashboard_id."'"); // just need one
	$sql_array = array(
	  'user_id'     => $_SESSION['admin_id'], 
	  'menu_id'     => $this->menu_id, 
	  'module_id'   => $this->module_id, 
	  'dashboard_id'=> $this->dashboard_id, 
	  'column_id'   => $column_id, 
	  'row_id'      => $row_id, 
	  'params'      => $result->fields['params'],
	);
	db_perform(TABLE_USERS_PROFILES, $sql_array);
  }

  function Remove() {
	global $db;
	$result = $db->Execute("delete from " . TABLE_USERS_PROFILES . " 
	  where user_id = " . $_SESSION['admin_id'] . " and menu_id = '" . $this->menu_id . "' 
	    and dashboard_id = '" . $this->dashboard_id . "'");
  }

  function Output($params) {
	global $db;
	// Build control box form data
	$control  = '<div class="row">';
	$control .= '<div style="white-space:nowrap">';
	if ($_SESSION['admin_security'][SECURITY_ID_USERS] > 1) { // only show add new if user permission is set to add
	  $control .= TEXT_TITLE . '&nbsp;' . html_input_field('company_links_field_0', '', 'size="40"') . '<br />';
	  $control .= TEXT_URL   . '&nbsp;' . html_input_field('company_links_field_1', '', 'size="64"');
	  $control .= '&nbsp;&nbsp;&nbsp;&nbsp;';
	  $control .= html_submit_field('sub_company_links', TEXT_ADD);
	}
	$control .= html_hidden_field('company_links_rId', '');
	$control .= '</div></div>';
	// Build content box
	$contents = '';
	if (is_array($params)) {
	  $index = 1;
	  foreach ($params as $title => $hyperlink) {
		if ($_SESSION['admin_security'][SECURITY_ID_USERS] > 3) { // only let delete if user permission is full
		  $contents .= '<div style="float:right; height:16px;">';
		  $contents .= html_icon('ucbooks/dashboard-remove.png', TEXT_REMOVE, 'small', 'onclick="return del_index(\'' . $this->dashboard_id . '\', ' . $index . ')"');
		  $contents .= '</div>';
		}
		$contents .= '<div style="height:16px;">';
		$contents .= '  <a href="' . $hyperlink . '" target="_blank">' . $title . '</a>' . chr(10);
		$contents .= '</div>';
		$index++;
	  }
	} else {
	  $contents = CP_COMPANY_LINKS_NO_RESULTS;
	}
	return $this->build_div(CP_COMPANY_LINKS_TITLE, $contents, $control);
  }

  function Update() {
	global $db;
	$my_title  = db_prepare_input($_POST['company_links_field_0']);
	$my_url    = db_prepare_input($_POST['company_links_field_1']);
	$remove_id = db_prepare_input($_POST[$this->dashboard_id . '_rId']);
	// do nothing if no title or url entered
	if (!$remove_id && ($my_title == '' || $my_url == '')) return; 
	// fetch the current params
	$result = $db->Execute("select params from " . TABLE_USERS_PROFILES . "
	  where menu_id = '" . $this->menu_id . "' and dashboard_id = '" . $this->dashboard_id . "'"); // just need one
	if ($remove_id) { // remove element
	  $params     = unserialize($result->fields['params']);
	  $first_part = array_slice($params, 0, $remove_id - 1);
	  $last_part  = array_slice($params, $remove_id);
	  $params     = array_merge($first_part, $last_part);
	} elseif ($result->fields['params']) { // append new url and sort
	  $params     = unserialize($result->fields['params']);
	  $params[$my_title] = $my_url;
	  ksort($params);
	} else { // first entry
	  $params     = array($my_title => $my_url);
	}
	db_perform(TABLE_USERS_PROFILES, array('params' => serialize($params)), "update", "menu_id = '".$this->menu_id."' and dashboard_id = '".$this->dashboard_id."'");
  }
}
?>