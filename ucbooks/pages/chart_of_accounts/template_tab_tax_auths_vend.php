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
//  Path: /modules/ucbooks/pages/admin/template_tab_tax_auths_vend.php
//
$tax_auths_vend_toolbar = new toolbar;
$tax_auths_vend_toolbar->icon_list['cancel']['show'] = false;
$tax_auths_vend_toolbar->icon_list['open']['show']   = false;
$tax_auths_vend_toolbar->icon_list['save']['show']   = false;
$tax_auths_vend_toolbar->icon_list['delete']['show'] = false;
$tax_auths_vend_toolbar->icon_list['print']['show']  = false;
if ($security_level > 1) $tax_auths_vend_toolbar->add_icon('new', 'onclick="loadPopUp(\'tax_auths_vend_new\', 0)"', $order = 10);

?>
<div id="tab_tax_auths_vend">
  <?php echo $tax_auths_vend_toolbar->build_toolbar(); ?>
  <h1><?php echo $tax_auths_vend->title; ?></h1>
  <div id="tax_auths_vend_content"><?php echo $tax_auths_vend->build_main_html(); ?></div>
</div>
