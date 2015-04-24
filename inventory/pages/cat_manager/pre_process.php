<?php

// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011 UcSoft, LLC             |
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
//  Path: /modules/work_orders/pages/main/pre_process.php
//
//this is for menu select coding by 4aixz(apu) date:16_04_2013
$menu_select = 'inventory';
$sub_menu_select = 'cat_manager';



$security_level = validate_user(SECURITY_WORK_ORDERS);
/* * ************  include page specific files    ******************** */
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
/* * ************   page specific initialization  ************************ */
$error = false;

$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/main/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
/* * *************   Act on the action request   ************************ */
switch ($action) {
    case 'new':
        break;
    case 'save':
        if ($security_level < 2) {
            $messageStack->add_session(ERROR_NO_PERMISSION, 'error');
            gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
            break;
        }
        $id = db_prepare_input($_POST['id']);


        if (!$error) {
            if ($id) { // if id is present only allow update of select fields
                $result = $db->Execute("select * from " . TABLE_PRODUCT_CATEGORY . " where id = " . $id);
                $cat_name = $result->fields['cat_name'];
                $sql_data_array = array(
                    'id' => $id,
                    'cat_name' => db_prepare_input($_POST['cat_name']),
                    'key_code' => db_prepare_input($_POST['key_code']),
                    'description' => db_prepare_input($_POST['description']),
                );
                $success = db_perform(TABLE_PRODUCT_CATEGORY, $sql_data_array, 'update', 'id = ' . $id);
                if (!$success)
                    $error = true;
                else {
                    $inventory_array = array(
                        'sku' => $_POST['cat_name'] . " item",
                        'inventory_type' => 'si',
                        'catalog' => '1',
                        'category_id' => db_prepare_input($_POST['cat_name']),
                    );
                    $success = db_perform(TABLE_INVENTORY, $inventory_array, 'update', "sku = '".$cat_name." item'");
                    if (!$success)
                        $error = true;
                }
            } else {
                $sql_data_array = array(
                    'cat_name' => db_prepare_input($_POST['cat_name']),
                    'key_code' => db_prepare_input($_POST['key_code']),
                    'description' => db_prepare_input($_POST['description']),
                );
                $success = db_perform(TABLE_PRODUCT_CATEGORY, $sql_data_array, 'insert');
                $inventory_array = array(
                    'sku' => $_POST['cat_name'] . " item",
                    'inventory_type' => 'si',
                    'catalog' => '1',
                    'category_id' => db_prepare_input($_POST['cat_name']),
                );
                $success1 = db_perform(TABLE_INVENTORY, $inventory_array, 'insert');
                if (!$success || !$success1)
                    $error = true;
            }
        }
        // finish
        if (!$error) {
            gen_add_audit_log(($id ? sprintf('Category Update ', TEXT_UPDATE) : sprintf('Category Add ', TEXT_ADD)) . ' - ' . $_POST['cat_name']);
            $messageStack->add(($id ? 'Product category updated successfully' : 'Product Category Added successfully'), 'success');
            //if ($action == 'save')
            gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
        } else {
            $messageStack->add(WO_MESSAGE_MAIN_ERROR, 'error');
            $action = 'edit';
        }
        break;
    case 'edit' :
        $id = isset($_POST['rowSeq']) ? $_POST['rowSeq'] : $_GET['id'];
        if (!$id) {
            $action = '';
            $error = true;
            break;
        }
        $result = $db->Execute("select * from " . TABLE_PRODUCT_CATEGORY . " where id = " . $id);
        $cat_name = $result->fields['cat_name'];
        $key_code = $result->fields['key_code'];
        $description = $result->fields['description'];

        break;
    case 'delete':
        if ($security_level < 4) {
            $messageStack->add_session(ERROR_NO_PERMISSION, 'error');
            gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
            break;
        }
        $id = db_prepare_input($_GET['id']);
        if (!$id)
            $error = true;
        if (!$error) {
            $result = $db->Execute("select cat_name from " . TABLE_PRODUCT_CATEGORY . " where id = " . $id);
            $cat_name = $result->fields['cat_name'];
            $db->Execute("delete from " . TABLE_PRODUCT_CATEGORY . " where id = " . $id);
            $db->Execute("delete from " . TABLE_INVENTORY . " where sku = '" . $cat_name.' item' ."'");
            gen_add_audit_log(sprintf('Product category deleted successfully', TEXT_DELETE) . $result->fields['cat_name']);
            $messageStack->add('Product category deleted successfully', 'success');
        }
        $action = '';
        break;

    default:
}

/* * ***************   prepare to display templates  ************************ */
$include_header = true;
$include_footer = true;
$include_tabs = true;
$include_calendar = true;

switch ($action) {
    case 'new':
    case 'edit':

        define('PAGE_TITLE', ($action == 'edit') ? 'Edit Product Category' : 'New Category Name');
        $include_template = 'template_new.php';
        break;

    default:
        define('PAGE_TITLE', 'Catagory Manager');
        $include_header = true;
        $include_footer = true;
        $include_template = 'template_main.php';
        $ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=inventory&page=ajax&op=load_product_category_manager', 'SSL');
}
?>