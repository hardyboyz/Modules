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
//  Path: /modules/ucform/pages/admin/template_tab_tools.php
//
?>
<div id="tab_tools">
<fieldset>
  <legend><?php echo UCFORM_TOOLS_REBUILD_TITLE; ?></legend>
  <p><?php echo UCFORM_TOOLS_REBUILD_DESC; ?></p>
  <p align="center"><?php echo UCFORM_TOOLS_REBUILD_SUBMIT . ' ' . html_button_field('fix', TEXT_SUBMIT, 'onclick="submitToDo(\'fix\')"'); ?>
</fieldset>
</div>
