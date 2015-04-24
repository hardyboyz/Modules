<style type="text/css">
    input[type='file'] {width: 215px;}
    .ui-widget a{margin: 0  10px}
    /*   #sample_csv_contacts{ background-color: blue !important; border-radius: 5px !important; padding: 3px !important; display: block !important; height: 40px !important; width: auto !important;}*/

    #sample_csv_contacts{
        color: #FFFFFF !important;
        display: inline-block;
        font: bold 13px 'Trebuchet MS';
        margin-left: 0px;
        background: none !important;
    }
    #sample_csv_contacts:hover{
        color:black !important;
    }
    #import_table_contacts{
        color: #FFFFFF !important;
        display: inline-block;
        font: bold 13px 'Trebuchet MS';
        margin-left: 0px;
        background: none !important;
    }

    #import_table_contacts:hover {
        color:black !important;
    }

    #import_format_contacts_{
        display: none;
    }


    #sample_csv_inventory {
        color: #FFFFFF !important;
        display: inline-block;
        font: bold 13px 'Trebuchet MS';
        margin-left: 0px;
        background: none !important;
    }
    #sample_csv_inventory:hover {
        color:black !important;
    }

    #import_table_inventory{
        color: #FFFFFF !important;
        display: inline-block;
        font: bold 13px 'Trebuchet MS';
        margin-left: 0px;
        background: none !important;
        width:100% !important;
    }
    #import_format_inventory_csv{
        display: none;
    }
    #import_table_inventory:hover {
        color:black !important;
    }
    #import_format_inventory_{
        display: none;
    }
</style>
<script type="text/javascript">
    function submit_wizard(obj) {
        $(obj).parents('#turnoff').submit();
    }

    function submit_upload_inventory(obj) {
        $(obj).parents('#import_export').find('#todo').val('import_table_inventory');
        $(obj).parents('#import_export').submit();
    }
    function submit_upload_type(obj) {
        //alert($(obj).find(".up").html());
        var submitval = $(obj).attr('rel');
        $('#upload_type').val($(obj).find(".up").html());
        $(obj).parents('#import_export').find('#todo').val(submitval);
        $(obj).parents('#import_export').submit();
    }
</script>
<?php
$setupwizard_status = mysql_query("select configuration_key, configuration_value from configuration where configuration_key = 'SETUP_WIZARD_STATUS'");
$status = mysql_fetch_object($setupwizard_status);
if ($status->configuration_value == 'true') {
    $show_text = "Turn off Wizard On Login";
    $class = 'sgreen';
} else {
    $show_text = "Turn on Wizard On Login";
    $class = 'sgray';
}
?>
<?php echo html_form('turnoff', 'index.php?module=ucounting&page=admin', '') . chr(10); ?>
<a style="float: right" class="<?php echo $class ?>" href="javascript:" onclick="submit_wizard(this)" ><?php echo $show_text ?></a>    
<input type="hidden" value="<?php echo $status->configuration_value ?>" name="wizard">	
</form>
<div class="clear"></div>




<?php
//echo html_form('import_export', 'index.php?module=ucounting&page=import_export', '', 'post', 'enctype="multipart/form-data"') . chr(10);
//echo html_hidden_field('todo', '') . chr(10);
//echo html_hidden_field('subject', $subject) . chr(10);
?>
<div  id="wizard" class="swMain">
    <ul class="anchor">
        <li><a class="done <?php
            if ($setupwizard_menu == 'step_1') {
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=first_dashboard', 'SSL') ?>"  isdone="1" rel="1">
                <label class="stepNumber">1</label>
                <span class="stepDesc">
                    Step 1<br>
                    <small>Setup Company</small>
                </span>
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_4') {
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=chart_of_account', 'SSL') ?>"   isdone="1" rel="4">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                    Step 2<br>
                    <small>Setup Chart of Accounts</small>
                </span>                   
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_7') { //used for step3
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=setup_account_defaults', 'SSL') ?>"   isdone="1" rel="4">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                    Step 3<br>
                    <small>Setup Account Defaults</small>
                </span>                   
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_8') { //used for step3
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=setup_tax_codes', 'SSL') ?>"   isdone="1" rel="4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                    Step 4<br>
                    <small>Setup Tax Codes</small>
                </span>                   
            </a>
        </li>
        <?php
        echo html_form('import_export', 'index.php?module=ucounting&page=import_export', '') . chr(10);
        echo html_hidden_field('todo', '') . chr(10);
        echo html_hidden_field('subject', $subject) . chr(10);
        ?>
        <input type="hidden" id="upload_type" name="upload_type" value="" />
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_2') {
                echo 'selected';
                //submit_upload_type go_contacts
            }
            ?>" href="javascript:" onclick="submit_upload_type(this)" rel="go_contacts"  isdone="1" rel="2">
                <label class="stepNumber">5</label>
                <span class="stepDesc">
                    Step 5<br>
                    <small class="up">Upload Contacts</small>
                </span>
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_3') {
                echo 'selected';
            }
            ?>" href="javascript:" onclick="submit_upload_type(this)" rel="go_inventory"  isdone="1" rel="3">
                <label class="stepNumber">6</label>
                <span class="stepDesc">
                    Step 6<br>
                    <small class="up">Upload Inventory</small>
                </span>                   
            </a>
        </li>
        </form>
        <li>

            <a class="done <?php
            if ($setupwizard_menu == 'step_5') {
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=print_template&action_type=print_template', 'SSL') ?>"   isdone="1" rel="5">
                <label class="stepNumber">7</label>
                <span class="stepDesc">
                    Step 7<br>
                    <small>Setup Print Template</small>
                </span>                   
            </a>

        </li>
        <li>

            <a class="done <?php
            if ($setupwizard_menu == 'step_6') {
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=tools&action_type=transaction_sequence', 'SSL') ?>"   isdone="1" rel="6">
                <label class="stepNumber">8</label>
                <span class="stepDesc">
                    Step 8<br>
                    <small>Setup Transaction Sequence</small>
                </span>                   
            </a>

        </li>
        <!--        <li><a class="done <?php
        if ($setupwizard_menu == 'step_4') {
            echo 'selected';
        }
        ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=fourth_dashboard', 'SSL') ?>"  isdone="1" rel="4">
                        <label class="stepNumber">4</label>
                        <span class="stepDesc">
                            Step 4<br>
                            <small>Create Transactions</small>
                        </span>                   
                    </a></li>-->

        <!--<li><a class="done <?php
        if ($setupwizard_menu == 'step_5') {
            echo 'selected';
        }
        ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=fifth_dashboard', 'SSL') ?>"  isdone="1" rel="4">
            <label class="stepNumber">5</label>
            <span class="stepDesc">
                Step 5<br>
                <small>Create Transactions</small>
            </span>                   
        </a></li>-->


    </ul>

</div>
<div style="clear: both"></div>
<br/>

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
//  Path: /modules/ucounting/pages/import_export/template_modules.php
//
echo html_form('import_export', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"', true) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('subject', $subject) . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=ucounting&page=import_export"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['save']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
if (count($extra_toolbar_buttons) > 0)
    foreach ($extra_toolbar_buttons as $key => $value)
        $toolbar->icon_list[$key] = $value;
$toolbar->add_help('10');
echo $toolbar->build_toolbar();
// Build the page
?>
<?php if ($subject == 'contacts') { ?>
    <h1>Upload Contacts<?php //echo PAGE_TITLE;                ?></h1>
    <input type="hidden" name="second_dashboard" value="second_dashboard" />
<?php } else if ($subject == 'inventory') { ?>
    <h1>Upload Inventory<?php //echo PAGE_TITLE;                ?></h1> 
    <input type="hidden" name="third_dashboard" value="third_dashboard" />
<?php } ?>
<fieldset>
<!--<legend><?php echo TEXT_IMPORT_EXPORT_INFO; ?></legend>-->
    <table class="ui-widget" style="border-style:none;width:100%">
  <!--	<tr><td><?php echo GEN_IMPORT_EXPORT_MESSAGE; ?></td></tr>
          <tr><td>
          <table class="ui-widget" style="border-collapse:collapse;width:100%">
           <thead class="ui-widget-header">
              <tr><th colspan="4"><?php echo GEN_TABLES_AVAILABLE . TEXT_IMPORT . '/' . TEXT_EXPORT; ?></th></tr>
           </thead>-->
        <tbody class="ui-widget-content">
            <?php
            foreach ($page_list as $mod => $params) {
                if ($subject <> $mod)
                    continue; // only this module
                $structure = $params['structure'];
                if (!$structure->Module->Table)
                    continue; // no tables to import
                foreach ($structure->Module->Table as $table) {
                    ?>
                    <tr>
        <!--		  <td><?php echo TEXT_MODULE . ' - ' . $mod; ?></td>
                          <td><?php echo TEXT_TABLE . ' - ' . $table->Name; ?></td>-->
                        <td>
                            <div style="width:100%; height:150px; margin-top: 10px;">
                                <div style="float:left; height: 100px; margin-top:25px; margin-left: 20px;">
                                    <div style="float:left; width: auto;">Download sample CSV file:<br/><br/><span class="button_bg"><?php echo html_button_field('sample_csv_' . $table->Name, SAMPLE_CSV, 'onclick="submitToDo(\'sample_csv_' . $table->Name . '\',true)"'); ?>
                                        </span>

                                    </div>

                                    <div style="float:left; height: 100px; margin-bottom: 10px;margin-left: 20px;">
                                        <div style="float:left; width: auto;">Upload sample CSV file:<br/><br/>

                                            <?php echo html_radio_field('import_format_' . $table->Name, 'csv', true, '', '') . ' '; ?>


        <?php echo html_file_field('file_name_' . $table->Name) . ' '; ?>
                                        </div>
                                    </div>
                                    <div style="float:left; height: 100px; margin-top: 20px; margin-left: 20px;">
                                        <span class="button_bg"><?php echo html_button_field('import_table_' . $table->Name, TEXT_IMPORT . ' ' . $table->Name, 'onclick="submit_upload_inventory(this)"'); ?>

                                        </span>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>

                                <!--                                <div style="float:left; width: 1000px; margin-top: 0px;">
                                
                                                                    <span style="font-size:15px; font-weight: bold; color: black;"> Upload Csv file
                                
                                                                      
                                
                                                                </div>-->
                                <div style="clear:both;"></div>
                            </div>

        <?php //echo html_button_field('sample_xml_' . $table->Name, SAMPLE_XML, 'onclick="submitToDo(\'sample_xml_' . $table->Name . '\',true)"') . ' ';   ?>

                        </td>
                    </tr>
                    <?php
                    if ($table->Name != "contacts") {
                        break;
                    } else if ($table->Name != "inventory") {
                        break;
                    }
                }
            }
            ?>
        </tbody>
    </table>
</td></tr>
</table>
</fieldset>

<!--<fieldset>
    <legend><?php echo TEXT_IMPORT; ?></legend>
    <table class="ui-widget" style="border-style:none;width:100%">
        <tr><td><?php echo GEN_IMPORT_MESSAGE; ?></td></tr>
        <tr><td>
                <table class="ui-widget" style="border-collapse:collapse;width:100%">
                    <thead class="ui-widget-header">
                        <tr><th colspan="4"><?php echo GEN_TABLES_AVAILABLE . TEXT_IMPORT; ?></th></tr>
                    </thead>
                    <tbody class="ui-widget-content">
<?php
foreach ($page_list as $mod => $params) {
    if ($subject <> $mod)
        continue; // only this module
    $structure = $params['structure'];
    if (!$structure->Module->Table)
        continue; // no tables to import
    if (!is_array($structure->Module->Table))
        $structure->Module->Table = array($structure->Module->Table);
    foreach ($structure->Module->Table as $table) {
        //echo $table->Name;
//      echo '<pre>';
//      
//      print_r($table) ;
//      echo '</pre>';

        if ($table->CanImport) {
            ?>
                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                        <td><?php echo TEXT_MODULE . ' - ' . $mod; ?></td>
                                                                                                                                                                                                                                        <td><?php echo TEXT_TABLE . ' - ' . $table->Name; ?></td>
                                                                                                                                                                                                                                        <td>
            <?php //echo html_radio_field ('import_format_' . $table->Name, 'xml', true, '', '') . ' ' . TEXT_XML . ' ';  ?>
            <?php //echo html_radio_field('import_format_' . $table->Name, 'csv', true, '', '') . ' ' . TEXT_CSV . ' '; ?>
            <?php echo html_file_field('file_name_' . $table->Name) . ' '; ?>
            <?php echo html_button_field('import_table_' . $table->Name, TEXT_IMPORT . ' ' . $table->Name, 'onclick="submitToDo(\'import_table_' . $table->Name . '\',true)"'); ?>
                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                    </tr>
            <?php
        }
        if ($table->Name != "contacts") {
            break;
        } else if ($table->Name != "inventory") {
            break;
        }
    }
}
?>
                    </tbody>
                </table>
            </td></tr>
    </table>
</fieldset>-->

<!--<fieldset>
<legend><?php echo TEXT_EXPORT; ?></legend>
  <table class="ui-widget" style="border-style:none;width:100%">
        <tr><td><?php echo GEN_EXPORT_MESSAGE; ?></td></tr>
        <tr><td>
        <table class="ui-widget" style="border-collapse:collapse;width:100%">
         <thead class="ui-widget-header">
            <tr><th colspan="4"><?php echo GEN_TABLES_AVAILABLE . TEXT_EXPORT; ?></th></tr>
         </thead>
         <tbody class="ui-widget-content">
<?php
foreach ($page_list as $mod => $params) {
    if ($subject <> $mod)
        continue; // only this module
    $structure = $params['structure'];
    if (!$structure->Module->Table)
        continue; // no tables to import
    if (!is_array($structure->Module->Table))
        $structure->Module->Table = array($structure->Module->Table);
    foreach ($structure->Module->Table as $table) {
        ?>
                                                                                                                                                            <tr>
                                                                                                                                                                  <td><?php echo TEXT_MODULE . ' - ' . $mod; ?></td>
                                                                                                                                                                  <td><?php echo TEXT_TABLE . ' - ' . $table->Name; ?></td>
                                                                                                                                                              <td>
        <?php e//cho html_radio_field ('export_format_' . $table->Name, 'xml', true, '', '') . ' ' . TEXT_XML . ' '; ?>
        <?php echo html_radio_field('export_format_' . $table->Name, 'csv', ture, '', '') . ' ' . TEXT_CSV . ' '; ?>
        <?php echo html_button_field('export_table_' . $table->Name, TEXT_EXPORT . ' ' . $table->Name, 'onclick="submitToDo(\'export_table_' . $table->Name . '\',true)"'); ?>
                                                                                                                                                              </td>
                                                                                                                                                                </tr>
        <?php
    }
}
?>
          </tbody>
         </table>
        </td></tr>
  </table>
</fieldset>-->
</form>

<br/><br/>
