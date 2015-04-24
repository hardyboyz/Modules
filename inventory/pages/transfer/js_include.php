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
//  Path: /modules/inventory/pages/transfer/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    // Include translations here as well.
    var securityLevel = <?php echo $security_level; ?>;
    var text_search   = '<?php echo TEXT_SEARCH; ?>';
    var journalID   = '<?php echo JOURNAL_ID; ?>';
    var sub_journalID   = '<?php echo SUB_JOURNAL_ID; ?>';
<?php echo js_calendar_init($cal_xfr); ?>

    function init() {
<?php if ($action == 'edit') echo '  EditTransfer(' . $oID . ')'; ?>
    }

    function check_form() {
        return true;
    }
//    $(document).ready(function(){
//        $("input[id*='sku_']").focusout(function(){
//            $('#item_list').hide();
//        });
//    });
   
    $(document).keydown(function(e) {
        if(e.keyCode==9){
            $("input[id*='sku_']").focusout(function(){
                $('#item_list .selected').trigger('click');
                $('#item_list').hide();
            })
        }
    });
    
    $("input[id*='sku_']").live('keyup',function(e){
        var id = $(this).attr('id').split('_');
        if(e.keyCode == 13){
            $('#item_list .selected').trigger('click');
            $('#item_list').hide();
        }else{
            if (e.keyCode != 40 && e.keyCode != 38) { 
                var keyword = $(this).val();
              
                $.ajax({
                    type: "GET",
                    url: 'index.php?module=ucbooks&page=ajax&op=autocopmplete&journalID='+journalID+'&action=item&row='+id[1]+'&keyword='+keyword,
                    dataType: "text",
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
                    },
                    success: function(data){
                        // alert(data);
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
    });
    function clearForm() {
        document.getElementById('id').value                  = 0;
        document.getElementById('ref_id').value              = 0;
        document.getElementById('source_store_id').value     = <?php echo $_SESSION['admin_prefs']['def_store_id'] ? $_SESSION['admin_prefs']['def_store_id'] : 0; ?>;
        document.getElementById('dest_store_id').value       = <?php echo $_SESSION['admin_prefs']['def_store_id'] ? $_SESSION['admin_prefs']['def_store_id'] : 0; ?>;
        document.getElementById('purchase_invoice_id').value = '';
        document.getElementById('post_date').value           = '<?php echo date(DATE_FORMAT); ?>';
        document.getElementById('adj_reason').value          = '';
        while (document.getElementById('item_table').rows.length > 0) removeInvRow(1);
        addInvRow();
    }

    function InventoryList(rowCnt) {
        var bID = document.getElementById('source_store_id').value;
        var sku = document.getElementById('sku_'+rowCnt).value;
        window.open("index.php?module=inventory&page=popup_inv&rowID="+rowCnt+"&type=v&storeID="+bID+"&f1=cog&search_text="+sku,"inventory","width=1150,height=550,resizable=1,scrollbars=1,top=150,left=100");
    }

    function serialList(rowID) {
        var choice    = document.getElementById('serial_'+rowID).value;
        var newChoice = prompt('<?php echo 'Enter Serial Number:'; ?>', choice);
        if (newChoice) document.getElementById('serial_'+rowID).value = newChoice;
    }

    function loadSkuDetails(iID, rowCnt) {
        var bID = document.getElementById('source_store_id').value;
        var sku = iID==0 ? document.getElementById('sku_'+rowCnt).value : '';
        $.ajax({
            type: "GET",
            url: 'index.php?module=inventory&page=ajax&op=inv_details&iID='+iID+'&sku='+sku+'&bID='+bID+'&rID='+rowCnt,
            dataType: ($.browser.msie) ? "text" : "xml",
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
            },
            success: processSkuStock
        });
    }

    function processSkuStock(sXml) {
        var stock;
        var xml = parseXml(sXml);
        if (!xml) return;
        if (!$(xml).find("rID").text()) return; // no results
        var rCnt = $(xml).find("rID").text();
        var rQty = parseFloat(document.getElementById('qty_'+rCnt).value);
        if (isNaN(rQty)) {
            rQty = 1;
            document.getElementById('qty_'+rCnt).value = rQty;
        }
        var isEdit = document.getElementById('id').value ? true : false;
        if (isEdit) {
            stock = parseFloat($(xml).find("branch_qty_in_stock").text()) + rQty;
        } else {
            stock = parseFloat($(xml).find("branch_qty_in_stock").text());
        }
   
        document.getElementById('sku_'+rCnt).value      = $(xml).find("sku").text();
        document.getElementById('sku_'+rCnt).style.color= '';
        document.getElementById('stock_'+rCnt).value    = stock;
        document.getElementById('serial_'+rCnt).value   = '';
        document.getElementById('acct_'+rCnt).value     = $(xml).find("account_inventory_wage").text();
        //document.getElementById('desc_'+rCnt).value     = $(xml).find("description_short").text();
        if ($(xml).find("inventory_type").text() == 'sr') document.getElementById('imgSerial_'+rCnt).style.display = '';
        updateBalance();
        rowCnt  = document.getElementById('item_table').rows.length;
        var qty = document.getElementById('qty_'+rowCnt).value;
        var sku = document.getElementById('sku_'+rowCnt).value;
        if (qty != '' && sku != '' && sku != text_search) rowCnt = addInvRow();
    }

    function updateBalance() {
        for (var i=1; i<document.getElementById('item_table').rows.length+1; i++) {
            var stock = parseFloat(document.getElementById('stock_'+i).value);
            if (isNaN(stock)) stock = 0;
            var adj   = parseFloat(document.getElementById('qty_'+i).value);
            if (isNaN(adj)) adj = 0;
            document.getElementById('bal_'+i).value = stock - adj;
        }
    }

    function setReturnItem(iID,rID) {
        
        loadSkuDetails(iID, rID);
        $('#item_table #item_list').hide();
    }
    function addInvRow() {
        var wrap    = new Array();
        var cell   = new Array();
        var newRow = document.getElementById("item_table").insertRow(-1);
        var rowCnt = newRow.rowIndex;
        if($('#item_table').find('.input_container1').attr('class')!='undefined'){
            // alert('test')
            if($('#item_table').find("input[id*='sku_']").last().val()!='undefined'){
                //  alert($('#item_table').find("input[id*='sku_']").last().val())
                $('#item_table').find('.input_container1').attr('class','')
                $('#item_table').find('#item_list').remove()
            }
            //$('#item_list').hide();
        }
        // NOTE: any change here also need to be intemplate form for reload if action fails
        cell[0]  = buildIcon(icon_path+'16x16/emblems/emblem-unreadable.png', '<?php echo TEXT_DELETE; ?>', 'style="cursor:pointer" onclick="if (confirm(\'<?php echo TEXT_ROW_DELETE_ALERT; ?>\')) removeInvRow('+rowCnt+');"');
        cell[1]  = '    <input type="text" name="qty_'+rowCnt+'" id="qty_'+rowCnt+'" style="text-align:right" size="6" maxlength="5" onchange="updateBalance('+rowCnt+')">';
        cell[2]  = '    <input type="text" name="stock_'+rowCnt+'" id="stock_'+rowCnt+'" readonly="readonly" style="text-align:right" size="6" maxlength="5">';
        cell[3]  = '    <input type="text" name="bal_'+rowCnt+'" id="bal_'+rowCnt+'" readonly="readonly" style="text-align:right" size="6" maxlength="5">';
        cell[4]  = '<span class="input_container1">    <input autocomplete="off" type="text" style="width:180px !important;" name="sku_'+rowCnt+'" id="sku_'+rowCnt+'" value="'+text_search+'" size="<?php echo (MAX_INVENTORY_SKU_LENGTH + 1); ?>" maxlength="<?php echo MAX_INVENTORY_SKU_LENGTH; ?>" onfocus="clearField(\'sku_'+rowCnt+'\', \''+text_search+'\')" onblur="setField(\'sku_'+rowCnt+'\', \''+text_search+'\'); loadSkuDetails(0, '+rowCnt+')">';
        cell[4] += buildIcon(icon_path+'16x16/actions/system-search.png', '<?php echo TEXT_SEARCH; ?>', 'style="cursor:pointer" onclick="InventoryList('+rowCnt+')"');
        cell[4] += buildIcon(icon_path+'16x16/actions/tab-new.png', '<?php echo TEXT_SERIAL_NUMBER; ?>', 'id="imgSerial_'+rowCnt+'" style="cursor:pointer; display:none;" onclick="serialList('+rowCnt+')"');
        // Hidden fields
        cell[4] += '<input type="hidden" name="serial_'+rowCnt+'" id="serial_'+rowCnt+'" value="" />';
        cell[4] += '<input type="hidden" name="acct_'+rowCnt+'" id="acct_'+rowCnt+'" value="" /><ul id="item_list"></ul></span>';
        // End hidden fields
        cell[5]  = '    <input type="text" style="width:180px !important;" name="desc_'+rowCnt+'" id="desc_'+rowCnt+'" size="64" maxlength="63">';
        wrap[4]     = 'nowrap';
        for (var i=0; i<cell.length; i++) {
            newCell = newRow.insertCell(-1);
            newCell.innerHTML = cell[i];
            if (wrap[i]) newCell.nowrap = wrap[i];
        }
        setField('sku_'+rowCnt, text_search);
        //        $("input[id*='sku_"+rowCnt+"']").tokenInput('index.php?module=ucbooks&page=ajax&op=autocopmplete&action=item', {
        //            onAdd: function (item) {
        //                var id = $(this).attr('id').split('_');
        //                setReturnItem(item.id,id[1]);
        //            },
        //            tokenLimit:1
        //        });
        return rowCnt;
    }

    function removeInvRow(delRowCnt) {
        var glIndex = delRowCnt;
        for (var i=delRowCnt; i<document.getElementById("item_table").rows.length; i++) {
            document.getElementById('qty_'+i).value   = document.getElementById('qty_'+(i+1)).value;
            document.getElementById('stock_'+i).value = document.getElementById('stock_'+(i+1)).value;
            document.getElementById('bal_'+i).value   = document.getElementById('bal_'+(i+1)).value;
            document.getElementById('sku_'+i).value   = document.getElementById('sku_'+(i+1)).value;
            document.getElementById('desc_'+i).value  = document.getElementById('desc_'+(i+1)).value;
            // Hidden fields
            document.getElementById('serial_'+i).value= document.getElementById('serial_'+(i+1)).value;
            document.getElementById('acct_'+i).value  = document.getElementById('acct_'+(i+1)).value;
            // End hidden fields
            document.getElementById('sku_'+i).style.color = (document.getElementById('sku_'+i).value == text_search) ? inactive_text_color : '';
            glIndex++; // increment the row counter (two rows per entry)
        }
        document.getElementById("item_table").deleteRow(-1);
        updateBalance();
    }

    function OpenXfrList() {
        window.open("index.php?module=inventory&page=popup_adj&list=1&adj_type=xfr","inv_adj_open","width=1150,height=550,resizable=1,scrollbars=1,top=150,left=100");
    }

    function EditTransfer(rID) {
        $.ajax({
            type: "GET",
            url: 'index.php?module=ucbooks&page=ajax&op=load_record&rID='+rID,
            dataType: ($.browser.msie) ? "text" : "xml",
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
            },
            success: processEditTransfer
        });
    }

    function processEditTransfer(sXml) {
        var sku, qty;
        var xml = parseXml(sXml);
        if (!xml) return;
        var id = $(xml).find("id").first().text();
        document.getElementById('id').value                  = id;
        document.getElementById('ref_id').value              = $(xml).find("so_po_ref_id").text();
        document.getElementById('source_store_id').value     = $(xml).find("store_id").text();
        document.getElementById('dest_store_id').value       = $(xml).find("bill_acct_id").text();
        document.getElementById('purchase_invoice_id').value = $(xml).find("purchase_invoice_id").text();
        document.getElementById('post_date').value           = formatDate($(xml).find("post_date").first().text());
        // turn off some icons
        if (id && securityLevel < 3) removeElement('tb_main_0', 'tb_icon_save');
        // fill item rows
        var rowCnt = 1;
        $(xml).find("items").each(function() {
            var type = $(this).find("gl_type").text();
            switch (type) {
                case 'ttl':
                    document.getElementById('adj_reason').value = $(this).find("description").text();
                    document.getElementById('gl_acct').value    = $(this).find("gl_account").text();
                    break;
                case 'adj':
                    sku = $(this).find("sku").text();
                    qty = $(this).find("qty").text();
                    document.getElementById('sku_'+rowCnt).value      = sku;
                    document.getElementById('sku_'+rowCnt).style.color= (document.getElementById('sku_'+rowCnt).value == text_search) ? inactive_text_color : '';
                    document.getElementById('serial_'+rowCnt).value   = $(this).find("serialize_number").text();
                    document.getElementById('qty_'+rowCnt).value      = -qty;
                    document.getElementById('desc_'+rowCnt).value     = $(this).find("description").text();
                    loadSkuDetails(0, rowCnt);
                    rowCnt = addInvRow();
                default:
            }
        });
    }

    // -->
</script>