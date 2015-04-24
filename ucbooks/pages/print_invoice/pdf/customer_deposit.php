<?php

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

?>