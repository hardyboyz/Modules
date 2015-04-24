<?php
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
?>

<?php

//start Delivery Order pdf
if($_GET['pdf_type'] == "do"){
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
        Delivery Order
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
                         <span style="font:12px arial"><b>DO Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                        $invoice_main->purchase_invoice_id
                        . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>DO Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->post_date))
                        . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due DO Date : </b></span></td><td> <span style="font:12px arial">' .
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
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td width="80%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Description</td>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >QTY</td>
        </tr>';

$last_item = array_pop($invoice_item);
//array_pop($invoice_item);
//array_pop($invoice_item);
$i = 1;
foreach ($invoice_item as $item) {
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i. '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>
        </tr>
        ';
    $i++;
}
$pdf .='</table>
    
    <br/>
    <br/>
    <br/>
    <div class="footer_area">
        <div style="width:70%;">
            <span style="font:14px Arial;">
                <i>Received in good order by :</i>
            </span>
            <br/>
            
            <div style="font:14px arial">'.$invoice_main->message.'</div>
            <br/>
            <br/>
            ........................................................
            <br>
            <span style="font:12px Arial;">
            Company Stamp and Authorised Signature
            </span>
        </div>
        
    </div>
</div>';

$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($pdf);
//end Delivery Order pdf

}else if($_GET['pdf_type'] == "inv" || $_GET['jID'] == 12){

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
//array_pop($invoice_item);
//array_pop($invoice_item);
$i = 1;
$total_item_amount = "";
foreach ($invoice_item as $item) {
    $total_item_amount= $total_item_amount + $item['full_price'] * $item['qty'];
    $u_price =  intval($item['debit_amount']) + intval($item['credit_amount']);
    $u_price = intval($u_price) / intval($item['qty']);
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
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
            <td width="20%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;">' . $currencies->format_full($last_item['debit_amount']+$last_item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">'.$invoice_main->message.'</div>
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
}
//end invoice pdf
else if($_GET['pdf_type'] == "qpdf" || $_GET['jID'] == 9){
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
       Quotation
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
                         <span style="font:12px arial"><b>Quotation Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                        $invoice_main->purchase_invoice_id
                        . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Quotation Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->post_date))
                        . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Quotation Date : </b></span></td><td> <span style="font:12px arial">' .
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
//array_pop($invoice_item);
//array_pop($invoice_item);
$i = 1;
$total_item_amount = "";
foreach ($invoice_item as $item) {
    $total_item_amount= $total_item_amount + $item['full_price'] * $item['qty'];
    $u_price =  intval($item['debit_amount']) + intval($item['credit_amount']);
    $u_price = intval($u_price) / intval($item['qty']);
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
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
            <td width="20%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;">' . $currencies->format_full($last_item['debit_amount']+ $last_item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">'.$invoice_main->message.'</div>
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
}else if($_GET['pdf_type'] == "so" || $_GET['jID'] == 10){
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
       Sales Orders
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
                         <span style="font:12px arial"><b>SO Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                        $invoice_main->purchase_invoice_id
                        . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>SO Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->post_date))
                        . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due SO Date : </b></span></td><td> <span style="font:12px arial">' .
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
//array_pop($invoice_item);
//array_pop($invoice_item);
$i = 1;
$total_item_amount = "";
foreach ($invoice_item as $item) {
    $total_item_amount= $total_item_amount + $item['full_price'] * $item['qty'];
    $u_price =  intval($item['debit_amount']) + intval($item['credit_amount']);
    $u_price = intval($u_price) / intval($item['qty']);
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
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
            <td width="20%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;">' . $currencies->format_full($last_item['debit_amount']+$last_item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">'.$invoice_main->message.'</div>
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
}else if($_GET['pdf_type'] == "cm" || $_GET['jID'] == 13 || $_GET['jID'] == 7){
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
       Credit Memo
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
                         <span style="font:12px arial"><b>Inv Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                        $invoice_main->purchase_invoice_id
                        . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Inv Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->post_date))
                        . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Inv Date : </b></span></td><td> <span style="font:12px arial">' .
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
//array_pop($invoice_item);
//array_pop($invoice_item);
$i = 1;
$total_item_amount = "";
foreach ($invoice_item as $item) {
    $total_item_amount= $total_item_amount + $item['full_price'] * $item['qty'];
    $u_price =  intval($item['debit_amount']) + intval($item['credit_amount']);
    $u_price = intval($u_price) / intval($item['qty']);
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
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
            <td width="20%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;">' . $currencies->format_full($last_item['debit_amount']+$last_item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">'.$invoice_main->message.'</div>
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
}else if($_GET['pdf_type'] == "pur"){
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
       Purchase
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
                         <span style="font:12px arial"><b>Pur Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                        $invoice_main->purchase_invoice_id
                        . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Pur Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->post_date))
                        . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Pur Date : </b></span></td><td> <span style="font:12px arial">' .
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
//array_pop($invoice_item);
//array_pop($invoice_item);
$i = 1;
$total_item_amount = "";
foreach ($invoice_item as $item) {
    $total_item_amount= $total_item_amount + $item['full_price'] * $item['qty'];
    $u_price =  intval($item['debit_amount']) + intval($item['credit_amount']);
    $u_price = intval($u_price) / intval($item['qty']);
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
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
    <div style="font:14px arial">'.$invoice_main->message.'</div>
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
}else if($_GET['pdf_type'] == "puro" || $_GET['jID'] == 4){
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
       Purchase Order
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
                         <span style="font:12px arial"><b>Pur Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                        $invoice_main->purchase_invoice_id
                        . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Pur Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->post_date))
                        . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Pur Date : </b></span></td><td> <span style="font:12px arial">' .
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
//array_pop($invoice_item);
//array_pop($invoice_item);
$i = 1;
$total_item_amount = "";
foreach ($invoice_item as $item) {
    $total_item_amount= $total_item_amount + $item['full_price'] * $item['qty'];
    $u_price =  intval($item['debit_amount']) + intval($item['credit_amount']);
    $u_price = intval($u_price) / intval($item['qty']);
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
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
            <td width="20%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;">' . $currencies->format_full($last_item['debit_amount']+$last_item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">'.$invoice_main->message.'</div>
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
}else if($_GET['pdf_type'] == "rqpuro" || $_GET['jID'] == 3){
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
       Request for Quote
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
                         <span style="font:12px arial"><b>Pur Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                        $invoice_main->purchase_invoice_id
                        . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Pur Date : </b></span></td><td> <span style="font:12px arial">' .
                        date("d-m-Y", strtotime($invoice_main->post_date))
                        . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Pur Date : </b></span></td><td> <span style="font:12px arial">' .
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
//array_pop($invoice_item);
//array_pop($invoice_item);
$i = 1;
$total_item_amount = "";
foreach ($invoice_item as $item) {
    $total_item_amount= $total_item_amount + $item['full_price'] * $item['qty'];
    $u_price =  intval($item['debit_amount']) + intval($item['credit_amount']);
    $u_price = intval($u_price) / intval($item['qty']);
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
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
            <td width="20%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;">' . $currencies->format_full($last_item['debit_amount']+$last_item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">'.$invoice_main->message.'</div>
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
}







if ($_GET['action'] == "email") {

   if($_GET['jID'] == 9){
       $file_name = "quotation.pdf";
   }else if($_GET['jID'] == 12){
       $file_name = "invoice.pdf";
   }else if($_GET['jID'] == 10){
       $file_name = "sales_order.pdf";
   }else if($_GET['jID'] == 13){
       $file_name = "credit_memo.pdf";
   }else if($_GET['jID'] == 4){
       $file_name = "purchase_order.pdf";
   }else if($_GET['jID'] == 7){
       $file_name = "credit_memo.pdf";
   }else if($_GET['jID'] == 3){
       $file_name = "request_for_quote.pdf";
   }
    
$temp_file = DIR_FS_MY_FILES . $_SESSION['company'] . '/temp/'.$file_name;
$mpdf->Output($temp_file, "F");
    $from_email = $_POST['from_email'] ? $_POST['from_email'] : $_SESSION['admin_email'];
    $from_name = $_POST['from_name'] ? $_POST['from_name'] : $_SESSION['display_name'];
    $to_email = $_POST['to_email'] ? $_POST['to_email'] : $_GET['rEmail'];
    $to_name = $_POST['to_name'] ? $_POST['to_name'] : $_GET['rName'];
    $cc_email = $_POST['cc_email'] ? $_POST['cc_email'] : '';
    $cc_name = $_POST['cc_name'] ? $_POST['cc_name'] : $cc_address;
    $message_subject = $title . ' ' . TEXT_FROM . ' ' . COMPANY_NAME;
    $message_subject = $_POST['message_subject'] ? $_POST['message_subject'] : $message_subject;
    $message_body = $report->emailmessage ? TextReplace($report->emailmessage) : sprintf(UCFORM_EMAIL_BODY, $title, COMPANY_NAME);
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
        ?>
        <script type="text/javascript">
            window.close();
        </script>
        <?
    }
} else {
    ob_clean ();
    $mpdf->Output();
    exit;
}
?>


