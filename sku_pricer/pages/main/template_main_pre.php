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
//  Path: /modules/sku_pricer/pages/main/template_main.php
//
// start the form
echo html_form('sku_pricer', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"', true) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['save']['params']   = 'onclick="submitToDo(\'save\')"';
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['print']['show']    = false;
//echo $toolbar->build_toolbar();
// Build the page
?>
<div class="pageHeading"><?php echo PAGE_TITLE; ?></div>
<table align="center" border="0" cellspacing="0" cellpadding="1" style="background-color: #eeeeee">
  <tr>
    <td><?php echo SKU_PRICER_SELECT; ?></td>
  </tr>
  <tr>
	<td align="center"><?php echo html_file_field('file_name') . '<br /><br />'; ?></td>
  </tr>
  <tr>
    <td><?php echo SKU_PRICER_DIRECTIONS; ?></td>
  </tr>
</table>
</form>
<div class="bottom_btn">
    <?php echo $toolbar->build_toolbar();  ?>
</div>
<br/>
