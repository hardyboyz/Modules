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
        Inventory Transfer
    </div>
    
    <br/>
    <div style="width:100%">
        <div style="width:40%; float:left; border:1px solid; height:80px; ">
        <p style="font:12px arial;margin:1px 4px;"><b> To : </b><br />
        '.$customer_info.'
        </p> 
        </div>
        <div style="width:40%; float:right; height:80px; border:1px solid;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top"  >
                <tr>
                    <td style="text-align:left;">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Reference Number</b></span> 
                            </td><td><span style="font:12px arial">: ' .
                $invoice_main->purchase_invoice_id
                . '</span>
                            </td>
                            </tr>
                       
                             
                             <tr>
                                <td>
                         <span style="font:12px arial"><b>Transfer From</b></span> 
                            </td><td><span style="font:12px arial">: ' .
                $transfer_from
                . '</span>
                            </td>
                            </tr>
                                 <tr>
                                <td>
                         <span style="font:12px arial"><b>Transfer To</b></span> 
                            </td><td><span style="font:12px arial">: ' .
                $transfer_to
                . '</span>
                            </td>
                            </tr>
                            <tr>
                                <td>
                         <span style="font:12px arial"><b>Transfer Account</b></span> 
                            </td><td><span style="font:12px arial">: ' .
                $item_val['ttl']['gl_account']
                . '</span>
                            </td>
                            </tr>

                            <tr>
                                <td>
                                <span style="font:12px arial"><b>Post Date</b></span></td><td> <span style="font:12px arial">: ' .
                date("d-m-Y", strtotime($invoice_main->post_date))
                . '</span></td></tr><tr><td>
                             <span style="font:12px arial"><b>Reason For Transfer</b></span></td><td> <span style="font:12px arial">: '
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

            <div class = "footer_area">
              ' . $message . '            
            </div>';

?>