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
//  Path: /modules/ucbooks/javascript/orders.js
//

var bill_add = new Array(0);
var ship_add = new Array(0);
var force_clear = false;
var check = false;
function ClearForm() {
    var numRows = 0;
    clearAddress('bill');
    clearAddress('ship');
    document.getElementById('search').value = text_search;
    document.getElementById('search').style.color = inactive_text_color;
    document.getElementById('purchase_invoice_id').value = '';
    document.getElementById('id').value = '';
    document.getElementById('recur_id').value = '0';
    document.getElementById('recur_frequency').value = '0';
    document.getElementById('terms').value = '0';
    document.getElementById('terms_text').value = text_terms;
    document.getElementById('item_count').value = '0';
    document.getElementById('weight').value = '0';
    document.getElementById('printed').value = '0';
    document.getElementById('so_po_ref_id').value = '0';
    document.getElementById('purch_order_id').value = '';
    document.getElementById('store_id').value = '';
    document.getElementById('post_date').value = defaultPostDate;
    document.getElementById('terminal_date').value = defaultTerminalDate;
    document.getElementById('gl_acct_id').value = default_GL_acct;
    document.getElementById('disc_gl_acct_id').value = default_disc_acct;
    document.getElementById('disc_percent').value = formatted_zero;
    document.getElementById('discount').value = formatted_zero;
    document.getElementById('ship_gl_acct_id').value = default_freight_acct;
    document.getElementById('ship_carrier').value = '';
    document.getElementById('ship_service').value = '';
    document.getElementById('freight').value = formatted_zero;
    document.getElementById('sales_tax').value = formatted_zero;
    document.getElementById('total').value = formatted_zero;
    document.getElementById('display_currency').value = defaultCurrency;
    document.getElementById('currencies_code').value = defaultCurrency;
    document.getElementById('currencies_value').value = '1';
    // handle checkboxes
    document.getElementById('waiting').checked = false;
    document.getElementById('drop_ship').checked = false;
    document.getElementById('closed').checked = false;
    document.getElementById('bill_add_update').checked = false;
    document.getElementById('ship_add_update').checked = false;
    $("#closed_text").hide();

    document.getElementById('ship_to_search').innerHTML = '&nbsp;'; // turn off ship to id search
    document.getElementById('purchase_invoice_id').readOnly = false;
    // remove all item rows and add a new blank one
    var desc = document.getElementById('desc_1').value;
    var sku = document.getElementById('sku_1').value;
    if ((sku != '' && sku != text_search) || desc != '') {
        if (force_clear || confirm(warn_form_has_data)) {
            while (document.getElementById('item_table').rows.length > 0)
                removeInvRow(1);
            addInvRow();
        } else {
            if (single_line_list == '1') {
                numRows = document.getElementById('item_table').rows.length;
            } else {
                numRows = document.getElementById('item_table').rows.length;
            }
            for (var i = 1; i <= numRows; i++) {
                document.getElementById('id_' + i).value = 0;
                document.getElementById('so_po_item_ref_id_' + i).value = 0;
            }
        }
    }
}

function clearAddress(type) {
    for (var i = 0; i < add_array.length; i++) {
        var add_id = add_array[i];
        document.getElementById(type + '_acct_id').value = '';
        document.getElementById(type + '_address_id').value = '';
        document.getElementById(type + '_country_code').value = store_country_code;
        if (type == 'bill') {
            if (add_id != 'country_code')
                document.getElementById(type + '_' + add_id).style.color = inactive_text_color;
            document.getElementById(type + '_' + add_id).value = default_array[i];
        }
        document.getElementById(type + '_to_select').style.visibility = 'hidden';
        if (document.getElementById(type + '_to_select')) {
            while (document.getElementById(type + '_to_select').options.length) {
                document.getElementById(type + '_to_select').remove(0);
            }
        }
        if (type == 'ship') {
            switch (journalID) {
                case '3':
                case '4':
                case '6':
                case '7':
                case '20':
                case '21':
                    document.getElementById(type + '_' + add_id).style.color = '';
                    document.getElementById(type + '_' + add_id).value = company_array[i];
                    break;
                case '9':
                case '10':
                case '32':
                case '12':
                case '36':
                case '13':
                case '30':
                case '31':
                case '18':
                case '19':
                    if (add_id != 'country_code')
                        document.getElementById(type + '_' + add_id).style.color = inactive_text_color;
                    document.getElementById(type + '_' + add_id).value = default_array[i];
                    break;
                default:
            }
        }
    }
}

function ajaxOrderData(cID, oID, jID, open_order, ship_only, goods_return, recurring_invoice, allDo) {
    var open_so_po = (open_order) ? '1' : '0';
    var only_ship = (ship_only) ? '1' : '0';
    var function_choice = fillOrderData;
    if (recurring_invoice != undefined) {
        var urls = 'index.php?module=ucbooks&page=ajax&op=load_recurring_invoice_order&cID=' + cID + '&oID="' + oID + '"&jID=' + jID + '&so_po=' + open_so_po + '&action='+action+'&ship_only=' + only_ship + '&rec_id=' + recurring_invoice;
    } else if (goods_return == 0) {//when goods return is 0 discount is given

        //         $.ajaxSetup({"async":false});
        //         check_discount(oID,jID); // this is for already given discount or not
        //         $.ajaxSetup({"async":true});
        //alert(descount_status);
        var urls = 'index.php?module=ucbooks&page=ajax&op=load_order_discount&cID=' + cID + '&oID=' + oID + '&jID=' + jID + '&so_po=' + open_so_po + '&action='+action+'&ship_only=' + only_ship + '&goods_return=0';
        function_choice = fillOrderDataDiscount; //Select return function for Discount
    } else if (allDo != undefined) {
        var urls = 'index.php?module=ucbooks&page=ajax&op=load_deleveryorder&cID=' + allDo + '&oID=' + oID + '&jID=' + jID + '&so_po=' + open_so_po + '&action='+action+'&ship_only=' + only_ship;
    } else {
        var urls = 'index.php?module=ucbooks&page=ajax&op=load_order&cID=' + cID + '&oID=' + oID + '&jID=' + jID + '&sub_jID=' + sub_journalID + '&so_po=' + open_so_po + '&action='+action+'&ship_only=' + only_ship;
    }

    
    $.ajax({
        type: "GET",
        url: urls,
        dataType: ($.browser.msie) ? "text" : "xml",
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
        },
        success: function_choice
    });

}

function check_discount(oID, jID) {
    $.ajax({
        type: "GET",
        url: 'index.php?module=ucbooks&page=ajax&op=check_order_discount&oID=' + oID,
        dataType: "text",
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
        }
    }).done(function(return_data) {
        if (return_data != 0) {//if already given discount then go to edit page of that discount
            window.open("index.php?module=ucbooks&page=orders&oID=" + return_data + "&jID=" + jID + "&action=edit", "_self")
        }

    });


}


function fillOrderDataDiscount(sXml) {//this is for fill data with Discount
    var xml = parseXml(sXml);
    if (!xml)
        return;
    if ($(xml).find("OrderData").length) {
        orderFillAddress(xml, 'bill', false);
        orderFillAddress(xml, 'ship', false);
        fillOrderDiscount(xml); //call to fillOrderDiscount function for  fill order with discount
    } else if ($(xml).find("BillContact").length) {
        orderFillAddress(xml, 'bill', true);
        orderFillAddress(xml, 'ship', false);
    } else if ($(xml).find("ShipContact").length) {
        orderFillAddress(xml, 'ship', true);
    }
}

function fillOrderDiscount(sXml) {//fill order with discount
    addHeadingDiscount(); //call for add discount heading 
    fillOrderDiscountdata(sXml); // Fill order discount with discount
}
function addHeadingDiscount() {//this is for add discount heading 
    var table = '<table style="border-collapse:collapse;width:100%;"><input type="hidden" name="discount_check" value="1" />';
    table += '<thead class="ui-widget-header">';
    table += '<tr><th>No.</th><th>Item Code</th><th>Description</th><th>Shipped</th>';
    table += '<th>GL Account</th><th>Tax Rate</th><th>Amount</th><th>Discount Amount</th></tr>';
    table += '</thead>';
    table += '<tbody class="ui-widget-content " id="item_table">';
    table += '<script type="text/javascript">addInvRowDiscount();</script>';
    table += '</tbody>';
    table += '</table>';
    $('#productList').html(table);
    var footer_table = '<tr><td><br/></td></tr><tr><td></td><td align="right">Sub Total <input type="text" style="text-align: right;" readonly="readonly"  name="total_item_amount" id="total_item_amount" /> </td></tr>';
    footer_table += '<tr><td></td><td align="right">Discount<input type="hidden" name="is_discount" value="1" /> <input type="hidden" name="total_item_amount" id="total_item_amount" /> <input type="text" style="text-align:right" maxlength="20" size="15" onchange="updateTotalPricesDis()"  value="0.00" id="discount_amount" name="discount_amount"></td></tr>';

    footer_table += '<tr><td></td><td align="right">Discount Tax <input type="text" style="text-align: right;" onchange="updateTotalPrices()" maxlength="20" size="15" readonly="readonly" value="0.00" id="discount_tax" name="sales_tax"></td></tr>';
    footer_table += '<tr style="display:none"><td></td><td align="right"><input type="text" style="text-align: right;" onchange="updateTotalPrices()" maxlength="20" size="15" value="0.00" id="freight" name="freight"></td></tr>';
    footer_table += '<tr><td></td><td align="right">Total Disount <input type="text" style="text-align:right" maxlength="20" size="15" readonly="readonly" value="0.00" id="total_discount" name="total"> </td></tr>';
    $('#footer_discount').html(footer_table);
    $("#discount_plus").hide();

}
function fillOrderData(sXml) { // edit response form fill
    var xml = parseXml(sXml);
    if (!xml)
        return;

    if ($(xml).find("OrderData").length) {
        orderFillAddress(xml, 'bill', false);
        orderFillAddress(xml, 'ship', false);
        fillOrder(xml);
    } else if ($(xml).find("BillContact").length) {
        orderFillAddress(xml, 'bill', true);
        orderFillAddress(xml, 'ship', false);
    } else if ($(xml).find("ShipContact").length) {
        //alert('xml')
        orderFillAddress(xml, 'ship', true);
    }
}

function orderFillAddress(xml, type, fill_address) {
    var newOpt, mainType;
    while (document.getElementById(type + '_to_select').options.length)
        document.getElementById(type + '_to_select').remove(0);
    var cTag = (type == 'ship' ? 'ShipContact' : 'BillContact');
    $(xml).find(cTag).each(function() {
        var id = $(this).find("id").text();
        if (!id)
            return;
        mainType = $(this).find("type").first().text() + 'm';
        switch (type) {
            default:
            case 'bill':
                bill_add = this;
                default_sales_tax = $(this).find("tax_id").text();
                //closed by shsheen  default_inv_acct  = ($(this).find("gl_type_account").text()) ? $(this).find("gl_type_account").text() : default_inv_acct;
                insertValue('bill_acct_id', id);
                insertValue('terms', $(this).find("special_terms").text());
                insertValue('terms_text', $(this).find("terms_text").text());
                insertValue('search', $(this).find("short_name").text());
                //insertValue('token-input-search',          $(this).find("short_name").text());
                insertValue('acct_1', default_inv_acct);
                if ($(this).find("dept_rep_id").text() != '')
                    insertValue('rep_id', $(this).find("dept_rep_id").text());
                if ($(this).find("ship_gl_acct_id").text() != '')
                    insertValue('ship_gl_acct_id', $(this).find("ship_gl_acct_id").text());
                custCreditLimit = $(this).find("credit_remaining").text();
                var rowCnt = 1;
                // $("#token-input-search").select();
                while (true) {
                    if (!document.getElementById('tax_' + rowCnt))
                        break;
                    document.getElementById('tax_' + rowCnt).value = $(this).find("tax_id").text();
                    rowCnt++;
                }
                if (show_status == '1') {
                    window.open("index.php?module=ucbooks&page=popup_status&form=orders&id=" + id, "contact_status", "width=1150px,height=400px,resizable=0,scrollbars=1,top=150,left=100");
                }
                break;
            case 'ship':
                ship_add = this;
                insertValue('ship_acct_id', id);
                insertValue('ship_search', $(this).find("short_name").text());
                break;
        }
        //now fill the addresses
        var iIndex = 0;
        $(this).find("Address").each(function() {
            newOpt = document.createElement("option");
            newOpt.text = $(this).find("primary_name").text() + ', ' + $(this).find("city_town").text() + ', ' + $(this).find("postal_code").text();

            document.getElementById(type + '_to_select').options.add(newOpt);
            document.getElementById(type + '_to_select').options[iIndex].value = $(this).find("address_id").text();

            if ($(this).find("type").text() == 'cb' || $(this).find("type").text() == 'cs') {
                check = true;
                insertValue(type + '_address_id', $(this).find("address_id").text());
                $(this).children().each(function() {
                    var tagName = this.tagName;
                    if (document.getElementById(type + '_' + tagName)) {
                        document.getElementById(type + '_' + tagName).value = $(this).text();
                        document.getElementById(type + '_' + tagName).style.color = '';
                    }
                });

            } else if (check == false) { // also fill the fields
                insertValue(type + '_address_id', $(this).find("address_id").text());
                $(this).children().each(function() {
                    var tagName = this.tagName;
                    if (document.getElementById(type + '_' + tagName)) {
                        document.getElementById(type + '_' + tagName).value = $(this).text();
                        document.getElementById(type + '_' + tagName).style.color = '';
                    }
                });
            }
            iIndex++;
        });
        // add a option for creating a new address
        newOpt = document.createElement("option");
        newOpt.text = text_enter_new;
        document.getElementById(type + '_to_select').options.add(newOpt);
        document.getElementById(type + '_to_select').options[iIndex].value = '0';
        document.getElementById(type + '_to_select').style.visibility = 'visible';
        document.getElementById(type + '_to_select').disabled = false;
    });
}

function fillOrder(xml) {
    $(xml).find("OrderData").each(function() {

        $(this).children().each(function() {
            
            var tagName = this.tagName;
            if (document.getElementById(tagName)) {
                document.getElementById(tagName).value = $(this).first().text();
                document.getElementById(tagName).style.color = '';
            }
        });
        // fix some special cases, checkboxes, and active fields
        insertValue('bill_to_select', $(this).find("bill_address_id").text());
        insertValue('ship_to_selecsales_order_numt', $(this).find("ship_address_id").text());

        if ($(this).find('closed').text() == 1) {
            $('#tb_icon_invoice').css("display", "none");
            $('#tb_icon_receive').css('display', 'none');
        }
        document.getElementById('display_currency').value = $(this).find("currencies_code").text();
        document.getElementById('closed').checked = $(this).find("cb_closed").text() == '1' ? true : false;
        document.getElementById('waiting').checked = $(this).find("cb_waiting").text() == '1' ? true : false;
        document.getElementById('drop_ship').checked = $(this).find("cb_drop_ship").text() == '1' ? true : false;
        var purchase_invoice_id = "";
        
        if ($(this).find("purch_order_id").text() != "") {
            purchase_invoice_id = $(this).find("purch_order_id").text();
        }
        else {
            purchase_invoice_id = $(this).find("sales_order_num").text();
        }

        document.getElementById("purch_order_id").value = purchase_invoice_id;
        if ($(this).find("cb_waiting").text() == '1')
            document.getElementById('waiting').value = '1'; // if hidden set value
        //
        // Uncomment to set Sales Invoice number = Sales Order number when invoicing a Sales Order
        //  if (journalID=='12' && $(this).find("purch_order_num").text()) document.getElementById('purchase_invoice_id').value = $(this).find("purch_order_num").text();
        //
    
        if ($(this).find("id").first().text() && journalID != '6' && journalID != '7')
            document.getElementById('purchase_invoice_id').readOnly = true;
        buildFreightDropdown();
        insertValue('ship_service', $(this).find("ship_service").text());
        if ($(this).find("cb_closed").text() == '1') {
            switch (journalID) {
                case  '6':
                case  '7':
                case '12':
                case '36':
                case '19':
                case '30':
                case '31':
                    $("#closed_text").show();
                    removeElement('tb_main_0', 'tb_icon_payment');
                    break;
                default:
            }
        }
        // disable the purchase_invoice_id field since it cannot change, except purchase/receive
        if ($(this).find("id").first().text() && journalID != '6' && journalID != '7' && journalID != '21') {
            document.getElementById('purchase_invoice_id').readOnly = true;
        }
        if ($(this).find("id").first().text() && $(this).find("attach_exist").text() == 1) {
            document.getElementById('show_attach').style.display = ''; // show attachment button and delete checkbox if it exists
        }
        if ($(this).find("id").first().text() && securityLevel < 3) { // turn off some icons
            removeElement('tb_main_0', 'tb_icon_print');
            removeElement('tb_main_0', 'tb_icon_save');
            removeElement('tb_main_0', 'tb_icon_payment');
            removeElement('tb_main_0', 'tb_icon_save_as_so');
            removeElement('tb_main_0', 'tb_icon_recur');
            removeElement('tb_main_0', 'tb_icon_ship_all');
        }
        // fill inventory rows and add a new blank one
        var order_discount = formatted_zero;
        var jIndex = 1;
   
        $(this).find("Item").each(function() {

            var gl_type = $(this).find("gl_type").text();
            switch (gl_type) {
                case 'ttl':
                case 'tax': // the total and tax will be recalculated when the form is loaded
                    break;
                case 'dsc':
                    order_discount = $(this).find("total").text();
                    if ($(this).find("gl_account").text())
                        insertValue('disc_gl_acct_id', $(this).find("gl_account").text());
                    break;
                case 'frt':
                    insertValue('freight', $(this).find("total").text());
                    if ($(this).find("gl_account").text())
                        insertValue('ship_gl_acct_id', $(this).find("gl_account").text());
                    break;
                case 'doo':
                case 'soo':
                case 'sos':
                case 'poo':
                case 'por':
                    if (action == 'prc_so' && $(this).find("purch_package_quantity").text() != '') {
                        quantity = $(this).find("qty").text() * $(this).find("purch_package_quantity").text();
                        unitPrice = formatCurrency(cleanCurrency($(this).find("unit_price").text()) / $(this).find("purch_package_quantity").text());
                    } else {
                        quantity = $(this).find("qty").text();
                        unitPrice = $(this).find("unit_price").text();
                    }
                    insertValue('id_' + jIndex, $(this).find("id").text());
                    insertValue('item_cnt_' + jIndex, $(this).find("item_cnt").text());
                    insertValue('so_po_item_ref_id_' + jIndex, $(this).find("so_po_item_ref_id").text());
                    insertValue('qty_' + jIndex, quantity);
                    if (journalID == 12) {
                        if ($(this).find("pstd").text() != '')
                            insertValue('pstd_' + jIndex, $(this).find("pstd").text());
                        else
                            insertValue('pstd_' + jIndex, $(this).find("qty").text());
                    } else {
                        if ((journalID == 19 || journalID == 13) && sub_journalID == 1)
                            insertValue('pstd_' + jIndex, 1);
                        else
                            insertValue('pstd_' + jIndex, $(this).find("pstd").text());
                    }

                    
                    
                    insertValue('sku_' + jIndex, $(this).find("sku").text());
                    //  insertValue('token-input-sku_'  + jIndex,              $(this).find("sku").text());
                    insertValue('desc_' + jIndex, $(this).find("description").text());
                    insertValue('descHidden_' + jIndex, $(this).find("description_hide").text());
                    insertValue('proj_' + jIndex, $(this).find("proj_id").text());
                    insertValue('date_1_' + jIndex, $(this).find("date_1").text());
                    insertValue('acct_' + jIndex, $(this).find("gl_account").text());
                    insertValue('tax_' + jIndex, $(this).find("taxable").text());
                    insertValue('full_' + jIndex, $(this).find("full_price").text());
                    insertValue('weight_' + jIndex, $(this).find("weight").text());
                    insertValue('serial_' + jIndex, $(this).find("serialize").text());
                    insertValue('stock_' + jIndex, $(this).find("stock").text());
                    insertValue('inactive_' + jIndex, $(this).find("inactive").text());
                    insertValue('lead_' + jIndex, $(this).find("lead").text());
                    insertValue('price_' + jIndex, unitPrice);
                    if (uom == 1) {
                        insertValue('uom_' + jIndex, $(this).find("uom").text());
                    }
                    if (uom_qty == 1) {
                        if ($(this).find("uom_qty").text() != 0) {
                            insertValue('uom_qty_' + jIndex, $(this).find("uom_qty").text());
                        } else {
                            insertValue('uom_qty_' + jIndex, 1);
                        }
                    }
                   
                    insertValue('discval_' + jIndex, $(this).find("discval").text());
                    if($(this).find("disc_percent").text() > '0'){
                        document.getElementById('disc_percent_' + jIndex).checked = true;
                    }
                    
                    insertValue('total_' + jIndex, $(this).find("total").text());
                    
                    

                    //  $("#token-input-sku_"  + jIndex).attr('onfocus','javascript: this.select()');
                    if ($(this).find("so_po_item_ref_id").text() || ((journalID == '04' || journalID == '10') && $(this).find("pstd").text())) {
                        if (!((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
                            // don't allow sku to change, hide the sku search icon
                            document.getElementById('sku_' + jIndex).readOnly = true;
                            document.getElementById('sku_open_' + jIndex).style.display = 'none';
                            // don't allow row to be removed, turn off the delete icon
                            //                        rowOffset = (single_line_list == '1') ? jIndex-1 : (jIndex*2)-2;
                            //                        document.getElementById("item_table").rows[rowOffset].cells[0].innerHTML = '&nbsp;';
                            $('#item_table .remove_icon').html('&nbsp;');
                        }
                    }


                    if (((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
                    //removeInvRow(jIndex);
                    } else {
                        updateRowTotal(jIndex, false);
                        
                        addInvRow();
                    }
                    jIndex++;
                default: // do nothing
            }
        });
        insertValue('discount', order_discount);

        calculateDiscountPercent();

    });
    if (((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
        addInvRow();
        removeInvRow(2)
        $('#item_table .remove_icon').html('');
        if (action != 'edit')
            clearCustomerDeposit()
    }
}
function clearCustomerDeposit() {
    document.getElementById('gl_acct_id').value = default_GL_acct;
    // remove all item rows and add a new blank one
    var desc = document.getElementById('desc_1').value;
    var sku = document.getElementById('sku_1').value;
    if ((sku != '' && sku != text_search) || desc != '') {
        if (force_clear || warn_form_has_data) {
            while (document.getElementById('item_table').rows.length > 0)
                removeInvRow(1);
            addInvRow();
        } else {
            if (single_line_list == '1') {
                numRows = document.getElementById('item_table').rows.length;
            } else {
                numRows = document.getElementById('item_table').rows.length;
            }
            for (var i = 1; i <= numRows; i++) {
                document.getElementById('id_' + i).value = 0;
                document.getElementById('so_po_item_ref_id_' + i).value = 0;
            }
        }
    }
}
function fillOrderDiscountdata(xml) {

    $(xml).find("OrderData").each(function() {
        var check_edit = $(this).find('store_id').siblings('description').text();
        $(this).children().each(function() {
            var tagName = this.tagName;
            if (document.getElementById(tagName)) {
                document.getElementById(tagName).value = $(this).first().text();
                document.getElementById(tagName).style.color = '';
            }
        });
        // fix some special cases, checkboxes, and active fields
        insertValue('bill_to_select', $(this).find("bill_address_id").text());
        insertValue('ship_to_select', $(this).find("ship_address_id").text());

        if ($(this).find('closed').text() == 1) {
            $('#tb_icon_invoice').css("display", "none");
            $('#tb_icon_receive').css('display', 'none');
        }

        document.getElementById('display_currency').value = $(this).find("currencies_code").text();
        document.getElementById('closed').checked = $(this).find("cb_closed").text() == '1' ? true : false;
        document.getElementById('waiting').checked = $(this).find("cb_waiting").text() == '1' ? true : false;
        document.getElementById('drop_ship').checked = $(this).find("cb_drop_ship").text() == '1' ? true : false;
        var purchase_invoice_id = "";
        if ($(this).find("purch_order_id").text() != "") {
            purchase_invoice_id = $(this).find("purch_order_id").text();
        }
        else {
            purchase_invoice_id = $(this).find("sales_order_num").text();
        }
        document.getElementById("purch_order_id").value = purchase_invoice_id;
        if ($(this).find("cb_waiting").text() == '1')
            document.getElementById('waiting').value = '1'; // if hidden set value
        //
        // Uncomment to set Sales Invoice number = Sales Order number when invoicing a Sales Order
        //  if (journalID=='12' && $(this).find("purch_order_num").text()) document.getElementById('purchase_invoice_id').value = $(this).find("purch_order_num").text();
        //
        if ($(this).find("id").first().text() && journalID != '6' && journalID != '7')
            document.getElementById('purchase_invoice_id').readOnly = true;
        //buildFreightDropdown();
        insertValue('ship_service', $(this).find("ship_service").text());
        if ($(this).find("cb_closed").text() == '1') {
            switch (journalID) {
                case  '6':
                case  '7':
                case '12':
                case '36':
                case '13':
                case '19':
                case '30':
                case '31':
                    $("#closed_text").show();
                    removeElement('tb_main_0', 'tb_icon_payment');
                    break;
                default:
            }
        }
        // disable the purchase_invoice_id field since it cannot change, except purchase/receive
        if ($(this).find("id").first().text() && journalID != '6' && journalID != '7' && journalID != '21') {
            document.getElementById('purchase_invoice_id').readOnly = true;
        }
        if ($(this).find("id").first().text() && $(this).find("attach_exist").text() == 1) {
            document.getElementById('show_attach').style.display = ''; // show attachment button and delete checkbox if it exists
        }
        if ($(this).find("id").first().text() && securityLevel < 3) { // turn off some icons
            removeElement('tb_main_0', 'tb_icon_print');
            removeElement('tb_main_0', 'tb_icon_save');
            removeElement('tb_main_0', 'tb_icon_payment');
            removeElement('tb_main_0', 'tb_icon_save_as_so');
            removeElement('tb_main_0', 'tb_icon_recur');
            removeElement('tb_main_0', 'tb_icon_ship_all');
        }
        // fill inventory rows and add a new blank one
        var order_discount = formatted_zero;
        var jIndex = 1;
        var totalamount = 0;
        $(this).find("Item").each(function() {

            var gl_type = $(this).find("gl_type").text();
            switch (gl_type) {
                case 'ttl':
                case 'tax': // the total and tax will be recalculated when the form is loaded
                    break;
                case 'dsc':
                    order_discount = $(this).find("total").text();
                    if ($(this).find("gl_account").text())
                        insertValue('disc_gl_acct_id', $(this).find("gl_account").text());
                    break;
                case 'frt':
                    insertValue('freight', $(this).find("total").text());
                    if ($(this).find("gl_account").text())
                        insertValue('ship_gl_acct_id', $(this).find("gl_account").text());
                    break;
                case 'soo':
                case 'doo':
                case 'sos':
                case 'poo':
                case 'por':
                    if (action == 'prc_so' && $(this).find("purch_package_quantity").text() != '') {
                        quantity = $(this).find("qty").text() * $(this).find("purch_package_quantity").text();
                        unitPrice = formatCurrency(cleanCurrency($(this).find("unit_price").text()) / $(this).find("purch_package_quantity").text());
                    } else {
                        quantity = $(this).find("qty").text();
                        unitPrice = $(this).find("unit_price").text();
                    }
                    insertValue('id_' + jIndex, $(this).find("id").text());
                    insertValue('item_cnt_' + jIndex, $(this).find("item_cnt").text());
                    insertValue('so_po_item_ref_id_' + jIndex, $(this).find("so_po_item_ref_id").text());
                    insertValue('qty_' + jIndex, quantity);
                    //                    insertValue('pstd_' + jIndex,              $(this).find("pstd").text());
                    insertValue('pstd_' + jIndex, 0);
                    insertValue('sku_' + jIndex, $(this).find("sku").text());
                    insertValue('desc_' + jIndex, $(this).find("description").text());
                    insertValue('descHidden_' + jIndex, $(this).find("description_hide").text());
                    insertValue('proj_' + jIndex, $(this).find("proj_id").text());
                    insertValue('date_1_' + jIndex, $(this).find("date_1").text());
                    insertValue('acct_' + jIndex, $(this).find("gl_account").text());
                    insertValue('tax_' + jIndex, $(this).find("taxable").text());
                    insertValue('full_' + jIndex, $(this).find("full_price").text());
                    insertValue('weight_' + jIndex, $(this).find("weight").text());
                    insertValue('serial_' + jIndex, $(this).find("serialize").text());
                    insertValue('stock_' + jIndex, $(this).find("stock").text());
                    insertValue('inactive_' + jIndex, $(this).find("inactive").text());
                    insertValue('lead_' + jIndex, $(this).find("lead").text());
                    insertValue('price_' + jIndex, unitPrice);
                    if (uom == 1) {
                        insertValue('uom_' + jIndex, $(this).find("uom").text());
                    }
                    if (uom_qty == 1) {
                        if ($(this).find("uom_qty").text() != 0) {
                            insertValue('uom_qty_' + jIndex, $(this).find("uom_qty").text());
                        } else {
                            insertValue('uom_qty_' + jIndex, 1);
                        }
                    }


                    //alert($(this).find("pre_item_total").text());

                    if (check_edit == "Customer Credit Memos Entry Discount" || check_edit == "Vendor Credit Memos Entry Discount" || check_edit == "Customer Debit Memos Entry Discount" || check_edit == "Vendor Debit Memos Entry Discount") {

                        insertValue('total_item_' + jIndex, $(this).find("pre_item_total").text());
                        insertValue('total_' + jIndex, $(this).find("total").text());
                    } else {
                        insertValue('total_item_' + jIndex, $(this).find("total").text());
                        insertValue('total_' + jIndex, 0);
                    }


                    if ($(this).find("so_po_item_ref_id").text() || ((journalID == 4 || journalID == '10' || journalID == 6) && $(this).find("pstd").text())) {
                        // don't allow sku to change, hide the sku search icon

                        document.getElementById('sku_' + jIndex).readOnly = true;
                        //document.getElementById('sku_open_' + jIndex).style.display = 'none';
                        // don't allow row to be removed, turn off the delete icon
                        rowOffset = (single_line_list == '1') ? jIndex - 1 : (jIndex * 2) - 2;
                        $('#item_table .remove_icon').html('&nbsp;');
                    //document.getElementById("item_table").rows[rowOffset].cells[-1].innerHTML = '&nbsp;';
                    }
                    if (journalID == 4) {
                        document.getElementById('sku_' + jIndex).readOnly = true;
                        document.getElementById('sku_open_' + jIndex).style.display = 'none';
                        // don't allow row to be removed, turn off the delete icon
                        rowOffset = (single_line_list == '1') ? jIndex - 1 : (jIndex * 2) - 2;
                        $('#item_table .remove_icon').html('&nbsp;');
                    }

                    //updateRowTotal(jIndex, false);

                    addInvRowDiscount();
                    totalamount += unitPrice * quantity;

                    jIndex++;
                default: // do nothing
            }
        });
        insertValue('discount', order_discount);
        if (check_edit == "Customer Credit Memos Entry Discount" || check_edit == "Vendor Credit Memos Entry Discount" || check_edit == "Customer Debit Memos Entry Discount" || check_edit == "Vendor Debit Memos Entry Discount") {

            $('#total_item_amount').val(formatCurrency(cleanCurrency($(this).find('pre_total').text())));
            var total_dis_amount = cleanCurrency($(this).find('total_amount').text());
            var total_dis_tax = cleanCurrency($(this).find('sales_tax').text());
            $('#discount_amount').val(formatCurrency(total_dis_amount - total_dis_tax));
            $('#discount_tax').val(total_dis_tax);
            $('#total_discount').val(total_dis_amount);

        } else {
            $('#total_item_amount').val(totalamount);
        }




    //$('#total').val(total_dis_amount);
    //calculateDiscountPercent();

    });


}

function AccountList() {
    var guess = document.getElementById('search').value;
    var override = document.getElementById('bill_add_update').checked ? true : false; // force popup if Add/Update checked
    if (guess != text_search && guess != '' && !override) {
        $.ajax({
            type: "GET",
            url: 'index.php?module=ucbooks&page=ajax&op=load_searches&jID=' + journalID + '&type=' + account_type + '&guess=' + guess,
            dataType: ($.browser.msie) ? "text" : "xml",
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
            },
            success: AccountListResp
        });
    } else { // force the popup
        AccountListResp();
    }
}

function AccountListResp(sXml) {
    var xml = parseXml(sXml);
    //  if (!xml) return;
    if ($(xml).find("result").text() == 'success') {
        var cID = $(xml).find("cID").text();
        ajaxOrderData(cID, 0, journalID, false, false);
    } else {
        var fill = '';
        switch (journalID) {
            case '3':
            case '4':
            case '6':
            case '7':
            case '20':
                fill = 'bill';
                break;
            case '9':
            case '10':
            case '32':
            case '12':
            case '36':
            case '13':
            case '19':
            case '30':
            case '31':
            case '18':
                fill = 'both';
                break;
            default:
        }

        //window.open("index.php?module=contacts&page=popup_accts&type="+account_type+"&fill="+fill+"&jID="+journalID+"&search_text="+document.getElementById('search').value,"accounts","width=850px,height=550px,resizable=1,scrollbars=1,top=150,left=100");
        window.open("index.php?module=contacts&page=popup_accts&type=" + account_type + "&fill=" + fill + "&jID=" + journalID + "&sub_jID=" + sub_journalID + "&search_text=" + document.getElementById('search').value, "accounts", "width=1120px,height=800px,resizable=1,scrollbars=1,top=150,left=100");
    }
}

function DropShipList(currObj) {
    window.open("index.php?module=contacts&page=popup_accts&type=c&fill=ship&jID=" + journalID + "&search_text=" + document.getElementById('ship_search').value, "accounts", "width=1150px,height=550px,resizable=1,scrollbars=1,top=150,left=100");
}

function OpenOrdrList(currObj) {
    window.open("index.php?module=ucbooks&page=popup_orders&jID=" + journalID, "search_po", "width=1150px,height=550px,resizable=1,scrollbars=1,top=150,left=100");
}

function OpenRecurList(currObj) {
    window.open("index.php?module=ucbooks&page=popup_recur&jID=" + journalID, "recur", "width=1150px,height=300px,resizable=1,scrollbars=1,top=150,left=100");
}

function InventoryList(rowCnt) {
    var storeID = document.getElementById('store_id').value;
    var sku = document.getElementById('sku_' + rowCnt).value;
    var cID = document.getElementById('bill_acct_id').value;
    window.open("index.php?module=inventory&page=popup_inv&type=" + account_type + "&jID=" + journalID + "&rowID=" + rowCnt + "&storeID=" + storeID + "&cID=" + cID + "&search_text=" + sku, "inventory", "width=1150px,height=800px,resizable=1,scrollbars=1,top=150,left=100");
}

function PriceManagerList(elementID) {
    var sku = document.getElementById('sku_' + elementID).value;
    if (!sku || sku == text_search) {
        alert(warn_price_sheet);
        return;
    }
    window.open("index.php?module=inventory&page=popup_prices&rowId=" + elementID + "&sku=" + sku + "&type=" + account_type, "prices", "width=1150px,height=550px,resizable=1,scrollbars=1,top=150,left=100");
}

function TermsList() {
    var terms = document.getElementById('terms').value;
    window.open("index.php?module=contacts&page=popup_terms&type=" + account_type + "&form=orders&val=" + terms, "terms", "width=1150px,height=300px,resizable=1,scrollbars=1,top=150,left=100");
}

function FreightList() {
    window.open("index.php?module=shipping&page=popup_shipping&form=orders", "shipping", "width=1150px,height=650px,resizable=1,scrollbars=1,top=150,left=100");
}

function convertQuote() {
    var id = document.getElementById('id').value;
    if (id != '') {
        window.open("index.php?module=ucbooks&page=popup_convert&oID=" + id, "popup_convert", "width=1150px,height=300px,resizable=1,scrollbars=1,top=150,left=100");
    } else {
        alert(cannot_convert_quote);
    }
}

function convertSO() {
    var id = document.getElementById('id').value;
    if (id != '') {
        window.open("index.php?module=ucbooks&page=popup_convert_po&oID=" + id, "popup_convert_po", "width=1150px,height=300px,resizable=1,scrollbars=1,top=150,left=100");
    } else {
        alert(cannot_convert_so);
    }
}

function serialList(rowID) {
    var choice = document.getElementById(rowID).value;
    var newChoice = prompt(serial_num_prompt, choice);
    if (newChoice)
        document.getElementById(rowID).value = newChoice;
}

function openBarCode() {
    window.open("index.php?module=ucbooks&page=popup_bar_code&jID=" + journalID, "bar_code", "width=1150px,height=150px,resizable=1,scrollbars=1,top=110,left=100");
}

function downloadAttachment() {
    document.getElementById('todo').value = 'dn_attach';
    document.getElementById('todo').form.submit();
}

function DropShipView(currObj) {
    var add_id;
    if (document.getElementById('drop_ship').checked) {
        for (var i = 0; i < add_array.length; i++) {
            add_id = add_array[i];
            if (add_id != 'country_code')
                document.getElementById('ship_' + add_id).style.color = inactive_text_color;
            document.getElementById('ship_' + add_id).value = default_array[i];
        }
        document.getElementById('ship_country_code').value = store_country_code;
        document.getElementById('ship_add_update').checked = false;
        document.getElementById('ship_add_update').disabled = false;
        // turn on ship to id search
        document.getElementById('ship_to_search').innerHTML = ship_search_HTML;
    } else {
        while (document.getElementById('ship_to_select').options.length) {
            document.getElementById('ship_to_select').remove(0);
        }
        for (var i = 0; i < add_array.length; i++) {
            add_id = add_array[i];
            switch (journalID) {
                case '3':
                case '4':
                case '6':
                case '7':
                case '20': // fill company address
                case '21':
                    document.getElementById('ship_' + add_id).style.color = '';
                    document.getElementById('ship_' + add_id).value = company_array[i];
                    break;
                case '9':
                case '10':
                case '32':
                case '12':
                case '36':
                case '13':
                case '19':
                case '30':
                case '31':
                case '18': // fill default address text
                case '19':
                    if (add_id != 'country_code')
                        document.getElementById('ship_' + add_id).style.color = inactive_text_color;
                    document.getElementById('ship_' + add_id).value = default_array[i];
                    break;
                default:
            }
        }
        document.getElementById('ship_country_code').value = store_country_code;
        document.getElementById('ship_add_update').checked = false;
        document.getElementById('ship_add_update').disabled = false;
        document.getElementById('ship_to_select').style.visibility = 'hidden';
        document.getElementById('ship_to_search').innerHTML = '&nbsp;'; // turn off ship to id search
    }
}

function fillAddress(type) {
    var index = document.getElementById(type + '_to_select').value;
    var address = '';
    if (type == "bill")
        address = bill_add;
    if (type == "ship")
        address = ship_add;
    if (index == '0') { // set to defaults
        document.getElementById(type + '_acct_id').value = 0;
        document.getElementById(type + '_address_id').value = 0;
        for (var i = 0; i < add_array.length; i++) {
            add_id = add_array[i];
            if (add_id != 'country_code')
                document.getElementById(type + '_' + add_id).style.color = inactive_text_color;
            document.getElementById(type + '_' + add_id).value = default_array[i];
        }
        return;
    }
    $(address).find("Address").each(function() {
        if ($(this).find("address_id").text() == index) {
            document.getElementById(type + '_acct_id').value = $(this).find("ref_id").text();
            document.getElementById(type + '_address_id').value = (index == 'new') ? '0' : $(this).find("address_id").text();
            var add_id;
            for (var i = 0; i < add_array.length; i++) {
                add_id = add_array[i];
                if (index != '0' && $(this).find(add_id).text()) {
                    document.getElementById(type + '_' + add_id).style.color = '';
                    document.getElementById(type + '_' + add_id).value = $(this).find(add_id).text();
                } else {
                    if (add_id != 'country_code')
                        document.getElementById(type + '_' + add_id).style.color = inactive_text_color;
                    document.getElementById(type + '_' + add_id).value = default_array[i];
                }
            }
        }
    });
}

function copyAddress() {
    document.getElementById('ship_address_id').value = document.getElementById('bill_address_id').value;
    document.getElementById('ship_acct_id').value = document.getElementById('bill_acct_id').value;
    var add_id;
    for (var i = 0; i < add_array.length; i++) {
        add_id = add_array[i];
        if (document.getElementById('bill_' + add_id).value != default_array[i]) {
            document.getElementById('ship_' + add_id).style.color = '';
            document.getElementById('ship_' + add_id).value = document.getElementById('bill_' + add_id).value;
        } else {
            if (add_id != 'country_code')
                document.getElementById('ship_' + add_id).style.color = inactive_text_color;
            document.getElementById('ship_' + add_id).value = default_array[i];
        }
    }
    document.getElementById('ship_country_code').selectedIndex = document.getElementById('bill_country_code').selectedIndex;
}

function addInvRow() {
    var newCell = '';
    var cell = '';
    var rowCnt = 0;
    var newRow = '';
    var newRow2 = '';

    if (single_line_list == '1') {
        newRow = document.getElementById('item_table').insertRow(-1);
        rowCnt = newRow.rowIndex;
    } else {

        newRow = document.getElementById('item_table').insertRow(-1);


        //             newCell =document.getElementById('item_table').rows[0].insertCell(0);
        //             newCell.innerHTML = '&nbsp;';
        //             newCell.style.display = 'none'
        //rowCnt  = (newRow.rowIndex - 1)/2;
        //rowCnt  = (newRow.rowIndex - 1);
        //rowCnt  = (newRow.rowIndex)/2;
        rowCnt = newRow.rowIndex;
    }
    var tabIndex = 70 + (rowCnt - 1) * 16;
    // NOTE: any change here also need to be made to template form for reload if action fails

    //  if (single_line_list != '1') newCell.rowSpan = 2;


    if (single_line_list != '1' && !((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
        // var index =  Number(tabIndex)+Number(1);
        // if(rowCnt==1){
        // cell    = '<input type="text" style="width:20px !important; " tabindex="'+index+'"  name="item_cnt_'+rowCnt+'" id="item_cnt_'+rowCnt+'" value="'+rowCnt+'" size="3" maxlength="3" readonly="readonly" />';
        //  }else{
        cell = '<input type="text" style="width:20px !important; "  name="item_cnt_' + rowCnt + '" id="item_cnt_' + rowCnt + '" value="' + rowCnt + '" size="3" maxlength="3" readonly="readonly" />';
        // }
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
    }


    //this is for item
    if (single_line_list == '1' && !((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
        // var index =  Number(tabIndex)+Number(2);
        cell = '<input type="text" name="item_cnt_' + rowCnt + '" id="item_cnt_' + rowCnt + '" value="' + rowCnt + '" size="3" maxlength="3" readonly="readonly" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
    }
    newCell.style = 'padding:0px !important';
    //item end
    if (!((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
        if ($('#item_table').find('.input_container1').attr('class') != 'undefined') {
            // alert('test')
            if ($('#item_table').find("input[id*='sku_']").last().val() != 'undefined') {
                //  alert($('#item_table').find("input[id*='sku_']").last().val())
                $('#item_table').find('.input_container1').attr('class', '')
                $('#item_table').find('#item_list').remove()
            }
        //$('#item_list').hide();
        }
        //this is for sku
        index = Number(tabIndex) + Number(3);
        cell = '<span><input style="width:110px !important" type="text" tabindex="' + index + '" name="sku_' + rowCnt + '" class="test" id="sku_' + rowCnt + '" size="' + (max_sku_len + 1) + '" maxlength="' + max_sku_len + '" autocomplete="off" onfocus="clearField(\'sku_' + rowCnt + '\', \'' + text_search + '\')" onblur="setField(\'sku_' + rowCnt + '\', \'' + text_search + '\'); loadSkuDetails(0, ' + rowCnt + ')" />&nbsp;';
        cell += buildIcon(icon_path + '16x16/actions/system-search.png', text_search, 'id="sku_open_' + rowCnt + '" align="top" style="cursor:pointer" onclick="InventoryList(' + rowCnt + ')"');
        cell += buildIcon(icon_path + '16x16/actions/document-properties.png', text_properties, 'id="sku_prop_' + rowCnt + '" align="top" style="cursor:pointer" onclick="InventoryProp(' + rowCnt + ')"');
        cell += '</span>';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        loadSkuDetails

    }







    index = Number(tabIndex) + Number(4);

    //this is for Description 
    index = Number(tabIndex) + Number(4);

    cell = '<input style="width:145px !important;" tabindex="' + index + '" value="" readonly="readonly" onclick=\'window.open("index.php?count=' + rowCnt + '&module=ucbooks&page=disc_msg","Description","height=500,width=750")\' name="desc_' + rowCnt + '" id="desc_' + rowCnt + '" size="' + ((single_line_list == '1') ? 50 : 75) + '" maxlength="255" /> <input type="hidden" name="descHidden_' + rowCnt + '" id="descHidden_' + rowCnt + '" value=""  />';

    newCell = newRow.insertCell(-1);
    newCell.align = 'center';
    newCell.innerHTML = cell;
    //if (single_line_list != '1') newCell.colSpan = 3;

    //end Description

    if (!((journalID == 19 || journalID == 13) && sub_journalID == 1)) {

        if (!(journalID == 6 && sub_journalID == 1)) {
            index = Number(tabIndex) + Number(5);
            cell = '<input style="" type="text" tabindex="' + index + '" name="qty_' + rowCnt + '" id="qty_' + rowCnt + '"' + (item_col_1_enable == '1' ? " " : " readonly=\"readonly\"") + ' size="7" maxlength="12" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
            newCell = newRow.insertCell(-1);
            newCell.innerHTML = cell;
            newCell.align = 'center';
            newCell.style.whiteSpace = 'nowrap';
        //end quantity
        }

        //this is for Unit Price
        index = Number(tabIndex) + Number(6);
        cell = '<input type="text" name="price_' + rowCnt + '" id="price_' + rowCnt + '" tabindex="' + index + '" size="10" maxlength="15" onchange="updateRowTotal(' + rowCnt + ', false)" style="text-align:right" />&nbsp;';
        cell += buildIcon(icon_path + '16x16/mimetypes/x-office-spreadsheet.png', text_price_manager, 'align="top" style="cursor:pointer" onclick="PriceManagerList(' + rowCnt + ')"');
        if (single_line_list != '1') {
            newCell = newRow.insertCell(-1);
        } else {
            newCell = newRow.insertCell(-1);
        }
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
    //End Unit Price
    }

    // second row ( or continued first row if option selected)

    //this is for GL Account
    if (single_line_list != '1') {
        //        cell    = '<input type="text" name="item_cnt_'+rowCnt+'" id="item_cnt_'+rowCnt+'" value="'+rowCnt+'" size="3" maxlength="3" readonly="readonly" />';
        //        newCell = newRow.insertCell(-1);
        //        newCell.innerHTML = cell;
        index = Number(tabIndex) + Number(7);
        cell = '<select name="acct_' + rowCnt + '" tabindex="' + index + '" id="acct_' + rowCnt + '" class="gl"></select>';
        newCell = newRow.insertCell(-1);
    } else {
        var index = Number(tabIndex) + Number(8);
        cell = htmlComboBox('acct_' + rowCnt, values = '', default_inv_acct, 'tabindex="' + index + '" size="10"', '220px', '');
        newCell = newRow.insertCell(-1);
    }
    newCell.innerHTML = cell;
    newCell.align = 'center';
    //end GL Acount

    //This is for Tax Rate
    var index = Number(tabIndex) + Number(9);
    cell = '<select name="tax_' + rowCnt + '" id="tax_' + rowCnt + '" tabindex="' + index + '" onchange="updateRowTotal(' + rowCnt + ', false)" class="tx"></select>';
    if (single_line_list != '1') {
        newCell = newRow.insertCell(-1);
    } else {
        newCell = newRow.insertCell(-1);
    }
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';
    //end Tax rate
    if (!((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
        // Project field
        var index = Number(tabIndex) + Number(10);
        if (single_line_list != '1') {
            cell = '<select tabindex="' + index + '" name="proj_' + rowCnt + '" id="proj_' + rowCnt + '" class="pr"></select>';
            newCell = newRow.insertCell(-1);
            newCell.innerHTML = cell;
            //newCell.colSpan = 2;
            newCell.align = 'center';
            newCell.style.whiteSpace = 'nowrap';
        }
    }
    if (!((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
        var index = Number(tabIndex) + Number(11);
        cell = '<input type="text" tabindex="' + index + '" style="width:30px !important" name="pstd_' + rowCnt + '" id="pstd_' + rowCnt + '"' + (item_col_2_enable == '1' ? " " : " readonly=\"readonly\"") + ' size="7" maxlength="12" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
        switch (journalID) {
            case  '6':
            case  '7':
            case '12':
            case '36':
            case '13':
            case '30':
            case '31':
            case '19':
            case '21':
                cell += '&nbsp;' + buildIcon(icon_path + '16x16/actions/tab-new.png', image_ser_num, 'id="imgSerial_' + rowCnt + '" style="cursor:pointer; display:none;" onclick="serialList(\'serial_' + rowCnt + '\')"');
            default:
        }
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
    }
    // uom field
    var index = Number(tabIndex) + Number(12);
    if (uom == 1) {
        cell = '<select tabindex="' + index + '" name="uom_' + rowCnt + '" id="uom_' + rowCnt + '" class="pr"></select>';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        //newCell.colSpan = 2;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
    }
    if (uom_qty == 1) {

        //uom field
        var index = Number(tabIndex) + Number(13);
        cell = '<input tabindex="' + index + '" style="width:30px !important" type="text" name="uom_qty_' + rowCnt + '" id="uom_qty_' + rowCnt + '" size="7" maxlength="6" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
    }




    // for textarea uncomment below, (No control over input length, truncated to 255 by db) or ...
    //  cell = '<textarea name="desc_'+rowCnt+'" id="desc_'+rowCnt+'" cols="'+((single_line_list=='1')?50:110)+'" rows="1" maxlength="255"></textarea>';
    // for standard controlled input, uncomment below






    //if (single_line_list != '1') newCell.colSpan = 3;
    var index = Number(tabIndex) + Number(14);
    if (single_line_list != '1') {
        cell = '<input type="text" tabindex="' + index + '" name="full_' + rowCnt + '" id="full_' + rowCnt + '" readonly="readonly" size="11" maxlength="10" style="text-align:right" />';
        //cell  = '<input type="text" name="full_'+rowCnt+'" id="full_'+rowCnt+'" readonly="readonly" size="11" maxlength="10" style="text-align:right" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';
        var index = Number(tabIndex) + Number(15);
        cell = '<input type="text" tabindex="' + index + '" name="discval_' + rowCnt + '" id="discval_' + rowCnt + '" size="8" onchange="updateRowTotal(' + rowCnt + ', true)" maxlength="10" style="text-align:right;width:30px !important" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        
        var index = Number(tabIndex) + Number(16);
        cell = '<input type="checkbox" value="1" tabindex="' + index + '" name="disc_percent_' + rowCnt + '" id="disc_percent_' + rowCnt + '" onclick="updateRowTotal(' + rowCnt + ', true)" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
       // newCell.style.display = 'none';
    }

    // Hidden fields

    cell = '<input type="hidden" name="id_' + rowCnt + '" id="id_' + rowCnt + '" value="" />';
    cell += '<input type="hidden" name="so_po_item_ref_id_' + rowCnt + '" id="so_po_item_ref_id_' + rowCnt + '" value="" />';
    cell += '<input type="hidden" name="weight_' + rowCnt + '" id="weight_' + rowCnt + '" value="0" />';
    cell += '<input type="hidden" name="stock_' + rowCnt + '" id="stock_' + rowCnt + '" value="NA" />';
    cell += '<input type="hidden" name="inactive_' + rowCnt + '" id="inactive_' + rowCnt + '" value="0" />';
    cell += '<input type="hidden" name="lead_' + rowCnt + '" id="lead_' + rowCnt + '" value="0" />';
    cell += '<input type="hidden" name="serial_' + rowCnt + '" id="serial_' + rowCnt + '" value="" />';
    cell += '<input type="hidden" name="date_1_' + rowCnt + '" id="date_1_' + rowCnt + '" value="" />';
    if (((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
        cell += '<input type="hidden" name="item_cnt_' + rowCnt + '" id="item_cnt_' + rowCnt + '" value="' + rowCnt + '" />';
        cell += '<input type="hidden" name="pstd_' + rowCnt + '" id="pstd_' + rowCnt + '" value="1" />';
        cell += '<input type="hidden" name="qty_' + rowCnt + '" id="qty_' + rowCnt + '" value="1" />';
        cell += '<input type="hidden" name="proj_' + rowCnt + '" id="proj_' + rowCnt + '" />';
        cell += '<input type="hidden" name="sku_' + rowCnt + '" id="sku_' + rowCnt + '" onchange="updateRowTotal(' + rowCnt + ', false)"  />';
        cell += '<input type="hidden" name="price_' + rowCnt + '" id="price_' + rowCnt + '" onchange="updateRowTotal(' + rowCnt + ', false)"  />';
        cell += '<input type="hidden" name="sku_open_' + rowCnt + '" id="sku_open_' + rowCnt + '" onclick="InventoryList(' + rowCnt + ')"  />';
        cell += '<input type="hidden" name="sku_prop_' + rowCnt + '" id="sku_prop_' + rowCnt + '" onclick="InventoryProp(' + rowCnt + ')"  />';
    } else if (journalID == 6 && sub_journalID == 1) {
        cell += '<input type="hidden" name="qty_' + rowCnt + '" id="qty_' + rowCnt + '" value="1" />';
    }
    if (single_line_list == '1') {
        cell += '<input type="hidden" name="proj_' + rowCnt + '" id="proj_' + rowCnt + '" value="" />';
        cell += '<input type="hidden" name="full_' + rowCnt + '" id="full_' + rowCnt + '" value="" />';
        cell += '<input type="hidden" name="discval_' + rowCnt + '" id="discval_' + rowCnt + '" value="" />';
        cell += '<input type="hidden" name="disc_percent_' + rowCnt + '" id="disc_percent_' + rowCnt + '" value="" />';
    }
    // End hidden fields


    var index = Number(tabIndex) + Number(16);
    //this is for amount
    cell += '<input type="text" name="total_' + rowCnt + '" tabindex="' + index + '" id="total_' + rowCnt + '" value="' + formatted_zero + '" size="11" maxlength="20" onchange="updateUnitPrice(' + rowCnt + ')" style="text-align:right" />';
    if (single_line_list != '1') {
        newCell = newRow.insertCell(-1);
    } else {
        newCell = newRow.insertCell(-1);
    }
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';
    //end amount




    //this is for close icon 
    cell = buildIcon(icon_path + '16x16/emblems/emblem-unreadable.png', image_delete_text, 'onclick="if (confirm(\'' + image_delete_msg + '\')) removeInvRow(' + rowCnt + ');"');
    newCell = newRow.insertCell(-1);
    newCell.innerHTML = cell;
    newCell.className = 'remove_icon';
    //close icon end


    // populate the drop downs
    var selElement = (single_line_list == '1') ? ('comboselacct_' + rowCnt) : ('acct_' + rowCnt);
    if (js_gl_array)
        buildDropDown(selElement, js_gl_array, default_inv_acct);
    if (tax_rates)
        buildDropDown('tax_' + rowCnt, tax_rates, default_sales_tax);
    if (uom == 1) {
        if (uom_list)
            buildDropDown('uom_' + rowCnt, uom_list, '3');
    }
    if (!((journalID == 19 || journalID == 13) && sub_journalID == 1)) {
        if (proj_list && single_line_list != '1')
            buildDropDown('proj_' + rowCnt, proj_list, false);
    }
    setField('sku_' + rowCnt, text_search);
    setId = rowCnt; // set the upc auto-reader to the newest line added
    return rowCnt;
}
function addInvRowDiscount() {

    var newCell = '';
    var cell = '';
    var rowCnt = 0;
    var newRow = '';
    var newRow2 = '';
    if (single_line_list == '1') {
        newRow = document.getElementById('item_table').insertRow(-1);
        rowCnt = newRow.rowIndex;
    } else {

        newRow = document.getElementById('item_table').insertRow(-1);


        //             newCell =document.getElementById('item_table').rows[0].insertCell(0);
        //             newCell.innerHTML = '&nbsp;';
        //             newCell.style.display = 'none'
        //rowCnt  = (newRow.rowIndex - 1)/2;
        //rowCnt  = (newRow.rowIndex - 1);
        //rowCnt  = (newRow.rowIndex)/2;
        rowCnt = newRow.rowIndex;
    }
    // NOTE: any change here also need to be made to template form for reload if action fails

    //  if (single_line_list != '1') newCell.rowSpan = 2;


    if (single_line_list != '1') {
        cell = '<input type="text" style="width:30px !important"  name="item_cnt_' + rowCnt + '" id="item_cnt_' + rowCnt + '" value="' + rowCnt + '" size="3" maxlength="3" readonly="readonly" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
    }


    //this is for item
    if (single_line_list == '1') {
        cell = '<input type="text" name="item_cnt_' + rowCnt + '" id="item_cnt_' + rowCnt + '" value="' + rowCnt + '" size="3" maxlength="3" readonly="readonly" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
    }
    //item end

    //this is for sku
    cell = '<input readonly="readonly" type="text" name="sku_' + rowCnt + '" id="sku_' + rowCnt + '" size="' + (max_sku_len + 1) + '" maxlength="' + max_sku_len + '" onfocus="clearField(\'sku_' + rowCnt + '\', \'' + text_search + '\')" onblur="setField(\'sku_' + rowCnt + '\', \'' + text_search + '\'); loadSkuDetails(0, ' + rowCnt + ')" />&nbsp;';
    //    cell   += buildIcon(icon_path+'16x16/actions/system-search.png', text_search, 'id="sku_open_'+rowCnt+'" align="top" style="cursor:pointer" onclick="InventoryList('+rowCnt+')"');
    //    cell   += buildIcon(icon_path+'16x16/actions/document-properties.png', text_properties, 'id="sku_prop_'+rowCnt+'" align="top" style="cursor:pointer" onclick="InventoryProp('+rowCnt+')"');
    newCell = newRow.insertCell(-1);
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';
    //sku end

    //this is for Description 
    cell = '<input style="width:145px !important;" value="" readonly="readonly"   name="desc_' + rowCnt + '" id="desc_' + rowCnt + '" size="' + ((single_line_list == '1') ? 50 : 75) + '" maxlength="255" /> <input type="hidden" name="descHidden_' + rowCnt + '" id="descHidden_' + rowCnt + '" value=""  />';
    newCell = newRow.insertCell(-1);
    newCell.innerHTML = cell;
    //if (single_line_list != '1') newCell.colSpan = 3;

    //end Description


    //this is form quantity
    cell = '<input style="width:30px !important" type="text" name="qty_' + rowCnt + '" id="qty_' + rowCnt + '"' + (item_col_1_enable == '1' ? " " : " readonly=\"readonly\"") + ' size="7" maxlength="12" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
    newCell = newRow.insertCell(-1);
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';
    //end quantity

    //this is for Unit Price
    cell = '<input type="text" readonly="readonly" name="price_' + rowCnt + '" id="price_' + rowCnt + '" size="10" maxlength="15" onchange="updateRowTotal(' + rowCnt + ', false)" style="text-align:right" />&nbsp;';
    //    cell += buildIcon(icon_path+'16x16/mimetypes/x-office-spreadsheet.png', text_price_manager, 'align="top" style="cursor:pointer" onclick="PriceManagerList('+rowCnt+')"');
    if (single_line_list != '1') {
        newCell = newRow.insertCell(-1);
    } else {
        newCell = newRow.insertCell(-1);
    }
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';
    newCell.style.display = 'none';
    //End Unit Price

    // second row ( or continued first row if option selected)

    //this is for GL Account
    if (single_line_list != '1') {
        //        cell    = '<input type="text" name="item_cnt_'+rowCnt+'" id="item_cnt_'+rowCnt+'" value="'+rowCnt+'" size="3" maxlength="3" readonly="readonly" />';
        //        newCell = newRow.insertCell(-1);
        //        newCell.innerHTML = cell;
        cell = '<select name="acct_' + rowCnt + '" id="acct_' + rowCnt + '" class="gl"></select>';
        newCell = newRow.insertCell(-1);
    } else {
        cell = htmlComboBox('acct_' + rowCnt, values = '', default_inv_acct, 'size="10"', '220px', '');
        newCell = newRow.insertCell(-1);
    }
    newCell.innerHTML = cell;
    newCell.align = 'center';
    //end GL Acount

    //This is for Tax Rate
    cell = '<select  name="tax_' + rowCnt + '" id="tax_' + rowCnt + '" onchange="updateRowTotal(' + rowCnt + ', false)" class="tx"></select>';
    if (single_line_list != '1') {
        newCell = newRow.insertCell(-1);
    } else {
        newCell = newRow.insertCell(-1);
    }
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';
    //end Tax rate

    // Project field
    if (single_line_list != '1') {
        cell = '<select name="proj_' + rowCnt + '" id="proj_' + rowCnt + '" class="pr"></select>';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        //newCell.colSpan = 2;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';
    }



    cell = '<input type="text" style="width:30px !important" name="pstd_' + rowCnt + '" id="pstd_' + rowCnt + '"' + (item_col_2_enable == '1' ? " " : " readonly=\"readonly\"") + ' size="7" maxlength="12" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
    switch (journalID) {
        case  '6':
        case  '7':
        case '12':
        case '36':
        case '13':
        case '30':
        case '31':
        case '19':
        case '21':
            cell += '&nbsp;' + buildIcon(icon_path + '16x16/actions/tab-new.png', image_ser_num, 'id="imgSerial_' + rowCnt + '" style="cursor:pointer; display:none;" onclick="serialList(\'serial_' + rowCnt + '\')"');
        default:
    }
    newCell = newRow.insertCell(-1);
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';
    newCell.style.display = 'none';

    // uom field
    if (uom == 1) {
        cell = '<select name="uom_' + rowCnt + '" id="uom_' + rowCnt + '" class="pr"></select>';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        //newCell.colSpan = 2;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';
    }
    if (uom_qty == 1) {

        //uom field
        cell = '<input style="width:30px !important" type="text" name="uom_qty_' + rowCnt + '" id="uom_qty_' + rowCnt + '" size="7" maxlength="6" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';
    }

        cell = '<input style="width:30px !important" type="text" name="discval_' + rowCnt + '" id="discval_' + rowCnt + '" size="7" maxlength="6" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';
    
        cell = '<input style="width:30px !important" type="text" name="disc_percent_' + rowCnt + '" id="disc_percent_' + rowCnt + '" size="7" maxlength="6" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';


    // for textarea uncomment below, (No control over input length, truncated to 255 by db) or ...
    //  cell = '<textarea name="desc_'+rowCnt+'" id="desc_'+rowCnt+'" cols="'+((single_line_list=='1')?50:110)+'" rows="1" maxlength="255"></textarea>';
    // for standard controlled input, uncomment below






    //if (single_line_list != '1') newCell.colSpan = 3;
    if (single_line_list != '1') {
        cell = '<input type="text" name="full_' + rowCnt + '" id="full_' + rowCnt + '" readonly="readonly" size="11" maxlength="10" style="text-align:right" />';
        //cell  = '<input type="text" name="full_'+rowCnt+'" id="full_'+rowCnt+'" readonly="readonly" size="11" maxlength="10" style="text-align:right" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';
        
        cell = '<input type="text" name="discval_' + rowCnt + '" id="discval_' + rowCnt + '" size="11" maxlength="10" style="text-align:right" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';
        
        cell = '<input type="text" name="disc_percent_' + rowCnt + '" id="disc_percent_' + rowCnt + '" size="11" maxlength="10" style="text-align:right" />';
        newCell = newRow.insertCell(-1);
        newCell.innerHTML = cell;
        newCell.align = 'center';
        newCell.style.whiteSpace = 'nowrap';
        newCell.style.display = 'none';
    }
    cell = '<input style="width:80px !important" readonly="readonly" type="text" name="total_item_' + rowCnt + '" id="total_item_' + rowCnt + '" size="7" maxlength="6" onchange="updateRowTotal(' + rowCnt + ', true)" style="text-align:right" />';
    newCell = newRow.insertCell(-1);
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';


    // Hidden fields
    cell = '<input type="hidden" name="id_' + rowCnt + '" id="id_' + rowCnt + '" value="" />';
    cell += '<input type="hidden" name="so_po_item_ref_id_' + rowCnt + '" id="so_po_item_ref_id_' + rowCnt + '" value="" />';
    cell += '<input type="hidden" name="weight_' + rowCnt + '" id="weight_' + rowCnt + '" value="0" />';
    cell += '<input type="hidden" name="stock_' + rowCnt + '" id="stock_' + rowCnt + '" value="NA" />';
    cell += '<input type="hidden" name="inactive_' + rowCnt + '" id="inactive_' + rowCnt + '" value="0" />';
    cell += '<input type="hidden" name="lead_' + rowCnt + '" id="lead_' + rowCnt + '" value="0" />';
    cell += '<input type="hidden" name="serial_' + rowCnt + '" id="serial_' + rowCnt + '" value="" />';
    cell += '<input type="hidden" name="date_1_' + rowCnt + '" id="date_1_' + rowCnt + '" value="" />';
    if (single_line_list == '1') {
        cell += '<input type="hidden" name="proj_' + rowCnt + '" id="proj_' + rowCnt + '" value="" />';
        cell += '<input type="hidden" name="full_' + rowCnt + '" id="full_' + rowCnt + '" value="" />';
        cell += '<input type="hidden" name="discval_' + rowCnt + '" id="discval_' + rowCnt + '" value="" />';
        cell += '<input type="hidden" name="disc_percent_' + rowCnt + '" id="disc_percent_' + rowCnt + '" value="" />';
    }
    // End hidden fields



    //this is for amount
    cell += '<input type="text" readonly="readonly" name="total_' + rowCnt + '" id="total_' + rowCnt + '" value="' + formatted_zero + '" size="11" maxlength="20" onchange="updateUnitPrice(' + rowCnt + ')" style="text-align:right" />';
    if (single_line_list != '1') {
        newCell = newRow.insertCell(-1);
    } else {
        newCell = newRow.insertCell(-1);
    }
    newCell.innerHTML = cell;
    newCell.align = 'center';
    newCell.style.whiteSpace = 'nowrap';

    //total_item_

    //end amount





    //this is for close icon 
    cell = buildIcon(icon_path + '16x16/emblems/emblem-unreadable.png', image_delete_text, 'onclick="if (confirm(\'' + image_delete_msg + '\')) removeInvRow(' + rowCnt + ');"');
    newCell = newRow.insertCell(-1);
    newCell.innerHTML = cell;
    newCell.className = 'remove_icon';
    newCell.style.display = 'none';
    //close icon end


    // populate the drop downs
    var selElement = (single_line_list == '1') ? ('comboselacct_' + rowCnt) : ('acct_' + rowCnt);
    if (js_gl_array)
        buildDropDown(selElement, js_gl_array, default_inv_acct);
    if (tax_rates)
        buildDropDown('tax_' + rowCnt, tax_rates, default_sales_tax);
    if (uom == 1) {
        if (uom_list)
            buildDropDown('uom_' + rowCnt, uom_list, '3');
    }
    if (proj_list && single_line_list != '1')
        buildDropDown('proj_' + rowCnt, proj_list, false);

    setField('sku_' + rowCnt, text_search);
    setId = rowCnt; // set the upc auto-reader to the newest line added
    return rowCnt;
}



function removeInvRow(index) {

    var i, offset, newOffset;
    var numRows;
    if (single_line_list == '1') {
        numRows = document.getElementById('item_table').rows.length;
    } else {
        numRows = document.getElementById('item_table').rows.length;
    }
    // remove row from display by reindexing and then deleting last row
    for (i = index; i < numRows; i++) {

        // move the delete icon from the previous row
        offset = (single_line_list == '1') ? i : i * 2;

        newOffset = (single_line_list == '1') ? i : i * 2;
        //        if (document.getElementById('item_table').rows[offset].cells[-1].innerHTML == '&nbsp;') {
        //            document.getElementById('item_table').rows[newOffset].cells[-1].innerHTML = '&nbsp;';
        //        } else {
        //            document.getElementById('item_table').rows[newOffset].cells[-1].innerHTML = delete_icon_HTML + i + ');">';
        //        }
        document.getElementById('qty_' + i).value = document.getElementById('qty_' + (i + 1)).value;
        document.getElementById('pstd_' + i).value = document.getElementById('pstd_' + (i + 1)).value;
        document.getElementById('sku_' + i).value = document.getElementById('sku_' + (i + 1)).value;
        document.getElementById('sku_' + i).readOnly = (document.getElementById('sku_' + (i + 1)).readOnly) ? true : false;
        document.getElementById('sku_open_' + i).style.display = (document.getElementById('sku_' + (i + 1)).readOnly) ? 'none' : '';
        document.getElementById('desc_' + i).value = document.getElementById('desc_' + (i + 1)).value;
        document.getElementById('descHidden_' + i).value = document.getElementById('descHidden_' + (i + 1)).value;
        document.getElementById('proj_' + i).value = document.getElementById('proj_' + (i + 1)).value;
        document.getElementById('price_' + i).value = document.getElementById('price_' + (i + 1)).value;
        if (uom == 1) {
            document.getElementById('uom_' + i).value = document.getElementById('uom_' + (i + 1)).selectedIndex;
        }
        if (uom_qty == 1) {
            document.getElementById('uom_qty_' + i).value = document.getElementById('uom_qty_' + (i + 1)).value;
        }
        document.getElementById('acct_' + i).value = document.getElementById('acct_' + (i + 1)).value;
        document.getElementById('tax_' + i).selectedIndex = document.getElementById('tax_' + (i + 1)).selectedIndex;
        // Hidden fields
        document.getElementById('id_' + i).value = document.getElementById('id_' + (i + 1)).value;
        document.getElementById('so_po_item_ref_id_' + i).value = document.getElementById('so_po_item_ref_id_' + (i + 1)).value;
        document.getElementById('weight_' + i).value = document.getElementById('weight_' + (i + 1)).value;
        document.getElementById('stock_' + i).value = document.getElementById('stock_' + (i + 1)).value;
        document.getElementById('inactive_' + i).value = document.getElementById('inactive_' + (i + 1)).value;
        document.getElementById('lead_' + i).value = document.getElementById('lead_' + (i + 1)).value;
        document.getElementById('serial_' + i).value = document.getElementById('serial_' + (i + 1)).value;
        document.getElementById('full_' + i).value = document.getElementById('full_' + (i + 1)).value;
        document.getElementById('discval_' + i).value = document.getElementById('discval_' + (i + 1)).value;
        document.getElementById('disc_percent_' + i).value = document.getElementById('disc_percent_' + (i + 1)).value;
        // End hidden fields
        document.getElementById('total_' + i).value = document.getElementById('total_' + (i + 1)).value;
        document.getElementById('sku_' + i).style.color = (document.getElementById('sku_' + i).value == text_search) ? inactive_text_color : '';
    }
    // document.getElementById('item_table').deleteRow(-1);
    // if (single_line_list != '1') document.getElementById('item_table').deleteRow(-1);
    $('#item_table tr').last().remove();
    $('#item_table tr').last().find('span').attr('class', 'input_container1');
    $('.input_container1').append('<ul id="item_list"></ul>');
    //$(imageParent).addClass('input_container1')

    updateTotalPrices();
}

function updateRowTotal(rowCnt, useAjax) {
    var qty = 0;
    var total_line = 0;
    var unit_price = cleanCurrency(document.getElementById('price_' + rowCnt).value);
    var full_price = cleanCurrency(document.getElementById('full_' + rowCnt).value);
    
    switch (journalID) {
        case  '3':
        case  '4':
        case  '9':
        case '10':
        case '32':
            qty = parseFloat(document.getElementById('qty_' + rowCnt).value);
            if (isNaN(qty))
                qty = 0; // if blank or a non-numeric value is in the qty field, assume zero
            break;
        case  '6':
        case  '7':
        case '12':
        case '36':
        case '13':
        case '30':
        case '31':
        case '18':
        case '19':
        case '21':
        case '20':
            qty = parseFloat(document.getElementById('pstd_' + rowCnt).value);
            if (isNaN(qty))
                qty = 0; // if blank or a non-numeric value is in the pstd field, assume zero
            break;
        default:
    }

    /* DISCOUNT and TOTAL PART EDIT BY HARDYBOYZ*/
    var disc            = parseFloat(document.getElementById('discval_' + rowCnt).value);       
    var disc_percent    = document.getElementById('disc_percent_' + rowCnt).checked;  
    if (isNaN(disc))
            disc = 0;
    
    if (uom_qty == 1) {
        var uom_qty_num     = parseFloat(document.getElementById('uom_qty_' + rowCnt).value);        
        if (isNaN(uom_qty_num))
            uom_qty_num = 0; // if blank or a non-numeric value is in the qty field, assume zero
        
            if (disc_percent > 0){
                disc = disc/100;
                total_line = qty * uom_qty_num * unit_price - ((qty * uom_qty_num * unit_price)*disc);
            }else{
                disc = disc;  
                total_line = (qty * uom_qty_num * unit_price) - disc;
            }
        
    } else {
        if (disc_percent > 0){
                disc = disc/100;
                total_line = qty * unit_price - ((qty * unit_price)*disc);
            }else{
                disc = disc;  
                total_line = (qty * unit_price) - disc;
            }
        
    }
    /* END OF EDIT DISCOUNT & TOTAL */
    
    var total_l = new String(total_line);
    document.getElementById('price_' + rowCnt).value = formatPrecise(unit_price);
    document.getElementById('total_' + rowCnt).value = formatCurrency(total_l);
    // calculate discount
    if (full_price > 0) {
        var discount = (full_price - unit_price) / full_price;
        //document.getElementById('disc_' + rowCnt).value = new String(Math.round(1000 * discount) / 10) + ' %';
        //document.getElementById('disc_percent_' + rowCnt).value = new String(Math.round(1000 * discount) / 10) + ' %';
    }
    updateTotalPrices();
    // call the ajax price sheet update based on customer
    if (useAjax && qty != 0 && sku != '' && sku != text_search) {
        switch (journalID) {
            case  '9': // only update prices for sales and if no SO was used
            case '10':
            case '32':
            case '12':
            case '36':
            case '13':
            case '19':
                var sku = document.getElementById('sku_' + rowCnt).value;
                var bill_acct_id = document.getElementById('bill_acct_id').value;
                so_exists = document.getElementById('so_po_item_ref_id_' + rowCnt).value;
                if (!so_exists && auto_load_sku) {
                    $.ajax({
                        type: "GET",
                        url: 'index.php?module=inventory&page=ajax&op=inv_details&fID=skuPrice&cID=' + bill_acct_id + '&sku=' + sku + '&qty=' + qty + '&rID=' + rowCnt,
                        dataType: ($.browser.msie) ? "text" : "xml",
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
                        },
                        success: processSkuPrice
                    });
                }
                break;
            default: // no AJAX
        }
    }
}

// ajax response to price sheet request
function processSkuPrice(sXml) { // call back function
    var xml = parseXml(sXml);
    if (!xml)
        return;
    var rowCnt = $(xml).find("rID").text();
    if (!rowCnt)
        return;
    document.getElementById('price_' + rowCnt).value = formatPrecise($(xml).find("sales_price").text());
    updateRowTotal(rowCnt, false);
}

function updateUnitPrice(rowCnt) {
    var qty = 0;
    var total_line = cleanCurrency(document.getElementById('total_' + rowCnt).value);
    document.getElementById('total_' + rowCnt).value = formatCurrency(total_line);
    switch (journalID) {
        case '3':
        case '4':
        case '9':
        case '10':
        case '32':
            qty = parseFloat(document.getElementById('qty_' + rowCnt).value);
            if (isNaN(qty)) {
                qty = 1;
                document.getElementById('qty_' + rowCnt).value = qty;
            }
            break;
        case '6':
        case '7':
        case '12':
        case '36':
        case '13':
        case '30':
        case '31':
        case '18':
        case '19':
        case '20':
        case '21':
            qty = parseFloat(document.getElementById('pstd_' + rowCnt).value);
            if (isNaN(qty)) {
                qty = 1;
                document.getElementById('pstd_' + rowCnt).value = qty;
            }
            break;
        default:
    }


    var unit_price = total_line / qty;
    var unit_p = new String(unit_price);
    document.getElementById('price_' + rowCnt).value = formatPrecise(unit_p);
    updateTotalPrices();
}

function updateTotalPrices() {
    var numRows = 0;
    var discount = parseFloat(cleanCurrency(document.getElementById('discount').value));
    if (isNaN(discount))
        discount = 0;
    //var discountPercent = parseFloat(cleanCurrency(document.getElementById('disc_percent').value));
    var discountPercent = 0;
    if (isNaN(discountPercent))
        discountPercent = 0;
    var item_count = 0;
    var shipment_weight = 0;
    var subtotal = 0;
    var taxable_subtotal = 0;
    var lineTotal = '';
    if (single_line_list == '1') {
        numRows = document.getElementById('item_table').rows.length;
    } else {
        numRows = document.getElementById('item_table').rows.length;
    }
    for (var i = 1; i < numRows + 1; i++) {
        switch (journalID) {
            case  '3':
            case  '4':
            case  '9':
            case '10':
            case '32':
                item_count += document.getElementById('qty_' + i).value ? parseFloat(document.getElementById('qty_' + i).value) : 0;
                shipment_weight += document.getElementById('qty_' + i).value * document.getElementById('weight_' + i).value;
                break;
            case  '6':
            case  '7':
            case '12':
            case '36':
            case '13':
            case '30':
            case '31':
            case '18':
            case '19':
            case '20':
            case '21':
                item_count += document.getElementById('pstd_' + i).value ? parseFloat(document.getElementById('pstd_' + i).value) : 0;
                shipment_weight += document.getElementById('pstd_' + i).value * document.getElementById('weight_' + i).value;
                break;
            default:
        }
        lineTotal = parseFloat(cleanCurrency(document.getElementById('total_' + i).value));
        if (document.getElementById('tax_' + i).value != '0') {
            tax_index = document.getElementById('tax_' + i).selectedIndex;
            if (tax_index == -1) { // if the rate array index is not defined
                tax_index = 0;
                document.getElementById('tax_' + i).value = tax_index;
            }
            if (tax_before_discount == '0') { // tax after discount
                taxable_subtotal += lineTotal * (1 - (discountPercent / 100)) * (tax_rates[tax_index].rate / 100);
            } else {
                taxable_subtotal += lineTotal * (tax_rates[tax_index].rate / 100);
            }
        }
        subtotal += lineTotal;
    }

    // recalculate discount
    discount = subtotal * (discountPercent / 100);
    var strDiscount = new String(discount);
    document.getElementById('discount').value = formatCurrency(strDiscount);
    // freight
    var strFreight = cleanCurrency(document.getElementById('freight').value);
    var freight = parseFloat(strFreight);
    if (isNaN(freight))
        freight = 0;
    strFreight = new String(freight);
    document.getElementById('freight').value = formatCurrency(strFreight);
    if (tax_freight != 0 && default_sales_tax != 0)
        for (keyVar in tax_rates) {
            if (tax_rates[keyVar].id == tax_freight)
                taxable_subtotal += parseFloat(freight) * tax_rates[keyVar].rate / 100;
        }

    var nst = new String(taxable_subtotal);
    document.getElementById('sales_tax').value = formatCurrency(nst);
    document.getElementById('item_count').value = item_count;
    document.getElementById('weight').value = shipment_weight;
    var st = new String(subtotal);
    document.getElementById('subtotal').value = formatCurrency(st);
    var new_total = subtotal - discount + freight + taxable_subtotal;
    var tot = new String(new_total);
    document.getElementById('total').value = formatCurrency(tot);
    if ((journalID == '12' || journalID == '36') && applyCreditLimit == '1') {
        if (tot > custCreditLimit && document.getElementById('override_user').value == '')
            showOverride();
    } else {
        if (document.getElementById('tb_icon_save'))
            document.getElementById('tb_icon_save').style.visibility = "";
        if (document.getElementById('tb_icon_print'))
            document.getElementById('tb_icon_print').style.visibility = "";
        if (document.getElementById('tb_icon_post_previous'))
            document.getElementById('tb_icon_post_previous').style.visibility = "";
        if (document.getElementById('tb_icon_post_next'))
            document.getElementById('tb_icon_post_next').style.visibility = "";
    }
}
function updateTotalPricesDis() {//this function for update all price with discunt
    var total_amount = cleanCurrency($('#total_item_amount').val());
    var discount_amount = cleanCurrency($('#discount_amount').val());

    var numRows = 0;
    // var discount = parseFloat(cleanCurrency(document.getElementById('discount').value));
    //if (isNaN(discount)) discount = 0;
    //var discountPercent = parseFloat(cleanCurrency(document.getElementById('disc_percent').value));
    //if (isNaN(discountPercent)) discountPercent = 0;
    var item_count = 0;
    // var shipment_weight  = 0;
    var subtotal = 0;
    var taxable_subtotal = 0;
    //var lineTotal        = '';
    if (single_line_list == '1') {
        numRows = document.getElementById('item_table').rows.length;
    } else {
        numRows = document.getElementById('item_table').rows.length;
    }

    var item_total = 0;
    var item_disc = 0;
    for (var i = 1; i < numRows + 1; i++) {

        item_total = cleanCurrency($('#total_item_' + i).val());
        item_disc = (item_total / total_amount) * discount_amount;
        //        alert('item_total:'+item_total);
        //        alert('total_amount:'+total_amount);
        //        alert('discount_amount:'+discount_amount);
        //        alert('item_disc:'+item_disc);
        $('#total_' + i).val(formatCurrency(cleanCurrency(item_disc.toString())));
        lineTotal = parseFloat(item_disc);
        //lineTotal = item_disc.toString();

        if (document.getElementById('tax_' + i).value != '0') {
            tax_index = document.getElementById('tax_' + i).selectedIndex;
            if (tax_index == -1) { // if the rate array index is not defined
                tax_index = 0;
                document.getElementById('tax_' + i).value = tax_index;
            }
            taxable_subtotal += lineTotal * (tax_rates[tax_index].rate / 100);
        }


        subtotal += lineTotal;
    }
    //    alert(taxable_subtotal);
    //    alert(subtotal);
    $('#discount_tax').val(formatCurrency(cleanCurrency(taxable_subtotal.toString())));
    $('#total_discount').val(formatCurrency(cleanCurrency((subtotal + taxable_subtotal).toString())));
// recalculate discount

}

function calculateDiscountPercent() {
    var percent = parseFloat(cleanCurrency(document.getElementById('disc_percent').value));
    var subTotal = parseFloat(cleanCurrency(document.getElementById('subtotal').value));
    var discount = new String((percent / 100) * subTotal);
    document.getElementById('discount').value = formatCurrency(discount);
    updateTotalPrices();
}

function calculateDiscount() {
    // determine the discount percent
    var discount = parseFloat(cleanCurrency(document.getElementById('discount').value));
    //alert(discount)
    if (isNaN(discount))
        discount = 0;
    var subTotal = parseFloat(cleanCurrency(document.getElementById('subtotal').value));
    // alert(subTotal)
    if (subTotal != 0) {
        var percent = 100000 * (1 - ((subTotal - discount) / subTotal));
        document.getElementById('disc_percent').value = Math.round(percent) / 1000;
    } else {
        document.getElementById('disc_percent').value = '0.00';
    }
    updateTotalPrices();
}

function showOverride() {
    if (document.getElementById('tb_icon_save'))
        document.getElementById('tb_icon_save').style.visibility = "hidden";
    if (document.getElementById('tb_icon_print'))
        document.getElementById('tb_icon_print').style.visibility = "hidden";
    if (document.getElementById('tb_icon_post_previous'))
        document.getElementById('tb_icon_post_previous').style.visibility = "hidden";
    if (document.getElementById('tb_icon_post_next'))
        document.getElementById('tb_icon_post_next').style.visibility = "hidden";
    $('#override_order').dialog('open');
}

function checkOverride() {
    var user = document.getElementById('override_user').value;
    var pass = document.getElementById('override_pass').value;
    $.ajax({
        type: "GET",
        url: 'index.php?module=ucounting&page=ajax&op=validate&u=' + user + '&p=' + pass + '&level=4',
        dataType: ($.browser.msie) ? "text" : "xml",
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
        },
        success: clearOverride
    });
}

function clearOverride(sXml) {
    var xml = parseXml(sXml);
    if (!xml)
        return;
    var result = $(xml).find("result").text();
    if (result == 'validated') {
        $('#override_order').dialog('close');
        if (document.getElementById('tb_icon_save'))
            document.getElementById('tb_icon_save').style.visibility = "";
        if (document.getElementById('tb_icon_print'))
            document.getElementById('tb_icon_print').style.visibility = "";
        if (document.getElementById('tb_icon_post_previous'))
            document.getElementById('tb_icon_post_previous').style.visibility = "";
        if (document.getElementById('tb_icon_post_next'))
            document.getElementById('tb_icon_post_next').style.visibility = "";
    } else {
        alert(adminNotValidated);
    }
}

function checkShipAll() {
    var numRows = 0;
    var item_count;
    if (single_line_list == '1') {
        numRows = document.getElementById('item_table').rows.length;
    } else {
        numRows = document.getElementById('item_table').rows.length;
    }
    for (var i = 1; i < numRows; i++) {
        item_count = parseFloat(document.getElementById('qty_' + i).value);
        if (item_count != 0 && !isNaN(item_count)) {
            document.getElementById('pstd_' + i).value = item_count;
        }
        updateRowTotal(i, false);
    }
}

function activateFields() {
    if (payments_installed) {
        var index = document.getElementById('payment_method').selectedIndex;
        for (var i = 0; i < document.getElementById('payment_method').options.length; i++) {
            document.getElementById('pm_' + i).style.visibility = 'hidden';
        }
        document.getElementById('pm_' + index).style.visibility = '';
    }
}

function updateDesc(rowID) {
// this function not used - it sets the chart of accounts description if required by the form
}

function buildFreightDropdown() {
    // fetch the selection
    if (!freightCarriers)
        return;
    var selectedCarrier = document.getElementById('ship_carrier').value;
    for (var i = 0; i < freightCarriers.length; i++) {
        if (freightCarriers[i] == selectedCarrier)
            break;
    }
    var selectedMethod = document.getElementById('ship_service').value;
    for (var j = 0; j < freightLevels.length; j++) {
        if (freightLevels[j] == selectedMethod)
            break;
    }
    // erase the drop-down
    while (document.getElementById('ship_service').options.length)
        document.getElementById('ship_service').remove(0);
    // build the new one, first check to see if None was selected
    if (i == freightCarriers.length)
        return; // None was selected, leave drop-down empty
    var m = 0; // allows skip if method is not available
    for (var k = 0; k < freightLevels.length; k++) {
        if (freightDetails[i][k] != '') {
            var newOpt = document.createElement("option");
            newOpt.text = freightDetails[i][k];
            document.getElementById('ship_service').options.add(newOpt);
            document.getElementById('ship_service').options[m].value = freightLevels[k];
            m++;
        }
    }
    // set the default choice 
    document.getElementById('ship_service').value = selectedMethod;
}

function recalculateCurrencies() {
    var workingTotal = 0;
    var workingUnitValue = 0;
    var itemTotal = 0;
    var numRows = 0;
    var newTotal = 0;
    var newValue = 0;
    var currentCurrency = document.getElementById('currencies_code').value;
    var currentValue = parseFloat(document.getElementById('currencies_value').value);
    var desiredCurrency = document.getElementById('display_currency').value;
    for (var i = 0; i < js_currency_codes.length; i++) {
        if (js_currency_codes[i] == desiredCurrency)
            newValue = js_currency_values[i];
    }
    // update the line item table
    if (single_line_list == '1') {
        numRows = document.getElementById('item_table').rows.length;
    } else {
        numRows = document.getElementById('item_table').rows.length;
    }
    for (var i = 1; i < numRows; i++) {
        itemTotal = parseFloat(cleanCurrency(document.getElementById('total_' + i).value, currentCurrency));
        if (isNaN(itemTotal))
            continue;
        workingTotal = itemTotal / currentValue;
        newTotal = workingTotal * newValue;
        switch (journalID) {
            case '3':
            case '4':
            case '9':
            case '10':
            case '32':
                workingUnitValue = newTotal / document.getElementById('qty_' + i).value;
                break;
            case '6':
            case '7':
            case '12':
            case '36':
            case '13':
            case '30':
            case '31':
            case '18':
            case '19':
            case '20':
            case '21':
                workingUnitValue = newTotal / document.getElementById('pstd_' + i).value;
                break;
            default:
        }
        if (isNaN(workingUnitValue))
            continue;
        document.getElementById('total_' + i).value = formatCurrency(new String(newTotal), desiredCurrency);
        document.getElementById('price_' + i).value = formatPrecise(new String(workingUnitValue), desiredCurrency);
    }
    // convert shipping
    var newFreight = parseFloat(document.getElementById('freight').value);
    newFreight = (newFreight / currentValue) * newValue;
    document.getElementById('freight').value = formatCurrency(new String(newFreight), desiredCurrency);

    updateTotalPrices();
    // prepare the page settings for post
    document.getElementById('currencies_code').value = desiredCurrency;
    document.getElementById('currencies_value').value = new String(newValue);
}

// AJAX auto load SKU pair
function loadSkuDetails(iID, rowCnt) {
    var qty = 0;
    var sku = '';
    if (!rowCnt)
        return;
    // if a sales order or purchase order exists, keep existing information.
    so_exists = document.getElementById('so_po_item_ref_id_' + rowCnt).value;
    if (so_exists != '')
        return;
    // check to see if there is a sku present
    if (!iID) {
        sku = document.getElementById('sku_' + rowCnt).value; // read the search field as the real value	  
    }
    if (sku == text_search)
        return;

    var cID = document.getElementById('bill_acct_id').value;
    var bID = document.getElementById('store_id').value;
    switch (journalID) {
        case  '3':
        case  '4':
        case  '9':
        case '10':
        case '32':
            qty = document.getElementById('qty_' + rowCnt).value;
            break;
        case  '6':
        case  '7':
        case '12':
        case '36':
        case '13':
        case '30':
        case '31':
        case '18':
        case '19':
        case '20':
        case '21':
            qty = document.getElementById('pstd_' + rowCnt).value;
            break;
        default:
    }
    $.ajax({
        type: "GET",
        url: 'index.php?module=inventory&page=ajax&op=inv_details&fID=skuDetails&bID=' + bID + '&cID=' + cID + '&qty=' + qty + '&iID=' + iID + '&sku=' + sku + '&rID=' + rowCnt + '&jID=' + journalID,
        dataType: ($.browser.msie) ? "text" : "xml",
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Ajax ErrorThrown: " + errorThrown + "\nTextStatus: " + textStatus + "\nError: " + XMLHttpRequest.responseText);
        },
        success: fillInventory
    });
}
// function created by shaheen for master stock item

// AJAX auto load SKU pair
function loadMasterSkuDetails(iID, rowCnt) {
    openwindow = window.open("index.php?module=ucbooks&page=masterStock&skuID=" + iID+"&rowCnt=" + rowCnt, "ucbooks", "width=1150px,height=800px,resizable=1,scrollbars=1,top=150,left=100");
}

function fillInventory(sXml) {
    var qty_pstd = 0;
    var qty = 0;
    var text = '';
    var exchange_rate = document.getElementById('currencies_value').value;
    var xml = parseXml(sXml);
    if (!xml)
        return;
    var rowCnt = $(xml).find("rID").text();
    var sku = $(xml).find("sku").first().text(); // only the first find, avoids bom add-ons
    if (!sku)
        return;
    document.getElementById('sku_' + rowCnt).value = sku;
    // document.getElementById('token-input-sku_'     +rowCnt).value       = sku;
    document.getElementById('sku_' + rowCnt).style.color = '';
    var imgSerial = document.getElementById('imgSerial_' + rowCnt);
    if (imgSerial != null && $(xml).find("inventory_type").text() == 'sr') {
        document.getElementById('imgSerial_' + rowCnt).style.display = '';
    }
    document.getElementById('weight_' + rowCnt).value = $(xml).find("item_weight").text();
    document.getElementById('stock_' + rowCnt).value = $(xml).find("branch_qty_in_stock").text(); // stock at this branch
    //document.getElementById('stock_'   +rowCnt).value       = $(xml).find("quantity_on_hand").text(); // to insert total stock available
    document.getElementById('lead_' + rowCnt).value = $(xml).find("lead_time").text();


    if (uom_qty == 1) {
        if ($(xml).find("uom_qty").text() == 0) {
            document.getElementById('uom_qty_' + rowCnt).value = 1;
        } else {
            document.getElementById('uom_qty_' + rowCnt).value = $(xml).find("uom_qty").text();
        }
    }
    if (uom == 1) {
        if ($(xml).find("uom").text() == '') {
            document.getElementById('uom_' + rowCnt).value = 'none';
        } else {
            document.getElementById('uom_' + rowCnt).value = $(xml).find("uom").text();
        }
    }

    /* ADD DISCOUNT */
    document.getElementById('discval_' + rowCnt).value = $(xml).find("discval").text();
    
    if (disc_percent > 0){
        document.getElementById('disc_percent_' + rowCnt).checked = true;
    }
    /* END ADD DISCOUNT */
    
    document.getElementById('inactive_' + rowCnt).value = $(xml).find("inactive").text();
    switch (journalID) {
        case  '3':
        case  '4':
            qty_pstd = 'qty_';
            if (journalID == '4') {
                $purchase_qty = $(xml).find("purch_package_quantity").text() != '' ? $(xml).find("purch_package_quantity").text() : 1;
            } else {
                $purchase_qty = 1;
            }
            document.getElementById('qty_' + rowCnt).value = $(xml).find("qty").first().text();
            if (uom_qty == 1) {
                if ($(xml).find("uom_qty").text() == 0) {
                    document.getElementById('uom_qty_' + rowCnt).value = 1;
                } else {
                    document.getElementById('uom_qty_' + rowCnt).value = $(xml).find("uom_qty").text();
                }
            }
            if (uom == 1) {
                if ($(xml).find("uom").text() == '') {
                    document.getElementById('uom_' + rowCnt).value = 'none';
                } else {
                    document.getElementById('uom_' + rowCnt).value = $(xml).find("uom").text();
                }
            }
            
             /* ADD DISCOUNT */
            document.getElementById('discval_' + rowCnt).value = $(xml).find("discval").text();

            if (disc_percent > 0){
                document.getElementById('disc_percent_' + rowCnt).checked = true;
            }
            /* END ADD DISCOUNT */
            
            document.getElementById('acct_' + rowCnt).value = $(xml).find("account_inventory_wage").text();
            document.getElementById('price_' + rowCnt).value = formatPrecise($(xml).find("sales_price").text() * exchange_rate * $purchase_qty);
            document.getElementById('full_' + rowCnt).value = formatCurrency($(xml).find("item_cost").text() * exchange_rate * $purchase_qty);

            if (default_sales_tax == -1)
                document.getElementById('tax_' + rowCnt).value = $(xml).find("purch_taxable").text();
            if ($(xml).find("description_purchase").text()) {
                document.getElementById('desc_' + rowCnt).value = $(xml).find("description_purchase").text();
                document.getElementById('descHidden_' + rowCnt).value = $(xml).find("description_purchase").text();
            } else {
                document.getElementById('desc_' + rowCnt).value = $(xml).find("description_short").text();
                document.getElementById('descHidden_' + rowCnt).value = $(xml).find("description_short").text();
            }
            break;
        case  '6':
        case  '7':
        case '31':
        case '21':
            qty_pstd = 'pstd_';
            document.getElementById('pstd_' + rowCnt).value = $(xml).find("qty").first().text();
            if (uom_qty == 1) {
                if ($(xml).find("uom_qty").text() == 0) {
                    document.getElementById('uom_qty_' + rowCnt).value = 1;
                } else {
                    document.getElementById('uom_qty_' + rowCnt).value = $(xml).find("uom_qty").text();
                }
            }
            if (uom == 1) {
                if ($(xml).find("uom").text() == '') {
                    document.getElementById('uom_' + rowCnt).value = 'none';
                } else {
                    document.getElementById('uom_' + rowCnt).value = $(xml).find("uom").text();
                }
            }
            /* ADD DISCOUNT */
            document.getElementById('discval_' + rowCnt).value = $(xml).find("discval").text();

            if (disc_percent > 0){
                document.getElementById('disc_percent_' + rowCnt).checked = true;
            }
            /* END ADD DISCOUNT */
            
            document.getElementById('acct_' + rowCnt).value = $(xml).find("account_inventory_wage").text();
            document.getElementById('price_' + rowCnt).value = formatPrecise($(xml).find("sales_price").text() * exchange_rate);
            document.getElementById('full_' + rowCnt).value = formatCurrency($(xml).find("item_cost").text() * exchange_rate);
            if (default_sales_tax == -1)
                document.getElementById('tax_' + rowCnt).value = $(xml).find("purch_taxable").text();
            if ($(xml).find("description_purchase").text()) {
                document.getElementById('desc_' + rowCnt).value = $(xml).find("description_purchase").text();
                document.getElementById('descHidden_' + rowCnt).value = $(xml).find("description_purchase").text();
            } else {
                document.getElementById('desc_' + rowCnt).value = $(xml).find("description_short").text();
                document.getElementById('descHidden_' + rowCnt).value = $(xml).find("description_short").text();
            }
            break;
        case  '9':
        case '10':
        case '32':
            qty_pstd = 'qty_';
            document.getElementById('qty_' + rowCnt).value = $(xml).find("qty").first().text();
            if (uom_qty == 1) {
                if ($(xml).find("uom_qty").text() == 0) {
                    document.getElementById('uom_qty_' + rowCnt).value = 1;
                } else {
                    document.getElementById('uom_qty_' + rowCnt).value = $(xml).find("uom_qty").text();
                }
            }
            if (uom == 1) {
                if ($(xml).find("uom").text() == '') {
                    document.getElementById('uom_' + rowCnt).value = 'none';
                } else {
                    document.getElementById('uom_' + rowCnt).value = $(xml).find("uom").text();
                }
            }
            
            /* ADD DISCOUNT */
            document.getElementById('discval_' + rowCnt).value = $(xml).find("discval").text();

            if (disc_percent > 0){
                document.getElementById('disc_percent_' + rowCnt).checked = true;
            }
            /* END ADD DISCOUNT */
            
            document.getElementById('acct_' + rowCnt).value = $(xml).find("account_sales_income").text();
            document.getElementById('price_' + rowCnt).value = formatPrecise($(xml).find("sales_price").text() * exchange_rate);
            document.getElementById('full_' + rowCnt).value = formatCurrency($(xml).find("full_price").text() * exchange_rate);
            if (default_sales_tax == -1)
                document.getElementById('tax_' + rowCnt).value = $(xml).find("item_taxable").text();
            if ($(xml).find("description_sales").text()) {
                document.getElementById('desc_' + rowCnt).value = $(xml).find("description_sales").text();
                document.getElementById('descHidden_' + rowCnt).value = $(xml).find("description_sales").text();
            } else {
                document.getElementById('desc_' + rowCnt).value = $(xml).find("description_short").text();
                document.getElementById('descHidden_' + rowCnt).value = $(xml).find("description_short").text();
            }
            break;
        case '12':
        case '36':
        case '13':
        case '30':
        case '19':

            qty_pstd = 'pstd_';
            document.getElementById('pstd_' + rowCnt).value = $(xml).find("qty").first().text();
            if (uom_qty == 1) {
                if ($(xml).find("uom_qty").text() == 0) {
                    document.getElementById('uom_qty_' + rowCnt).value = 1;
                } else {
                    document.getElementById('uom_qty_' + rowCnt).value = $(xml).find("uom_qty").text();
                }
            }
            if (uom == 1) {
                if ($(xml).find("uom").text() == '') {
                    document.getElementById('uom_' + rowCnt).value = 'none';
                } else {
                    document.getElementById('uom_' + rowCnt).value = $(xml).find("uom").text();
                }
            }
            
            /* ADD DISCOUNT */
            document.getElementById('discval_' + rowCnt).value = $(xml).find("discval").text();

            if (disc_percent > 0){
                document.getElementById('disc_percent_' + rowCnt).checked = true;
            }
            /* END ADD DISCOUNT */
            
            document.getElementById('acct_' + rowCnt).value = $(xml).find("account_sales_income").text();
            document.getElementById('price_' + rowCnt).value = formatPrecise($(xml).find("sales_price").text() * exchange_rate);
            document.getElementById('full_' + rowCnt).value = formatCurrency($(xml).find("full_price").text() * exchange_rate);
            if (default_sales_tax == -1)
                document.getElementById('tax_' + rowCnt).value = $(xml).find("item_taxable").text();
            if ($(xml).find("description_sales").text()) {
                document.getElementById('desc_' + rowCnt).value = $(xml).find("description_sales").text();
                document.getElementById('descHidden_' + rowCnt).value = $(xml).find("description_sales").text();
            } else {
                document.getElementById('desc_' + rowCnt).value = $(xml).find("description_short").text();
                document.getElementById('descHidden_' + rowCnt).value = $(xml).find("description_short").text();
            }
            break;
        default:
    }
    updateRowTotal(rowCnt, false);
    $(xml).find("stock_note").each(function() {
        text += $(this).find("text_line").text() + "\n";
    });
    if (text)
        alert(text);
    document.getElementById('desc_' + rowCnt).select();
    document.getElementById('desc_' + rowCnt).focus();
    if (single_line_list == '1') {
        rowCnt = document.getElementById('item_table').rows.length;
    } else {
        rowCnt = parseInt((document.getElementById('item_table').rows.length));
    }
    qty = document.getElementById(qty_pstd + rowCnt).value;
    var sku = document.getElementById('sku_' + rowCnt).value;

    if (qty != '' && sku != '' && sku != text_search)
        rowCnt = addInvRow();
//document.getElementById('sku_'+rowCnt).focus();

}

function InventoryProp(elementID) {
    var sku = document.getElementById('sku_' + elementID).value;
    if (sku != text_search && sku != '') {
        $.ajax({
            type: "GET",
            url: 'index.php?module=inventory&page=ajax&op=inv_details&fID=skuValid&strict=1&sku=' + sku,
            dataType: ($.browser.msie) ? "text" : "xml",
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
            },
            success: processSkuProp
        });
    }
}

function processSkuProp(sXml) {
    var xml = parseXml(sXml);
    if (!xml)
        return;
    if ($(xml).find("id").first().text() != 0) {
        var id = $(xml).find("id").first().text();
        window.open("index.php?module=inventory&page=main&action=properties&cID=" + id, "inventory", "width=1150px,height=600px,resizable=1,scrollbars=1,top=50,left=100");
    }
}

function ContactProp() {
    var type = '';
    var bill_acct_id = document.getElementById('bill_acct_id').value;
    switch (journalID) {
        case  '3':
        case  '4':
        case  '6':
        case  '7':
        case '31':
        case '20':
        case '21':
            type = 'v';
            break;
        case  '9':
        case '10':
        case '32':
        case '12':
        case '36':
        case '13':
        case '30':
        case '18':
        case '19':
            type = 'c';
            break;
        default:
    }
    if (bill_acct_id == 0 || bill_acct_id == '') {
        alert(no_contact_id);
    } else {
        window.open("index.php?module=contacts&page=main&type=" + type + "&action=properties&cID=" + bill_acct_id, "contacts", "width=1150px,height=700px,resizable=1,scrollbars=1,top=50,left=100");
    }
}

function PreProcessLowStock() {
    var rowCnt;
    if (!lowStockExecute) {
        alert(lowStockExecuted);
        return;
    }
    var acct = document.getElementById('bill_acct_id').value;
    if (!acct) {
        alert(lowStockNoVendor);
        return;
    }
    var store = document.getElementById('store_id').value;
    if (single_line_list == '1') {
        rowCnt = document.getElementById('item_table').rows.length;
    } else {
        rowCnt = document.getElementById('item_table').rows.length;
    }
    if (rowCnt <= 1)
        rowCnt = 1;
    if (isNaN(store))
        store = 0;
    $.ajax({
        type: "GET",
        url: 'index.php?module=ucbooks&page=ajax&op=low_stock&cID=' + acct + '&sID=' + store + '&rID=' + rowCnt,
        dataType: ($.browser.msie) ? "text" : "xml",
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
        },
        success: PostProcessLowStock
    });
}

function PostProcessLowStock(sXml) {
    var xml = parseXml(sXml);
    var exchange_rate = document.getElementById('currencies_value').value;
    if (!xml)
        return;
    var i = 0;
    $(xml).find("LowStock").each(function() {
        addInvRow();
        var rowCnt = $(this).find("rID").text();
        document.getElementById('sku_' + rowCnt).value = $(this).find("sku").text();
        document.getElementById('sku_' + rowCnt).style.color = '';
        document.getElementById('full_' + rowCnt).value = formatCurrency($(xml).find("full_price").text() * exchange_rate);
        document.getElementById('weight_' + rowCnt).value = $(this).find("item_weight").text();
        document.getElementById('stock_' + rowCnt).value = $(this).find("quantity").text();
        document.getElementById('lead_' + rowCnt).value = $(this).find("lead_time").text();
        document.getElementById('inactive_' + rowCnt).value = $(this).find("inactive").text();
        document.getElementById('qty_' + rowCnt).value = $(this).find("reorder_quantity").text();
        document.getElementById('acct_' + rowCnt).value = $(this).find("account_inventory_wage").text();
        document.getElementById('price_' + rowCnt).value = formatPrecise($(this).find("item_cost").text() * exchange_rate);
        document.getElementById('tax_' + rowCnt).value = $(this).find("purch_taxable").text();
        if ($(this).find("description_purchase").text()) {
            document.getElementById('desc_' + rowCnt).value = $(this).find("description_purchase").text();
            document.getElementById('descHidden_' + rowCnt).value = $(this).find("description_purchase").text();
        } else {
            document.getElementById('desc_' + rowCnt).value = $(this).find("description_short").text();
            document.getElementById('descHidden_' + rowCnt).value = $(this).find("description_short").text();
        }
        i++;
        updateRowTotal(rowCnt, false);
    });
    if (i == 0) {
        alert(lowStockNoProducts);
    } else {
        alert(lowStockProcessed + i);
    }
    lowStockExecute = false;
}
