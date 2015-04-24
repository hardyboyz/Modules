<?php

$pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_left">
            <img alt="" height="90" src="user_company/' . $company_code . '/' . $_SESSION['company'] . '/ucform/images/company_logo/' . $logo . '" />
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
                         <span style="font:12px arial"><b>Pur Number</b></span> 
                            </td><td><span style="font:12px arial">: ' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Pur Date</b></span></td><td> <span style="font:12px arial">: ' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr>
                            <tr><td>
                             <span style="font:12px arial"><b>REF Num</b></span></td><td> <span style="font:12px arial">: '
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
            <td width="40%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid; ">Description</td>
            <td width="10%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">GL Account</td>
            <td width="16%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Tax</td>
            <td width="24%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Amount</td>
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
            <td width="10%" align="center" style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td width="30%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td width="10%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['gl_account'] . '</td>
            <td width="26%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description_short'] . '</td>
                
            <td width="24%" style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full(str_replace(",", "", $amount), true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
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
    <div style="font:14px arial">' . $message . '</div>
    <br/>
    <br/>
    <div class="footer_area">

        
    </div>
</div>';
    
?>