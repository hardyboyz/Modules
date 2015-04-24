<?php

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
                         <span style="font:12px arial"><b>Store Id</b></span> 
                            </td><td><span style="font:12px arial">: ' .
                $store
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Reason For Adjustment</b></span> 
                            </td><td><span style="font:12px arial">: ' .
                $item_val['ttl']['description']
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Adjustment Account</b></span> 
                            </td><td><span style="font:12px arial">: ' .
                $item_val['ttl']['gl_account']
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Post Date</b></span></td><td> <span style="font:12px arial">: ' .
                date("d-m-Y", strtotime($item_val['ttl']['post_date']))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Reference</b></span></td><td> <span style="font:12px arial">: '
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
            <td width="5%" style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border: 1px solid;">No.</td>
            <td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Item Name</td>
            <td width="50%" style="background-color: #cccccc;font: bold 12px arial; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;border-top: 1px solid; ">Description</td>
            <td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Qty in Stock</td>
            <td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Adj Qty</td>
            <td style="background-color: #cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px;  border: 1px solid; border-left:none;" >Item Cost</td>';

        $pdf .='<td style="background-color: #cccccc;font: bold 12px arial; border-right: 1px solid;text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Balance</td>';

        $pdf .='</tr>';


        $i = 1;
        foreach ($adjustment as $item) {
            $pdf .='
    
        <tr>
            <td style="border-bottom: none;border-right: 1px solid; border-left: 1px solid; padding: 5px;  font:12px arial">' . $i . '</td>
            <td style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['sku'] . '</td>
            <td style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['description'] . '</td>
            <td style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['remaining'] . '</td>
            <td style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['qty'] . '</td>
            <td style="border-bottom: none;border-right: 1px solid; padding: 5px; font:12px arial">' . $currencies->format_full( $item['unit_cost'], true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>';

            $pdf .='<td style="border-bottom: none;border-left: 1px solid;border-right: 1px solid; padding: 5px; font:12px arial">' . $item['balance'] . '</td>';


            $pdf .='</tr>
        ';
            $i++;
        }
        $pdf .='</table>
    
    <br/>
   
</div>';

?>