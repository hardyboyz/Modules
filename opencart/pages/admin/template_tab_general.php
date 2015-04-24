<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/pages/admin/template_tab_general.php
//
?>
<div id="tab_general">
<table class="ui-widget" style="border-collapse:collapse;width:100%">
 <thead class="ui-widget-header">
	  <tr><th colspan="5"><?php echo OPENCART_CONFIG_INFO; ?></th></tr>
 </thead>
 <tbody class="ui-widget-content">
  <tr>
    <td colspan="4"><?php echo OPENCART_ADMIN_URL; ?></td>
    <td><?php echo html_input_field('opencart_url', $_POST['opencart_url'] ? $_POST['opencart_url'] : OPENCART_URL, 'size="64"'); ?></td>
  </tr>
  <tr>
    <td colspan="4"><?php echo OPENCART_ADMIN_USERNAME; ?></td>
    <td><?php echo html_input_field('opencart_username', $_POST['opencart_username'] ? $_POST['opencart_username'] : OPENCART_USERNAME, ''); ?></td>
  </tr>
  <tr>
    <td colspan="4"><?php echo OPENCART_ADMIN_PASSWORD; ?></td>
    <td><?php echo html_input_field('opencart_password', $_POST['opencart_password'] ? $_POST['opencart_password'] : OPENCART_PASSWORD, ''); ?></td>
  </tr>
  <tr>
    <td colspan="4"><?php echo OPENCART_TAX_CLASS; ?></td>
    <td><?php echo html_input_field('opencart_product_tax_class', $_POST['opencart_product_tax_class'] ? $_POST['opencart_product_tax_class'] : OPENCART_PRODUCT_TAX_CLASS, ''); ?></td>
  </tr>
  <tr>
    <td colspan="4"><?php echo OPENCART_USE_PRICES; ?></td>
    <td><?php echo html_pull_down_menu('opencart_use_price_sheets', $sel_yes_no, $_POST['opencart_use_price_sheets'] ? $_POST['opencart_use_price_sheets'] : OPENCART_USE_PRICE_SHEETS, 'onclick="togglePriceSheets()"'); ?></td>
	  </tr>
  	  <tr id="price_sheet_row">
	    <td colspan="4"><?php echo OPENCART_TEXT_PRICE_SHEET; ?></td>
        <td><?php echo html_pull_down_menu('opencart_price_sheet', pull_down_price_sheet_list(), $_POST['opencart_price_sheet'] ? $_POST['opencart_price_sheet'] : OPENCART_PRICE_SHEET, ''); ?></td>
  </tr>
   <?php if(!empty($_SESSION['set_security']) && $_SESSION['set_security']=='yes'){ ?>
  <tr>
    <td colspan="4"><?php echo OPENCART_SHIP_ID; ?></td>
    <td><?php echo html_input_field('opencart_status_confirm_id', $_POST['opencart_status_confirm_id'] ? $_POST['opencart_status_confirm_id'] : OPENCART_STATUS_CONFIRM_ID, ''); ?></td>
  </tr>
  <tr>
    <td colspan="4"><?php echo OPENCART_PARTIAL_ID; ?></td>
    <td><?php echo html_input_field('opencart_status_partial_id', $_POST['opencart_status_partial_id'] ? $_POST['opencart_status_partial_id'] : OPENCART_STATUS_PARTIAL_ID, ''); ?></td>
  </tr>
  <?php } ?>
 </tbody>
</table>
</div>
