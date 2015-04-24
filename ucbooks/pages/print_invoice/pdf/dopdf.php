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
                                <td><span style="font:12px arial"><b>DO Number </b></span></td><td>: <span style="font:12px arial">' .$invoice_main->purchase_invoice_id. '</span></td></tr>
                            <tr>
                                <td><span style="font:12px arial"><b>Date</b></span></td><td>: <span style="font:12px arial">' .date("d-m-Y", strtotime($invoice_main->post_date)). '</span></td></tr>
                            <tr>
                                <td><span style="font:12px arial"><b>Due Date </b></span></td><td>: <span style="font:12px arial">'.$payment_due_date.'</span></td></tr>
                            <tr>
                                <td><span style="font:12px arial"><b>Ref Num </b></span></td><td>: <span style="font:12px arial">'. $invoice_main->purch_order_id .'</span></td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <br/> <br />
    
   <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border-bottom:1px solid;">
        <tr bgcolor="#cccccc">
            <th width="5%">No.</th>
            '.item_code_col().'
            <th class="item_left" width="70%">Description</th>
            <th>QTY</th>';
            if(UOM == 1){
                $pdf .='<th>UOM</th>';
            }
            if(UOM_QTY == 1){
                $pdf .='<th>UOM QTY</th>';
            }
        $pdf .='</tr>';

     
        $i = 1;
        foreach ($item_val['item'] as $item) {
            $pdf .='
    
        <tr>
            <td class="item_first">' . $i . '</td>
            '.item_code_col2($item['sku']).'
            <td class="item_left">' . $item['description'] . '</td>
            <td class="item">' . $item['qty'] . '</td>';
            if(UOM == 1){
                $pdf .='<td class="item">' . $item['uom'] . '</td>';
            }
            if(UOM_QTY == 1){
                $pdf .='<td class="item">' . (($item['uom_qty'] !=0) ? $item['uom_qty'] : 1) . '</td>';
            }
        $pdf .='</tr>
        ';
            $i++;
        }
        
        $pdf .='</table><br/><div class="footer_area"><br/>';

?>