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
//  Path: /modules/ucpos/pages/admin/template_tab_general.php
//

?>
<div id="general" class="tabset_content">
  <h2 class="tabset_label"><?php echo TEXT_UCPOS_SETTINGS; ?></h2>
  <fieldset class="formAreaTitle">
	<table width="600" align="center">
	  <tr><th colspan="2"><?php echo MENU_HEADING_CONFIG; ?></th></tr> 
	  <tr>
		<td><?php echo UCPOS_REQUIRE_ADDRESS_DESC; ?></td>
		<td><?php echo html_pull_down_menu('ucpos_require_address', $sel_yes_no, $_POST['ucpos_require_address'] ? $_POST['ucpos_require_address'] : UCPOS_REQUIRE_ADDRESS, ''); ?></td>
	  </tr>
	  <tr>
		<td><?php echo UCPOS_RECEIPT_PRINTER_NAME_DESC; ?></td>
		<td><?php echo html_input_field('ucpos_receipt_printer_name', $_POST['ucpos_receipt_printer_name'] ? $_POST['ucpos_receipt_printer_name'] : UCPOS_RECEIPT_PRINTER_NAME, ''); ?></td>
	  </tr>
	  <tr>
		<td><?php echo UCPOS_RECEIPT_PRINTER_STARTING_LINE_DESC ?></td>
		<td><?php echo html_input_field('ucpos_receipt_printer_starting_line', $_POST['ucpos_receipt_printer_starting_line'] ? $_POST['ucpos_receipt_printer_starting_line'] : UCPOS_RECEIPT_PRINTER_STARTING_LINE, ''); ?></td>
	  </tr>	  
	  <tr>
		<td><?php echo UCPOS_RECEIPT_PRINTER_CLOSING_LINE_DESC ?><br><a href="<?php echo DIR_WS_ADMIN.'modules/ucpos/printer_codes.htm'?>">opening drawer codes</a></td>
		<td><?php echo html_input_field('ucpos_receipt_printer_closing_line', $_POST['ucpos_receipt_printer_closing_line'] ? $_POST['ucpos_receipt_printer_closing_line'] : UCPOS_RECEIPT_PRINTER_CLOSING_LINE, ''); ?></td>
	  </tr>  
	</table>
  </fieldset>
</div>
