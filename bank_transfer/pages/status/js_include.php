<?php
// +-----------------------------------------------------------------+
// |                   bank_transfer Open Source ERP                    |
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
//  Path: /modulesuc/bank_transfer/pages/status/js_include.php
//

?>
<script type="text/javascript">
<!--
// pass any php variables generated during pre-process that are used in the javascript functions.
// Include translations here as well.
var journal_ID = '<?php echo JOURNAL_ID; ?>';
var sub_journal_ID = '<?php echo SUB_JOURNAL_ID; ?>';

function init() {
  document.getElementById('search_text').focus();
  document.getElementById('search_text').select();
}

function check_form() {
  return true;
}

// Insert other page specific functions here.
function deliveryList(oID) {
  window.open("index.php?module=bank_transfer&page=popup_delivery&jID="+journal_ID+"&oID="+oID,"inv_delivery","width=1150,height=550,resizable=1,scrollbars=1,top=150,left=100");
}

function printOrder(id) {
  var printWin = window.open("index.php?module=ucform&page=popup_gen&gID=<?php echo POPUP_FORM_TYPE; ?>&date=a&xfld=journal_main.id&xcr=EQUAL&xmin="+id,"reportFilter","width=1150px,height=550px,resizable=1,scrollbars=1,top=150px,left=100px");
  printWin.focus();
}

function shipList(oID, carrier) {
  window.open("index.php?module=shipping&page=popup_label_mgr&method="+carrier+"&oID="+oID,"popup_label_mgr","width=1150,height=700,resizable=1,scrollbars=1,top=50,left=100");
}

function voidShipment(sID, carrier) {
  window.open("index.php?module=shipping&page=popup_label_mgr&method="+carrier+"&action=delete&sID="+sID,"popup_label_mgr","width=1150,height=700,resizable=1,scrollbars=1,top=50,left=100");
}

function closeShipment(carrier) {
  window.open("index.php?module=shipping&page=popup_label_mgr&method="+carrier+"&action=close","popup_label_mgr","width=1150,height=700,resizable=1,scrollbars=1,top=50,left=100");
}

function loadPopUp(subject, action, id) {
  window.open("index.php?module=shipping&page=popup_tracking&subject="+subject+"&action="+action+"&sID="+id,"popup_tracking","width=1150,height=350,resizable=1,scrollbars=1,top=150,left=100");
}

// -->
</script>