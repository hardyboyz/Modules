<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011 UcSoft, LLC             |
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
//  Path: /modules/work_orders/pages/main/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass some php variables
    var fetchDetails  = false;
<?php echo js_calendar_init($cal_date); ?>

    // required function called with every page load
    function init() {
        $('#inv_image').dialog({ autoOpen:false, width:800 });
<?php
if ($action <> 'new' && $action <> 'edit' && $action <> 'build') { // set focus for main window
    echo "  document.getElementById('search_text').focus();";
    echo "  document.getElementById('search_text').select();";
}
?>
<?php
if (!$error && $action == 'print')
    echo '  printWOrder(' . $id . ');';
if (!$error && $action == 'new')
    echo '  document.getElementById("sku").focus();';
?>
    }

    function check_form() {
        var error = 0;
        var error_message = "<?php echo JS_ERROR; ?>";
        if (error == 1) {
            alert(error_message);
            return false;
        } else {
            return true;
        }
    }
   $(document).keydown(function(e) {
        if(e.keyCode==9){
            $("input[id*='sku']").focusout(function(){
                $('#item_list .selected').trigger('click');
                $('#item_list').hide();
            })
        }
    });
    
    $("input[id*='sku']").live('keyup',function(e){
       // var id = $(this).attr('id').split('_');
        if(e.keyCode == 13){
            $('#item_list .selected').trigger('click');
            $('#item_list').hide();
        }else{
            if (e.keyCode != 40 && e.keyCode != 38) { 
                var keyword = $(this).val();
              
                $.ajax({
                    type: "GET",
                    url: 'index.php?module=ucbooks&page=ajax&op=autocopmplete&action=item&row=1&keyword='+keyword,
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
                            $('#sku').parent('span').attr('class','input_container1');
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
        $("input[id*='sku']").live('focusout',function(){
            $('#item_list').hide();
        });
    });
    function setReturnItem(iID,rID) {
        // alert(iID)
        loadSkuDetails(iID,rID);
        $('#item_list').hide();
    }
    function deleteWO(id) {
        location.href = 'index.php?module=work_orders&page=main&action=delete&id='+id;
    }

    function InventoryList(rowCnt) {
        var storeID = document.getElementById('store_id').value;
        var sku     = document.getElementById('sku').value;
        window.open("index.php?module=inventory&page=popup_inv&type=v&f1=as&rowID="+rowCnt+"&storeID="+storeID+"&cID=0&search_text="+sku,"inventory","width=1150px,height=550px,resizable=1,scrollbars=1,top=150,left=100");
    }

    function loadSkuDetails(iID, rowCnt) {
        var qty, bID, sku;
        if (!rowCnt) return;
        // check to see if there is a sku present
        if (!iID) sku = document.getElementById('sku').value;
        if (sku == text_search) return;
        bID = document.getElementById('store_id').value;
        qty = document.getElementById('qty').value;
        $.ajax({
            type: "GET",
            contentType: "application/xml; charset=utf-8",
            url: 'index.php?module=inventory&page=ajax&op=inv_details&fID=skuDetails&bID='+bID+'&cID=0&qty='+qty+'&iID='+iID+'&sku='+sku+'&rID='+rowCnt,
            dataType: ($.browser.msie) ? "text" : "xml",
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
            },
            success: fillInventory
        });
    }

    function fillInventory(sXml) {
        var xml = parseXml(sXml);
        if (!xml) return;
        var iID = $(xml).find("id").first().text();
        var sku = $(xml).find("sku").first().text();
        if (!iID) return;
        document.getElementById('sku_id').value = iID;
        document.getElementById('sku').value    = sku;
        // Fetch wo details
        $.ajax({
            type: "GET",
            contentType: "application/json; charset=utf-8",
            url: 'index.php?module=work_orders&page=ajax&op=load_task_list&iID='+iID,
            dataType: ($.browser.msie) ? "text" : "xml",
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
            },
            success: fillTaskList
        });
        fetchBOMList();
    }

    function fillTaskList(sXml) { // call back function
        var tbody, row, td;
        var xml = parseXml(sXml);
        if (!xml) return;
        document.getElementById('wo_id').value    = $(xml).find("WOid").text();
        document.getElementById('wo_title').value = $(xml).find("WOTitle").text();
        if ($(xml).find("ImageURL").text()) {
            var imgURL = $(xml).find("ImageURL").text();
            var temp = '<img src="'+imgURL+'" alt="ALT" title="TITLE" height="64" />';
            document.getElementById("image_here").innerHTML = temp;
        } else {
            document.getElementById("image_here").innerHTML = '&nbsp;';
        }
        while (document.getElementById('table_tasks').rows.length) document.getElementById("table_tasks").deleteRow(-1);
        if ($(xml).find("Message").text()) {
            alert($(xml).find("Message").text());
            return;
        }
        var odd = true;
        tbody = document.getElementById('table_tasks');
        $(xml).find("Task").each(function() {
            row = document.createElement("TR");
            row.setAttribute('class', odd?'odd':'even'); 
            td = document.createElement("TD");
            td.setAttribute('align', 'center'); 
            td.innerHTML = $(this).find("Step").text();
            row.appendChild(td);
            td = document.createElement("TD");
            td.innerHTML = $(this).find("Task_name").text();
            row.appendChild(td);
            td = document.createElement("TD");
            td.innerHTML = $(this).find("Description").text();
            row.appendChild(td);
            tbody.appendChild(row);
            odd = !odd;
        });
    }

    function printWOrder(id) { // request funtion
        $.ajax({
            type: "GET",
            contentType: "application/json; charset=utf-8",
            url: 'index.php?module=work_orders&page=ajax&op=load_wo_detail&id='+id,
            dataType: ($.browser.msie) ? "text" : "xml",
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
            },
            success: fillwoDetail
        });
    }

    function fillwoDetail(sXml) { // call back function
        var xml = parseXml(sXml);
        if (!xml) return;
        var id = $(xml).find("id").text();
        var details = "&gID=inv:wo&date=a&xfld=wo_journal_main.id&xcr=EQUAL&xmin="+id;
        if ($(xml).find("sEmail").text()) details += "&sEmail="+$(xml).find("sEmail").text();
        if ($(xml).find("sName").text())  details += "&sName=" +$(xml).find("sName").text();
        if ($(xml).find("rEmail").text()) details += "&rEmail="+$(xml).find("rEmail").text();
        if ($(xml).find("rName").text())  details += "&rName=" +$(xml).find("rName").text();
        var printWin = window.open("index.php?module=ucform&page=popup_gen"+details,"reportFilter","width=1150px,height=550px,resizable=1,scrollbars=1,top=150px,left=100px");
        printWin.focus();
    }

    // ajax pair to fetch whether there are enough parts to build number of sku requested.
    function fetchBOMList() { // request funtion
        var skuID = document.getElementById('sku_id').value;
        var qty   = document.getElementById('qty').value;
        if (skuID && qty) {
            $.ajax({
                type: "GET",
                contentType: "application/json; charset=utf-8",
                url: 'index.php?module=work_orders&page=ajax&op=load_bom_list&skuID='+skuID+'&qty='+qty,
                dataType: ($.browser.msie) ? "text" : "xml",
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
                },
                success: fillBOMList
            });
        }
    }

    function fillBOMList(sXml) {
        var xml = parseXml(sXml);
        if (!xml) return;
        var shortage = $(xml).find("shortage").text();
        if (shortage != 'none') {
            alert('<?php echo WO_INSUFFICIENT_INVENTORY; ?>'+"\n"+shortage);
        }
    }

    // -->
</script>