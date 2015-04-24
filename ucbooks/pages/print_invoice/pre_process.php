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
//  Path: /modules/ucbooks/pages/orders/pre_process.php
//
/* * ************   Check user security   **************************** */


//this is for menu select coding by 4aixz(apu) date:16_04_2013




define('JOURNAL_ID', $_GET['jID']);

//$security_level = validate_user($security_token);
/* * ************  include page specific files    ******************** */
gen_pull_language('contacts');

require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
require_once(DIR_FS_MODULES . 'inventory/functions/inventory.php');
require_once(DIR_FS_WORKING . 'functions/ucbooks.php');
require_once(DIR_FS_WORKING . 'classes/gen_ledger.php');
require_once(DIR_FS_WORKING . 'classes/orders.php');
require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');
if (defined('MODULE_SHIPPING_STATUS')) {
    require_once(DIR_FS_MODULES . 'shipping/functions/shipping.php');
    require_once(DIR_FS_MODULES . 'shipping/defaults.php');
}
/* * ************   page specific initialization  ************************ */

if (file_exists(DIR_FS_ADMIN . 'user_company/' . COMPANY_CODE . '/user_design/user_design.php')) {   // this is for if this is user specific design
    //$a = "ast";
    require_once(DIR_FS_ADMIN . 'user_company/' . COMPANY_CODE . '/user_design/user_design.php');

    if ($_GET['jID'] == 18 || $_GET['jID'] == 20) {
        $sql = "SELECT * FROM journal_main WHERE id = " . $_GET['oID'];
        $res = mysql_query($sql);
        $invoice_main = mysql_fetch_object($res);

        $contact = $db->Execute("select * from " . TABLE_CONTACTS . " where id = '" . $invoice_main->bill_acct_id . "'");
        $type = $contact->fields['type'];
        $invoices = fill_paid_invoice_array($_GET['oID'], $invoice_main->bill_acct_id, $type);
        $invoice_item = $invoices['invoices'];
    }

    $ob_get_design = new get_design();
    $new_design = $ob_get_design->get_pram($_GET['oID'], $_GET['jID'], $_GET['pdf_type'], $_GET['action'], $invoice_item);
    $pdf = $new_design['pdf'];
    $css = $new_design['css'];
    $res = mysql_query("SELECT * FROM configuration");
    while ($configuration_item = mysql_fetch_assoc($res)) {
        $configuration[] = $configuration_item;
    }
} else {  // this is according to the system, not user specific
    if ($_GET['action'] == "pdf" || $_GET['action'] == "email") {
        $sql = "SELECT * FROM journal_main WHERE id = " . $_GET['oID'];
        $res = mysql_query($sql);
        $invoice_main = mysql_fetch_object($res);

        if ($_GET['jID'] == 18 || $_GET['jID'] == 20) {  // this is for payable and receiveable
            $contact = $db->Execute("select * from " . TABLE_CONTACTS . " where id = '" . $invoice_main->bill_acct_id . "'");
            $type = $contact->fields['type'];
            $invoices = fill_paid_invoice_array($_GET['oID'], $invoice_main->bill_acct_id, $type);
            $invoice_item = $invoices['invoices'];
        } else {
//            $sql = "SELECT * FROM journal_item WHERE ref_id = " . $invoice_main->id;
//            $res = mysql_query($sql);
//            while ($item = mysql_fetch_assoc($res)) {
//                $invoice_item[] = $item;
//            }

            $sql = "SELECT journal_item.id, journal_item.ref_id, journal_item.item_cnt, journal_item.gl_type, journal_item.sku, journal_item.qty, 
journal_item.description, journal_item.debit_amount, journal_item.credit_amount, journal_item.gl_account, journal_item.taxable, journal_item.full_price,
journal_item.post_date,journal_item.uom,journal_item.uom_qty, tax_rates.description_short, tax_rates.rate_accounts, journal_item.discval, journal_item.disc_percent  
FROM journal_item left join tax_rates on journal_item.taxable = tax_rates.tax_rate_id WHERE ref_id = " . $invoice_main->id;
            $res = mysql_query($sql);
            while ($item = mysql_fetch_assoc($res)) {
                $invoice_item[] = $item;
            }
          
            $query_discount = mysql_query("SELECT SUM(journal_item.discval) as discount_col from journal_item WHERE ref_id='".$invoice_main->id."'");
            $data           = mysql_fetch_assoc($query_discount);
            $discount_col   = $data['discount_col'];  
          
            $adjustment = array();
            $transfer = array();
            foreach ($invoice_item as $key => $all_item) {
                if ($_GET['pdf_type'] == 'adjustment') {
                    if (!empty($all_item['sku'])) {
                        $adjustment_result = mysql_query('SELECT sum(remaining) as remaining, unit_cost from ' . TABLE_INVENTORY_HISTORY . ' where journal_id=6 and sku="' . $all_item['sku'] . '"');
                        while ($adjustment_item = mysql_fetch_assoc($adjustment_result)) {
                            $item_cost = $db->Execute('SELECT item_cost from ' . TABLE_INVENTORY . ' where sku="' . $all_item['sku'] . '"');
                            $adjustment[$key]['unit_cost'] = $item_cost->fields['item_cost'];
                            $adjustment[$key]['remaining'] = strval(load_store_stock($all_item['sku'], $invoice_main->store_id)) - $all_item['qty'];
                            $adjustment[$key]['sku'] = $all_item['sku'];
                            $adjustment[$key]['qty'] = $all_item['qty'];
                            $adjustment[$key]['description'] = $all_item['description'];
                            $adjustment[$key]['balance'] = strval(load_store_stock($all_item['sku'], $invoice_main->store_id));
                        }
                    }
                }
                if ($_GET['pdf_type'] == 'transfer') {
                    if (!empty($all_item['sku'])) {
                        $store = $db->Execute('SELECT store_id,qty from ' . TABLE_INVENTORY_HISTORY . ' where journal_id=16 and sku="' . $all_item['sku'] . '" and ref_id="' . $invoice_main->so_po_ref_id . '"');
                        $transfer_store_id = $store->fields['store_id'];
                        $transfer[$key]['remaining'] = strval(load_store_stock($all_item['sku'], $invoice_main->store_id)) + $store->fields['qty'];
                        $transfer[$key]['sku'] = $all_item['sku'];
                        $transfer[$key]['qty'] = $store->fields['qty'];
                        $transfer[$key]['description'] = $all_item['description'];
                        $transfer[$key]['balance'] = strval(load_store_stock($all_item['sku'], $invoice_main->store_id));
                    }
                }
                if ($all_item['item_cnt'] != 0) {
                    $item_val['item'][] = $all_item;
                    $rate_account = $all_item['rate_accounts'];
                    $rate_account_in_array = explode(":", $rate_account);
                    $total_rate_percentage = 0;
                    $total_tax = 0;
                    $total_price = $all_item['debit_amount'] + $all_item['credit_amount'];
                    foreach ($rate_account_in_array as $value) {
                        if ($value != "") {
                            $sql = "Select tax_rate from tax_authorities where tax_auth_id=" . $value;
                            $res = mysql_query($sql);
                            $tax_rate = mysql_fetch_assoc($res);
                            $total_rate_percentage = $total_rate_percentage + $tax_rate['tax_rate'];
                        }
                    }
                    $total_tax = $total_tax + (($all_item['debit_amount'] + $all_item['credit_amount']) * ($total_rate_percentage / 100));
                    $item_val['total_rate_percentage'][$all_item['description_short']] = $item_val['total_rate_percentage'][$all_item['description_short']] + $total_tax;
                    $item_val['total_price_on_tax'][$all_item['description_short']] = $item_val['total_price_on_tax'][$all_item['description_short']] + $total_price;
                } else if ($all_item['gl_type'] == "tax") {
                    $item_val['tax'][] = $all_item;
                } else if ($all_item['gl_type'] == "dsc") {
                    $item_val['dsc'] = $all_item;
                } else if ($all_item['gl_type'] == "frt") {
                    $item_val['frt'] = $all_item;
                } else if ($all_item['gl_type'] == "ttl") {
                    $item_val['ttl'] = $all_item;
                }
            }
        }


        $res = mysql_query("SELECT * FROM configuration");

        while ($configuration_item = mysql_fetch_assoc($res)) {
            $configuration[] = $configuration_item;
        }
        $aa = "SELECT gov_id_number,account_number FROM contacts where id = '" . $invoice_main->bill_acct_id . "'";
        $gst = mysql_query($aa);

        while ($gstt = mysql_fetch_assoc($gst)) {
            $gst_number = $gstt['gov_id_number'];
            $account_number = $gstt['account_number'];
        }
    }
}
// get sales ref name//
$sales_ref_name_array = array();
$sales_ref_name_array = gen_get_rep_ids($type);
foreach ($sales_ref_name_array as $sales_rep) {
    if ($invoice_main->rep_id == $sales_rep['id']) {
        $sales_rep = $sales_rep['text'];
        break;
    }
}
// end sales ref name block//
$branchs = array();
$branchs = gen_get_store_ids();
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
$include_template = 'template_main_' . PRINT_INVOICE_STYLE . '.php'; // include display template (required)
define('PAGE_TITLE', constant('ORD_TEXT_' . JOURNAL_ID . '_WINDOW_TITLE'));
?>