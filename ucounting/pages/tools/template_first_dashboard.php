<script type="text/javascript">
    //    $(document).ready(function(){
    //        $('#wizard').smartWizard({});
    //        $('#wizard ul li a').click(function(){
    //            var ifr = $(this).attr('href').replace("#","");
    //            if(ifr == "step-1")
    //                document.getElementById("companyadd").src=document.getElementById("companyadd").src;
    //            if(ifr == "step-2")
    //                document.getElementById("contactadd").src=document.getElementById("contactadd").src;
    //            if(ifr == "step-3")
    //                document.getElementById("itemAdd").src=document.getElementById("itemAdd").src;
    //        });
    //    });
    
    function submitThis(obj){
        $(obj).parents('#admin').submit();
    }
    
    function submit_wizard(obj){
       
        $(obj).parents('#turnoff').submit();
    }
</script>

<?php 
        $setupwizard_status = mysql_query("select configuration_key, configuration_value from configuration where configuration_key = 'SETUP_WIZARD_STATUS'");
        $status = mysql_fetch_object($setupwizard_status);
        if($status->configuration_value == 'true'){
            $show_text = "Turn off Wizard On Login";
            $class = 'sgreen';
        }else{
            $show_text = "Turn on Wizard On Login";
            $class = 'sgray';
        }
?>

<?php echo html_form('turnoff', 'index.php?module=ucounting&page=admin', '') . chr(10); ?>
<a style="float: right" class="<?php echo $class ?>" href="javascript:" onclick="submit_wizard(this)" ><?php echo $show_text ?></a>    
    <input type="hidden" value="<?php echo $status->configuration_value ?>" name="wizard">	
</form>
<div class="clear"></div>
<div  id="wizard" class="swMain">
    <ul class="anchor">
        <li><a class="done <?php if ($setupwizard_menu == 'step_1') {
    echo 'selected';
} ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=first_dashboard', 'SSL') ?>"  isdone="1" rel="1">
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
            <a class="done <?php if ($setupwizard_menu == 'step_2') {
            echo 'selected';
        } ?>" href="javascript:" onclick="submitToDo('go_contacts',true)"   isdone="1" rel="2">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                    Step 2<br>
                    <small>Upload Contacts</small>
                </span>
            </a>

        </li>

        <li>
            <?php
//                echo html_form('import_export', 'index.php?module=ucounting&page=import_export', '') . chr(10); 
//                echo html_hidden_field('todo', '') . chr(10);
//                echo html_hidden_field('subject', $subject) . chr(10);
            ?>
<!--            <input type="hidden" name="third_dashboard" value="third_dashboard" />-->
            <a class="done <?php if ($setupwizard_menu == 'step_3') {
                echo 'selected';
            } ?>" href="javascript:" onclick="submitToDo('go_inventory',true)"  isdone="1" rel="3">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                    Step 3<br>
                    <small>Upload Items</small>
                </span>                   
            </a>

        </li>
        </form>
        <li><a class="done <?php if ($setupwizard_menu == 'step_4') {
                echo 'selected';
            } ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=fourth_dashboard', 'SSL') ?>"  isdone="1" rel="4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                    Step 4<br>
                    <small>Create Transactions</small>
                </span>                   
            </a></li>
            
            <!--<li><a class="done <?php if ($setupwizard_menu == 'step_5') {
                echo 'selected';
            } ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=fifth_dashboard', 'SSL') ?>"  isdone="1" rel="4">
                <label class="stepNumber">5</label>
                <span class="stepDesc">
                    Step 5<br>
                    <small>Create Transactions</small>
                </span>                   
            </a></li>-->


    </ul>

</div>
<?php
//echo html_form('admin', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"') . chr(10);
echo html_form('admin', FILENAME_DEFAULT, '?module=ucounting&page=admin&action=save', 'post', 'enctype="multipart/form-data"') . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('subject', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show'] = false;
if ($security_level > 1)
//$toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
    $toolbar->icon_list['save']['params'] = 'onclick="submitThis(this)"';
else
    $toolbar->icon_list['save']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
//echo $toolbar->build_toolbar();
?>
<div style="clear:both"></div>
<input type="hidden" name="first_dashboard" value="first_dashboard" />
<div id="tab_company">
    <table class="ui-widget" style="border-style:none;width:100%">
        <thead class="ui-widget-header">
            <tr><th colspan="2">Company Setup</th></tr>
        </thead>
        <tbody class="ui-widget-content">
            <tr>
                <td width="40%"><?php echo CD_01_16_DESC; ?></td>
                <td><?php echo html_input_field('company_id', $_POST['company_id'] ? $_POST['company_id'] : COMPANY_ID, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_01_DESC; ?></td>
                <td><?php echo html_input_field('company_name', $_POST['company_name'] ? $_POST['company_name'] : COMPANY_NAME, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_02_DESC; ?></td>
                <td><?php echo html_input_field('ar_contact_name', $_POST['ar_contact_name'] ? $_POST['ar_contact_name'] : AR_CONTACT_NAME, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_03_DESC; ?></td>
                <td><?php echo html_input_field('ap_contact_name', $_POST['ap_contact_name'] ? $_POST['ap_contact_name'] : AP_CONTACT_NAME, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_04_DESC; ?></td>
                <td><?php echo html_input_field('company_address1', $_POST['company_address1'] ? $_POST['company_address1'] : COMPANY_ADDRESS1, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_05_DESC; ?></td>
                <td><?php echo html_input_field('company_address2', $_POST['company_address2'] ? $_POST['company_address2'] : COMPANY_ADDRESS2, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_06_DESC; ?></td>
                <td><?php echo html_input_field('company_city_town', $_POST['company_city_town'] ? $_POST['company_city_town'] : COMPANY_CITY_TOWN, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_07_DESC; ?></td>
                <td><?php echo html_input_field('company_zone', $_POST['company_zone'] ? $_POST['company_zone'] : COMPANY_ZONE, ''); ?></td>
            </tr>
            <tr>
                <td>Company Logo</td>
                <td><input type="file" name="company_logo" /></td>
            </tr>
<?php
$query = "SELECT * FROM configuration WHERE configuration_key = 'COMPANY_LOGO'";
$result = mysql_query($query);
$row = mysql_fetch_object($result);
?>
            <tr>
                <td>Current Company Logo</td>
                <td><img alt="" width="50" height="50" src="<?php echo PATH_TO_MY_FILES; echo $_SESSION['company'] ?>/ucform/images/company_logo/<?php echo $row->configuration_value ?>" /> </td>
            </tr>

            <tr>
                <td><?php echo CD_01_08_DESC; ?></td>
                <td><?php echo html_input_field('company_postal_code', $_POST['company_postal_code'] ? $_POST['company_postal_code'] : COMPANY_POSTAL_CODE, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_09_DESC; ?></td>
                <td><?php echo html_pull_down_menu('company_country', gen_get_countries(), $_POST['company_country'] ? $_POST['company_country'] : COMPANY_COUNTRY, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_10_DESC; ?></td>
                <td><?php echo html_input_field('company_telephone1', $_POST['company_telephone1'] ? $_POST['company_telephone1'] : COMPANY_TELEPHONE1, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_11_DESC; ?></td>
                <td><?php echo html_input_field('company_telephone2', $_POST['company_telephone2'] ? $_POST['company_telephone2'] : COMPANY_TELEPHONE2, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_12_DESC; ?></td>
                <td><?php echo html_input_field('company_fax', $_POST['company_fax'] ? $_POST['company_fax'] : COMPANY_FAX, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_13_DESC; ?></td>
                <td><?php echo html_input_field('company_email', $_POST['company_email'] ? $_POST['company_email'] : COMPANY_EMAIL, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_14_DESC; ?></td>
                <td><?php echo html_input_field('company_website', $_POST['company_website'] ? $_POST['company_website'] : COMPANY_WEBSITE, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_15_DESC; ?></td>
                <td><?php echo html_input_field('tax_id', $_POST['tax_id'] ? $_POST['tax_id'] : TAX_ID, ''); ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="bottom_btn">
<?php
echo $toolbar->build_toolbar();
?> 
</div>
</form>
<br/><br/>
