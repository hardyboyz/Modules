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
//  Path: /modules/ucbooks/pages/search/template_main.php
//
echo html_form('site_search', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['save']['show']     = false;
$toolbar->icon_list['print']['show']    = false;
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
$toolbar->add_help('');
//echo $toolbar->build_toolbar(); 
// Build the page
?>
<h1><?php echo HEADING_TITLE_SEARCH_INFORMATION; ?></h1>
<?php if ($query_numrows > 0) { ?>
<div style="float:right"><?php echo $query_split->display_links($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['list']); ?></div>
<div><?php echo $query_split->display_count($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['list'], TEXT_DISPLAY_NUMBER . TEXT_RESULTS); ?></div>
<?php } ?>
<table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto; width: 100%; background-color: #EEEEEE;">
 <thead class="ui-widget-header">
  <tr>
    <th><?php echo TEXT_FILTER; ?></th>
    <th><?php echo TEXT_TYPE; ?></th>
    <th><?php echo TEXT_FROM; ?></th>
    <th><?php echo TEXT_TO; ?></th>
    <th></th>
  </tr>
 </thead>
 <tbody class="ui-widget-content">
  <tr>
    <td><?php echo TEXT_TRANSACTION_TYPE; ?></td>
    <td colspan="3"><?php echo html_pull_down_menu('journal_id', gen_build_pull_down($journal_choices), $_GET['journal_id']); ?></td>
	<td rowspan="3" align="right"><?php echo html_icon('actions/view-refresh.png', TEXT_RESET, 'large', 'style="cursor:pointer;" onclick="submitToDo(\'reset\')"');
    echo '&nbsp;' . html_icon('actions/system-search.png', TEXT_SEARCH, 'large', 'style="cursor:pointer;" onclick="submitToDo(\'search\')"'); ?></td>
  </tr>
  <tr>
    <td><?php echo TEXT_TRANSACTION_DATE; ?></td>
    <td><?php echo html_pull_down_menu('date_id', gen_build_pull_down($DateChoices), $_GET['date_id'], $params = ''); ?></td>
    <td><?php echo html_calendar_field($cal_from); ?></td>
    <td><?php echo html_calendar_field($cal_to); ?></td>
  </tr>
  <tr>
    <td><?php echo TEXT_REFERENCE_NUMBER; ?></td>
    <td><?php echo html_pull_down_menu('ref_id', gen_build_pull_down($choices), $_GET['ref_id'], $params = ''); ?></td>
    <td><?php echo html_input_field('ref_id_from', $_GET['ref_id_from'], $params = ''); ?></td>
    <td><?php echo html_input_field('ref_id_to', $_GET['ref_id_to'], $params = ''); ?></td>
  </tr>
  <tr>
    <td><?php echo TEXT_CUST_VEND_ACCT; ?></td>
    <td><?php echo html_pull_down_menu('account_id', gen_build_pull_down($choices), $_GET['account_id'], $params = ''); ?></td>
    <td><?php echo html_input_field('account_id_from', $_GET['account_id_from'], $params = ''); ?></td>
    <td><?php echo html_input_field('account_id_to', $_GET['account_id_to'], $params = ''); ?></td>
  </tr>
  <tr>
    <td><?php echo TEXT_INVENTORY_ITEM; ?></td>
    <td><?php echo html_pull_down_menu('sku_id', gen_build_pull_down($choices), $_GET['sku_id'], $params = ''); ?></td>
    <td><?php echo html_input_field('sku_id_from', $_GET['sku_id_from'], $params = ''); ?></td>
    <td><?php echo html_input_field('sku_id_to', $_GET['sku_id_to'], $params = ''); ?></td>
  </tr>
  <tr>
    <td><?php echo TEXT_TRANSACTION_AMOUNT; ?></td>
    <td><?php echo html_pull_down_menu('amount_id', gen_build_pull_down($choices), $_GET['amount_id'], $params = ''); ?></td>
    <td><?php echo html_input_field('amount_id_from',($_GET['amount_id_from'])? $currencies->precise($_GET['amount_id_from']):'', $params = ''); ?></td>
    <td><?php echo html_input_field('amount_id_to', ($_GET['amount_id_to']) ? $currencies->precise($_GET['amount_id_to']):'', $params = ''); ?></td>
  </tr>
  <tr>
    <td><?php echo TEXT_GENERAL_LEDGER_ACCOUNT; ?></td>
    <td><?php echo html_pull_down_menu('gl_acct_id', gen_build_pull_down($choices), $_GET['gl_acct_id'], $params = ''); ?></td>
    <td><?php echo html_pull_down_menu('gl_acct_id_from', $gl_array_list, $_GET['gl_acct_id_from']); ?></td>
    <td><?php echo html_pull_down_menu('gl_acct_id_to', $gl_array_list, $_GET['gl_acct_id_to']); ?></td>
  </tr>
  <tr>
    <td><?php echo TEXT_JOURNAL_RECORD_ID; ?></td>
    <td><?php echo html_pull_down_menu('main_id', gen_build_pull_down($choices), $_GET['main_id'], $params = ''); ?></td>
    <td><?php echo html_input_field('main_id_from', $_GET['main_id_from'], $params = ''); ?></td>
    <td><?php echo html_input_field('main_id_to', $_GET['main_id_to'], $params = ''); ?></td>
  </tr>
 </tbody>
</table>
<table class="ui-widget" style="border-collapse:collapse;width:100%">
 <thead class="ui-widget-header">
  <tr><?php echo $list_header; ?></tr>
 </thead>
 <tbody class="ui-widget-content">
	<?php
	$odd = true;
	while (!$query_result->EOF) {
	  $jID = (int)$query_result->fields['journal_id'];
	  switch ($jID) {
	    case  2: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_JOURNAL_ENTRY]; 
		  $module = 'ucbooks'; 
		  $mod = 'journal'; 
		  break;
		case  3: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_PURCHASE_QUOTE]; 
		  $module = 'ucbooks'; 
		  $mod = 'orders'; 
		  break;
		case  4: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_PURCHASE_ORDER]; 
		  $module = 'ucbooks'; 
		  $mod = 'orders'; 
		  break;
		case  6: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_PURCHASE_INVENTORY]; 
		  $module = 'ucbooks'; 
		  $mod = 'orders'; 
		  break;
		case  7: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_PURCHASE_CREDIT]; 
		  $module = 'ucbooks'; 
		  $mod = 'orders'; 
		  break;
		case  9: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_SALES_QUOTE]; 
		  $module = 'orders'; 
		  $mod = 'orders'; 
		  break;
		case 10: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_SALES_ORDER]; 
		  $module = 'ucbooks'; 
		  $mod = 'orders'; 
		  break;
		case 12: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_SALES_INVOICE]; 
		  $module = 'ucbooks'; 
		  $mod = 'orders'; 
		  break;
		case 13: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_SALES_CREDIT]; 
		  $module = 'ucbooks'; 
		  $mod = 'orders'; 
		  break;
	    case 14: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_ASSEMBLE_INVENTORY]; 
		  $module = 'inventory'; 
		  $mod = 'assemblies'; 
		  break;
	    case 16: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_ADJUST_INVENTORY]; 
		  $module = 'inventory'; 
		  $mod = 'adjustments'; 
		  break;
	    case 18:
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_CUSTOMER_RECEIPTS]; 
		  $module = 'ucbooks'; 
		  $mod = 'bills'; 
		  $type = gen_get_contact_type($query_result->fields['bill_acct_id']);
		  break;
	    case 19: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_POS_MGR]; 
		  $module = 'ucpos'; $mod = 'pos_mgr'; 
		  break;
	    case 20: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_PAY_BILLS]; 
		  $module = 'ucbooks'; 
		  $mod = 'bills'; 
		  $type = gen_get_contact_type($query_result->fields['bill_acct_id']);
		  break;
	    case 21: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_POS_MGR]; 
		  $module = 'ucpos'; $mod = 'pos_mgr'; 
		  break;
		default: 
		  $security_level = $_SESSION['admin_security'][SECURITY_ID_SEARCH]; 
		  $module = 'ucbooks'; 
		  $mod = 'orders'; 
		  break;
	  }
	  if ($security_level > 0) {
	  	$params = 'module=' . $module . '&amp;page=' . $mod . '&amp;oID=' . $query_result->fields['id'] . '&amp;jID=' . $jID . '&amp;action=edit';
		if ($type) $params .= '&amp;type=' . $type;
	?>
  <tr class="<?php echo $odd?'odd':'even'; ?>" style="cursor:pointer">
	<td onclick="window.open('<?php echo html_href_link(FILENAME_DEFAULT, $params, 'SSL'); ?>','_blank')"><?php echo $query_result->fields['id']; ?></td>
	<td onclick="window.open('<?php echo html_href_link(FILENAME_DEFAULT, $params, 'SSL'); ?>','_blank')"><?php echo $query_result->fields['description']; ?></td>
	<td onclick="window.open('<?php echo html_href_link(FILENAME_DEFAULT, $params, 'SSL'); ?>','_blank')"><?php echo $query_result->fields['bill_primary_name']; ?></td>
	<td onclick="window.open('<?php echo html_href_link(FILENAME_DEFAULT, $params, 'SSL'); ?>','_blank')"><?php echo gen_locale_date($query_result->fields['post_date']); ?></td>
	<td onclick="window.open('<?php echo html_href_link(FILENAME_DEFAULT, $params, 'SSL'); ?>','_blank')"><?php echo $query_result->fields['purchase_invoice_id']; ?></td>
	<td align="right" onclick="window.open('<?php echo html_href_link(FILENAME_DEFAULT, $params, 'SSL'); ?>','_blank')"><?php echo $currencies->format($query_result->fields['total_amount']); ?></td>
	<td align="right">
<?php // build the action toolbar
	  if (function_exists('add_extra_action_bar_buttons')) echo add_extra_action_bar_buttons($query_result->fields);
	  echo html_icon('actions/edit-find-replace.png', TEXT_EDIT, 'small', 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, $params, 'SSL') . '\',\'_blank\')"') . chr(10);
?>
	</td>
  </tr>
<?php } else { // no permission ?>
  <tr><td colspan="7"><?php echo $query_result->fields['description'] . ' - ' . ERROR_NO_SEARCH_PERMISSION; ?></td></tr>
<?php	  
	  }
	  $query_result->MoveNext();
	  $odd = !$odd;
	}
?>
 </tbody>
</table>
<?php if ($query_numrows > 0) { ?>
<div style="float:right"><?php echo $query_split->display_links($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['list']); ?></div>
<div><?php echo $query_split->display_count($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['list'], TEXT_DISPLAY_NUMBER . TEXT_RESULTS); ?></div>
<?php } ?>
<div class="bottom_btn">
      <?php echo $toolbar->build_toolbar();  ?>
  </div>
</form>

<br/>
