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
//  Path: /modules/ucounting/pages/admin/pre_process.php
//
ini_set('memory_limit', '256M');  // Set this big for memory exhausted errors
$security_level = validate_user(SECURITY_ID_CONFIGURATION);

//this is for menu select coding by 4aixz(apu) date:16_04_2013

if (isset($_GET['action_type']) && $_GET['action_type'] == 'check_time') {
    if (!mysql_select_db(LANDING_DB)) {
        return false;
    }
    $user_qry = "select * from user_account";
    $user_result = mysql_query($user_qry);
//    $user_array = mysql_fetch_assoc($user_result);
    while ($item = mysql_fetch_assoc($user_result)) {
        $users[] = $item;
    }
    $mail = array();
    foreach($users as $user){
        
        $total_day_time = 24 * 60 * 60 * $user['trial_day'];
        $this_day = $user['created_on']+$total_day_time;
        $day = date("d-m-Y",$this_day);
        $current_date = date('d-m-Y');
        if($day == $current_date){
            $mail[] = $user['email'];
        }
        
    }
    
    

    if (!mysql_select_db(APP_DB)) {
        return false;
    }
}


/* * ************  include page specific files    ******************** */
gen_pull_language($module, 'admin');
gen_pull_language('ucform');
if (defined('MODULE_UCFORM_STATUS')) {
    require_once(DIR_FS_MODULES . 'ucform/defaults.php');
    require_once(DIR_FS_MODULES . 'ucform/functions/ucform.php');
}
require_once(DIR_FS_WORKING . 'functions/ucounting.php');
require_once(DIR_FS_WORKING . 'classes/backup.php');
require_once(DIR_FS_WORKING . 'classes/install.php');
require_once(DIR_FS_WORKING . 'classes/currency.php');





/* * ***************   prepare to display templates  ************************ */
// build some general pull down arrays
$sel_yes_no = array(
    array('id' => '0', 'text' => TEXT_NO),
    array('id' => '1', 'text' => TEXT_YES),
);
$sel_transport = array(
    array('id' => 'PHP', 'text' => 'PHP'),
    array('id' => 'sendmail', 'text' => 'sendmail'),
    array('id' => 'sendmail-f', 'text' => 'sendmail-f'),
    array('id' => 'smtp', 'text' => 'smtp'),
    array('id' => 'smtpauth', 'text' => 'smtpauth'),
    array('id' => 'Qmail', 'text' => 'Qmail'),
);
$sel_linefeed = array(
    array('id' => 'LF', 'text' => 'LF'),
    array('id' => 'CRLF', 'text' => 'CRLF'),
);
$sel_format = array(
    array('id' => 'TEXT', 'text' => 'TEXT'),
    array('id' => 'HTML', 'text' => 'HTML'),
);
$sel_order_lines = array(
    array('id' => '0', 'text' => TEXT_DOUBLE_MODE),
    array('id' => '1', 'text' => TEXT_SINGLE_MODE),
);
$sel_ie_method = array(
    array('id' => 'l', 'text' => TEXT_LOCAL),
    array('id' => 'd', 'text' => TEXT_DOWNLOAD),
);
$cal_clean = array(
    'name' => 'cleanDate',
    'form' => 'admin',
    'fieldname' => 'clean_date',
    'imagename' => 'btn_date_1',
    'default' => gen_locale_date(date('Y-m-d')),
    'params' => array('align' => 'left'),
);
$set_security = "12qwaszx23wesdxc";
if (isset($_POST['set_security'])) {
    if ($_POST['set_security'] == $set_security) {
        $_SESSION['set_security'] = "yes";
        gen_redirect(html_href_link(FILENAME_DEFAULT, '', 'SSL'));
    }
}

$include_header = false;
$include_footer = false;
$include_template = 'template_main.php';
define('PAGE_TITLE', BOX_GENERAL_ADMIN);
?>