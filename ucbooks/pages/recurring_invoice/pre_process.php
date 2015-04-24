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
//$security_level = validate_user($security_token);
/* * ************  include page specific files    ******************** */
gen_pull_language('contacts');

require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
require_once(DIR_FS_WORKING . 'functions/ucbooks.php');
require_once(DIR_FS_WORKING . 'classes/gen_ledger.php');
require_once(DIR_FS_WORKING . 'classes/orders.php');
if (defined('MODULE_SHIPPING_STATUS')) {
    require_once(DIR_FS_MODULES . 'shipping/functions/shipping.php');
    require_once(DIR_FS_MODULES . 'shipping/defaults.php');
}
/* * ************   page specific initialization  ************************ */

$error = false;

$orders = new orders();

// this is for insert value in journal_main for recurring invoice coding by 4axiz(apu) date:16-july-2013 
// 
// Create connection
//if (!mysql_select_db(APP_DB)) {
//    return false;
//}

$query = "SELECT * FROM invoice_recurring";
$res = mysql_query($query);

while ($recurring_info_value = mysql_fetch_assoc($res)) {
    $recurring_info[] = $recurring_info_value;
}


foreach ($recurring_info as $recurring) {
    //if (!empty($recurring['last_exec_date'])) {
    if ($recurring['last_exec_date'] > 0) {

        $recurring_day = date("Y-m-d", strtotime($recurring['last_exec_date']) + $recurring['recurring_interval'] * (60 * 60 * 24));
    } else {
        $recurring_day = date("Y-m-d", strtotime($recurring['create_date']) + $recurring['recurring_interval'] * (60 * 60 * 24));
    }



    $terminal_date = "";
    if (date("Y-m-d") == $recurring_day) {
        $invoice_info = unserialize($recurring['inv_info']);
        //$invoice_item_info = unserialize($recurring['inv_detail_info']);
//        $recur_order->journal_rows = $invoice_info->journal_rows;
//        $recur_order->journal_main_array = $invoice_info->journal_main_array;

        $terminal_date = strtotime($invoice_info->terminal_date) - strtotime($invoice_info->post_date);
        $terminal_date = $terminal_date / (60 * 60 * 24);
        $terminal_date = date("Y-m-d", strtotime(date('Y-m-d')) + $terminal_date * (60 * 60 * 24));

        $orders->closed = $invoice_info->closed;
        $orders->journal_id = $invoice_info->journal_id;
        $orders->gl_type = $invoice_info->gl_type;
        $orders->gl_acct_id = $invoice_info->gl_acct_id;
        $orders->description = $invoice_info->description;
        $orders->currencies_code = $invoice_info->currencies_code;
        $orders->currencies_value = $invoice_info->currencies_value;
        $orders->account_type = $invoice_info->account_type;
        $orders->bill_acct_id = $invoice_info->bill_acct_id;
        $orders->bill_primary_name = $invoice_info->bill_primary_name;
        $orders->bill_contact = $invoice_info->bill_contact;
        $orders->bill_address1 = $invoice_info->bill_address1;
        $orders->bill_address2 = $invoice_info->bill_address2;
        $orders->bill_city_town = $invoice_info->bill_city_town;
        $orders->bill_state_province = $invoice_info->bill_state_province;
        $orders->bill_postal_code = $invoice_info->bill_postal_code;
        $orders->bill_address_id = $invoice_info->bill_address_id;
        $orders->bill_telephone1 = $invoice_info->bill_telephone1;
        $orders->bill_email = $invoice_info->bill_email;
        $orders->bill_country_code = $invoice_info->bill_country_code;
        $orders->ship_add_update = $invoice_info->ship_add_update;
        $orders->ship_acct_id = $invoice_info->ship_acct_id;
        $orders->ship_address_id = $invoice_info->ship_address_id;
        $orders->ship_country_code = $invoice_info->ship_country_code;
        $orders->shipper_code = $invoice_info->shipper_code;
        $orders->drop_ship = $invoice_info->drop_ship;
        $orders->freight = $invoice_info->freight;
        $orders->disc_gl_acct_id = $invoice_info->disc_gl_acct_id;
        $orders->ship_gl_acct_id = $invoice_info->ship_gl_acct_id;
        $orders->ship_primary_name = $invoice_info->ship_primary_name;
        $orders->ship_contact = $invoice_info->ship_contact;
        $orders->ship_address1 = $invoice_info->ship_address1;
        $orders->ship_address2 = $invoice_info->ship_address2;
        $orders->ship_city_town = $invoice_info->ship_city_town;
        $orders->ship_state_province = $invoice_info->ship_state_province;
        $orders->ship_postal_code = $invoice_info->ship_postal_code;
        $orders->ship_telephone1 = $invoice_info->ship_telephone1;
        $orders->ship_email = $invoice_info->ship_email;
        $orders->message = $invoice_info->message;
        $orders->product_desc = $invoice_info->product_desc;
        $orders->note = $invoice_info->note;
        $orders->post_date = date('Y-m-d');
        $orders->period = gen_calculate_period(date('Y-m-d'));
        $orders->so_po_ref_id = $invoice_info->so_po_ref_id;
        $orders->purch_order_id = $invoice_info->purch_order_id;
        //$orders->purchase_invoice_id = $invoice_info->purchase_invoice_id;
        $orders->purchase_invoice_id = '';
        $orders->store_id = $invoice_info->store_id;
        $orders->recur_id = $invoice_info->recur_id;
        $orders->recur_frequency = $invoice_info->recur_frequency;
        $orders->admin_id = $invoice_info->admin_id;
        $orders->rep_id = $invoice_info->rep_id;
        $orders->terms = $invoice_info->terms;
        $orders->waiting = $invoice_info->waiting;
        $orders->terminal_date = $terminal_date;
        $orders->item_count = $invoice_info->item_count;
        $orders->weight = $invoice_info->weight;
        $orders->printed = $invoice_info->printed;
        $orders->subtotal = $invoice_info->subtotal;
        $orders->discount = $invoice_info->discount;
        $orders->disc_percent = $invoice_info->disc_percent;
        $orders->rm_attach = $invoice_info->rm_attach;
        $orders->sales_tax = $invoice_info->sales_tax;
        $orders->total_amount = $invoice_info->total_amount;
        $orders->from_recurring = 1;
        foreach ($invoice_info->item_rows as $item) {
            $item['id'] = '';
            $item_row[] = $item;
        }
        $orders->item_rows = $item_row;



        if ($post_success = $orders->post_ordr('insert')) {


            $update_query = "UPDATE invoice_recurring SET last_exec_date = '" . date('Y-m-d') . "', update_date = '" . date("Y-m-d g:i:s") . "' WHERE parent_inv_id=" . $recurring['parent_inv_id'];
            mysql_query($update_query);


            $sql = "SELECT * FROM journal_main WHERE id = " . $orders->id;
            $res = mysql_query($sql);
            $invoice_main_email = mysql_fetch_object($res);

            $sql = "SELECT journal_item.id, journal_item.ref_id, journal_item.item_cnt, journal_item.gl_type, journal_item.sku, journal_item.qty, 
journal_item.description, journal_item.debit_amount, journal_item.credit_amount, journal_item.gl_account, journal_item.taxable, journal_item.full_price,
journal_item.post_date,journal_item.uom,journal_item.uom_qty, tax_rates.description_short, tax_rates.rate_accounts  
FROM journal_item left join tax_rates on journal_item.taxable = tax_rates.tax_rate_id WHERE ref_id = " . $invoice_main_email->id;
            $res = mysql_query($sql);
            while ($item = mysql_fetch_assoc($res)) {
                $invoice_item[] = $item;
            }
            foreach ($invoice_item as $all_item) {

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




        $str = $invoice_main_email->terms;


        if (($invoice_main_email->terms == 0) OR ($invoice_main_email->terms == null)) {
            $payment_due_date = '30 Days';
        } else if ($str[0] == '1') {
            $payment_due_date = 'COD';
        } else if ($str[0] == '2') {
            $payment_due_date = 'PREPAID';
        } else if ($str[0] == '4') {
            $payment_due_date = $str[6] . $str[7] . '-' . $str[9] . $str[10] . '-' . $str[12] . $str[13] . $str[14] . $str[15];
        } else if ($str[0] == '5') {
            $payment_due_date = 'Month End';
        } else if ($str[0] = '3') {
            if ($str[8] !== ':') {
                $payment_due_date = $str[6] . $str[7] . $str[8] . ' days';
            } else {
                $payment_due_date = $str[6] . $str[7] . ' days';
            }
        }



        if ($invoice_main_email->rep_id == "1") {
            $sales_rep = "Jagga";
        } else if ($invoice_main_email->rep_id == "2") {
            $sales_rep = "";
        } else {
            $sales_rep = "none";
        }




        $company_code = COMPANY_CODE;

        $css = '
<style type="text/css">
    .pdf_area{width: 17cm; height: 25.7cm; border: 1px solid; padding: 2cm;}
    .company_area{width: 17cm; height: 5cm; }
        .company_area_left{width: 8cm; height: 5cm; float: left; text-align: left;}
        .company_area_right{width: 8cm; height: 5cm; float: right; text-align: right;}
        .company_name{font: bold 16px Arial;}
        .company_addrss{font:regular 14px Arial;}
    .adress_area{width: 17cm; height: 5cm; border: 1px solid;}
        .address_left{width: 5.96cm; height: 5cm; float: left; border-right: 1px solid; }
        .address_middle{width: 5.98cm; height: 5cm; float: left; border-right: 1px solid;}
        .address_right{width: 5cm; height: 5cm; float: left;}
        .address_heading_text{font: bold 16px Arial; width: 5cm; height: 26px; background-color: #e2dfdf; border-bottom: 1px solid; text-align: center; padding: 4px 0 0 0}
        .address_left .address_heading_text{width: 5.96cm;}
        .address_middle .address_heading_text{width: 5.98cm;}
        .address_right .address_heading_text{border-right: none; }
        .invoice_heading{font:bold 20px Arial; text-align: center;}
        .address_content{padding: 5px 0 0 10px; line-height: 18px; font:regular 14px Arial;}
    .invoice_item_area{width: 17cm; height: auto; border: 1px solid;}
        .invocie_item_header{width: 17cm; height: 30px; background-color: #e2dfdf; border-bottom: 1px solid;}
            .invocie_item_header_sku{width: 2cm; height: 26px; float: left; font: bold 16px Arial; text-align: center; padding: 4px 0 0 0; border-right: 1px solid;}
            .invocie_item_header_description{width: 6.5cm; height: 26px; float: left;font: bold 16px Arial; text-align: center; padding: 4px 0 0 0; border-right: 1px solid;}
            .invocie_item_header_qty{width: 2cm; height: 26px; float: left;font: bold 16px Arial; text-align: center; padding: 4px 0 0 0; border-right: 1px solid;}
            .invocie_item_header_nutprice{width: 3cm; height: 26px; float: left;font: bold 16px Arial; text-align: center; padding: 4px 0 0 0; border-right: 1px solid;}
            .invocie_item_header_extension{width: 3cm; height: 26px; float: left;font: bold 16px Arial; text-align: center; padding: 4px 0 0 0;}
         .invocie_item{width: 17cm; height: 30px; }
            .invocie_item_sku{width: 1.7cm; height: 27px; float: left; border-right: 1px solid; padding-left: .3cm; padding-top: 3px;}
            .invocie_item_description{width: 6.2cm; height: 27px; float: left; border-right: 1px solid; padding-left: .3cm; padding-top: 3px;}
            .invocie_item_qty{width: 1.7cm; height: 27px; float: left; border-right: 1px solid; padding-left: .3cm ; padding-top: 3px;}
            .invocie_item_nutprice{width: 2.7cm; height: 27px; float: left; border-right: 1px solid; padding-left: .3cm; padding-top: 3px;}
            .invocie_item_extension{width: 2.7cm; height: 27px; float: left; padding-left: .3cm; padding-top: 3px;}
         .invocie_sub{width: 17cm; height: auto;}
            
            .invocie_sub_right{width: 6.44cm; height: auto; float: right; border: 1px solid; border-top: none; margin: 0 -2px 0 0}
            .sub_total_left{width: 2.72cm; float: left; height: 26px; border-right: 1px solid; padding: 4px 0 0 .3cm; font-weight: bold;  }
            .sub_total_right{width: 2.7cm; float: left; height: 24px; padding: 4px 0 0 .3cm; }
            .heading_td{background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;}
            .body_td{ border-right: 1px solid; border-bottom: 1px solid; padding-top: 5px; padding-bottom: 5px; }
            .subtotal_td{border: 1px solid; border-top: none;}
            .heading_td.bottom_none{border-bottom: none;}
            .body_td.bottom_border{border-bottom: none !important;}
</style>';


        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . 'cheshta_' . $company_code . '/ucform/images/company_logo/' . COMPANY_LOGO . '" />
        </div>
        <div class="company_area_right" style="font:14px arial;"  >
            
            <span class="company_name" >' . COMPANY_NAME . '</span>
            <br/>
            <span style="font:12px arial;" class="company_addrss" >' . COMPANY_ADDRESS1 . '</span>
            <br/>
			<span style="font:12px arial;" class="company_addrss" >' . COMPANY_POSTAL_CODE . ' ' . COMPANY_COUNTRY . ' ' . COMPANY_CITY_TOWN . '</span>
            <br/>
            <span style="font:12px arial;" class="company_addrss"> ' . COMPANY_TELEPHONE1 . '  </span>
            <br/>
            <span style="font:12px arial;" class="company_addrss"> ' . COMPANY_EMAIL . ' </span>
            <br/>
            <span style="font:12px arial;" class="company_addrss"> ' . COMPANY_WEBSITE . '</span>
            <br/>
            <span style="font:12px arial;" class="company_addrss"> GST No: ' . TAX_ID . '</span>
            
        </div>
     
    </div>
    <div class="invoice_heading">
      TAX INVOICE
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" " valign="top"  >
                <tr>
                    <td style="font:bold 12px arial;">
                         ' . $invoice_main_email->bill_primary_name . ' 
                    </td>
                </tr>
				
				<tr>
                    <td style="font:12px arial;">
                         ' . $invoice_main_email->bill_address1 . ', ' . $invoice_main_email->bill_address2 . ' 
                    </td>
                </tr>
				
				 <tr>
                    <td style="font:12px arial;">
                         ' . $invoice_main_email->bill_city_town . ',  ' . $invoice_main_email->bill_state_province . ',  ' . $invoice_main_email->bill_postal_code . ',  ' . $invoice_main_email->bill_country_code . '
                    </td>
                </tr>
				
				<tr>
                    <td style="font:12px arial;">
                         ' . $invoice_main_email->bill_telephone1 . ' 
                    </td>
                </tr>
				<tr>
					<td style="font:12px arial;">
                        Attn: ' . $invoice_main_email->bill_contact . ' 
                    </td>
                </tr>
				
            </table>
        </div>
		
        <div style="width:40%; float:right; height:80px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>INV Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main_email->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>INV Date : </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main_email->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due INV Date : </b></span></td><td> <span style="font:12px arial">
                ' . $payment_due_date . '
            </span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>PO Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main_email->purch_order_id .
                '</span>
                             
                             </td>
                             </tr>
                            </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    
    <br/>
    
    
    <br/><br/>
    


    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" ">
        <tr>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="30%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST Code</td>
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if (UOM == 1) {
            $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
        }
        if (UOM_QTY == 1) {
            $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
        }
        $pdf .='<td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';


        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if (UOM_QTY == 1) {
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval(($item['uom_qty'] != 0) ? $item['uom_qty'] : 1);
            } else {
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;

            $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="30%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td width="20%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
            <td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if (UOM == 1) {
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if (UOM_QTY == 1) {
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] != 0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td width="20%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="12%" style="border-top:1px solid;"></td>
            <td width="30%" style="border-top:1px solid;"></td>
            <td width="8%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid; "></td>';
        if (UOM == 1) {
            $pdf .='<td style="border-top:1px solid;"></td>';
        }
        if (UOM_QTY == 1) {
            $pdf .='<td style="border-top:1px solid;"></td>';
        }
        $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main_email->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main_email->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main_email->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Tax </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main_email->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Invoice Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';



        $pdf .='
    
    <br/>
    <br/>
    <div style="font:14px arial">' . $invoice_main_email->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
        
        <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount(RM)</th>
                <th width="33%">GST(RM)</th>
            </tr>
            
            
';
        foreach ($item_val['total_price_on_tax'] as $key => $val) {
            $pdf .='
                <tr>
                    <td width="33%" align="center">' . $key . '</td>
                    <td width="33%" align="center">' . $currencies->format_full($val, true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
                        
                    <td width="33%" align="center">' . $currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main_email->currencies_code, $invoice_main_email->currencies_value) . '</td>
                </tr>
            ';
        }


        $pdf .= '
            
            </table>
            <br/><br/>
        <table width = "100%" style"border:1px solid;">
            <tr>
                <td width="100%" style="background-color:#cccccc; font: bold 14px arial; text-align:center;">
                    <i>No signature is required as this is a computer generated form.</i>
                </td>
            </tr>
        </table>
    </div>
</div>';
        require_once(DIR_FS_INCLUDES . 'mpdf/mpdf.php');
        $mpdf = new mPDF();

        $mpdf->WriteHTML($css, 1);
        $mpdf->WriteHTML($pdf);
        if (ENVIRONMENT == 'cheshta' || ENVIRONMENT == 'local') {
            $temp_file = DIR_FS_MY_FILES . 'cheshta_' . COMPANY_CODE . '/temp/filename.pdf';
        } else {
            $temp_file = DIR_FS_MY_FILES .  COMPANY_CODE . '/temp/filename.pdf';
        }
        
        $mpdf->Output($temp_file, "F");
        

        if (!empty($recurring['sender_email'])) {
            $from_email = $recurring['sender_email'];
            $from_name = $recurring['sender_name'];

            //$to_email = $recurring['receiver_email'];
            $to_email = "test@localhost";
            $to_name = $recurring['receiver_name'];
            $cc_email = $recurring['cc_email'];
            $cc_name = $recurring['cc'];
            $message_subject = $recurring['email_subject'];
            $email_text = $recurring['email_body'];

            $block = array();
            if ($cc_email) {
                $block['EMAIL_CC_NAME'] = $cc_name;
                $block['EMAIL_CC_ADDRESS'] = $cc_email;
            }
            $attachments_list['file'] = $temp_file;
            $success = validate_send_mail($to_name, $to_email, $message_subject, $email_text, $from_name, $from_email, $block, $attachments_list);
            if ($success) {
                $messageStack->add(EMAIL_SEND_SUCCESS, 'success');
                unlink($temp_file);
            }
        }
    }
}















