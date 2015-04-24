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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border: 1px solid;">
        <tr>
            <td width="35%" style="border-bottom: none; background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid;" >Sold To</td>
            <td width="35%" style="border-bottom: none; background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid;" >Ship To</td>
            <td width="30%" style="border-bottom: none; background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid;" >Sales/Invoice</td>
        </tr>
        <tr>
            <td width="35%" style="border-right: 1px solid;  padding: 5px; font:12px arial " valign="top" >
                ' .
        $invoice_main->bill_primary_name
        . '<br/>' .
        $invoice_main->bill_contact
        . '<br/>' .
        $invoice_main->bill_address1
        . '<br/>' .
        $invoice_main->bill_address2
        . '<br/>' .
        $invoice_main->bill_city_town
        . ' ' .
        $invoice_main->bill_state_province
        . ' ' .
        $invoice_main->bill_postal_code
        . '<br/>' .
        $invoice_main->bill_country_code
        . '<br/>' .
        $invoice_main->bill_telephone1
        . '<br/>' .
        $invoice_main->bill_email
        . '
            </td>
            <td width="35%" style="border-right: 1px solid;  padding: 5px; font:12px arial; " valign="top">' .
        $invoice_main->ship_primary_name
        . '<br/>' .
        $invoice_main->ship_contact
        . '<br/>' .
        $invoice_main->ship_address1
        . '<br/>' .
        $invoice_main->ship_address2
        . '<br/>' .
        $invoice_main->ship_city_town
        . ' ' .
        $invoice_main->ship_state_province
        . ' ' .
        $invoice_main->ship_postal_code
        . '<br/>' .
        $invoice_main->ship_country_code
        . '<br/>' .
        $invoice_main->ship_telephone1
        . '<br/>' .
        $invoice_main->ship_email
        . '
            </td>
            <td width="30%" style="border-right: 1px solid;  padding: 5px; font:12px arial"  valign="top" > 
                <span style="font:12px arial"><b>INV Number : </b></span>' .
        $invoice_main->purchase_invoice_id
        . '<br/>
                <span style="font:12px arial"><b> INV Date : </b></span>' .
        date("d-m-Y", strtotime($invoice_main->post_date))
        . '<br/>
            </td>
        </tr>
    </table>
    
    <br/><br/>
    


    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border-bottom:none">
        <tr>
            <td width="12%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">SKU</td>
            <td width="38%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Qty</td>
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Unit Price</td>
            <td width="20%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Extension</td>
        </tr>';

$last_item = array_pop($invoice_item);
foreach ($invoice_item as $item) {
    $pdf .='
    
        <tr>
            <td width="12%" style="border-bottom: 1px solid;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $item['sku'] . '</td>
            <td width="38%" style="border-bottom: 1px solid;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="10%" style="border-bottom: 1px solid;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>
            <td width="20%" style="border-bottom: 1px solid;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'] / $item['qty'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td width="20%" style="border-bottom: 1px solid; border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
}
$pdf .='<tr>
            <td width="12%"></td>
            <td width="38%"></td>
            <td width="10%" style="border-right: 1px solid;"></td>
            <td width="20%" style="border: 1px solid;  border-left:none; border-top: none; padding:5px; font:12px arial;"><b>Subtotal</b></td>
            <td width="20%" style="border: 1px solid; border-left:none; border-top: none; padding:5px; font:12px arial;">' . $currencies->format_full($last_item['debit_amount'] + $last_item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr> </table>
    
    <div class="footer_area">
    </div>
</div>';


$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($pdf);

if ($_GET['action'] == "email") {

$temp_file = DIR_FS_MY_FILES . $_SESSION['company'] . '/temp/ionvoice.pdf';
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


<!--<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" width="150" height="150" src="user_company/user_file/ucountingnew_demo_2/ucform/images/company_logo/<?php echo $logo; ?>" />
        </div>
        <div class="company_area_right">
            <span class="company_name"><?php echo $company_name; ?></span>
            <br/>
            <span class="company_addrss"><?php echo $company_address ?></span>
            <br/>
            <span class="company_addrss"> <?php echo $phone ?>  </span>
            <br/>
            <span class="company_addrss"> <?php echo $email ?> </span>
            <br/>
            <span class="company_addrss"> <?php echo $web ?>  </span>
            
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="invoice_heading">
        Sales/Invoice
    </div>
    <div class="adress_area">
        <div class="address_left">
            <div class="address_heading_text">Sold To </div>
            <div class="address_content">
<?php
echo $invoice_main->bill_primary_name;
echo "<br/>";
echo $invoice_main->bill_contact;
echo "<br/>";
echo $invoice_main->bill_address1;
echo "<br/>";
echo $invoice_main->bill_address2;
echo "<br/>";
echo $invoice_main->bill_city_town;
echo " ";
echo $invoice_main->bill_state_province;
echo " ";
echo $invoice_main->bill_postal_code;
echo "<br/>";
echo $invoice_main->bill_country_code;
echo "<br/>";
echo $invoice_main->bill_telephone1;
echo "<br/>";
echo $invoice_main->bill_email;
?>
            </div>
        </div>
        <div class="address_middle">
            <div class="address_heading_text">Ship To </div>
            <div class="address_content">
<?php
echo $invoice_main->ship_primary_name;
echo "<br/>";
echo $invoice_main->ship_contact;
echo "<br/>";
echo $invoice_main->ship_address1;
echo "<br/>";
echo $invoice_main->ship_address2;
echo "<br/>";
echo $invoice_main->ship_city_town;
echo " ";
echo $invoice_main->ship_state_province;
echo " ";
echo $invoice_main->ship_postal_code;
echo "<br/>";
echo $invoice_main->ship_country_code;
echo "<br/>";
echo $invoice_main->ship_telephone1;
echo "<br/>";
echo $invoice_main->ship_email;
?>
            </div>
        </div>
        <div class="address_right">
            <div class="address_heading_text">Sales/Invoice</div>
            <div class="address_content">
                <span><b>INV Number : </b></span>
<?php echo $invoice_main->purchase_invoice_id; ?>
                <br/>
                <span><b> INV Date : </b></span>
<?php echo date("d-m-Y", strtotime($invoice_main->post_date)); ?>
                <br/>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <br/><br/>
    <div class="invoice_item_area">
        <div class="invocie_item_header">
            <div class="invocie_item_header_sku">SKU</div>
            <div class="invocie_item_header_description">Description</div>
            <div class="invocie_item_header_qty">Qty</div>
            <div class="invocie_item_header_nutprice">Unit Price</div>
            <div class="invocie_item_header_extension">Extension</div>
        </div>
<?php
$last_item = array_pop($invoice_item);
foreach ($invoice_item as $item) {
    ?>
                <div class="invocie_item">
                    <div class="invocie_item_sku"><?php echo $item['sku']; ?></div>
                    <div class="invocie_item_description"><?php echo $item['description']; ?></div>
                    <div class="invocie_item_qty"><?php echo $item['qty']; ?></div>
                    <div class="invocie_item_nutprice"><?php echo $currencies->format_full($item['debit_amount'] + $item['credit_amount'] / $item['qty'], true, $invoice_main->currencies_code, $invoice_main->currencies_value); ?></div>
                    <div class="invocie_item_extension"><?php echo $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value); ?></div>
                    <div class="clear"></div>
                </div>
<?php } ?>
       
    </div>
    <div class="clear"></div>
    
    <div class="invocie_sub">
        
        <div class="invocie_sub_right">
            <div class="sub_total_left">
                Subtotal
            </div>
            <div class="sub_total_right">
<?php echo $currencies->format_full($last_item['debit_amount'] + $last_item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value); ?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="footer_area">
    </div>
</div>-->