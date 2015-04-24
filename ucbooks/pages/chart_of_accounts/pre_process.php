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
//  Path: /modules/ucbooks/pages/admin/pre_process.php
//

$menu_select = 'company';
$sub_menu_select = 'chart_of_account';
$security_level = validate_user(SECURITY_ID_CONFIGURATION);




/* * ************  include page specific files    ******************** */
gen_pull_language($module, 'admin');
gen_pull_language('ucounting', 'admin');
require_once(DIR_FS_WORKING . 'functions/ucbooks.php');
require_once(DIR_FS_WORKING . 'classes/install.php');
require_once(DIR_FS_WORKING . 'classes/chart_of_accounts.php');
require_once(DIR_FS_WORKING . 'classes/tax_auths.php');
require_once(DIR_FS_WORKING . 'classes/tax_auths_vend.php');
require_once(DIR_FS_WORKING . 'classes/tax_rates.php');
require_once(DIR_FS_WORKING . 'classes/tax_rates_vend.php');

/* * ************   page specific initialization  ************************ */
$error = false;
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];

if (isset($_POST['page_name']) && $_POST['page_name'] == 'chart_of_account' && empty($action)) {
    $menu_select = 'home';
    $sub_menu_select = 'setup_wizard';
    $action = 'save';
}

$install = new ucbooks_admin();
$chart_of_accounts = new chart_of_accounts();
$tax_auths = new tax_auths();
$tax_auths_vend = new tax_auths_vend();
$tax_rates = new tax_rates();
$tax_rates_vend = new tax_rates_vend();

/* * *************   Act on the action request   ************************ */
switch ($action) {
    case 'export':
        validate_security($security_level, 3);
//        header('Content-Type: text/csv; charset=utf-8');
//        header('Content-Disposition: attachment; filename=sample_chart_of_account.csv');
//        $output = fopen('php://output', 'w');
//        $csv_array = array();
//        $csv_array[] = array('id', 'description', 'heading', 'primary', 'type');
//        $sql = 'SELECT SQL_CALC_FOUND_ROWS id, description, heading_only, primary_acct_id, account_type, account_inactive
//		FROM   chart_of_accounts
//		where 1=1 
//		ORDER BY  id
//				 	asc
//		LIMIT 10';
//        $result = $db->Execute($sql);
//        while (!$result->EOF) {
//            $csv_array[] = array($result->fields['id'], $result->fields['description'], $result->fields['heading_only'], $result->fields['primary_acct_id'], $result->fields['account_type']);
//
//            $result->MoveNext();
//        }
//        $csv_array[] = array('Field description given below');
//        $csv_array[] = array('id:', 'GL Account');
//        $csv_array[] = array('description', 'Account Description');
//        $csv_array[] = array('heading', 'This account is a heading and cannot accept posted values', 'if yes then give 1,if no then give 0');
//        $csv_array[] = array('primary', 'If this account is a sub-account, select primary account');
//        $csv_array[] = array('type', 'Account type (Required)');
//        global $coa_types_list;
//        $csv_array[] = array('Description of Account type (Required)');
//        foreach ($coa_types_list as $key => $item) {
//
//            $csv_array[] = array($item['id'], $item['text']);
//        }
//
//
//        foreach ($csv_array as $csv_row) {
//            fputcsv($output, $csv_row);
//        }

        $file = 'sample_file/sample_chart_of_account.csv';
        if (!file_exists($file))
            die("I'm sorry, the file doesn't seem to exist.");


        // Send file headers
        header("Content-type: txt");
        header("Content-Disposition: attachment;filename=sample_chart_of_account.csv");
        header("Content-Transfer-Encoding: binary");
        header('Pragma: no-cache');
        header('Expires: 0');
        // Send the file contents.
        set_time_limit(0);
        readfile($file);
        exit;
    case 'import':
        validate_security($security_level, 3);
        $delete_chart = ($_POST['delete_chart']) ? true : false;
        $std_chart = db_prepare_input($_POST['std_chart']);
        // first verify the file was uploaded ok
        if (isset($_FILES))
            $file_name = $_FILES['file_name']['name'];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        if ($ext != 'csv') {
            if (!$std_chart)
                if (!validate_upload('file_name', 'text', 'txt'))
                    break;
            if ($delete_chart) {
                $result = $db->Execute("select id from " . TABLE_JOURNAL_MAIN . " limit 1");
                if ($result->RecordCount() > 0) {
                    $messageStack->add(GL_JOURNAL_NOT_EMTPY, 'error');
                    break;
                }
                $db->Execute("TRUNCATE TABLE " . TABLE_CHART_OF_ACCOUNTS);
                $db->Execute("TRUNCATE TABLE " . TABLE_CHART_OF_ACCOUNTS_HISTORY);
            }
            $filename = $std_chart ? (DIR_FS_WORKING . 'language/' . $_SESSION['language'] . '/charts/' . $std_chart) : $_FILES['file_name']['tmp_name'];
            $accounts = xml_to_object(file_get_contents($filename));
            if (is_object($accounts->ChartofAccounts))
                $accounts = $accounts->ChartofAccounts; // just pull the first one
            if (is_object($accounts->account))
                $accounts->account = array($accounts->account); // in case of only one chart entry
            if (is_array($accounts->account))
                foreach ($accounts->account as $account) {
                    $result = $db->Execute("select id from " . TABLE_CHART_OF_ACCOUNTS . " where id = '" . $account->id . "'");
                    if ($result->RecordCount() > 0) {
                        $messageStack->add(sprintf(GL_ACCOUNT_DUPLICATE, $account->id), 'error');
                        continue;
                    }
                    $sql_data_array = array(
                        'id' => $account->id,
                        'description' => $account->description,
                        'heading_only' => $account->heading ? $account->heading : 0,
                        'primary_acct_id' => $account->primary,
                        'account_type' => $account->type,
                    );
                    db_perform(TABLE_CHART_OF_ACCOUNTS, $sql_data_array, 'insert');
                }
        } else if ($ext == 'csv') {
            $uploaded_file = 'tmp/' . $file_name;
            if (move_uploaded_file($_FILES['file_name']['tmp_name'], $uploaded_file)) {
                if (($handle = fopen('tmp/' . $file_name, "r")) !== FALSE) {
                    $row = 1;
                    while (($data = fgetcsv($handle) ) !== FALSE) {
                        $check = false;
                        if ($row != 1) {
                            $result = $db->Execute("select id from " . TABLE_CHART_OF_ACCOUNTS . " where id = '" . $data[0] . "'");
                            if ($result->RecordCount() > 0) {
                                $result1 = $db->Execute("select id from " . TABLE_JOURNAL_ITEM . " where gl_account = '" . $data[0] . "'");
                                if ($result1->RecordCount() > 0) {
                                    $messageStack->add(sprintf(GL_ACCOUNT_DUPLICATE, $data[0]), 'error');
                                    continue;
                                } else {
                                    if (preg_match('/[^a-z0-9_-]/i', $data[0])) {
                                        $check = false;
                                    } else if (empty($data[0])) {
                                        $check = false;
                                    } else {
                                        $check = true;
                                    }
                                    $sql_data_array = array(
                                        'id' => $data[0],
                                        'description' => $data[1],
                                        'heading_only' => $data[2] ? $data[2] : 0,
                                        'primary_acct_id' => $data[3],
                                        'account_type' => $data[4],
                                    );
                                    if ($check == true) {
                                        db_perform(TABLE_CHART_OF_ACCOUNTS, $sql_data_array, 'update', "id = '$data[0]'");
                                        $messageStack->add(sprintf('The gl account: %s is successfully updated', $data[0]), 'success');
                                    } else {
                                        $messageStack->add(sprintf('Sorry, {!0@#$^&*+"/\[]()<>} these are not allowed in COA name for : %s', $data[0]), 'error');
                                    }
                                    continue;
                                }
                            }
                            if (preg_match('/[^a-z0-9_-]/i', $data[0])) {
                                $check = false;
                            } else if (empty($data[0])) {
                                $check = false;
                            } else {

                                $check = true;
                            }
                            $sql_data_array = array(
                                'id' => $data[0],
                                'description' => $data[1],
                                'heading_only' => $data[2] ? $data[2] : 0,
                                'primary_acct_id' => $data[3],
                                'account_type' => $data[4],
                            );
                            if ($check == true) {
                                db_perform(TABLE_CHART_OF_ACCOUNTS, $sql_data_array, 'insert');
                            } else {
                                $messageStack->add(sprintf('Sorry, {!@0#$^&*+"/\[]()<>} these are not allowed in COA name for : %s', $data[0]), 'error');
                            }
                        }
                        $row++;
                    }
                }
            }
        }
        build_and_check_account_history_records();
        break;
    case 'save':
        validate_security($security_level, 3);
// some special values for checkboxes
        $_POST['ar_use_credit_limit'] = isset($_POST['ar_use_credit_limit']) ? '1' : '0';
        $_POST['ap_use_credit_limit'] = isset($_POST['ap_use_credit_limit']) ? '1' : '0';
// save general tab
        foreach ($install->keys as $key => $default) {
            $field = strtolower($key);
            if (isset($_POST[$field]))
                write_configure($key, $_POST[$field]);
        }

        if (isset($_POST['page_name']) && $_POST['page_name'] == 'chart_of_account') {

            gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=chart_of_account', 'SSL'));
        } else {
            gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
        }

//gen_redirect(html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL'));
        $messageStack->add(UCBOOKS_CONFIG_SAVED, 'success');
        break;
    case 'delete_chart' :
        validate_security($security_level, 4);
        $result = $db->Execute('SELECT * FROM ' . TABLE_JOURNAL_MAIN . '');
        if ($result->RecordCount() > 0) {
            $messageStack->add('This Chart of account can not be deleted', 'error');
            gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=chart_of_account', 'SSL'));
            break;
        } else {
            $result = $db->Execute('DELETE FROM ' . TABLE_CHART_OF_ACCOUNTS . '');
            $result1 = $db->Execute('DELETE FROM ' . TABLE_CHART_OF_ACCOUNTS_HISTORY . '');
            if ($result->RecordCount() <= 0 && $result1->RecordCount() <= 0) {
                $messageStack->add('You have successfully deleted this chart of accounts', 'success');
                gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=chart_of_account', 'SSL'));
                break;
            }
        }

    case 'delete':
        validate_security($security_level, 4);
        $subject = $_POST['subject'];
        $id = $_POST['rowSeq'];
        if (!$subject || $id == '')
            break;
        $$subject->btn_delete($id);
        switch ($subject) {
            case 'chart_of_accounts': $default_tab_id = 'chart_of_accounts';
                break;
            case 'tax_auths': $default_tab_id = 'tax_auths';
                $_POST['page_name'] = 'setup_tax_codes';
                $_POST['action_type'] = 'setup_tax_codes';
                break;
            case 'tax_auths_vend': $default_tab_id = 'tax_auths_vend';
                $_POST['page_name'] = 'setup_tax_codes';
                $_POST['action_type'] = 'setup_tax_codes';
                break;
            case 'tax_rates': $default_tab_id = 'tax_rates';
                $_POST['page_name'] = 'setup_tax_codes';
                $_POST['action_type'] = 'setup_tax_codes';
                break;
            case 'tax_rates_vend': $default_tab_id = 'tax_rates_vend';
                $_POST['page_name'] = 'setup_tax_codes';
                $_POST['action_type'] = 'setup_tax_codes';
                break;
        }
        break;
    default:
}

/* * ***************   prepare to display templates  ************************ */
// build some general pull down arrays
$sel_yes_no = array(
    array('id' => '0', 'text' => TEXT_NO),
    array('id' => '1', 'text' => TEXT_YES),
);

$sel_gl_desc = array(
    array('id' => '0', 'text' => TEXT_NUMBER),
    array('id' => '1', 'text' => TEXT_DESCRIPTION),
    array('id' => '2', 'text' => TEXT_BOTH),
);

$sel_order_lines = array(
    array('id' => '0', 'text' => TEXT_DOUBLE_MODE),
    array('id' => '1', 'text' => TEXT_SINGLE_MODE),
);

$sel_inv_due = array(// invoice date versus due date for aging
    array('id' => '0', 'text' => BNK_INVOICE_DATE),
    array('id' => '1', 'text' => BNK_DUE_DATE),
);

// load available charts based on language
if (is_dir($dir = DIR_FS_WORKING . 'language/' . $_SESSION['language'] . '/charts')) {
    $charts = scandir($dir);
} else {
    $charts = scandir(DIR_FS_WORKING . 'language/en_us/charts');
}
$sel_chart = array(array('id' => '0', 'text' => TEXT_SELECT));
foreach ($charts as $chart) {
    if (strpos($chart, 'xml')) {
        $temp = xml_to_object(file_get_contents(DIR_FS_WORKING . 'language/' . $_SESSION['language'] . '/charts/' . $chart));
        if ($temp->ChartofAccounts)
            $temp = $temp->ChartofAccounts;
        $sel_chart[] = array('id' => $chart, 'text' => $temp->description);
    }
}



// some pre-defined gl accounts
$cash_chart = gen_coa_pull_down(2, false, true, false, $restrict_types = array(0));    // cash types only
$ar_chart = gen_coa_pull_down(2, false, true, false, $restrict_types = array(2));    // ar types only
$ap_chart = gen_coa_pull_down(2, false, true, false, $restrict_types = array(20));   // ap types only
$ocl_chart = gen_coa_pull_down(2, false, true, false, $restrict_types = array(22));   // other current liability types only
$inc_chart = gen_coa_pull_down(2, false, true, false, $restrict_types = array(30));   // income types only
$inv_chart = gen_coa_pull_down(2, false, true, false, $restrict_types = array(4, 34)); // inv, expenses types only

$include_header = true;
$include_footer = true;
$include_tabs = true;
$include_calendar = false;
if ((isset($_GET['action_type']) && $_GET['action_type'] == "chart_of_account") || (isset($_POST['page_name']) && $_POST['page_name'] == 'chart_of_account')) {
    $menu_select = 'home';
    $sub_menu_select = 'setup_wizard';
    $setupwizard_menu = "";
    $include_header = true;
    $include_footer = true;
    $setupwizard_menu = 'step_4';
    $include_template = 'chart_of_account.php';
    define('PAGE_TITLE', BOX_UCBOOKS_MODULE_ADM);
} else if ((isset($_GET['action_type']) && $_GET['action_type'] == "setup_account_defaults") || (isset($_POST['page_name']) && $_POST['page_name'] == 'setup_account_defaults')) {
    $menu_select = 'home';
    $sub_menu_select = 'setup_wizard';
    $setupwizard_menu = "";
    $include_header = true;
    $include_footer = true;
    $chart_of_accounts->title = 'Setup Account Defaults';
    $setupwizard_menu = 'step_7';
    $include_template = 'setup_account_defaults.php';
    define('PAGE_TITLE', BOX_UCBOOKS_MODULE_ADM);
} else if ((isset($_GET['action_type']) && $_GET['action_type'] == "setup_tax_codes") || (isset($_POST['page_name']) && $_POST['page_name'] == 'setup_tax_codes')) {
    $menu_select = 'home';
    $chart_of_accounts->title = 'Setup Tax Codes';
    $sub_menu_select = 'setup_wizard';
    $setupwizard_menu = "";
    $include_header = true;
    $include_footer = true;
    $setupwizard_menu = 'step_8';
    $include_template = 'setup_tax_codes.php';
    define('PAGE_TITLE', BOX_UCBOOKS_MODULE_ADM);
} else {
    $include_template = 'template_main.php';
    define('PAGE_TITLE', BOX_UCBOOKS_MODULE_ADM);
}
?>