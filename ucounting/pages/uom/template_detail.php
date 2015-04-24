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
//  Path: /modules/ucounting/pages/roles/template_roles.php
//
echo html_form('roles', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo',   '')        . chr(10);
echo html_hidden_field('rowSeq', $uom_id) . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL') . '\'"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['print']['show']    = false;
if ($security_level > 2) {
  $toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
} else {
  $toolbar->icon_list['save']['show']   = false;
}
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
$toolbar->add_help('07.08.07');
//echo $toolbar->build_toolbar();
// Build the page
?>
<style type="text/css">
    .radio_btn{width: 20px}
</style>
<div class="bottom_btn" id="bottom_btn">
    <?php echo $toolbar->build_toolbar();  ?>
</div>
<h1>UOM</h1>
  
  
  <table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto; width: 100%;">
   <tbody class="ui-widget-content">
       
       <tr>
           <td width="33">
               <br/><br/>
               <span class="form_label">UOM :</span>
               <?php echo  html_input_field('uom', $uInfo->uom, 'size="25"'); ?>
           </td>
           <td width="33">
               
               
           </td>
           <td width="33">
               
           </td>
       </tr>
       
       

   </tbody>
  </table>

<div class="bottom_btn">
    <?php echo $toolbar->build_toolbar();  ?>
</div>
  
  
	
  
</form>
<br/>
