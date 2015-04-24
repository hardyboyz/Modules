<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/pages/main/template_main.php
//
echo html_form('opencart', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
echo html_hidden_field('action', '') . chr(10);
echo html_hidden_field('todo', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['save']['show']     = false;
$toolbar->icon_list['print']['show']    = false;
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
echo $toolbar->build_toolbar(); 
?>
<h1><?php echo PAGE_TITLE; ?></h1>
<!--<div align="right"><img src="<?php echo DIR_WS_ADMIN . 'modules/opencart/images/opencart.png'; ?>" alt="OpenCart Logo" /></div>-->
<table class="ui-widget" style="border-style:none;width:600px;margin-left:auto;margin-right:auto">
 <thead class="ui-widget-header">
    <tr><th colspan="2"><?php echo OPENCART_BULK_UPLOAD_TITLE; ?></th></tr>
 </thead>
 <tbody class="ui-widget-content">
    <tr><td colspan="2"><?php echo OPENCART_BULK_UPLOAD_INFO; ?></td></tr>
    <tr>
      <td align="right"><?php echo OPENCART_INCLUDE_IMAGES; ?></td>
      <td><?php echo html_checkbox_field('include_images', '1', false); ?></td>
    </tr>
    <tr>
      <td align="right"><?php echo OPENCART_BULK_UPLOAD_TEXT; ?></td>
      <td><span style=" margin-bottom:20px;" class="button_bg"><?php echo html_button_field('bulkupload', OPENCART_BULK_UPLOAD_BTN, 'onclick="bulkUpload()" style="background:none !important;"'); ?></span></td>
	</tr>
	<tr class="ui-state-highlight"><td colspan="2"><div id="bulk_upload"></div></td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><th colspan="2" class="ui-widget-header"><?php echo OPENCART_PRODUCT_SYNC_TITLE; ?></th></tr>
    <tr><td colspan="2"><?php echo OPENCART_PRODUCT_SYNC_INFO; ?></td></tr>
<!--
    <tr>
      <td align="right"><?php echo OPENCART_DELETE_OPENCART; ?></td>
      <td><?php echo html_checkbox_field('delete_opencart', '1', false); ?></td>
    </tr>
-->
    <tr>
      <td align="right"><?php echo OPENCART_PRODUCT_SYNC_TEXT; ?></td>
      <td><span style=" margin-bottom:20px;" class="button_bg"><?php echo html_button_field('sync', OPENCART_PRODUCT_SYNC_BTN, 'onclick="submitToDo(\'sync\')" style="background:none !important;"'); ?></span></td>
	</tr>
        <?php if(!empty($_SESSION['set_security']) && $_SESSION['set_security']=='yes'){ ?>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><th colspan="2" class="ui-widget-header"><?php echo OPENCART_SHIP_CONFIRM_TITLE; ?></th></tr>
    <tr><td colspan="2"><?php echo OPENCART_SHIP_CONFIRM_INFO; ?></td></tr>
    <tr>
      <td align="right"><?php echo OPENCART_TEXT_CONFIRM_ON; ?></td>
      <td><?php echo html_calendar_field($cal_zc); ?></td>
	</tr>
    <tr>
      <td align="right"><?php echo OPENCART_SHIP_CONFIRM_TEXT; ?></td>
      <td><span style=" margin-bottom:20px;" class="button_bg"><?php echo html_button_field('confirm', OPENCART_SHIP_CONFIRM_BTN, 'onclick="submitToDo(\'confirm\')" style="background:none !important;"'); ?></span></td>
	</tr>
        <?php } ?>
   </tbody>
  </table>
</form>
