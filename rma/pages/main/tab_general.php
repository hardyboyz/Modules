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
//  Path: /modules/rma/pages/main/tab_general.php
//
?>

<div id="tab_general">

  <h5><?php echo TEXT_GENERAL; ?></h5>

  <table style="width: 100%">
      <tr>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_PURCHASE_INVOICE_ID; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('purchase_invoice_id', $cInfo->purchase_invoice_id); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_ENTERED_BY; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_pull_down_menu('entered_by', $user_choices, ($cInfo->entered_by ? $cInfo->entered_by : $_SESSION['admin_id'])); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_CALLER_NAME; ?> :</span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('caller_name', $cInfo->caller_name, 'size="33"', false); ?></td>
                  </tr>
                  
              </table>
          </td>
          
      </tr>
      <tr>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_ORIG_PO_SO; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('purch_order_id', $cInfo->purch_order_id); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_STATUS; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_pull_down_menu('status', gen_build_pull_down($status_codes), $cInfo->status); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_TELEPHONE; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('caller_telephone1', $cInfo->caller_telephone1, 'size="33"'); ?></td>
                  </tr>
                  
              </table>
          </td>
      </tr>
      <tr>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_INVOICE_DATE; ?> :</span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_calendar_field($cal_invoice); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_CREATION_DATE; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_calendar_field($cal_create); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo GEN_EMAIL; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('caller_email', $cInfo->caller_email, 'size="33"'); ?></td>
                  </tr>
                  
              </table>
          </td>
      </tr>
      <tr>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_REASON_FOR_RETURN; ?> :</span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_pull_down_menu('return_code', gen_build_pull_down($reason_codes), $cInfo->return_code); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_CLOSED; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_calendar_field($cal_close); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_CUSTOMER_ID; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('contact_id', $cInfo->contact_id, ''); ?></td>
                  </tr>
                  
              </table>
          </td>
      </tr>
      <tr>
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"><span class="form_label" ><?php echo TEXT_NOTES; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_textarea_field('caller_notes', 28, 3, $cInfo->caller_notes, '', true); ?></td>
                  </tr>
                  
              </table>
          </td>
          
          <td width="33%">
              <table width="100%">
                  <tr>
                      <td width="40%"> <span class="form_label" ><?php echo GEN_PRIMARY_NAME; ?> </span></td>
                      <td width="4%">:</td>
                      <td width="56%"><?php echo html_input_field('contact_name', $cInfo->contact_name, 'size="33"'); ?></td>
                  </tr>
                  
              </table>
          </td>
          <td width="33%">
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
              
              
              <br/><br/>
              
              
              <br/><br/>
              
              
              <br/><br/>
          </td>
          <td width="33%">
              
              
              <br/><br/>
              
              
              <br/><br/>
              
              
              <br/><br/>
              
              
              <br/><br/>
          </td>
          <td width="33%">
              
              
              <br/><br/>
              
              
              <br/><br/>
              
              
              <br/><br/>
              
              
              <br/><br/>
             
              
              <br/><br/>
          </td>
      </tr>

  </tbody>
 </table>





<?php // *********************** Attachments  ************************************* ?>
   
   <h5><?php echo TEXT_ATTACHMENTS; ?></h5>
   <table class="ui-widget" style="margin-left:auto;margin-right:auto">
    <thead class="ui-widget-header">
     <tr><th colspan="3"><?php echo TEXT_ATTACHMENTS; ?></th></tr>
    </thead>
    <tbody class="ui-widget-content">
     <tr><td colspan="3"><?php echo TEXT_SELECT_FILE_TO_ATTACH . ' ' . html_file_field('file_name'); ?></td></tr>
     <tr  class="ui-widget-header">
      <th><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small'); ?></th>
      <th><?php echo TEXT_FILENAME; ?></th>
      <th><?php echo TEXT_ACTION; ?></th>
     </tr>
<?php 
if (sizeof($attachments) > 0) { 
  foreach ($attachments as $key => $value) {
    echo '<tr>';
    echo ' <td>' . html_checkbox_field('rm_attach_'.$key, '1', false) . '</td>' . chr(10);
    echo ' <td>' . $value . '</td>' . chr(10);
    echo ' <td>' . html_button_field('dn_attach_'.$key, TEXT_DOWNLOAD, 'onclick="submitSeq(' . $key . ', \'download\', true)"') . '</td>';
    echo '</tr>' . chr(10);
  }
} else {
  echo '<tr><td colspan="3">' . TEXT_NO_DOCUMENTS . '</td></tr>'; 
} ?>
    </tbody>
   </table>
   
</div>