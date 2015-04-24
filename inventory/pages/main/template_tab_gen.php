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
//  Path: /modules/inventory/pages/main/template_tab_gen.php
//
?>
<style type="text/css">
    #serialize, #remove_image, #inactive{width:20px !important;}
</style>
<div id="tab_general">

    <div id="inv_image" title="<?php echo $cInfo->sku; ?>">
        <?php
        if ($cInfo->image_with_path) {
            echo html_image(DIR_WS_MY_FILES . $_SESSION['company'] . '/inventory/images/' . $cInfo->image_with_path, '', 600) . chr(10);
        } else {
            echo TEXT_NO_IMAGE;
        }
        ?>
        <div>
            <h2><?php echo TEXT_SKU . ': ' . $cInfo->sku; ?></h2>
            <p><?php echo '<br />' . $cInfo->description_sales; ?></p>
        </div>
    </div>
    <table class="ui-widget" style="border-style:none;width:100%">
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo isset($label['sku']) ? $label['sku'] : TEXT_SKU; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('sku', $cInfo->sku, 'readonly="readonly" size="' . (MAX_INVENTORY_SKU_LENGTH + 1) . '" maxlength="' . MAX_INVENTORY_SKU_LENGTH . '"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo isset($label['description_short']) ? $label['description_short'] : INV_ENTRY_INVENTORY_DESC_SHORT; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('description_short', $cInfo->description_short, 'size="33"', false); ?>
                            <?php if ($cInfo->id) echo '&nbsp;' . html_icon('categories/preferences-system.png', TEXT_WHERE_USED, 'small', 'onclick="ajaxWhereUsed()"') . chr(10); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_ITEM_MINIMUM_STOCK; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('minimum_stock_level', $cInfo->minimum_stock_level, 'size="6" style="text-align:right"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_ITEM_REORDER_QUANTITY; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('reorder_quantity', $cInfo->reorder_quantity, 'size="6" style="text-align:right"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_HEADING_LEAD_TIME; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('lead_time', $cInfo->lead_time, 'size="6" style="text-align:right"', false); ?></td>
                    </tr>

                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo TEXT_INACTIVE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_checkbox_field('inactive', '1', $cInfo->inactive); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_QTY_ON_HAND; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('quantity_on_hand', $cInfo->quantity_on_hand, 'disabled="disabled" size="6" style="text-align:right"', false); ?>

                        </td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_QTY_ON_ORDER; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('quantity_on_order', $cInfo->quantity_on_order, 'disabled="disabled" size="6" style="text-align:right"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_QTY_ON_ALLOCATION; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('quantity_on_allocation', $cInfo->quantity_on_allocation, 'disabled="disabled" size="6" style="text-align:right"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_QTY_ON_SALES_ORDER; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('quantity_on_sales_order', $cInfo->quantity_on_sales_order, 'disabled="disabled" size="6" style="text-align:right"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_ITEM_WEIGHT; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('item_weight', $cInfo->item_weight, 'size="11" style="text-align:right"', false); ?></td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h5><?php echo TEXT_CUSTOMER_DETAILS; ?></h5>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_INVENTORY_DESC_SALES; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_textarea_field('description_sales', 40, 2, $cInfo->description_sales, '', $reinsert_value = true); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_FULL_PRICE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%">
                            <?php
                            echo html_input_field('full_price', $currencies->precise($cInfo->full_price), 'size="15" style="text-align:right"', false);
                            if (ENABLE_MULTI_CURRENCY)
                                echo ' (' . DEFAULT_CURRENCY . ')';
                            echo '&nbsp;' . html_icon('mimetypes/x-office-spreadsheet.png', BOX_SALES_PRICE_SHEETS, 'small', $params = 'onclick="priceMgr(' . $cInfo->id . ', 0, 0, \'c\')"') . chr(10);
                            ?>
                        </td>
                    </tr>


                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_ITEM_TAXABLE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_pull_down_menu('item_taxable', $tax_rates, $cInfo->item_taxable); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo TEXT_DEFAULT_PRICE_SHEET; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"> <?php echo html_pull_down_menu('price_sheet', get_price_sheet_data('c'), $cInfo->price_sheet); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>

        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
        <?php if ($_SESSION['admin_security'][SECURITY_ID_PURCHASE_INVENTORY] > 0) { ?>
            <tr>
                <td colspan="2">
                    <h5><?php echo TEXT_VENDOR_DETAILS; ?></h5>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <table width="100%">
                        <tr>
                            <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_INVENTORY_DESC_PURCHASE; ?> </span></td>
                            <td width="4%">:</td>
                            <td width="56%"> <?php echo html_textarea_field('description_purchase', 40, 2, $cInfo->description_purchase, '', $reinsert_value = true); ?></td>
                        </tr>
                        <tr>
                            <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_INV_ITEM_COST; ?> </span></td>
                            <td width="4%">:</td>
                            <td width="56%">
                                <?php
                                echo html_input_field('item_cost', $currencies->precise($cInfo->item_cost), 'size="15" style="text-align:right"', false);
                                if (ENABLE_MULTI_CURRENCY)
                                    echo ' (' . DEFAULT_CURRENCY . ')';
                                echo '&nbsp;' . html_icon('mimetypes/x-office-spreadsheet.png', BOX_PURCHASE_PRICE_SHEETS, 'small', $params = 'onclick="priceMgr(' . $cInfo->id . ', 0, 0, \'v\')"') . chr(10);
                                if (($cInfo->inventory_type == 'as' || $cInfo->inventory_type == 'sa') && $cInfo->id)
                                    echo '&nbsp;' . html_icon('apps/accessories-calculator.png', TEXT_CURRENT_COST, 'small', 'onclick="ajaxAssyCost()"') . chr(10);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%"><span class="form_label" ><?php echo TEXT_DEFAULT_PRICE_SHEET; ?> </span></td>
                            <td width="4%">:</td>
                            <td width="56%"><?php echo html_pull_down_menu('price_sheet_v', get_price_sheet_data('v'), $cInfo->price_sheet_v); ?></td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table width="100%">
                        <tr>
                            <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_PURCH_TAX; ?> </span></td>
                            <td width="4%">:</td>
                            <td width="56%"><?php echo html_pull_down_menu('purch_taxable', $purch_tax_rates, $cInfo->purch_taxable); ?></td>
                        </tr>
                        <tr>
                            <td width="40%"><span class="form_label" ><?php echo INV_HEADING_PREFERRED_VENDOR; ?> </span></td>
                            <td width="4%">:</td>
                            <td width="56%"><?php echo html_pull_down_menu('vendor_id', gen_get_contact_array_by_type('v'), $cInfo->vendor_id); ?></td>
                        </tr>

                    </table>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h5><?php echo TEXT_ITEM_DETAILS; ?></h5>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table width="50%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_INVENTORY_TYPE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php
        echo html_hidden_field('inventory_type', $cInfo->inventory_type);
        echo html_input_field('inv_type_desc', $inventory_types_plus[$cInfo->inventory_type], 'readonly="readonly"', false);
        ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_HEADING_UPC_CODE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('upc_code', $cInfo->upc_code, 'size="16" style="text-align:right"', false); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_INVENTORY_COST_METHOD; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php
                            foreach ($cost_methods as $key => $value)
                                $cost_pulldown_array[] = array('id' => $key, 'text' => $value);
        ?>
                            <?php echo html_pull_down_menu('cost_method', $cost_pulldown_array, $cInfo->cost_method, (is_null($cInfo->last_journal_date) ? '' : 'disabled="disabled"')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_ACCT_SALES; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_pull_down_menu('account_sales_income', $gl_array_list, $cInfo->account_sales_income); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_ACCT_INV; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_pull_down_menu('account_inventory_wage', $gl_array_list, $cInfo->account_inventory_wage); ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_ACCT_COS; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_pull_down_menu('account_cost_of_sales', $gl_array_list, $cInfo->account_cost_of_sales); ?></td>
                    </tr>
                    <tr><td width="40%"><span class="form_label" >Location </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('location', $cInfo->location, 'style="text-align:left"', false); ?></td>
                    </tr>
                    <tr><td width="40%"><span class="form_label" >Cost Code.</span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('cost_code', $cInfo->cost_code, 'style="text-align:left"', false); ?></td>
                    </tr>
                    <?php if (UOM == 1) { ?>
                        <tr>
                            <td width="40%"><span class="form_label" >UOM</span></td>
                            <td width="4%">:</td>
                            <td width="56%"><?php echo html_pull_down_menu('uom', $uom_droup_down, $cInfo->uom); ?></td>
                        </tr>
                    <?php } if (UOM_QTY == 1) { ?>
                        <tr>
                            <td width="40%"><span class="form_label" >UOM Qty</span></td>
                            <td width="4%">:</td>
                            <td width="56%"><?php echo html_input_field('uom_qty', $cInfo->uom_qty, 'size="16" style=""'); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
            <td width="50%">
                <table width="50%">
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo TEXT_REMOVE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_checkbox_field('remove_image', '1', $cInfo->remove_image) . $cInfo->image_with_path; ?></td>
                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_SELECT_IMAGE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_file_field('inventory_image'); ?>
                            <?php
                            if ($cInfo->image_with_path) { // show image if it is defined
                                echo html_image(DIR_WS_MY_FILES . $_SESSION['company'] . '/inventory/images/' . $cInfo->image_with_path, $cInfo->image_with_path, '', '100', 'onclick="showImage();"', 'margin-left:45px;float:right;position:absolute;margin-top:-20px;');
                            } else
                                echo '&nbsp;';
                            ?>
                        </td>

                    </tr>
                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_INVENTORY_SERIALIZE; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_checkbox_field('serialize', '1', $cInfo->serialize, '', 'disabled="disabled"'); ?></td>
                    </tr>

                    <tr>
                        <td width="40%"><span class="form_label" ><?php echo INV_ENTRY_IMAGE_PATH; ?> </span></td>
                        <td width="4%">:</td>
                        <td width="56%">
                            <?php
                            echo html_hidden_field('image_with_path', $cInfo->image_with_path);
                            echo html_input_field('inventory_path', substr($cInfo->image_with_path, 0, strrpos($cInfo->image_with_path, '/')));
                            ?>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h5><?php echo 'POS'; ?></h5>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr><td width="40%"><span class="form_label" >Allow upload to POS Catalog</span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_checkbox_field('catalog', '1', $cInfo->catalog, false, 'style="width:10px;"'); ?></td>
                    </tr>
                    <tr><td width="40%"><span class="form_label" >POS - Manufacturer Name.</span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('manufacturer', $cInfo->manufacturer, 'style="text-align:left"', false); ?></td>
                    </tr>
                    <tr><td width="40%"><span class="form_label" ><?php echo 'POS - Category Name.'; ?></span></td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_pull_down_menu('category_id', $product_category, $cInfo->category_id); ?></td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
        </tr>

    </table>
    <table class="ui-widget" style="border-style:none;width:100%">



        <tr><td>



                <?php if (ENABLE_MULTI_BRANCH || $cInfo->inventory_type == 'sr') { ?>
                </td>
                <td valign="top">
                    <?php if (ENABLE_MULTI_BRANCH) { ?>
                        <table class="ui-widget" style="border-collapse:collapse;width:100%">
                            <thead class="ui-widget-header">
                                <tr>
                                    <th><?php echo GEN_STORE_ID; ?></th>
                                    <th><?php echo INV_HEADING_QTY_IN_STOCK; ?></th>
                                </tr>
                            </thead>
                            <tbody class="ui-widget-content">
                                <?php
                                foreach ($store_stock as $stock) {
                                    echo '<tr>' . chr(10);
                                    echo '  <td>' . $stock['store'] . '</td>' . chr(10);
                                    if (is_int($stock['qty']) == false)
                                        echo '  <td align="center">' . $currencies->format($stock['qty']) . '</td>' . chr(10);
                                    else
                                        echo '  <td align="center">' . $stock['qty'] . '</td>' . chr(10);
                                    echo '</tr>' . chr(10);
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                    }
                    if ($cInfo->inventory_type == 'sr') {
                        ?>
                        <table class="ui-widget" style="border-collapse:collapse;width:100%">
                            <thead class="ui-widget-header">
                                <tr>
                                    <th><?php echo GEN_STORE_ID; ?></th>
                                    <th><?php echo TEXT_QUANTITY; ?></th>
                                    <th><?php echo INV_HEADING_SERIAL_NUMBER; ?></th>
                                </tr>
                            </thead>
                            <tbody class="ui-widget-content">
                                <?php
                                foreach ($serial_list as $value) {
                                    echo '<tr>' . chr(10);
                                    echo '  <td>' . $value['store'] . '</td>' . chr(10);
                                    if (is_int($value['qty']) == false)
                                        echo '  <td>' . $currencies->format($value['qty']) . '</td>' . chr(10);
                                    else
                                        echo '  <td>' . $value['qty'] . '</td>' . chr(10);
                                    echo '  <td align="center">' . $value['serial'] . '</td>' . chr(10);
                                    echo '</tr>' . chr(10);
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php } ?>
                <?php } ?>
            </td></tr>
        </tbody>
    </table>
</div>
