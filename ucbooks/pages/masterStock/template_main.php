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
//  Path: /modules/inventory/pages/popup_inv/template_main.php
//
echo html_form('search_form', FILENAME_DEFAULT, gen_get_all_get_params(array('action', 'f0', 'f1', 'f2', 'f3', 'f4', 'f5'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="self.close()"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['save']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
$toolbar->add_icon('new', 'onclick="LoadNewMasterStock(' . $skuid . ',' . $_GET['rowCnt'] . ')"', 'SSL', '+ New');
echo $toolbar->build_toolbar();
// Build the page
?>



<h1><?php
echo 'Master Stock Item';
?></h1>
<div id="filter_bar">

    <table class="ui-widget" style="border-collapse:collapse;width:100%;">
        <thead class="ui-widget-header">
            <tr>
                <th>SKU</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity On Hand</th>
                <th>Quantity On order</th>
            </tr>
        </thead>
        <tbody class="ui-widget-content">
            <?php
            $odd = true;
            while (!$query_result->EOF) {
                $bkgnd = ($query_result->fields['inactive']) ? ' style="background-color:pink"' : '';
                switch ($query_result->fields['inventory_type']) {
                    default:
                    case 'c': $price = $query_result->fields['full_price'];
                        break;
                    case 'v': $price = $query_result->fields['item_cost'];
                        break;
                }
                ?>
                <?php if ($action == 'save') { ?>
                    <tr class = "<?php echo $odd ? 'odd' : 'even'; ?>" style = "cursor:pointer" onclick = "setReturnItemOpenar(<?php echo $query_result->fields['id']; ?>,<?php echo $_GET['rowCnt']; ?>)">
                    <?php } else { ?>
                    <tr class = "<?php echo $odd ? 'odd' : 'even'; ?>" style = "cursor:pointer" onclick = "window.opener.setReturnItem(<?php echo $query_result->fields['id']; ?>,<?php echo $_GET['rowCnt']; ?>)">
                    <?php } ?>
                    <td<?php echo $bkgnd;
                    ?>><?php echo $query_result->fields['sku']; ?></td>
                    <td><?php echo $query_result->fields['description_short']; ?></td>
                    <td align="right"><?php echo $currencies->precise($price); ?></td>
                    <td align="center"><?php echo $query_result->fields['quantity_on_hand']; ?></td>
                    <td align="center"><?php echo $query_result->fields['quantity_on_order']; ?></td>
                </tr>
                <?php
                $query_result->MoveNext();
                $odd = !$odd;
            }
            ?>
        </tbody>
    </table>

</form>
