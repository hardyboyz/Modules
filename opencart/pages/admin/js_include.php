<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/pages/admin/js_include.php
//
?>
<script type="text/javascript">
<!--
// pass any php variables generated during pre-process that are used in the javascript functions.
// Include translations here as well.
function init() {
	$(function() {
		$('#admintabs').tabs();
	});
}

function check_form() {
  return true;
}

// Insert other page specific functions here.
function loadPopUp(action, id) {
  window.open("index.php?module=phreedom&page=popup_setup&topic="+module+"&subject="+module+"&action="+action+"&sID="+id,"popup_setup","width=500,height=550,resizable=1,scrollbars=1,top=150,left=200");
}

function togglePriceSheets() {
  if (document.getElementById('opencart_use_price_sheets').selectedIndex) {
    document.getElementById('price_sheet_row').style.display = '';
  } else {
    document.getElementById('price_sheet_row').style.display = 'none';
  }
}

// -->
</script>