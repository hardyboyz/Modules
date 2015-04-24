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
//  Path: /modules/contacts/pages/popup_accts/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    var journalID = '<?php echo JOURNAL_ID; ?>';
    var fill = '<?php echo $fill; ?>';

    function init() {
        document.getElementById('search_text').focus();
        document.getElementById('search_text').select();
    }

    function check_form() {
        return true;
    }
    // Insert javscript file references here.


    // Insert other page specific functions here.
    function setReturnOrder(pointer) {
        var cID = 0; // the customer is in the order.
        var oID = document.getElementById('open_order_'+pointer).value;
        var goods_return = 1;
        var dialog = '';
        if(journalID == 13 || journalID == 7){
            dialog = "<div id='dialog-confirm' title='Cause For the Credit Memo'><p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span> Is this Credit Memo for a Goods Return or Discount Given?</p></div>";
        }else if(journalID == 30 || journalID == 31){
            dialog = "<div id='dialog-confirm' title='Cause For the Debit Memo'><p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span> Is this Debit Memo for a Goods Return or Discount Given?</p></div>";
        }
        window.opener.ClearForm();
        if(journalID==13 || journalID==7 || journalID == 30 || journalID == 31 ){
            $( dialog ).dialog({
                resizable: false,
                height:200,
                modal: true,
                buttons: {
                    "Goods Return": function() {
                        goods_return  = 1;
                        window.opener.ajaxOrderData(cID, oID, journalID, true, false,goods_return);
                        self.close();
                    },
                    "Discount Given": function() {
                        goods_return  = 0;
                        window.opener.ajaxOrderData(cID, oID, journalID, true, false,goods_return);
                        self.close();
                    }
                }
            });
        }
        else{
            goods_return  = 1;
            window.opener.ajaxOrderData(cID, oID, journalID, true, false,goods_return);
            self.close();
        }
        
        //        if(!confirm("Is this Credit Memo for a Goods Return ?")){
        //            goods_return  = 0;
        //        };      
        
    }

    function setReturnAccount(cID) {
        var oID = 0; // contact only
        if (fill == 'ship') {
            var ship_only = true;
            window.opener.clearAddress('ship');
        } else {
            var ship_only = false;
            window.opener.ClearForm();
        }
        window.opener.ajaxOrderData(cID, oID, journalID, false, ship_only);
        self.close();
    }

    // -->
</script>