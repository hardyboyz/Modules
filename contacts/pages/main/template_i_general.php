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
//  Path: /modules/contacts/pages/main/template_i_general.php
//
// some setup
$acct_def = (!$cInfo->dept_rep_id) ? array() : array(array('id'=>$cInfo->dept_rep_id, 'text'=>gen_get_contact_name($cInfo->dept_rep_id)));
// *********************** Display account information ****************************** ?>
<div id="tab_general">
  
    <h5><?php echo ACT_CATEGORY_CONTACT; ?></h5>
    <table width="100%">
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo ACT_SHORT_NAME; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('short_name', $cInfo->short_name, 'size="21"', true); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo TEXT_TITLE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_middle', $cInfo->contact_middle, 'size="33"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo GEN_FIRST_NAME; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo ACT_ACCOUNT_NUMBER; ?> :</span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('account_number', $cInfo->account_number, 'size="17"'); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo TEXT_LINK_TO . ' '; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_combo_box('dept_rep_id', $acct_def, $cInfo->dept_rep_id, 'onkeyup="loadContacts()"'); ?></td>
                    </tr>
                    
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo ' ' . TEXT_INACTIVE . ' '; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_checkbox_field('inactive', '1', $cInfo->inactive); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"> </td>
                        <td width="4%">&nbsp;</td>
                        <td width="56%"></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo GEN_LAST_NAME; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_last', $cInfo->contact_last, 'size="33"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo ACT_ID_NUMBER; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('gov_id_number', $cInfo->gov_id_number, 'size="17"'); ?></td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
   
  

<?php // *********************** Mailing/Main Address (only one allowed) ****************************** ?>
  
    <h5><?php echo ACT_CATEGORY_M_ADDRESS; ?></h5>
    <table id="<?php echo $type; ?>m_address_form" class="ui-widget" style="border-collapse:collapse;width:100%;">
      <?php echo draw_address_fields($cInfo, $type.'m', false, true, false); ?>
    </table>
  
<?php // *********************** Attachments  ************************************* ?>
  <div>
  
   <h5><?php echo TEXT_ATTACHMENTS; ?></h5>
   <table class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto;">
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
if (sizeof($cInfo->attachments) > 0) {
  foreach ($cInfo->attachments as $key => $value) {
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
</div>