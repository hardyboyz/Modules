<style type="text/css">
    #gentabs input[type="text"]{width: 144px !important}
    #report_form{display: none;}
    #report_viewer{width: 1087px; ; overflow: hidden}
    #tb_icon_cancel,#tb_icon_save,#tb_icon_copy,#tb_icon_help,#tb_icon_export_xml,#tb_icon_export_html{display: none;}
    #tb_icon_export_htmla{display: inline}

</style>
<script type="text/javascript">
    $(document).ready(function (){
        $('.no_data').parent('#popup_gen').removeAttr("name");
<?php
if (isset($_GET['rID'])) {
    if ($error != 1) {
        ?>
                        submitToDoRpt('exp_html', true);
                        //submiotToDoNewPdf()
        <?php
    }
} else if (isset($_GET['emailsend'])) {
    ?>
                $("#rpt_email").show();
                $("#delivery_method").val("S");
    <?php if ($_GET['jID'] == 10) { ?>
                    $("#tb_icon_print").html("Send Sales Order");
    <?php } else if ($_GET['jID'] == 12) { ?>
                    $("#tb_icon_print").html("Send Invoice");
    <?php } else if ($_GET['jID'] == 9) { ?>
                    $("#tb_icon_print").html("Send Quotation");
    <?php } else if ($_GET['jID'] == 13) { ?>
                    $("#tb_icon_print").html("Send Credit Memo");
    <?php } else if ($_GET['jID'] == 4) { ?>
                    $("#tb_icon_print").html("Send Purchase Order");
    <?php } else if ($_GET['jID'] == 7) { ?>
                    $("#tb_icon_print").html("Send Credit Memo");
    <?php } else if ($_GET['jID'] == 3) { ?>
                    $("#tb_icon_print").html("Request for Quote");
    <?php } ?>
                $("#tb_icon_print").removeAttr('onclick');
                $("#tb_icon_print").attr('onclick','submitToDo("exp_html", true)');
                $("#gentabs").hide();
                                       
    <?php
} else {
    ?>
                submitToDoNewPdf();
<?php } ?>
        //showLoading();
        
    });
    var optionsReg = {
        beforeSubmit: function() {
            //showLoading();
        },
        success: function(responseText, statusText, xhr, $form) {
            
            $("#report_viewer").html(responseText);
            hideLoading();

        }
    };

    function submitToDoRpt(todo, multi_submit) {
        if (!multi_submit) multi_submit = false;  
        document.getElementById('todo').value = todo;
        if (!form_submitted && check_form() && !multi_submit) {
            showLoading();
            $('#popup_gen').attr('target', '_self')
            form_submitted = true;
            $("#popup_gen").ajaxSubmit(optionsReg);
        } else if (multi_submit) {
            $('#popup_gen').attr('target', '_self')
            $("#popup_gen").ajaxSubmit(optionsReg);						 
        }
    }
    
    function submitToDoNewPdf(){
        $('#popup_gen').attr('target', '_blank')
        submitToDo('exp_pdf', true);
    }
    
    function submitToDoNewCsv(){
        $('#popup_gen').attr('target', '_self')
        submitToDo('exp_csv', true);
    }
    
    
    
    
</script>


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
//  Path: /modules/ucform/pages/popup_gen/template_filter.php
//

//$pdf_type is to indicate which report will be send as attachment.
$pdf_type="";
if ($_GET['gID'] == "cust:inv" || $_GET['gID'] == "cust:quot" || $_GET['gID'] == "cust:so" || $_GET['gID'] == "cust:cm" || $_GET['gID'] == "vend:po" || $_GET['gID'] == "vend:cm" || $_GET['gID'] == "vend:quot") {
	if($_GET['jID']==12) $pdf_type="inv";
    echo html_form('popup_gen', FILENAME_DEFAULT, "module=ucbooks&page=print_invoice&oID=" . $_GET['xmin'] . "&jID=" . $_GET['jID'] . "&action=email&pdf_type=".$pdf_type);
} else {
    echo html_form('popup_gen', FILENAME_DEFAULT, gen_get_all_get_params(array('action', 'gID')));
}

// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('title', $report->title) . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="self.close()"';
$toolbar->icon_list['open']['show'] = false;
//$toolbar->icon_list['print']['params'] = 'onclick="submitToDo(\'exp_pdf\', true)"';
$toolbar->icon_list['print'] = array(
    'show' => true,
    'icon' => 'mimetypes/text-html.png',
    'params' => 'onclick="submitToDoNewPdf()"',
    'text' => 'PDF',
    'order' => '15',
);
$toolbar->icon_list['delete']['show'] = false;
if ($report->reporttype == 'rpt' || $report->title == 'Customer Statement') {
    $toolbar->icon_list['save']['params'] = 'onclick="entrySave();"';
    if ($report->title != 'Customer Statement') {
        $toolbar->add_icon('export_csv', 'onclick="submitToDoNewCsv()"', $order = 10);
    }
    $toolbar->icon_list['export_html'] = array(
        'show' => true,
        'icon' => 'mimetypes/text-html.png',
        'params' => 'onclick="submitToDoRpt(\'exp_html\', true)"',
        'text' => TEXT_GENERATE_HTML,
        'order' => '9',
    );


    $toolbar->icon_list['export_xml'] = array(
        'show' => true,
        'icon' => 'mimetypes/text-x-script.png',
        'params' => 'onclick="submitToDo(\'exp_xml\', true)"',
        'text' => 'export xml',
        'order' => '12',
    );

    $toolbar->add_icon('copy', 'onclick="querySaveAs()"', $order = 13);
} else {
    $toolbar->icon_list['save']['show'] = false;
}
$toolbar->add_help('11.02');
if ($error == 0) {
    echo $toolbar->build_toolbar();
}
?>

<?php if ($error == 1) { ?>

    <!--    <b class="no_data">No Data Found !!</b>-->
    <b class="no_data">There is not enough data to generate this
        report, when you have entered the relevan't data, you can generate
        this report</b>

<?php } ?>


<!-- start the tabsets -->
<?php if (sizeof($r_list) > 1) { ?>
    <h1><?php //echo UCFORM_GROUP . $title;   ?></h1>
<?php } else { ?>
    <?php if ($error == 0) { ?>
        <h1><?php echo ($report->reporttype == 'frm' ? TEXT_FORM : TEXT_REPORT) . ': ' . ($report->title); ?></h1>
        <?php
    }
}
?>
<br/><br/>
<div style ="display: none">
    <table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto;">
        <thead class="ui-widget-header">
            <tr><th colspan="3"><?php echo TEXT_DELIVERY_METHOD; ?></th></tr>
        </thead>
        <tbody class="ui-widget-content">
<!--            <tr>
                <td align="center"><?php echo TEXT_BROWSER . html_radio_field('delivery_method', 'I', ($delivery_method == 'I') ? true : false, '', 'onclick="hideEmail();"', false); ?></td>
                <td align="center"><?php echo TEXT_DOWNLOAD . html_radio_field('delivery_method', 'D', ($delivery_method == 'D') ? true : false, '', 'onclick="hideEmail();"', false); ?></td>
                <td align="center"><?php echo TEXT_EMAIL . html_radio_field('delivery_method', 'S', ($delivery_method == 'S') ? true : false, '', 'onclick="hideEmail();"', false); ?></td>
            </tr>-->
        <input type="hidden" id="delivery_method" name="delivery_method" value="I" />
        </tbody>
    </table>
</div>
<div id="rpt_email" style="display:none">
    <table class="ui-widget" style="border-style:none;width:100%">
        <thead class="ui-widget-header">
            <tr><th colspan="3"><?php echo TEXT_DELIVERY_METHOD; ?></th></tr>
        </thead>
        <tbody class="ui-widget-content">
            <tr>
                <td align="right"><?php echo TEXT_SENDER_NAME; ?></td>
                <td><?php echo html_input_field('from_name', $from_name) . ' ' . TEXT_EMAIL . html_input_field('from_email', $from_email, 'size="40"'); ?></td>
            </tr>
            <tr>
                <td align="right"><?php echo TEXT_RECEPIENT_NAME; ?></td>
                <td><?php echo html_input_field('to_name', $to_name) . ' ' . TEXT_EMAIL . html_input_field('to_email', $to_email, 'size="40"'); ?></td>
            </tr>
            <tr>
                <td align="right"><?php echo TEXT_CC_NAME; ?></td>
                <td><?php echo html_input_field('cc_name', $cc_name) . ' ' . TEXT_EMAIL . html_input_field('cc_email', $cc_email, 'size="40"'); ?></td>
            </tr>
            <tr>
                <td align="right"><?php echo TEXT_MESSAGE_SUBJECT; ?></td>
                <td><?php echo html_input_field('message_subject', $message_subject, 'size="75"'); ?></td>
            </tr>
            <tr>
                <td align="right" valign="top"><?php echo TEXT_MESSAGE_BODY; ?></td>
                <td><?php echo html_textarea_field('message_body', '60', '8', $message_body); ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php if (sizeof($r_list) > 1) { ?>
    <div id="frm_select" style="display: none">
        <p><?php echo GEN_HEADING_PLEASE_SELECT; ?></p>
        <?php
        foreach ($r_list as $value) {
            echo '<div>' . html_radio_field('rID', $value['id'], false, '', 'onchange="fetchEmailMsg()" checked="checked"');
            echo '&nbsp;' . $value['text'] . '</div>' . chr(10);
            break;
        }
        ?>
    <!--        <input type="hidden" name="delivery_method" value="D" onclick="hideEmail();"/>-->
    </div>
    <?php
} elseif (!$rID) {
    echo TEXT_NO_DOCUMENTS;
} else {
    echo html_hidden_field('rID', $rID) . chr(10);
    ?>

    <div id="gentabs" style="background-color: #fff;">
        <ul style="display: none">
            <?php
            foreach ($tab_list as $key => $value)
                echo add_tab_list('tab_' . $key, $value);
            ?>
        </ul>

        <div  id="tab_crit">
            <div id="rpt_body" <?php if ($error == 1) { ?> style="display: none;" <?php } ?> >
                <table class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto; background: #fff; border: 1px solid #aaaaaa;">
                    <thead  class="ui-widget-header">
                        <tr><th colspan="5"><?php echo TEXT_CRITERIA_SETUP; ?></th></tr>
                    </thead>
                    <tbody class="ui-widget-content">
                        <?php
                        if ($report->datelist <> '') {
                            if ($report->datelist == 'z') { // special case for period pull-down
                                echo '<tr align="left"><td>' . TEXT_PERIOD . '</td>';
                                echo '<td colspan="3">' . chr(10);
                                echo html_pull_down_menu('period', gen_get_period_pull_down(false), CURRENT_ACCOUNTING_PERIOD);
                                echo '</td><td>';
                                echo '<a href="javascript:" onclick="submitToDoRpt(\'exp_html\', true)">Update</a>';
                                echo '</td></tr>' . chr(10);
                            } else {
                                
                                ?>
                                <tr  class="ui-widget-header">
                                    <th colspan="2">&nbsp;</th>
                                    <th align="center"><?php echo TEXT_FROM; ?></th>
                                    <th align="center"><?php echo TEXT_TO; ?></th>
                                    <th align="center">&nbsp;</th>
                                </tr>
                                <tr  <?php if ($error == 1) { ?> style="display: block" <?php } else { ?>  <?php } ?> >
                                    <td><?php echo TEXT_DATE; ?></td>
                                    <td>
                                        <?php
                                        //echo html_pull_down_menu('datedefault', gen_build_pull_down($ValidDateChoices), $DateArray[0]); 
                                        echo html_pull_down_menu('datedefault', gen_build_pull_down($ValidDateChoices), "f");
                                        ?>
                                    </td>
                                    <td><?php echo html_calendar_field($cal_from,''); ?></td>
                                    <td><?php echo html_calendar_field($cal_to,''); ?></td>
                                    <td>
                                        <?php echo '<a href="javascript:" onclick="submitToDoRpt(\'exp_html\', true)">Update</a>'; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        if ($report->reporttype == 'rpt' && $report->grouplist <> '') {
                            ?>
                            <tr  >
                                <td><?php echo TEXT_GROUP; ?></td>
                                <td colspan="4">
                                    <?php echo html_pull_down_menu('defgroup', $group_list, $group_default); ?>
                                    <?php echo ' ' . TEXT_GROUP_PAGE_BREAK . ' ' . html_checkbox_field('grpbreak', '1', $group_break); ?>
                                </td>
                            </tr>
                            <?php
                        } // end if ($grouplist)
                        if ($report->reporttype == 'rpt' && $report->sortlist <> '' || $report->title == 'Customer Statement') {
                            ?>
                            <tr  >
                                <td><?php echo TEXT_SORT; ?></td>
                                <td colspan="4"><?php echo html_pull_down_menu('defsort', $sort_list, $sort_default); ?></td>
                            </tr>
                            <?php
                        } // end if ($sortlist)
                        if ($report->reporttype == 'rpt') {
                            ?>
                            <tr >
                                <td><?php echo TEXT_TRUNC; ?></td>
                                <td colspan="4">
                                    <?php echo html_radio_field('deftrunc', '0', ($report->truncate <> '1') ? true : false) . TEXT_NO; ?>
                                    <?php echo html_radio_field('deftrunc', '1', ($report->truncate == '1') ? true : false) . TEXT_YES; ?>
                                </td>
                            </tr>
                            <?php
                        }

                        if ($report->filterlist <> '') {
                            ?>
                            <tr  class="ui-widget-header">
                                <th><?php echo TEXT_FILTER; ?></th>
                                <th><?php echo TEXT_TYPE; ?></th>
                                <th><?php echo TEXT_FROM; ?></th>
                                <th><?php echo TEXT_TO; ?></th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php
                            foreach ($report->filterlist as $key => $LineItem) {
                                // retrieve the dropdown based on the params field (dropdown type)
                                $CritBlocks = explode(':', $CritChoices[$LineItem->type]);
                                $numInputs = array_shift($CritBlocks); // will be 0, 1 or 2
                                $filter_choices = array();
                                foreach ($CritBlocks as $value) {
                                    if ($LineItem->description == 'Branch ID') {
                                        if ($value != 'RANGE') {
                                            $filter_choices[] = array('id' => $value, 'text' => constant('TEXT_' . $value));
                                        }
                                    } else {
                                        $filter_choices[] = array('id' => $value, 'text' => constant('TEXT_' . $value));
                                    }
                                }
                                if ($LineItem->visible) {
                                    $field_0 = html_pull_down_menu('defcritsel' . $key, $filter_choices, $LineItem->default);
                                    $field_1 = html_input_field('fromvalue' . $key, $LineItem->min_val, 'size="21" maxlength="20"');
                                    $field_2 = html_input_field('tovalue' . $key, $LineItem->max_val, 'size="21" maxlength="20"');
                                } else {
                                    $field_0 = html_hidden_field('defcritsel' . $key, $LineItem->default ? $LineItem->default : $CritBlocks[0]) . chr(10);
                                    $field_1 = html_hidden_field('fromvalue' . $key, $LineItem->min_val);
                                    $field_2 = html_hidden_field('tovalue' . $key, $LineItem->max_val);
                                }
                                echo '<tr ' . ($LineItem->visible ? '' : ' style="display:none"') . '>' . chr(10);
                                echo '<td>' . $LineItem->description . '</td>' . chr(10); // add the description
                                echo '<td>' . $field_0 . '</td>' . chr(10);
                                echo '<td>' . ($numInputs >= 1 ? $field_1 : '&nbsp;') . '</td>' . chr(10);
                                echo '<td>' . ($numInputs == 2 ? $field_2 : '&nbsp;') . '</td>' . chr(10);
                                echo '<td>&nbsp;</td>' . chr(10);
                                echo '</tr>' . chr(10);
                            }
                            if ($report->title == 'Customer Statement') {
                                ?>
                                <tr >
                                    <td><?php echo 'Show only Outstanding Amounts : '; ?></td>
                                    <td colspan="4">
                                        <input type="checkbox" name="open_invoice" checked="checked" id="open_invoice"/>
                                    </td>
                                </tr>
                                <?php
                            }
                        } // end if ($filterlist <> '') 
                        ?>

                    </tbody>
                </table>
                <br/><br/>
            </div>
        </div>
        <div style="clear: both"></div>
    <?php } ?>

    <?php
    echo "<div id='report_form'>";
    ?>
    <?php // ********************  end criteria tab, start fields tab **************************    ?>
    <?php if ($report->reporttype == 'rpt' || $report->title == 'Customer Statement') { ?>
        <div id="tab_field">
            <table><tr><td>
                        <table id="field_setup" class="ui-widget" style="border-collapse:collapse;width:100%">
                            <thead  class="ui-widget-header">
                                <tr><th id="fieldListHeading" colspan="10"><?php echo TEXT_FIELD_LIST; ?></th></tr>
                                <tr>
                                    <th><?php echo UCFORM_TBLFNAME; ?></th>
                                    <th><?php echo UCFORM_DISPNAME; ?></th>
                                    <th><?php echo TEXT_COLUMN . '<br />' . TEXT_BREAK; ?></th>
                                    <th><?php echo TEXT_COLUMN . '<br />' . TEXT_WIDTH; ?></th>
                                    <th><?php echo TEXT_TOTAL . '<br />' . TEXT_WIDTH; ?></th>
                                    <th><?php echo TEXT_SHOW . '<br />' . TEXT_FIELD; ?></th>
                                    <th><?php echo UCFORM_TEXTPROC; ?></th>
                                    <th><?php echo TEXT_TOTAL . '<br />' . TEXT_COLUMN; ?></th>
                                    <th><?php echo TEXT_ALIGN; ?></th>
                                    <th><?php echo TEXT_ACTION; ?></th>
                                </tr>
                            </thead>
                            <tbody  class="ui-widget-content">
                                <?php for ($i = 0; $i < sizeof($report->fieldlist); $i++) { ?>
                                    <tr>
                                        <td nowrap="nowrap"><?php echo html_combo_box('fld_fld[]', CreateSpecialDropDown($report), $report->fieldlist[$i]->fieldname, '', '220px', '', 'fld_combo_' . $i); ?></td>
                                        <td><?php echo html_input_field('fld_desc[]', $report->fieldlist[$i]->description, 'size="20" maxlength="25"'); ?></td>
                                        <td><?php echo html_pull_down_menu('fld_brk[]', $nyChoice, $report->fieldlist[$i]->columnbreak, 'onchange="calculateWidth()"'); ?></td>
                                        <td><?php echo html_input_field('fld_clmn[]', ($report->fieldlist[$i]->columnwidth ? $report->fieldlist[$i]->columnwidth : PF_DEFAULT_COLUMN_WIDTH), 'size="4" maxlength="3" onchange="calculateWidth()"'); ?></td>
                                        <td align="center"><?php echo '&nbsp;'; ?></td>
                                        <td><?php echo html_pull_down_menu('fld_vis[]', $nyChoice, $report->fieldlist[$i]->visible, 'onchange="calculateWidth()"'); ?></td>
                                        <td><?php echo html_pull_down_menu('fld_proc[]', $pFields, $report->fieldlist[$i]->processing); ?></td>
                                        <td><?php echo html_pull_down_menu('fld_tot[]', $nyChoice, $report->fieldlist[$i]->total); ?></td>
                                        <td><?php echo html_pull_down_menu('fld_algn[]', $kFontAlign, $report->fieldlist[$i]->align); ?></td>
                                        <td nowrap="nowrap" align="center">
                                            <?php
                                            echo html_icon('actions/view-fullscreen.png', TEXT_MOVE, 'small', 'style="cursor:move"', '', '', 'move_fld_' . $i) . chr(10);
                                            echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick="if (confirm(\'' . TEXT_DELETE_ENTRY . '\')) rowAction(\'field_setup\', \'delete\'); calculateWidth();"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td valign="bottom">
                        <?php echo html_icon('actions/list-add.png', TEXT_ADD, 'small', 'onclick="rowAction(\'field_setup\', \'add\')"'); ?>
                    </td></tr>
            </table>
        </div>
    <?php } ?>
    <?php // ********************  end fields tab, start page setup tab **************************    ?>
    <?php if ($report->reporttype == 'rpt') { ?>
        <div id="tab_page">
            <table  class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto;">
                <thead class="ui-widget-header">
                    <tr><th colspan="8"><?php echo UCFORM_PGLAYOUT ?></th></tr>
                </thead>
                <tbody class="ui-widget-content">
                    <tr>
                        <td colspan="4" align="center">
                            <?php echo TEXT_PAPER . ' ' . html_pull_down_menu('papersize', gen_build_pull_down($PaperSizes), $report->page->size, 'onchange="calculateWidth()"'); ?>
                        </td>
                        <td colspan="4" align="center">
                            <?php echo TEXT_ORIEN . ' ' . html_radio_field('paperorientation', 'P', ($report->page->orientation == 'P') ? true : false, '', 'onchange="calculateWidth()"') . ' ' . TEXT_PORTRAIT; ?>
                            <?php echo ' ' . html_radio_field('paperorientation', 'L', ($report->page->orientation == 'L') ? true : false, '', 'onchange="calculateWidth()"') . '  ' . TEXT_LANDSCAPE; ?>
                        </td>
                    </tr>
                    <tr  class="ui-widget-header"><th colspan="8"><?php echo UCFORM_PGMARGIN; ?></th></tr>
                    <tr>
                        <td colspan="2" align="center"><?php echo TEXT_TOP . ' ' . html_input_field('margintop', $report->page->margin->top, 'size="5" maxlength="3"') . ' ' . TEXT_MM; ?></td>
                        <td colspan="2" align="center"><?php echo TEXT_BOTTOM . ' ' . html_input_field('marginbottom', $report->page->margin->bottom, 'size="5" maxlength="3"') . ' ' . TEXT_MM; ?></td>
                        <td colspan="2" align="center"><?php echo TEXT_LEFT . ' ' . html_input_field('marginleft', $report->page->margin->left, 'size="5" maxlength="3" onchange="calculateWidth()"') . ' ' . TEXT_MM; ?></td>
                        <td colspan="2" align="center"><?php echo TEXT_RIGHT . ' ' . html_input_field('marginright', $report->page->margin->right, 'size="5" maxlength="3" onchange="calculateWidth()"') . ' ' . TEXT_MM; ?></td>
                    </tr>
                    <tr class="ui-widget-header"><th colspan="8"><?php echo UCFORM_PGHEADER; ?></th></tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                        <td align="center"><?php echo TEXT_SHOW; ?></td>
                        <td align="center"><?php echo TEXT_FONT; ?></td>
                        <td align="center"><?php echo TEXT_SIZE; ?></td>
                        <td align="center"><?php echo TEXT_COLOR; ?></td>
                        <td align="center"><?php echo TEXT_ALIGN; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><?php echo TEXT_PGCOYNM; ?></td>
                        <td align="center"><?php echo html_checkbox_field('coynameshow', '1', ($report->page->heading->show == '1') ? true : false); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('coynamefont', $kFonts, $report->page->heading->font); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('coynamesize', $kFontSizes, $report->page->heading->size); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('coynamecolor', $kFontColors, $report->page->heading->color); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('coynamealign', $kFontAlign, $report->page->heading->align); ?></td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" colspan="3"><?php echo UCFORM_PGTITL1 . ' ' . html_input_field('title1desc', $report->page->title1->text, 'size="30" maxlength="50"'); ?></td>
                        <td align="center"><?php echo html_checkbox_field('title1show', '1', ($report->page->title1->show == '1') ? true : false); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('title1font', $kFonts, $report->page->title1->font); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('title1size', $kFontSizes, $report->page->title1->size); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('title1color', $kFontColors, $report->page->title1->color); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('title1align', $kFontAlign, $report->page->title1->align); ?></td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" colspan="3"><?php echo UCFORM_PGTITL2 . ' ' . html_input_field('title2desc', $report->page->title2->text, 'size="30" maxlength="50"'); ?></td>
                        <td align="center"><?php echo html_checkbox_field('title2show', '1', ($report->page->title2->show == '1') ? true : false); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('title2font', $kFonts, $report->page->title2->font); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('title2size', $kFontSizes, $report->page->title2->size); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('title2color', $kFontColors, $report->page->title2->color); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('title2align', $kFontAlign, $report->page->title2->align); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><?php echo UCFORM_PGFILDESC; ?></td>
                        <td align="center"><?php echo html_pull_down_menu('filterfont', $kFonts, $report->page->filter->font); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('filtersize', $kFontSizes, $report->page->filter->size); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('filtercolor', $kFontColors, $report->page->filter->color); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('filteralign', $kFontAlign, $report->page->filter->align); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><?php echo UCFORM_RPTDATA; ?></td>
                        <td align="center"><?php echo html_pull_down_menu('datafont', $kFonts, $report->page->data->font); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('datasize', $kFontSizes, $report->page->data->size); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('datacolor', $kFontColors, $report->page->data->color); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('dataalign', $kFontAlign, $report->page->data->align); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><?php echo UCFORM_TOTALS; ?></td>
                        <td align="center"><?php echo html_pull_down_menu('totalsfont', $kFonts, $report->page->totals->font); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('totalssize', $kFontSizes, $report->page->totals->size); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('totalscolor', $kFontColors, $report->page->totals->color); ?></td>
                        <td align="center"><?php echo html_pull_down_menu('totalsalign', $kFontAlign, $report->page->totals->align); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
<?php
if ($report->title == 'Customer Statement') {
    echo "<b>This report's full information is only available in pdf format.</b>";
}
?>
</div>
</form>

<?php
if ($_GET['type'] == "frm" && $report->title != 'Customer Statement' || $report->reporttype == 'frm' && $report->title != 'Customer Statement') {
    ?>
    <?php if ($error == 1) { ?>
        <style type="text/css">
            #report_viewer{display: block;}

        </style>
    <?php } else { ?>
        <style type="text/css">
            #report_viewer{display: none;}
        </style>
        <div id="frm_msg" >
            <b>This report type is only available in pdf format.</b>

        </div>
        <?php
    }
}
?>
<div id="report_viewer">

</div>