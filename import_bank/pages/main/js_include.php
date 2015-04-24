<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010 UcSoft, LLC                   |
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
// |                                                                 |
// | The license that is bundled with this package is located in the |
// | file: /doc/manual/ch01-Introduction/license.html.               |
// | If not, see http://www.gnu.org/licenses/                        |
// +-----------------------------------------------------------------+
//  Path: /modules/import_bank/pages/main/js_include.php
//

?>
<script type="text/javascript">
<!--
// pass any php variables generated during pre-process that are used in the javascript functions.
// Include translations here as well.

function init() {
<?php if ($action == 'beg_balances') echo 'updateBalance();' . chr(10); ?>
}

function check_form() {

<?php if ($action == 'beg_balances') { ?>
  // check for balance of credits and debits
  var bal_total = cleanCurrency(document.getElementById('balance_total').value);
  if (bal_total != 0) {
  	error_message += "<?php echo GL_ERROR_OUT_OF_BALANCE; ?>";
  	error = 1;
  }
<?php } ?>

  return true;
}


// -->
</script>