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
//  Path: /modules/ucbooks/pages/journal/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    // Include translations here as well.
    var image_delete_text = '<?php echo TEXT_DELETE; ?>';
    var image_delete_msg  = '<?php echo GL_DELETE_GL_ROW; ?>';
    var text_acct_ID      = '<?php echo TEXT_GL_ACCOUNT; ?>';
    var text_increased    = '<?php echo GL_ACCOUNT_INCREASED; ?>';
    var text_decreased    = '<?php echo GL_ACCOUNT_DECREASED; ?>';
    var journalID         = '<?php echo JOURNAL_ID; ?>';
    var sub_journalID         = '<?php echo SUB_JOURNAL_ID; ?>';
    var securityLevel     = <?php echo $security_level; ?>;
<?php echo js_calendar_init($cal_gl); ?>

<?php echo $js_gl_array; ?>
<?php echo $js_tax_rates; ?>
<?php echo $js_gl_array2; ?>

    function init() {
<?php if ($action == 'edit') echo '  EditJournal(' . $oID . ');' . chr(10); ?>
        document.getElementById("purchase_invoice_id").focus();
    }

    function check_form() {
        var error = 0;
        var error_message = "<?php echo JS_ERROR; ?>";
        // check for balance of credits and debits
        var bal_total = cleanCurrency(document.getElementById('balance_total').value);
        if (bal_total != 0) {
            error_message += "<?php echo GL_ERROR_OUT_OF_BALANCE; ?>";
            error = 1;
        }
        // check for all accounts valid
        for (var i = 1; i <= ((document.getElementById("item_table").rows.length - 1) / 2); i++) {
            if (!updateDesc(i)) {
                error_message += "<?php echo GL_ERROR_BAD_ACCOUNT; ?>";
                error = 1;
                break;
            }
        }
        // With edit of order and recur, ask if roll through future entries or only this entry
        var todo = document.getElementById('todo').value;
        if (document.getElementById('id').value != "" && document.getElementById('recur_id').value > 0) {
            switch (todo) {
                case 'delete':
                    message = '<?php echo GL_ERROR_RECUR_DEL_ROLL_REQD; ?>';
                    break;
                default:
                case 'save':
                    message = '<?php echo GL_ERROR_RECUR_ROLL_REQD; ?>';
                }
                if (confirm(message)) {
                    document.getElementById('recur_frequency').value = '1';
                } else {
                    document.getElementById('recur_frequency').value = '0';
                }		    
            }
            // Check for purchase_invoice_id exists with a recurring entry
            if (document.getElementById('purchase_invoice_id').value == "" && document.getElementById('recur_id').value > 0) {
                error_message += "<?php echo GL_ERROR_NO_REFERENCE; ?>";
                error = 1; // exit the script
            }

            if (error == 1) {
                alert(error_message);
                return false;
            }
            return true;
        }

        function OpenGLList() {
            window.open("index.php?module=ucbooks&page=popup_journal&list=1&form=journal","gl_open","width=1150,height=550,resizable=1,scrollbars=1,top=150,left=100");
        }

        function OpenRecurList(currObj) {
            window.open("index.php?module=ucbooks&page=popup_recur&jID="+journalID,"recur","width=1150px,height=300px,resizable=1,scrollbars=1,top=150,left=100");
        }

        function verifyCopy() {
            var id = document.getElementById('id').value;
            if (!id) {
                alert('<?php echo GL_JS_CANNOT_COPY; ?>');
                return;
            }
            if (confirm('<?php echo GL_JS_COPY_CONFIRM; ?>')) {
                // don't allow recurring entries for copy
                document.getElementById('recur_id').value        = '0';
                document.getElementById('recur_frequency').value = '0';
                submitToDo('copy');
            }
        }

        // Insert other page specific functions here.
        function EditJournal(rID) {
            $.ajax({
                type: "GET",
                url: 'index.php?module=ucbooks&page=ajax&op=load_record&rID='+rID,
                dataType: ($.browser.msie) ? "text" : "xml",
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
                },
                success: processEditJournal
            });
        }

        function processEditJournal(sXml) {
            var DebitOrCredit;
            var xml = parseXml(sXml);
            if (!xml) return;
            //document.getElementById('auto_complete').checked = false;
            var id = $(xml).find("id").first().text();
            document.getElementById('id').value        = id;
            if ($(xml).find("purchase_invoice_id").text()) document.getElementById('purchase_invoice_id').value = $(xml).find("purchase_invoice_id").text();
            if ($(xml).find("purch_order_id").text()) document.getElementById('purch_order_id').value = $(xml).find("purch_order_id").text();
           
            document.getElementById('post_date').value = formatDate($(xml).find("post_date").first().text());
            document.getElementById('recur_id').value  = $(xml).find("recur_id").text();
            document.getElementById('store_id').value  = $(xml).find("store_id").text();
            if($(xml).find("tax_inclusive").text() == 1){
                $('#tax_inclusive').attr('checked','checked');
            }
            // delete the rows
            while (document.getElementById("item_table").rows.length > 0) document.getElementById("item_table").deleteRow(-1);
            // turn off some icons
            if (id && securityLevel < 3) {
                removeElement('tb_main_0', 'tb_icon_recur');
                removeElement('tb_main_0', 'tb_icon_save');
            }
            // fill item rows
            var jIndex = 1;
            $(xml).find("items").each(function(i,v) {
            
                if($(this).find("debit_amount").text()==0){
                  
                    var tax = 0 ;
                    var rowCnt = addGLRow('','','',$(this).find("taxable").text());
                    document.getElementById('id_'+jIndex).value     = $(this).find("id").text();
                    document.getElementById('acct_'+jIndex).value   = $(this).find("gl_account").text();
                    if ($(this).find("description").text()) document.getElementById('desc_'+jIndex).value = $(this).find("description").text();
                    var tax_index = document.getElementById('tax_'+jIndex).selectedIndex;
                    if($(xml).find("tax_inclusive").text() == 1){
          
                        // if($(this).find("debit_amount").text() !=0 && $(this).find("credit_amount").text() == 0){
                        tax =  parseFloat($(this).find("credit_amount").text()) * (tax_rates[tax_index].rate / (parseFloat(100)));
                        // alert(tax)
                        //  }else{
                        //  tax =  parseFloat($(this).find("credit_amount").text()) * (tax_rates[tax_index].rate / (parseFloat(100)));
                        // }
            
                    }
                    document.getElementById('tax_0').value = tax;
                    document.getElementById('debit_'+jIndex).value  = formatCurrency(parseFloat($(this).find("debit_amount").text()));
                
                    document.getElementById('credit_'+jIndex).value = formatCurrency(parseFloat($(this).find("credit_amount").text())+parseFloat(tax));
               
                    DebitOrCredit = ($(this).find("credit_amount").text() != 0) ? 'd' : 'c';
                    formatRow(jIndex, DebitOrCredit);
                    jIndex++;
                    //}else{
                    //    jIndex++;
                    //}
                }else{
                    document.getElementById('acct_0').value   = $(this).find("gl_account").text();
                }
            });
             // updateBalance();
            if ($(xml).find("closed").text() == '1') alert('<?php echo WARNING_ENTRY_RECONCILED; ?>');
            
        }
        function get_gl(){
             for (var i=0; i<js_gl_array2.length; i++) {
                
                newOpt = document.createElement("option");
                newOpt.text = js_gl_array2[i].description;
                document.getElementById('acct_0').options.add(newOpt);
                document.getElementById('acct_0').options[i].value = js_gl_array2[i].id;
            }
        }

        function glProperties(id, description, asset) {
            this.id          = id;
            this.description = description;
            this.asset       = asset;
        }

        // Insert other page specific functions here.
        function salesTaxes(id, text, rate) {
            this.id   = id;
            this.text = text;
            this.rate = rate;
        }
        function submitToDooo(todo){
            document.getElementById('todo').value = todo;
            document.getElementById('todo').form.submit();
          
        }
        function addGLRow(debit, credit, description, tax_val) {
            if (!debit)       debit  = '';
            if (!credit)      credit = '';
            if (!description) description = '';
            if (!tax_val)     tax_val = '';
            var cell = new Array();
            var newRow = document.getElementById("item_table").insertRow(-1);
            var rowCnt = newRow.rowIndex;
            // NOTE: any change here also need to be made below for reload if action fails
            cell[0]  = '<td>';
            cell[0] += buildIcon(icon_path+'16x16/emblems/emblem-unreadable.png', image_delete_text, 'style="cursor:pointer" onclick="if (confirm(\''+image_delete_msg+'\')) removeGLRow('+rowCnt+');"') + '<\/td>';
            cell[2]  = '<td>';
            cell[2] += '<select style="width:300px;" name="acct_'+rowCnt+'" id="acct_'+rowCnt+'" onchange="updateDesc('+rowCnt+')"><\/select>&nbsp;';
            // Hidden fields
            cell[2] += '<input type="hidden" name="id_'+rowCnt+'" id="id_'+rowCnt+'" value="" />';
            // End hidden fields
            cell[1] = '<td><input style="width:350px !important;" type="text" name="desc_'+rowCnt+'" id="desc_'+rowCnt+'" value="'+description+'"><\/td>';
            cell[3] = '<td><select name="tax_'+rowCnt+'" id="tax_'+rowCnt+'" onchange="formatRow('+rowCnt+', \'\')"<\/select>&nbsp;</td>';
            cell[5] = '<td><input type="hidden" name="debit_'+rowCnt+'" id="debit_'+rowCnt+'" style="text-align:right" size="13" maxlength="12"  value="'+debit+'" onchange="formatRow('+rowCnt+', \'d\')"><\/td>';
            cell[4] = '<td><input type="text" name="credit_'+rowCnt+'" id="credit_'+rowCnt+'" style="text-align:right" size="13" maxlength="12" value="'+credit+'"onchange="formatRow('+rowCnt+', \'c\')"><\/td>';
            var newCell, cellCnt, newDiv, divIdName, newDiv, newOpt;
            for (var i=0; i<cell.length; i++) {
                newCell = newRow.insertCell(-1);
                newCell.innerHTML = cell[i];
            }
            // build the account dropdown
            for (i=0; i<js_gl_array.length; i++) {
                newOpt = document.createElement("option");
                newOpt.text = js_gl_array[i].description;
                document.getElementById('acct_'+rowCnt).options.add(newOpt);
                document.getElementById('acct_'+rowCnt).options[i].value = js_gl_array[i].id;
            }
            for (var i=0; i<tax_rates.length; i++) {
                newOpt = document.createElement("option");
                newOpt.text = tax_rates[i].text;
                document.getElementById('tax_'+rowCnt).options.add(newOpt);
                document.getElementById('tax_'+rowCnt).options[i].value = tax_rates[i].id;
                if(tax_val!=''){
                    if(tax_val == tax_rates[i].id){
                        $('#tax_'+rowCnt+' option[value='+tax_val+']').attr('selected','selected');
                    }
                }
            }
            //            // insert information row
            //            newRow = document.getElementById("item_table").insertRow(-1);
            //            newRow.className += ' ui-state-highlight';
            //            newCell = newRow.insertCell(-1);
            //            newCell.colSpan = 3;
            //            newCell.innerHTML = '<td colspan="3">&nbsp;<\/td>';
            //            newCell = newRow.insertCell(-1);
            //            newCell.colSpan = 2;
            //            newCell.innerHTML = '<td colspan="2" id="msg_'+rowCnt+'">&nbsp;<\/td>';
            document.getElementById("acct_" + rowCnt).focus();
            return rowCnt;
        }

        function removeGLRow(delRowCnt) {
            var glIndex = delRowCnt;
            // remove row from display by reindexing and then deleting last row
            for (var i = delRowCnt; i < (document.getElementById("item_table").rows.length); i++) {
                // remaining cell values
                document.getElementById('acct_'+i).value   = document.getElementById('acct_'+(i)).value;
                document.getElementById('desc_'+i).value   = document.getElementById('desc_'+(i)).value;
                document.getElementById('tax_'+i).value   = document.getElementById('tax_'+(i)).value;
                document.getElementById('debit_'+i).value  = document.getElementById('debit_'+(i)).value;
                document.getElementById('credit_'+i).value = document.getElementById('credit_'+(i)).value;
                // Hidden fields
                document.getElementById('id_'+i).value = document.getElementById('id_'+(i+1)).value;
                // End hidden fields
                // move information fields
                document.getElementById("item_table").rows[glIndex].cells[0].innerHTML = document.getElementById("item_table").rows[glIndex].cells[0].innerHTML;
                document.getElementById("item_table").rows[glIndex].cells[1].innerHTML = document.getElementById("item_table").rows[glIndex].cells[1].innerHTML;
                glIndex = glIndex; // increment the row counter (two rows per entry)
            }
            document.getElementById("item_table").deleteRow(-1);
            // document.getElementById("item_table").deleteRow(-1);
            updateBalance(true);
        }

        function showAction(rowID, DebitOrCredit) {
            var acct = document.getElementById('acct_'+rowID).value;
            var textValue = ' ';
            for (var i = 0; i < js_gl_array.length; i++) {
                if (js_gl_array[i].id == acct) {
                    if ((js_gl_array[i].asset == '1' && DebitOrCredit == 'd') || (js_gl_array[i].asset == '0' && DebitOrCredit == 'c')) {
                        textValue = text_increased;
                        
                    } else {
                        textValue = text_decreased;
                    }
                    break;
                }
            }
            if(journalID==34 || sub_journalID==2 || sub_journalID==3 || journalID==35){
            
                if(rowID==1){
                    if (document.getElementById('credit_'+rowID).value == '') {
                        textValue = ' ';
                    } 
                }else{
                    if (document.getElementById('debit_'+rowID).value == '') {
                        textValue = ' ';
                    }  
                }
                
            }else{
               
                if (document.getElementById('debit_'+rowID).value == '' && document.getElementById('credit_'+rowID).value == '') {
                    textValue = ' ';
                }
            }
            if(document.all) { // IE browsers
                document.getElementById("item_table").rows[(rowID*2)-1].cells[1].innerText = textValue;  
            } else { //firefox
                document.getElementById("item_table").rows[(rowID*2)-1].cells[1].textContent = textValue;  
            }
        }

        function formatRow(rowID, DebitOrCredit) {
            var temp;
            
            //showAction(rowID, DebitOrCredit);
            if (DebitOrCredit == 'c') {
                if (document.getElementById('credit_'+rowID).value != '') {
                    temp = cleanCurrency(document.getElementById('credit_'+rowID).value);
                    document.getElementById('credit_'+rowID).value = formatCurrency(temp);
                   
                }
            } else {
                if(rowID==1){
                    if (document.getElementById('debit_'+rowID).value != '') {
                        temp = cleanCurrency(document.getElementById('debit_'+rowID).value);
                        document.getElementById('debit_'+rowID).value = formatCurrency(temp);
                    }
                }else{
                    if (document.getElementById('credit_'+rowID).value != '') {
                        temp = cleanCurrency(document.getElementById('credit_'+rowID).value);
                        document.getElementById('credit_'+rowID).value = formatCurrency(temp);
                   
                    } 
                }
            }
            updateBalance();
        }
       

        function updateBalance() {
  
            var debit_total = 0;
            var credit_total = 0;
            var balance_total = 0;
            var description = '';
            var taxable_subtotal_debit = 0;
            var taxable_subtotal_credit = 0;
            var tax_index = '';
            var debit_amount = 0;
            var credit_amount = 0;
            for (var i = 1; i <= (document.getElementById('item_table').rows.length); i++) {
                temp = parseFloat(cleanCurrency(document.getElementById('debit_'+i).value));
                if (!isNaN(temp)) {
                    debit_total += temp;
                    debit_amount = temp;
                }
                //alert(debit_total)
                temp = parseFloat(cleanCurrency(document.getElementById('credit_'+i).value));
                if (!isNaN(temp)){
                    credit_total += temp;
                    credit_amount = temp;
                }
             
                description = document.getElementById('desc_'+i).value;
                var tax = document.getElementById('tax_'+i).value;
                if (tax != '0') {
                    tax_index = document.getElementById('tax_'+i).selectedIndex;
                    if (tax_index == -1) { // if the rate array index is not defined
                        tax_index = 0;
                        document.getElementById('tax_'+i).value = tax_index;
                    }
                    if(debit_amount != 0){
                        if(document.getElementById('tax_inclusive').checked == true){
                            taxable_subtotal_debit += debit_amount * (tax_rates[tax_index].rate / (parseFloat(100)+parseFloat(tax_rates[tax_index].rate)));
                        }else{
                            taxable_subtotal_debit += debit_amount * (tax_rates[tax_index].rate / 100);
                        }
                
                    }
                    if(credit_amount != 0){
                        if(document.getElementById('tax_inclusive').checked == true){
                            taxable_subtotal_credit += credit_amount * (tax_rates[tax_index].rate / (parseFloat(100)+parseFloat(tax_rates[tax_index].rate)));
                        }else{
                            taxable_subtotal_credit += credit_amount * (tax_rates[tax_index].rate / 100);
                        }
                
                    }
                    debit_amount = 0;
                    credit_amount = 0
                    var tt = taxable_subtotal_debit+taxable_subtotal_credit;
                    var ttv = new String(tt);
                    document.getElementById('tax_total').value = formatCurrency(ttv);
                    document.getElementById('tax_0').value = formatCurrency(ttv);
                    //taxable_subtotal = 0;
                }else{
                    if(debit_amount != 0){
                        taxable_subtotal_debit += debit_amount * (0 / 100);
                    }
                    if(credit_amount != 0){
                        taxable_subtotal_credit += credit_amount * (0 / 100);
                    }
            
                    debit_amount = 0;
                    credit_amount = 0
                    var tt = taxable_subtotal_debit+taxable_subtotal_credit;
                    var ttv = new String(tt);
                    document.getElementById('tax_total').value = formatCurrency(ttv);
                    document.getElementById('tax_0').value = formatCurrency(ttv);
                }
            }
            // alert(taxable_subtotal_debit)
            var credit_0 = credit_total;
  
            //debit_total = debit_total+parseInt(document.getElementById('tax_total').value);
            if(document.getElementById('tax_inclusive').checked == true){
            
                debit_total = debit_total;
                credit_total = credit_total;
            }else{
                debit_total = debit_total+taxable_subtotal_debit;
                credit_total = credit_total+taxable_subtotal_credit;
            }
//            credit_total = debit_total;
//            if(debit_total>credit_total ){
//                if(debit_total == credit_total){
//                    balance_total = 0; 
//                }else{
//                    balance_total = debit_total-credit_total;
//                }
//
//            }else if(debit_total<credit_total ){
//                if(credit_total ==  debit_total){
//                    balance_total = 0; 
//                }else{
//           
//                    balance_total =    credit_total - debit_total;
//                }
//            } else if(debit_total == credit_total) {
//                balance_total = 0;
//            }
  
  
  
            //balance_total = debit_total - credit_total;
            var dt = new String(credit_total);
           
            document.getElementById('debit_total').value = formatCurrency(dt);
            var ct = new String(credit_total);
            var ct0 = new String(credit_0);
            document.getElementById('debit_0').value = formatCurrency(ct0);
            document.getElementById('credit_total').value = formatCurrency(ct);
            var tot = new String(balance_total);
            document.getElementById('balance_total').value = formatCurrency(tot);
            if (document.getElementById('balance_total').value == formatted_zero) {
                document.getElementById('balance_total').style.color = '';
            } else {
                document.getElementById('balance_total').style.color = 'red';
            }
        }

        function updateDesc(rowID) {
       
            var acct = document.getElementById('acct_'+rowID).value;
            var DebitOrCredit = '';
            if(rowID!=1){
                if (document.getElementById('debit_'+rowID).value != '') {
                    DebitOrCredit = 'd';
                } 
            }else{
                if (document.getElementById('credit_'+rowID).value != '') {
                    DebitOrCredit = 'c';
                } 
            }
          
            // showAction(rowID, DebitOrCredit);
            return true;
        }

        // -->
</script>