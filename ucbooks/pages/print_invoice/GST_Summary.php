<?php

$pdf .='<div class="footer_area" style="padding-top:20px;padding-bottom:20px">
        <table width="100%">
            <tr>
                <th width="33%">GST Summary</th>
                <th width="33%">Amount ('.$invoice_main->currencies_code.')</th>
                <th width="33%">GST ('.$invoice_main->currencies_code.')</th>
            </tr>';

        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td width="33%" align="center">'.$key.'</td>
                    <td width="33%" align="center">'.$currencies->format_full($val, true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                        
                    <td width="33%" align="center">'.$currencies->format_full($item_val['total_rate_percentage'][$key], true, $invoice_main->currencies_code, $invoice_main->currencies_value).'</td>
                </tr>
            ';
        }
        
$pdf .= '</table></div>';
    
?>