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
//  Path: /modules/inventory/pages/transfer/template_main.php
//
$hidden_fields = NULL;
echo html_form('inv_xfer', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('id', $cInfo->id) . chr(10);
echo html_hidden_field('ref_id', $cInfo->ref_id) . chr(10);
// customize the toolbar actions
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php"';
$toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
$toolbar->icon_list['open']['params'] = 'onclick="OpenXfrList()"';
$toolbar->icon_list['delete']['params'] = 'onclick="if (confirm(\'' . INV_ADJ_DELETE_ALERT . '\')) submitToDo(\'delete\')"';
if ($_GET['action'] == 'edit') {
    $toolbar->icon_list['print']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID=' . $_GET['jID'] . '&sub_jID='.SUB_JOURNAL_ID.'&amp;action=pdf&amp;pdf_type=transfer', 'SSL') . '\',\'_blank\')"';
    $toolbar->icon_list['print']['icon'] = 'devices/network-wired.png';
    $toolbar->icon_list['print']['text'] = "Generate Inventory Transfer PDF";
}else{
    $toolbar->icon_list['print']['show'] = false;
}
$toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL') . '\'"', $order = 2);
if ($security_level < 4)
    $toolbar->icon_list['delete']['show'] = false;
if ($security_level < 2)
    $toolbar->icon_list['save']['show'] = false;
if (count($extra_toolbar_buttons) > 0)
    foreach ($extra_toolbar_buttons as $key => $value)
        $toolbar->icon_list[$key] = $value;
$toolbar->add_help('07.04.02');
//echo $toolbar->build_toolbar();
// Build the page
if ($_SESSION['set_security'] != 'yes') {
    $toolbar->icon_list['new']['show'] = false;
    $toolbar->icon_list['open']['show'] = false;
}
?>
<style>
    .input_container input {
        width: 200px;
        padding: 3px;
        border: 1px solid #cccccc;
        border-radius: 0;
    }
    .input_container ul {
        background: none repeat scroll 0 0 #ffffff;
        border: 1px solid #000000;
        max-height: 200px;
        list-style: none outside none;
        overflow-y: scroll;
        position: absolute;
        width: 320px;
        z-index: 1;
        margin: 0px;
        padding: 0px;
        min-height: 20px;
    }
    .selected{
        background-color: #d0efa0;
    }
    .input_container ul li {
        padding: 2px;
        cursor: pointer;
    }
    .input_container ul li:hover {
        background: #d0efa0;
    }
    #customer_list {
        display: none;
    }
    .input_container1 input {
        width: 320px;
        padding: 3px;
        border: 1px solid #cccccc;
        border-radius: 0;
        text-align: left;
    }
    .input_container1 ul {
        background: none repeat scroll 0 0 #ffffff;
        border: 1px solid #000000;
        max-height: 200px;
        list-style: none outside none;
        overflow-y: scroll;
        position: absolute;
        width: 320px;
        z-index: 1;
        margin: 0px;
        padding: 0px;
        min-height: 20px;
    }

    .input_container1 ul li {
        padding: 2px;
        cursor: pointer;
        text-align: left;
    }
    .input_container1 ul li:hover {
        background: #d0efa0;
    }
    #item_list {
        display: none;
        margin-top: 0px;
    }
</style>
<!--Top Error Message Start -->
<div class="bottom_btn" id="bottom_btn">
<?php
echo $toolbar->build_toolbar();
?>
</div>
<!--Top Error Message End -->
<h1><?php echo PAGE_TITLE; ?></h1>
<div>
    <table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto;width:100%;">
        <tbody class="ui-widget-content">
            <tr>
                <td align="right"><?php echo INV_XFER_FROM_STORE; ?></td>
                <td>
<?php echo html_pull_down_menu('source_store_id', gen_get_store_ids(), $cInfo->source_store_id ? $cInfo->source_store_id : $_SESSION['admin_prefs']['def_store_id']); ?>
<?php echo INV_XFER_TO_STORE . html_pull_down_menu('dest_store_id', gen_get_store_ids(), $cInfo->dest_store_id); ?>
                </td>
                <td align="right"><?php echo TEXT_POST_DATE . '&nbsp;'; ?></td>
                <td><?php echo html_calendar_field($cal_xfr); ?></td>
            </tr>
            <tr>
                <td align="right"><?php echo TEXT_TRANSFER_REASON; ?></td>
                <td><?php echo html_input_field('adj_reason', $cInfo->adj_reason, 'size="50"'); ?></td>
                <td align="right"><?php echo TEXT_REFERENCE; ?></td>
                <td><?php echo html_input_field('purchase_invoice_id', $cInfo->purchase_invoice_id); ?></td>
            </tr>
            <tr>
                <td align="right"><?php echo TEXT_TRANSFER_ACCT; ?></td>
                <td><?php echo html_pull_down_menu('gl_acct', $gl_array_list, $cInfo->gl_acct ? $cInfo->gl_acct : INV_STOCK_DEFAULT_INVENTORY); ?></td>
            </tr>
            <tr><td collspan="3">   &nbsp;
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div>
    <table class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto;width:100%;">
        <thead class="ui-widget-header">
            <tr>
                <th><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small'); ?></th>
                <th><?php echo TEXT_QUANTITY; ?></th>
                <th><?php echo INV_HEADING_QTY_IN_STOCK; ?></th>
                <th><?php echo TEXT_BALANCE; ?></th>
                <th><?php echo TEXT_SKU; ?></th>
                <th><?php echo TEXT_DESCRIPTION; ?></th>
            </tr>
        </thead>
        <tbody id="item_table" class="ui-widget-content">
            <?php
            if (!$error) {
                $hidden_fields .= '<script type="text/javascript">addInvRow();</script>';
            } else {
                $rowCnt = 1;
                while (true) {
                    if (!isset($_POST['sku_' . $rowCnt]))
                        break;
                    echo '  <tr>' . chr(10);
                    echo '    <td>' . html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick="if (confirm(\'' . INV_MSG_DELETE_INV_ITEM . '\')) removeInvRow(' . $rowCnt . ');"') . '</td>' . chr(10);
                    echo '    <td>' . html_input_field('qty_' . $rowCnt, $_POST['qty_' . $rowCnt], 'size="6" maxlength="5" style="text-align:right" onchange="updateBalance()"') . '</td>' . chr(10);
                    echo '    <td>' . html_input_field('stock_' . $rowCnt, $_POST['stock_' . $rowCnt], 'readonly="readonly" size="6" maxlength="5" style="text-align:right"') . '</td>' . chr(10);
                    echo '    <td>' . html_input_field('balance_' . $rowCnt, $_POST['balance_' . $rowCnt], 'readonly="readonly" size="6" maxlength="5" style="text-align:right"') . '</td>' . chr(10);
                    echo '    <td nowrap="nowrap">' . chr(10);
                    echo html_input_field('sku_' . $rowCnt, $_POST['sku_' . $rowCnt], 'size="' . (MAX_INVENTORY_SKU_LENGTH + 1) . '" maxlength="' . MAX_INVENTORY_SKU_LENGTH . '" onfocus="clearField(\'sku_' . $rowCnt . '\', \'' . TEXT_SEARCH . '\')" onblur="setField(\'sku_' . $rowCnt . '\', \'' . TEXT_SEARCH . '\'); loadSkuDetails(0, ' . $rowCnt . ')"') . '&nbsp;';
                    echo html_icon('actions/system-search.png', TEXT_SEARCH, 'small', $params = 'align="top" style="cursor:pointer" onclick="InventoryList()"');
                    echo html_icon('actions/tab-new.png', TEXT_SERIAL_NUMBER, 'small', 'align="top" style="cursor:pointer; display:none;" onclick="serialList(\'serial_' . $rowCnt . '\')"');
// Hidden fields
                    echo html_hidden_field('serial_' . $rowCnt, $_POST['serial_' . $rowCnt]) . chr(10);
                    echo html_hidden_field('acct_' . $rowCnt, $_POST['acct_' . $rowCnt]) . chr(10);
// End hidden fields
                    echo '    </td>' . chr(10);
                    echo '    <td>' . html_input_field('desc_' . $rowCnt, $_POST['desc_' . $rowCnt], 'size="90"') . '</td>' . chr(10);
                    echo '  </tr>' . chr(10);
                    $rowCnt++;
                }
            }
            ?>
        </tbody>
        <tfoot class="ui-widget-footer">
            <tr>
                <td><?php echo html_icon('actions/list-add.png', TEXT_ADD, 'medium', 'onclick="addInvRow()"'); ?></td>
            <tr>
        </tfoot>
    </table>
</div>
<?php echo $hidden_fields; // display the hidden fields that are not used in this rendition of the form  ?>
<div class="bottom_btn">
<?php echo $toolbar->build_toolbar(); ?>
</div>
</form>
