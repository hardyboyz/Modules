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
//  Path: /modules/rma/pages/main/tab_disposition.php
//
?>

<div id="tab_disposition">


  <h5><?php echo TEXT_DETAILS; ?></h5>
  <p><?php echo RMA_DISPOSITION_DESC; ?></p>
<table width="100%" class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto">
 <tbody class="ui-widget-content">
     <tr>
         <td width="50%" >
             <table width="100%">
                 <tr>
                     <td><span class="form_label" ><?php echo TEXT_NOTES; ?> </span><br/></td>
                     <td>:</td>
                     <td><?php echo html_textarea_field('close_notes', 40, 3, $cInfo->close_notes, '', true); ?></td>
                 </tr>
             </table>
             
             
             <br/><br/>
         </td>
         <td width="50%"></td>
         
     </tr>
   
   <tr>
	<td colspan="2">
	 <table class="ui-widget" style="border-collapse:collapse;width:100%">
	  <thead class="ui-widget-header">
        <tr>
          <th><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small'); ?></th>
          <th><?php echo TEXT_QUANTITY; ?></th>
          <th><?php echo TEXT_SKU; ?></th>
          <th><?php echo TEXT_NOTES; ?></th>
          <th><?php echo TEXT_ACTION; ?></th>
        </tr>
	  </thead>
	 <tbody id="item_table" class="ui-widget-content">
<?php 
if (sizeof($close_details) > 0) {
	for ($i=0; $i<sizeof($close_details); $i++) { ?>
		<tr>
		  <td><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick = "if (confirm(image_delete_msg)) $(this).parent().parent().remove();"'); ?></td>
		  <td><?php echo html_input_field('qty[]', $close_details[$i]['qty'], 'size="7" maxlength="6" style="text-align:right"'); ?></td>
		  <td nowrap="nowrap" ><?php echo html_input_field('sku[]' . $i, $close_details[$i]['sku'], 'size="24" onfocus="activeField(this, \''.TEXT_SEARCH.'\')" onblur="inactiveField(this, \''.TEXT_SEARCH.'\')"'); ?>
		  <?php echo '&nbsp;' . html_icon('status/folder-open.png', TEXT_SEARCH, 'small', 'align="top" style="cursor:pointer" onclick="ItemList(' . $i . ')"'); ?>
		  </td>
		  <td><?php echo html_input_field('notes[]', $close_details[$i]['notes'], 'size="48"'); ?></td>
		  <td><?php echo html_pull_down_menu('action[]', gen_build_pull_down($action_codes), $close_details[$i]['action']); ?></td>
		</tr>
<?php
	}
} else {
	$hidden_fields .= '  <script type="text/javascript">addItemRow();</script>' . chr(10);
} ?>
       </tbody>
      </table>
	</td>
   </tr>
   <tr>
    <td colspan="2"><?php echo html_icon('actions/list-add.png', TEXT_ADD, 'medium', 'onclick="addItemRow()"'); ?></td>
   </tr>
 </tbody>
</table>

</div>