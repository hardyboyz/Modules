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
//  Path: /modules/ucbooks/pages/bills/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    // Include translations here as well.
    var pmt_array          = new Array(); // holds the encrypted payment information
    var add_array          = new Array("<?php echo implode('", "', $js_arrays['fields']); ?>");
    //var company_array    = new Array("<?php echo implode('", "', $js_arrays['company']); ?>");
    var securityLevel      = <?php echo $security_level; ?>;
    var default_array      = new Array("<?php echo implode('", "', $js_arrays['text']); ?>");
    var defaultPostDate    = '<?php echo date(DATE_FORMAT, time()); ?>';
    var defaultGlAcct      = '<?php echo JOURNAL_ID == 18 ? AR_SALES_RECEIPTS_ACCOUNT : AP_PURCHASE_INVOICE_ACCOUNT; ?>';
    var defaultDiscAcct    = '<?php echo JOURNAL_ID == 18 ? AR_DISCOUNT_SALES_ACCOUNT : AP_DISCOUNT_PURCHASE_ACCOUNT; ?>';
    var journalID          = '<?php echo JOURNAL_ID; ?>';
    var text_enter_new     = '<?php echo TEXT_ENTER_NEW; ?>';
    var post_error         = <?php echo $error ? "true;" : "false;"; ?>
    var account_type       = '<?php echo $account_type; ?>';
    var store_country_code = '<?php echo STORE_COUNTRY; ?>';
    var payments_installed = <?php echo count($payment_modules) ? 'true' : 'false'; ?>;
<?php echo js_calendar_init($cal_bills); ?>

    function init() {
        document.getElementById('bill_to_select').style.visibility = 'hidden';
        if (journalID == '18') activateFields();
        // change color of the bill and ship address fields if they are the default values
        var add_id;
        for (var i=0; i<add_array.length; i++) {
            add_id = add_array[i];
            if (document.getElementById('bill_'+add_id).value == default_array[i]) {
                document.getElementById('bill_'+add_id).style.color = inactive_text_color;
            }
        }
        document.getElementById('search').focus();

        
<?php
if ($action == 'edit') { // if paying from sales window automatically check first box
    echo 'ajaxBillData(0, ' . $oID . ', ' . JOURNAL_ID . ');';
} else if ($action == 'pmt') {
    echo 'loadNewPayment();' . chr(10);
    echo 'updateTotalPrices();' . chr(10);
} else {
    echo 'updateTotalPrices();' . chr(10);
}
?>

<?php if ($post_success && $action == 'print') { ?>
            ClearForm();
            var printWin = window.open("index.php?module=ucform&page=popup_gen&gID=<?php echo POPUP_FORM_TYPE; ?>&date=a&xfld=journal_main.id&xcr=EQUAL&xmin=<?php echo $print_record_id; ?>","reportFilter","width=1150px,height=550px,resizable=1,scrollbars=1,top=150px,left=100px");
            printWin.focus();
<?php } ?>
        $("#search").change(function(){
            // if(document.getElementById('search').value != ''){ AccountList(); }
        });
    }

    function check_form() {
        var error = 0;
        var error_message = "<?php echo JS_ERROR; ?>";

        var todo = document.getElementById('todo').value;

        if (journalID == '18' && (todo == 'save' || todo == 'print')) { // only check payment if saving
            var index = document.getElementById('shipper_code').selectedIndex;
            var payment_method = document.getElementById('shipper_code').options[index].value;
<?php
foreach ($payment_modules as $pmt_class) { // fetch the javascript validation of payments module
    $value = $pmt_class['id'];
    echo $$value->javascript_validation();
}
?>
        }

        if (error == 1) {
            alert(error_message);
            return false;
        }
        return true;
    }
    //    $('#search').live('keypress',function(){
    //        alert('test')
    //    })
    $(document).keydown(function(e) {
        if(e.keyCode==9){
            $("#search").focusout(function(){
                $('#customer_list .selected').trigger('click');
                $('#customer_list').hide();
            })
        }
    });
    var chosen = '';
    $('#search').live('keyup',function(e){
        if(e.keyCode == 13){
            $('#customer_list .selected').trigger('click');
            $('#customer_list').hide();
       
        }else{
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
                        $('#customer_list').show();
                        $('#customer_list').html(data);
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
      
                    
                    }else{
                        $('#customer_list').hide();
                    }
                }
            });
        }
    });
   
        $("#search").focusout(function(){
            $('#customer_list').hide();
        });
   
    var chosen = '';
    $('#search1').live('keydown', function(e) {
        //$('#search').focusout();
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
        if(e.keyCode == 13){
            $('.selected').click();
       
        }
    });
    
    function setReturnEntry(cID) {
       
        var oID = 0; // contact only
        ClearForm()
      
        ajaxBillData(cID, oID, journalID);
       
        $('#customer_list').hide();
    }
            
   
            
    // Insert other page specific functions here.
    // ******* AJAX payment stored payment values request function pair *********/
    function loadNewPayment() { // request funtion

        var contact_id = document.getElementById('bill_acct_id').value;
        if (!contact_id) return;
        $.ajax({
            type: "GET",
            url: 'index.php?module=ucbooks&page=ajax&op=stored_payments&contact_id='+contact_id,
            dataType: ($.browser.msie) ? "text" : "xml",
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
            },
            success: showNewPayment
        });
    }

    function showNewPayment(sXml) { // call back function

        var show = false;
        var xml  = parseXml(sXml);
        if (!xml) return;
        while (document.getElementById('payment_id').options.length) document.getElementById('payment_id').remove(0);
        // build the dropdown
        newOpt = document.createElement("option");
        newOpt.text = '<?php echo TEXT_ENTER_NEW; ?>';
        document.getElementById('payment_id').options.add(newOpt);	
        document.getElementById('payment_id').options[0].value = '';
        pmt_array[0] = new Object();
        pmt_array[0].field_0 = '';
        pmt_array[0].field_1 = '';
        pmt_array[0].field_2 = '';
        pmt_array[0].field_3 = '';
        pmt_array[0].field_4 = '';
        pmt_array[0].field_5 = '';
        pmt_array[0].field_6 = '';
        var jIndex = 1;
        $(xml).find("Payment").each(function() {
     
            show        = true;
            newOpt      = document.createElement("option");
            newOpt.text = $(this).find("name").text();
            if ($(this).find("hint").text()) newOpt.text += ', ' + $(this).find("hint").text();
            document.getElementById('payment_id').options.add(newOpt);
            document.getElementById('payment_id').options[jIndex].value = $(this).find("id").text();
            pmt_array[jIndex] = new Object();
       
            if ($(this).find("field_0").text()) pmt_array[jIndex].field_0 = $(this).find("field_0").text();
            if ($(this).find("field_1").text()) pmt_array[jIndex].field_1 = $(this).find("field_1").text();
            if ($(this).find("field_2").text()) pmt_array[jIndex].field_2 = $(this).find("field_2").text();
            if ($(this).find("field_3").text()) pmt_array[jIndex].field_3 = $(this).find("field_3").text();
            if ($(this).find("field_4").text()) pmt_array[jIndex].field_4 = $(this).find("field_4").text();
            if ($(this).find("field_5").text()) pmt_array[jIndex].field_5 = $(this).find("field_5").text();
            if ($(this).find("field_6").text()) pmt_array[jIndex].field_6 = $(this).find("field_6").text();
            jIndex++;
        });
        document.getElementById('payment_id').style.visibility = show ? '' : 'hidden';
    }
    // ******* END - AJAX payment stored payment values request function pair *********/

    function fillPayment() {
        var index = document.getElementById('shipper_code').selectedIndex;
        var pmtMethod = document.getElementById('shipper_code').options[index].value;
        var pmtIndex = document.getElementById('payment_id').selectedIndex;
        if (document.getElementById(pmtMethod+'_field_0')) 
            document.getElementById(pmtMethod+'_field_0').value = pmt_array[pmtIndex].field_0;
        if (document.getElementById(pmtMethod+'_field_1')) 
            document.getElementById(pmtMethod+'_field_1').value = pmt_array[pmtIndex].field_1;
        if (document.getElementById(pmtMethod+'_field_2')) 
            document.getElementById(pmtMethod+'_field_2').value = pmt_array[pmtIndex].field_2;
        if (document.getElementById(pmtMethod+'_field_3')) 
            document.getElementById(pmtMethod+'_field_3').value = pmt_array[pmtIndex].field_3;
        if (document.getElementById(pmtMethod+'_field_4')) 
            document.getElementById(pmtMethod+'_field_4').value = pmt_array[pmtIndex].field_4;
        if (document.getElementById(pmtMethod+'_field_5')) 
            document.getElementById(pmtMethod+'_field_5').value = pmt_array[pmtIndex].field_5;
        if (document.getElementById(pmtMethod+'_field_6')) 
            document.getElementById(pmtMethod+'_field_6').value = pmt_array[pmtIndex].field_6;
    }

    // -->
</script>

<script type="text/javascript" src="modules/ucbooks/javascript/banking.js"></script>
