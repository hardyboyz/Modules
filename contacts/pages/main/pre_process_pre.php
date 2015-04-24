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
//  Path: /modules/contacts/pages/main/pre_process.php
//
/* * ************   page specific initialization  ************************ */


//this is for menu select coding by 4aixz(apu) date:16_04_2013

if ($_GET['type'] == "c") {
    if ($_GET['action'] == "new") {
        $menu_select = 'customers';
        $sub_menu_select = 'new_customer';
    } else {
        $menu_select = 'customers';
        $sub_menu_select = 'customer_manager';
    }
} else if ($_GET['type'] == "i") {
    $menu_select = 'customers';
    $sub_menu_select = 'uccrm';
} else if ($_GET['type'] == "j") {
    $menu_select = 'customers';
    $sub_menu_select = 'project_manager';
} else if ($_GET['type'] == "v") {
    if ($_GET['action'] == "new") {
        $menu_select = 'vendors';
        $sub_menu_select = 'new_vendor';
    } else {
        $menu_select = 'vendors';
        $sub_menu_select = 'vendor_manager';
    }
} else if ($_GET['type'] == "e") {
    if ($_GET['action'] == "new") {
        $menu_select = 'employees';
        $sub_menu_select = 'new_employee';
    } else {
        $menu_select = 'employees';
        $sub_menu_select = 'employee_manager';
    }
} else if($_GET['type'] == "b"){
    if($_GET['list'] == 1){
        $menu_select = 'company';
        $sub_menu_select = 'branch_manager';
    }else{
        $menu_select = 'company';
        $sub_menu_select = 'new_branch';
    }
}




$error = false;
$contact_js = '';
$js_pmt_array = '';
$js_actions = '';
$criteria = array();
$tab_list = array();
if ($_POST['crm_date'])
    $_POST['crm_date'] = gen_db_date($_POST['crm_date']);
if ($_POST['due_date'])
    $_POST['due_date'] = gen_db_date($_POST['due_date']);
$search_text = $_POST['search_text'] ? db_input($_POST['search_text']) : db_input($_GET['search_text']);
if (isset($_POST['search_text']))
    $_GET['search_text'] = $_POST['search_text']; // save the value for get redirects 
if ($search_text == TEXT_SEARCH)
    $search_text = '';
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
if (!$action && $search_text <> '')
    $action = 'search'; // if enter key pressed and search not blank
$type = isset($_GET['type']) ? $_GET['type'] : 'c'; // default to customer
// load the filters
$f0 = isset($_POST['todo']) ? (isset($_POST['f0']) ? $_POST['f0'] : '0') : (isset($_GET['f0']) ? $_GET['f0'] : '1'); // show inactive checkbox
$_GET['f0'] = $f0;
if (file_exists(DIR_FS_WORKING . 'custom/classes/type/' . $type . '.php')) {
    require_once(DIR_FS_WORKING . 'custom/classes/type/' . $type . '.php');
} else {
    require_once(DIR_FS_WORKING . 'classes/type/' . $type . '.php'); // is needed here for the defining of the class and retriving the security_token
}
if ($type <> 'i' && file_exists(DIR_FS_WORKING . 'custom/classes/type/i.php')) {
    require_once(DIR_FS_WORKING . 'custom/classes/type/i.php');
} elseif ($type <> 'i') {
    require_once(DIR_FS_WORKING . 'classes/type/i.php');
}
$cInfo = new $type();
// load the sort fields
$_GET['sf'] = $_POST['sort_field'] ? $_POST['sort_field'] : $_GET['sf'];
$_GET['so'] = $_POST['sort_order'] ? $_POST['sort_order'] : $_GET['so'];
/* * ************   Check user security   **************************** */

/* * *************   hook for custom security  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/contacts/main/extra_security.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
$security_level = validate_user($cInfo->security_token); // in this case it must be done after the class is defined for
/* * ************  include page specific files    ******************** */
require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_MODULES . 'ucounting/functions/ucounting.php');
require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');
require_once(DIR_FS_WORKING . 'functions/contacts.php');
require_once(DIR_FS_WORKING . 'classes/contacts.php');
require_once(DIR_FS_WORKING . 'classes/contact_fields.php');
$fields = new contact_fields();
/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/main/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
/* * *************   Act on the action request   ************************ */

switch ($action) {
    case 'new':
        validate_security($security_level, 2);
        break;
    case 'save':
        $id = (int) db_prepare_input($_POST['id']);  // if present, then its an edit
        $id ? validate_security($security_level, 3) : validate_security($security_level, 2);
        // error check
        $error = $cInfo->data_complete($error);
        // start saving data
        if (!$error) {
            $cInfo->save_contact();
            $cInfo->save_addres();
            if ($type <> 'i' && $_POST['i_short_name']) { // is null
                $crmInfo = new i;
                // error check contact
                $error = $crmInfo->data_complete($error);
                if (!$error) {
                    $crmInfo->save_contact();
                    $crmInfo->save_addres();
                }
            }
            // payment fields
            if (ENABLE_ENCRYPTION && $_POST['payment_cc_name'] && $_POST['payment_cc_number']) { // save payment info
                $encrypt = new encryption();
                $cc_info = array(
                    'name' => db_prepare_input($_POST['payment_cc_name']),
                    'number' => db_prepare_input($_POST['payment_cc_number']),
                    'exp_mon' => db_prepare_input($_POST['payment_exp_month']),
                    'exp_year' => db_prepare_input($_POST['payment_exp_year']),
                    'cvv2' => db_prepare_input($_POST['payment_cc_cvv2']),
                );
                if ($enc_value = $encrypt->encrypt_cc($cc_info)) {
                    $payment_array = array(
                        'hint' => $enc_value['hint'],
                        'module' => 'contacts',
                        'enc_value' => $enc_value['encoded'],
                        'ref_1' => $cInfo->id,
                        'ref_2' => $cInfo->address[$type . 'm']['address_id'],
                        'exp_date' => $enc_value['exp_date'],
                    );
                    db_perform(TABLE_DATA_SECURITY, $payment_array, $_POST['payment_id'] ? 'update' : 'insert', 'id = ' . $_POST['payment_id']);
                } else {
                    $error = true;
                }
            }
            // Check attachments
            $result = $db->Execute("select attachments from " . TABLE_CONTACTS . " where id = $id");
            $attachments = $result->fields['attachments'] ? unserialize($result->fields['attachments']) : array();
            $image_id = 0;
            while ($image_id < 100) { // up to 100 images
                if (isset($_POST['rm_attach_' . $image_id])) {
                    @unlink(CONTACTS_DIR_ATTACHMENTS . 'contacts_' . $cInfo->id . '_' . $image_id . '.zip');
                    unset($attachments[$image_id]);
                }
                $image_id++;
            }
            if (is_uploaded_file($_FILES['file_name']['tmp_name'])) { // find an image slot to use
                $image_id = 0;
                while (true) {
                    if (!file_exists(CONTACTS_DIR_ATTACHMENTS . 'contacts_' . $cInfo->id . '_' . $image_id . '.zip'))
                        break;
                    $image_id++;
                }
                saveUploadZip('file_name', CONTACTS_DIR_ATTACHMENTS, 'contacts_' . $cInfo->id . '_' . $image_id . '.zip');
                $attachments[$image_id] = $_FILES['file_name']['name'];
            }
            $sql_data_array = array('attachments' => sizeof($attachments) > 0 ? serialize($attachments) : '');
            db_perform(TABLE_CONTACTS, $sql_data_array, 'update', 'id = ' . $cInfo->id);
            // check for crm notes
            if ($_POST['crm_action'] <> '' || $_POST['crm_note'] <> '') {
                $sql_data_array = array(
                    'contact_id' => $cInfo->id,
                    'log_date' => $_POST['crm_date'],
                    'entered_by' => $_POST['crm_rep_id'],
                    'action' => $_POST['crm_action'],
                    'notes' => db_prepare_input($_POST['crm_note']),
                );
                db_perform(TABLE_CONTACTS_LOG, $sql_data_array, 'insert');
            }
            gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
        }
        $action = 'edit';
        break;

    case 'edit':
    case 'properties':
        $cInfo->getContact();
        break;

    case 'delete':
    case 'crm_delete':
        validate_security($security_level, 4);
        $short_name = gen_get_contact_name($cInfo->id);
        $temp = $cInfo->delete();
        if ($temp == true) {
            gen_add_audit_log(TEXT_CONTACTS . '-' . TEXT_DELETE . '-' . constant('ACT_' . strtoupper($type) . '_TYPE_NAME'), $short_name);
        } else {
            $error = $messageStack->add($temp, 'error');
        }
        break;

    case 'download':
        $cID = db_prepare_input($_POST['id']);
        $imgID = db_prepare_input($_POST['rowSeq']);
        $filename = 'contacts_' . $cID . '_' . $imgID . '.zip';
        if (file_exists(CONTACTS_DIR_ATTACHMENTS . $filename)) {
            require_once(DIR_FS_MODULES . 'ucounting/classes/backup.php');
            $backup = new backup();
            $backup->download(CONTACTS_DIR_ATTACHMENTS, $filename, true);
        }
        die;

    case 'dn_attach': // download from list, assume the first document only
        $cID = db_prepare_input($_POST['rowSeq']);
        $result = $db->Execute("select attachments from " . TABLE_CONTACTS . " where id = $cID");
        $attachments = unserialize($result->fields['attachments']);
        foreach ($attachments as $key => $value) {
            $filename = 'contacts_' . $cID . '_' . $key . '.zip';
            if (file_exists(CONTACTS_DIR_ATTACHMENTS . $filename)) {
                require_once(DIR_FS_MODULES . 'ucounting/classes/backup.php');
                $backup = new backup();
                $backup->download(CONTACTS_DIR_ATTACHMENTS, $filename, true);
                die;
            }
        }

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
$include_header = true;
$include_footer = true;

switch ($action) {
    case 'properties':
        $include_header = false;
        $include_footer = false;
    // now fall through just like edit
    case 'edit':
    case 'update':
    case 'new':
        for ($i = 1; $i < 13; $i++) {
            $j = ($i < 10) ? '0' . $i : $i;
            $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => $j . '-' . strftime('%B', mktime(0, 0, 0, $i, 1, 2000)));
        }
        $today = getdate();
        for ($i = $today['year']; $i < $today['year'] + 10; $i++) {
            $year = strftime('%Y', mktime(0, 0, 0, 1, 1, $i));
            $expires_year[] = array('id' => $year, 'text' => $year);
        }
        // load the tax rates
        $tax_rates = inv_calculate_tax_drop_down($type);
        $sales_rep_array = gen_get_rep_ids($type);
        $sale_reps = array();
        foreach ($sales_rep_array as $value)
            $sale_reps[$value['id']] = $value['text'];
        $include_template = 'template_detail.php';
        define('PAGE_TITLE', ($action == 'new') ? $page_title_new : constant('ACT_' . strtoupper($type) . '_PAGE_TITLE_EDIT') . ' - (' . $cInfo->short_name . ') ' . $cInfo->address[m][0]->primary_name);
        break;
        
    default: 
        define('PAGE_TITLE', constant('ACT_' . strtoupper($type) . '_HEADING_TITLE'));
        $include_template = 'template_main.php';
        $ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=contacts&page=ajax&op=load_project_managers&type='.$_GET['type'], 'SSL');


}
?>