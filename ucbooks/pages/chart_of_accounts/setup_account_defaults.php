<style>
    table{
        float: left;
    }
    .action_btn{
        float: left;
    }
</style>
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
        echo html_form('chart_of_accounts', FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=setup_account_defaults&action=save', 'post', 'enctype="multipart/form-data"') . chr(10);
        // include hidden fields
        echo html_hidden_field('todo', '') . chr(10);
        echo html_hidden_field('subject', '') . chr(10);
        echo html_hidden_field('rowSeq', '') . chr(10);
        ?>
        <input type="hidden" id="upload_type" name="upload_type" value="" />
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_2') {
                echo 'selected';
                //submit_upload_type go_contacts
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=import_export&action_type=go_contacts', 'SSL') ?>"rel="go_contacts"  isdone="1" rel="2">
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
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=import_export&action_type=go_inventory', 'SSL') ?>"  rel="go_inventory"  isdone="1" rel="3">
                <label class="stepNumber">6</label>
                <span class="stepDesc">
                    Step 6<br>
                    <small class="up">Upload Inventory</small>
                </span>                   
            </a>
        </li>
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
<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2007-2008 UcSoft, LLC                          |
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
//  Path: /modules/ucbooks/pages/chart_of_accounts/template_main.php
//
//echo html_form('chart_of_accounts', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"', true) . chr(10);
//echo html_form('chart_of_accounts', FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action=save', 'post', 'enctype="multipart/form-data"') . chr(10);
//// include hidden fields
//echo html_hidden_field('todo', '') . chr(10);
//echo html_hidden_field('subject', '') . chr(10);
//echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show'] = false;
if ($security_level > 1) {
    //$toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
    $toolbar->icon_list['save']['params'] = 'onclick="submitThis(this)"';
} else {
    $toolbar->icon_list['save']['show'] = false;
}
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
//echo $toolbar->build_toolbar();
?>
<input type="hidden" name="page_name" value="chart_of_accounts" />
<div class="bottom_btn" id="bottom_btn">
    <?php
    echo $toolbar->build_toolbar();
    ?>
    <div style="clear:both"></div>
</div>
<h1><?php echo $chart_of_accounts->title; ?></h1>
<div id="admintabs">
    <!--<ul>
    <?php
    echo add_tab_list('tab_general', TEXT_GENERAL);
    echo add_tab_list('tab_customers', MENU_HEADING_CUSTOMERS);
    echo add_tab_list('tab_vendors', MENU_HEADING_VENDORS);
    echo add_tab_list('tab_chart_of_accounts', GL_POPUP_WINDOW_TITLE);
    echo add_tab_list('tab_tax_auths', SETUP_TITLE_TAX_AUTHS);
    echo add_tab_list('tab_tax_auths_vend', SETUP_TITLE_TAX_AUTHS_VEND);
    echo add_tab_list('tab_tax_rates', SETUP_TITLE_TAX_RATES);
    echo add_tab_list('tab_tax_rates_vend', SETUP_TITLE_TAX_RATES_VEND);
    if (file_exists(DIR_FS_MODULES . $module . '/custom/pages/chart_of_accounts/template_tab_custom.php')) {
        echo add_tab_list('tab_custom', TEXT_CUSTOM_TAB);
    }
    echo add_tab_list('tab_stats', TEXT_STATISTICS);
    ?>
    </ul>-->
    <?php
//  require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_general.php');
//  require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_customers.php');
//  require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_vendors.php');
    require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_setup_account_defaults_dashboard.php');
//  require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_tax_auths.php');
//  require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_tax_auths_vend.php');
//  require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_tax_rates.php');
//  require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_tax_rates_vend.php');
//  if (file_exists(DIR_FS_MODULES . $module . '/custom/pages/chart_of_accounts/template_tab_custom.php')) {
//    require (DIR_FS_MODULES . $module . '/custom/pages/chart_of_accounts/template_tab_custom.php');
//  }
//  require (DIR_FS_MODULES . $module . '/pages/chart_of_accounts/template_tab_stats.php');
    ?>
</div>

<div class="bottom_btn" >
    <?php
    echo $toolbar->build_toolbar();
    ?>
</div>
</form>
<br/><br/><br/>
