<?php
//$single_company = false;
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
//  Path: /modules/ucounting/pages/main/template_main.php
//
echo html_form('login', FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;action=validate', 'post', 'onsubmit="return submit_wait();"') . chr(10);
if ($single_company)
    echo html_hidden_field('company', $companies[0]['id']) . chr(10);
if ($single_language)
    echo html_hidden_field('language', $languages[0]['id']) . chr(10);
?>


<style type="text/css">

</style>



<div style="margin: 3% auto 0 38%; width: 20%;"><img style="width:120%" src="images/logo_whitebg.png"/></div>
<?php
mysql_connect(DB_SERVER_HOST, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
$database = LANDING_DB;
mysql_select_db($database);
$sql = 'SELECT company_name from user_account where company_code = "'.COMPANY_CODE.'"';
$query = mysql_query($sql);
if ($query) {
    $company_name = mysql_fetch_array($query);
}if (empty($company_name['company_name'])) {
    mysql_connect(DB_SERVER_HOST, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
    $database = APP_DB;
    mysql_select_db($database);
    $sql = 'SELECT configuration_value from configuration where configuration_key = "COMPANY_NAME"';
    $query = mysql_query($sql);
    if ($query) {
        $company_name = mysql_fetch_array($query);
    }
}
?>
<!-- container -->
<div class="bizz_content login_block ">
    <?php echo $messageStack->output(); ?>
    <div class="login_area">
        <h2 style="background-color: none !important;" class="heading_style"><?php
    if (!empty($company_name['company_name'])) {
        echo $company_name['company_name'];
    } else
        echo $company_name['configuration_value'];
    ?> </h2><form  class="form-signin form_class" name="login" method="POST" action="login-db.php">

            <input type="text" class="form-control" value="" placeholder="Please enter user name" name="admin_name" required autofocus>

            <input type="password" class="form-control" value="" name="admin_pass" placeholder="Please enter password" required>
            <input class="btn btn-lg btn-primary btn-block" value="Login"  type="submit"/>
            <a style="margin-left:7%; margin-top:5%; float: left;" href="index.php?module=ucounting&amp;page=main&amp;req=pw_lost_req">Forgot your password?</a>
        </form>

    </div>
</div>
<!--<div class="container">

     wrapper 

    <div class="wrapper">


         header 

        <div class="header">


            <div class="top-nav">

            </div>


            <div class="page-title">


                <h2>Ucounting Login</h2>
            </div>
        </div>

         // header 


         sub-header 		

        <div class="sub-header">


             content 

            <div class="content-top"></div>

            <div class="content clearfix">

                <p>
                <p>

<?php echo $messageStack->output(); ?>
                    <br/>				
                <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="left" valign="top" class="main_bg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                                            <tr>
                                                <td align="center" valign="top"><table width="330" border="0" align="center" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td align="left" valign="top">

                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <form name="login" method="POST" action="login-db.php" style="color: #000000; border: 1px solid #808080; background-color: #9E9E9E">



                                                                    <tr>
                                                                        <td align="left" valign="top">
                                                                            <table width="100%" border="0" cellspacing="0" cellpadding="10">
                                                                                <tr>
                                                                                    <td height="100%" align="left" valign="top" class="white_box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                            <tr>
                                                                                                <td width="5%" align="left" valign="top">&nbsp;</td>
                                                                                                <td width="95%" height="30" align="left" valign="middle" class="user_text"><?php echo TEXT_LOGIN_NAME; ?> </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td align="left" valign="top">&nbsp;</td>
                                                                                                <td align="left" valign="top"><?php echo html_input_field('admin_name', ""); ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td align="left" valign="top">&nbsp;</td>
                                                                                                <td align="left" valign="top"><span style="color: Red; visibility: hidden; padding-left:0px;" id="email_block">Please provide a first name</span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td align="left" valign="top">&nbsp;</td>
                                                                                                <td width="95%" height="30" align="left" valign="middle" class="user_text"><?php echo TEXT_LOGIN_PASS; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td align="left" valign="top">&nbsp;</td>
                                                                                                <td align="left" valign="top"><?php echo html_password_field('admin_pass', ''); ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td align="left" valign="top">&nbsp;</td>
                                                                                                <td align="left" valign="top"><span style="color: Red; visibility: hidden; padding-left:0px;" id="password_block">Please provide a password</span></td>
                                                                                            </tr>

                                                                                            <tr>
                                                                                                <td align="left" valign="top">&nbsp;</td>
                                                                                                <td height="53" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                        <tr>
                                                                                                            <td width="8%" align="left" valign="top">&nbsp;
                                                                                                            </td>
                                                                                                            <td width="33%" align="left" valign="middle" class="user_text">Remember me &nbsp;</td>
                                                                                                            <td width="57%" align="right" valign="top"><?php echo html_submit_field('submit', TEXT_LOGIN_BUTTON); ?></td>
                                                                                                            <td width="2%" align="left" valign="top">&nbsp;</td>
                                                                                                        </tr>
                                                                                                    </table></td>
                                                                                            </tr>
                                                                                        </table></td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td height="31" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td width="5%" align="left" valign="top">User name :</td>
                                                                                    <td width="5%" align="left" valign="top">admin</td>
                                                                                    <td width="95%" align="left" valign="middle" class="lost_text"><a href="forgot-password.php" class="loglink">Forgot your password?</a>&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="5%" align="left" valign="top">Password :</td>
                                                                                    <td width="5%" align="left" valign="top">admin</td>

                                                                                </tr>
                                                                            </table></td>
                                                                    </tr>
                                                                    </form>
                                                                </table>

                                                            </td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
                <div class="colleft small"></div>
            </div>	
            <div class="content-btm"></div>

             // content 
        </div>-->

<!-- // sub-header -->


<!-- footer -->


<div class="footer">			


    <div class="copy">
        <p>Copyright &copy; 2010 Feradigm Sdn BHd. All rights reserved.</p>		
    </div>


    <?php /* ?><?php include(get_footer_path()); ?><?php */ ?>				
    <!-- // footer -->

</div>	


</div>
<!-- // wrapper -->

</div>
<!-- // container -->













<!--<div style="margin-left:25%;margin-right:25%;margin-top:50px;">
    <table class="ui-widget">
        <thead class="ui-widget-header">
            <tr height="70">
                <th style="text-align:right"><img src="modules/ucounting/images/ucsoft_logo.png" alt="Ucounting Business Toolkit" height="50" /></th>
            </tr>
        </thead>
        <tbody class="ui-widget-content">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td colspan="2"><?php echo $messageStack->output(); ?></td>
                        </tr>
                        <tr>
                            <td width="35%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_LOGIN_NAME; ?></td>
                            <td width="65%"><?php echo html_input_field('admin_name', $_POST['admin_name']); ?></td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_LOGIN_PASS; ?></td>
                            <td><?php echo html_password_field('admin_pass', ''); ?></td>
                        </tr>
<?php if (!$single_company) { ?>
                                                                <tr>
                                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_LOGIN_COMPANY; ?></td>
                                                                    <td><?php echo html_pull_down_menu('company', $companies, $company_index); ?></td>
                                                                </tr>
<?php } ?>
<?php if (!$single_language) { ?>
                                                                <tr>
                                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_LOGIN_LANGUAGE; ?></td>
                                                                    <td><?php echo html_pull_down_menu('language', $languages, $language_index); ?></td>
                                                                </tr>
<?php } ?>
                        <tr>
                            <td colspan="2" align="right">&nbsp;
                                <div id="wait_msg" style="display:none;"><?php echo TEXT_FORM_PLEASE_WAIT; ?></div>
<?php echo html_submit_field('submit', TEXT_LOGIN_BUTTON); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><?php echo '<a href="' . html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;req=pw_lost_req', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
<?php echo TEXT_COPYRIGHT; ?> (c) 2008, 2009, 2010 <a href="http://www.UcSoft.com">UcSoft, LLC</a><br />
<?php echo sprintf(TEXT_COPYRIGHT_NOTICE, '<a href="' . DIR_WS_MODULES . 'ucounting/language/en_us/manual/ch01-Introduction/license.html">' . TEXT_HERE . '</a>'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>-->
</form>
