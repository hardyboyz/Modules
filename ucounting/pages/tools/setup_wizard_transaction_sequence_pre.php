<script type="text/javascript">
    function submitThis(obj){
        $(obj).parents('#admin').submit();
    }
</script>
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
        <?php
        echo html_form('import_export', 'index.php?module=ucounting&page=import_export', '') . chr(10);
        echo html_hidden_field('todo', '') . chr(10);
        echo html_hidden_field('subject', $subject) . chr(10);
        ?>
        <li>

            <input type="hidden" name="second_dashboard" value="second_dashboard" />
            <a class="done <?php
        if ($setupwizard_menu == 'step_2') {
            echo 'selected';
        }
        ?>" href="javascript:" onclick="submitToDo('go_contacts',true)"   isdone="1" rel="2">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                    Step 2<br>
                    <small>Upload Contacts</small>
                </span>
            </a>

        </li>

        <li>

            <a class="done <?php
               if ($setupwizard_menu == 'step_3') {
                   echo 'selected';
               }
        ?>" href="javascript:" onclick="submitToDo('go_inventory',true)"  isdone="1" rel="3">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                    Step 3<br>
                    <small>Upload Inventory</small>
                </span>                   
            </a>

        </li>




        </form>
        <li>

            <a class="done <?php
               if ($setupwizard_menu == 'step_4') {
                   echo 'selected';
               }
        ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=chart_of_account', 'SSL') ?>"   isdone="1" rel="4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                    Step 4<br>
                    <small>Setup Chart of Accounts</small>
                </span>                   
            </a>

        </li>
        <li>

            <a class="done <?php
               if ($setupwizard_menu == 'step_5') {
                   echo 'selected';
               }
        ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=print_template&action_type=print_template', 'SSL') ?>"   isdone="1" rel="5">
                <label class="stepNumber">5</label>
                <span class="stepDesc">
                    Step 5<br>
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
                <label class="stepNumber">6</label>
                <span class="stepDesc">
                    Step 6<br>
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
//  Path: /modules/ucounting/pages/admin/template_main.php
//

//echo html_form('admin', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"') . chr(10);
echo html_form('admin', FILENAME_DEFAULT, 'module=ucounting&page=tools&action=ordr_nums', 'post', 'enctype="multipart/form-data"') . chr(10);
///ucountingnew2/index.php?module=ucounting&page=tools&

// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('subject', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show'] = false;
if ($security_level > 1) {
    $toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
} else {
    $toolbar->icon_list['save']['show'] = false;
}
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
//echo $toolbar->build_toolbar();
?>
<input type="hidden" name="setup_wizard_action" value="transaction_sequence" />
<h1>Transaction Sequence</h1>

<div id="admintabs">
    <!--<ul>
    <?php
    echo add_tab_list('tab_modules', MENU_HEADING_MODULES);
    echo add_tab_list('tab_company', MENU_HEADING_MY_COMPANY);
    echo add_tab_list('tab_config', MENU_HEADING_CONFIG);
    echo add_tab_list('tab_email', MENU_HEADING_EMAIL);
    echo add_tab_list('tab_currency', SETUP_TITLE_CURRENCIES);
    if (file_exists(DIR_FS_MODULES . $module . '/custom/pages/admin/template_tab_custom.php')) {
        echo add_tab_list('tab_custom', TEXT_CUSTOM_TAB);
    }
    echo add_tab_list('tab_manager', BOX_COMPANY_MANAGER);
    echo add_tab_list('tab_tools', TEXT_TOOLS);
    echo add_tab_list('tab_stats', TEXT_STATISTICS);
    ?>
    </ul>-->
    <?php
//  require (DIR_FS_MODULES . $module . '/pages/admin/template_tab_modules.php');
//  require (DIR_FS_MODULES . $module . '/pages/admin/template_tab_company.php');
//  require (DIR_FS_MODULES . $module . '/pages/admin/template_tab_config.php');
//  require (DIR_FS_MODULES . $module . '/pages/admin/template_tab_email.php');
//  require (DIR_FS_MODULES . $module . '/pages/admin/template_tab_currency.php');
//  if (file_exists(DIR_FS_MODULES . $module . '/custom/pages/admin/template_tab_custom.php')) {
//    require (DIR_FS_MODULES . $module . '/custom/pages/admin/template_tab_custom.php');
//  }
//  require (DIR_FS_MODULES . $module . '/pages/admin/template_tab_manager.php');
    require (DIR_FS_MODULES . $module . '/pages/tools/template_tab_tools.php');
//  require (DIR_FS_MODULES . $module . '/pages/admin/template_tab_stats.php');
    ?>
</div>
</form>

<br/>

