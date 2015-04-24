<?php
require_once ("header.php");

//Start DELIVERY ORDER PDF****************************************************//
if ($_GET['pdf_type'] == "do") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_32_0'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = $default_message->configuration_value;
      
        require ("pdf/do.php");
      
        $pdf .= $message;
      
        $css .='<title>Delivery Order</title>';
        $mpdf->WriteHTML($css, 1);
        $mpdf->WriteHTML($pdf);
    }
}
//end Delivery Order pdf


//Start Invoice *******************************************************************//
else if ($_GET['pdf_type'] == "inv" && ($_GET['jID'] == 12 && $_GET['sub_jID'] == 0) || ($_GET['jID'] == 12  && $_GET['sub_jID']==1)) {  
  if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        
        require_once ("company_info.php");
        if(TEMPLATE_RECEIPT_SIZE==1){
            require_once ("pdf/invoice_receipt_size.php"); 
          $css .= "<style>th{ font-size:10px;}
                    td{ font-size:10px;}</style>";
          
        }else{
            require_once ("pdf/invoice.php"); 
            require_once ("GST_Summary.php");
        }
    
        $pdf .= $invoice_main->message;
    
    }
  
    $css .='<title>Invoice</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}
//End invoice pdf


//Start Quotations*****************************************************************//
else if ($_GET['pdf_type'] == "qpdf" || $_GET['jID'] == 9) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	      
        require_once ("pdf/quotation.php");		
        require_once ("GST_Summary.php");
        
        $pdf .= $invoice_main->message;
    }
    
    $css .='<title>Quotation</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 
//End Quotations*********************************************************************//


//Start Sales Order******************************************************************//
else if ($_GET['pdf_type'] == "so" || $_GET['jID'] == 10) {

    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        $sql           = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_10_0'";
        $x                = mysql_query($sql);
        $default_message  = mysql_fetch_object($x);
        $message          = strlen($invoice->message) < 5 ? $default_message->configuration_value : $invoice->message;
        
        require_once ("pdf/so.php");
        require_once ("GST_Summary.php");
       
        $pdf .= $message;
    }
    
    $css .='<title>Sales Order</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}
//End Sales Order**********************************************************************//


//Start Customer Deposit****************************************************************//
else if ($_GET['pdf_type'] == "cust_deposit") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	        
        require_once ("pdf/customer_deposit.php");
        require_once ("GST_Summary.php");

       $pdf .= $invoice_main->message;
    }
    $css .='<title>Customer Deposit</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 
//End Customer Deposit******************************************************************//


//start CREDIT NOTE pdf *****************************************************************//
else if ($_GET['pdf_type'] == "cm" || $_GET['jID'] == 13 || $_GET['jID'] == 7) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	  
	      require_once ("pdf/credit_note.php");
        require_once ("GST_Summary.php");

        $pdf .= $invoice_main->message;
    }
  
    $css .='<title>Credit Note</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}
//End Credit Note ************************************************************************//


// Customer Debit Note pdf **********************************************************//
else if ($_GET['pdf_type'] == "dm" || $_GET['jID'] == 30 || $_GET['jID'] == 31) {    // to print invoice whether this is sales debit memo or purchase debit memo
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
		
		    require_once ("pdf/debit_note.php");
        require_once ("GST_Summary.php");

        $pdf .= $invoice_main->message;
    }
  
    $css .='<title>Debit Note</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}
//End Customer Debit Note ******************************************************************//

//start PURCHASE pdf **************************************************************//
else if ($_GET['pdf_type'] == "pur") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	    require_once("pdf/purchases.php");
      require_once("GST_Summary.php");
		
    $pdf.= $invoice_main->message;
        
    }
    
    $css .='<title>Purchases</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 
//End Purchase pdf*****************************************************************//


//Self billed Invoice pdf **************************************************************//
else if ($_GET['pdf_type'] == "pur_self") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {

		  require_once("pdf/self_billed_invoice.php");
      require_once("GST_Summary.php");        

      $pdf .= $invoice_main->message;
    }
  
    $css .='<title>Billed Invoice</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}


//start purchase order pdf **********************************************************//
else if ($_GET['pdf_type'] == "puro" || $_GET['jID'] == 4) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	
	      require_once("pdf/po.php");
        require_once("GST_Summary.php"); 
	

        $pdf .= $invoice_main->message;
    }
  
    $css .='<title>Purchase Order</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 



//start request purchase order pdf **************************************//
else if ($_GET['pdf_type'] == "rqpuro" || $_GET['jID'] == 3) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
	      
        require_once("pdf/request_po.php");
        require_once("GST_Summary.php");
        
        $pdf .= $invoice_main->message;
    }
  
    $css .='<title>Purchase Request</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 



//start Delivery ORDERS pdf ******************************************//
else if ($_GET['pdf_type'] == "dopdf" || $_GET['jID'] == 10) {	
	
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        
        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_RECEIPT_DEFAULT'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = strlen($invoice_main->message) == 0 ? $default_message->configuration_value : $invoice_main->message;
        require_once ("pdf/dopdf.php");
      
        $pdf .= $message;
        
    }

    $css .='<title>Delivery Order</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 


/**********************
Receipt pdf => Banking - Sales Payment Receipts
***********************/
else if ($_GET['pdf_type'] == "rec" || $_GET['jID'] == 18) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
       

        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_RECEIPT_DEFAULT'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = strlen($invoice_main->message) == 0 ? $default_message->configuration_value : $invoice_main->message;
        
        require_once ("pdf/sales_payment_receipt.php");
      
        $pdf .= $message;
        
    }
  
    $css .='<title>Sales Payment Receipt</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 


//start Receive Money pdf *********************************
else if ($_GET['pdf_type'] == "ReM" || $_GET['jID'] == 34) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        
        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_RECEIVEMONEY_DEFAULT'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = strlen($invoice_main->message) == 0 ? $default_message->configuration_value : $invoice_main->message;
      
        require_once ("pdf/receive_money.php");
        $pdf .= $message;
      
    }
  
    $css .='<title>Receive Money</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 


//Start Spend Money PDF *****************************************************************************//
else if ($_GET['pdf_type'] == "SM" || $_GET['jID'] == 35) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        
        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_SPENDMONEY_DEFAULT'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = strlen($invoice_main->message) == 0 ? $default_message->configuration_value : $invoice_main->message;
      
        require_once ("pdf/spend_money.php");
        $pdf .= $message;
        
    }
    $css .='<title>Spend Money</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 



//start Purchase Payment Voucher pdf
else if ($_GET['pdf_type'] == "pay_bill" || $_GET['jID'] == 20) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        
        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_PPAYMENTVOUCHER_DEFAULT'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = strlen($invoice_main->message) == 0 ? $default_message->configuration_value : $invoice_main->message;
        
        require_once ("pdf/purchase_payment_voucher.php");
      
        $pdf .= $message;
        
    }
    $css .='<title>Payment Voucher</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
} 



//Start Inventory Adjustment PDF
else if ($_GET['pdf_type'] == "adjustment") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        foreach ($branchs as $branche) {
            if($invoice_main->store_id==$branche['id']){
                $store = $branche['text'];
                break;
            }
        }
        
        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_INVENTORYADJUSTMENT_DEFAULT'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = strlen($invoice_main->message) == 0 ? $default_message->configuration_value : $invoice_main->message;

        require_once ("pdf/adjustment.php");
      
        $pdf .= $message;
    }
  
    $css .='<title>Inventory Adjustment</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);

} 


//start inventory transfer pdf
else if ($_GET['pdf_type'] == "transfer") {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        foreach ($branchs as $branche) {
            if($invoice_main->store_id==$branche['id']){
                $transfer_from = $branche['text'];
                break;
            }
        }
          foreach ($branchs as $branche) {
            if($transfer_store_id==$branche['id']){
                $transfer_to = $branche['text'];
                $to = $db->Execute('SELECT contact, address1, address2, city_town, state_province from address_book where ref_id="'.$transfer_store_id.'"');
                $customer_info = $to->fields['contact']." ".$to->fields['address1']." ".$to->fields['address2']." ".$to->fields['city_town'].' '.$to->fields['state_province'];
                break;
            }
        }
        
        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_INVENTORYTRANSFER_DEFAULT'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = strlen($invoice_main->message) == 0 ? $default_message->configuration_value : $invoice_main->message;
        
        require_once ("pdf/transfer.php");
      
        $pdf .= $message;
      
    }
  
    $css .='<title>Inventory Transfer</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
 
  
} 


//start General Journal pdf
else if ($_GET['pdf_type'] == "genJournal" || $_GET['jID'] == 2) {
    if (isset($new_design)) {
        $pdf = $new_design['pdf'];
    } else {
        
        $sql = "SELECT * FROM configuration WHERE configuration_key = 'MESSAGE_GJVOUCHER_DEFAULT'";
        $x = mysql_query($sql);
        $default_message = mysql_fetch_object($x);
        
        $message = strlen($invoice_main->message) == 0 ? $default_message->configuration_value : $invoice_main->message;
        require_once ("pdf/general_journal.php");
      
        $pdf .= $message;
    }
    
    $css .='<title>General Journal</title>';
    $mpdf->WriteHTML($css, 1);
    $mpdf->WriteHTML($pdf);
}


//Start Sending Email
if ($_GET['action'] == "email") {

    if ($_GET['jID'] == 9) {
        $file_name = "quotation.pdf";
    } else if ($_GET['jID'] == 12) {
        $file_name = "invoice.pdf";
    } else if ($_GET['jID'] == 10) {
        $file_name = "sales_order.pdf";
    } else if ($_GET['jID'] == 13) {
        $file_name = "credit_memo.pdf";
    } else if ($_GET['jID'] == 4) {
        $file_name = "purchase_order.pdf";
    } else if ($_GET['jID'] == 7) {
        $file_name = "credit_memo.pdf";
    } else if ($_GET['jID'] == 3) {
        $file_name = "request_for_quote.pdf";
    }

    $temp_file = DIR_FS_MY_FILES . $_SESSION['company'] . '/temp/' . $file_name;
    $mpdf->Output($temp_file, "F");
    $from_email = $_POST['from_email'] ? $_POST['from_email'] : $_SESSION['admin_email'];
    $from_name = $_POST['from_name'] ? $_POST['from_name'] : $_SESSION['display_name'];
    $to_email = $_POST['to_email'] ? $_POST['to_email'] : $_GET['rEmail'];
    $to_name = $_POST['to_name'] ? $_POST['to_name'] : $_GET['rName'];
    $cc_email = $_POST['cc_email'] ? $_POST['cc_email'] : '';
    $cc_name = $_POST['cc_name'] ? $_POST['cc_name'] : $cc_address;
    $message_subject = $title . ' ' . TEXT_FROM . ' ' . COMPANY_NAME;
    $message_subject = $_POST['message_subject'] ? $_POST['message_subject'] : $message_subject;
    $message_body = $report->emailmessage ? TextReplace($report->emailmessage) : sprintf(UCFORM_EMAIL_BODY, $title, COMPANY_NAME);
    $email_text = $_POST['message_body'] ? $_POST['message_body'] : $message_body;


    $block = array();
    if ($cc_email) {
        $block['EMAIL_CC_NAME'] = $cc_name;
        $block['EMAIL_CC_ADDRESS'] = $cc_email;
    }
    $attachments_list['file'] = $temp_file;
    $success = validate_send_mail($to_name, $to_email, $message_subject, $email_text, $from_name, $from_email, $block, $attachments_list);
    if ($success) {
        $messageStack->add(EMAIL_SEND_SUCCESS, 'success');
        unlink($temp_file);
        ?>
        <script type="text/javascript">
            window.close();
        </script>
        <?php

    }
} else {
    ob_clean();
    $mpdf->Output();
    exit;
}
?>
