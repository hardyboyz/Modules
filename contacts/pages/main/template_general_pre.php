<style type="text/css">
    select{width: 207px;}
    td{padding: 0 !important;}
    input[type='checkbox']{ width:20px;}
    .gips-container{ left: 150px !important; top:12px !important;}
    .gips-body{ font-weight: bold !important;}
</style>

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
//  Path: /modules/contacts/pages/main/template_general.php
//
?>
<div id="tab_general">

    <h5><?php echo ACT_CATEGORY_CONTACT; ?></h5>
    <br/>


    <table style="width:100%">
        <tr>
            <td width="33%" style="padding-right:10px !important;">
                <table style="width:100%">
                    
                    <tr>
                        <td style="width:40%">

                            <span class="form_label" ><?php echo constant('ACT_' . strtoupper($type) . '_SHORT_NAME'); ?> </span>
                        </td>
                        <td style="width:4%">
                            :
                        </td>
                        <td style="width:56%">
                            <?php echo html_input_field('short_name', $cInfo->short_name, "class='tool_tip' tooltip_text='" . ACT_ID_AUTO_FILL . "'", false, false); ?><br/><br/>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td width="33%" colspan="3"  style="padding-right:10px !important;">
                <table style="width:40%">
                    <tr>
                        <td style="width: 19%">
                            <span class="form_label" ><?php echo GEN_PRIMARY_NAME ?> </span>
                        </td>
                        <td style="width:2%">
                            :
                        </td>
                        <td style="width: 56%">
                           
                            <?php
                                echo  html_input_field("address[$type" . "m][primary_name]", $cInfo->address[$type . "m"]['primary_name'], 'size="49" maxlength="48"', true) . '</td></tr>' . chr(10);
                            ?>
                        </td>
                    </tr>
                    
                </table>
            </td>

        </tr>
      <!-- <tr>
            <td width="33%" style="padding-right:10px !important;">
                <table style="width:100%">
                    
                    <tr>
                        <td width="40%">
                            <span class="form_label" ><?php echo GEN_FIRST_NAME; ?> </span>
                        </td>
                        <td width="4%">
                            :
                        </td>
                        <td width="56%">
                            <?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33" maxlength="32"', false); ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="33%" style="padding-right:10px !important;">
                <table style="width:100%">
                    <tr>
                        <td width="40%">
                            <span class="form_label" ><?php echo GEN_MIDDLE_NAME; ?> :</span>
                        </td>
                        <td width="4%">
                            :
                        </td>
                        <td width="56%">
                            <?php echo html_input_field('contact_middle', $cInfo->contact_middle, 'size="33" maxlength="32"', false); ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="33%">
                <table style="width:100%">
                    <tr>
                        <td width="32%">
                            <span class="form_label" ><?php echo GEN_LAST_NAME; ?> </span>
                        </td>
                        <td width="4%">
                            :
                        </td>
                        <td width="56%">
                            <?php echo html_input_field('contact_last', $cInfo->contact_last, 'size="33" maxlength="32"', false); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>-->
        <tr>
            <td width="33%" style="padding-right:10px !important;">
                <table style="width:100%">
                    <tr>
                        <td width="32%"><span class="form_label" ><?php echo constant('ACT_' . strtoupper($type) . '_GL_ACCOUNT_TYPE'); ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_pull_down_menu('gl_type_account', gen_coa_pull_down(), $action == 'new' ? AR_DEF_GL_SALES_ACCT : $cInfo->gl_type_account); ?></td>
                    </tr>
                </table>
            </td>
            <td width="33%" style="padding-right:10px !important;">
                <table style="width:100%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo constant('ACT_' . strtoupper($type) . '_ACCOUNT_NUMBER'); ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('account_number', $cInfo->account_number, 'size="17" maxlength="16"'); ?></td>
                    </tr>
                </table>
            </td>
            <td width="33%" style="padding-right:10px !important;">
                <table style="width:100%">
                    <tr>
                        <td width="35%"><span class="form_label" ><?php echo constant('ACT_' . strtoupper($type) . '_ID_NUMBER'); ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('gov_id_number', $cInfo->gov_id_number, 'size="17" maxlength="16"'); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="33%">
                <table style="width:100%">
                    <tr>
                        <td style="width:32%">
                            <span class="form_label" ><?php echo constant('ACT_' . strtoupper($type) . '_REP_ID'); ?> </span>
                        </td>
                        <td style="width:4%">
                            :
                        </td>
                        <td style="width:56%">
                            <?php echo html_pull_down_menu('dept_rep_id', $sales_rep_array, $cInfo->dept_rep_id ? $cInfo->dept_rep_id : '0'); ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="33%" style="padding-right:10px !important;">
                <table style="width:100%">
                    <tr>
                        <td width="32%"><span class="form_label" ><?php echo INV_ENTRY_ITEM_TAXABLE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_pull_down_menu('tax_id', $tax_rates, $cInfo->tax_id); ?></td>
                    </tr>
                </table>
            </td>
            <td width="33%">
                <table style="width:100%">
                    <tr>
                        <td width="32%"><span class="form_label" ><?php echo ACT_CATEGORY_PAYMENT_TERMS; ?> :</span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php
                            echo html_hidden_field('terms', $cInfo->special_terms) . chr(10);
                            echo html_input_field('terms_text', gen_terms_to_language($cInfo->special_terms, true, $cInfo->terms_type), 'readonly="readonly" size="20"') . '&nbsp;' . chr(10);
                            echo html_icon('apps/accessories-text-editor.png', ACT_TERMS_DUE, 'small', 'style="cursor:pointer" onclick="TermsList()"');
                            ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>

            <td width="33%">
                <table style="width:100%">
                    <tr>
                        <td width="32%"><span class="form_label" ><?php echo TEXT_DEFAULT_PRICE_SHEET; ?> :</span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_pull_down_menu('price_sheet', get_price_sheet_data(), $cInfo->price_sheet); ?></td>
                    </tr>
                </table>
            </td>


            <td width="33%" style="padding-right:10px !important;">
                <table style="width:100%">
                    <tr>
                        <td style="width:32%">
                            <span class="form_label" ><?php echo TEXT_INACTIVE; ?> </span>
                        </td>
                        <td style="width:4%">
                            :
                        </td>
                        <td style="width:56%">
                            <?php echo html_checkbox_field('inactive', '1', $cInfo->inactive); ?>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>

        <tr>
            <td width="33%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
            <td width="33%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
            <td width="33%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width: 100%">
        <tr>
            <td width="50%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table style="width:100%">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%"></td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br/><br/><br/>




    <?php // *********************** Mailing/Main Address (only one allowed) ******************************  ?>
    <br/><br/>
    <h5><?php echo ACT_CATEGORY_M_ADDRESS; ?></h5>
    <br/><br/>
    <table id="<?php echo $type; ?>m_address_form" class="ui-widget" style="border-collapse:collapse;width:100%;">
        <?php echo draw_address_fields($cInfo, $type . 'm', false, true, false); ?>
    </table>

    <?php // *********************** Attachments  *************************************  ?>
    <div>
        <br/><br/>
        <h5><?php echo TEXT_ATTACHMENTS; ?></h5>
        <br/><br/>
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

                        echo ' <td>Delete : ' . html_checkbox_field('rm_attach_' . $key, '1', false) . '</td>' . chr(10);
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