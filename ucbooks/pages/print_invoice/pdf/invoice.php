<?php
$pdf .= '
        <div class="invoice_heading">
          TAX INVOICE
        </div>
    
    <br/>
    <div style="width:100%;padding-bottom:10px">'.billto_shipto(TEMPLATE_SHIPTO_BOX, $customer_info, $shipping_info).invoice($invoice_main, $payment_due_date).'</div>

    <table width="100%" cellspacing="0" class="border">
        <tr bgcolor="#cccccc">
            <th>No.</th>
            '.item_code_col().'
			      <th class="item_left">Description</th>
            <th>GST</th>
            <th>Unit Price ('.$invoice_main->currencies_code.')</th>
            <th>Qty</th>';
            if(UOM == 1){
                $pdf .='<th>UOM</th>';
            }
            if(UOM_QTY == 1){
                $pdf .='<th>UOM QTY</th>';
            }
    
          $pdf .= discount_col($discount_col);
         
            $pdf .='<th>Total ('.$invoice_main->currencies_code.')</th>
        </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
          
            if(UOM_QTY == 1){
                  if($item['disc_percent'] > 0){
                    $u_price = (floatval($u_price_item) / floatval($item['qty'])) / (floatval($item['uom_qty']) !=0 ? $item['uom_qty'] : 1) + floatval($item['disc_percent']);
                  }else{
                    $u_price = (floatval($u_price_item) + floatval($item['discval'])) / (floatval($item['uom_qty']) !=0 ? $item['uom_qty'] : 1) / floatval($item['qty']);
                  }
              
              }else{
                if($item['disc_percent'] > 0){
                    $u_price = (floatval($u_price_item) / floatval($item['qty'])) + floatval($item['disc_percent']);
                }else{
                    $u_price = (floatval($u_price_item) + floatval($item['discval'])) / floatval($item['qty']);
                }
              }
            $total_item_amount = $total_item_amount + $u_price_item;
            
            $percent = $item['disc_percent'] > 0 ? $item['discval'].'%' : $currencies->format_full($item['discval'], true, $invoice_main->currencies_code, $invoice_main->currencies_value);
            
            $pdf .='
    
        <tr>
            <td valign="top" align="center" class="item_first">' . $i . '</td>
			      '.item_code_col2($item['sku']).'
			      <td valign="top" class="item_left" width="35%">' . $item['description'] . '</td>
            <td valign="top" align="center" class="item">' . $item['description_short'] . '</td>
            <td valign="top" align="center" class="item">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" class="item">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td valign="top" align="center" class="item">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td valign="top" align="center" class="item">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            //discount
          
            $pdf .= discount_item($discount_col,$percent);
            
            $pdf.='<td valign="top" class="item">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td></td>
			      '.add_col(TEMPLATE_ITEM_CODE).'
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            '.add_col($discount_col).'
            <td></td>';
            if(UOM == 1){
            $pdf .='<td></td>'; 
             }
              if(UOM_QTY == 1){
                $pdf .='<td></td>';   
              }
            $pdf .='</tr></table>';


            $pdf .='<div style="text-align:right">
                 <table width="100%">
                    <tr>
                        <td align="right" width="85%"><b>Sub Total</b></td>
                        <td align="right">' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                    </tr>';
                if (in_array($item_val['dsc'], $item_val)) {
                    $discount = $invoice_main->discount;
                    $pdf .='<tr>
                                <td align="right"><b>Discount </b></td>
                                <td align="right">' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                            </tr>';
                }
                if (in_array($item_val['frt'], $item_val)) {
                    $freight = $invoice_main->freight;
                    $pdf .='<tr>
                                <td align="right" align="right"><b>Freight </b></td>
                                <td align="right">' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                            </tr>';
                }
                //if (in_array($item_val['tax'], $item_val)) {
                    $tax = $invoice_main->sales_tax;
                    $pdf .='<tr>
                                <td align="right"><b>GST @ 6%</b></td>
                                <td align="right">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                            </tr>';
                //}
                if (in_array($item_val['ttl'], $item_val)) {
                    $total = $invoice_main->total_amount;
                    $pdf .='<tr> 
                                <td align="right"><b>Net Total </b></td>
                                <td align="right">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                            </tr>';
                }
                $pdf .='</table>
                </div>';

        $pdf .= add_line('25%');

?>