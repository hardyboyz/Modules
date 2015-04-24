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

require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');


/* * *************   Act on the action request   ************************ */


/* * ***************   prepare to display templates  ************************ */

$time_start = strtotime(str_replace("/", "-", $_POST['start']));
$time_end = strtotime(str_replace("/", "-", $_POST['end']));
$start = date('Y-m-d', $time_start);
$end = date('Y-m-d', $time_end);


$data = array();

$sql = 'select "Field 5 a" AS `name_of_field`,"Total Value of Standard Rated Supply (excluding GST)" AS `description`,
 sum(journal_item.credit_amount-journal_item.debit_amount) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short in("DS (6%)","SR (6%)") && journal_item.gl_type ="sos" 
			&& (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")';

$res = mysql_query($sql);
$data['field_5_a'] = mysql_fetch_row($res);

$sql = 'select "Field 5 b" AS `name_of_field`,"Total Output Tax (Inclusive of bad debt recovered and other adjustments)" AS `description`,
        sum(((journal_item.credit_amount-journal_item.debit_amount)*tax_authorities.tax_rate)/100) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short in( "SR (6%)","DS (6%)","AS (6%)") && journal_item.gl_type in ("sos") 
&& (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
';
$res = mysql_query($sql);
$data['field_5_b'] = mysql_fetch_row($res);

$sql = 'select "Field 6 a" AS `name_of_field`,"Total Value of Standard Rated Acquisition" AS `description`,
    sum(journal_item.debit_amount-journal_item.credit_amount) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short in( "TX (6%)","IM (6%)","TXE43 (6%)","TX-RE (6%)") && journal_item.gl_type in ("por")
        && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
    ';

$res = mysql_query($sql);
$data['field_6_a'] = mysql_fetch_row($res);

$sql = 'select  "Field 6 b" AS `name_of_field`,"Total Input Tax (Inclusive of bad debt recovered and other adjustments)"
AS `description`,
        sum(((journal_item.debit_amount-journal_item.credit_amount)*tax_authorities.tax_rate)/100) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short in("TX (6%)","IM (6%)","TXE43 (6%)","TX-RE (6%)","AP (6%)" )&& journal_item.gl_type in ("por")
         && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
        ';
$res = mysql_query($sql);
$data['field_6_b'] = mysql_fetch_row($res);

$field_7_val = $data['field_5_b'][2] - $data['field_6_b'][2];
$field_8_val = $data['field_6_b'][2] - $data['field_5_b'][2];
if ($field_7_val < 0) {
    $field_7_val = "";
}

if ($field_8_val < 0) {
    $field_8_val = "";
}


$data['field_7'] = array('Field 7', 'GST Amount Payable (Item 5b-6b)', $field_7_val);
$data['field_8'] = array('Field 8', 'GST Amount Claimable (Item 6b-5b)', $field_8_val);

$sql = 'select "Field 10" AS `name_of_field`,"Total Value of zero rated supplies" AS `description`,
 sum(journal_item.credit_amount-journal_item.debit_amount) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short = "ZR (0%)" && journal_item.gl_type in ("sos")
        && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
    ';

$res = mysql_query($sql);
$data['field_10'] = mysql_fetch_row($res);


$sql = 'select "Field 11" AS `name_of_field`,"Total Value of export Supplies" AS `description`,
 sum(journal_item.credit_amount-journal_item.debit_amount) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short = "ZR-export (0%)" && journal_item.gl_type in ("sos")
        && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
        ';

$res = mysql_query($sql);
$data['field_11'] = mysql_fetch_row($res);

$sql = 'select "Field 12" AS `name_of_field`,"Total Value of exempt supplies" AS `description`,
 sum(journal_item.credit_amount-journal_item.debit_amount) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short in( "ES43 (0%)","ESN43 (0%)","ES (0%)") && journal_item.gl_type in ("sos")
        && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
        ';

$res = mysql_query($sql);
$data['field_12'] = mysql_fetch_row($res);

$sql = 'select "Field 13" AS `name_of_field`,"Total Value of supplies granted GST relief" AS `description`,
 sum(journal_item.credit_amount-journal_item.debit_amount) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short in( "RS (0%)") && journal_item.gl_type in ("sos")
        && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
        ';

$res = mysql_query($sql);
$data['field_13'] = mysql_fetch_row($res);

$sql = 'select "Field 14" AS `name_of_field`,"Total Value of goods imported under ATS" AS `description`,
sum(journal_item.debit_amount-journal_item.credit_amount) as value
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short = "IS (0%)" && journal_item.gl_type in ("por")
         && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
        ';

$res = mysql_query($sql);
$data['field_14'] = mysql_fetch_row($res);


$sql = '
select "Field 15" AS `name_of_field`,"Total Value of GST suspended under item 14" AS `description`,
       sum(((journal_item.debit_amount-journal_item.credit_amount)*6)/100) as gst
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short = "IS (0%)" && journal_item.gl_type in ("por") 
        && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
';

$res = mysql_query($sql);
$data['field_15'] = mysql_fetch_row($res);

$sql = "SELECT 'Field 16' AS `name_of_field`,'Total Value of capital goods acquired' AS `description`,
ifnull(sum(journal_item.debit_amount + journal_item.credit_amount),0) as `value` from journal_item join chart_of_accounts
on journal_item.gl_account = chart_of_accounts.id WHERE journal_item.gl_type = 'por' and chart_of_accounts.account_type = 8
AND journal_item.post_date >= '" . $start . "' and journal_item.post_date < '" . $end . "'";

$res = mysql_query($sql);
$data['field_16'] = mysql_fetch_row($res);

$sql = 'select "Field 17" AS `name_of_field`,"Bad Debt Relief" AS `description`,
sum(journal_item.debit_amount-journal_item.credit_amount) as value
from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short = "AP (6%)" && journal_item.gl_type in ("por")
        && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
        ';

$res = mysql_query($sql);
$data['field_17'] = mysql_fetch_row($res);

$sql = 'select "Field 18" AS `name_of_field`,"Bad Debt Recovered" AS `description`, 
sum(journal_item.credit_amount - journal_item.debit_amount) as value
from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short = "AS (6%)" && journal_item.gl_type in ("sos")
        && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
        ';

$res = mysql_query($sql);
$data['field_18'] = mysql_fetch_row($res);

if ($_POST['report_type'] == 'gst_03_report') {

    if (isset($_POST['doc_type']) && $_POST['doc_type'] == 'pdf') {

        $css = '
<style type="text/css">
    td{font-family:freeserif;}
</style>';


        require_once(DIR_FS_INCLUDES . 'mpdf/mpdf.php');
        $mpdf = new mPDF();
        $pdf = '
<h1 style="font-family:freeserif; ">GST Tax Return Reports</h1>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid;" >
<tr>
    <td width="30%" style="background-color: #cccccc; font-family:freeserif; font-size:16pt text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-left: none">Name of field in GST return</td>
    <td width="50%" style="background-color: #cccccc; font-family:freeserif; font-size:16pt text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
    <td width="20%" style="background-color: #cccccc;; font-family:freeserif; font-size:16pt text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Total</td>
</tr>';
//$currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value)
        foreach ($data as $item) {
            $item[2] = $currencies->format($item[2]);
            $pdf .='
        <tr>';
            foreach ($item as $value) {
                $pdf .='<td style="border-bottom:1px solid; border-right:1px solid; font-family:freeserif; font-size:16pt">' . $value . '</td>';
            }
            $pdf .='</tr>
    ';
        }


        $pdf .='
</table>';


        $mpdf->WriteHTML($css, 1);
        $mpdf->WriteHTML($pdf);

        ob_clean();
        $mpdf->Output();
        exit;
    } else {
        $heading = array('Name of field in GST return', 'Description', 'Total');
        $file_name = 'gst_03_report_' . date('d-m-Y') . '.csv';
        $fp = fopen('gaf/' . $file_name, 'w');

        fputcsv($fp, array('GST Tax Return Reports'));
        fputcsv($fp, $heading);
        foreach ($data as $item) {
            $item[2] = $currencies->format($item[2]);
            fputcsv($fp, $item);
        }
        fclose($fp);
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=" . $file_name);
        readfile('gaf/' . $file_name);
        exit;
    }
} else if ($_POST['report_type'] == 'gst_03_with_transactions_report') {

    //this is for Purchase 
    $sql = "select journal_main.id,journal_main.post_date,journal_main.purchase_invoice_id, journal_main.total_amount,journal_main.sales_tax ,
            CASE WHEN journal_main.sub_journal_id=3 THEN journal_main.bill_primary_name ELSE journal_main.product_desc END as product_desc
            from journal_main JOIN journal_item on journal_main.id = journal_item.ref_id
            WHERE journal_item.gl_type = 'por' and journal_item.post_date BETWEEN '" . $start . "' and '" . $end . "' GROUP BY journal_item.ref_id order by journal_main.post_date";
        //    $sql = "select * from journal_main WHERE journal_id = '6' and post_date BETWEEN '" . $start . "' and '" . $end . "' order by purchase_invoice_id";
    $res = mysql_query($sql);
    while ($item = mysql_fetch_assoc($res)) {
        $PurcInfo[] = $item;
    }
    if (isset($PurcInfo)) {// get Purchase 
        foreach ($PurcInfo as $get_purc_item) {
            $output_pur = array();
            $invoice_item = array();
            $output_pur['date'] = $get_purc_item['post_date'];
            $output_pur['inv_no'] = $get_purc_item['purchase_invoice_id'];
            $output_pur['description'] = $get_purc_item['product_desc'];
            $output_pur['amount'] = $get_purc_item['total_amount'] - $get_purc_item['sales_tax'];

            $sql = "SELECT * FROM journal_item WHERE ref_id = " . $get_purc_item['id'];
            $res = mysql_query($sql);
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
                } else if ($get_specific_val['gl_type'] == "tax") {
                    $purc_tax[] = $get_specific_val;
                } else if ($get_specific_val['gl_type'] == "dsc") {
                    $purc_dsc[] = $get_specific_val;
                } else if ($get_specific_val['gl_type'] == "frt") {
                    $purc_frt[] = $get_specific_val;
                } else if ($get_specific_val['gl_type'] == "ttl") {
                    $purc_ttl[] = $get_specific_val;
                }
            }

            //if (is_array($purc_tax)) {
            $tax_rate = array();
            $tax_auth_array = gen_build_tax_auth_array();
            $sql = 'select rate_accounts,description_short FROM tax_rates WHERE tax_rate_id =' . $purc_item[0]['taxable'];
            $res = mysql_query($sql);
            while ($item = mysql_fetch_assoc($res)) {
                $tax_rate = $item;
            }
            $tax_rate_per = gen_calculate_tax_rate($tax_rate['rate_accounts'], $tax_auth_array);
            $output_pur['rate'] = $tax_rate_per . '%';
            $output_pur['gst'] = $get_purc_item['sales_tax'];
            $output_pur['code'] = $tax_rate['description_short'];
//            } else {
//                $output_pur['rate'] = '';
//                $output_pur['gst'] = '';
//                $output_pur['code'] = '';
//            }
            $output_pur_all[] = $output_pur;
        }
    }



    //this is for sales  
    $sql = "select journal_main.id,journal_main.post_date,journal_main.purchase_invoice_id, journal_main.total_amount,journal_main.sales_tax ,
            CASE WHEN journal_main.sub_journal_id=2 THEN journal_main.bill_primary_name ELSE journal_main.product_desc END as product_desc
            from journal_main JOIN journal_item on journal_main.id = journal_item.ref_id
            WHERE journal_item.gl_type = 'sos' and journal_item.post_date BETWEEN '" . $start . "' and '" . $end . "' GROUP BY journal_item.ref_id order by journal_main.post_date";
//    $sql = "select * from journal_main WHERE journal_id = '12' and post_date BETWEEN '" . $start . "' and '" . $end . "' order by purchase_invoice_id";
    $res = mysql_query($sql);
    while ($item = mysql_fetch_assoc($res)) {
        $SuppInfo[] = $item;
    }
    if (isset($SuppInfo)) {// get Purchase 
        foreach ($SuppInfo as $get_supp_item) {
            $output_supp = array();
            $invoice_item = array();
            $output_supp['date'] = $get_supp_item['post_date'];
            $output_supp['inv_no'] = $get_supp_item['purchase_invoice_id'];
            $output_supp['description'] = $get_supp_item['product_desc'];
            $output_supp['amount'] = $get_supp_item['total_amount'] - $get_supp_item['sales_tax'];

            $sql = "SELECT * FROM journal_item WHERE ref_id = " . $get_supp_item['id'];
            $res = mysql_query($sql);
            while ($item = mysql_fetch_assoc($res)) {
                $invoice_item[] = $item;
            }
            $supp_item = '';
            $supp_tax = '';
            $supp_dsc = '';
            $supp_frt = '';
            $supp_ttl = '';
            foreach ($invoice_item as $get_specific_val) {

                if ($get_specific_val['gl_type'] == "sos") {
                    $supp_item[] = $get_specific_val;
                } else if ($get_specific_val['gl_type'] == "tax") {
                    $supp_tax[] = $get_specific_val;
                } else if ($get_specific_val['gl_type'] == "dsc") {
                    $supp_dsc[] = $get_specific_val;
                } else if ($get_specific_val['gl_type'] == "frt") {
                    $supp_frt[] = $get_specific_val;
                } else if ($get_specific_val['gl_type'] == "ttl") {
                    $supp_ttl[] = $get_specific_val;
                }
            }

            //if (is_array($supp_tax)) {
            $tax_rate = array();
            $tax_auth_array = gen_build_tax_auth_array();
            $sql = 'select rate_accounts,description_short FROM tax_rates WHERE tax_rate_id =' . $supp_item[0]['taxable'];
            $res = mysql_query($sql);
            while ($item = mysql_fetch_assoc($res)) {
                $tax_rate = $item;
            }
            $tax_rate_per = gen_calculate_tax_rate($tax_rate['rate_accounts'], $tax_auth_array);
            $output_supp['rate'] = $tax_rate_per . '%';
            $output_supp['gst'] = $get_supp_item['sales_tax'];
            $output_supp['code'] = $tax_rate['description_short'];


//            } else {
//                $output_supp['rate'] = '';
//                $output_supp['gst'] = '';
//                $output_supp['code'] = '';
//                
//            }
            $output_supp_all[] = $output_supp;
        }
    }

    //Purchase group total
    $sql = 'SELECT tax_rates.description_short, tax_authorities.tax_rate from tax_rates
JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id   WHERE tax_rates.type = "v" ';
    $res = mysql_query($sql);
    $purchase_group = '';
    while ($pur_tax_title = mysql_fetch_assoc($res)) {
        $pur_array = array();
        $pur_array['description_short'] = $pur_tax_title['description_short'];
        $sql = 'select tax_rates.description_short, sum(journal_item.debit_amount-journal_item.credit_amount) as total_amount,
        tax_authorities.tax_rate,sum(((journal_item.debit_amount-journal_item.credit_amount)*tax_authorities.tax_rate)/100) as gst
        from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
        JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
        WHERE tax_rates.description_short = "' . $pur_tax_title['description_short'] . '" && journal_item.gl_type in ("por")
           && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '") 
        ';
        $pur_tax_res = mysql_query($sql);
        $pur_tax_row = mysql_fetch_assoc($pur_tax_res);


        $pur_array['total_amount'] = $pur_tax_row['total_amount'];
        $pur_array['tax_rate'] = $pur_tax_title['tax_rate'];
        $pur_array['gst'] = $pur_tax_row['gst'];
        $purchase_group[] = $pur_array;
    }



    //Sales group total

    $sql = 'SELECT tax_rates.description_short, tax_authorities.tax_rate from tax_rates
JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id   WHERE tax_rates.type = "c" ';
    $res = mysql_query($sql);
    $sales_group = '';

    while ($tax_title = mysql_fetch_assoc($res)) {
        $sup_array = array();

        if ($tax_title['description_short'] == "ZR-export (0%)") {
            continue;
        }
        if ($tax_title['description_short'] == "ZR (0%)") {
            $sql = 'select tax_rates.description_short, sum(journal_item.credit_amount-journal_item.debit_amount) as total_amount,
            tax_authorities.tax_rate,sum(((journal_item.credit_amount-journal_item.debit_amount)*tax_authorities.tax_rate)/100) as gst
            from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
            JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
            WHERE tax_rates.description_short in( "ZR (0%)" , "ZR-export (0%)") && journal_item.gl_type in ("sos")
                && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
            ';
        } else {
            $sql = 'select tax_rates.description_short, sum(journal_item.credit_amount-journal_item.debit_amount) as total_amount,
            tax_authorities.tax_rate,sum(((journal_item.credit_amount-journal_item.debit_amount)*tax_authorities.tax_rate)/100) as gst
            from journal_item JOIN tax_rates on journal_item.taxable = tax_rates.tax_rate_id
            JOIN tax_authorities on tax_rates.rate_accounts = tax_authorities.tax_auth_id
            WHERE tax_rates.description_short = "' . $tax_title['description_short'] . '" && journal_item.gl_type in ("sos")
                && (journal_item.post_date >= "' . $start . '"  and journal_item.post_date <= "' . $end . '")
            ';
        }
        $tax_res = mysql_query($sql);
        $tax_row = mysql_fetch_assoc($tax_res);
        $sup_array['description_short'] = $tax_title['description_short'];
        $sup_array['total_amount'] = $tax_row['total_amount'];
        $sup_array['tax_rate'] = $tax_title['tax_rate'];
        $sup_array['gst'] = $tax_row['gst'];
        $sales_group[] = $sup_array;
    }


    if (isset($_POST['doc_type']) && $_POST['doc_type'] == 'pdf') {
        $css = '
<style type="text/css">
    td{font-family:freeserif;}
    .th_heading{background-color: #cccccc; font-family:freeserif; font-size:12px text-align: center; height: 17px; padding-top: 3px;}
    .td_stryle{}
</style>';


        require_once(DIR_FS_INCLUDES . 'mpdf/mpdf.php');
        $mpdf = new mPDF();
        //Purchase report pdf start
        $pdf = '
            <div style="page-break-after:always">
<h1 style="font-family:freeserif; ">Purchase</h1>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid;" >
<tr>
    <th width="12%" class="th_heading" style=" border: 1px solid; border-left: none">Date</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Purchase no</th>
    <th width="30%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Description</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Amount</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Rate</th>
    <th width="12" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">GST</th>
    <th width="10%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Code</th>
</tr>';
//$currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value)
        foreach ($output_pur_all as $purchase_output) {
            $pdf .='<tr>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . date('m/d/Y', strtotime($purchase_output['date'])) . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;" >' . $purchase_output['inv_no'] . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $purchase_output['description'] . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $currencies->format($purchase_output['amount']) . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $purchase_output['rate'] . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $currencies->format($purchase_output['gst']) . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $purchase_output['code'] . '</td>';
            $pdf .='</tr>';
        }


        $pdf .='
</table> </div>';
//Purchase report pdf end
//Purchase GST Group report pdf start         
        $pdf .= '
            <div style="page-break-after:always">
<h1 style="font-family:freeserif; ">Purchase GST Group</h1>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid;" >
<tr>
    <th width="12%" class="th_heading" style=" border: 1px solid; border-left: none">Code</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Amount</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Rate</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</th>
</tr>';
        foreach ($purchase_group as $purchase_group_gst) {
            $pdf .= '<tr>';
            $pdf .= '<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $purchase_group_gst['description_short'] . '</td>';
            $pdf .= '<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $currencies->format($purchase_group_gst['total_amount']) . '</td>';
            $pdf .= '<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $purchase_group_gst['tax_rate'] . '%</td>';
            $pdf .= '<td style="border-right: 1px solid; border-bottom: 1px solid;">' . (($purchase_group_gst['gst'] != 0) ? $currencies->format($purchase_group_gst['gst']) : '-') . '</td>';
            $pdf .='</tr>';
        }


        $pdf .='
</table></div>';
//Purchase GST Group report pdf end
        //Sales report pdf start
        $pdf .= '
            <div style="page-break-after:always">
<h1 style="font-family:freeserif; ">Sales</h1>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid;" >
<tr>
    <th width="12%" class="th_heading" style=" border: 1px solid; border-left: none">Date</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Inv no</th>
    <th width="30%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Description</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Amount</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Rate</th>
    <th width="12" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">GST</th>
    <th width="10%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Code</th>
</tr>';
//$currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value)
        foreach ($output_supp_all as $sales_output) {
            $pdf .='<tr>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . date('m/d/Y', strtotime($sales_output['date'])) . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;" >' . $sales_output['inv_no'] . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $sales_output['description'] . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $currencies->format($sales_output['amount']) . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $sales_output['rate'] . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $currencies->format($sales_output['gst']) . '</td>';
            $pdf .='<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $sales_output['code'] . '</td>';
            $pdf .='</tr>';
        }


        $pdf .='
</table></div>';
        //Sales report pdf end
//Sales GST Group report pdf start           
        $pdf .= '
            <div style="page-break-after:always">
<h1 style="font-family:freeserif; "> Sales GST Group</h1>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid;" >
<tr>
    <th width="12%" class="th_heading" style=" border: 1px solid; border-left: none">Code</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Amount</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Rate</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</th>
</tr>';
        foreach ($sales_group as $sales_group_gst) {
            $pdf .= '<tr>';
            $pdf .= '<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $sales_group_gst['description_short'] . '</td>';
            $pdf .= '<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $currencies->format($sales_group_gst['total_amount']) . '</td>';
            $pdf .= '<td style="border-right: 1px solid; border-bottom: 1px solid;">' . $sales_group_gst['tax_rate'] . '%</td>';
            $pdf .= '<td style="border-right: 1px solid; border-bottom: 1px solid;">' . (($sales_group_gst['gst'] != 0) ? $currencies->format($sales_group_gst['gst']) : '-') . '</td>';
            $pdf .='</tr>';
        }


        $pdf .='
</table></div>';
//Sales GST Group report pdf end
        //GST03 report pdf start        
        $pdf .= '
            <div style="page-break-after:always">
<h1 style="font-family:freeserif; ">GST03</h1>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid;" >
<tr>
    <th width="12%" class="th_heading" style=" border: 1px solid; border-left: none">Item</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</th>
    <th width="12%" class="th_heading" style=" border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Amount</th>
</tr>';

        foreach ($data as $item) {
            $item[2] = $currencies->format($item[2]);
            $pdf .='
        <tr>';
            foreach ($item as $value) {
                $pdf .='<td style="border-bottom:1px solid; border-right:1px solid; font-family:freeserif; font-size:12px">' . $value . '</td>';
            }
            $pdf .='</tr>';
        }


        $pdf .='
</table></div>';
//GST03 report pdf end  




        $mpdf->WriteHTML($css, 1);
        $mpdf->WriteHTML($pdf);

        ob_clean();
        $mpdf->Output();
        exit;
    } else if (isset($_POST['doc_type']) && $_POST['doc_type'] == 'csv') {
        //create data array for csv
        foreach ($purchase_group as $purchase_group_item) {
            if ($purchase_group_item['gst'] == 0) {
                $purchase_group_item['gst'] = '   - ';
            }
            $purchase_groups[] = $purchase_group_item;
        }

        foreach ($sales_group as $sales_group_item) {
            if ($sales_group_item['gst'] == 0) {
                $sales_group_item['gst'] = '   - ';
            }
            $sales_groups[] = $sales_group_item;
        }
        $csv_data[] = array(
            'report_title' => 'Purchase',
            'report_header' => array('Date', 'Inv No', 'Description', 'Amount', 'Rate', 'GST', 'Code'),
            'report_data' => $output_pur_all
        );
        $csv_data[] = array(
            'report_title' => 'Purchase GST Group',
            'report_header' => array('Code', 'Amount', 'Rate', 'GST'),
            'report_data' => $purchase_groups
        );
        $csv_data[] = array(
            'report_title' => 'Sales',
            'report_header' => array('Date', 'Inv No', 'Description', 'Amount', 'Rate', 'GST', 'Code'),
            'report_data' => $output_supp_all
        );
        $csv_data[] = array(
            'report_title' => 'Sales GST Group',
            'report_header' => array('Code', 'Amount', 'Rate', 'GST'),
            'report_data' => $sales_groups
        );
        $csv_data[] = array(
            'report_title' => 'GST 03 REPORT',
            'report_header' => array('Code', 'Description', 'GST'),
            'report_data' => $data
        );
        $file_name = 'gst_03_report_' . date('d-m-Y') . '.csv';
        $fp = fopen('gaf/' . $file_name, 'w');

        //write csv file
        function is_multi_2d($array) {//thsi is for check 2 dimensional array or not
            foreach ($array as $v) {
                if (is_array($v))
                    return true;
            }
            return false;
        }

        foreach ($csv_data as $csv_items) {
            foreach ($csv_items as $csv_item) {
                if (!is_array($csv_item)) {
                    fputcsv($fp, array($csv_item), '\n'); // this is for report title 
                } else {
                    if (!is_multi_2d($csv_item)) {// check 2 dimensional array or not
                        fputcsv($fp, $csv_item); //this is report heading 
                    } else {
                        foreach ($csv_item as $single_item) {
                            fputcsv($fp, $single_item); // this report data
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
}




$include_tabs = true;
$include_calendar = false;
$include_template = 'template_main.php';
define('PAGE_TITLE', CP_ADD_REMOVE_BOXES);
?>