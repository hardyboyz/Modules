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
//  Path: /modules/contacts/pages/main/template_e_general.php
//
echo html_hidden_field('account_number', $cInfo->account_number); // not used for employees
?>
<style type="text/css">
    #inactive,#gl_type_account_e,#gl_type_account_s,#gl_type_account_b{width: 20px !important;}
</style>
<div id="tab_general">

    <h5><?php echo ACT_CATEGORY_CONTACT; ?></h5>
    <table width="100%">
        <tr>
            <td colspan="3" style="padding-left: 0 !important">
                <table style="width:40%">
                    <tr>
                        <td style="width: 19%">
                            <span class="form_label" ><?php echo GEN_PRIMARY_NAME ?> </span>
                        </td>
                        <td style="width:2%; padding-left: 0 !important" >
                            :
                        </td>
                        <td style="width: 56%; padding-left: 3px !important">

                            <?php
                            echo html_input_field("address[$type" . "m][primary_name]", $cInfo->address[$type . "m"]['primary_name'], 'size="49" maxlength="48"', true) . '</td></tr>' . chr(10);
                            ?>
                        </td>
                    </tr>

                </table>
            </td> 
        </tr>
        <tr>
            <td width="33%" style="padding-left: 0 !important">
                <table widht="100%">
                    
                    <tr>
                        <td width="40%"><span class="form_label"><?php echo constant('ACT_' . strtoupper($type) . '_SHORT_NAME'); ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('short_name', $cInfo->short_name, 'size="21" maxlength="20"', true); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label"><?php echo GEN_FIRST_NAME; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33" maxlength="32"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label"><?php echo constant('ACT_' . strtoupper($type) . '_REP_ID'); ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%">
                            <?php
                            $default_selection = ($action == 'new' ? EMP_DEFAULT_DEPARTMENT : $cInfo->dept_rep_id);
                            $selection_array = gen_get_pull_down(TABLE_DEPARTMENTS, true, 1);
                            echo html_pull_down_menu('dept_rep_id', $selection_array, $default_selection);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label"><?php echo TEXT_EMPLOYEE_ROLES; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%">
                            <?php
                            $col_count = 1;
                            foreach ($employee_types as $key => $value) {
                                $preset = (($action == 'new' && $key == 'e') || (strpos($cInfo->gl_type_account, $key) !== false)) ? '1' : '0';
                                echo html_checkbox_field('gl_type_account[' . $key . ']', '1', $preset) . '&nbsp;' . $value;
                                $col_count++;
                                if ($col_count == 6) {
                                    echo chr(10);
                                    //echo '<td>&nbsp;</td>';
                                    $col_count = 1;
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>

                </table>
            </td>
            <td width="33%">
                <table widht="100%">
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label"><?php echo constant('ACT_' . strtoupper($type) . '_ID_NUMBER'); ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('gov_id_number', $cInfo->gov_id_number, 'size="17" maxlength="16"'); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label"><?php echo GEN_MIDDLE_NAME; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"> <?php echo html_input_field('contact_middle', $cInfo->contact_middle, 'size="33" maxlength="32"', false); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td width="33%">
                <table widht="100%">
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label"><?php echo TEXT_INACTIVE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_checkbox_field('inactive', '1', $cInfo->inactive); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label"><?php echo GEN_LAST_NAME; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_last', $cInfo->contact_last, 'size="33" maxlength="32"', false); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



    <?php // *********************** Mailing/Main Address (only one allowed) ****************************** ?>

    <h5><?php echo ACT_CATEGORY_M_ADDRESS; ?></h5>
    <table id="<?php echo $type; ?>m_address_form" class="ui-widget" style="border-collapse:collapse;width:100%;">
        <?php echo draw_address_fields($cInfo, $type . 'm', false, true, false); ?>
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
                        echo ' <td>' . html_checkbox_field('rm_attach_' . $key, '1', false) . '</td>' . chr(10);
                        echo ' <td>' . $value . '</td>' . chr(10);
                        echo ' <td>' . html_button_field('dn_attach_' . $key, TEXT_DOWNLOAD, 'onclick="submitSeq(' . $key . ', \'download\', true)"') . '</td>';
                        echo '</tr>' . chr(10);
                    }
                } else {
                    echo '<tr><td colspan="3">' . TEXT_NO_DOCUMENTS . '</td></tr>';
                }
                ?>
            </tbody>
        </table>

    </div>
</div>