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
//  Path: /modules/ucounting/pages/roles/pre_process.php
//
//this is for menu select coding by 4aixz(apu) date:16_04_2013
$menu_select = 'company';
$sub_menu_select = 'uom_manager';


$security_level = validate_user(SECURITY_ID_ROLES);
/* * ************   include page specific files    ******************** */
gen_pull_language($module, 'admin');
//gen_pull_language('contacts');
//require_once(DIR_FS_WORKING . 'functions/ucounting.php');
//require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');
/* * ************  page specific initialization  ************************ */
$error = false;
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
// load the sort fields
$_GET['sf'] = $_POST['sort_field'] ? $_POST['sort_field'] : $_GET['sf'];
$_GET['so'] = $_POST['sort_order'] ? $_POST['sort_order'] : $_GET['so'];
/* * *************   hook for custom actions   ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/roles/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
/* * *************   Act on the action request   ************************ */
switch ($action) {
    case 'save':
    case 'fill_all':
        $sql = 'SELECT * from uom WHERE uom = "'.$_POST['uom'].'"';
        $res = mysql_query($sql);
        $result  = mysql_fetch_assoc($res);
        if($result){
            $messageStack->add('You enter duplicket UOM!!', 'error');
        }else{
            if($_POST['rowSeq'] != ''){
                $sql = 'UPDATE uom SET uom="'.$_POST['uom'].'" WHERE uom_id = ' . $_POST['rowSeq'];
                $res = mysql_query($sql);
                if(!res){
                    $messageStack->add('error', 'error');
                }
            }else{
                $sql = 'INSERT INTO uom (uom) VALUES ("'.$_POST['uom'].'")';
                $res = mysql_query($sql);
                if(!res){
                    $messageStack->add('error', 'error');
                }
            }
            
            
        }
        break;

    
    case 'edit':
        if (isset($_POST['rowSeq']))
            $uom_id = db_prepare_input($_POST['rowSeq']);
        $result = $db->Execute("select * from uom where uom_id = " . (int) $uom_id);
        $temp = unserialize($result->fields['admin_prefs']);
       
        $uInfo = new objectInfo($result->fields);
        
           
        break;

    case 'delete':
        validate_security($security_level, 4);
        $uom_id = (int) db_prepare_input($_POST['rowSeq']);
        // fetch the name for the audit log
        
        $db->Execute("delete from uom where uom_id = " . $uom_id);
        gen_add_audit_log(sprintf(GEN_LOG_USER, TEXT_DELETE), $result->fields['admin_name']);
        gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
        break;

    case 'go_first': $_GET['list'] = 1;
        break;
    case 'go_previous': $_GET['list']--;
        break;
    case 'go_next': $_GET['list']++;
        break;
    case 'go_last': $_GET['list'] = 99999;
        break;
    case 'search':
    case 'search_reset':
    case 'go_page':
    default:
}

/* * ***************   prepare to display templates  ************************ */
$fill_all_values = array(
    array('id' => '-1', 'text' => GEN_HEADING_PLEASE_SELECT),
    array('id' => '0', 'text' => TEXT_NONE),
    array('id' => '1', 'text' => TEXT_READ_ONLY),
    array('id' => '2', 'text' => TEXT_ADD),
    array('id' => '3', 'text' => TEXT_EDIT),
    array('id' => '4', 'text' => TEXT_FULL),
);

$include_header = true;
$include_footer = true;
$include_tabs = true;
$include_calendar = false;

switch ($action) {
    case 'new':
    case 'edit':
    case 'fill_all':
        $include_template = 'template_detail.php';
        $role_name = isset($uInfo->admin_name) ? (' - ' . $uInfo->admin_name) : '';
        define('PAGE_TITLE', 'Uom');
        break;
    default:
        define('PAGE_TITLE', "Uom");
        $include_header = true;
        $include_footer = true;
        $include_template = 'template_main.php';
        $ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=ajax&op=load_uom', 'SSL');


       
}
?>