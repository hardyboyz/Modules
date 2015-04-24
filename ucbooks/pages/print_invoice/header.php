<?php
  
function discount_col($data){
  if($data > 0){
    if($_GET['pdf_type']=="inv"){
     return '<th>Discount</th>';
    }else{
      return '<td style="background-color:#cccccc;font: bold 12px arial; text-align: center; height: 17px; padding-top: 3px; border-left: 1px solid; border-bottom: 1px solid; border-top: 1px solid;">Discount</td>';
    }
  }
}

function discount_item($data,$percent,$border){
  if($data > 0){
      if($_GET['pdf_type']=="inv"){
        return $border == 1 ? '<td valign="top" align="center" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $percent .'</td>' : '<td valign="top" align="center" class="item">' . $percent . '</td>';
      }else{
        return '<td valign="top" align="center" style="border-bottom: none;border-left: 1px solid; padding: 5px; font:12px arial">' . $percent .'</td>'; 
      }
  }
}

function add_col($data,$border){
  if($data > 0){
    return $border==1 ? '<td style="border-top:1px solid;"></td>' : '<td></td>';
  }
}

function billto_shipto($data, $customer_info, $shipping_info){
  if($data==1){
   return '<div style="width:37%; float:left; height:90px; ">
		      <table width="100%"><tr><td bgcolor="#c0c0c0">Bill To</td></tr></table>
            '.$customer_info.'
          </div>
		
          <div style="width:3%; float:left; height:90px; "></div>

          <div style="width:37%; float:left; height:90px; ">
          <table width="100%"><tr><td bgcolor="#c0c0c0">Ship To</td></tr></table>
                '.$shipping_info.'
          </div>'; 
    }else{
       return '<div style="width:60%; float:left; border:1px solid; height:90px;">'.$customer_info.'</div>';
    }
}

function item_code_col(){
  if(TEMPLATE_ITEM_CODE==1){
    return '<th>Item Code</th>';
  }
}

function item_code_col2($val){
  if(TEMPLATE_ITEM_CODE==1){
    return '<td valign="top" align="center" class="item">' . $val . '</td>';
  }
}

function invoice($invoice_main, $payment_due_date){
  return '<div style="width:20%; float:right; height:90px;">
            <table width="100%"><tr><td bgcolor="#c0c0c0"> Invoice </td></tr></table>
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top">
                <tr>
                  <td style="text-align:left;">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr><td><span style="font:10px arial"><b>INV Number</b></span></td><td><span style="font:10px arial">: ' .$invoice_main->purchase_invoice_id. '</span></td></tr>
                        <tr><td><span style="font:10px arial"><b>INV Date</b></span></td><td> <span style="font:10px arial">: ' .date("d-m-Y", strtotime($invoice_main->post_date)). '</span></td></tr>
                        <tr><td><span style="font:10px arial"><b>Due Date</b></span></td><td> <span style="font:10px arial">: '.$payment_due_date.'</span></td></tr>
                        <tr><td><span style="font:10px arial"><b>PO Num</b></span></td><td> <span style="font:10px arial">: '. $invoice_main->purch_order_id .'</span></td></tr>
                     </table>
                  </td>
              </tr>
            </table>
        </div>
        '.add_line(); 
}

function add_line($width = '100%'){
   return '<hr align="right" width='.$width.' style="border:solid 1px #cccccc">';
}

  //$css  = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">'
  $css = '
  <style type="text/css">
      .pdf_area{width: 19cm; height: 25.7cm; border: 1px solid; padding: 2cm;}
      .company_area{width: 19cm; height: 3cm; }
          .company_area_left{width: 8cm; height: 3cm; float: left; text-align: left;}
          .company_area_right{width: 10cm; height: 3cm; float: right; text-align: right;}
          .company_name{font: bold 16pxl;}
          .company_addrss{font:regular 14px;}
      .adress_area{width: 17cm; height: 5cm; border: 1px solid;}
          .address_left{width: 5.96cm; height: 5cm; float: left; border-right: 1px solid; }
          .address_middle{width: 5.98cm; height: 5cm; float: left; border-right: 1px solid;}
          .address_right{width: 5cm; height: 5cm; float: left;}
          .address_heading_text{font: bold 16px; width: 5cm; height: 26px; background-color: #e2dfdf; border-bottom: 1px solid; text-align: center; padding: 4px 0 0 0}
          .address_left .address_heading_text{width: 5.96cm;}
          .address_middle .address_heading_text{width: 5.98cm;}
          .address_right .address_heading_text{border-right: none; }
          .invoice_heading{font-size:20px; text-align: center;}
          .address_content{padding: 5px 0 0 10px; line-height: 18px; font:regular 14px;}
      .invoice_item_area{width: 17cm; height: auto; border: 1px solid;}
          .invocie_item_header{width: 17cm; height: 30px; background-color: #e2dfdf; border-bottom: 1px solid;}
              .invocie_item_header_sku{width: 2cm; height: 26px; float: left; font: bold 16px; text-align: center; padding: 4px 0 0 0; border-right: 1px solid;}
              .invocie_item_header_description{width: 6.5cm; height: 26px; float: left;font: bold 16px; text-align: center; padding: 4px 0 0 0; border-right: 1px solid;}
              .invocie_item_header_qty{width: 2cm; height: 26px; float: left;font: bold 16px; text-align: center; padding: 4px 0 0 0; border-right: 1px solid;}
              .invocie_item_header_nutprice{width: 3cm; height: 26px; float: left;font: bold 16px; text-align: center; padding: 4px 0 0 0; border-right: 1px solid;}
              .invocie_item_header_extension{width: 3cm; height: 26px; float: left;font: bold 16px; text-align: center; padding: 4px 0 0 0;}
           .invocie_item{width: 17cm; height: 30px; }
              .invocie_item_sku{width: 1.7cm; height: 27px; float: left; border-right: 1px solid; padding-left: .3cm; padding-top: 3px;}
              .invocie_item_description{width: 6.2cm; height: 27px; float: left; border-right: 1px solid; padding-left: .3cm; padding-top: 3px;}
              .invocie_item_qty{width: 1.7cm; height: 27px; float: left; border-right: 1px solid; padding-left: .3cm ; padding-top: 3px;}
              .invocie_item_nutprice{width: 2.7cm; height: 27px; float: left; border-right: 1px solid; padding-left: .3cm; padding-top: 3px;}
              .invocie_item_extension{width: 2.7cm; height: 27px; float: left; padding-left: .3cm; padding-top: 3px;}
           .invocie_sub{width: 17cm; height: auto;}

              .invocie_sub_right{width: 6.44cm; height: auto; float: right; border: 1px solid; border-top: none; margin: 0 -2px 0 0}
              .sub_total_left{width: 2.72cm; float: left; height: 26px; border-right: 1px solid; padding: 4px 0 0 .3cm; font-weight: bold;  }
              .sub_total_right{width: 2.7cm; float: left; height: 24px; padding: 4px 0 0 .3cm; }
              .heading_td{background-color: #cccccc;font: bold 12px; text-align: center; height: 17px; padding-top: 3px; border-right: 1px solid; border-bottom: 1px solid;}
              .body_td{ border-right: 1px solid; border-bottom: 1px solid; padding-top: 5px; padding-bottom: 5px; }
              .subtotal_td{border: 1px solid; border-top: none;}
              .heading_td.bottom_none{border-bottom: none;}
              .body_td.bottom_border{border-bottom: none !important;}
              .item{ border-left:solid 1px #cccccc;font-size:11px; padding:5px;vertical-align:top; text-align:center;}
              .item_first{ border-left:none;font-size:11px; padding:5px;vertical-align:top; text-align:center;}
              .border{border:solid 1px #cccccc;}
              th{ border:solid 1px #FFFFFF;font-size:11px; padding:5px;}
              th.item_left{border:solid 1px #FFFFFF;font-size:11px; padding:5px;text-align:left}
              .item_left {padding-left:5px;}
  </style>
  <body style="font-family: Arial; font-size: 10pt;">';


  echo "<pre>";
  //print_r($invoice_main);
  echo "</pre>";

  echo "<pre>";
  //print_r($invoice_item);

  echo "</pre>";

  echo "<pre>";
  //print_r($configuration);
  echo "</pre>";

  $customer_info = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" valign="top">';

  if($_GET['pdf_type']!="SM"){
      if (!empty($invoice_main->bill_primary_name)) {
          $customer_info .= '   <tr>

                          <td style="font:bold 12px;">
                               ' . $invoice_main->bill_primary_name . ' 
                          </td>
                      </tr>';
      }
  }

  $customer_info .= '<tr>
  <td style = "font:12px;">';
  if (!empty($invoice_main->bill_address1))
      $customer_info .= $invoice_main->bill_address1;
  if (!empty($invoice_main->bill_address2)) {
      if (!empty($invoice_main->bill_address1))
          $customer_info .=', ' . $invoice_main->bill_address2;
      else
          $customer_info .= $invoice_main->bill_address2;
  }
  $customer_info .= '</td>
  </tr>';


  $customer_info .= '<tr>
  <td style = "font:12px;">';
  if (!empty($invoice_main->bill_city_town))
      $customer_info .= $invoice_main->bill_city_town;
  if (!empty($invoice_main->bill_state_province)) {
      if (!empty($invoice_main->bill_city_town))
          $customer_info .= ', ' . $invoice_main->bill_state_province;
      else
          $customer_info .= $invoice_main->bill_state_province;
  }
  if (!empty($invoice_main->bill_postal_code)) {
      if (!empty($invoice_main->bill_state_province))
          $customer_info .= ', ' . $invoice_main->bill_postal_code;
      else
          $customer_info .= ', ' . $invoice_main->bill_postal_code;
  }
  $countries = gen_get_countries();
  foreach ($countries as $country) {
      if ($country['id'] == $invoice_main->bill_country_code) {
          $country_name = $country['text'];
          break;
      }
  }
  if (!empty($invoice_main->bill_country_code)) {
      if (!empty($invoice_main->bill_postal_code))
          $customer_info .= ', ' . $country_name;
      else
          $customer_info .= '<br />'.$country_name;
  }
  $customer_info .= '</td>
  </tr>';

  if (!empty($invoice_main->bill_telephone1)) {
      $customer_info .= '<tr>
  <td style = "font:12px;">
  ' . $invoice_main->bill_telephone1 . '
  </td>';
  }
  if (!empty($invoice_main->bill_contact)) {
      $customer_info .= '</tr>
  <tr>
  <td style = "font:12px;">
  Attn: ' . $invoice_main->bill_contact . '
  </td>
  </tr>';
  }
  $customer_info .='</table>';


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


  foreach ($configuration as $key => $val) {
      if ($val['configuration_key'] == "COMPANY_LOGO") {
          $logo = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_ID") {
          $company_id = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_NAME") {
          $company_name = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_ADDRESS1") {
          $company_address = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_ADDRESS2") {
          $company_address2 = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_CITY_TOWN") {
          $company_city_town =  $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_ZONE") {
          $company_state = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_POSTAL_CODE") {
          $company_postal_code =  $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_COUNTRY") {
          $company_country = $val['configuration_value'];
          $countries = gen_get_countries();
          foreach ($countries as $country) {
              if ($country['id'] == $company_country) {
                  $company_country = $country['text'];
                  break;
              }
          }
      }
      if ($val['configuration_key'] == "COMPANY_EMAIL") {
          $email = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_WEBSITE") {
          $web = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_TELEPHONE1") {
          $phone = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_TELEPHONE2") {
          if (!empty($val['configuration_value']))
              $phone .= " ," . $val['configuration_value'];
          else
              $phone .= $val['configuration_value'];
      }
      if ($val['configuration_key'] == "COMPANY_FAX") {
          $fax = $val['configuration_value'];
      }
      if ($val['configuration_key'] == "TAX_ID") {
          $tax_id = $val['configuration_value'];
      }
  }
  ?>

  <?php
  //Line 1
  $company_info = '<span class="company_name">' . $company_name;
  if (!empty($company_id)) {
      $company_info .= ' (' . $company_id . ')</span><br/>';
  } else {
      $company_info .= '</span><br/>';
  }
  // Line 2 
  if (!empty($company_address)) {
      $company_info .= ' <span class="company_addrss">' . $company_address . ',</span>
              <br/>';
  }
  // Line 3 
  if (!empty($company_address2)) {
      $company_info .= ' <span class="company_addrss">' . $company_address2 . ',</span>
              <br/>';
  }

  // Line 4
  if (!empty($company_postal_code)) {
      $company_info .= ' <span class="company_addrss">' . $company_postal_code;
      if (empty($company_city_town))
          $company_info .= '</span>';
  }


  if (!empty($company_city_town)) {
      if (!empty($company_postal_code))
          $company_info .= '  ' . $company_city_town . ',</span><br/>';
      else
          $company_info .= ' <span class="company_addrss">' . $company_city_town . ',</span><br/>';
  }
  // Line 5
  if (!empty($company_state)) {
      $company_info .= ' <span class="company_addrss">' . $company_state;
      if (empty($company_country))
          $company_info .= '</span>';
  }


  if (!empty($company_country)) {
      if (!empty($company_state))
          $company_info .= ', ' . $company_country . '</span><br/>';
      else
          $company_info .= ' <span class="company_addrss">' . $company_country . '</span><br/>';
  }
  // Line 6
  if (!empty($phone)) {
      $company_info .= ' <span class="company_addrss">(T)' . $phone;
      if (empty($fax))
          $company_info .= '</span>';
  }


  if (!empty($fax)) {
      if (!empty($phone))
          $company_info .= ' (F)' . $fax . '</span><br/>';
      else
          $company_info .= ' <span class="company_addrss">(F)' . $fax . '</span><br/>';
  }
  // Line 7

  if (!empty($email)) {
      $company_info .= ' <span class="company_addrss">' . $email . '</span>
              <br/>';
  }
  //Line 8

  if (!empty($web)) {
      $company_info .= ' <span class="company_addrss">' . $web . '</span>
              <br/>';
  }
  // Line 9
  if (!empty($tax_id)) {
      $company_info .= ' <span class="company_addrss">GST ID: ' . $tax_id . '</span>
              <br/>';
  }

  $str = $invoice_main->terms;
  //print_r (explode(":",$str,0));

  if (($invoice_main->terms == 0) OR ($invoice_main->terms == null))
    {
    $payment_due_date = '30 Days';
    }
  else if ($str[0] == '1')
    {
    $payment_due_date = 'COD';
    }
  else if ($str[0] == '2')
    {
    $payment_due_date = 'PREPAID';
    }
  else if ($str[0] == '4')
    {
    $payment_due_date = $str[6].$str[7].'-'.$str[9].$str[10].'-'.$str[12].$str[13].$str[14].$str[15];
    }
  else if ($str[0] == '5')
    {
    $payment_due_date = 'Month End';
    }	
  else if ($str[0] = '3')
    {
      if ($str[8] !== ':')
        {
          $payment_due_date = $str[6].$str[7].$str[8].' days';
        }
      else
        {
          $payment_due_date = $str[6].$str[7].' days';
        }
    }



  if ($invoice_main->rep_id == "1")
    { 
    $sales_rep = "";
    }
  else if ($invoice_main->rep_id == "2")
    { 
    $sales_rep = "";
    }
  else 
    {
    $sales_rep = "none";
    }


    $company_code     = COMPANY_CODE;

    require_once(DIR_FS_INCLUDES . 'mpdf/mpdf.php');
    
    $mpdf = new mPDF();


?>