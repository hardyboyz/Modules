<?php

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
            <div style = "font:14px arial">' . $message . '</div>
            <br/>
            <br/>
            <div class = "footer_area">
            

            </div>
            </div>';

?>