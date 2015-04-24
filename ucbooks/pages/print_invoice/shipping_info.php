<?php

//Shipping Information*****************************************************
  $shipping_info = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style=" " valign="top"  >';

  if (!empty($invoice_main->ship_primary_name)) {
      $shipping_info .= '   <tr>

                      <td style="font:bold 12px;">
                           ' . $invoice_main->ship_primary_name . ' 
                      </td>
                  </tr>';
  }

  $shipping_info .= '<tr>
  <td style = "font:12px;">';
  if (!empty($invoice_main->ship_address1))
      $shipping_info .= $invoice_main->ship_address1;
  if (!empty($invoice_main->ship_address2)) {
      if (!empty($invoice_main->ship_address1))
          $shipping_info .=', ' . $invoice_main->ship_address2;
      else
          $shipping_info .= $invoice_main->ship_address2;
  }
  $shipping_info .= '</td>
  </tr>';


  $shipping_info .= '<tr>
  <td style = "font:12px;">';
  if (!empty($invoice_main->ship_city_town))
      $shipping_info .= $invoice_main->ship_city_town;
  if (!empty($invoice_main->ship_state_province)) {
      if (!empty($invoice_main->ship_city_town))
          $shipping_info .= ', ' . $invoice_main->ship_state_province;
      else
          $shipping_info .= $invoice_main->ship_state_province;
  }
  if (!empty($invoice_main->ship_postal_code)) {
      if (!empty($invoice_main->ship_state_province))
          $shipping_info .= ', ' . $invoice_main->ship_postal_code;
      else
          $shipping_info .= ', ' . $invoice_main->ship_postal_code;
  }
  $countries = gen_get_countries();
  foreach ($countries as $country) {
      if ($country['id'] == $invoice_main->ship_country_code) {
          $country_name = $country['text'];
          break;
      }
  }
  if (!empty($invoice_main->ship_country_code)) {
      if (!empty($invoice_main->ship_postal_code))
          $shipping_info .= ', ' . $country_name;
      else
          $shipping_info .= $country_name;
  }
  $shipping_info .= '</td>
  </tr>';

  if (!empty($invoice_main->ship_telephone1)) {
      $shipping_info .= '<tr>
  <td style = "font:12px;">
  ' . $invoice_main->ship_telephone1 . '
  </td>';
  }
  if (!empty($invoice_main->ship_contact)) {
      $shipping_info .= '</tr>
  <tr>
  <td style = "font:12px;">
  Attn: ' . $invoice_main->ship_contact . '
  </td>
  </tr>';
  }
  $shipping_info .='</table >';

?>