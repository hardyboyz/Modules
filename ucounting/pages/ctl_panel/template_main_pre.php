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
//  Path: /modules/ucounting/pages/ctl_panel/template_main.php
//
echo html_form('cpanel', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '&mID=' . $menu_id, 'SSL') . '\'"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['save']['params']   = 'onclick="submitToDo(\'save\')"';
$toolbar->icon_list['print']['show']    = false;
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
$toolbar->add_help('07.09.01');
echo $toolbar->build_toolbar();
// Build the page
?>
<h1><?php echo CP_ADD_REMOVE_BOXES; ?></h1>
  <table class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto;">
	<thead class="ui-widget-header">
	  <tr>
		<th><?php echo TEXT_SHOW; ?></th>
		<th><?php echo TEXT_TITLE; ?></th>
		<th><?php echo TEXT_DESCRIPTION; ?></th>
	  </tr>
	</thead>
	<tbody class="ui-widget-content">
<?php 
$odd = true;
foreach ($the_list as $value) {
	if (!$value['security'] || $_SESSION['admin_security'][$value['security']] > 0 || $_SESSION['admin_security'][$value['security']] ==NULL) { // make sure user can view this control panel element
		echo '	  <tr class="'.($odd?'odd':'even').'"><td align="center">';
		$checked = (in_array($value['dashboard_id'], $my_profile)) ? ' selected' : '';
		echo html_checkbox_field($value['dashboard_id'], '1', $checked, '', $parameters = '');
		echo '	  </td><td>' . $value['title'] . '</td><td>' . $value['description'] . '</td></tr>';
		$odd = !$odd;
	}
} ?>
    </tbody>
  </table>
</form>

<br/>
