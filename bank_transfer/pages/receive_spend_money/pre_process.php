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
//  Path: /modules/ucbooks/pages/journal/pre_process.php
//
//this is for menu select coding by 4aixz(apu) date:16_04_2013
if (($_GET['jID'] == 2 && $_GET['sub_jID'] == 2) || $_GET['jID'] == 34) {
    $menu_select = 'banking';
    $sub_menu_select = 'Received_money';
    $next_running_num = 'received_money';
    define('PAGE_TITLE', "Received Money");
} else if (($_GET['jID'] == 2 && $_GET['sub_jID'] == 3) || $_GET['jID'] == 35) {
    $menu_select = 'banking';
    $sub_menu_select = 'Spend_money';
    $next_running_num = 'spend_money';
    define('PAGE_TITLE', "Spend Money");
}


$security_level = validate_user(SECURITY_ID_JOURNAL_ENTRY);
/* * ************  include page specific files    ******************** */
require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_ADMIN . 'modules/ucbooks/functions/ucbooks.php');
require_once(DIR_FS_WORKING . 'classes/gen_ledger.php');
/* * ************   page specific initialization  ************************ */
define('JOURNAL_ID', $_GET['jID']); // General Journal
define('SUB_JOURNAL_ID', $_GET['sub_jID']); // General Journal
$error = false;
$post_date = ($_POST['post_date']) ? gen_db_date($_POST['post_date']) : date('Y-m-d', time());
$period = gen_calculate_period($post_date);
$glEntry = new journal();
$glEntry->id = ($_POST['id'] <> '') ? $_POST['id'] : ''; // will be null unless opening an existing gl entry
// All general journal entries are in the default currency.
$glEntry->currencies_code = DEFAULT_CURRENCY;
$glEntry->currencies_value = 1;
$action = (isset($_GET['action']) ? $_GET['action'] : $_POST['todo']);
/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/journal/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}

function add_item_journal_rows($journal_item) { // read in journal items and add to journal row array
    for ($i = 0; $i < count($journal_item); $i++) {
        $tax_type = select_tax_type($journal_item[$i]['tax']);
        $gl_type = '';
        if ($journal_item[$i]['tax'] != 0) {
            if ($tax_type == 'c') {
                $gl_type = 'sos';
            } else if ($tax_type == 'v') {
                $gl_type = 'por';
            }
        }
        $journal_rows[] = array(
            'id' => $journal_item[$i]['id'],
            'qty' => '1',
            'gl_account' => $journal_item[$i]['gl_account'],
            'gl_type' => $gl_type,
            'description' => $journal_item[$i]['description'],
            'taxable' => $journal_item[$i]['tax'],
            'debit_amount' => $journal_item[$i]['debit_amount'],
            'credit_amount' => $journal_item[$i]['credit_amount'],
            'post_date' => $journal_item[$i]['post_date']
        );
    }
    return $journal_rows;
}

function add_tax_journal_rows($journal_rows) { // read in journal items and add to journal row tax array
    global $currencies;

    $total = 0;
    $auth_array = array();
    $tax_rates = ord_calculate_tax_drop_down('b');
    $tax_auths = gen_build_tax_auth_array();
    //$tax_discount   = $this->account_type == 'v' ? AP_TAX_BEFORE_DISCOUNT : AR_TAX_BEFORE_DISCOUNT;
    // calculate each tax value by authority per line item
    foreach ($journal_rows as $idx => $line_item) {
        if ($line_item['debit_amount'] != 0) {
            $set_value = 'debit_amount';
        } else {
            $set_value = 'credit_amount';
        }

        if ($line_item['taxable'] > 0) {
            foreach ($tax_rates as $rate) {
                if ($rate['id'] == $line_item['taxable']) {
                    $auths = explode(':', $rate['auths']);
                    foreach ($auths as $auth) {
                        if ($auth == "") {
                            continue;
                        }
                        $line_total = $line_item['debit_amount'] + $line_item['credit_amount']; // one will always be zero
                        if (ENABLE_ORDER_DISCOUNT && $tax_discount == '0') {
                            $line_total = $line_total * (1 - $this->disc_percent);
                        }
                        if (ROUND_TAX_BY_AUTH) {
                            $auth_array[$auth] += round((($tax_auths[$auth]['tax_rate'] / 100) * $line_total), $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
                        } else {
                            if (isset($_POST['tax_inclusive'])) {
                                //credit_amount * (tax_rates[tax_index].rate / (parseFloat(100)+parseFloat(tax_rates[tax_index].rate)));
                                $auth_array[$auth][$set_value] += ($tax_auths[$auth]['tax_rate'] / (100 + $tax_auths[$auth]['tax_rate'])) * $line_total;
                            } else {
                                $auth_array[$auth][$set_value] += ($tax_auths[$auth]['tax_rate'] / 100) * $line_total;
                            }
                        }
                    }
                }
            }
        }
    }
    // calculate each tax total by authority and put into journal row array
    foreach ($auth_array as $auth => $auth_tax_collected) {


        foreach ($auth_tax_collected as $key => $value) {
            if (($value == 0 || $value == '' ) && $tax_auths[$auth]['account_id'] == '')
                continue;
            $val = round($value, 2);
            $key_val = $key;

            $journal_rows_tax[] = array(// record for specific tax authority
                'qty' => '1',
                'gl_type' => 'tax', // code for tax entry
                $key_val => $val,
                'description' => $tax_auths[$auth]['description_short'],
                'gl_account' => $tax_auths[$auth]['account_id'],
                'post_date' => $journal_rows[0]['post_date'],
            );
        }

        //$total += $auth_tax_collected;
    }
    return $journal_rows_tax;
}

/*
 * Tax calcuation
 * 
 */

function calculate_tax($tax_id) {
    $tax_rates = ord_calculate_tax_drop_down('b');
    $tax_auths = gen_build_tax_auth_array();
    $tax_rate = 0;
    foreach ($tax_rates as $rate) {
        if ($rate['id'] == $tax_id) {
            $auths = explode(':', $rate['auths']);
            foreach ($auths as $auth) {
                if ($auth == "") {
                    continue;
                }
                $tax_rate = $tax_rate + $tax_auths[$auth]['tax_rate'];
            }
        }
    }
    return $tax_rate;
}

function select_tax_type($tax_id) {
    $tax_rates = ord_calculate_tax_drop_down_with_type('b');
    $tax_auths = gen_build_tax_auth_array();
    $tax_rate = 0;
    foreach ($tax_rates as $rate) {
        if ($rate['id'] == $tax_id) {
            $tax_type = $rate['type'];
        }
    }
    return $tax_type;
}

/* * *************   Act on the action request   ************************ */
switch ($action) {
    case 'save':
    case 'copy':
        validate_security($security_level, 2);
        // for copy operation, erase the id to force post a new journal entry with same values
        if ($action == 'copy') {
            $glEntry->id = '';
        }

        if (isset($_POST['tax_inclusive'])) {
            $tax_inclusive = 1;
        } else {
            $tax_inclusive = 0;
        }

        $glEntry->journal_id = JOURNAL_ID;
        $glEntry->sub_journal_id = SUB_JOURNAL_ID;
        $glEntry->post_date = $post_date;
        $glEntry->period = $period;
        $glEntry->admin_id = $_SESSION['admin_id'];
        $glEntry->purchase_invoice_id = db_prepare_input($_POST['purchase_invoice_id']);
        $glEntry->purch_order_id = db_prepare_input($_POST['purch_order_id']);
        $glEntry->recur_id = db_prepare_input($_POST['recur_id']);
        $glEntry->recur_frequency = db_prepare_input($_POST['recur_frequency']);
        $glEntry->store_id = db_prepare_input($_POST['store_id']);
        $glEntry->tax_inclusive = $tax_inclusive;
        $glEntry->bill_contact = db_prepare_input($_POST['bill_contact']);
        $glEntry->bill_primary_name = db_prepare_input($_POST['description']);
        if ($glEntry->store_id == '')
            $glEntry->store_id = 0;

        // process the request, build main record
        $x = 0;
        $total_amount = 0;
        $journal_entry_desc = GL_ENTRY_TITLE;
        while (isset($_POST['acct_' . $x])) { // while there are gl rows to read in
            if (!$_POST['debit_' . $x] && !$_POST['credit_' . $x]) { // skip blank rows
                $x++;
                continue;
            }
            $debit_amount = ($_POST['debit_' . $x]) ? $currencies->clean_value($_POST['debit_' . $x]) : 0;
            $credit_amount = ($_POST['credit_' . $x]) ? $currencies->clean_value($_POST['credit_' . $x]) : 0;
            //$glEntry->journal_rows[] = array(
            if($x==0 && !empty($_POST['purchase_invoice_id']) &&empty($_GET['oID'])) {
                $journal_item[] = array(
                    'id' => ($action == 'copy') ? '' : db_prepare_input($_POST['id_' . $x]),
                    'qty' => '1',
                    'gl_account' => db_prepare_input($_POST['acct_' . $x]),
                    'description' => db_prepare_input($_POST['purchase_invoice_id']),
                    'tax' => db_prepare_input($_POST['tax_' . $x]),
                    'debit_amount' => $debit_amount,
                    'credit_amount' => $credit_amount,
                    'post_date' => $glEntry->post_date);
                $total_amount += $debit_amount;
                if ($x == 1)
                    $journal_entry_desc = db_prepare_input($_POST['desc_' . $x]);
                $x++;
            } else {
                $journal_item[] = array(
                    'id' => ($action == 'copy') ? '' : db_prepare_input($_POST['id_' . $x]),
                    'qty' => '1',
                    'gl_account' => db_prepare_input($_POST['acct_' . $x]),
                    'description' => db_prepare_input($_POST['desc_' . $x]),
                    'tax' => db_prepare_input($_POST['tax_' . $x]),
                    'debit_amount' => $debit_amount,
                    'credit_amount' => $credit_amount,
                    'post_date' => $glEntry->post_date);
                $total_amount += $debit_amount;
                if ($x == 1)
                    $journal_entry_desc = db_prepare_input($_POST['desc_' . $x]);
                $x++;
            }
        }
        $journal_item = add_item_journal_rows($journal_item); //this function for get journal _item array
        $journal_rows_tax = add_tax_journal_rows($journal_item); //this funcion for ger journal_item tax array

        for ($i = 1; $i < count($journal_item); $i++) {
            if (isset($_POST['tax_inclusive'])) {
                if ($journal_item[$i]['taxable'] != 0) {
                    $tax_rate = calculate_tax($journal_item[$i]['taxable']);
                    if ($journal_item[$i]['debit_amount'] != "") {
                        $journal_item_amount = round($journal_item[$i]['debit_amount'] - ($journal_item[$i]['debit_amount'] * $tax_rate) / (100 + $tax_rate), $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
                        $journal_item[$i]['debit_amount'] = $journal_item_amount;
                    } else {
                        $journal_item_amount = round($journal_item[$i]['credit_amount'] - ($journal_item[$i]['credit_amount'] * $tax_rate) / (100 + $tax_rate), $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
                        $journal_item[$i]['credit_amount'] = $journal_item_amount;
                    }
                }
            }
        }

        if ($journal_rows_tax) {
            $glEntry->journal_rows = array_merge($journal_item, $journal_rows_tax); //merge jounal_item and journal_item tax
        } else {
            $glEntry->journal_rows = $journal_item;
        }

        $total_tax = 0;
        foreach ($journal_rows_tax as $journal_tax) {
            if (array_key_exists('debit_amount', $journal_tax)) {
                $total_tax = $total_tax + $journal_tax['debit_amount'];
            } elseif (array_key_exists('credit_amount', $journal_tax)) {
                $total_tax = $total_tax + $journal_tax['credit_amount'];
            }
        }
//        if (!isset($_POST['tax_inclusive'])) {   // for testing purpose only
//            $total_amount = $total_amount;
//        } else {
//            $total_amount = $total_amount + $total_tax;  
//        }


        $glEntry->journal_main_array = array(
            'period' => $glEntry->period,
            'journal_id' => JOURNAL_ID,
            'sub_journal_id' => SUB_JOURNAL_ID,
            'post_date' => $glEntry->post_date,
            'sales_tax' => $total_tax,
            //'total_amount' => $total_amount, // for testing purpose only
            'total_amount' => $currencies->clean_value($_POST['credit_0']), // for testing purpose only
            'description' => GL_ENTRY_TITLE,
            'purchase_invoice_id' => $glEntry->purchase_invoice_id,
            'purch_order_id' => $glEntry->purch_order_id,
            'admin_id' => $glEntry->admin_id,
            //'bill_primary_name' => $journal_entry_desc,
            'bill_primary_name' => $glEntry->bill_primary_name,
            'bill_contact' => $glEntry->bill_contact,
            'recur_id' => $glEntry->recur_id,
            'store_id' => $glEntry->store_id,
            'tax_inclusive' => $glEntry->tax_inclusive,
        );


        // check for errors and prepare extra values
        if (!$glEntry->period)
            $error = true; // bad post_date was submitted

        if (!$glEntry->journal_rows) { // no rows entered
            $messageStack->add(GL_ERROR_NO_ITEMS, 'error');
            $error = true;
        }
        // finished checking errors

        if (!$error) {
            // *************** START TRANSACTION *************************
            $db->transStart();
            if ($glEntry->recur_id > 0) { // if new record, will contain count, if edit will contain recur_id
                $first_id = $glEntry->id;
                $first_post_date = $glEntry->post_date;
                $first_purchase_invoice_id = $glEntry->purchase_invoice_id;
                if ($glEntry->id) { // it's an edit, fetch list of affected records to update if roll is enabled
                    $affected_ids = $glEntry->get_recur_ids($glEntry->recur_id, $glEntry->id);
                    for ($i = 0; $i < count($affected_ids); $i++) {
                        $glEntry->id = $affected_ids[$i]['id'];
                        $glEntry->journal_main_array['id'] = $affected_ids[$i]['id'];
                        if ($i > 0) { // Remove row id's for future posts, keep if re-posting single entry
                            for ($j = 0; $j < count($glEntry->journal_rows); $j++) {
                                $glEntry->journal_rows[$j]['id'] = '';
                            }
                            $glEntry->post_date = $affected_ids[$i]['post_date'];
                        }
                        $glEntry->period = gen_calculate_period($glEntry->post_date, true);
                        $glEntry->journal_main_array['post_date'] = $glEntry->post_date;
                        $glEntry->journal_main_array['period'] = $glEntry->period;
                        $glEntry->purchase_invoice_id = $affected_ids[$i]['purchase_invoice_id'];
                        if (!$glEntry->validate_purchase_invoice_id()) {
                            $error = true;
                            break;
                        } else if (!$glEntry->Post('edit')) {
                            $error = true;
                            break;
                        }
                        // test for single post versus rolling into future posts, terminate loop if single post
                        if (!$glEntry->recur_frequency)
                            break;
                    }
                } else { // it's an insert
                    // fetch the next recur id
                    $glEntry->journal_main_array['recur_id'] = time();
                    $day_offset = 0;
                    $month_offset = 0;
                    $year_offset = 0;
                    for ($i = 0; $i < $glEntry->recur_id; $i++) {
                        if (!$glEntry->validate_purchase_invoice_id()) {
                            $error = true;
                            break;
                        } else if (!$glEntry->Post('insert')) {
                            $error = true;
                            break;
                        }
                        $glEntry->id = '';
                        $glEntry->journal_main_array['id'] = $glEntry->id;
                        for ($j = 0; $j < count($glEntry->journal_rows); $j++)
                            $glEntry->journal_rows[$j]['id'] = '';
                        switch ($glEntry->recur_frequency) {
                            default:
                            case '1': $day_offset = ($i + 1) * 7;
                                break; // Weekly
                            case '2': $day_offset = ($i + 1) * 14;
                                break; // Bi-weekly
                            case '3': $month_offset = ($i + 1) * 1;
                                break; // Monthly
                            case '4': $month_offset = ($i + 1) * 3;
                                break; // Quarterly
                            case '5': $year_offset = ($i + 1) * 1;
                                break; // Yearly
                        }
                        $glEntry->post_date = gen_specific_date($post_date, $day_offset, $month_offset, $year_offset);
                        $glEntry->period = gen_calculate_period($glEntry->post_date, true);
                        if (!$glEntry->period && $i < ($glEntry->recur_id - 1)) { // recur falls outside of available periods, ignore last calculation
                            $messageStack->add(ORD_PAST_LAST_PERIOD, 'error');
                            $error = true;
                            break;
                        }
                        $glEntry->journal_main_array['post_date'] = $glEntry->post_date;
                        $glEntry->journal_main_array['period'] = $glEntry->period;
                        $glEntry->purchase_invoice_id = string_increment($glEntry->journal_main_array['purchase_invoice_id']);
                    }
                }
                // restore the first values to continue with post process
                $glEntry->id = $first_id;
                $glEntry->journal_main_array['id'] = $first_id;
                $glEntry->post_date = $first_post_date;
                $glEntry->journal_main_array['post_date'] = $first_post_date;
                $glEntry->purchase_invoice_id = $first_purchase_invoice_id;
                $glEntry->journal_main_array['purchase_invoice_id'] = $first_purchase_invoice_id;
            } else {
                if (!$glEntry->validate_purchase_invoice_id()) {
                    $error = true;
                } else if (!$glEntry->Post($glEntry->id ? 'edit' : 'insert')) {
                    $error = true;
                }
            }
            if (!$error) {
                $db->transCommit();
                if (empty($_POST['id'])) {
                    $glEntry->increment_purchase_invoice_id();
                }
                if (DEBUG)
                    $messageStack->write_debug();
                gen_add_audit_log(GL_LOG_ADD_JOURNAL . (($glEntry->id) ? TEXT_EDIT : TEXT_ADD), $glEntry->purchase_invoice_id);
                gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&page=receive_spend_money&oID=' . $glEntry->id . '&jID=' . JOURNAL_ID . '&sub_jID=' . SUB_JOURNAL_ID . '&action=edit', 'SSL'));
            }
            // *************** END TRANSACTION *************************
        }
        $db->transRollback();
        $messageStack->add(GL_ERROR_NO_POST, 'error');
        if (DEBUG)
            $messageStack->write_debug();
        $cInfo = new objectInfo($_POST); // if we are here, there was an error, reload page
        $cInfo->post_date = gen_db_date($_POST['post_date']);
        break;

    case 'delete':
        validate_security($security_level, 4);
        // check for errors and prepare extra values
        if (!$glEntry->id) {
            $error = true;
        } else {
            $delGL = new journal();
            $delGL->journal($glEntry->id); // load the posted record based on the id submitted
            $recur_id = db_prepare_input($_POST['recur_id']);
            $recur_frequency = db_prepare_input($_POST['recur_frequency']);
            // *************** START TRANSACTION *************************
            $db->transStart();
            if ($recur_id > 0) { // will contain recur_id
                $affected_ids = $delGL->get_recur_ids($recur_id, $delGL->id);
                for ($i = 0; $i < count($affected_ids); $i++) {
                    $delGL->id = $affected_ids[$i]['id'];
                    $delGL->journal($delGL->id); // load the posted record based on the id submitted
                    if (!$delGL->unPost('delete')) {
                        $error = true;
                        break;
                    }
                    // test for single post versus rolling into future posts, terminate loop if single post
                    if (!$recur_frequency)
                        break;
                }
            } else {
                if (!$delGL->unPost('delete'))
                    $error = true;
            }

            if (!$error) {
                $db->transCommit(); // if not successful rollback will already have been performed
                if (DEBUG)
                    $messageStack->write_debug();
                gen_add_audit_log(GL_LOG_ADD_JOURNAL . TEXT_DELETE, $delGL->purchase_invoice_id);
                gen_redirect(html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&page=status&jID=' . JOURNAL_ID . '&sub_jID=' . SUB_JOURNAL_ID . '&list=1', 'SSL'));
            } // *************** END TRANSACTION *************************
        }
        $db->transRollback();
        $messageStack->add(GL_ERROR_NO_DELETE, 'error');
        if (DEBUG)
            $messageStack->write_debug();
        $cInfo = new objectInfo($_POST); // if we are here, there was an error, reload page
        $cInfo->post_date = gen_db_date($_POST['post_date']);
        break;

    case 'edit':
        $oID = (int) $_GET['oID'];
        validate_security($security_level, 2);
        $cInfo = new objectInfo(array());
        break;
    default:
}

/* * ***************   prepare to display templates  ************************ */
// retrieve the list of gl accounts and fill js arrays
$gl_array_list = gen_coa_pull_down();
$i = 0;
$js_gl_array = 'var js_gl_array = new Array();' . chr(10);
foreach ($gl_array_list as $account) {
    $is_asset = $coa_types_list[$account['type']]['asset'] ? '1' : '0';
    $js_gl_array .= 'js_gl_array[' . $i . '] = new glProperties("' . $account['id'] . '", "' . $account['text'] . '", "' . $is_asset . '");' . chr(10);
    $i++;
}

$gl_array_list2 = gen_coa_pull_down_bank();
$i = 0;
$js_gl_array2 = 'var js_gl_array2 = new Array();' . chr(10);
foreach ($gl_array_list2 as $account2) {
    $is_asset = $coa_types_list[$account2['type']]['asset'] ? '1' : '0';
    $js_gl_array2 .= 'js_gl_array2[' . $i . '] = new glProperties("' . $account2['id'] . '", "' . $account2['text'] . '", "' . $is_asset . '");' . chr(10);
    $i++;
}

// load the tax rates
$tax_rates = ord_calculate_tax_drop_down("b");
// generate a rate array parallel to the drop down for the javascript total calculator
$js_tax_rates = 'var tax_rates = new Array(' . count($tax_rates) . ');' . chr(10);
for ($i = 0; $i < count($tax_rates); $i++) {
    $js_tax_rates .= 'tax_rates[' . $i . '] = new salesTaxes("' . $tax_rates[$i]['id'] . '", "' . $tax_rates[$i]['text'] . '", "' . $tax_rates[$i]['rate'] . '");' . chr(10);
}

$cal_gl = array(
    'name' => 'datePost',
    'form' => 'journal',
    'fieldname' => 'post_date',
    'imagename' => 'btn_date_1',
    'default' => gen_locale_date($post_date),
);

$include_header = true;
$include_footer = true;
$include_tabs = false;
$include_calendar = true;
$include_template = 'template_main.php';
define('PAGE_TITLE', GL_ENTRY_TITLE);
?>