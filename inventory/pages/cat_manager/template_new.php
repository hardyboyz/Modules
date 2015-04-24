<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011 UcSoft, LLC             |
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
//  Path: /modules/work_orders/pages/main/template_new.php
//
echo html_form('cat_manager', FILENAME_DEFAULT, gen_get_all_get_params(array('action', 'id')), 'post', '');
// include hidden fields
echo html_hidden_field('todo', '')                . chr(10);
echo html_hidden_field('id', $id)                 . chr(10);

// customize the toolbar actions
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL') . '\'"';
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=inventory&page=cat_manager&"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
if (!$hide_save && $security_level > 1) {
  $toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
} else {
  $toolbar->icon_list['save']['show'] = false;
}
$toolbar->icon_list['print']['show'] = false;
if ($security_level > 1) $toolbar->add_icon('new', 'onclick="submitToDo(\'new\')"', $order = 2);
$toolbar->add_help('07.04.WO.04');
//echo $toolbar->build_toolbar(); 
if($_SESSION['set_security'] != 'yes'){
    $toolbar->icon_list['new']['show'] = false;
    $toolbar->icon_list['open']['show'] = false;
}
?>
<style>
   ul.token-input-list {
    float: left;
    max-height: 22px !important;
    width: 256px;
}
</style>
<!--Top Error Message Start -->
<div class="bottom_btn" id="bottom_btn">
    <?php
    echo $toolbar->build_toolbar();
    ?>
</div>
<!--Top Error Message End -->
<h1><?php echo PAGE_TITLE; ?></h1>
<table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto; width: 100%">
    <tr>
        <td width="50%">
            <table width="100%">
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
              
                <tr>
                    <td width="40%"><span class="form_label"><?php echo 'Category Name : '; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"><?php echo html_input_field('cat_name', $cat_name, '') . '&nbsp;'; ?></td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo 'Category Key Code : '; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"><?php echo html_input_field('key_code', $key_code, '') . '&nbsp;'; ?></td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo 'Description : '; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"><?php echo html_textarea_field('description', 40, 3, $description, $params = ''); ?></td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
            </table>
        </td>
        
    </tr>
</table>


 <div class="bottom_btn">
      <?php echo $toolbar->build_toolbar();  ?>
  </div>
</form>

<br/>


