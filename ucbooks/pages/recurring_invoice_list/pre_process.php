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
//  Path: /modules/ucbooks/pages/status/pre_process.php
//
/* * ************   Check user security   **************************** */


$menu_select = 'customers';
$sub_menu_select = 'recurring_invoice';


//this is for delete recurring coding by 4axiz(apu) date:22-july-2013
if(isset($_GET['action_type'])&& $_GET['action_type'] == "delete"){
    if($_POST['id'] != ""){
        $query = "DELETE FROM invoice_recurring WHERE id = ".$_POST['id'];
        if(mysql_query($query)){
            $query = "DELETE FROM recurring_to_invoice WHERE recurring_id = ".$_POST['id'];
            if(mysql_query($query)){
                echo "success";
                exit;
            }
        }
    }
}






//$security_level = validate_user($security_token);
/* * ************  include page specific files    ******************** */
require(DIR_FS_WORKING . 'defaults.php');
require(DIR_FS_WORKING . 'functions/ucbooks.php');
/* * ************   page specific initialization  ************************ */
// load the sort fields
$_GET['sf'] = $_POST['sort_field'] ? $_POST['sort_field'] : ($_GET['sf'] ? $_GET['sf'] : TEXT_DATE);
$_GET['so'] = $_POST['sort_order'] ? $_POST['sort_order'] : ($_GET['so'] ? $_GET['so'] : 'desc');
$acct_period = $_GET['search_period'] ? $_GET['search_period'] : CURRENT_ACCOUNTING_PERIOD;
$search_text = $_POST['search_text'] ? db_input($_POST['search_text']) : db_input($_GET['search_text']);
if (isset($_POST['search_text']))
    $_GET['search_text'] = $_POST['search_text']; // save the value for get redirects 
if ($search_text == TEXT_SEARCH)
    $search_text = '';
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
if (!$action && $search_text <> '')
    $action = 'search'; // if enter key pressed and search not blank
$date_today = date('Y-m-d');
/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/status/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}



/* * ***************   prepare to display templates  ************************ */

define('PAGE_TITLE', sprintf(BOX_STATUS_MGR, $page_title));
$include_header = true;
$include_footer = true;
$include_template = 'template_main.php';

$ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_recuring_invoice', 'SSL');

