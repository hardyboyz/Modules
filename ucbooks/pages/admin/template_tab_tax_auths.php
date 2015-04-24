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
//  Path: /modules/ucbooks/pages/admin/template_tab_tax_auths.php
//
if (isset($_GET['action_type']) && $_GET['action_type'] == 'sales_tax_authorities') {
    echo html_form('admin', FILENAME_DEFAULT,"module=ucbooks&page=admin&action_type=sales_tax_authorities", 'post', 'enctype="multipart/form-data"', true) . chr(10);
    echo html_hidden_field('todo', '') . chr(10);
    echo html_hidden_field('subject', '') . chr(10);
    echo html_hidden_field('rowSeq', '') . chr(10);
}
$tax_auths_toolbar = new toolbar;
$tax_auths_toolbar->icon_list['cancel']['show'] = false;
$tax_auths_toolbar->icon_list['open']['show'] = false;
$tax_auths_toolbar->icon_list['save']['show'] = false;
$tax_auths_toolbar->icon_list['delete']['show'] = false;
$tax_auths_toolbar->icon_list['print']['show'] = false;

if ($security_level > 1)
    $tax_auths_toolbar->add_icon('new', 'onclick="loadPopUp(\'tax_auths_new\', 0)"', $order = 10);
?>
<div id="tab_tax_auths">
    <?php echo $tax_auths_toolbar->build_toolbar(); ?>
    <h1>Sales Tax Authorities <?php //echo $tax_auths->title;   ?></h1>
    <div id="tax_auths_content"><?php echo $tax_auths->build_main_html(); ?></div>
</div>

<?php
    if (isset($_GET['action']) && $_GET['action'] == 'sales_tax_authorities') {
?>
</from>
<?php
    }
?>
