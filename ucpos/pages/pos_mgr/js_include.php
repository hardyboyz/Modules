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
//  Path: /modules/ucpos/pages/pos_mgr/js_include.php
//
?>
<script type="text/javascript">
<!--
function init() {
  document.getElementById('search_text').focus();
  document.getElementById('search_text').select();
}

function check_form() {
  return true;
}

function printOrder(id) {
  var printWin = window.open("index.php?module=ucform&page=popup_gen&gID=<?php echo POPUP_FORM_TYPE; ?>&date=a&xfld=journal_main.id&xcr=EQUAL&xmin="+id,"reportFilter","width=1150px,height=550px,resizable=1,scrollbars=1,top=150px,left=100px");
  printWin.focus();
}

// -->
</script>