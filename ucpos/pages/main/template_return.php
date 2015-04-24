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
//  Path: /modules/ucpos/pages/main/template_return.php
//
 
// start the form
echo html_form('pos', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
$hidden_fields = NULL;

// include hidden fields
echo html_hidden_field('todo',               '') . chr(10);
echo html_hidden_field('id',                 $order->id) . chr(10); // db journal entry id, null = new entry; not null = edit
echo html_hidden_field('bill_acct_id',       $order->bill_acct_id) . chr(10);	// id of the account in the bill to/remit to
echo html_hidden_field('bill_address_id',    $order->bill_address_id) . chr(10);
echo html_hidden_field('currencies_code',    $order->currencies_code) . chr(10);
echo html_hidden_field('printed',            $order->printed) . chr(10);
echo html_hidden_field('purchase_invoice_id',$order->purchase_invoice_id) . chr(10);
echo html_hidden_field('post_date',          $order->post_date) . chr(10);
echo html_hidden_field('rep_id',             $order->rep_id) . chr(10);
echo html_hidden_field('gl_acct_id',         $order->gl_acct_id) . chr(10);
if (!ENABLE_MULTI_BRANCH)   echo html_hidden_field('store_id', $order->store_id) . chr(10);
if (!ENABLE_MULTI_CURRENCY) echo html_hidden_field('display_currency', DEFAULT_CURRENCY) . chr(10);
if (!ENABLE_MULTI_CURRENCY) echo html_hidden_field('currencies_value', '1') . chr(10);

// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '&amp;module=ucpos&amp;page=main', 'SSL') . '\'"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['save']['show']     = false;
$toolbar->icon_list['print']['show']    = false;
// pull in extra toolbar overrides and additions
if (count($extra_toolbar_buttons) > 0) {
  foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
}
echo $toolbar->build_toolbar();

// Build the page
?>
<h1><?php echo PAGE_TITLE; ?></h1>
<table style="background-color:#EEEEEE; width: 100%">
    <tr>
        <td width="33%">
            <br/><br/>
            <?php if (ENABLE_MULTI_BRANCH) { // show currency slection pulldown ?>
                <span class="form_label" ><?php echo TEXT_BRANCH ?> :</span><br/>
                <?php echo html_pull_down_menu('store_id', gen_get_store_ids(), $order->store_id ? $order->store_id : $_SESSION['admin_prefs']['def_store_id']); ?>                    
                
                <br/><br/>
            <?php } ?>
            <span class="form_label" ><?php echo TEXT_SKU ?> :</span><br/>
            <?php
            
            echo html_input_field('sku', '', ' size="' . (MAX_INVENTORY_SKU_LENGTH + 1) . '" maxlength="' . MAX_INVENTORY_SKU_LENGTH . '" onfocus="clearField(\'sku\', \'' . TEXT_SEARCH . '\')" onblur="setField(\'sku\', \'' . TEXT_SEARCH . '\'); loadSkuDetails(0, 0)"') . chr(10);            
            echo html_icon('actions/system-search.png', TEXT_SEARCH, 'small', 'id="sku_open" align="top" style="cursor:pointer" onclick="InventoryList(0)"') . chr(10);
            ?>
            <br/><br/>
        </td> 

        <td width="33%">
            <br/><br/>
            <?php if (ENABLE_MULTI_CURRENCY) { // show currency slection pulldown  ?>
                <span class="form_label" ><?php echo TEXT_CURRENCY ?> :</span><br/>
                <?php echo html_pull_down_menu('display_currency', gen_get_pull_down(TABLE_CURRENCIES, false, false, 'code', 'title'), $order->currencies_code, 'onchange="recalculateCurrencies();"');  ?>
                <br/><br/>         
                <span class="form_label" ><?php echo TEXT_EXCHANGE_RATE ?> :</span><br/>
                <?php echo html_input_field('currencies_value', $order->currencies_value, 'readonly="readonly"'); ?>
                <br/><br/>
            <?php } ?>
            <span class="form_label" ><?php echo ORD_ACCT_ID ?> :</span><br/>
            <?php
            
            echo html_input_field('search', isset($order->short_name) ? $order->short_name : TEXT_SEARCH, 'size="21" maxlength="20" onfocus="clearField(\'search\', \'' . TEXT_SEARCH . '\')" onblur="setField(\'search\', \'' . TEXT_SEARCH . '\');"');
            echo '&nbsp;' . html_icon('actions/system-search.png', TEXT_SEARCH, 'small', 'align="top" style="cursor:pointer" onclick="accountGuess(true)"');
            ?>
        </td> 
        <td width="33%">

        </td>
    </tr>
    <tr>
        <td width="60%">
            <table id="item_table">
                <tr>
                    <th class="dataTableHeadingContent"><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small'); ?></th>
                    <th class="dataTableHeadingContent"><?php echo TEXT_QUANTITY; ?></th>
                    <th class="dataTableHeadingContent"><?php echo TEXT_SKU; ?></th>
                    <th class="dataTableHeadingContent"><?php echo TEXT_DESCRIPTION; ?></th>
                    <th class="dataTableHeadingContent"><?php echo TEXT_UNIT_PRICE; ?></th>
                    <th class="dataTableHeadingContent"><?php echo TEXT_AMOUNT; ?></th>
                </tr>
                <?php
                    if ($order->item_rows) {
                    for ($j = 0, $i = 1; $j < count($order->item_rows); $j++, $i++) {
                            echo '<tr>' . chr(10);
                            // turn off delete icon if required
                            echo '  <td align="center">' . html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick="if (confirm(\'' . TEXT_DELETE_ENTRY . '\')) removeInvRow(' . $i . ');"') . '</td>' . chr(10);
                            echo '  <td nowrap="nowrap" align="center">';
                            echo html_input_field('pstd_' . $i, $order->item_rows[$j]['pstd'], ' size="7" maxlength="6" onchange="updateRowTotal(' . $i . ', true)" style="text-align:right"');
                            // for serialized items, show the icon
                            echo html_icon('actions/tab-new.png', TEXT_SERIAL_NUMBER, 'small', 'align="top" style="cursor:pointer" onclick="serialList(\'serial_' . $i . '\')"');
                            echo '</td>' . chr(10);
                            echo '  <td nowrap="nowrap" align="center">';
                            echo html_input_field('sku_' . $i, $order->item_rows[$j]['sku'], ' size="' . (MAX_INVENTORY_SKU_LENGTH + 1) . '" maxlength="' . MAX_INVENTORY_SKU_LENGTH . '"readonly="readonly" ') . chr(10);
                            echo '</td>' . chr(10);
                            echo '  <td>' . html_input_field('desc_' . $i, $order->item_rows[$j]['desc'], 'readonly="readonly" size="50" maxlength="255"') . '</td>' . chr(10);
                            echo '  <td nowrap="nowrap" align="center">';
                            echo html_input_field('price_' . $i, $currencies->precise($order->item_rows[$j]['price'], true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="8" maxlength="20" style="text-align:right"') . chr(10);
                            echo '</td>' . chr(10);
                            echo '  <td align="center">' . chr(10);
                            // Hidden fields
                            echo html_hidden_field('id_'     	. $i, $order->item_rows[$j]['id'])    	 	. chr(10);
                            echo html_hidden_field('stock_' 	. $i, $order->item_rows[$j]['stock']) 		. chr(10);
                            echo html_hidden_field('inactive_' 	. $i, $order->item_rows[$j]['inactive'])	. chr(10);
                            echo html_hidden_field('serial_' 	. $i, $order->item_rows[$j]['serial']) 		. chr(10);
                            echo html_hidden_field('full_'   	. $i, $order->item_rows[$j]['full']) 		. chr(10);
                            echo html_hidden_field('disc_'   	. $i, $order->item_rows[$j]['disc']) 		. chr(10);
                            echo html_hidden_field('acct_'   	. $i, $order->item_rows[$j]['acct']) 		. chr(10);
                            echo html_hidden_field('tax_'   	. $i, $order->item_rows[$j]['tax']) 		. chr(10);
                            // End hidden fields
                            echo html_input_field('total_' . $i, $currencies->format($order->item_rows[$j]['total'], true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="11" maxlength="20" style="text-align:right"') . chr(10);
                            echo '  </td>' . chr(10);
                            echo '</tr>' . chr(10);
                    }
                    } else {
                            $hidden_fields .= '<script type="text/javascript">addInvRow();</script>' . chr(10);
                    } ?>
            </table>
        </td>
    </tr>
    <tr>
        <td width="33%">
            <?php echo html_pull_down_menu('bill_to_select', gen_null_pull_down(), '', 'onchange="fillAddress(\'bill\')"'); ?>
            <br/>
            <span class="form_label" ><?php echo GEN_PRIMARY_NAME; ?> :</span><br/>
            <?php echo html_input_field('bill_primary_name',$order->bill_primary_name, 'size="33" maxlength="32" onfocus="clearField(\'bill_primary_name\', \'' . GEN_PRIMARY_NAME . '\')" onblur="setField(\'bill_primary_name\', \'' . GEN_PRIMARY_NAME . '\')"', true) . chr(10); ?>
            <br/><br/>
            <span class="form_label" ><?php echo TEXT_ADD_UPDATE ?> :</span>
            <?php echo html_checkbox_field('bill_add_update', '1', ($order->bill_add_update) ? true : false, '', '') . TEXT_ADD_UPDATE . '<br />' . chr(10); ?>
            <br/>
            <span class="form_label" ><?php echo GEN_CONTACT; ?> :</span><br/>
            <?php echo html_input_field('bill_contact',     $order->bill_contact, 'size="33" maxlength="32" onfocus="clearField(\'bill_contact\', \'' . GEN_CONTACT . '\')" onblur="setField(\'bill_contact\', \'' . GEN_CONTACT . '\')"', ADDRESS_BOOK_CONTACT_REQUIRED) . '<br />' . chr(10); ?>
            <br/>
            <span class="form_label" ><?php echo GEN_ADDRESS1; ?> :</span><br/>
            <?php echo html_input_field('bill_address1',    $order->bill_address1, 'size="33" maxlength="32" onfocus="clearField(\'bill_address1\', \'' . GEN_ADDRESS1 . '\')" onblur="setField(\'bill_address1\', \'' . GEN_ADDRESS1 . '\')"', ADDRESS_BOOK_ADDRESS1_REQUIRED) . '<br />' . chr(10); ?>
            <br/>
            <span class="form_label" ><?php echo GEN_ADDRESS2; ?> :</span><br/>
            <?php echo html_input_field('bill_address2',    $order->bill_address2, 'size="33" maxlength="32" onfocus="clearField(\'bill_address2\', \'' . GEN_ADDRESS2 . '\')" onblur="setField(\'bill_address2\', \'' . GEN_ADDRESS2 . '\')"', ADDRESS_BOOK_ADDRESS2_REQUIRED) . '<br />' . chr(10); ?>
            <br/>
            <span class="form_label" ><?php echo GEN_CITY_TOWN; ?> :</span><br/>
            <?php echo html_input_field('bill_city_town',   $order->bill_city_town, 'size="25" maxlength="24" onfocus="clearField(\'bill_city_town\', \'' . GEN_CITY_TOWN . '\')" onblur="setField(\'bill_city_town\', \'' . GEN_CITY_TOWN . '\')"', ADDRESS_BOOK_CITY_TOWN_REQUIRED) . chr(10); ?>
            <br/><br/>
            <span class="form_label" ><?php echo GEN_STATE_PROVINCE; ?> :</span><br/>
            <?php echo html_input_field('bill_state_province', $order->bill_state_province, 'size="3" maxlength="5" onfocus="clearField(\'bill_state_province\', \'' . GEN_STATE_PROVINCE . '\')" onblur="setField(\'bill_state_province\', \'' . GEN_STATE_PROVINCE . '\')"', ADDRESS_BOOK_STATE_PROVINCE_REQUIRED) . chr(10); ?>
            <br/><br/>
            <span class="form_label" ><?php echo GEN_POSTAL_CODE; ?> :</span><br/>
            <?php echo html_input_field('bill_postal_code', $order->bill_postal_code, 'size="11" maxlength="10" onfocus="clearField(\'bill_postal_code\', \'' . GEN_POSTAL_CODE . '\')" onblur="setField(\'bill_postal_code\', \'' . GEN_POSTAL_CODE . '\')"', ADDRESS_BOOK_POSTAL_CODE_REQUIRED) . '<br />' . chr(10); ?>
            <br/>
            <span class="form_label" >Country :</span><br/>
            <?php echo html_pull_down_menu('bill_country_code', gen_get_countries(), $order->bill_country_code ? $order->bill_country_code : COMPANY_COUNTRY) . '<br />' . chr(10);  ?>
            <br/>
            <span class="form_label" ><?php echo GEN_TELEPHONE1; ?> :</span><br/>
            <?php echo html_input_field('bill_telephone1',  $order->bill_telephone1, 'size="21" maxlength="20" onfocus="clearField(\'bill_telephone1\', \'' . GEN_TELEPHONE1 . '\')" onblur="setField(\'bill_telephone1\', \'' . GEN_TELEPHONE1 . '\')"', ADDRESS_BOOK_TELEPHONE1_REQUIRED) . chr(10); ?>
            <br/><br/>
            <span class="form_label" ><?php echo GEN_EMAIL ?> :</span><br/>
            <?php echo html_input_field('bill_email',       $order->bill_email, 'size="35" maxlength="48" onfocus="clearField(\'bill_email\', \'' . GEN_EMAIL . '\')" onblur="setField(\'bill_email\', \'' . GEN_EMAIL . '\')"', ADDRESS_BOOK_EMAIL_REQUIRED) . chr(10); ?>

        </td>
        <td width="33%">
            
            <br/><br/>
            <span class="form_label" ><?php echo TEXT_SUBTOTAL ?> :</span><br/>
            <?php echo html_input_field('subtotal', $currencies->format($order->subtotal, true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="15" maxlength="20" style="text-align:right"');  ?>
            <br/><br/>
            <?php
            if (ENABLE_ORDER_DISCOUNT) {
                $hidden_fields .= html_hidden_field('disc_gl_acct_id', '') . chr(10);
                ?>
                <span class="form_label" ><?php echo TEXT_DISCOUNT_PERCENT ?></span><br/>
                <?php echo html_input_field('disc_percent', ($order->disc_percent ? number_format(100*$order->disc_percent,3) : '0'), 'size="7" maxlength="6" onchange="calculateDiscountPercent()" style="text-align:right"') . ' '; ?>
                <br/><br/>
                <span class="form_label" ><?php echo TEXT_AMOUNT ?></span><br/>
                <?php echo html_input_field('discount', $currencies->format(($order->discount ? $order->discount : '0'), true, $order->currencies_code, $order->currencies_value), 'size="15" maxlength="20" onchange="calculateDiscount()" style="text-align:right"'); ?>
                <br/><br/>
                <?php
            } else {
                $hidden_fields .= html_hidden_field('disc_gl_acct_id', '') . chr(10);
                $hidden_fields .= html_hidden_field('discount', '0') . chr(10);
                $hidden_fields .= html_hidden_field('disc_percent', '0') . chr(10);
            }
            ?>
           <span class="form_label" ><?php echo ORD_SALES_TAX  ?> :</span><br/>
           <?php echo  html_input_field('sales_tax', $currencies->format(($order->sales_tax ? $order->sales_tax : '0.00'), true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="15" maxlength="20" onchange="updateTotalPrices()" style="text-align:right"'); ?>
           <br/><br/>
           <span class="form_label" ><?php echo TEXT_TOTAL  ?> :</span><br/>
           <?php echo html_input_field('total', $currencies->format($order->total_amount, true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="15" maxlength="20" style="text-align:right"'); ?>
           <br/><br/>
           <span class="form_label" ><?php echo TEXT_AMOUNT_PAID   ?> :</span><br/>
           <?php echo  html_input_field('pmt_recvd', $currencies->format($order->pmt_recvd), 'readonly="readonly" size="15" maxlength="20" style="text-align:right"'); ?>
           <br/><br/>
           <span class="form_label" ><?php echo TEXT_BALANCE_DUE   ?> :</span><br/>
           <?php echo html_input_field('bal_due', $currencies->format($order->bal_due), 'readonly="readonly" size="15" maxlength="20" style="text-align:right"'); ?>
           <br/><br/>
           <?php echo html_button_field('payment', TEXT_REFUND, 'onclick="centerPopup(), popupPayment()"'); ?>
           <br/><br/>
           <table id="pmt_table">
                <thead>
                    <tr>
                            <th class="dataTableHeadingContent"><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small'); ?></th>
                            <th class="dataTableHeadingContent"><?php echo TEXT_REFUND_METHOD; ?></th>
                            <th class="dataTableHeadingContent"><?php echo TEXT_AMOUNT; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($order->pmt_rows) {
                    for ($j = 0, $i = 1; $j < count($order->pmt_rows); $j++, $i++) {
                            echo '<tr>' . chr(10);
                            // turn off delete icon if required
                            echo '  <td align="center">' . html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick="if (confirm(\'' . TEXT_DELETE_ENTRY . '\')) removePmtRow(' . $i . ');"') . '</td>' . chr(10);
                            echo '  <td>' . html_input_field('pdes_' . $i, constant('MODULE_PAYMENT_' . strtoupper($order->pmt_rows[$j]['meth']) . '_TEXT_TITLE'), 'readonly="readonly" size="50" maxlength="255"') . '</td>' . chr(10);
                            echo '  <td align="center">' . chr(10);
                            // Hidden fields
                            echo html_hidden_field('meth_'. $i, $order->pmt_rows[$j]['meth']) . chr(10);
                            echo html_hidden_field('f0_'  . $i, $order->pmt_rows[$j]['f0']) . chr(10);
                            echo html_hidden_field('f1_'  . $i, $order->pmt_rows[$j]['f1']) . chr(10);
                            echo html_hidden_field('f2_'  . $i, $order->pmt_rows[$j]['f2']) . chr(10);
                            echo html_hidden_field('f3_'  . $i, $order->pmt_rows[$j]['f3']) . chr(10);
                            echo html_hidden_field('f4_'  . $i, $order->pmt_rows[$j]['f4']) . chr(10);
                            // End hidden fields
                            echo html_input_field('pmt_' . $i, $currencies->format($order->pmt_rows[$j]['pmt'], true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="11" maxlength="20" style="text-align:right"') . chr(10);
                            echo '  </td>' . chr(10);
                            echo '</tr>' . chr(10);
                    }
                    } else {
                    $hidden_fields .= '<script type="text/javascript">addPmtRow();</script>' . chr(10);
                    } ?>
                </tbody>
            </table>
        </td>
        <td width="33%">
            
        </td>
    </tr>
    
  
  <tr>
    <td><?php echo "<b><u>" . TEXT_NOTES . "</u></b><br>" . UCPOS_ITEM_NOTES; ?></td>
    <td><?php echo '&nbsp;'; ?></td>
  </tr>
</table>
<?php // display the hidden fields that are not used in this rendition of the form
echo $hidden_fields;
?>
  <applet name="jZebra" code="jzebra.RawPrintApplet.class" archive="<?php echo DIR_WS_ADMIN . 'modules/ucounting/includes/jzebra/jzebra.jar'; ?>" width="16" height="16">
    <param name="printer" value="<?php echo UCPOS_RECEIPT_PRINTER_NAME; ?>">
  </applet>

<div id="popupPayment">
<?php 
$SeccondToolbar      = new toolbar;
$SeccondToolbar->icon_list['cancel']['params'] = 'onclick="disablePopup()"';
$SeccondToolbar->icon_list['open']['show']     = false;
$SeccondToolbar->icon_list['save']['params']   = 'onclick="SavePayment(\'save\')"';
$SeccondToolbar->icon_list['save']['show']     = true; 
$SeccondToolbar->icon_list['delete']['show']   = false;
$SeccondToolbar->icon_list['print']['params']  = 'onclick="SavePayment(\'print\')"';
$SeccondToolbar->icon_list['print']['show']    = true; 
// pull in extra toolbar overrides and additions
if (count($extra_SeccondToolbar_buttons) > 0) {
	foreach ($extra_SeccondToolbar_buttons as $key => $value) $SeccondToolbar->icon_list[$key] = $value;
}
// add the help file index and build the toolbar
echo $SeccondToolbar->build_toolbar(); 
 // Build the page
?>
<h1><?php echo PAYMENT_TITLE; ?></h1>
  <table width="300" align="center">
    <tr>
	  <td colspan="2">
<?php
	echo '    <fieldset>';
    echo '    <legend>'. TEXT_PAYMENT_METHOD . '</legend>';
	echo '    <div style="position: relative; height: 150px;">';
	echo html_pull_down_menu('payment_method', $payment_modules, $order->shipper_code, 'onchange="activateFields()"') . chr(10);
	$count = 0;
	foreach ($payment_modules as $value) {
	  echo '      <div id="pm_' . $count . '" style="visibility:hidden; position:absolute; top:22px; left:1px">' . chr(10);
	  $pmt_class = $value['id'];
	  $disp_fields = $$pmt_class->selection();
	  for ($i=0; $i<count($disp_fields['fields']); $i++) {
		echo $disp_fields['fields'][$i]['title'] . '<br />' . chr(10);
		echo $disp_fields['fields'][$i]['field'] . '<br />' . chr(10);
	  }
	  echo '      </div>' . chr(10);
	  $count++;
	}
	echo '    </div>';
	echo '</fieldset>';
?>
	  </td>
	</tr>
	<tr>
	  <td><?php echo TEXT_AMOUNT . ' ' . html_input_field('amount', $currencies->format($amount), 'size="15" maxlength="20" style="text-align:right"'); ?></td>
	  <td><?php echo html_icon('devices/media-floppy.png',		 TEXT_SAVE,  'large', 'onclick="SavePayment(\'save_return\')"' , 0, 0, 'btn_save'); ?></td>
	  <td><?php echo html_icon('ucbooks/pdficon_large.gif', TEXT_PRINT, 'large', 'onclick="SavePayment(\'print_return\')"', 0, 0, 'btn_save'); ?></td>
	</tr>
    <tr><td colspan="2"><b><?php echo TEXT_NOTES; ?></b></td></tr>
    <tr><td colspan="2"><?php echo UCPOS_PAYMENT_NOTES; ?></td></tr>
  </table>

</div>
<div id="backgroundPopup"></div>
</form>
