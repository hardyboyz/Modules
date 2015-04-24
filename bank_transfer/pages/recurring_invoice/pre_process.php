<?php

// +-----------------------------------------------------------------+
// |                   bank_transfer Open Source ERP                    |
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
//  Path: /modules/bank_transfer/pages/orders/pre_process.php
//
/* * ************   Check user security   **************************** */


//this is for menu select coding by 4aixz(apu) date:16_04_2013
//$security_level = validate_user($security_token);
/* * ************  include page specific files    ******************** */
gen_pull_language('contacts');

require_once(DIR_FS_WORKING . 'defaults.php');
require_once(DIR_FS_MODULES . 'inventory/defaults.php');
require_once(DIR_FS_WORKING . 'functions/bank_transfer.php');
require_once(DIR_FS_WORKING . 'classes/gen_ledger.php');
require_once(DIR_FS_WORKING . 'classes/orders.php');
if (defined('MODULE_SHIPPING_STATUS')) {
    require_once(DIR_FS_MODULES . 'shipping/functions/shipping.php');
    require_once(DIR_FS_MODULES . 'shipping/defaults.php');
}
/* * ************   page specific initialization  ************************ */

$error = false;

$order = new orders();

// this is for insert value in journal_main for recurring invoice coding by 4axiz(apu) date:16-july-2013 

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
        $recurring_day = date("Y-m-d", strtotime($recurring['inv_date']) + $recurring['recurring_interval'] * (60 * 60 * 24));
    }



    $terminal_date = "";
    if (date("Y-m-d") == $recurring_day) {
        $invoice_info = unserialize($recurring['inv_info']);
        $invoice_item_info = unserialize($recurring['inv_detail_info']);
        $terminal_date = strtotime($invoice_info['terminal_date']) - strtotime($invoice_info['post_date']);
        $terminal_date = $terminal_date / (60 * 60 * 24);
        $terminal_date = date("Y-m-d", strtotime(date('Y-m-d')) + $terminal_date * (60 * 60 * 24));


        $invoice_info['from_recurring'] = 1;
        $invoice_info['post_date'] = date('Y-m-d');
        $invoice_info['terminal_date'] = $terminal_date;
        $query = $order->create_insert_query("journal_main", $invoice_info);

        if (mysql_query($query)) {
            $invoice_id = mysql_insert_id();
            $update_query = "UPDATE invoice_recurring SET last_exec_date = '" . date('Y-m-d') . "', update_date = '" . date("Y-m-d g:i:s") . "' WHERE parent_inv_id=" . $recurring['parent_inv_id'];
            mysql_query($update_query);
        }

        foreach ($invoice_item_info as $invoice_item) {

            if ($invoice_item['date_1'] > 0) {
                $invoice_item['date_1'] = $terminal_date;
            } else {
                $invoice_item['date_1'] = "";
            }
            $invoice_item['post_date'] = date('Y-m-d');
            $invoice_item['ref_id'] = $invoice_id;

            $query = $order->create_insert_query("journal_item", $invoice_item);
            mysql_query($query);
        }

        $sql = "SELECT * FROM journal_main WHERE id = " . $invoice_id;
        $res = mysql_query($sql);
        $invoice_main_email = mysql_fetch_object($res);

        $sql = "SELECT * FROM journal_item WHERE ref_id = " . $invoice_main_email->id;
        $res = mysql_query($sql);
        while ($item = mysql_fetch_assoc($res)) {
            $invoice_item_email[] = $item;
        }

        $res = mysql_query("SELECT * FROM configuration");

        while ($configuration[] = mysql_fetch_assoc($res)) {
            
        }









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


echo "<pre>";
//print_r($invoice_main);
echo "</pre>";

echo "<pre>";
//print_r($invoice_item);

echo "</pre>";

echo "<pre>";
//print_r($configuration);
echo "</pre>";
require_once(DIR_FS_INCLUDES . 'mpdf/mpdf.php');
$mpdf = new mPDF();

foreach ($configuration as $key => $val) {
    if ($val['configuration_key'] == "COMPANY_LOGO") {
        $logo = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_NAME") {
        $company_name = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_ADDRESS1") {
        $company_address = $val['configuration_value'] . "</br>";
    }
    if ($val['configuration_key'] == "COMPANY_ADDRESS2") {
        $company_address .= $val['configuration_value'] . "</br>";
    }
    if ($val['configuration_key'] == "COMPANY_CITY_TOWN") {
        $company_address .= " " . $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_ZONE") {
        $company_address .= " " . $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_POSTAL_CODE") {
        $company_address .= " " . $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_EMAIL") {
        $email = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_WEBSITE") {
        $web = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_TELEPHONE1") {
        $phone = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_TELEPHONE2") {
        $phone .= " ," . $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_FAX") {
        $fax = $val['configuration_value'];
    }
}








//start invoice pdf
$pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" width="150" height="150" src="user_company/'.$company_code.'/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:14px arial;"  >
            
            <span class="company_name" >' . $company_name . '</span>
            <br/>
            <span style="font:12px arial;" class="company_addrss" >' . $company_address . '</span>
            <br/>
            <span style="font:12px arial;" class="company_addrss"> ' . $phone . '  </span>
            <br/>
            <span style="font:12px arial;" class="company_addrss"> ' . $email . ' </span>
            <br/>
            <span style="font:12px arial;" class="company_addrss"> ' . $web . '</span>
            
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="invoice_heading">
       Sales/Invoice
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" " valign="top"  >
                <tr>
                    <td style="height:22px; background-color: #cccccc;font: bold 12px arial;">
                        Client
                    </td>
                </tr>
                <tr>
                    <td style="font:bold 12px arial;">
                         '.$invoice_main->bill_primary_name.' 
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
                        $invoice_main->purchase_invoice_id
                        . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>INV Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->post_date))
                        . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due INV Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->terminal_date))
                        . '</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>PO Num : </b></span></td><td> <span style="font:12px arial">' 
                            .$invoice_main->purch_order_id.
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
    


    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border-bottom:1px solid;">
        <tr>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td width="38%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Total Amount</td>
        </tr>';

$last_item = array_pop($invoice_item);
array_pop($invoice_item);
array_pop($invoice_item);
$i = 1;
$total_item_amount = "";
foreach ($invoice_item as $item) {
    $total_item_amount= $total_item_amount + $item['full_price'] * $item['qty'];
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'] / $item['qty'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
    $i++;
}

$pdf .='
       <tr>
            <td width="12%" style="border-right: 1px solid; border-left: 1px solid;"></td>
            <td width="38%" style="border-right: 1px solid;"></td>
            <td width="10%" style="border-right: 1px solid;"></td>
            <td width="20%" style="border: 1px solid;  border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;"><b>Total</b></td>
            <td width="20%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <br/>
    <div class="footer_area">
        <table width = "100%" style"border:1px solid;">
            <tr>
                <td width="100%" style="background-color:#cccccc; font: bold 14px arial; text-align:center;">
                    <i>No signature is required as this is a computer generated form.</i>
                </td>
            </tr>
        </table>
        
    </div>
</div>';
   









        $mpdf->WriteHTML($css, 1);
        $mpdf->WriteHTML($pdf);
        
        $temp_file = DIR_FS_MY_FILES . $_SESSION['company'] . '/temp/filename.pdf';
        $mpdf->Output($temp_file, "F");

        $from_email = $_POST['from_email'] ? $_POST['from_email'] : $_SESSION['admin_email'];
        $from_name = $_POST['from_name'] ? $_POST['from_name'] : $_SESSION['display_name'];
        $to_email = $invoice_main_email->bill_email;
        //$to_email = "apu@4axiz.com";
        $to_name = $_POST['to_name'] ? $_POST['to_name'] : $_GET['rName'];
        $cc_email = $_POST['cc_email'] ? $_POST['cc_email'] : '';
        $cc_name = $_POST['cc_name'] ? $_POST['cc_name'] : $cc_address;
        $message_subject = $title . ' ' . TEXT_FROM . ' ' . COMPANY_NAME;
        $message_subject = $_POST['message_subject'] ? $_POST['message_subject'] : $message_subject;
        $message_body =  "To view the attachment, you must have pdf reader software installed on your computer.";
        $email_text = $_POST['message_body'] ? $_POST['message_body'] : $message_body;

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



echo "<pre>";
//print_r($recurring_info);
echo "</pre>";














