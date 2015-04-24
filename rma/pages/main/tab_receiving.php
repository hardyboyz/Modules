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
//  Path: /modules/rma/pages/main/tab_receiving.php
//
?>
<style type="text/css">
    .ui-widget-content.input input{width: 170px;}
</style>
<div id="tab_receiving">

  <h5><?php echo TEXT_DETAILS; ?></h5>
  <table width="100%">
      <tr>
          <td width="50%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_RECEIVE_DATE; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_calendar_field($cal_rcv); ?></td>
                  </tr>
              </table>
          </td>
          <td width="50%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_RECEIVE_CARRIER; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('receive_carrier', $cInfo->receive_carrier); ?></td>
                  </tr>
              </table>
          </td>
      </tr>
      <tr>
          <td width="50%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_RECEIVED_BY; ?> :</span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_pull_down_menu('received_by', $user_choices, ($cInfo->received_by ? $cInfo->received_by : '')); ?></td>
                  </tr>
              </table>
          </td>
          <td width="50%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_RECEIVE_TRACKING_NUM; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('receive_tracking', $cInfo->receive_tracking); ?></td>
                  </tr>
              </table>
          </td>
      </tr>
      <tr>
          <td width="50%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_RECEIVE_DATE; ?> :</span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_textarea_field('receive_notes', 40, 3, $cInfo->receive_notes, '',true); ?></td>
                  </tr>
              </table>
          </td>
          <td width="50%">
              <table width="100%">
                  <tr>
                      <td width="40%"></td>
                      <td width="4%"></td>
                      <td width="56%"></td>
                  </tr>
              </table>
          </td>
      </tr>
  </table>
<table class="ui-widget" style="border-style:none;width:100%">
 <tbody class="ui-widget-content">
     <tr>
         <td width="33%">
             
             
             <br/><br/>
             
             
             <br/><br/>
             
             
         </td>
         <td width="33%">
             
             
             <br/><br/>
             
             
             <br/><br/>
         </td>
         <td width="33%"></td>
     </tr>

   </tbody>
  </table>
  <br/><br/>
<table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto">
 <tbody class="ui-widget-content input">
   <tr>
	<td colspan="2">
           
	 <table class="ui-widget" style="border-collapse:collapse;width:100%">
	  <thead class="ui-widget-header">
        <tr>
          <th><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small'); ?></th>
          <th><?php echo TEXT_QUANTITY; ?></th>
          <th><?php echo TEXT_SKU; ?></th>
          <th><?php echo TEXT_DESCRIPTION; ?></th>
          <th><?php echo TEXT_DATE_MANUFACTURE; ?></th>
          <th><?php echo TEXT_DATE_WARRANTY; ?></th>
        </tr>
	  </thead>
	 <tbody id="rcv_table" class="ui-widget-content">
<?php 
if (sizeof($receive_details) > 0) {
	for ($i=0; $i<count($receive_details); $i++) { ?>
		<tr>
		  <td align="center"><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick = "if (confirm(image_delete_msg)) $(this).parent().parent().remove();"'); ?></td>
		  <td align="center"><?php echo html_input_field('rcv_qty[]', $receive_details[$i]['qty'], 'size="7" maxlength="6" style="text-align:right"'); ?></td>
		  <td nowrap="nowrap" align="center"><?php echo html_input_field('rcv_sku[]', $receive_details[$i]['sku'], 'size="' . (MAX_INVENTORY_SKU_LENGTH + 1) . '" onfocus="activeField(this, \''.TEXT_SEARCH.'\')" onblur="inactiveField(this, \''.TEXT_SEARCH.'\')"'); ?>
		  <?php echo '&nbsp;' . html_icon('status/folder-open.png', TEXT_SEARCH, 'small', 'align="top" style="cursor:pointer" onclick="RcvList(' . $i . ')"'); ?>
		  </td>
		  <td><?php echo html_input_field('rcv_desc[]',  $receive_details[$i]['desc'], 'size="32"'); ?></td>
		  <td><?php echo html_input_field('rcv_mfg[]',   $receive_details[$i]['mfg'],  'size="32"'); ?></td>
		  <td><?php echo html_input_field('rcv_wrnty[]', $receive_details[$i]['wrnty'],'size="32"'); ?></td>
		</tr>
<?php
	}
} else {
	$hidden_fields .= '  <script type="text/javascript">addRcvRow();</script>' . chr(10);
} ?>
       </tbody>
      </table>
	</td>
   </tr>
   <tr>
    <td colspan="2"><?php echo html_icon('actions/list-add.png', TEXT_ADD, 'medium', 'onclick="addRcvRow()"'); ?></td>
   </tr>
 </tbody>
</table>

</div>