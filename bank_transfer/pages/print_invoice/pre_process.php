<?php

// +-----------------------------------------------------------------+
// |                   bank_transfer Open Source ERP                    |
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
//  Path: /modules/bank_transfer/pages/orders/pre_process.php
//
/* * ************   Check user security   **************************** */


//this is for menu select coding by 4aixz(apu) date:16_04_2013




define('JOURNAL_ID', $_GET['jID']);

//$security_level = validate_user($security_token);
/* * ************  include page specific files    ******************** */
gen_pull_language('contacts');

require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
require_once(DIR_FS_WORKING . 'functions/bank_transfer.php');
require_once(DIR_FS_WORKING . 'classes/gen_ledger.php');
require_once(DIR_FS_WORKING . 'classes/orders.php');
if (defined('MODULE_SHIPPING_STATUS')) {
    require_once(DIR_FS_MODULES . 'shipping/functions/shipping.php');
    require_once(DIR_FS_MODULES . 'shipping/defaults.php');
}
/* * ************   page specific initialization  ************************ */

if($_GET['action'] == "pdf" || $_GET['action'] == "email"){
    $sql = "SELECT * FROM journal_main WHERE id = ".$_GET['oID'];
    $res = mysql_query($sql);
    $invoice_main = mysql_fetch_object($res);
    
    $sql = "SELECT * FROM journal_item WHERE ref_id = ".$invoice_main->id;
    $res = mysql_query($sql);
    while($item = mysql_fetch_assoc($res)){
        $invoice_item[] = $item;
    }
    
   $res = mysql_query("SELECT * FROM configuration");
    
    while($configuration_item = mysql_fetch_assoc ($res)){
        $configuration[] = $configuration_item;
    }
    
   
}


$error = false;
$post_success = false;
$order = new orders();

/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/orders/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
/* * *************   Act on the action request   ************************ */


/* * ***************   prepare to display templates  ************************ */





$include_header = false;
$include_footer = false;
$include_tabs = false;
$include_calendar = true;
$include_template = 'template_main.php'; // include display template (required)
define('PAGE_TITLE', constant('ORD_TEXT_' . JOURNAL_ID . '_WINDOW_TITLE'));
?>