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
$sub_menu_select = 'select_print_templet';


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

if (isset($_POST['setup_wizard_action']) && $_POST['setup_wizard_action'] == 'print_template') {
    $action = $_GET['action'];
}

/* * *************   Act on the action request   ************************ */
switch ($action) {
    case 'save':
    case 'fill_all':
        $sql = 'UPDATE configuration SET configuration_value = ' . $_POST['print_template'] . ' WHERE configuration_key ="PRINT_INVOICE_STYLE"';
        mysql_query($sql);
        if (isset($_POST['setup_wizard_action']) && $_POST['setup_wizard_action'] == 'print_template') {
            
            gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=print_template&action_type=print_template&sf=&so=&action_type=print_template', 'SSL'));
        } else {
            gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
        }

        break;
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
if ((isset($_GET['action_type']) && $_GET['action_type'] == "print_template") || isset($_POST['setup_wizard_action']) && $_POST['setup_wizard_action'] == 'print_template') {
    $menu_select = 'home';
    $sub_menu_select = 'setup_wizard';
    $setupwizard_menu = "";
    $include_header = true;
    $include_footer = true;
    $setupwizard_menu = 'step_5';
    $include_template = 'setup_wizard_print_template.php';
    define('PAGE_TITLE', BOX_UCBOOKS_MODULE_ADM);
} else {
    switch ($action) {
        case 'new':
        case 'edit':
        case 'fill_all':
            $include_template = 'template_detail.php';

            define('PAGE_TITLE', 'Print Template Style');
            break;
        default:
            define('PAGE_TITLE', 'Print Template Style');
            $include_template = 'template_detail.php';
    }
}
?>