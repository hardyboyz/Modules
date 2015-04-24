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
    function submit_upload_type(obj){
       //alert($(obj).find(".up").html());
       var submitval = $(obj).attr('rel');
       $('#upload_type').val($(obj).find(".up").html());
       $(obj).parents('#import_export').find('#todo').val(submitval);
        $(obj).parents('#import_export').submit();
    }
</script>


<script type="text/javascript">
    $(function(){
        $('.counter').counter({
            //format: '00'
        });
       
        $("#example").fancybox({
            hideOnOverlayClick:false,
            hideOnContentClick:false,
            showCloseButton: false,
            enableEscapeButton:false
        }
    ).trigger('click');
        
        $("#example_2").fancybox({  
            hideOnOverlayClick:false,
            hideOnContentClick:false,
            showCloseButton: false,
            enableEscapeButton:false
        }).trigger('click');
        $("#example_3").fancybox({
            hideOnOverlayClick:false,
            hideOnContentClick:false,
            showCloseButton: false,
            enableEscapeButton:false
        }).trigger('click');
        //        setTimeout(function () {
        //            $.fancybox.close();
        //        }, 10000);
        $('.close').click(function (){
            $.fancybox.close();
        });
    });
</script>

<?php
 if ($messageStack->size > 0)
            echo $messageStack->output();
if (isset($_SESSION['trial_account']) && $_SESSION['trial_account'] > 0 && isset($_SESSION['account_status']) && $_SESSION['account_status'] == 'trial') {
    //unset($_SESSION['account_status']);
    ?>
    <script type="text/javascript">
        $(function(){
            setTimeout(function () {
                //$.fancybox.close();
                $("#continue_trial").show();
            }, 10000);
            $("#continue_trial").click(function(){
                $.fancybox.close();
            });
//            $(".request_ssales").live('click',function(){
//                //alert('test');
//                $("#admin").submit();
//            });
//            $("a#renew").live('click',function(){
//                alert('test');
//                $("#renew_submit").submit();
//            });
                    
        });
     
    </script>
    <a href="#show_notification" id="example" style="display: none;"></a>
    <div id="notification" style="display:none;">
        <div class="notification" id="show_notification">

            <p>
                Please wait <span class="counter counter-analog" data-direction="down" >0:10</span>
            </p>
            Your have <b><?php echo $_SESSION['trial_account']; ?></b> day remaining for the trial account. Are You ready to start their accounts. <br/><br/>

            <br/><br/>


            <?php $url_code = COMPANY_CODE; ?>
            <ul class="popup_box">
                <li>
                    <a target="_blank" href="<?php echo LANDING_SITE ?>login/login_action/<?php echo $url_code ?>">
                        <div class="popup_area">
                            <div class="popup_img"><img alt="" src="images/package_upgrade.png"/></div>
                            <div class="popup_text"> Upgrade to JKDM GST subsidy Package (RM100 Deposit)</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo LANDING_SITE ?>gst" target="_blank">
                        <div class="popup_area">

                            <div class="popup_img"><img alt="" src="images/find_out_more_icon.png"/></div>
                            <div class="popup_text"> Find out more about JKDM GST Subsidy Package</div>

                        </div> 
                    </a>
                </li>
                <li>
                    <?php echo html_form('admin', FILENAME_DEFAULT, 'module=ucounting&page=admin&action_type=renew_trial', 'post') . chr(10); ?>
                    <input type="hidden" value="request_sales_person" name="client_choose"/>
                    <a href="javascript:" class="request_ssales" onclick="$(this).parents('#admin').submit();">
                        <div class="popup_area">
                            <div class="popup_img"><img alt="" src="images/sales_person_icon.png"/></div>
                            <div class="popup_text"> Request Sales Person to contact me.</div>
                        </div>
                    </a>
                    </form>
                </li>
                <li>
                    <a target="_blank" href="<?php echo LANDING_SITE ?>login/login_action/<?php echo $url_code ?>">
                        <div class="popup_area">
                            <div class="popup_img"><img alt="" src="images/package_upgrade.png"/></div>
                            <div class="popup_text"> Upgrade to Small, Medium or Large Business package</div>
                        </div>
                    </a>     
                </li>
                <li>
                    <div class="popup_area" id="continue_trial" style="display: none;" >
                        <div class="popup_img"><img alt="" src="images/continue_icon.png"/></div>
                        <div class="popup_text">  Continue with Trial </div>
                    </div>
                </li>
            </ul>
            <div style="clear: both"></div>
            <br/>

            <!--            <a href="javascript:">Yes</a>
                        <a class="close" href="javascript:">No</a>-->
            <div style="clear: both;"></div>
            <!--<div class="close_btn"></div>-->

        </div> 
    </div>
    <?php
} else if (isset($_SESSION['account_status']) && $_SESSION['account_status'] == 'trial') {
    //unset($_SESSION['account_status']);
    if (!mysql_select_db(LANDING_DB)) {
        return false;
    }
    $user_qry = "select * from user_account where company_code = '" . COMPANY_CODE . "';";
    $user_result = mysql_query($user_qry);
    $user_array = mysql_fetch_assoc($user_result);


    if (!mysql_select_db(APP_DB)) {
        return false;
    }
    ?>
    <a href="#show_notification_2" id="example_2" style="display: none;"></a>
    <div style="display: none;">

        <div class="notification" id="show_notification_2" style="width: 500px;">
            <?php //echo html_form('admin', FILENAME_DEFAULT, 'module=ucounting&page=admin&action_type=renew_trial', 'post') . chr(10); ?>
            <h2 style="font-size: 20px">Your trial has ended</h2><br/> 
            <input type="hidden" name="user_email" value="<?php echo $user_array['email'] ?>" />
    <!--            <input type="radio" value="renew_trial" name="client_choose"/>Renew trial <br/>
            <input type="radio" value="start_company" name="client_choose"/>Start Company Accounts  <br/>
            <input type="radio" value="request_sales_person" name="client_choose"/>Request to meet Sales person for a LIVE demo
            <br/><br/><br/>-->

            <ul class="popup_box">
                <li style="width:250px;">
                    <?php echo html_form('renew_submit', FILENAME_DEFAULT, 'module=ucounting&page=admin&action_type=renew_trial', 'post') . chr(10); ?>
                    <input type="hidden" value="renew_trial" name="client_choose"/>
                  
                    <a href="javascript:" id="renew" onclick="$(this).parents('#renew_submit').submit();">
                        <div class="popup_area">
                            <div class="popup_img"><img alt="" src="images/renew-icon.png"/></div>
                            <div style="width:186px;" class="popup_text">Renew trial</div>
                        </div>
                    </a>
                    </form>

                </li>
                <li  style="width:250px;">
                    <a target="_blank" href="<?php echo LANDING_SITE ?>login/login_action/<?php echo $url_code ?>">
                        <div class="popup_area">
                            <div class="popup_img"><img alt="" src="images/package_upgrade.png"/></div>
                            <div style="width:186px;" class="popup_text"> Upgrade to JKDM GST subsidy Package (RM100 Deposit)</div>
                        </div>
                    </a>
                </li>
                <li  style="width:250px;">
                    <a href="<?php echo LANDING_SITE ?>gst" target="_blank">
                        <div class="popup_area">

                            <div class="popup_img"><img alt="" src="images/find_out_more_icon.png"/></div>
                            <div style="width:186px;" class="popup_text"> Find out more about JKDM GST Subsidy Package</div>

                        </div> 
                    </a>
                </li>
                <li  style="width:250px;">
                    <?php echo html_form('admin', FILENAME_DEFAULT, 'module=ucounting&page=admin&action_type=renew_trial', 'post') . chr(10); ?>
                    <input type="hidden" value="request_sales_person" name="client_choose"/>
                    <a href="javascript:" class="request_ssales" onclick="$(this).parents('#admin').submit();">
                        <div class="popup_area">
                            <div class="popup_img"><img alt="" src="images/sales_person_icon.png"/></div>
                            <div style="width:186px;" class="popup_text"> Request Sales Person to contact me.</div>
                        </div>
                    </a>
                    </form>
                </li>
                <li  style="width:250px;">
                    <a target="_blank" href="<?php echo LANDING_SITE ?>login/login_action/<?php echo $url_code ?>">
                        <div class="popup_area">
                            <div class="popup_img"><img alt="" src="images/package_upgrade.png"/></div>
                            <div style="width:186px;" class="popup_text"> Upgrade to Small, Medium or Large Business package</div>
                        </div>
                    </a>     
                </li>
                <div style="clear: both"></div>
            </ul>
            <div style="clear: both"></div>
            <br/>
            <!--<div class="close_btn"></div>-->
            <!--            </form>-->
        </div>

    </div>
    <?php
} else if (isset($_SESSION['account_status']) && $_SESSION['account_status'] == 'pre_active') {
    if (!mysql_select_db(LANDING_DB)) {
        return false;
    }
    $user_qry = "select * from user_account where company_code = '" . COMPANY_CODE . "';";
    $user_result = mysql_query($user_qry);
    $user_array = mysql_fetch_assoc($user_result);


    if (!mysql_select_db(APP_DB)) {
        return false;
    }

    for ($i = 1; $i <= 20; $i++) {
        if ($i <= 9) {
            $i = "0" . $i;
        }
        $year[] = "20" . $i;
    }
    ?>
    <a href="#show_notification_3" id="example_3" style="display: none;"></a>
    <div id="notification" style="display:none;">
        <div class="notification" id="show_notification_3" style="width: 400px;">
            <form name="install" id="install" action="install/index.php?action=install&lang=en_us" method="post">
                <?php //echo html_form('admin', FILENAME_DEFAULT, 'install/index.php?action=install&lang=en_us', 'post') . chr(10); ?>
                <input type="hidden" name="company_name" value="<?php echo $user_array['company_code'] ?>" />
                <input type="hidden" name="user_username" value="<?php echo $user_array['email'] ?>" />
                <input type="hidden" name="user_password" value="4axiz.com" />
                <input type="hidden" name="user_pw_confirm" value="4axiz.com" />
                <input type="hidden" name="user_email" value="<?php echo $user_array['email'] ?>" />
                <input type="hidden" name="db_name" value="<?php if(ENVIRONMENT == "cheshta") echo "cheshta_" ; echo $user_array['company_code'] ?>" />
                <input type="hidden" name="setup_type" value="resetup" />

                <h2 style="font-size: 20px">RESETUP Account.</h2><br/> 

                <label>First accounting period :</label>
                <select name="fy_month" id="acc_period">
                    <option <?php if (date("m") == "01") { ?> selected="selected" <?php } ?> value="01">January</option>
                    <option <?php if (date("m") == "02") { ?> selected="selected" <?php } ?> value="02">February</option>
                    <option <?php if (date("m") == "03") { ?> selected="selected" <?php } ?> value="03">March</option>
                    <option <?php if (date("m") == "04") { ?> selected="selected" <?php } ?> value="04">April</option>
                    <option <?php if (date("m") == "05") { ?> selected="selected" <?php } ?> value="05">May</option>
                    <option <?php if (date("m") == "06") { ?> selected="selected" <?php } ?> value="06">June</option>
                    <option <?php if (date("m") == "07") { ?> selected="selected" <?php } ?> value="07">july</option>
                    <option <?php if (date("m") == "08") { ?> selected="selected" <?php } ?> value="08">August</option>
                    <option <?php if (date("m") == "09") { ?> selected="selected" <?php } ?> value="09">September</option>
                    <option <?php if (date("m") == "10") { ?> selected="selected" <?php } ?> value="10">October</option>
                    <option <?php if (date("m") == "11") { ?> selected="selected" <?php } ?> value="11">November</option>
                    <option <?php if (date("m") == "12") { ?> selected="selected" <?php } ?> value="12">December</option>
                </select>
                <br/>
                <br/>
                <label>First fiscal year :</label>
                <select name="fy_year" id="fiscal_year">
                    <?php foreach ($year as $year_val) { ?>
                        <option <?php if (date("Y") == $year_val) { ?> selected="selected" <?php } ?> value="<?php echo $year_val; ?>"><?php echo $year_val; ?></option>
                    <?php } ?>
                </select>
                <br/>
                <br/>
                <input type="submit" value="submit" />
                <!--<div class="close_btn"></div>-->
            </form>
        </div>
    </div>
    <?php
}
?>


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
<div  id="wizard" class="swMain">
       <ul class="anchor">
        <li><a class="done <?php
            if ($setupwizard_menu == 'step_1') { //used for step1
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
            if ($setupwizard_menu == 'step_4') { //used for step2
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
            if ($setupwizard_menu == 'step_2') { //used for step3
                echo 'selected';
                //submit_upload_type go_contacts
            }
            ?>" href="javascript:void(0)" onclick="submit_upload_type(this)" rel="go_contacts"  isdone="1" rel="2">
                <label class="stepNumber">5</label>
                <span class="stepDesc">
                    Step 5<br>
                    <small class="up">Upload Contacts</small>
                </span>
            </a>

        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_3') { //used for step4
                echo 'selected';
            }
            ?>" href="javascript:void(0)" onclick="submit_upload_type(this)" rel="go_inventory"  isdone="1" rel="3">
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
            if ($setupwizard_menu == 'step_5') { //used for step5
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
            if ($setupwizard_menu == 'step_6') { //used for step6
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
//echo html_form('admin', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"') . chr(10);
echo html_form('admin', FILENAME_DEFAULT, '?module=ucounting&page=admin&action=save', 'post', 'enctype="multipart/form-data"') . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('subject', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php"';
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
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
                <td><?php //echo CD_01_01_DESC;                                ?>Company Name</td>
                <td><?php echo html_input_field('company_name', $_POST['company_name'] ? $_POST['company_name'] : COMPANY_NAME, 'required="required"'); ?></td>
            </tr>
            <tr>
                <td width="40%"><?php //echo CD_01_16_DESC;                                ?>Company Registration Number</td>
                <td><?php echo html_input_field('company_id', $_POST['company_id'] ? $_POST['company_id'] : COMPANY_ID, 'required="required"'); ?></td>
            </tr>
          
            
            <tr>
                <td><?php echo CD_01_04_DESC; ?></td>
                <td><?php echo html_input_field('company_address1', $_POST['company_address1'] ? $_POST['company_address1'] : COMPANY_ADDRESS1, 'required="required"'); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_05_DESC; ?></td>
                <td><?php echo html_input_field('company_address2', $_POST['company_address2'] ? $_POST['company_address2'] : COMPANY_ADDRESS2, ''); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_08_DESC; ?></td>
                <td><?php echo html_input_field('company_postal_code', $_POST['company_postal_code'] ? $_POST['company_postal_code'] : COMPANY_POSTAL_CODE, 'required="required"'); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_06_DESC; ?></td>
                <td><?php echo html_input_field('company_city_town', $_POST['company_city_town'] ? $_POST['company_city_town'] : COMPANY_CITY_TOWN, 'required="required"'); ?></td>
            </tr>
            <tr>
                <td><?php echo CD_01_07_DESC; ?></td>
                <td><?php echo html_input_field('company_zone', $_POST['company_zone'] ? $_POST['company_zone'] : COMPANY_ZONE, 'required="required"'); ?></td>
            </tr>
              <tr>
                <td><?php echo CD_01_09_DESC; ?></td>
                <td><?php echo html_pull_down_menu('company_country', gen_get_countries(), $_POST['company_country'] ? $_POST['company_country'] : COMPANY_COUNTRY, 'required="required"'); ?></td>
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
                <td><?php //echo CD_01_15_DESC;                                ?>GST No.</td>
                <td><?php echo html_input_field('tax_id', $_POST['tax_id'] ? $_POST['tax_id'] : TAX_ID, ''); ?></td>
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
                <td><img alt="" height="80" src="<?php
            echo PATH_TO_MY_FILES;
            echo $_SESSION['company']
            ?>/ucform/images/company_logo/<?php echo $row->configuration_value ?>" /> </td>
            </tr>
            
        </tbody>
</table>

  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>
</head>
<body>

<div id="tabs">
<ul>
  <li><a href="#tabs-1">Default Messages</a></li>
  <li><a href="#tabs-2">Template Options</a></li>
</ul>
<div id="tabs-1">
  
    <table class="ui-widget" style="border-collapse:collapse;width:100%">
        <thead class="ui-widget-header">
            <tr>
                <th colspan="4">Default Messages</th>
            </tr>
        </thead>
        <tbody class="ui-widget-content">
            <tr style="background-color:#9BC365">
                <td> Quotation Message :</td>
                <td><?php
                    if (MESSAGE_9_0 == '' || MESSAGE_9_0 == 'MESSAGE_9_0') {
                        $msg_9_0 = '';
                    } else {
                        $msg_9_0 = MESSAGE_9_0;
                    }
                    echo html_textarea_field('message_9_0', 28, 3, $_POST['message_9_0'] ? $_POST['message_9_0'] : $msg_9_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_9_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            <tr>
                <td> Sales Orders Message :</td>
                <td><?php
                    if (MESSAGE_10_0 == '' || MESSAGE_10_0 == 'MESSAGE_10_0') {
                        $msg_10_0 = '';
                    } else {
                        $msg_10_0 = MESSAGE_10_0;
                    }
                    echo html_textarea_field('message_10_0', 28, 3, $_POST['message_10_0'] ? $_POST['message_10_0'] : $msg_10_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_10_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            <tr style="background-color:#9BC365">
                <td> Delivery Orders Message :</td>

                <td><?php
                    if (MESSAGE_32_0 == '' || MESSAGE_32_0 == 'MESSAGE_32_0') {
                        $msg_32_0 = '';
                    } else {
                        $msg_32_0 = MESSAGE_32_0;
                    }
                    echo html_textarea_field('message_32_0', 28, 3, $_POST['message_32_0'] ? $_POST['message_32_0'] : $msg_32_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_32_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            <tr>
                <td> Sales Invoices Message :</td>

                <td><?php
                    if (MESSAGE_12_0 == '' || MESSAGE_12_0 == 'MESSAGE_12_0') {
                        $msg_12_0 = '';
                    } else {
                        $msg_12_0 = MESSAGE_12_0;
                    }
                    echo html_textarea_field('message_12_0', 28, 3, $_POST['message_12_0'] ? $_POST['message_12_0'] : $msg_12_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_12_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            <tr style="background-color:#9BC365">
                <td> POS Message :</td>

                <td><?php
                    if (MESSAGE_12_1 == '' || MESSAGE_12_1 == 'MESSAGE_12_1') {
                        $msg_12_1 = '';
                    } else {
                        $msg_12_1 = MESSAGE_12_1;
                    }
                    echo html_textarea_field('message_12_1', 28, 3, $_POST['message_12_1'] ? $_POST['message_12_1'] : $msg_12_1, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_12_1',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            <tr>
                <td>  Sales Credit Notes Message :</td>
                <td><?php
                    if (MESSAGE_13_0 == '' || MESSAGE_13_0 == 'MESSAGE_13_0') {
                        $msg_13_0 = '';
                    } else {
                        $msg_13_0 = MESSAGE_13_0;
                    }
                    echo html_textarea_field('message_13_0', 28, 3, $_POST['message_13_0'] ? $_POST['message_13_0'] : $msg_13_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_13_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>

            </tr>  
            <tr style="background-color:#9BC365">
                <td>   Sales Debit Notes Message :</td>
                <td><?php
                    if (MESSAGE_12_2 == '' || MESSAGE_12_2 == 'MESSAGE_12_2') {
                        $msg_12_2 = '';
                    } else {
                        $msg_12_2 = MESSAGE_12_2;
                    }
                    echo html_textarea_field('message_12_2', 28, 3, $_POST['message_12_2'] ? $_POST['message_12_2'] : $msg_12_2, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_12_2',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>

            </tr> 
            <tr>
                <td> Request for Quotes Message :</td>
                <td><?php
                    if (MESSAGE_3_0 == '' || MESSAGE_3_0 == 'MESSAGE_3_0') {
                        $msg_3_0 = '';
                    } else {
                        $msg_3_0 = MESSAGE_3_0;
                    }
                    echo html_textarea_field('message_3_0', 28, 3, $_POST['message_3_0'] ? $_POST['message_3_0'] : $msg_3_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_3_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>

            </tr>  
            <tr style="background-color:#9BC365">
                <td> Purchase Orders Message :</td>
                <td><?php
                    if (MESSAGE_4_0 == '' || MESSAGE_4_0 == 'MESSAGE_4_0') {
                        $msg_4_0 = '';
                    } else {
                        $msg_4_0 = MESSAGE_4_0;
                    }
                    echo html_textarea_field('message_4_0', 28, 3, $_POST['message_4_0'] ? $_POST['message_4_0'] : $msg_4_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_4_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>

            </tr>
            <tr>
                <td> Purchases Message :</td>
                <td><?php
                    if (MESSAGE_6_0 == '' || MESSAGE_6_0 == 'MESSAGE_6_0') {
                        $msg_6_0 = ' ';
                    } else {
                        $msg_6_0 = MESSAGE_6_0;
                    }
                    echo html_textarea_field('message_6_0', 28, 3, $_POST['message_6_0'] ? $_POST['message_6_0'] : $msg_6_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_6_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>

            </tr>
            <tr style="background-color:#9BC365">
                <td>  Expenses Message :</td>
                <td><?php
                    if (MESSAGE_6_1 == '' || MESSAGE_6_1 == 'MESSAGE_6_1') {
                        $msg_6_1 = '';
                    } else {
                        $msg_6_1 = MESSAGE_6_1;
                    }
                    echo html_textarea_field('message_6_1', 28, 3, $_POST['message_6_1'] ? $_POST['message_6_1'] : $msg_6_1, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_6_1',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>

            </tr>  
            <tr>
                <td> Purchase Credit Notes Message :</td>
                <td><?php
                    if (MESSAGE_7_0 == '' || MESSAGE_7_0 == 'MESSAGE_7_0') {
                        $msg_7_0 = '';
                    } else {
                        $msg_7_0 = MESSAGE_7_0;
                    }
                    echo html_textarea_field('message_7_0', 28, 3, $_POST['message_7_0'] ? $_POST['message_7_0'] : $msg_7_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_7_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>  
            <tr style="background-color:#9BC365">
                <td> Purchase Debit Notes Message :</td>
                <td><?php
                    if (MESSAGE_31_0 == '' || MESSAGE_31_0 == 'MESSAGE_31_0') {
                        $msg_31_0 = '';
                    } else {
                        $msg_31_0 = MESSAGE_31_0;
                    }
                    echo html_textarea_field('message_31_0', 28, 3, $_POST['message_31_0'] ? $_POST['message_31_0'] : $msg_31_0, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_31_0',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>

            <tr>
                <td> Receipt Message :</td>
                <td><?php
                    if (MESSAGE_RECEIPT_DEFAULT == '' || MESSAGE_RECEIPT_DEFAULT == 'MESSAGE_RECEIPT_DEFAULT') {
                        $msg_receipt_default = '';
                    } else {
                        $msg_receipt_default = MESSAGE_RECEIPT_DEFAULT;
                    }
                    echo html_textarea_field('message_receipt_default', 28, 3, $_POST['message_receipt_default'] ? $_POST['message_receipt_default'] : $msg_receipt_default, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_receipt_default',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            
            <tr style="background-color:#9BC365">
                <td> Spend Money Message :</td>
                <td><?php
                    if (MESSAGE_SPENDMONEY_DEFAULT == '' || MESSAGE_SPENDMONEY_DEFAULT == 'MESSAGE_SPENDMONEY_DEFAULT') {
                        $msg_spendmoney_default = '';
                    } else {
                        $msg_spendmoney_default = MESSAGE_SPENDMONEY_DEFAULT;
                    }
                    echo html_textarea_field('message_spendmoney_default', 28, 3, $_POST['message_spendmoney_default'] ? $_POST['message_spendmoney_default'] : $msg_spendmoney_default, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_spendmoney_default',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            
            <tr>
                <td> Receive Money Message :</td>
                <td><?php
                    if (MESSAGE_RECEIVEMONEY_DEFAULT == '' || MESSAGE_RECEIVEMONEY_DEFAULT == 'MESSAGE_RECEIVEMONEY_DEFAULT') {
                        $msg_receivemoney_default = '';
                    } else {
                        $msg_receivemoney_default = MESSAGE_RECEIVEMONEY_DEFAULT;
                    }
                    echo html_textarea_field('message_receivemoney_default', 28, 3, $_POST['message_receivemoney_default'] ? $_POST['message_receivemoney_default'] : $msg_receivemoney_default, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_receivemoney_default',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            
            <tr style="background-color:#9BC365">
                <td> Purchase Payment Voucher Message :</td>
                <td><?php
                    if (MESSAGE_PPAYMENTVOUCHER_DEFAULT == '' || MESSAGE_PPAYMENTVOUCHER_DEFAULT == 'MESSAGE_PPAYMENTVOUCHER_DEFAULT') {
                        $msg_ppaymentvoucher_default = '';
                    } else {
                        $msg_ppaymentvoucher_default = MESSAGE_PPAYMENTVOUCHER_DEFAULT;
                    }
                    echo html_textarea_field('message_ppaymentvoucher_default', 28, 3, $_POST['message_ppaymentvoucher_default'] ? $_POST['message_ppaymentvoucher_default'] : $msg_ppaymentvoucher_default, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_ppaymentvoucher_default',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            
            <tr>
                <td> Inventory Adjustment Message :</td>
                <td><?php
                    if (MESSAGE_INVENTORYADJUSTMENT_DEFAULT == '' || MESSAGE_INVENTORYADJUSTMENT_DEFAULT == 'MESSAGE_INVENTORYADJUSTMENT_DEFAULT') {
                        $msg_inventoryadjustment_default = '';
                    } else {
                        $msg_inventoryadjustment_default = MESSAGE_INVENTORYADJUSTMENT_DEFAULT;
                    }
                    echo html_textarea_field('message_inventoryadjustment_default', 28, 3, $_POST['message_inventoryadjustment_default'] ? $_POST['message_inventoryadjustment_default'] : $msg_inventoryadjustment_default, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_inventoryadjustment_default',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            
            <tr style="background-color:#9BC365">
                <td> Inventory Transfer Message :</td>
                <td><?php
                    if (MESSAGE_INVENTORYTRANSFER_DEFAULT == '' || MESSAGE_INVENTORYTRANSFER_DEFAULT == 'MESSAGE_INVENTORYTRANSFER_DEFAULT') {
                        $msg_inventorytransfer_default = '';
                    } else {
                        $msg_inventorytransfer_default = MESSAGE_INVENTORYTRANSFER_DEFAULT;
                    }
                    echo html_textarea_field('message_inventorytransfer_default', 28, 3, $_POST['message_inventorytransfer_default'] ? $_POST['message_inventorytransfer_default'] : $msg_inventorytransfer_default, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_inventorytransfer_default',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>
            
            <tr>
                <td> General Journal Message :</td>
                <td><?php
                    if (MESSAGE_GJVOUCHER_DEFAULT == '' || MESSAGE_GJVOUCHER_DEFAULT == 'MESSAGE_GJVOUCHER_DEFAULT') {
                        $msg_gjvoucher_default = '';
                    } else {
                        $msg_gjvoucher_default = MESSAGE_GJVOUCHER_DEFAULT;
                    }
                    echo html_textarea_field('message_gjvoucher_default', 28, 3, $_POST['message_gjvoucher_default'] ? $_POST['message_gjvoucher_default'] : $msg_gjvoucher_default, '', true);
                    ?>
                    <script type="text/javascript">
                        //<![CDATA[
                        // Replace the <textarea id="editor"> with an CKEditor
                        // instance, using default configurations.

                        CKEDITOR.replace('message_gjvoucher_default',
                                {
                                    enterMode: CKEDITOR.ENTER_BR,
                                    width: 800,
                                    height: 50
                                });

                        //]]>
                    </script>
                </td>
            </tr>


        </tbody>
    </table>
</div>

</div>
<style>
#tab-2 label {
  width:20px;
}
</style>
<div id="tabs-2">
   <table width="45%">
     <tr>
       <td><label for='template_shipto_box'>Show Shipping Address in Invoice</label></td>
       <td><?php echo html_checkbox_field('template_shipto_box', '1', TEMPLATE_SHIPTO_BOX=='1' ? true : false, '', 'tabindex="2"') . '<br />' . chr(10); ?></td>
     </tr>
     
     <tr>
       <td><label for='template_receipt_size'>Print Invoice in Receipt Size</label></td>
       <td><?php echo html_checkbox_field('template_receipt_size', '1', TEMPLATE_RECEIPT_SIZE=='1' ? true : false, '', 'tabindex="9"') . '<br />' . chr(10); ?> </td>
     </tr>
     
     <tr>
       <td><label for='template_item_code'>Show Item Code in Invoice and DO</label></td>
       <td><?php echo html_checkbox_field('template_item_code', '1', TEMPLATE_ITEM_CODE=='1' ? true : false, '', 'tabindex="9"') . '<br />' . chr(10);?> </td>
    </tr>
  </table>
  
</div>

<div class="bottom_btn">
    <?php
    echo $toolbar->build_toolbar();
    ?> 
</div>
</form>
<br/><br/>

