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
//  Path: /modules/inventory/pages/cat_inv/template_id.php
//

echo html_form('inventory', FILENAME_DEFAULT, gen_get_all_get_params(array('action')));
echo html_hidden_field('todo', '') . chr(10);
// customize the toolbar actions
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action','page')) . '&amp;page=main', 'SSL') . '\'"';
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=inventory&list=1&sf=&so=&f0=0&f1=&&page=main"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['save']['show']     = false;
$toolbar->icon_list['print']['show']    = false;
$toolbar->add_icon('continue', 'onclick="submitToDo(\'create\')"', $order = 10);
$toolbar->add_help('07.04.01.01');

?>
  <h1><?php echo INV_HEADING_NEW_ITEM; ?></h1>
  <table class="ui-widget" style="border-collapse:collapse;width:100%;margin-left:auto;margin-right:auto">
      <tr>
          <td colspan="3">
              &nbsp;
          </td>
      </tr>
      <tr>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_SKU; ?> </span></td>                         
                      <td width="4%">:</td>                         
                      <td width="56%"><?php echo html_input_field('sku', $sku, 'size="' . (MAX_INVENTORY_SKU_LENGTH + 2) . '" maxlength="' . MAX_INVENTORY_SKU_LENGTH . '"'); ?></td>                         
                  </tr>
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_INVENTORY_TYPE; ?> </span></td>                         
                      <td width="4%">:</td>                         
                      <td width="56%"><?php echo html_pull_down_menu('inventory_type', gen_build_pull_down($inventory_types), isset($inventory_type) ? $inventory_type : 'si', 'onchange="setSkuLength()"'); ?></td>                         
                  </tr>
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_INVENTORY_COST_METHOD; ?> </span></td>                         
                      <td width="4%">:</td>                         
                      <td width="56%"><?php echo html_pull_down_menu('cost_method', gen_build_pull_down($cost_methods), isset($cost_method) ? $cost_method : 'f'); ?></td>                         
                  </tr>
              </table>
          </td>
      </tr>
      <tr>
          <td colspan="3">
              &nbsp;
          </td>
      </tr>
  </table>

  <table class="ui-widget" style="border-collapse:collapse;width:100%;margin-left:auto;margin-right:auto">
   <thead class="ui-widget-header">
    <tr>
	  <th nowrap="nowrap" colspan="2"><?php echo sprintf(INV_ENTER_SKU, MAX_INVENTORY_SKU_LENGTH, MAX_INVENTORY_SKU_LENGTH-5); ?></th>
    </tr>
   </thead>
   <tbody class="ui-widget-content">
       <tr>
           <td>
               <br/><br/>
               
               
               <br/><br/>
               
               
               <br/><br/>
               
               
               <br/><br/>
           </td>
       </tr>  

    <tr>
	  <td nowrap="nowrap" colspan="2"><?php echo '&nbsp;'; ?></td>
    </tr>
  </tbody>
  </table>
  <div class="bottom_btn">
      <?php echo $toolbar->build_toolbar();  ?>
  </div>
</form>

<br/>
