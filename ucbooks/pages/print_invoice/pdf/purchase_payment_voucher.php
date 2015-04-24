<?php

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
   
</div>';


?>