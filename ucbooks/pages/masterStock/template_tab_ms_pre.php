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
//  Path: /modules/inventory/pages/main/template_tab_ms.php
//
// start the master stock tab html
echo html_form('save', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"', true) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
$toolbar->icon_list['cancel']['show'] = false;
echo $toolbar->build_toolbar();
echo html_hidden_field('ms_attr_0', $cInfo->ms_attr_0) . chr(10);
echo html_hidden_field('ms_attr_1', $cInfo->ms_attr_1) . chr(10);
// Build the page
?>


<div id="tab_master">
    <div style="margin:auto">
        <div style="float:right;width:33%">
            <table id="sku_list" class="ui-widget" style="border-collapse:collapse;width:100%">
                <thead class="ui-widget-header">
                    <tr>
                        <th colspan="3" align="center"><?php echo INV_MS_CREATED_SKUS; ?></th>
                    </tr>
                    <tr>
                        <th><?php echo TEXT_SKU; ?></th>
                        <th><?php echo '&nbsp;'; ?></th>
                        <th><?php echo '&nbsp;'; ?></th>
                    </tr>
                </thead>
                <tbody id="sku_list_body" class="ui-widget-content">

                </tbody>
            </table>
        </div>
        <input type="hidden" name="sku" id="sku" value="<?php echo $sku; ?>"/>
        <div style="float:right;width:33%">
            <table class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto">
                <thead class="ui-widget-header">
                    <tr><th colspan="2"><?php echo INV_TEXT_ATTRIBUTE_2; ?></th></tr>
                </thead>
                <tbody class="ui-widget-content">
                    <tr>
                        <td><?php echo TEXT_TITLE; ?></td>
                        <td><?php echo html_input_field('attr_name_1', $cInfo->attr_name_1, 'size="11" onchange="masterStockTitle(1)"'); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?php echo INV_MASTER_STOCK_ATTRIB_ID . ' '; ?>
                            <?php echo html_input_field('attr_id_1', '', 'size="15" maxlength="15"', true); ?>
                            <?php echo html_button_field('attr_add_1', TEXT_ADD, 'onclick="masterStockBuildList(\'add\', 1)"', 'SSL'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo TEXT_DESCRIPTION; ?></td>
                        <td><?php echo html_input_field('attr_desc_1', '', '', true); ?></td>
                    </tr>
                    <tr>
                        <th colspan="2"><?php echo INV_TEXT_ATTRIBUTES; ?></th>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <table>
                                <tr>
                                    <td><?php echo html_pull_down_menu('attr_index_1', $attr_array1, '', 'size="5"'); ?></td>
                                    <td valign="top"><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick="masterStockBuildList(\'delete\', 1)"') . chr(10); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto">
                <thead class="ui-widget-header">
                    <tr><th colspan="2"><?php echo INV_TEXT_ATTRIBUTE_1; ?></th></tr>
                </thead>
                <tbody class="ui-widget-content">
                    <tr>
                        <td><?php echo TEXT_TITLE; ?></td>
                        <td><?php echo html_input_field('attr_name_0', $cInfo->attr_name_0, 'size="11" onchange="masterStockTitle(0)"'); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?php echo INV_MASTER_STOCK_ATTRIB_ID . ' '; ?>
                            <?php echo html_input_field('attr_id_0', '', 'size="15" maxlength="15"', true); ?>
                            <?php echo html_button_field('attr_add_0', TEXT_ADD, 'onclick="masterStockBuildList(\'add\', 0)"', 'SSL'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo TEXT_DESCRIPTION; ?></td>
                        <td><?php echo html_input_field('attr_desc_0', '', '', true); ?></td>
                    </tr>
                    <tr>
                        <th colspan="2"><?php echo INV_TEXT_ATTRIBUTES; ?></th>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <table>
                                <tr>
                                    <td><?php echo html_pull_down_menu('attr_index_0', $attr_array0, '', 'size="5"'); ?></td>
                                    <td valign="top"><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick="masterStockBuildList(\'delete\', 0)"') . chr(10); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
