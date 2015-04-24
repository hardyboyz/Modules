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
//  Path: /modules/ucounting/pages/ctl_panel/pre_process.php
//
$security_level = validate_user(0, true);
error_reporting(0);
/* * ************  include page specific files    ******************** */

/* * ************   page specific initialization  ************************ */

// retireve current user profile for this page


/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_WORKING . 'custom/pages/ctl_panel/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}

/* * *************   Act on the action request   ************************ */


/* * ***************   prepare to display templates  ************************ */


$sql = "select * from configuration WHERE configuration_key = 'COMPANY_NAME' or configuration_key = 'COMPANY_ID' or configuration_key='TAX_ID'";

$res = mysql_query($sql);
while ($item = mysql_fetch_assoc($res)) {
    $com_info[] = $item;
}
$company_array['type'] = "C";
foreach ($com_info as $key => $val) {
    $company_array[$val['configuration_key']] = $val['configuration_value'];
}
$company_array['PeriodStart'] = $_POST['period_start'];
$company_array['PeriodEnd'] = $_POST['period_end'];
$company_array['GAFCreationDate'] = date('m-d-Y');
$company_array['ProductVersion'] = 'Ucounting 2.0';
$company_array['GAFVersion'] = 'GAFv1.0.0';
$time_start = strtotime(str_replace("/", "-", $_POST['period_start']));
$time_end = strtotime(str_replace("/", "-", $_POST['period_end']));
$start_date = date('Y-m-d', $time_start);
$end_date = date('Y-m-d', $time_end);

//this is for Purchase 
$sql = "select * from journal_main WHERE journal_id = '6' and post_date BETWEEN '" . $start_date . "' and '" . $end_date . "'";
$res = mysql_query($sql);
while ($item = mysql_fetch_assoc($res)) {
    $PurcInfo[] = $item;
}
if (isset($PurcInfo)) {
    
    $purc_total_amount = '';
    $purc_total_tax = '';
    $purc_total = count($PurcInfo);

    foreach ($PurcInfo as $get_purc_item) {
        $purc_info_array['type'] = "P";
        

        $sql = "select * from contacts where id = " . $get_purc_item['bill_address_id'];
        $res = mysql_query($sql);
        while ($item = mysql_fetch_assoc($res)) {
            $purc_info_array['SupplierUEN'] = $item['short_name'];
        }
        $purc_info_array['SupplierName'] = $get_purc_item['bill_primary_name'];
        $purc_info_array['InvoiceDate'] = date('d/m/Y',strtotime($get_purc_item['post_date'])) ;
        $purc_info_array['InvoiceNo'] = $get_purc_item['purchase_invoice_id'];
        $sql = "SELECT * FROM journal_item WHERE ref_id = " . $get_purc_item['id'];
        $res = mysql_query($sql);
        $invoice_item = '';

        while ($item = mysql_fetch_assoc($res)) {
            $invoice_item[] = $item;
        }
        $purc_item = '';
        $purc_tax = '';
        $purc_dsc = '';
        $purc_frt = '';
        $purc_ttl = '';
        foreach ($invoice_item as $get_specific_val) {

            if ($get_specific_val['gl_type'] == "por") {
                $purc_item[] = $get_specific_val;
            }
            else if ($get_specific_val['gl_type'] == "tax") {
                $purc_tax[] = $get_specific_val;
            }
            else if ($get_specific_val['gl_type'] == "dsc") {
                $purc_dsc[] = $get_specific_val;
            }
            else if ($get_specific_val['gl_type'] == "frt") {
                $purc_frt[] = $get_specific_val;
            }
            else if ($get_specific_val['gl_type'] == "ttl") {
                $purc_ttl[] = $get_specific_val;
            }
            // $var = '';
        }
        
        $purc_info_array['PermitNo'] = $get_purc_item['purch_order_id'];
        if (is_array($purc_item)) {
            $purc_info_array['LineNo'] = count($purc_item);
        } else {
            $purc_info_array['LineNo'] = '';
        }
        $purc_info_array['ProductDescription'] = $get_purc_item['product_desc'];
        if (is_array($purc_ttl)) {
            $total_purc = $get_purc_item['total_amount'];
            $purc_info_array['PurchaseValueSGD'] = $currencies->format($get_purc_item['total_amount'], true, $get_purc_item['currencies_code'], $get_purc_item['currencies_value']);
            
        } else {
            $purc_info_array['PurchaseValueSGD'] = '';
        }
        $purc_total_gst = $get_purc_item['sales_tax'];
        $purc_info_array['GSTValueSGD'] = $currencies->format($get_purc_item['sales_tax'], true, $get_purc_item['currencies_code'], $get_purc_item['currencies_value']);
        if (is_array($purc_tax)) {
            $sql = 'select description_short FROM tax_rates WHERE tax_rate_id =' . $purc_item[0]['taxable'];
            $res = mysql_query($sql);
            while ($item = mysql_fetch_assoc($res)) {
                $tax_rate = $item;
            }

            $purc_info_array['TaxCode'] = $tax_rate['description_short'];
        } else {
            $purc_info_array['TaxCode'] = '';
        }
        if ($get_purc_item['currencies_code'] == 'MYR') {
            $purc_info_array['FCYCode'] = '';
        }else{
            $purc_info_array['FCYCode'] = $get_purc_item['currencies_code'];
        }
        if ($get_purc_item['currencies_code'] == 'MYR') {
            $purc_info_array['PurchaseFCY'] = '0';
        } else {
            $purc_info_array['PurchaseFCY'] = $get_purc_item['total_amount'] * $get_purc_item['currencies_value'];
        }
        if ($get_purc_item['currencies_code'] == 'MYR') {
            $purc_info_array['GSTFCY'] = '0';
        } else {
            $purc_info_array['GSTFCY'] = $get_purc_item['sales_tax'] * $get_purc_item['currencies_value'];
        }

        $purc_info_array_all[] = $purc_info_array;

        $purc_total_amount = $purc_total_amount + $total_purc;
        $purc_total_tax = $purc_total_tax + $purc_total_gst;
    }
    $purc_total_amount = $currencies->format($purc_total_amount, true, $get_purc_item['currencies_code'], $get_purc_item['currencies_value']);
    $purc_total_tax = $currencies->format($purc_total_tax, true, $get_purc_item['currencies_code'], $get_purc_item['currencies_value']);
}
//this is for Sales
$sql = "select * from journal_main WHERE journal_id = '12' and post_date BETWEEN '" . $start_date . "' and '" . $end_date . "'";
$res = mysql_query($sql);
while ($item = mysql_fetch_assoc($res)) {
    $SuppInfo[] = $item;
}
if (isset($SuppInfo)) {
    $Supp_total_amount = '';
    $Supp_total_tax = '';
    $supp_total = count($SuppInfo);
    foreach ($SuppInfo as $get_Supp_item) {
        $Supp_info_array['type'] = "S";
        

        $sql = "select * from contacts where id = " . $get_Supp_item['bill_address_id'];
        $res = mysql_query($sql);
        while ($item = mysql_fetch_assoc($res)) {
            $Supp_info_array['SupplierUEN'] = $item['short_name'];
        }
        $Supp_info_array['SupplierName'] = $get_Supp_item['bill_primary_name'];
        $Supp_info_array['InvoiceDate'] = date('d/m/Y',strtotime($get_Supp_item['post_date'])) ;
        $Supp_info_array['InvoiceNo'] = $get_Supp_item['purchase_invoice_id'];
        $sql = "SELECT * FROM journal_item WHERE ref_id = " . $get_Supp_item['id'];
        $res = mysql_query($sql);
        $invoice_item = '';

        while ($item = mysql_fetch_assoc($res)) {
            $invoice_item[] = $item;
        }
        $Supp_item = '';
        $Supp_tax = '';
        $Supp_dsc = '';
        $Supp_frt = '';
        $Supp_ttl = '';
        foreach ($invoice_item as $get_specific_val) {

            if ($get_specific_val['gl_type'] == "sos") {
                $Supp_item[] = $get_specific_val;
            }
            else if ($get_specific_val['gl_type'] == "tax") {
                $Supp_tax[] = $get_specific_val;
            }
            else if ($get_specific_val['gl_type'] == "dsc") {
                $Supp_dsc[] = $get_specific_val;
            }
            else if ($get_specific_val['gl_type'] == "frt") {
                $Supp_frt[] = $get_specific_val;
            }
            else if ($get_specific_val['gl_type'] == "ttl") {
                $Supp_ttl[] = $get_specific_val;
            }
            // $var = '';
        }
        //$Supp_info_array['PermitNo'] = $get_Supp_item['purch_order_id'];
        if (is_array($Supp_item)) {
            $Supp_info_array['LineNo'] = count($Supp_item);
        } else {
            $Supp_info_array['LineNo'] = '';
        }
        $Supp_info_array['ProductDescription'] = $get_Supp_item['product_desc'];
        if (is_array($Supp_ttl)) {
            
            $total_supp = $get_Supp_item['total_amount'];
            
            $Supp_info_array['SupphaseValueSGD'] = $currencies->format($get_Supp_item['total_amount'], true, $get_Supp_item['currencies_code'], $get_Supp_item['currencies_value']);
        } else {
            $Supp_info_array['SupphaseValueSGD'] = '';
        }
        $supp_total_gst = $get_Supp_item['sales_tax'];
        $Supp_info_array['GSTValueSGD'] = $currencies->format($get_Supp_item['sales_tax'], true, $get_Supp_item['currencies_code'], $get_Supp_item['currencies_value']);
        //$Supp_info_array['GSTValueSGD'] = $get_Supp_item['sales_tax'];

        if (is_array($Supp_tax)) {
            $sql = 'select description_short FROM tax_rates WHERE tax_rate_id =' . $Supp_item[0]['taxable'];
            $res = mysql_query($sql);
            while ($item = mysql_fetch_assoc($res)) {
                $tax_rate = $item;
            }
            $Supp_info_array['TaxCode'] = $tax_rate['description_short'];
        } else {
            $Supp_info_array['TaxCode'] = '';
        }
        $Supp_info_array['country'] = $get_Supp_item['bill_address1'];
        if ($get_Supp_item['currencies_code'] == 'MYR') {
            $Supp_info_array['FCYCode'] = '';
        }else{
            $Supp_info_array['FCYCode'] = $get_Supp_item['currencies_code'];
        }


        if ($get_Supp_item['currencies_code'] == 'MYR') {
            $Supp_info_array['SupphaseFCY'] = '0';
        } else {
            $Supp_info_array['SupphaseFCY'] = $get_Supp_item['total_amount'] * $get_Supp_item['currencies_value'];
        }
        if ($get_Supp_item['currencies_code'] == 'MYR') {
            $Supp_info_array['GSTFCY'] = '0';
        } else {
            $Supp_info_array['GSTFCY'] = $get_Supp_item['sales_tax'] * $get_Supp_item['currencies_value'];
        }

        $Supp_info_array_all[] = $Supp_info_array;
        $Supp_total_amount = $Supp_total_amount + $total_supp;
        $Supp_total_tax = $Supp_total_tax + $supp_total_gst;
    }
    $Supp_total_amount = $currencies->format($Supp_total_amount, true, $get_purc_item['currencies_code'], $get_purc_item['currencies_value']);
    $Supp_total_tax = $currencies->format($Supp_total_tax, true, $get_purc_item['currencies_code'], $get_purc_item['currencies_value']);
}



//this is for gl



$sql = "select gl_account  from journal_item WHERE  post_date BETWEEN '" . $start_date . "' and '" . $end_date . "' group BY gl_account ";
$res = mysql_query($sql);
while ($item = mysql_fetch_assoc($res)) {
    $gl_group[] = $item;
}
if (isset($gl_group)) {
    foreach ($gl_group as $gl_group_item) {
        $sql = "select *  from journal_item WHERE  gl_account = " . $gl_group_item['gl_account'] . " and post_date  < '" . $start_date . "'";
        $res = mysql_query($sql);
        $opening_balance = '';
        while ($item = mysql_fetch_assoc($res)) {

            if ($item['debit_amount']) {
                $opening_balance = $opening_balance + $item['debit_amount'];
            }
            if ($item['credit_amount']) {
                $opening_balance = $opening_balance - $item['credit_amount'];
            }
        }
        $gl_group_before_item[$gl_group_item['gl_account']] = $opening_balance;
    }
}

if (isset($gl_group_before_item)) {
    $gl_total_debit = 0;
    $gl_total_credit = 0;
    $gl_total_balance = 0;
    foreach ($gl_group_before_item as $key => $val) {
        $sql = "select description FROM chart_of_accounts WHERE id = " . $key;
        $res = mysql_query($sql);
        while ($item = mysql_fetch_assoc($res)) {
            $gl_name = $item;
        }
        $opening_balance_array['type'] = "L";
        $opening_balance_array['TransactionDate'] = date('d/m/Y', strtotime($start_date)) ;
        $opening_balance_array['AccountID'] = $key;
        $opening_balance_array['AccountName'] = $gl_name['description'];
        $opening_balance_array['TransactionDescription'] = 'Opening Balance';
        $opening_balance_array['Name'] = '';
        $opening_balance_array['TransactionID'] = '';
        $opening_balance_array['SourceDocumentID'] = '';
        $opening_balance_array['SourceType'] = '';
        $opening_balance_array['Debit'] = '0';
        $opening_balance_array['Credit'] = '0';
        $opening_balance_array['Balance'] = $currencies->format($val, true, $get_purc_item['currencies_code'], $get_purc_item['currencies_value']);


        $gl_info_array_all[] = $opening_balance_array;
        $sql = "select journal_item.*, journal_main.currencies_code, journal_main.currencies_value, journal_main.bill_primary_name, journal_main.id as journal_main_id,
journal_main.purchase_invoice_id, journal_main.journal_id from journal_item JOIN journal_main
on journal_item.ref_id = journal_main.id WHERE journal_item.post_date BETWEEN '" . $start_date . "' and '" . $end_date . "' and journal_item.gl_account = " . $key;
        $res = mysql_query($sql);
        $glInfo = '';
        while ($item = mysql_fetch_assoc($res)) {
            $glInfo[] = $item;
        }


        $blance = $val;
        $gl_total_balance = $gl_total_balance+$val;
        foreach ($glInfo as $glInfo_item) {
            $gl_info_array['type'] = "L";
            $gl_info_array['TransactionDate'] = date('d/m/Y',strtotime($glInfo_item['post_date'])) ;
            $gl_info_array['AccountID'] = $glInfo_item['gl_account'];

            $gl_info_array['AccountName'] = $gl_name['description'];
            $gl_info_array['TransactionDescription'] = $glInfo_item['product_desc'];
            $gl_info_array['Name'] = $glInfo_item['bill_primary_name'];
            $gl_info_array['TransactionID'] = $glInfo_item['journal_main_id'];
            $gl_info_array['SourceDocumentID'] = $glInfo_item['purchase_invoice_id'];
            if ($glInfo_item['journal_id'] == 12) {
                $gl_info_array['SourceType'] = 'AR';
            } else if ($glInfo_item['journal_id'] == 13) {
                $gl_info_array['SourceType'] = '';
            } else if ($glInfo_item['journal_id'] == 18) {
                $gl_info_array['SourceType'] = 'AR';
            } else if ($glInfo_item['journal_id'] == 6) {
                $gl_info_array['SourceType'] = 'AP';
            } else if ($glInfo_item['journal_id'] == 7) {
                $gl_info_array['SourceType'] = '';
            } else if ($glInfo_item['journal_id'] == 20) {
                $gl_info_array['SourceType'] = 'AP';
            }else{
                $gl_info_array['SourceType'] = '';
            }



            $gl_info_array['Debit'] = $currencies->format($glInfo_item['debit_amount'], true, $glInfo_item['currencies_code'], $glInfo_item['currencies_value']);
            $gl_info_array['Credit'] = $currencies->format($glInfo_item['credit_amount'], true, $glInfo_item['currencies_code'], $glInfo_item['currencies_value']);
            if ($glInfo_item['debit_amount']) {
                $blance = $blance + number_format(floatval($glInfo_item['debit_amount']), 2, '.', '');
            }
            if ($glInfo_item['credit_amount']) {
                $blance = $blance - number_format(floatval($glInfo_item['credit_amount']), 2, '.', '');
            }
            $gl_info_array['Balance'] = $currencies->format($blance, true, $glInfo_item['currencies_code'], $glInfo_item['currencies_value']);
            $gl_info_array_all[] = $gl_info_array;
            $gl_total_debit = $gl_total_debit + $glInfo_item['debit_amount'];
            $gl_total_credit = $gl_total_credit + $glInfo_item['credit_amount'];
            $gl_total_balance = $gl_total_balance+$blance;
        }
    }
}
//$count_gl = count($gl_info_array_all) - count($gl_group_before_item);
$count_gl = count($gl_info_array_all);
$sql = "select configuration_value from configuration where configuration_key = 'SHIPPING_DEFAULT_CURRENCY'";
$res = mysql_query($sql);
while ($item = mysql_fetch_assoc($res)) {
    $default_currency = $item;
}
//$heading_csv = array(
//    array('header_start' => 'CompInfoStart',
//        'header' => array('Type', 'CompanyID','CompanyName', 'GSTNo', 'PeriodStart', 'PeriodEnd', 'GAFCreationDate', 'ProductVersion', 'GAFVersion'),
//        'com_val' => $company_array,
//        'header_end' => 'CompInfoEnd'
//    ),
//    array('header_start' => 'PurcDataStart',
//        'header' => array('Type', 'SupplierID', 'SupplierName', 'InvoiceDate', 'InvoiceNo', 'ImportK1No', 'LineNo', 'ProductDescription', 'PurchaseValueRM', 'GSTValueRM', 'TaxCode', 'FCYCode', 'PurchaseFCY', 'GSTFCY'),
//        'purc_val' => $purc_info_array_all,
//        'header_end' => array('PurcDataEnd','','','','','','',$purc_total_amount, $purc_total_tax, $purc_total)
//    ),
//    array('header_start' => 'SuppDataStart',
//        'header' => array('Type','CustomerName', 'CustomerID', 'InvoiceDate', 'InvoiceNo', 'LineNo', 'ProductDescription', 'SupplyValueRM', 'GSTValueRM', 'TaxCode',  'FCYCode', 'SupplyFCY', 'GSTFCY'),
//        'supp_val' => $Supp_info_array_all,
//        'header_end' => array('SuppDataEnd','','','','','',$Supp_total_amount, $Supp_total_tax, $supp_total)
//    ),
//    array('header_start' => 'GLDataStart',
//        'header' => array('Type','TransactionDate', 'AccountID', 'AccountName', 'TransactionDescription', 'Name', 'TransactionID', 'SourceDocumentID', 'SourceType', 'Debit', 'Credit', 'Balance'),
//        'gl_val' => $gl_info_array_all,
//        'header_end' => array('GLDataEnd','','','','','','','', $currencies->format($gl_total_debit, true, $glInfo_item['currencies_code'], $glInfo_item['currencies_value']), $currencies->format($gl_total_credit, true, $glInfo_item['currencies_code'], $glInfo_item['currencies_value']), $count_gl)
//    )
//);


$heading_txt = array(
    array(
        'com_val' => $company_array
    ),
    array(
        'purc_val' => $purc_info_array_all
    ),
    array(
        'supp_val' => $Supp_info_array_all
    ),
    array(
        'gl_val' => $gl_info_array_all
    ),
    array(
        'total_val' => array('type'=>"F",$purc_total,$purc_total_amount,$purc_total_tax,$supp_total,$Supp_total_amount,$Supp_total_tax,$count_gl,$currencies->format($gl_total_debit, true, $glInfo_item['currencies_code'], $glInfo_item['currencies_value']),$currencies->format($gl_total_credit, true, $glInfo_item['currencies_code'], $glInfo_item['currencies_value']),$currencies->format($gl_total_balance, true, $glInfo_item['currencies_code'], $glInfo_item['currencies_value']))
    )
);

if (isset($_POST['doc_type']) && $_POST['doc_type'] == 'csv_file') {
    $file_name = 'gaf_' . date('d-m-Y') . '.csv';
    $fp = fopen('gaf/' . $file_name, 'w');

    foreach ($heading_txt as $fields) {
        if (is_array($fields)) {
            foreach ($fields as $subfield) {
                $heading_print = array();
                if (!is_array($subfield)) {
                    if(!empty($subfield))
                    fputcsv($fp, array($subfield), '\n');
                } else {
                    foreach ($subfield as $subfield_headig) {
                        if (is_array($subfield_headig)) {
                            fputcsv($fp, $subfield_headig);
                        } else {
                            $heading_print[] = $subfield_headig;
                        }
                    }
                    if(!empty($heading_print))
                    fputcsv($fp, $heading_print);
                }
            }
        }
    }
    fclose($fp);
    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=" . $file_name);
    readfile('gaf/' . $file_name);
    exit;
} else if (isset($_POST['doc_type']) && $_POST['doc_type'] == 'text_file') {
    $file_name = 'gaf_' . date('d-m-Y') . '.txt';
    $fp = fopen('gaf/' . $file_name, 'w');
    $write_content = "";
    foreach ($heading_txt as $sub_heading) {
        if (is_array($sub_heading)) {
            foreach ($sub_heading as $sub_heading_item) {
                if (!is_array($sub_heading_item)) {
                    fwrite($fp, $sub_heading_item . "|" . "\r\n");
                } else {
                    $sub_heading_text = "";
                    $x = 0;
                    foreach ($sub_heading_item as $sub_heading_item_text) {
                        if (!is_array($sub_heading_item_text)) {
                            $x = 1;
                            $sub_heading_text .= $sub_heading_item_text . "|";
                        } else {
                            $x = 0;
                            $sub_text = '';
                            foreach ($sub_heading_item_text as $sub_item_text) {
                                if ($sub_item_text == "") {
                                    $sub_item_text = "";
                                }
                                $sub_text .= $sub_item_text . "|";
                            }
                            fwrite($fp, $sub_text . "\r\n");
                        }
                    }
                    if($x==1){
                        fwrite($fp, $sub_heading_text . "\r\n");
                    }
                    
                }
            }
        }
    }
    fclose($fp);

    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=" . $file_name);
    readfile('gaf/' . $file_name);
    exit;
}
$include_tabs = true;
$include_calendar = false;
$include_template = 'template_main.php';
define('PAGE_TITLE', CP_ADD_REMOVE_BOXES);
?>