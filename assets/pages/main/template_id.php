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
//  Path: /modules/assets/pages/cat_inv/template_id.php
//

echo html_form('asset', FILENAME_DEFAULT, gen_get_all_get_params(array('action')));
echo html_hidden_field('todo', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action','page')) . '&amp;page=main', 'SSL') . '\'"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['save']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
$toolbar->add_icon('continue', 'onclick="submitToDo(\'create\')"', $order = 10);
$toolbar->add_help('');
//echo $toolbar->build_toolbar();
?>
  <h1><?php echo ASSETS_HEADING_NEW_ITEM; ?></h1>
  <table style="width: 100%; background-color: #EEEEEE">
      <tr>
          <td colspan="3" align="left"><h5><?php echo ASSETS_ENTER_ASSET_ID; ?></h5></td>
      </tr>
      <tr>
          <td width="33%">
              <br/><br/>
              <span class="form_label"><?php echo TEXT_ASSET_ID; ?> :</span><br/>
              <?php echo html_input_field('asset_id', $asset_id, 'size="17" maxlength="16"'); ?>
              <br/><br/>
              <span class="form_label"><?php echo ASSETS_ENTRY_ASSET_TYPE; ?> :</span><br/>
              <?php echo html_pull_down_menu('asset_type', gen_build_pull_down($assets_types), isset($asset_type) ? $asset_type : 'vh'); ?>
              <br/><br/>
          </td>
          <td width="33%">
              
          </td>
          <td width="33%">
              
          </td>
      </tr>
    
  </table>

<div class="bottom_btn">
    <?php
    echo $toolbar->build_toolbar();
    ?>
</div>
</form>

<br/>
