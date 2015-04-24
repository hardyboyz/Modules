<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011, 2012 UcSoft, LLC       |
// | http://www.UcSoft.com                                        |
// +-----------------------------------------------------------------+
// | This program is free software: you can redistribute it and/or   |
// | modify it under the terms of the GNU General Public License as  |
// | published by the Free Software Foundation, either version 3 of  |
// | the License, or any later version.                              |
// |                                                                 |
// | This program is distributed in the hope that it will be useful, |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of  |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the   |
// | GNU General Public License for more details.                    |
// +-----------------------------------------------------------------+
//  Path: /modules/ucbooks/pages/orders/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    // Include translations here as well.
    var setId                = 0; // flag used for AJAX loading of sku for bar code reading of line item
    var skuLength            = <?php echo ORD_BAR_CODE_LENGTH; ?>;
    var resClockID           = 0;
    var max_sku_len          = <?php echo MAX_INVENTORY_SKU_LENGTH; ?>;
    var auto_load_sku        = <?php echo INVENTORY_AUTO_FILL; ?>;
    var image_ser_num        = '<?php echo TEXT_SERIAL_NUMBER; ?>';
    var add_array            = new Array("<?php echo implode('", "', $js_arrays['fields']); ?>");
    var company_array        = new Array("<?php echo implode('", "', $js_arrays['company']); ?>");
    var default_array        = new Array("<?php echo implode('", "', $js_arrays['text']); ?>");
    var journalID            = '<?php echo JOURNAL_ID; ?>';
    var securityLevel        = <?php echo $security_level; ?>;
    var single_line_list     = '<?php echo SINGLE_LINE_ORDER_SCREEN; ?>';
    var uom                  = '<?php echo UOM; ?>';
    var uom_qty              = '<?php echo UOM_QTY; ?>';
    var account_type         = '<?php echo $account_type; ?>';
    var text_search          = '<?php echo TEXT_SEARCH; ?>';
    var text_enter_new       = '<?php echo TEXT_ENTER_NEW; ?>';
    var text_properties      = '<?php echo TEXT_PROPERTIES; ?>';
    var text_terms           = '<?php echo gen_terms_to_language('0', true, ($account_type == "v") ? 'AP' : 'AR'); ?>';
    var text_Please_Select   = '<?php echo GEN_HEADING_PLEASE_SELECT; ?>';
    var text_gl_acct         = '<?php echo TEXT_GL_ACCOUNT; ?>';
    var text_sales_tax       = '<?php echo ORD_TAXABLE; ?>';
    var text_price_manager   = '<?php echo TEXT_PRICE_MANAGER; ?>';
    var text_acct_ID         = '<?php echo TEXT_GL_ACCOUNT; ?>';
    var post_error           = <?php echo $error ? "true;" : "false;"; ?>
    var default_inv_acct     = '<?php echo DEF_INV_GL_ACCT; ?>';
    var default_sales_tax    = '0';
    var default_GL_acct      = '<?php echo DEF_GL_ACCT; ?>';
    var default_disc_acct    = '<?php echo ($account_type == "v") ? AP_DISCOUNT_PURCHASE_ACCOUNT : AR_DISCOUNT_SALES_ACCOUNT; ?>';
    var default_freight_acct = '<?php echo ($account_type == "v") ? AP_DEF_FREIGHT_ACCT : AR_DEF_FREIGHT_ACCT; ?>';
    var image_delete_text    = '<?php echo TEXT_DELETE; ?>';
    var image_delete_msg     = '<?php echo TEXT_ROW_DELETE_ALERT; ?>';
    var store_country_code   = '<?php echo COMPANY_COUNTRY; ?>';
    var acct_period          = '<?php echo CURRENT_ACCOUNTING_PERIOD; ?>';
    var item_col_1_enable    = '<?php echo ($item_col_1_enable) ? '1' : '0'; ?>';
    var item_col_2_enable    = '<?php echo ($item_col_2_enable) ? '1' : '0'; ?>';
    var ship_search_HTML     = '<?php echo GEN_CUSTOMER_ID . " " . html_input_field("ship_search", $order->ship_short_name) . "&nbsp;" . html_icon("status/folder-open.png", TEXT_SEARCH, "small", 'align="top" style="cursor:pointer" title="' . TEXT_SEARCH . '" onclick="DropShipList(this)"'); ?>';
    var delete_icon_HTML     = '<?php echo substr(html_icon("emblems/emblem-unreadable.png", TEXT_DELETE, "small", "onclick=\"if (confirm(\'" . TEXT_ROW_DELETE_ALERT . "\')) removeInvRow("), 0, -2); ?>';
    var payments_installed   = <?php echo count($payment_modules) ? 'true' : 'false'; ?>;
    var serial_num_prompt    = '<?php echo ORD_JS_SERIAL_NUM_PROMPT; ?>';
    var no_stock_a           = '<?php echo ORD_JS_NO_STOCK_A; ?>';
    var no_stock_b           = '<?php echo ORD_JS_NO_STOCK_B; ?>';
    var no_stock_c           = '<?php echo ORD_JS_NO_STOCK_C; ?>';
    var inactive_a           = '<?php echo ORD_JS_INACTIVE_A; ?>';
    var inactive_b           = '<?php echo ORD_JS_INACTIVE_B; ?>';
    var show_status          = '<?php echo ($account_type == "v") ? AP_SHOW_CONTACT_STATUS : AR_SHOW_CONTACT_STATUS; ?>';
    var warn_form_modified   = '<?php echo ORD_WARN_FORM_MODIFIED; ?>';
    var warn_form_has_data   = '<?php echo ORD_WARN_FORM_HAS_DATA; ?>';
    var warn_price_sheet     = '<?php echo ORD_PRICE_SHEET_WARN; ?>';
    var cannot_convert_quote = '<?php echo ORD_JS_CANNOT_CONVERT_QUOTE; ?>';
    var cannot_convert_so    = '<?php echo ORD_JS_CANNOT_CONVERT_SO; ?>';
    var sku_not_unique       = '<?php echo ORD_JS_SKU_NOT_UNIQUE; ?>';
    var no_contact_id        = '<?php echo ORD_JS_NO_CID; ?>';
    var defaultPostDate      = '<?php echo date(DATE_FORMAT, time()); ?>';
    var defaultTerminalDate  = '<?php echo $req_date; ?>';
    var defaultCurrency      = '<?php echo DEFAULT_CURRENCY; ?>';
    var tax_freight          = '<?php echo ($account_type == "c") ? AR_ADD_SALES_TAX_TO_SHIPPING : AP_ADD_SALES_TAX_TO_SHIPPING; ?>';
    var tax_before_discount  = '<?php echo ($account_type == "c") ? AR_TAX_BEFORE_DISCOUNT : AP_TAX_BEFORE_DISCOUNT; ?>';
    var lowStockNoProducts   = '<?php echo LOW_STOCK_NO_PRODUCTS ?> ';
    var lowStockProcessed    = '<?php echo LOW_STOCK_PROCESSED ?> ';
    var lowStockNoVendor     = '<?php echo LOW_STOCK_NO_VENDOR ?>';
    var lowStockExecuted     = '<?php echo LOW_STOCK_ALREADY_EXECUTED ?>';
    var lowStockExecute      = true;
    var custCreditLimit      = <?php echo AR_CREDIT_LIMIT_AMOUNT; ?>;
    var applyCreditLimit     = '<?php echo APPLY_CUSTOMER_CREDIT_LIMIT ?>';
    var adminNotValidated    = '<?php echo TEXT_ADMIN_NOT_VALIDATED; ?>';
    var action				 = '<?php echo $action; ?>';
    var edit_contact = '';
    var edit_item = '';
    var fill = '<?php echo $fill; ?>';
    var text = '';
<?php
echo js_calendar_init($cal_order);
if ($template_options['terminal_date'])
    echo js_calendar_init($cal_terminal);
?>
    // List the currency codes and exchange rates
<?php if (ENABLE_MULTI_CURRENCY) echo $currencies->build_js_currency_arrays(); ?>
    // List the gl accounts for line item pull downs
<?php echo $js_gl_array; ?>
    // List the tax rates
<?php echo $js_tax_rates; ?>
    
<?php echo $js_uom_list; ?>
    // List the active projects
<?php echo $js_proj_list; ?>
    // list the freight options
<?php echo $shipping_methods; ?>
<?php echo $ajax_order_type; ?>
    function init() {
        document.getElementById('ship_to_select').style.visibility = 'hidden';
        document.getElementById('bill_to_select').style.visibility = 'hidden';
        document.getElementById('ship_to_search').innerHTML = '&nbsp;'; // turn off ship to id search
        $('#override_order').dialog({ autoOpen:false, width:600 });
<?php
if ($error && isset($order->shipper_code)) {
    $values = explode(':', $order->shipper_code);
    echo '  document.getElementById("ship_carrier").value = "' . $values[0] . '";' . chr(10);
    echo '  buildFreightDropdown();';
    echo '  document.getElementById("ship_service").value = "' . $values[1] . '";' . chr(10);
} else {
    echo '  buildFreightDropdown();';
}
?>
        setField('sku_1',text_search);
        // change color of the bill and ship address fields if they are the default values
        var add_id;
        for (var i=0; i<add_array.length; i++) {
            add_id = add_array[i];
            if (document.getElementById('bill_'+add_id).value == '') {
                document.getElementById('bill_'+add_id).value = default_array[i];
            }
            if (document.getElementById('bill_'+add_id).value == default_array[i]) {
                if (add_id != 'country_code') document.getElementById('bill_'+add_id).style.color = inactive_text_color;
            }
            if (document.getElementById('ship_'+add_id).value == default_array[i]) {
                if (add_id != 'country_code') document.getElementById('ship_'+add_id).style.color = inactive_text_color;
            }
        }

        if (journalID == '19') activateFields();
        document.orders.elements['search'].focus();

<?php
if (!$error)
    echo 'DropShipView(document.orders);' . "\n";
if (!$error && $action == 'print') {
    echo '  force_clear = true;' . "\n";
    echo '  ClearForm();' . "\n";
    echo '  var printWin = window.open("index.php?module=ucform&page=popup_gen&gID=' . POPUP_FORM_TYPE . '&date=a&xfld=journal_main.id&xcr=EQUAL&xmin=' . $order->id . '","popup_gen","width=1150px,height=550px,resizable=1,scrollbars=1,top=150px,left=100px");' . "\n";
    echo '  printWin.focus();' . "\n";
}
if (!$error && $action == 'email') {
    echo '  force_clear = true;' . "\n";
    echo '  ClearForm();' . "\n";
    echo '  var printWin = window.open("index.php?module=ucbooks&page=popup_email&oID=' . $order->id . '","forms","width=1150px,height=350px,resizable=1,scrollbars=1,top=150px,left=100px");' . "\n";
    echo '  printWin.focus()' . "\n";
}
if ($action == 'edit')
    echo ' if(ajax_order_type == 1){  ajaxOrderData(0, ' . $oID . ', ' . JOURNAL_ID . ', false, false,0);} else{ajaxOrderData(0, ' . $oID . ', ' . JOURNAL_ID . ', false, false);}' . "\n";
if ($action == 'prc_so')
    echo '  ajaxOrderData(0, ' . $oID . ', ' . JOURNAL_ID . ', true, false);' . "\n";
if ($action == 'prc_doo')
    echo '  ajaxOrderData(0, ' . $oID . ', ' . JOURNAL_ID . ', true, false);' . "\n";
if ($action == 'prc_do')
    echo 'ajaxOrderData(0, "' . $oID . '", 12, undefined, undefined,undefined,undefined,' . $contactID . ');' . "\n";
if (ORD_ENABLE_LINE_ITEM_BAR_CODE)
    echo 'refreshOrderClock();';
?>

    }
   
    $("#ship_email").live('blur',function(){
        $('#sku_1').focus();
        $("html, body").scrollTop($("#productList").offset().top);
    })
    //    $(document).ready(function(){
    //        $("input[id*='sku_']").focusout(function(){
    //            $('#item_list').hide();
    //        });
    //        $("#search").focusout(function(){
    //            $('#customer_list').hide();
    //        })
    //    });
  
    $(document).keydown(function(e) {
        if(e.keyCode == 13) {
 
            if($('#tb_icon_save').hasClass('btn_save')){
                $('#tb_icon_save').trigger('click');
                window.close();
            }else if($('#closed').is(':focus')){
                if($('#closed').is(':checked')==false){
                    $('#closed').prop('checked', true);
                }else{
                    $('#closed').prop('checked', false);
                }
            }else if($('#bill_add_update').is(':focus')){
                if($('#bill_add_update').is(':checked')==false){
                    $('#bill_add_update').prop('checked', true);
                }else{
                    $('#bill_add_update').prop('checked', false);
                }
            }else if($('#ship_add_update').is(':focus')){
                if($('#ship_add_update').is(':checked')==false){
                    $('#ship_add_update').prop('checked', true);
                }else{
                    $('#ship_add_update').prop('checked', false);
                }
            }else if($('#rm_attach').is(':focus')){
                if($('#rm_attach').is(':checked')==false){
                    $('#rm_attach').prop('checked', true);
                }else{
                    $('#rm_attach').prop('checked', false);
                }
            }else if($('#is_recurring').is(':focus')){
                if($('#is_recurring').is(':checked')==false){
                    $('#is_recurring').prop('checked', true);
                    $('#recurring_interval').prop('disabled',false)
                }else{
                    $('#is_recurring').prop('checked', false);
                    $('#recurring_interval').prop('disabled',true)
                }
            }else if($('#is_mail').is(':focus')){
                if($('#is_mail').is(':checked')==false){
                    $('#is_mail').prop('checked', true);
                }else{
                    $('#is_mail').prop('checked', false);
                }
            }else if($('input[id*="desc_"]').is(':focus')){
                var id = $("*:focus").attr("id");
                $('#'+id).trigger('click');
            }
        }else if(e.keyCode==9){
            var i=0;
            $("input[id*='sku_']").focusout(function(){
                // alert($('#'+$(this).attr('id')).siblings('#item_list').find('.selected').html())
                //if(i==0){
                 $('#'+$(this).attr('id')).siblings('#item_list').find('.selected').trigger('click');
                $('#item_list').hide();
                //  i++;
                // }
                
            })
            $("#search").focusout(function(){
                $('#customer_list .selected').trigger('click');
                $('#customer_list').hide();
            })
            
        }
       
    });
//    $("input[id*='sku_']").live('blur',function(){
//        $('#'+$(this).attr('id')).siblings('#item_list').find('.selected').trigger('click');
//        $('#item_list').hide();
//    })

    var chosen = '';
    $('#search').live('keyup',function(e){
        if(e.keyCode == 13){
            $('.selected').trigger('click');
            
            $('#customer_list').hide();
       
        }else{
            if (e.keyCode != 40 && e.keyCode != 38) { 
                $('#item_list').hide();
                var keyword = $('#search').val();
                // alert(keyword)
                var type='';
                if(journalID==12 || journalID==36 || journalID==32 || journalID==10 || journalID==9 || journalID==13|| journalID==30){
                    type = 'cm';
                }else if(journalID==3 || journalID==4 || journalID==6|| journalID==7|| journalID==31){
                    type = 'vm';
                }else if(journalID==18){
                    type='c';
                }else if(journalID==20){
                    type='v';
                }
            
                $.ajax({
                    type: "GET",
                    url: 'index.php?module=ucbooks&page=ajax&op=autocopmplete&type='+type+'&journalID='+journalID+'&action=contact&keyword='+keyword,
                    dataType: "text",
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
                    },
                    success: function(data){
                        //alert(data);
                        if(data!=''){
                            // if (e.keyCode != 40 && e.keyCode != 38) { 
                            chosen = -1;
                            // }
                            $('#customer_list').show();
                            $('#customer_list').html(data);
                     
      
                    
                        }else{
                            $('#customer_list').hide();
                        }
                    }
                });
            
            }else{
                var dropdown =  '#customer_list';
                if (e.keyCode == 40) { 
                    if(chosen === "") {
                        chosen = 0;
                    } else if((chosen+1) < $('#customer_list li').length) {
                        chosen++; 
                    }
                    $('#customer_list li').removeClass('selected');
                    $('#customer_list li:eq('+chosen+')').addClass('selected');
                    var selectedTop = $('.selected').offset().top;
                    //alert(selectedTop)
                    var selectedBottom = selectedTop + $('#customer_list li:eq('+chosen+')').outerHeight();
                    var ddTop = $(dropdown).offset().top
                    var ddBottom = ddTop + $(dropdown).outerHeight();
                    var ddScrollTop = $(dropdown).scrollTop();

                    if (selectedBottom > ddBottom) {
                        $(dropdown).scrollTop(ddScrollTop + (selectedBottom - ddBottom));
                    } else if (ddTop > selectedTop) {
                        $(dropdown).scrollTop(ddScrollTop + (selectedTop - ddTop));
                    }
                    return false;
                }
                if (e.keyCode == 38) { 
                    if(chosen === "") {
                        chosen = 0;
                    } else if(chosen > 0) {
                        chosen--;            
                    }
                    $('#customer_list li').removeClass('selected');
                    $('#customer_list li:eq('+chosen+')').addClass('selected');
                    var selectedTop = $('.selected').offset().top;
                           
                    var selectedBottom = selectedTop + $('#customer_list li:eq('+chosen+')').outerHeight();
                    var ddTop = $(dropdown).offset().top
                    var ddBottom = ddTop + $(dropdown).outerHeight();
                    var ddScrollTop = $(dropdown).scrollTop();

                    if (selectedBottom > ddBottom) {
                        $(dropdown).scrollTop(ddScrollTop + (selectedBottom - ddBottom));
                    } else if (ddTop > selectedTop) {
                        $(dropdown).scrollTop(ddScrollTop + (selectedTop - ddTop));
                    }
                    return false;
                }
            }
        }
    });
    
    var chosen = '';
    $("input[id*='sku_']").live('keyup',function(e){
        var id = $(this).attr('id').split('_');
        if(e.keyCode == 13){
            $('#item_list .selected').trigger('click');
            $('#item_list').hide();
       
        }else{
            if (e.keyCode != 40 && e.keyCode != 38) { 
                $('#customer_list').hide();
                var keyword = $(this).val();
                // alert(keyword)
                vartype='';
                if(journalID==12 || journalID==36 || journalID==32 || journalID==10 || journalID==9 || journalID==13|| journalID==30){
                    type = 'cm';
                }else if(journalID==3 || journalID==4 || journalID==6|| journalID==7|| journalID==31){
                    type = 'vm';
                }else if(journalID==18){
                    type='c';
                }else if(journalID==20){
                    type='v';
                }
                $.ajax({
                    type: "GET",
                    url: 'index.php?module=ucbooks&page=ajax&op=autocopmplete&type='+type+'&journalID='+journalID+'&action=item&row='+id[1]+'&keyword='+keyword,
                    dataType: "text",
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
                    },
                    success: function(data){
                        //alert(data);
                        if(data!=''){
                            chosen = -1;
                            $('#item_table').find('.input_container1').attr('class','')
                            $('#item_table').find('#item_list').remove()
                            $('#sku_'+id[1]).parent('span').attr('class','input_container1');
                            $('.input_container1').append('<ul id="item_list"></ul>');
                            $('#item_list').show();
                            $('#item_list').html(data);
                       
                    
                        }else{
                            $('#item_list').hide();
                        }
                    }
                });
            }else{
                var dropdown =  '#item_list';
                if (e.keyCode == 40) { 
                    if(chosen === "") {
                        chosen = 0;
                    } else if((chosen+1) < $('#item_list li').length) {
                        chosen++; 
                    }
                    $('#item_list li').removeClass('selected');
                    $('#item_list li:eq('+chosen+')').addClass('selected');
                    var selectedTop = $('.selected').offset().top;
                    var selectedBottom = selectedTop + $('#item_list li:eq('+chosen+')').outerHeight();
                    var ddTop = $(dropdown).offset().top
                    var ddBottom = ddTop + $(dropdown).outerHeight();
                    var ddScrollTop = $(dropdown).scrollTop();

                    if (selectedBottom > ddBottom) {
                        $(dropdown).scrollTop(ddScrollTop + (selectedBottom - ddBottom));
                    } else if (ddTop > selectedTop) {
                        $(dropdown).scrollTop(ddScrollTop + (selectedTop - ddTop));
                    }
                    return false;
                }
                if (e.keyCode == 38) { 
                    if(chosen === "") {
                        chosen = 0;
                    } else if(chosen > 0) {
                        chosen--;            
                    }
                    $('#item_list li').removeClass('selected');
                    $('#item_list li:eq('+chosen+')').addClass('selected');
                    var selectedTop = $('.selected').offset().top;
                    var selectedBottom = selectedTop + $('#item_list li:eq('+chosen+')').outerHeight();
                    var ddTop = $(dropdown).offset().top
                    var ddBottom = ddTop + $(dropdown).outerHeight();
                    var ddScrollTop = $(dropdown).scrollTop();

                    if (selectedBottom > ddBottom) {
                        $(dropdown).scrollTop(ddScrollTop + (selectedBottom - ddBottom));
                    } else if (ddTop > selectedTop) {
                        $(dropdown).scrollTop(ddScrollTop + (selectedTop - ddTop));
                    }
                    return false;
                }
      
            }
        }
    });
    $(document).ready(function(){
//        $("input[id*='sku_']").live('focusout',function(){
//            $('#item_list').hide();
//        });
//        $("#search").live('focusout',function(){
//            $('#customer_list').hide();
//        });
    });
 
    $('#file_name').live('blur',function(){
        var n = $(document).height()    ;
        $("html, body").animate({
            scrollTop: 1000
        },500);
        
        //$("html, body").scrollTop($("#productList").offset().top);
    })
    $('#item_cnt_1').live('blur',function(){
        $("#hidden_btn").css('border','0 none');
        $("#hidden_btn").attr('rel','');
    })
    $('#total').live('blur',function(e){
        $('#bottom_btn').hide();
        $('.bottom_btn #tb_icon_save').css('background','none !important');
        $('.bottom_btn #tb_icon_save').css('color','black !important');
        $('.bottom_btn #tb_icon_save').removeClass('button_bg');
        $('.bottom_btn #tb_icon_save').addClass('btn_save');
        // var n = $(document).height()    ;
        $("html, body").scrollTop($(".footer").offset().top);;
   
    })
    function setReturnItem(iID,rID) {
    
        loadSkuDetails(iID, rID);
        $('#item_table #item_list').hide();
    }
    function setReturnAccount(cID) {
        var fill='';
        var oID = 0; // contact only
        if (fill == 'ship') {
            var ship_only = true;
            clearAddress('ship');
        } else {
            var ship_only = false;
            ClearForm();
        }
      
        ajaxOrderData(cID, oID, journalID, false, ship_only);
       
        $('#customer_list').hide();
    }

    
    
    function check_form() {
        var error = 0;
        var i, stock, qty, inactive, message;
        var error_message = "<?php echo JS_ERROR; ?>";
        var todo = document.getElementById('todo').value;
        if (single_line_list=='1') {
            var numRows = document.getElementById('item_table').rows.length;
        } else {
            var numRows = document.getElementById('item_table').rows.length;
        }

        // With edit of order and recur, ask if roll through future entries or only this entry
        if (document.getElementById('id').value != "" && document.getElementById('recur_id').value > 0) {
            switch (todo) {
                case 'delete':
                    message = '<?php echo ORD_JS_RECUR_DEL_ROLL_REQD; ?>';
                    break;
                default:
                case 'save':
                    message = '<?php echo ORD_JS_RECUR_ROLL_REQD; ?>';
                }
                if (confirm(message)) {
                    document.getElementById('recur_frequency').value = '1';
                } else {
                    document.getElementById('recur_frequency').value = '0';
                }		    
            }

            switch (journalID) {
                case  '6':
                case  '7':
           
                    // Check for purchase_invoice_id exists with a recurring entry
                    if (document.getElementById('purchase_invoice_id').value == "" && document.getElementById('recur_id').value > 0) {
                        error_message += "<?php echo ORD_JS_RECUR_NO_INVOICE; ?>";
                        error = 1; // exit the script
                        break;
                    }
                    // validate that for purchases, either the waiting box needs to be checked or an invoice number needs to be entered
                    //	  if (document.orders.purchase_invoice_id.value == "" && !document.orders.waiting.checked) {
                    //		error_message += "<?php echo ORD_JS_WAITING_FOR_PAYMENT; ?>";
                    //		error = 1; // exit the script
                    //                break;
                    //	  }
                    for (var i=1; i<numRows; i++) {
                        if($('.invoice_table #pstd_'+i).val()== ""){
                    
                            alert('The Received must be non-blank');
                            $('.invoice_table #pstd_'+i).focus();
                            return false;
                        }
                    }
                    break;
                case  '9':
                case '10':
                case '32':
                case '36':
                case '12':
                case '30':    
                    //validate item status (inactive, out of stock [SO] etc.)
                    for (var i=1; i<numRows; i++) {
                        if (document.getElementById('inactive_'+i).value=='1') {
                            if (!confirm(inactive_a + document.getElementById('sku_'+i).value + inactive_b)) return false;
                        }
                        if (document.getElementById('stock_'+i).value=='NA') continue; // skip if we don't care about inventory
                        if(journalID == '12'){
                            qty = (journalID == '12') ? parseFloat(document.getElementById('pstd_'+i).value) : parseFloat(document.getElementById('qty_'+i).value);
                        }else{
                            qty = (journalID == '36') ? parseFloat(document.getElementById('pstd_'+i).value) : parseFloat(document.getElementById('qty_'+i).value);
                        }
                        
                        stock = parseFloat(document.getElementById('stock_'+i).value);
                        if (qty > stock) {
                            if (!confirm(no_stock_a + document.getElementById('sku_'+i).value + no_stock_b + stock + no_stock_c)) return false;
                        }
                
                        if($('.invoice_table #pstd_'+i).val()== ""){
                    
                            alert('The quantity must be non-blank');
                            $('.invoice_table #pstd_'+i).focus();
                            return false;
                        }
               
                    }
                    break;
                case  '3':
                case  '4':
                case '13':
                case '18':
                case '20':
                default:
                }

                if (error == 1) {
                    alert(error_message);
                    return false;
                }
                return true;
            }

            // Insert other page specific functions here.
            function salesTaxes(id, text, rate) {
                this.id   = id;
                this.text = text;
                this.rate = rate;
            }
            // Insert other page specific functions here.
            function uom_option(id, text) {
                this.id   = id;
                this.text = text;

            }

            function printOrder(id) {
                var printWin = window.open("index.php?module=ucform&page=popup_gen&gID=<?php echo POPUP_FORM_TYPE; ?>&date=a&xfld=journal_main.id&xcr=EQUAL&xmin="+id,"reportFilter","width=1150px,height=550px,resizable=1,scrollbars=1,top=150px,left=100px");
                printWin.focus();
            }
<?php if (ORD_ENABLE_LINE_ITEM_BAR_CODE) { ?>
                function refreshOrderClock() {
                    if (resClockID) {
                        clearTimeout(resClockID);
                        resClockID = 0;
                    }
                    if (setId) { // call the ajax to load the inventory info
                        var upc = document.getElementById('sku_'+setId).value;
                        if (upc != text_search && upc.length == skuLength) {
                            var acct = document.getElementById('bill_acct_id').value;
                            switch (journalID) {
                                case  '3':
                                case  '4':
                                case  '9':
                                case '10':
                                case '32':
                                    var qty = document.getElementById('qty_'+setId).value;
                                    break;
                                case  '6':
                                case  '7':
                                case '12':
                                case '36':
                                case '13':
                                case '18':
                                case '20':
                                case '30':
                                case '31':    
                                    var qty = document.getElementById('pstd_'+setId).value;
                                    break;
                                default:
                            }
                            $.ajax({
                                type: "GET",
                                url: 'index.php?module=inventory&page=ajax&op=inv_details&fID=skuDetails&cID='+acct+'&qty='+qty+'&upc='+upc+'&rID='+setId+'&jID='+journalID,
                                dataType: ($.browser.msie) ? "text" : "xml",
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
                                },
                                success: fillInventory
                            });
                            setId = 0;
                        }
                    }
                    resClockID = setTimeout("refreshOrderClock()", 250);
                }
<?php } ?>

            // -->
</script>
<script type="text/javascript" src="modules/ucbooks/javascript/orders.js"></script>
