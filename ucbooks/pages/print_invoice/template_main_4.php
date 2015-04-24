<?php
$company_code = COMPANY_CODE;
$css = '
<style type="text/css">
    .pdf_area{width: 19cm; height: 25.7cm; border: 1px solid; padding: 2cm;}
    .company_area{width: 19cm; height: 3cm; }
        .company_area_left{width: 8cm; height: 3cm; float: left; text-align: left;}
        .company_area_right{width: 10cm; height: 3cm; float: right; text-align: right;}
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

$customer_info = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" " valign="top"  >';

if (!empty($invoice_main->bill_primary_name)) {
    $customer_info .= '   <tr>
           
                    <td style="font:bold 12px arial;">
                         ' . $invoice_main->bill_primary_name . ' 
                    </td>
                </tr>';
}

$customer_info .= '<tr>
<td style = "font:12px arial;">';
if (!empty($invoice_main->bill_address1))
    $customer_info .= $invoice_main->bill_address1;
if (!empty($invoice_main->bill_address2)) {
    if (!empty($invoice_main->bill_address1))
        $customer_info .=', ' . $invoice_main->bill_address2;
    else
        $customer_info .= $invoice_main->bill_address2;
}
$customer_info .= '</td>
</tr>';


$customer_info .= '<tr>
<td style = "font:12px arial;">';
if (!empty($invoice_main->bill_city_town))
    $customer_info .= $invoice_main->bill_city_town;
if (!empty($invoice_main->bill_state_province)) {
    if (!empty($invoice_main->bill_city_town))
        $customer_info .= ', ' . $invoice_main->bill_state_province;
    else
        $customer_info .= $invoice_main->bill_state_province;
}
if (!empty($invoice_main->bill_postal_code)) {
    if (!empty($invoice_main->bill_state_province))
        $customer_info .= ', ' . $invoice_main->bill_postal_code;
    else
        $customer_info .= ', ' . $invoice_main->bill_postal_code;
}
$countries = gen_get_countries();
foreach ($countries as $country) {
    if ($country['id'] == $invoice_main->bill_country_code) {
        $country_name = $country['text'];
        break;
    }
}
if (!empty($invoice_main->bill_country_code)) {
    if (!empty($invoice_main->bill_postal_code))
        $customer_info .= ', ' . $country_name;
    else
        $customer_info .= $country_name;
}
$customer_info .= '</td>
</tr>';

if (!empty($invoice_main->bill_telephone1)) {
    $customer_info .= '<tr>
<td style = "font:12px arial;">
' . $invoice_main->bill_telephone1 . '
</td>';
}
if (!empty($invoice_main->bill_contact)) {
    $customer_info .= '</tr>
<tr>
<td style = "font:12px arial;">
Attn: ' . $invoice_main->bill_contact . '
</td>
</tr>';
}
$customer_info .='</table >';

foreach ($configuration as $key => $val) {
    if ($val['configuration_key'] == "COMPANY_LOGO") {
        $logo = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_ID") {
        $company_id = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_NAME") {
        $company_name = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_ADDRESS1") {
        $company_address = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_ADDRESS2") {
        $company_address2 = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_CITY_TOWN") {
        $company_city_town =  $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_ZONE") {
        $company_state = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_POSTAL_CODE") {
        $company_postal_code =  $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_COUNTRY") {
        $company_country = $val['configuration_value'];
        $countries = gen_get_countries();
        foreach ($countries as $country) {
            if ($country['id'] == $company_country) {
                $company_country = $country['text'];
                break;
            }
        }
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
        if (!empty($val['configuration_value']))
            $phone .= " ," . $val['configuration_value'];
        else
            $phone .= $val['configuration_value'];
    }
    if ($val['configuration_key'] == "COMPANY_FAX") {
        $fax = $val['configuration_value'];
    }
    if ($val['configuration_key'] == "TAX_ID") {
        $tax_id = $val['configuration_value'];
    }
}
?>

<?php
//Line 1
$company_info = '<span class="company_name" >' . $company_name;
if (!empty($company_id)) {
    $company_info .= ' (' . $company_id . ')</span><br/>';
} else {
    $company_info .= '</span><br/>';
}
// Line 2 
if (!empty($company_address)) {
    $company_info .= ' <span style="font:12px arial;" class="company_addrss" >' . $company_address . ',</span>
            <br/>';
}
// Line 3 
if (!empty($company_address2)) {
    $company_info .= ' <span style="font:12px arial;" class="company_addrss" >' . $company_address2 . ',</span>
            <br/>';
}

// Line 4
if (!empty($company_postal_code)) {
    $company_info .= ' <span style="font:12px arial;" class="company_addrss" >' . $company_postal_code;
    if (empty($company_city_town))
        $company_info .= '</span>';
}


if (!empty($company_city_town)) {
    if (!empty($company_postal_code))
        $company_info .= '  ' . $company_city_town . ',</span><br/>';
    else
        $company_info .= ' <span style="font:12px arial;" class="company_addrss" >' . $company_city_town . ',</span><br/>';
}
// Line 5
if (!empty($company_state)) {
    $company_info .= ' <span style="font:12px arial;" class="company_addrss" >' . $company_state;
    if (empty($company_country))
        $company_info .= '</span>';
}


if (!empty($company_country)) {
    if (!empty($company_state))
        $company_info .= ', ' . $company_country . '</span><br/>';
    else
        $company_info .= ' <span style="font:12px arial;" class="company_addrss" >' . $company_country . '</span><br/>';
}
// Line 6
if (!empty($phone)) {
    $company_info .= ' <span style="font:12px arial;" class="company_addrss" >(T)' . $phone;
    if (empty($fax))
        $company_info .= '</span>';
}


if (!empty($fax)) {
    if (!empty($phone))
        $company_info .= ' (F)' . $fax . '</span><br/>';
    else
        $company_info .= ' <span style="font:12px arial;" class="company_addrss" >(F)' . $fax . '</span><br/>';
}
// Line 7

if (!empty($email)) {
    $company_info .= ' <span style="font:12px arial;" class="company_addrss" >' . $email . '</span>
            <br/>';
}
//Line 8

if (!empty($web)) {
    $company_info .= ' <span style="font:12px arial;" class="company_addrss" >' . $web . '</span>
            <br/>';
}
// Line 9
if (!empty($tax_id)) {
    $company_info .= ' <span style="font:12px arial;" class="company_addrss" >GST ID: ' . $tax_id . '</span>
            <br/>';
}

$str = $invoice_main->terms;
//print_r (explode(":",$str,0));

if (($invoice_main->terms == 0) OR ($invoice_main->terms == null))
	{
	$payment_due_date = '30 Days';
	}
else if ($str[0] == '1')
	{
	$payment_due_date = 'COD';
	}
else if ($str[0] == '2')
	{
	$payment_due_date = 'PREPAID';
	}
else if ($str[0] == '4')
	{
	$payment_due_date = $str[6].$str[7].'-'.$str[9].$str[10].'-'.$str[12].$str[13].$str[14].$str[15];
	}
else if ($str[0] == '5')
	{
	$payment_due_date = 'Month End';
	}	
else if ($str[0] = '3')
	{
		if ($str[8] !== ':')
			{
				$payment_due_date = $str[6].$str[7].$str[8].' days';
			}
		else
			{
				$payment_due_date = $str[6].$str[7].' days';
			}
	}

		

if ($invoice_main->rep_id == "1")
	{ 
	$sales_rep = "";
	}
else if ($invoice_main->rep_id == "2")
	{ 
	$sales_rep = "";
	}
else 
	{
	$sales_rep = "none";
	}

require_once ("shipping_info.php");
//start Delivery Order pdf *************************************//

if ($_GET['pdf_type'] == "do") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
            
           '.$company_info.'
            
        </div>

    </div>
    <div class="invoice_heading">
        Delivery Order
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
            '.$shipping_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>DO Number : </b></span> 
                            </td><td><span style="font:12px arial">DO/' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>DO Date : </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>DO Due Date : </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>PO Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
           
			<td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Item Code</td>
			<td width="70%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Description</td>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >QTY</td>';
            if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; ">UOM QTY</td>';
            }
        $pdf .='</tr>';

     
        $i = 1;
        foreach ($item_val['item'] as $item) {
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
			<td valign="top" align="center" width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['sku'] . '</td>
			<td valign="top" align="left" width="70%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="12%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; border-right:1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
        $pdf .='</tr>
        ';
            $i++;
        }
        $pdf .='</table>
    
    <br/>
    <br/>
    <br/>
    <div class="footer_area">
       <div style="font:10px arial">' . $invoice_main->message . '</div>
        
    </div>
</div>';
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
	
	

//end Delivery Order pdf





//Start Invoice *******************************************************************//
//start invoice pdf



} else if ($_GET['pdf_type'] == "inv" && ($_GET['jID'] == 12 && $_GET['sub_jID'] == 0) || ($_GET['jID'] == 12  && $_GET['sub_jID']==1)) {  
  if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {

        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
            
           '.$company_info.'
            
        </div>
     
    </div>
    <div class="invoice_heading">
      TAX INVOICE
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
            '.$customer_info.'
        </div>
		
        <div style="width:30%; float:right; height:90px; border:1px solid;">
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
                             <span style="font:12px arial"><b>Due Date : </b></span></td><td> <span style="font:12px arial">
                '.$payment_due_date.'
            </span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>PO Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Item Code</td>
			<td width="39%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</td>
            <td width="14%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval( ($item['uom_qty'] !=0) ? $item['uom_qty'] : 1);
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="5%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
			<td valign="top" align="center" width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['sku'] . '</td>
			<td valign="top" width="39%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" width="14%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="5%" style="border-top:1px solid;"></td>
			 <td width="10%" style="border-top:1px solid;"></td>
            <td width="39%" style="border-top:1px solid;"></td>
            <td width="8%" style="border-top:1px solid;"></td>
            <td width="14%" style="border-top:1px solid; "></td>';
            if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          }
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>GST</b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Net Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';



        $pdf .='
    
    <br/>
    <br/>
    <div style="font:10px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
        
        <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount</th>
                <th width="33%">GST</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                </tr>
            ';
        }
        

        $pdf .= '
            
            </table>
            <br/><br/>
    
    </div>
</div>';
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}
//end invoice pdf


else if ($_GET['pdf_type'] == "qpdf" || $_GET['jID'] == 9) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	
	
        //*********************start Quotations******************//
		
		
		
		
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
       <div class="company_area_right" style="font:14px arial;"  >
            
            '.$company_info.'
            
        </div>
      
    </div>
    <div class="invoice_heading">
       Quotation
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
            '.$customer_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Quo Num: </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Date: </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Date: </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>Ref Num: </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="45%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</td>
            <td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval( ($item['uom_qty'] !=0) ? $item['uom_qty'] : 1);
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="5%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="45%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="5%" style="border-top:1px solid;"></td>
            <td width="45%" style="border-top:1px solid;"></td>
            <td width="10%" style="border-top:1px solid;"></td>
            <td width="16%" style="border-top:1px solid; "></td>';
            if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          }
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>GST</b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Net Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';
        $pdf .='
      
    
    <br/>
    <br/>
    <div style="font:10px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
	
	
	 <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount</th>
                <th width="33%">GST</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                </tr>
            ';
        }
        

        $pdf .= '
            
            </table>
			
			<br>
			<br>
			
        <table width = "100%" style"border:1px solid;">
            <tr>
                <td width="100%" style="background-color:#cccccc; font: bold 14px arial; text-align:center;">
                    <i>No signature is required as this is a computer generated form.</i>
                </td>
            </tr>
        </table>
        
    </div>
</div>';
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "so" || $_GET['jID'] == 10) {
    
	
	
	
	//start SALES ORDER pdf ******************************************//

	
	
	
	
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
          '.$company_info.'
            
        </div>
    </div>
    <div class="invoice_heading">
      Sales Order
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
            '.$customer_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>SO Number: </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Date: </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Date: </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>Ref Num: </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="45%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</td>
            <td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval( ($item['uom_qty'] !=0) ? $item['uom_qty'] : 1);
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="5%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="45%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="5%" style="border-top:1px solid;"></td>
            <td width="45%" style="border-top:1px solid;"></td>
            <td width="10%" style="border-top:1px solid;"></td>
            <td width="16%" style="border-top:1px solid; "></td>';
            if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          }
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>GST</b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Net Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';

        $pdf .='
       
    
    <br/>
    <br/>
    <div style="font:10px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
	
	<table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount</th>
                <th width="33%">GST</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                </tr>
            ';
        }
        

        $pdf .= '
            
            </table>
			<br/>
    <br/>
			
        <table width = "100%" style"border:1px solid;">
            <tr>
                <td width="100%" style="background-color:#cccccc; font: bold 14px arial; text-align:center;">
                    <i>No signature is required as this is a computer generated form.</i>
                </td>
            </tr>
        </table>
        
    </div>
</div>';
    }

    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}else if ($_GET['pdf_type'] == "cust_deposit") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	
	
        //start CREDIT NOTE pdf *****************************************************************//
		
		
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
            
            '.$company_info.'
            
        </div>
    </div>
    <div class="invoice_heading">
       Customer Deposit
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
            '.$customer_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Deposit Num: </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                           <tr><td>
                             <span style="font:12px arial"><b>Ref Num: </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="45%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</td>
            <td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval( ($item['uom_qty'] !=0) ? $item['uom_qty'] : 1);
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="5%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="45%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="5%" style="border-top:1px solid;"></td>
            <td width="45%" style="border-top:1px solid;"></td>
            <td width="10%" style="border-top:1px solid;"></td>
            <td width="16%" style="border-top:1px solid; "></td>';
            if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          }
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>GST</b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Net Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';

        $pdf .='
       
    
    <br/>
    <br/>
    <div style="font:10px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
    <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount</th>
                <th width="33%">GST</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "cm" || $_GET['jID'] == 13 || $_GET['jID'] == 7) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	
	
        //start CREDIT NOTE pdf *****************************************************************//
		
		
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
            
         '.$company_info.'
            
        </div>
    </div>
    <div class="invoice_heading">
       Credit Note
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
            '.$customer_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>CRN Num: </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Date: </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Date: </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>Ref Num: </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="45%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</td>
            <td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval( ($item['uom_qty'] !=0) ? $item['uom_qty'] : 1);
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="5%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="45%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="5%" style="border-top:1px solid;"></td>
            <td width="45%" style="border-top:1px solid;"></td>
            <td width="10%" style="border-top:1px solid;"></td>
            <td width="16%" style="border-top:1px solid; "></td>';
            if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          }
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>GST</b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Net Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';

        $pdf .='
       
    
    <br/>
    <br/>
    <div style="font:10px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
    <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount</th>
                <th width="33%">GST</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}
else if ($_GET['pdf_type'] == "dm" || $_GET['jID'] == 30 || $_GET['jID'] == 31) {    // to print invoice whether this is sales debit memo or purchase debit memo
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	
	
	
        // Customer Debit Note pdf **********************************************************//
		
		
		
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt=""  height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
          '.$company_info.'
            
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="invoice_heading">
       Debit Note
    </div>
    
    <br/>
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
            '.$customer_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>DBN Num: </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Date: </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Date: </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>Ref Num: </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="45%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</td>
            <td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval( ($item['uom_qty'] !=0) ? $item['uom_qty'] : 1);
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="5%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="45%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="5%" style="border-top:1px solid;"></td>
            <td width="45%" style="border-top:1px solid;"></td>
            <td width="10%" style="border-top:1px solid;"></td>
            <td width="16%" style="border-top:1px solid; "></td>';
            if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          }
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>GST</b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Net Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';

        $pdf .='
       
    
    <br/>
    <br/>
    <div style="font:10px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
	
	<table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount</th>
                <th width="33%">GST</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                </tr>
            ';
        }
        

        $pdf .= '
            
            </table>
	
	<br/>
    <br/>
        <table width = "100%" style"border:1px solid;">
            <tr>
                <td width="100%" style="background-color:#cccccc; font: bold 14px arial; text-align:center;">
                    <i>No signature is required as this is a computer generated form.</i>
                </td>
            </tr>
        </table>
        
    </div>
</div>';
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}
else if ($_GET['pdf_type'] == "pur") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	
        //start PURCHASE pdf **************************************************************//
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
            
           '.$company_info.'
            
        </div>
    </div>
    <div class="invoice_heading">
       Purchase Voucher
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
           '.$customer_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
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
                             <span style="font:12px arial"><b>Pur Due Date : </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>Ref Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="45%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</td>
            <td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval( ($item['uom_qty'] !=0) ? $item['uom_qty'] : 1);
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="5%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="45%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="5%" style="border-top:1px solid;"></td>
            <td width="45%" style="border-top:1px solid;"></td>
            <td width="10%" style="border-top:1px solid;"></td>
            <td width="16%" style="border-top:1px solid; "></td>';
            if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          }
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>GST</b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Net Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';

        $pdf .='
       
    
    <br/>
    <br/>
    <div style="font:10px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
    <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount('.$invoice_main->currencies_code.')</th>
                <th width="33%">GST('.$invoice_main->currencies_code.')</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "pur_self") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        //self billed Invoice pdf **************************************************************//
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:10px arial;"  >
            
          '.$company_info.'
            
        </div>
    </div>
    <div class="invoice_heading">
      Self Billed Invoice
    </div>
    
    <br/>
    <div style="width:100%">
        
        <div style="width:40%; float:left; border:1px solid; height:40px; ">
        <h3>Supplier</h3>
           '.$customer_info.'
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
                                <span style="font:12px arial"><b>Date : </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Date : </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>REF: </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
                '</span>
                             
                             </td>
                             </tr>
                            </table>
                    </td>
                </tr>
            </table>
        </div>
        <div style="clear:both"></div>
        <br/>
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
            <h3>Recipient</h3>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" " valign="top"  >
                <tr>
                    <td style="font:bold 12px arial;">
                         ' . $invoice_main->ship_primary_name . ' 
                    </td>
                </tr>
				
				<tr>
                    <td style="font:12px arial;">
                         '.$invoice_main->ship_address1.', '.$invoice_main->bill_address2.' 
                    </td>
                </tr>
				
				 <tr>
                    <td style="font:12px arial;">
                         '.$invoice_main->ship_city_town.',  '.$invoice_main->bill_state_province.',  '.$invoice_main->bill_postal_code.',  '.$invoice_main->bill_country_code.'
                    </td>
                </tr>
				
				<tr>
                    <td style="font:12px arial;">
                         '.$invoice_main->ship_telephone1.' 
                    </td>
                </tr>
				<tr>
					<td style="font:12px arial;">
                        Attn: '.$invoice_main->ship_contact.' 
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    
    <br/>
    
    
    <br/><br/>
    


    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" ">
        <tr>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td width="30%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST Code</td>
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Unit Price</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
         if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">UOM QTY</td>';
            }
            $pdf .='<td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:1px solid;" >Total Amount</td>
        </tr>';


        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval((($item['uom_qty'] !=0) ? $item['uom_qty'] : 1));
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="30%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="8%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf .='<td width="20%" style="border-bottom: none;border-right: 1px solid; border-left:1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="12%" style="border-top:1px solid;"></td>
            <td width="30%" style="border-top:1px solid;"></td>
            <td width="8%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td>';
        if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
        if(UOM_QTY == 1){
        $pdf .='<td style="border-top:1px solid;"></td>';   
        }
        $pdf .='
            <td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Purchase Tax </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Purchase Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';

        $pdf .='
       
    
    <br/>
    <br/>
    <div style="font:14px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
    <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount('.$invoice_main->currencies_code.')</th>
                <th width="33%">GST('.$invoice_main->currencies_code.')</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}else if ($_GET['pdf_type'] == "puro" || $_GET['jID'] == 4) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	
	
        //start purchase order pdf **********************************************************//
		
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
       <div class="company_area_right" style="font:12px arial;"  >
            
          '.$company_info.'
            
        </div>
    </div>
    <div class="invoice_heading">
       Purchase Order
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
        '.$customer_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>PO Num: </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>PO Date : </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>PO Expiry: </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>Ref Num: </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; border-right:none">No.</td>
            <td width="45%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">GST</td>
            <td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="8%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
        if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid;" >Total Amount</td>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval( ($item['uom_qty'] !=0) ? $item['uom_qty'] : 1);
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="5%" style="border-bottom: none;border-left: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="45%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" width="16%" style="border-bottom: none;border-left: 1px solid;  border-right:1px solid;padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="5%" style="border-top:1px solid;"></td>
            <td width="45%" style="border-top:1px solid;"></td>
            <td width="10%" style="border-top:1px solid;"></td>
            <td width="16%" style="border-top:1px solid; "></td>';
            if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          }
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>GST</b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Net Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';
        
        $pdf .='
       
    
     <br/>
    <br/>
    <div style="font:10px arial">' . $invoice_main->message . '</div>
    <br/>
    <br/>
    <div class="footer_area">
    <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount('.$invoice_main->currencies_code.')</th>
                <th width="33%">GST('.$invoice_main->currencies_code.')</th>
            </tr>
            
            
';
        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                </tr>
            ';
        }
        

        $pdf .= '
            
            </table>
            <br/><br/>
        <table width = "100%" style"border:1px solid;">
            <tr>
                <td width="100%" style="background-color:#cccccc; font: bold 12px arial; text-align:center;">
                    <i>No signature is required as this is a computer generated form.</i>
                </td>
            </tr>
        </table>
        
    </div>
</div>';
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "dopdf" || $_GET['jID'] == 10) {
    
	
	//start Delivery ORDERS pdf ******************************************//
	
	
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
            '.$company_info.'
            
        </div>
    </div>
    <div class="invoice_heading">
       Delivery Order
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
           '.$shipping_info.'
        </div>
        <div style="width:30%; float:right; height:90px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>DO Number: </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Date: </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Due Date: </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>Ref Num: </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >QTY</td>';
            if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid; ">UOM QTY</td>';
            }
        $pdf .='</tr>';

     
        $i = 1;
        foreach ($item_val['item'] as $item) {
            $pdf .='
    
        <tr>
            <td valign="top" align="center" width="8%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="80%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td valign="top" align="center" width="12%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; border-right:1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
        $pdf .='</tr>
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
            
            <div style="font:10px arial">' . $invoice_main->message . '</div>
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
    }

    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "rqpuro" || $_GET['jID'] == 3) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	
        //start request purchase order pdf **************************************//
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:14px arial;"  >
            
           '.$company_info.'
            
        </div>
    </div>
    <div class="invoice_heading">
       Request for Quote
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
            '.$customer_info.'
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
                             <span style="font:12px arial"><b>Due Pur Date : </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>PO Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="38%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>';
            if(UOM == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">UOM QTY</td>';
            }
            $pdf .='<td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; " >Total Amount</td>
        </tr>';


        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            if(UOM_QTY == 1){
                $u_price = floatval($u_price_item) / floatval($item['qty']) / floatval((($item['uom_qty'] !=0) ? $item['uom_qty'] : 1));
            }else{
                $u_price = floatval($u_price_item) / floatval($item['qty']);
            }
            $total_item_amount = $total_item_amount + $u_price_item;
            $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="38%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
  $pdf .='<td width="20%" style="border-bottom: none;border-left: 1px solid; border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="12%" style="border-top:1px solid;"></td>
            <td width="38%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid; border-right:none;"></td>';
        if(UOM == 1){
            $pdf .='<td style="border-top:1px solid;"></td>'; 
         }
          if(UOM_QTY == 1){
            $pdf .='<td style="border-top:1px solid;"></td>';   
          } 
            $pdf .='<td colspan="2" style="border:1px solid; border-left:1px solid;">
                 <table width="100%" style="">
                    <tr>
                        
                        <td width="50%" style=" font:12px arial;"><b>Sub Total</b></td>
                        <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
        if (in_array($item_val['dsc'], $item_val)) {
            //$discount = $item_val['dsc']['debit_amount'] + $item_val['dsc']['credit_amount'];
            $discount = $invoice_main->discount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Discount </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['frt'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $freight = $invoice_main->freight;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Freight </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['tax'], $item_val)) {
            //$freight = $item_val['frt']['debit_amount'] + $item_val['frt']['credit_amount'];
            $tax = $invoice_main->sales_tax;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Purchase Tax </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        if (in_array($item_val['ttl'], $item_val)) {
            //$total = $item_val['ttl']['debit_amount'] + $item_val['ttl']['credit_amount'];
            $total = $invoice_main->total_amount;
            $pdf .='<tr>
                            
                            <td width="50%" style=" font:12px arial;"><b>Req Quote Total </b></td>
                            <td width="50%" style=" font:12px arial;">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        </tr>';
        }
        $pdf .='</table></td>
       </tr>
    </table>';
        
        $pdf .='
       
    
    <br/>
    <br/>
    <div style="font:14px arial">' . $invoice_main->message . '</div>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "rec" || $_GET['jID'] == 18) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
       

	   //start invoice pdf
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" width="150" height="150" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:14px arial;"  >
            
          '.$company_info.'
            
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="invoice_heading">
       Customer Receipt
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
           '.$customer_info.'
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
                             <span style="font:12px arial"><b>Due Pur Date : </b></span></td><td> <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>PO Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Invoice #</td>
                        <td width="26%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Amount Due</td>
            <td width="34%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Amount Received</td>
        </tr>';

        //$last_item = array_pop($invoice_item);
//array_pop($invoice_item);
//array_pop($invoice_item);
        $i = 1;
        $total_amount_due = "";
        $total_amount_recv = "";
        foreach ($invoice_item as $item) {
            if ($item['amount_paid'] == "") {
                continue;
            }
            $total_amount_due = $total_amount_due + str_replace(",", "", $item['total_amount']);
            $total_amount_recv = $total_amount_recv + str_replace(",", "", $item['amount_paid']);
            $u_price = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            $u_price = floatval($u_price) / floatval($item['qty']);
            $pdf .='
    
        <tr>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['purchase_invoice_id'] . '</td>
                        <td width="26%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $item['total_amount']), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width="34%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $item['amount_paid']), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="10%" style="border-right: 1px solid; border-left: 1px solid;"></td>
            <td width="10%" style="border-right: 1px solid;"></td>
            
            <td width="26%" style="border: 1px solid;  border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;"><b>Total Due : </b>' . $currencies->format_full($total_amount_due, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width="34%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;"><b>Total Received : </b>' . $currencies->format_full($total_amount_recv, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">' . $invoice_main->message . '</div>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "ReM" || $_GET['jID'] == 34) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        
		
		//start invoice pdf *********************************
		
		
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt=""  height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
            
           '.$company_info.'
            
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="invoice_heading">
       Received Money Note
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
           '.$customer_info.'
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
                             <span style="font:12px arial"><b>Due Pur Date : </b></span></td><td> <span style="font:12px arial">' . $payment_due_date . '</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>PO Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">GL Account</td>
            <td width="26%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Tax</td>
            <td width="34%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Amount</td>
        </tr>';

        //$last_item = array_pop($invoice_item);
//array_pop($invoice_item);
//array_pop($invoice_item);
        $i = 1;
        $actual_tax = 0;
        $taxx = 0;
        $taxx1 = 0;
        $tax_rates = ord_calculate_tax_drop_down("b");
        if ($invoice_main->tax_inclusive == '1') {
            $total_amount_recv = $invoice_main->total_amount;
        } else {
            $total_amount_recv = $invoice_main->total_amount;
        }
        foreach ($invoice_item as $item) {
            if ($item['gl_type'] == 'tax') {
                continue;
            }
            foreach ($tax_rates as $tax) {
                if ($tax['text'] == $item['description_short']) {
                    $actual_tax = $tax['rate'];
                }
            }

            if ($item['credit_amount'] > 0) {
                $taxx1 += $item['credit_amount'] * ($actual_tax / 100);
                $taxx = $item['credit_amount'] * ($actual_tax / 100);
                if ($invoice_main->tax_inclusive == '1') {
                    $amount = $item['credit_amount'] + $taxx;
                } else {
                    $amount = $item['credit_amount'];
                    $total_amount_recv = $invoice_main->total_amount;
                }
            } else {
                continue;
            }
            $pdf .='
    
        <tr>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['gl_account'] . '</td>
            <td width="26%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
            <td width="34%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $amount), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr><td width="12%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td>
            <td colspan="2" style="border-top:1px solid;"><b>Total Received : </b>' . $currencies->format_full($total_amount_recv, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
           </tr>  
           <tr><td width="12%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td> 
            <td colspan="2" style="border-top:1px solid;"><b>GST : </b>' . $currencies->format_full($invoice_main->sales_tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:12px arial">' . $invoice_main->message . '</div>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "SM" || $_GET['jID'] == 35) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        //start invoice pdf
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" width="150" height="150" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:14px arial;"  >
            
          '.$company_info.'
            
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="invoice_heading">
       Payment Voucher
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
           '.$customer_info.'
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
                             <span style="font:12px arial"><b>Due Pur Date : </b></span></td><td> <span style="font:12px arial">' . $payment_due_date . '</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>PO Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">GL Account</td>
            <td width="26%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Tax</td>
            <td width="34%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Amount</td>
        </tr>';

        //$last_item = array_pop($invoice_item);
//array_pop($invoice_item);
//array_pop($invoice_item);
        $i = 1;
        $actual_tax = 0;
        $taxx = 0;
        $taxx1 = 0;
        $tax_rates = ord_calculate_tax_drop_down("b");
        if ($invoice_main->tax_inclusive == '1') {
            $total_amount_recv = $invoice_main->total_amount;
        } else {
            $total_amount_recv = $invoice_main->total_amount;
        }
        foreach ($invoice_item as $item) {
            if ($item['gl_type'] == 'tax') {
                continue;
            }
            foreach ($tax_rates as $tax) {
                if ($tax['text'] == $item['description_short']) {
                    $actual_tax = $tax['rate'];
                }
            }

            if ($item['debit_amount'] > 0) {
                $taxx1 += $item['debit_amount'] * ($actual_tax / 100);
                $taxx = $item['debit_amount'] * ($actual_tax / 100);
                if ($invoice_main->tax_inclusive == '1') {
                    $amount = $item['debit_amount'] + $taxx;
                } else {
                    $amount = $item['debit_amount'];
                    $total_amount_recv = $invoice_main->total_amount;
                }
            } else {
                continue;
            }
            $pdf .='
    
        <tr>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="20%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['gl_account'] . '</td>
            <td width="26%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
                
            <td width="34%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $amount), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr><td width="12%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td>
            <td colspan="2" style="border-top:1px solid;"><b>Total Received : </b>' . $currencies->format_full($total_amount_recv, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
           </tr>  
           <tr><td width="12%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td>
            <td width="20%" style="border-top:1px solid;"></td> 
            <td colspan="2" style="border-top:1px solid;"><b>GST : </b>' . $currencies->format_full($invoice_main->sales_tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr> 
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">' . $invoice_main->message . '</div>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "pay_bill" || $_GET['jID'] == 20) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        //start invoice pdf
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:12px arial;"  >
            
           '.$company_info.'
            
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="invoice_heading">
       Payment Voucher
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:60%; float:left; border:1px solid; height:90px; ">
            '.$customer_info.'
        </div>
        <div style="width:30%; float:right; height:80px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Pur Num: </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Pur Date: </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>Ref Num : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purch_order_id .
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
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Invoice #</td>
                        <td width="26%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Amount Due</td>
            <td width="34%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Amount Received</td>
        </tr>';

        $i = 1;
        $total_amount_due = "";
        $total_amount_recv = "";
        foreach ($invoice_item as $item) {
            if ($item['amount_paid'] == "") {
                continue;
            }
            $total_amount_due = $total_amount_due + str_replace(",", "", $item['total_amount']);
            $total_amount_recv = $total_amount_recv + str_replace(",", "", $item['amount_paid']);
            $u_price = floatval($item['debit_amount']) + floatval($item['credit_amount']);
            $u_price = floatval($u_price) / floatval($item['qty']);
            $pdf .='
    
        <tr>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['purchase_invoice_id'] . '</td>
                        <td width="26%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $item['total_amount']), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width="34%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $item['amount_paid']), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td width="10%" style="border-right: 1px solid; border-left: 1px solid;"></td>
            <td width="10%" style="border-right: 1px solid;"></td>
                        <td width="26%" style="border: 1px solid;  border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;"><b>Total Due : </b>' . $currencies->format_full($total_amount_due, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width="34%" style="border: 1px solid; border-left:none; border-top: none; border-bottom:none; padding:5px; font:12px arial;"><b>Total Received : </b>' . $currencies->format_full($total_amount_recv, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>  
    </table>
    
    <br/>
    <br/>
    <div style="font:14px arial">' . $invoice_main->message . '</div>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} else if ($_GET['pdf_type'] == "adjustment") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        foreach ($branchs as $branche) {
            if($invoice_main->store_id==$branche['id']){
                $store = $branche['text'];
                break;
            }
        }

        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:14px arial;"  >
            
           '.$company_info.'
            
        </div>

    </div>
    <div class="invoice_heading">
        Inventory Adjustment
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
           '.$customer_info.'
        </div>
        <div style="width:40%; float:right; height:80px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Store Id : </b></span> 
                            </td><td><span style="font:12px arial">' .
                $store
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Reason For Adjustment : </b></span> 
                            </td><td><span style="font:12px arial">' .
                $item_val['ttl']['description']
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Adjustment Account : </b></span> 
                            </td><td><span style="font:12px arial">' .
                $item_val['ttl']['gl_account']
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Post Date : </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($item_val['ttl']['post_date']))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Reference : </b></span></td><td> <span style="font:12px arial">'
                . $invoice_main->purchase_invoice_id .
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
            <td width="30%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Item Name</td>
            <td width="30%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Description</td>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Qty in Stock</td>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Adj Qty</td>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Item Cost</td>';

        $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; border-right: 1px solid;text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Balance</td>';

        $pdf .='</tr>';


        $i = 1;
        foreach ($adjustment as $item) {
            $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="25%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['sku'] . '</td>
            <td width="25%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['remaining'] . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full( $item['unit_cost'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>';

            $pdf .='<td width="10%" style="border-bottom: none;border-left: 1px solid;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['balance'] . '</td>';


            $pdf .='</tr>
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
            
            <div style="font:14px arial">' . $invoice_main->message . '</div>
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
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
//end Delivery Order pdf
//Start Invoice *******************************************************************//
//start invoice pdf
} else if ($_GET['pdf_type'] == "transfer") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        foreach ($branchs as $branche) {
            if($invoice_main->store_id==$branche['id']){
                $transfer_from = $branche['text'];
                break;
            }
        }
          foreach ($branchs as $branche) {
            if($transfer_store_id==$branche['id']){
                $transfer_to = $branche['text'];
                break;
            }
        }
        $pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
        </div>
        <div class="company_area_right" style="font:14px arial;"  >
            
            '.$company_info.'
            
        </div>

    </div>
    <div class="invoice_heading">
        Inventory Transfer
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
        '.$customer_info.'
        </div>
        <div style="width:40%; float:right; height:80px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Reference Number : </b></span> 
                            </td><td><span style="font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                       
                             
                             <tr>
                                <td>
                         <span style="font:12px arial"><b>Transfer From : </b></span> 
                            </td><td><span style="font:12px arial">' .
                $transfer_from
                . '</span>
                            </td>
                            </tr>
                                 <tr>
                                <td>
                         <span style="font:12px arial"><b>Transfer To : </b></span> 
                            </td><td><span style="font:12px arial">' .
                $transfer_to
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Transfer Account : </b></span> 
                            </td><td><span style="font:12px arial">' .
                $item_val['ttl']['gl_account']
                . '</span>
                            </td>
                            </tr>

                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Post Date : </b></span></td><td> <span style="font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Reason For Transfer : </b></span></td><td> <span style="font:12px arial">'
                . $item_val['ttl']['description'] .
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

            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Quantity</td>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Qty in Stock</td>';

        $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Balance</td>';
        $pdf .='<td width="30%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Item Name</td>
            <td width="30%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Description</td>';

        $pdf .='</tr>';


        $i = 1;
        foreach ($transfer as $item) {
            $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="25%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>
            <td width="25%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['remaining'] . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['balance'] . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['sku'] . '</td>';

            $pdf .='<td width = "10%" style = "border-bottom: none;border-left: 1px solid;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>';


            $pdf .='</tr>
            ';
            $i++;
        }
        $pdf .='</table>

            <br/>
            <br/>
            <br/>
            <div class = "footer_area">
            <div style = "width:70%;">
            <span style = "font:14px Arial;">
            <i>Received in good order by :</i>
            </span>
            <br/>

            <div style = "font:14px arial">' . $invoice_main->message . '</div>
            <br/>
            <br/>
            ........................................................
            <br>
            <span style = "font:12px Arial;">
            Company Stamp and Authorised Signature
            </span>
            </div>

            </div>
            </div>';
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
//end Delivery Order pdf
//Start Invoice *******************************************************************//
//start invoice pdf
} else if ($_GET['pdf_type'] == "genJournal" || $_GET['jID'] == 2) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        //start invoice pdf
        $pdf = '<div class = "pdf_area">

            <div class = "company_area">
            <div class = "company_area_left">
            <img alt = "" width = "150" height = "150" src = "user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
            </div>
            <div class = "company_area_right" style = "font:14px arial;" >

            '.$company_info.'


            </div>
            <div class = "clear"></div>
            </div>
            <div class = "invoice_heading">
            Journal Voucher
            </div>

            <br/>
            <div style = "width:100%">
            <div style = "width:40%; float:left; border:1px solid; height:80px; ">
            '.$customer_info.'
            </div>
            <div style = "width:40%; float:right; height:80px; border:1px solid;">
            <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" style = "" valign = "top" >
            <tr>
            <td style = "text-align:left;">
            <table border = "0" cellspacing = "0" cellpadding = "0">
            <tr>
            <td>
            <span style = "font:12px arial"><b>Reference Number : </b></span>
            </td><td><span style = "font:12px arial">' .
                $invoice_main->purchase_invoice_id
                . '</span>
            </td>
            </tr>
            <tr>
            <td>
            <span style = "font:12px arial"><b>Post Date : </b></span></td><td> <span style = "font:12px arial">' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
            </td></tr>
            <tr><td>


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



            <table width = "100%" border = "0" cellspacing = "0" cellpadding = "0" style = " border-bottom:1px solid;">
            <tr>
            <td width = "10%" style = "background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td width = "10%" style = "background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width = "20%" style = "background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">GL Account</td>
            <td width = "26%" style = "background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Tax</td>
            <td width = "34%" style = "background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Debit Amount</td>
            <td width = "34%" style = "background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Credit Amount</td>
            </tr>';

        //$last_item = array_pop($invoice_item);
//array_pop($invoice_item);
//array_pop($invoice_item);
        $i = 1;
        $actual_tax = 0;
        $taxx = 0;
        $taxx1 = 0;
        $tax_rates = ord_calculate_tax_drop_down("b");
        if ($invoice_main->tax_inclusive == '1') {
            $total_amount_recv = $invoice_main->total_amount - $invoice_main->sales_tax;
        } else {
            $total_amount_recv = $invoice_main->total_amount;
        }
        foreach ($invoice_item as $item) {
            if ($item['gl_type'] == 'tax') {
                continue;
            }
            foreach ($tax_rates as $tax) {
                if ($tax['text'] == $item['description_short']) {
                    $actual_tax = $tax['rate'];
                }
            }

            if ($item['debit_amount'] > 0) {
                $taxx1 += $item['debit_amount'] * ($actual_tax / 100);
                $taxx = $item['debit_amount'] * ($actual_tax / 100);
                if ($invoice_main->tax_inclusive == '1') {
                    $amount = $item['debit_amount'] + $taxx;
                } else {
                    $amount = $item['debit_amount'];
                    $total_amount_recv = $invoice_main->total_amount + $taxx1;
                }
            } else {
                $taxx2 += $item['credit_amount'] * ($actual_tax / 100);
                $taxx = $item['credit_amount'] * ($actual_tax / 100);
                if ($invoice_main->tax_inclusive == '1') {
                    $amount = $item['credit_amount'] + $taxx;
                } else {
                    $amount = $item['credit_amount'];
                    $total_amount_recv = $invoice_main->total_amount + $taxx2;
                }
            }
            $pdf .='

            <tr>
            <td width = "10%" style = "border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width = "10%" style = "border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width = "20%" style = "border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['gl_account'] . '</td>
            <td width = "26%" style = "border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>

            <td width = "34%" style = "border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $item['debit_amount']), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width = "34%" style = "border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $item['credit_amount']), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            </tr>
            ';
            $i++;
        }

        $pdf .='
            <tr><td width = "12%" style = "border-top:1px solid;"></td>
            <td width = "20%" style = "border-top:1px solid;"></td>
            <td width = "20%" style = "border-top:1px solid;"></td>
            <td width = "20%" style = "border-top:1px solid;"></td>
            <td colspan = "2" style = "border-top:1px solid;"><b>Totals : </b>' . $currencies->format_full($total_amount_recv, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            </tr>
            <tr><td width = "12%" style = "border-top:1px solid;"></td>
            <td width = "20%" style = "border-top:1px solid;"></td>
            <td width = "20%" style = "border-top:1px solid;"></td>
            <td width = "20%" style = "border-top:1px solid;"></td>
            <td colspan = "2" style = "border-top:1px solid;"><b>GST : </b>' . $currencies->format_full($invoice_main->sales_tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            </tr>
            </table>

            <br/>
            <br/>
            <div style = "font:14px arial">' . $invoice_main->message . '</div>
            <br/>
            <br/>
            <div class = "footer_area">
            <table width = "100%" style"border:1px solid;">
            <tr>
            <td width = "100%" style = "background-color:#cccccc; font: bold 14px arial; text-align:center;">
            <i>No signature is required as this is a computer generated form.</i>
            </td>
            </tr>
            </table>

            </div>
            </div>';
    }
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}






if ($_GET['action'] == "email") {

    if ($_GET['jID'] == 9) {
        $file_name = "quotation.pdf";
    } else if ($_GET['jID'] == 12) {
        $file_name = "invoice.pdf";
    } else if ($_GET['jID'] == 10) {
        $file_name = "sales_order.pdf";
    } else if ($_GET['jID'] == 13) {
        $file_name = "credit_memo.pdf";
    } else if ($_GET['jID'] == 4) {
        $file_name = "purchase_order.pdf";
    } else if ($_GET['jID'] == 7) {
        $file_name = "credit_memo.pdf";
    } else if ($_GET['jID'] == 3) {
        $file_name = "request_for_quote.pdf";
    }

    $temp_file = DIR_FS_MY_FILES . $_SESSION['company'] . '/temp/' . $file_name;
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
        <?php

    }
} else {
    ob_clean();
    $mpdf->Output();
    exit;
}
?>