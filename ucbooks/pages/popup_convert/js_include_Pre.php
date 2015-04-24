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
//  Path: /modules/ucbooks/pages/popup_convert/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    // Include translations here as well.

    function init() {
<?php
if ($action == 'save' && !$error) {
    echo '  window.opener.force_clear = true;' . "\n";
    echo '  window.opener.ClearForm();' . "\n";
    echo '  self.close();' . "\n";
    if($_POST['jID']==3){
        echo 'window.opener.document.location.href="index.php?module=ucbooks&page=orders&oID=' . $_POST['oID'] . '&jID=4&action=edit"';
    }else if ($_POST['conv_type'] == 'inv') {
        echo 'window.opener.document.location.href="index.php?module=ucbooks&page=orders&oID=' . $_POST['oID'] . '&jID=12&action=edit"';
    }else if($_POST['conv_type'] == 'do'){
         echo 'window.opener.document.location.href="index.php?module=ucbooks&page=orders&oID=' . $_POST['oID'] . '&jID=32&action=edit"';
    } else {
        echo 'window.opener.document.location.href="index.php?module=ucbooks&page=orders&oID=' . $_POST['oID'] . '&jID=10&action=edit"';
    }
}
?>
      }

      function check_form() {
          return true;
      }
      // Insert javscript file references here.

      // -->
</script>