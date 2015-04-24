<?php
// +-----------------------------------------------------------------+
// |                   bank_transfer Open Source ERP                    |
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
//  Path: /modules/bank_transfer/pages/reconciliation/template_main.php
//
// load the sort fields
$_GET['sf'] = $_POST['sort_field'] ? $_POST['sort_field'] : $_GET['sf'];
$_GET['so'] = $_POST['sort_order'] ? $_POST['sort_order'] : $_GET['so'];
echo html_form('reconciliation', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '')   . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params']   = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show']       = false;
if ($security_level > 1) {
	$toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
} else {
	$toolbar->icon_list['save']['show']   = false;
}
$toolbar->icon_list['delete']['show']     = false;
$toolbar->icon_list['print']['show']      = false;
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
$toolbar->add_help('07.05.04');
$toolbar->search_period = $period;
$toolbar->period_strict = false; // hide the All option in period selection
//echo $toolbar->build_toolbar($add_search = false, $add_period = true); 
// Build the page
?>
<style type="text/css">
    .checkbox_cl input{width: 10px;}
</style>
<h1><?php echo BANKING_HEADING_RECONCILIATION; ?></h1>
<div align="center"><?php echo TEXT_CASH_ACCOUNT . '&nbsp;' . html_pull_down_menu('gl_account', $account_array, $gl_account, 'onchange="submit();"'); ?></div>
<?php if (ENABLE_MULTI_CURRENCY) echo '<p>'.sprintf(GEN_PRICE_SHEET_CURRENCY_NOTE, $currencies->currencies[DEFAULT_CURRENCY]['title']) .'</p>'. chr(10); ?>
<table class="ui-widget" style="border-collapse:collapse;width:100%;margin-left:auto;margin-right:auto; background-color: #eeeeee;">
 <thead class="ui-widget-header">
  <tr>
<?php
	$heading_array = array(
	  'reference'  => TEXT_REFERENCE,
	  'post_date'  => TEXT_DATE,
	  'dep_amount' => BNK_DEPOSIT_CREDIT,
	  'pmt_amount' => BNK_CHECK_PAYMENT,
	);
	$result      = html_heading_bar($heading_array, $_GET['sf'], $_GET['so'], array(TEXT_SOURCE, TEXT_CLEAR, '&nbsp;'));
	echo $result['html_code'];
?>
  </tr>
 </thead>
 <tbody class="ui-widget-content checkbox_cl">
	<?php $i = 0;
	if (sizeof($combined_list) > 0) {
	  $odd = true;
	  foreach ($combined_list as $values) { 
		$bkgnd = ($values['partial']) ? ' style="background-color:yellow"' : '';
	?>
		<tr class="<?php echo $odd?'odd':'even'; ?>">
			<td width="16%"><?php echo $values['reference']; ?></td>
			<td width="10%"><?php echo gen_locale_date($values['post_date']); ?></td>
			<td width="15%" align="right"><?php echo $values['dep_amount'] ? $currencies->format($values['dep_amount']) : '&nbsp;'; ?></td>
			<td width="15%" align="right"><?php echo $values['pmt_amount'] ? $currencies->format($values['pmt_amount']) : '&nbsp;'; ?></td>
			<td width="30%"><?php echo htmlspecialchars($values['name']); ?></td>
			<td width="7%" align="center">
				<?php if (sizeof($values['detail']) == 1) {
				  echo html_checkbox_field('chk[' . $i . ']', '1', ($values['cleared'] == 1 ? true : false), '', 'onclick="updateBalance()"') . chr(10);
				  echo html_hidden_field('id[' . $i . ']', $values['detail'][0]['id']) . chr(10); 
				  echo html_hidden_field('pmt_' . $i, $values['detail'][0]['payment']) . chr(10); 
				} else {
				  echo html_checkbox_field('sum_' . $i, '1', ($values['cleared'] == 1 ? true : false), '', 'onclick="updateSummary(' . $i . ')"') . chr(10);
				} ?>
			</td>
<?php if (sizeof($values['detail']) > 1) { ?>
			<td id="disp_<?php echo $i; ?>" width="7%"<?php echo $bkgnd; ?> style="cursor:pointer" onclick="showDetails('<?php echo $i; ?>')"><?php echo TEXT_DETAILS; ?></td>
<?php } else { ?>
			<td width="7%">&nbsp;</td>
<?php } ?>
		</tr>
<?php 
		if (sizeof($values['detail']) > 1) {
		  $j   = 0;
		  $ref = $i;
		  $even = true;
		  echo '<tr id="detail_' . $i . '" style="display:none"><td colspan="7"><table style="width:100%">' . chr(10);
		  foreach ($values['detail'] as $detail) { ?>
		    <tr class="<?php echo $even?'even':'odd'; ?>">
			  <td width="16%"><?php echo '&nbsp;'; ?></td>
			  <td width="10%"><?php echo gen_locale_date($detail['post_date']); ?></td>
			  <td width="15%" align="right"><?php echo $detail['dep_amount'] ? $currencies->format($detail['dep_amount']) : '&nbsp;'; ?></td>
			  <td width="15%" align="right"><?php echo $detail['pmt_amount'] ? $currencies->format($detail['pmt_amount']) : '&nbsp;'; ?></td>
			  <td width="30%"><?php echo htmlspecialchars($detail['name']); ?></td>
			  <td width="7%" align="center">
			    <?php echo html_checkbox_field('chk[' . $i . ']', '1', ($detail['cleared'] == 1 ? true : false), '', ' onclick="updateDetail(' . $ref . ')"') . chr(10); ?>
			    <?php echo html_hidden_field('id[' . $i . ']', $detail['id']) . chr(10); ?>
			    <?php echo html_hidden_field('pmt_' . $i, $detail['payment']) . chr(10); ?>
			  </td>
			  <td id="<?php echo 'disp_' . $ref . '_' . $j; ?>" width="7%"><?php echo '&nbsp;'; ?></td>
		    </tr>
<?php
			$i++;
			$j++;
			$even = !$even;
		  }
		  echo '</table></td></tr>' . chr(10);
		} else {
		  $i++;
		}
		$odd = !$odd;
	  }
	} else {
	  echo '<tr><td>' . BNK_NO_GL_ENTRIES . '</td></tr>';
	}
?>
 </tbody>
 <tfoot class="ui-widget-header">
  <tr>
	<td colspan="3" align="right"><?php echo BNK_START_BALANCE . '&nbsp;'; ?></td>
	<td colspan="2" align="right"><?php echo html_input_field('start_balance', $statement_balance, 'style="text-align:right" size="13" onchange="updateBalance()"'); ?></td>
  </tr>
  <tr>
	<td colspan="3" align="right"><?php echo BNK_OPEN_CHECKS . '&nbsp;'; ?></td>
	<td colspan="2" align="right"><?php echo html_input_field('open_checks', '0', 'disabled="disabled" style="text-align:right" size="13"'); ?></td>
  </tr>
  <tr>
	<td colspan="3" align="right"><?php echo BNK_OPEN_DEPOSITS . '&nbsp;'; ?></td>
	<td colspan="2" align="right"><?php echo html_input_field('open_deposits', '0', 'disabled="disabled" style="text-align:right" size="13"'); ?></td>
  </tr>
  <tr>
	<td colspan="3" align="right"><?php echo BNK_GL_BALANCE . '&nbsp;'; ?></td>
	<td colspan="2" align="right"><?php echo html_input_field('gl_balance', $gl_balance, 'disabled="disabled" style="text-align:right" size="13"'); ?></td>
  </tr>
  <tr>
	<td colspan="3" align="right"><?php echo BNK_END_BALANCE . '&nbsp;'; ?></td>
	<td colspan="2" id="balance_total" align="right"><?php echo html_input_field('balance', '0', 'readonly="readonly" style="text-align:right" size="13"'); ?></td>
  </tr>
 </tfoot>
</table>
 <div class="bottom_btn">
      <?php echo $toolbar->build_toolbar();  ?>
  </div>
</form>

<br/>
