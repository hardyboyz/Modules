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
//  Path: /modules/inventory/pages/popup_inv/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    // Include translations here as well.
    $(document).ready(function(){
<?php if ($action == 'new') { ?>
            masterStockTitle(0);
            masterStockTitle(1);
            masterStockBuildSkus();
<?php } ?>
    })
    var openerDoc;
    var masterWindow;
        
    function check_form() {
        return true;
    }
    function init(){}
    // Insert other page specific functions here.
    function setReturnItem(iID,rID) {
        masterWindow.loadSkuDetails(iID, rID);
        self.close();  
    }
    function setReturnItemOpenar(iID,rID) {
        window.opener.loadSkuDetails(iID, rID);
        self.close();
    }
    
    function LoadNewMasterStock(iID,rowCnt){
      window.location.href = "index.php?module=ucbooks&page=masterStock&action=new&skuID=" + iID+"&rowCnt=" + rowCnt+""; 
    }
    function SaveNewMasterStock(iID,rowCnt){
        masterWindow = window.openerDoc;
        window.open("index.php?module=ucbooks&page=masterStock&action=save&skuID=" + iID+"&rowCnt=" + rowCnt, "ucbooks", "width=1150px,height=800px,resizable=1,scrollbars=1,top=150,left=100");
        
    }
    
    function masterStockTitle(id) {
        if(document.all) { // IE browsers
            document.getElementById('sku_list').rows[1].cells[id+1].innerText = document.getElementById('attr_name_'+id).value;
        } else { //firefox
            document.getElementById('sku_list').rows[1].cells[id+1].textContent = document.getElementById('attr_name_'+id).value;
        }
    }

    function masterStockBuildList(action, id) {
        switch (action) {
            case 'add':
                if (document.getElementById('attr_id_'+id).value == '' || document.getElementById('attr_id_'+id).value == '') {
                    alert('<?php echo JS_MS_INVALID_ENTRY; ?>');
                    return;
                }
                var newOpt = document.createElement("option");
                newOpt.text = document.getElementById('attr_id_'+id).value + ' : ' + document.getElementById('attr_desc_'+id).value;
                newOpt.value = document.getElementById('attr_id_'+id).value + ':' + document.getElementById('attr_desc_'+id).value;
                document.getElementById('attr_index_'+id).options.add(newOpt);
                document.getElementById('attr_id_'+id).value = '';
                document.getElementById('attr_desc_'+id).value = '';
                break;

            case 'delete':
                if (confirm('<?php echo INV_MSG_DELETE_INV_ITEM; ?>')) {
                    var elementIndex = document.getElementById('attr_index_'+id).selectedIndex;
                    document.getElementById('attr_index_'+id).remove(elementIndex);
                } else {
                    return;
                }
                break;

            default:
            }
            masterStockBuildSkus();
        }

        function masterStockBuildSkus() {
            var newRow, newCell, newValue0, newValue1, newValue2, attrib0, attrib1;
            var ms_attr_0 = '';
            var ms_attr_1 = '';
            while (document.getElementById('sku_list_body').rows.length > 0) {
                document.getElementById('sku_list_body').deleteRow(0);
            }
            var sku = document.getElementById('sku').value;
            newValue0 = '';
            newValue1 = '';
            newValue2 = '';
            if (document.getElementById('attr_index_0').length) {
                for (i=0; i<document.getElementById('attr_index_0').length; i++) {
                    attrib0 = document.getElementById('attr_index_0').options[i].value;
                    ms_attr_0 += attrib0 + ',';
                    attrib0 = attrib0.split(':');
                    newValue0 = sku + '-' + attrib0[0];
                    newValue1 = attrib0[1];
                    if (document.getElementById('attr_index_1').length) {
                        for (j=0; j<document.getElementById('attr_index_1').length; j++) {
                            attrib1 = document.getElementById('attr_index_1').options[j].value
                            attrib1 = attrib1.split(':');
                            newValue0 = sku + '-' + attrib0[0] + attrib1[0];
                            newValue2 = attrib1[1];
                            insertTableRow(newValue0, newValue1, newValue2);
                        }
                    } else {
                        insertTableRow(newValue0, newValue1, newValue2);
                    }
                }
            } else { // blank row
                insertTableRow(newValue0, newValue1, newValue2);
            }

            for (j=0; j<document.getElementById('attr_index_1').length; j++) {
                attrib1 = document.getElementById('attr_index_1').options[j].value;
                ms_attr_1 += attrib1 + ',';
            }

            document.getElementById('ms_attr_0').value = ms_attr_0;
            document.getElementById('ms_attr_1').value = ms_attr_1;
        }

        function insertTableRow(newValue0, newValue1, newValue2) {
            newRow = document.getElementById('sku_list_body').insertRow(-1);
            if(document.all) { // IE browsers
                newCell = newRow.insertCell(-1);
                newCell.innerText = newValue0;
                newCell = newRow.insertCell(-1);
                newCell.innerText = newValue1;
                newCell = newRow.insertCell(-1);
                newCell.innerText = newValue2;
            } else { //firefox
                newCell = newRow.insertCell(-1);
                newCell.textContent = newValue0;
                newCell = newRow.insertCell(-1);
                newCell.textContent = newValue1;
                newCell = newRow.insertCell(-1);
                newCell.textContent = newValue2
            }
        }
        function masterStockTitle(id) {
            if(document.all) { // IE browsers
                document.getElementById('sku_list').rows[1].cells[id+1].innerText = document.getElementById('attr_name_'+id).value;
            } else { //firefox
                document.getElementById('sku_list').rows[1].cells[id+1].textContent = document.getElementById('attr_name_'+id).value;
            }
        }

        function masterStockBuildList(action, id) {
            switch (action) {
                case 'add':
                    if (document.getElementById('attr_id_'+id).value == '' || document.getElementById('attr_id_'+id).value == '') {
                        alert('<?php echo JS_MS_INVALID_ENTRY; ?>');
                        return;
                    }
                    var newOpt = document.createElement("option");
                    newOpt.text = document.getElementById('attr_id_'+id).value + ' : ' + document.getElementById('attr_desc_'+id).value;
                    newOpt.value = document.getElementById('attr_id_'+id).value + ':' + document.getElementById('attr_desc_'+id).value;
                    document.getElementById('attr_index_'+id).options.add(newOpt);
                    document.getElementById('attr_id_'+id).value = '';
                    document.getElementById('attr_desc_'+id).value = '';
                    break;

                case 'delete':
                    if (confirm('<?php echo INV_MSG_DELETE_INV_ITEM; ?>')) {
                        var elementIndex = document.getElementById('attr_index_'+id).selectedIndex;
                        document.getElementById('attr_index_'+id).remove(elementIndex);
                    } else {
                        return;
                    }
                    break;

                default:
                }
                masterStockBuildSkus();
            }

            function masterStockBuildSkus() {
                var newRow, newCell, newValue0, newValue1, newValue2, attrib0, attrib1;
                var ms_attr_0 = '';
                var ms_attr_1 = '';
                while (document.getElementById('sku_list_body').rows.length > 0) {
                    document.getElementById('sku_list_body').deleteRow(0);
                }
                var sku = document.getElementById('sku').value;
                newValue0 = '';
                newValue1 = '';
                newValue2 = '';
                if (document.getElementById('attr_index_0').length) {
                    for (i=0; i<document.getElementById('attr_index_0').length; i++) {
                        attrib0 = document.getElementById('attr_index_0').options[i].value;
                        ms_attr_0 += attrib0 + ',';
                        attrib0 = attrib0.split(':');
                        newValue0 = sku + '-' + attrib0[0];
                        newValue1 = attrib0[1];
                        if (document.getElementById('attr_index_1').length) {
                            for (j=0; j<document.getElementById('attr_index_1').length; j++) {
                                attrib1 = document.getElementById('attr_index_1').options[j].value
                                attrib1 = attrib1.split(':');
                                newValue0 = sku + '-' + attrib0[0] + attrib1[0];
                                newValue2 = attrib1[1];
                                insertTableRow(newValue0, newValue1, newValue2);
                            }
                        } else {
                            insertTableRow(newValue0, newValue1, newValue2);
                        }
                    }
                } else { // blank row
                    insertTableRow(newValue0, newValue1, newValue2);
                }

                for (j=0; j<document.getElementById('attr_index_1').length; j++) {
                    attrib1 = document.getElementById('attr_index_1').options[j].value;
                    ms_attr_1 += attrib1 + ',';
                }

                document.getElementById('ms_attr_0').value = ms_attr_0;
                document.getElementById('ms_attr_1').value = ms_attr_1;
            }

            function insertTableRow(newValue0, newValue1, newValue2) {
                newRow = document.getElementById('sku_list_body').insertRow(-1);
                if(document.all) { // IE browsers
                    newCell = newRow.insertCell(-1);
                    newCell.innerText = newValue0;
                    newCell = newRow.insertCell(-1);
                    newCell.innerText = newValue1;
                    newCell = newRow.insertCell(-1);
                    newCell.innerText = newValue2;
                } else { //firefox
                    newCell = newRow.insertCell(-1);
                    newCell.textContent = newValue0;
                    newCell = newRow.insertCell(-1);
                    newCell.textContent = newValue1;
                    newCell = newRow.insertCell(-1);
                    newCell.textContent = newValue2
                }
            }
    
            // -->
</script>
