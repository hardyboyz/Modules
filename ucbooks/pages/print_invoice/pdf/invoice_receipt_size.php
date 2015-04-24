<?php
$mpdf = new mPDF('utf-8', array(100,236));
$pdf = '<div class="pdf_area">
    
    <div class="company_area">
        <div class="company_area_right" style="font:11px arial;">
            
           '.$company_info.'
            
        </div>
     
    </div>
    <div class="invoice_heading">
      TAX INVOICE
    </div>
    <div style="width:100%">
        <div style="width:100%; text-align:center; border-bottom:solid 1px #111111; border-top:solid 1px #111111;font:11px arial">
            '.$invoice_main->bill_primary_name.'
        </div>
		
        <div style="width:100%; float:left; border-bottom:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top">
                <tr>
                    <td style="text-align:center;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr><td><span style="font:11px arial">INV No </span></td><td><span style="font:10px arial">: ' .$invoice_main->purchase_invoice_id. '</span></td></tr>
                            <tr><td><span style="font:11px arial">INV Date  </span></td><td><span style="font:10px arial">: ' .date("d-m-Y", strtotime($invoice_main->post_date)). '</span></td></tr>
                            <!--<tr><td><span style="font:11px arial">Due Date  </span></td><td><span style="font:10px arial">: '.$payment_due_date.'</span></td></tr>
                            <tr><td><span style="font:11px arial">PO Num  </span></td><td><span style="font:10px arial">: '. $invoice_main->purch_order_id .'</span></td>-->
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: none">
        <tr>
            <th>No.</th>
            <th>Description</th>
            <th>GST</th>
            <th>Price</th>
            <th>Qty</th>';
            if(UOM_QTY == 1){
                $pdf .='<th>UOM QTY</th>';
            }
            $pdf .='<th>Total</th>
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
            <td valign="top" align="center" style="padding: 5px;  font:11px arial">' . $i . '</td>
			      <td valign="top" style="padding: 5px; font:11px arial">' . $item['description'] . '</td>
            <td valign="top" style="padding: 5px; font:11px arial">' . $item['description_short'] . '</td>
            <td valign="top" align="center" style="padding: 5px; font:11px arial">' . $currencies->format_full($u_price, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
            <td valign="top" align="center" style="padding: 5px; font:11px arial">' . $item['qty'] . '</td>';
            if(UOM_QTY == 1){
                $pdf .='<td valign="top" align="center" style="font:11px arial">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
            $pdf.='<td valign="top" align="center" style="padding: 5px; font:11px arial">' . $currencies->format_full($item['debit_amount'] + $item['credit_amount'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td></td>
			      <td></td>
            <td></td>
            <td></td>';
            if(UOM_QTY == 1){
                $pdf .='<td></td>';   
              }
            $pdf .='</tr></table>';


            $pdf .='<div style="text-align:right">
                 <table width="100%">
                    <tr>
                        <td align="right" width="70%"><b>Sub Total</b></td>
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
                if (in_array($item_val['tax'], $item_val)) {
                    $tax = $invoice_main->sales_tax;
                    $pdf .='<tr>
                                <td align="right"><b>GST @ 6%</b></td>
                                <td align="right">' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                            </tr>';
                }
                if (in_array($item_val['ttl'], $item_val)) {
                    $total = $invoice_main->total_amount;
                    $pdf .='<tr> 
                                <td align="right"><b>Net Total </b></td>
                                <td align="right">' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                            </tr>';
                }
                $pdf .='</table>
                </div>';
        add_line();
        $pdf .='
    
    <div class="footer_area">
        
        <table width="100%">
            <tr>
                <th width="33%" style="font-size:11px">GST Summary</th>
                <th width="33%" style="font-size:11px">Amount</th>
                <th width="33%" style="font-size:11px">GST</th>
            </tr>
            ';
        foreach($item_val['total_price_on_tax'] as $key => $val){
	$GSTSummary = strlen($item_val['total_rate_percentage'][$key]) > 0 ? $item_val['total_rate_percentage'][$key] : $tax;
	$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value);
            $pdf .='
                <tr>
                    <td width="33%" align="center" style="font-size:11px">'.$key.'</td>
                    <td width="33%" align="center" style="font-size:11px">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center" style="font-size:11px">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                </tr>
            ';
        }
        

        $pdf .= '
            
            </table>
            <br/>
    <div style="font:11px arial;border-bottom:dotted 1px #111111;">' . $invoice_main->message . '</div>
    <br/>
    </div>
</div>';

?>