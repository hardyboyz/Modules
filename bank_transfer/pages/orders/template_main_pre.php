<script type="text/javascript">
    function printThis(oID){
        //showLoading();
        $('#print').attr('src', '<?php echo 'index.php?module=ucform&page=popup_gen&gID=' . POPUP_FORM_TYPE . '&date=a&xfld=journal_main.id&xcr=EQUAL&xmin=' . $_GET['oID'] ?>');
        //hideLoading();
    }
    
    function email_invoice(){
        
        window.open('<?php echo 'index.php?module=ucform&page=popup_gen&gID=' . POPUP_FORM_TYPE . '&date=a&xfld=journal_main.id&xcr=EQUAL&xmin=' . $_GET['oID'] . '&emailsend=email&jID='.$_GET['jID'] ?>','email','height=600,width=1100,left=100');
    }
</script>
<style type="text/javascript">
    #tb_icon_save{width: 100px !important; text-align: center !important;}
</style>

<?php if (isset($_GET['action']) != 'edit') { ?>
    <style type="text/css">   
        #tb_icon_print{display: none;}
        #tb_icon_email{display: none;}
    </style>
<?php }
    if($_GET['jID'] == 6){
?>
    <style type="text/css">
        #tb_icon_email{display: none;}
    </style>
    
<?php } ?>
    
    
    
    
<?php
// +-----------------------------------------------------------------+
// |                   bank_transfer Open Source ERP                    |
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
//  Path: /modules/bank_transfer/pages/orders/template_main.php
//
echo html_form('orders', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"', true) . chr(10);
$hidden_fields = NULL;
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('id', $order->id) . chr(10); // db journal entry id, null = new entry; not null = edit
echo html_hidden_field('recur_id', $order->recur_id ? $order->recur_id : 0) . chr(10); // recur entry flag - number of recurs
echo html_hidden_field('recur_frequency', $order->recur_frequency ? $order->recur_frequency : 0) . chr(10); // recur entry flag - how often
echo html_hidden_field('so_po_ref_id', $order->so_po_ref_id) . chr(10); // PO/SO number assigned to purchase/sales-invoice (if applicable)
echo html_hidden_field('bill_acct_id', $order->bill_acct_id) . chr(10); // id of the account in the bill to/remit to
echo html_hidden_field('bill_address_id', $order->bill_address_id) . chr(10);
echo html_hidden_field('ship_acct_id', $order->ship_acct_id) . chr(10); // id of the account in the ship to
echo html_hidden_field('ship_address_id', $order->ship_address_id) . chr(10);
echo html_hidden_field('terms', $order->terms) . chr(10);
echo html_hidden_field('item_count', $order->item_count) . chr(10);
echo html_hidden_field('weight', $order->weight) . chr(10);
echo html_hidden_field('currencies_code', $order->currencies_code) . chr(10);
echo html_hidden_field('printed', $order->printed);
echo html_hidden_field('override_user', '');
echo html_hidden_field('override_pass', '');
if (!isset($template_options['closed']))
    echo html_hidden_field('closed', $order->closed);
if (!isset($template_options['waiting']))
    echo html_hidden_field('waiting', $order->waiting);
if (!isset($template_options['terminal_date']))
    echo html_hidden_field('terminal_date', $order->terminal_date ? gen_locale_date($order->terminal_date) : '');
if (!isset($template_options['terms']))
    echo html_hidden_field('terms_text', $order->terms_text); // placeholder when not used
if (!ENABLE_MULTI_CURRENCY) {
    echo html_hidden_field('display_currency', DEFAULT_CURRENCY) . chr(10);
    echo html_hidden_field('currencies_value', '1') . chr(10);
}
if (!ENABLE_MULTI_BRANCH)
    echo html_hidden_field('store_id', '0') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['params'] = 'onclick="OpenOrdrList(this)"';
$toolbar->icon_list['delete']['params'] = 'onclick="if (confirm(\'' . ORD_DELETE_ALERT . '\')) submitToDo(\'delete\')"';
$toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
$toolbar->icon_list['save']['order'] = 1;
$toolbar->icon_list['open']['show'] = false;

$toolbar->add_icon('email', 'onclick=email_invoice()');
//$toolbar->icon_list['print']['params'] = 'onclick="submitToDo(\'print\')"';
//$toolbar->icon_list['print']['params'] = 'onclick="printOrder(\''.$_GET['oID'].'\')"';
if($_GET['jID'] == 12 ){
    $toolbar->icon_list['print']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=do', 'SSL') . '\',\'_blank\')"';
    //$toolbar->icon_list['print_invoice']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=do', 'SSL') . '\',\'_blank\')"';
    $toolbar->icon_list['print']['text'] = "Generate DO PDF";
    $toolbar->add_icon('print_invoice', 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=inv', 'SSL') . '\',\'_blank\')"', 3);
    $toolbar->icon_list['print_invoice']['icon'] = 'devices/network-wired.png';
    $toolbar->icon_list['print_invoice']['text'] = "Generate INV";
} else if($_GET['jID'] == 9 ){
   $toolbar->icon_list['print']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=qpdf', 'SSL') . '\',\'_blank\')"';
   $toolbar->icon_list['print']['text'] = "Generate Quotations PDF";
}else if($_GET['jID'] == 10){
    $toolbar->icon_list['print']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=so', 'SSL') . '\',\'_blank\')"';
   $toolbar->icon_list['print']['text'] = "Generate SO PDF";
}else if($_GET['jID'] == 13){
    $toolbar->icon_list['print']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=cm', 'SSL') . '\',\'_blank\')"';
   $toolbar->icon_list['print']['text'] = "Generate CM PDF";
}else if($_GET['jID'] == 6){
    $toolbar->icon_list['print']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=pur', 'SSL') . '\',\'_blank\')"';
   $toolbar->icon_list['print']['text'] = "Generate Purchase PDF";
}else if($_GET['jID'] == 4){
    $toolbar->icon_list['print']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=puro', 'SSL') . '\',\'_blank\')"';
   $toolbar->icon_list['print']['text'] = "Generate Purchase Order PDF";
}else if($_GET['jID'] == 3){
    $toolbar->icon_list['print']['params'] = 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=print_invoice&amp;oID=' . $_GET['oID'] . '&amp;jID='.$_GET['jID'].'&amp;action=pdf&amp;pdf_type=rqpuro', 'SSL') . '\',\'_blank\')"';
   $toolbar->icon_list['print']['text'] = "Generate Request for Quotes PDF";
}else{
     $toolbar->icon_list['print']['params'] = 'onclick="printThis(\'' . $_GET['oID'] . '\')"';
     $toolbar->icon_list['print']['text'] = "Generate PDF";
}


$toolbar->icon_list['print']['order'] = 2;

$toolbar->icon_list['delete']['order'] = 5;
$toolbar->icon_list['cancel']['order'] = 6;


//$toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action', 'jID')) . 'jID=' . JOURNAL_ID, 'SSL') . '\'"', 2);
if (isset($_GET['oID']) && $_GET['jID'] == 10) {
    $toolbar->add_icon('invoice', 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;oID=' . $_GET['oID'] . '&amp;jID=12&amp;action=prc_so', 'SSL') . '\',\'_blank\')"');
    //echo html_button_field('invoice_' . $oID, TEXT_RECEIVE, 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;oID=' . $oID . '&amp;jID=6&amp;action=prc_so', 'SSL') . '\',\'_blank\')"');
}
if (isset($_GET['oID']) && $_GET['jID'] == 4) {
    $toolbar->add_icon('receive', 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;oID=' . $_GET['oID'] . '&amp;jID=6&amp;action=prc_so', 'SSL') . '\',\'_blank\')"');
    //echo html_button_field('invoice_' . $oID, TEXT_RECEIVE, 'onclick="window.open(\'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;oID=' . $oID . '&amp;jID=6&amp;action=prc_so', 'SSL') . '\',\'_blank\')"');
}
if ($security_level > 1 && ENABLE_BAR_CODE_READERS) {
    $toolbar->add_icon('bar_code', 'onclick="openBarCode()"', 9);
    $toolbar->icon_list['bar_code']['icon'] = 'devices/network-wired.png';
    $toolbar->icon_list['bar_code']['text'] = TEXT_UPC_CODE;
}
if (JOURNAL_ID == 12 && $security_level > 2) {
    $toolbar->add_icon('post_previous', 'onclick="submitToDo(\'post_previous\')"', 10);
    $toolbar->icon_list['post_previous']['show'] = false;
    $toolbar->icon_list['post_previous']['icon'] = 'actions/go-previous.png';
    $toolbar->icon_list['post_previous']['text'] = TEXT_SAVE_OPEN_PREVIOUS;
    $toolbar->add_icon('post_next', 'onclick="submitToDo(\'post_next\')"', 11);
    $toolbar->icon_list['post_next']['show'] = false;
    $toolbar->icon_list['post_next']['icon'] = 'actions/go-next.png';
    $toolbar->icon_list['post_next']['text'] = TEXT_SAVE_OPEN_NEXT;
}
if ($security_level > 1 && in_array(JOURNAL_ID, array(4, 6, 10, 12))) {
    $toolbar->add_icon('recur', 'onclick="OpenRecurList(this)"', 12);
    $toolbar->icon_list['recur']['show'] = false;
}
if ($security_level > 1 && in_array(JOURNAL_ID, array(3, 9))) {
    $toolbar->add_icon('cvt_quote', 'onclick="convertQuote()"', 3);
    $toolbar->icon_list['cvt_quote']['icon'] = 'emblems/emblem-symbolic-link.png';
    $toolbar->icon_list['cvt_quote']['text'] = JOURNAL_ID == 3 ? ORD_CONVERT_TO_RFQ_PO : ORD_CONVERT_TO_SO_INV;
}
if ($security_level > 1 && JOURNAL_ID == 10 && $_SESSION['admin_security'][SECURITY_ID_PURCHASE_ORDER] > 1) {
    $toolbar->add_icon('cvt_quote', 'onclick="convertSO()"', 13);
    $toolbar->icon_list['cvt_quote']['show'] = false;
    $toolbar->icon_list['cvt_quote']['icon'] = 'emblems/emblem-symbolic-link.png';
    $toolbar->icon_list['cvt_quote']['text'] = ORD_CONVERT_TO_PO;
}
if ($security_level > 1 && (JOURNAL_ID == 6 || JOURNAL_ID == 12)) {
    $toolbar->add_icon('payment', 'onclick="submitToDo(\'payment\')"', 15); // POS and POP (Purchase)
    $toolbar->icon_list['payment']['text'] = "Make Payment";
    $toolbar->icon_list['payment']['order'] = 4;

    $toolbar->add_icon('ship_all', 'onclick="checkShipAll()"', 20);
    $toolbar->icon_list['ship_all']['show'] = false;
    if (JOURNAL_ID == 6)
        $toolbar->icon_list['ship_all']['text'] = TEXT_RECEIVE_ALL;
}
if ($security_level < 4)
    $toolbar->icon_list['delete']['show'] = false;
if ($security_level < 2 ||  JOURNAL_ID == 7)
    $toolbar->icon_list['print']['show'] = false;
if ($security_level < 2) {
    $toolbar->icon_list['save']['show'] = false;
    $toolbar->icon_list['new']['show'] = false;
}
if ($security_level < 3 && $order->id) {
    $toolbar->icon_list['save']['show'] = false;
    $toolbar->icon_list['print']['show'] = false;
}
if ($security_level > 1 && JOURNAL_ID == 4) {
    $toolbar->add_icon('import', 'onclick=PreProcessLowStock()');
    $toolbar->icon_list['import']['text'] = LOW_STOCK_BUTTON;
}
// pull in extra toolbar overrides and additions
if (count($extra_toolbar_buttons) > 0) {
    foreach ($extra_toolbar_buttons as $key => $value)
        $toolbar->icon_list[$key] = $value;
}

// add the help file index and build the toolbar
switch (JOURNAL_ID) {
    case 3: $toolbar->add_help('07.02.04');
        break;
    case 4: $toolbar->add_help('07.02.03');
        break;
    case 6: $toolbar->add_help('07.02.05');
        break;
    case 7: $toolbar->add_help('07.02.07');
        break;
    case 9: $toolbar->add_help('07.03.04');
        break;
    case 10: $toolbar->add_help('07.03.03');
        break;
    case 12: $toolbar->add_help('07.03.05');
        break;
    case 13: $toolbar->add_help('07.03.07');
        break;
    case 18: $toolbar->add_help('07.03.06');
        break;
    case 19: $toolbar->add_help('07.03.06');
        break;
    case 20: $toolbar->add_help('07.02.06');
        break;
    case 21: $toolbar->add_help('07.02.06');
        break;
}

// Build the page
?>
<div class="bottom_btn" id="bottom_btn">
    <?php
    echo $toolbar->build_toolbar();
    ?> 
</div>
    <br/><br/>
<style type="text/css">
    .input_width input {width: 152px !important;}
    #waiting{width: 20px !important}
    .td_padding td {padding: 0}
    .country_menu select{width: 70%}
    .select_width select{width: 80%}
    .gl{width:100px}
    .address_table td{height: 20px;}
    .address_table{margin: 0 0 15px 0;}
    .address_heading{margin: 15px 0 0 0;}
    #waiting{display: none;}
</style>
<script>
    
</script>
<div id="override_order" title="<?php echo TEXT_CREDIT_LIMIT_TITLE; ?>">
    <p><?php echo TEXT_CREDIT_LIMIT_DESC; ?></p>
    <p>
        <?php echo TEXT_ADMIN_USER . '&nbsp;' . html_input_field('override_user', '', 'onblur="document.getElementById(\'override_user\').value = this.value;"', true); ?><br />
        <?php echo TEXT_ADMIN_PASS . '&nbsp;' . html_password_field('override_pass', '', true, 'onblur="document.getElementById(\'override_pass\').value = this.value;"'); ?>
    </p>
    <p align="right"><?php echo html_icon('actions/go-next.png', TEXT_CONTINUE, 'small', 'onclick="checkOverride();"'); ?></p>
</div>
<h1><?php echo constant('ORD_TEXT_' . JOURNAL_ID . '_WINDOW_TITLE'); ?></h1>
<table  class="ui-widget" style="width:100%;">
    <tbody class="ui-widget-content">
        <tr>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tbody>
                        <tr>
                            <td width="32%">
                                <br/><br/>
                                <span class="form_label"><?php echo ORD_ACCT_ID ?></span><br/>
                                <?php
                                echo ' <br/> ' . html_input_field('search', isset($order->short_name) ? $order->short_name : TEXT_SEARCH, 'size="21" maxlength="20" onfocus="clearField(\'search\', \'' . TEXT_SEARCH . '\')" onblur="setField(\'search\', \'' . TEXT_SEARCH . '\');"');
                                echo '&nbsp;' . html_icon('actions/system-search.png', TEXT_SEARCH, 'small', 'align="top" style="cursor:pointer" onclick="AccountList()"');
                                echo '&nbsp;' . html_icon('actions/document-properties.png', TEXT_PROPERTIES, 'small', 'align="top" style="cursor:pointer" onclick="ContactProp()"');
                                ?>
                            </td>
                            <td width="32%">
                                <br/><br/>
                                <span class="form_label" ><?php echo TEXT_DATE ?> :</span>
                                <br/><br/>
                                <?php echo html_calendar_field($cal_order); ?>
                            </td>
                            <td width="36%">
                                <br/><br/>
                                
                                    <span class="form_label" ><?php echo constant('ORD_HEADING_NUMBER_' . JOURNAL_ID); ?> :</span><br/><br/>
                              
                                <?php echo html_input_field('purchase_invoice_id', $order->purchase_invoice_id, ' class="tool_tip" tooltip_text="' . ORD_TT_PURCH_INV_NUM . '"'); ?>


                                <?php if (isset($template_options['waiting'])) { // show waiting for invoice (purchase_receive, vendor cm) checkbox ?>

                                    <span class="form_label" style="display: none;" ><?php echo $template_options['waiting']['title']; ?> :</span>
                                    <?php echo $template_options['waiting']['field']; ?>


                                <?php } ?>
                                <?php if (isset($template_options['closed'])) { // show close checkbox  ?>

                                    <span class="form_label" ><?php echo $template_options['closed']['title']; ?> :</span>
                                    <?php
                                    echo $template_options['closed']['field'];
                                    ?>


                                <?php } ?>

                                <span class="form_label" ><div id="closed_text" class="ui-state-error" style="display:none"><?php echo TEXT_ORDER_CLOSED_FIELD; ?></div></span>
                                <span id="ship_to_search">&nbsp;</span>
                            </td>
                        </tr>
                        <!--<tr>
                            <td width="33%">
                        <?php if (ENABLE_MULTI_CURRENCY) { // show currency slection pulldown    ?>

                                                    <span class="form_label" ><?php echo TEXT_CURRENCY; ?> :</span><br/>
                                                    <br/>
                            <?php echo html_pull_down_menu('display_currency', gen_get_pull_down(TABLE_CURRENCIES, false, false, 'code', 'title'), $order->currencies_code, 'onchange="recalculateCurrencies();"'); ?>

                        <?php } ?>
                                    <br/><br/>

                            </td>
                            <td width="33%">
                        <?php if (ENABLE_MULTI_CURRENCY) { // show currency slection pulldown    ?>
                                                <span class="form_label" ><?php echo TEXT_EXCHANGE_RATE; ?> :</span><br/><br/>
                            <?php echo html_input_field('currencies_value', $order->currencies_value, 'readonly="readonly"'); ?>
                        <?php } ?>
                                <br/><br/>
                            </td>
                            <td width="33%">
                                
                            </td>
                        </tr>-->

                        <tr >
                            <td colspan="3">
                                <table class="td_padding address_heading" style="width:100%">
                                    <tr>
                                        <td width="50%">
                                            <table style="width: 100%">
                                                <tr>
                                                    <td width="30%"><h5><?php echo in_array(JOURNAL_ID, array(3, 4, 6, 7)) ? TEXT_REMIT_TO : TEXT_BILL_TO; ?></h5></td>
                                                    <td width="70%"><?php echo html_pull_down_menu('bill_to_select', gen_null_pull_down(), '', 'onchange="fillAddress(\'bill\')"'); ?></td>
                                                </tr>
                                            </table>                                           
                                        </td>
                                        <td width="50%">
                                            <table style="width: 100%">
                                                <tr>
                                                    <td width="30%"><h5><?php echo (defined('MODULE_SHIPPING_STATUS')) ? ORD_SHIP_TO : '&nbsp;'; ?></h5></td>
                                                    <td width="70%"><?php echo html_pull_down_menu('ship_to_select', gen_null_pull_down(), '', 'disabled="disabled" onchange="fillAddress(\'ship\')"'); ?></td>
                                                </tr>
                                            </table>                                             
                                        </td>
                                    </tr>
                                </table>
                            </td>


                        </tr>
                        <tr >
                            <td colspan="3">
                                <table class="td_padding" style="width:100%">
                                    <tr>
                                        <td width="50%" >
                                            <table class="address_table" style="width: 100%">
                                                <tr>
                                                    <td width="40%" height="30px" >
                                                        <span class="form_label" ><?php echo GEN_PRIMARY_NAME ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_primary_name', $order->bill_primary_name, 'size="33" maxlength="32" onfocus="clearField(\'bill_primary_name\', \'' . GEN_PRIMARY_NAME . '\')" onblur="setField(\'bill_primary_name\', \'' . GEN_PRIMARY_NAME . '\')"', true) . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo ORD_ADD_UPDATE ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_checkbox_field('bill_add_update', '1', ($order->bill_add_update) ? true : false, '', '') . '<br />' . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo GEN_CONTACT ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_contact', $order->bill_contact, 'size="33" maxlength="32" onfocus="clearField(\'bill_contact\', \'' . GEN_CONTACT . '\')" onblur="setField(\'bill_contact\', \'' . GEN_CONTACT . '\')"', ADDRESS_BOOK_CONTACT_REQUIRED) . chr(10);

                                                        echo(defined('MODULE_SHIPPING_STATUS') ? html_button_field('copy_ship', ORD_COPY_BILL, 'onclick="copyAddress()"') : '') . '<br />' . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo GEN_ADDRESS1 ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_address1', $order->bill_address1, 'size="33" maxlength="32" onfocus="clearField(\'bill_address1\', \'' . GEN_ADDRESS1 . '\')" onblur="setField(\'bill_address1\', \'' . GEN_ADDRESS1 . '\')"', ADDRESS_BOOK_ADDRESS1_REQUIRED) . '<br />' . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo GEN_ADDRESS2 ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_address2', $order->bill_address2, 'size="33" maxlength="32" onfocus="clearField(\'bill_address2\', \'' . GEN_ADDRESS2 . '\')" onblur="setField(\'bill_address2\', \'' . GEN_ADDRESS2 . '\')"', ADDRESS_BOOK_ADDRESS2_REQUIRED) . '<br />' . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo GEN_CITY_TOWN ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_city_town', $order->bill_city_town, 'size="25" maxlength="24" onfocus="clearField(\'bill_city_town\', \'' . GEN_CITY_TOWN . '\')" onblur="setField(\'bill_city_town\', \'' . GEN_CITY_TOWN . '\')"', ADDRESS_BOOK_CITY_TOWN_REQUIRED) . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo GEN_STATE_PROVINCE ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_state_province', $order->bill_state_province, 'size="3" maxlength="5" onfocus="clearField(\'bill_state_province\', \'' . GEN_STATE_PROVINCE . '\')" onblur="setField(\'bill_state_province\', \'' . GEN_STATE_PROVINCE . '\')"', ADDRESS_BOOK_STATE_PROVINCE_REQUIRED) . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo GEN_POSTAL_CODE ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_postal_code', $order->bill_postal_code, 'size="11" maxlength="10" onfocus="clearField(\'bill_postal_code\', \'' . GEN_POSTAL_CODE . '\')" onblur="setField(\'bill_postal_code\', \'' . GEN_POSTAL_CODE . '\')"', ADDRESS_BOOK_POSTAL_CODE_REQUIRED) . '<br />' . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" >Country </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td class="country_menu" width="56%" height="30px">
                                                        <?php
                                                        echo html_pull_down_menu('bill_country_code', gen_get_countries(), $order->bill_country_code) . '<br />' . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo GEN_TELEPHONE1 ?> </span>  
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_telephone1', $order->bill_telephone1, 'size="21" maxlength="20" onfocus="clearField(\'bill_telephone1\', \'' . GEN_TELEPHONE1 . '\')" onblur="setField(\'bill_telephone1\', \'' . GEN_TELEPHONE1 . '\')"', ADDRESS_BOOK_TELEPHONE1_REQUIRED) . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="40%" height="30px">
                                                        <span class="form_label" ><?php echo GEN_EMAIL ?> </span>
                                                    </td>
                                                    <td width="4%" height="30px">
                                                        :
                                                    </td>
                                                    <td width="56%" height="30px">
                                                        <?php
                                                        echo html_input_field('bill_email', $order->bill_email, 'size="35" maxlength="48" onfocus="clearField(\'bill_email\', \'' . GEN_EMAIL . '\')" onblur="setField(\'bill_email\', \'' . GEN_EMAIL . '\')"', ADDRESS_BOOK_EMAIL_REQUIRED) . chr(10);
                                                        ?>

                                                    </td>
                                                </tr>

                                            </table>

                                        </td>
                                        <td width="50%">
                                            <table class="address_table" style="width: 100%">
                                                <?php
                                                if (defined('MODULE_SHIPPING_STATUS')) { // show shipping address
                                                    ?>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_PRIMARY_NAME ?> </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_primary_name', $order->ship_primary_name, 'size="33" maxlength="32" onfocus="clearField(\'ship_primary_name\', \'' . GEN_PRIMARY_NAME . '\')" onblur="setField(\'ship_primary_name\', \'' . GEN_PRIMARY_NAME . '\')"', true) . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo ORD_ADD_UPDATE ?> </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">

                                                            <?php
                                                            echo html_checkbox_field('ship_add_update', '1', ($order->ship_add_update) ? true : false, '', '') . '<br />' . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_CONTACT ?> </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_contact', $order->ship_contact, 'size="33" maxlength="32" onfocus="clearField(\'ship_contact\', \'' . GEN_CONTACT . '\')" onblur="setField(\'ship_contact\', \'' . GEN_CONTACT . '\')"', ADDRESS_BOOK_SHIP_CONTACT_REQ) . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr style="display:none">
                                                        <td width="40%">
                                                            <span class="form_label" ><?php echo ORD_DROP_SHIP ?>  </span>
                                                        </td>
                                                        <td width="4%">
                                                            :
                                                        </td>
                                                        <td width="56%">
                                                            <?php
                                                            echo html_checkbox_field('drop_ship', '1', ($order->drop_ship) ? true : false, '', 'onclick="DropShipView(this)"') . '<br />' . chr(10);
                                                            ?>
                                                            <br/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_ADDRESS1 ?> </span> 
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_address1', $order->ship_address1, 'size="33" maxlength="32" onfocus="clearField(\'ship_address1\', \'' . GEN_ADDRESS1 . '\')" onblur="setField(\'ship_address1\', \'' . GEN_ADDRESS1 . '\')"', ADDRESS_BOOK_SHIP_ADD1_REQ) . '<br />' . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_ADDRESS2 ?> </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_address2', $order->ship_address2, 'size="33" maxlength="32" onfocus="clearField(\'ship_address2\', \'' . GEN_ADDRESS2 . '\')" onblur="setField(\'ship_address2\', \'' . GEN_ADDRESS2 . '\')"', ADDRESS_BOOK_SHIP_ADD2_REQ) . '<br />' . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_CITY_TOWN ?> </span> 
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_city_town', $order->ship_city_town, 'size="25" maxlength="24" onfocus="clearField(\'ship_city_town\', \'' . GEN_CITY_TOWN . '\')" onblur="setField(\'ship_city_town\', \'' . GEN_CITY_TOWN . '\')"', ADDRESS_BOOK_SHIP_CITY_REQ) . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_STATE_PROVINCE ?> </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_state_province', $order->ship_state_province, 'size="3" maxlength="5" onfocus="clearField(\'ship_state_province\', \'' . GEN_STATE_PROVINCE . '\')" onblur="setField(\'ship_state_province\', \'' . GEN_STATE_PROVINCE . '\')"', ADDRESS_BOOK_SHIP_STATE_REQ) . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_POSTAL_CODE ?> </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_postal_code', $order->ship_postal_code, 'size="11" maxlength="10" onfocus="clearField(\'ship_postal_code\', \'' . GEN_POSTAL_CODE . '\')" onblur="setField(\'ship_postal_code\', \'' . GEN_POSTAL_CODE . '\')"', ADDRESS_BOOK_SHIP_POSTAL_CODE_REQ) . '<br />' . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" >Country </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td class="country_menu" width="56%" height="30px">
                                                            <?php
                                                            echo html_pull_down_menu('ship_country_code', gen_get_countries(), $order->ship_country_code) . '<br />' . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_TELEPHONE1 ?>  </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_telephone1', $order->ship_telephone1, 'size="21" maxlength="20" onfocus="clearField(\'ship_telephone1\', \'' . GEN_TELEPHONE1 . '\')" onblur="setField(\'ship_telephone1\', \'' . GEN_TELEPHONE1 . '\')"', ADDRESS_BOOK_TELEPHONE1_REQUIRED) . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%" height="30px">
                                                            <span class="form_label" ><?php echo GEN_EMAIL ?>  </span>
                                                        </td>
                                                        <td width="4%" height="30px">
                                                            :
                                                        </td>
                                                        <td width="56%" height="30px">
                                                            <?php
                                                            echo html_input_field('ship_email', $order->ship_email, 'size="35" maxlength="48" onfocus="clearField(\'ship_email\', \'' . GEN_EMAIL . '\')" onblur="setField(\'ship_email\', \'' . GEN_EMAIL . '\')"', ADDRESS_BOOK_EMAIL_REQUIRED) . chr(10);
                                                            ?>

                                                        </td>
                                                    </tr>

                                                    <?php
                                                } else {
                                                    echo html_hidden_field('ship_primary_name', '') . chr(10);
                                                    echo html_hidden_field('ship_add_update', '0') . chr(10);
                                                    echo html_hidden_field('ship_contact', '') . chr(10);
                                                    echo html_hidden_field('drop_ship', '0') . chr(10);
                                                    echo html_hidden_field('ship_address1', '') . chr(10);
                                                    echo html_hidden_field('ship_address2', '') . chr(10);
                                                    echo html_hidden_field('ship_city_town', '') . chr(10);
                                                    echo html_hidden_field('ship_state_province', '') . chr(10);
                                                    echo html_hidden_field('ship_postal_code', '') . chr(10);
                                                    echo html_hidden_field('ship_country_code', COMPANY_COUNTRY) . chr(10);
                                                    echo html_hidden_field('ship_telephone1', '') . chr(10);
                                                    echo html_hidden_field('ship_email', '') . chr(10);
                                                }
                                                ?>
                                            </table>

                                        </td>
                                    </tr>
                                </table>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <script type="text/javascript">
                     $(document).ready(function(){
                         
                         $("#hidden_btn").click(function(){
                             $("#currency_area").slideToggle("fast");
                             var class_name = $(this).attr("class"); 
                             
                             if(class_name == "plus" ){
                                 $(this).children("img").attr("src","images/minus_icon.png");
                                 $(this).removeClass('plus').addClass('minus');
                                 $(this).children("img").removeAttr("title").attr("title","Hide Form");
                                 
                             } else if(class_name == "minus"){
                                 $(this).children("img").attr("src","images/plus_icon.png");
                                 $(this).removeClass('minus').addClass('plus');
                                 $(this).children("img").removeAttr("title").attr("title","Show More Form");
                             }
                             
                         });
                     });
                </script>
                <div id="hidden_btn" style="width:25px; height: 25px; cursor: pointer;" class="plus">
                    <img alt="" src="images/plus_icon.png" title="Show More Form" />
                </div>
                <br/>
                <br/>
                <div id="currency_area" style="display:none;">
                <table class="input_width" style="border-collapse:collapse;width:100%;">
                    <tr>
                        <td width="33%">
                            <table class="td_padding" style="width: 100%">
                                <?php if ($template_options['terms']) { ?>
                                    <tr>
                                        <td width="40%" height="30px"><span class="form_label"><?php echo ACT_TERMS_DUE; ?></span></td>
                                        <td width="4%" height="30px">:</td>
                                        <td width="56%" height="30px"><?php echo html_input_field('terms_text', gen_terms_to_language('0', true, 'ap'), 'readonly="readonly" size="25"') . '&nbsp;' . html_icon('apps/accessories-text-editor.png', ACT_TERMS_DUE, 'small', 'align="top" style="cursor:pointer" onclick="TermsList()"'); ?></td>
                                    </tr>
                                <?php }
                                ?>
                            </table>
                        </td>
                        <td width="33%">
                            <table class="td_padding" style="width: 100%">
                                <?php
                                if ($template_options['terminal_date']) {
                                    ?>
                                    <tr>
                                        <td width="40%" height="30px"><span class="form_label"><?php echo (in_array(JOURNAL_ID, array(3, 4, 9)) ? TEXT_EXPIRATION_DATE : TEXT_SHIP_BY_DATE); ?></span></td>
                                        <td width="4%" height="30px">:</td>
                                        <td width="56%" height="30px"><?php echo html_calendar_field($cal_terminal); ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                        <td  width="33%">
                            
                        </td>
                    </tr>
                    <tr class="select_width">
                        <td width="33%">

                            <table class="td_padding" style="width: 100%">
                                <tr>
                                    <td width="40%" height="30px"><span class="form_label"><?php echo DEF_GL_ACCT_TITLE; ?></span></td>
                                    <td width="4%" height="30px">:</td>
                                    <td width="56%" height="30px"><?php echo html_pull_down_menu('gl_acct_id', $gl_array_list, $order->gl_acct_id, ''); ?></td>
                                </tr>
                            </table>

                        </td>
                        <td width="33%">

                            <table class="td_padding" style="width: 100%">
                                <?php if (defined('MODULE_SHIPPING_STATUS')) { ?>
                                    <tr>
                                        <td width="40%" height="30px"><?php echo ORD_FREIGHT_GL_ACCT ?></td>
                                        <td width="4%" height="30px">:</td>
                                        <td width="56%" height="30px"><?php echo html_pull_down_menu('ship_gl_acct_id', $gl_array_list, $order->ship_gl_acct_id, ''); ?></td>
                                    </tr>
                                    <?php
                                } else {
                                    echo '        <tr style="display:none"><td colspan="2">' . chr(10);
                                    echo html_pull_down_menu('ship_carrier', gen_null_pull_down(), '', 'style="visibility:hidden"');
                                    echo html_pull_down_menu('ship_service', gen_null_pull_down(), '', 'style="visibility:hidden"');
                                    echo html_hidden_field('ship_gl_acct_id', '');
                                    echo html_hidden_field('freight', '0') . chr(10);
                                    echo '        </td></tr>' . chr(10);
                                }
                                ?>
                            </table>
                        </td>
                        <td width="33%">
                            <table class="td_padding" style="width: 100%">
                                <?php if (ENABLE_ORDER_DISCOUNT && in_array(JOURNAL_ID, array(9, 10, 12, 19))) { ?>
                                    <tr>
                                        <td width="40%" height="30px"><span class="form_label"><?php echo ORD_DISCOUNT_GL_ACCT; ?></span> </td>
                                        <td width="4%" height="30px">:</td>
                                        <td width="56%" height="30px"><?php echo html_pull_down_menu('disc_gl_acct_id', $gl_array_list, $order->disc_gl_acct_id, ''); ?></td>
                                    </tr>
                                    <?php
                                } else {
                                    $hidden_fields .= html_hidden_field('disc_gl_acct_id', '') . chr(10);
                                    $hidden_fields .= html_hidden_field('discount', '0') . chr(10);
                                    $hidden_fields .= html_hidden_field('disc_percent', '0') . chr(10);
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="33%">
                            <table class="td_padding" style="width: 100%">
                                <tr>
                                    <td width="40%" height="30px">
                                        <span class="form_label"><?php echo in_array(JOURNAL_ID, array(6, 9, 10, 12)) ? ORD_HEADING_NUMBER_4 : TEXT_REFERENCE_NUMBER; ?></span>
                                    </td>
                                    <td width="4%" height="30px"> :</td>
                                    <td width="56%" height="30px"><?php echo html_input_field('purch_order_id', $order->purch_order_id, 'size="21" maxlength="20"'); ?> </td>
                                </tr>
                            </table>
                            

                        </td>
                        <td widht="33%">
                            <table class="td_padding" style="width: 100%">
                                <tr>
                                    <td width="40%" height="30px"><span class="form_label"><?php echo (in_array(JOURNAL_ID, array(3, 4, 6, 7, 9)) ? TEXT_BUYER : TEXT_SALES_REP); ?></span></td>
                                    <td width="4%" height="30px">:</td>
                                    <td width="56%" height="30px"><?php echo html_pull_down_menu('rep_id', gen_get_rep_ids($account_type), $order->rep_id ? $order->rep_id : $default_sales_rep); ?></td>
                                </tr>
                            </table>
                            
                        </td>
                        <td widht="33%">
                            <?php if (ENABLE_MULTI_BRANCH) { ?>
                                <table class="td_padding" style="width: 100%">
                                    <tr>

                                        <td width="40%" height="30px"><span class="form_label"><?php echo GEN_STORE_ID . chr(10); ?></span></td>
                                        <td width="4%" height="30px">:</td>
                                        <td width="56%" height="30px"><?php echo html_pull_down_menu('store_id', gen_get_store_ids(), $order->store_id ? $order->store_id : $_SESSION['admin_prefs']['def_store_id']); ?></td>                        

                                    </tr>
                                </table>
                            <?php } ?>
                        </td>

                    </tr>
                    <tr>
                        <td width="33%">
                            <?php if (ENABLE_MULTI_CURRENCY) { // show currency slection pulldown    ?>
                                <table class="td_padding" style="width: 100%">
                                    <tr>
                                        <td width="40%" height="30px"><span class="form_label"><?php echo TEXT_CURRENCY; ?></span></td>
                                        <td width="4%" height="30px">:</td>
                                        <td width="56%" height="30px"><?php echo html_pull_down_menu('display_currency', gen_get_pull_down(TABLE_CURRENCIES, false, false, 'code', 'title'), $order->currencies_code, 'onchange="recalculateCurrencies();"'); ?></td>
                                    </tr>
                                </table>
                            <?php } ?>

                        </td>
                        <td width="33%">
                            <?php if (ENABLE_MULTI_CURRENCY) { ?>
                                <table class="td_padding" style="width: 100%">
                                    <tr>
                                        <td width="40%" height="30px"><span class="form_label"><?php echo TEXT_EXCHANGE_RATE; ?></span></td>
                                        <td width="4%" height="30px">:</td>
                                        <td width="56%" height="30px"><?php echo html_input_field('currencies_value', $order->currencies_value); ?></td>
                                    </tr>
                                </table>
                            <?php } ?>

                        </td>
                        <td width="33%">
                            <?php if (JOURNAL_ID == 12) { ?>
                                <table class="td_padding" style="width: 100%">
                                    <tr>
                                        <td width="40%" height="30px">
                                            <span class="form_label"><?php echo ORD_HEADING_NUMBER_10 . chr(10); ?></span>
                                        </td>
                                        <td width="4%" height="30px"> :</td>
                                        <td width="56%" height="30px">
                                            <?php echo html_input_field('sales_order_num', $order->sales_order_num, 'readonly="readonly" size="21" maxlength="20"'); ?>
                                        </td>
                                    </tr>
                                </table>
                            <?php } ?>
                        </td>
                    </tr>
                    
                        
                        
                <tr>
                    <td colspan="3">
                        <br/><br/>
                        <table class="ui-widget" width="100%">
                            <tr>
                                <td width="20%">Message</td>
                                <td width="4%">:</td>
                                <td width="56%">
                                    <?php 
                                       
                                        //echo html_input_field('message', $order->message, 'readonly="readonly" size="21" maxlength="20"'); 
                                    ?>
                                    <?php echo html_textarea_field('message', 28, 3, $order->message, '', true); ?>

                                    <script type="text/javascript">
                                        //<![CDATA[
                                        

                                                // Replace the <textarea id="editor"> with an CKEditor
                                                // instance, using default configurations.
                                                
                                                        
                                                        setTimeout(function(){
                                                           
                                                        CKEDITOR.replace( 'message',
                                                        {
                                                                enterMode : CKEDITOR.ENTER_BR,
                                                                toolbar :
                                                                [
                                                                        [ 'Bold', 'Italic','Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','Font','FontSize','TextColor','BGColor','Subscript','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', ],

                                                                ]
                                                        });
                                                        
                                                        },1000)
                                                        
                                                       // CKEDITOR.instances.message.setData(t);

                                        //]]>
                                    </script>

                                    <?php //echo html_input_field('message', $order->message, 'size="21" maxlength="20"'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>

                        </table>


                        
                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <td width="33%">
                        <table class="td_padding" style="width: 100%">
                            <tr>
                                <td width="40%" height="30px"><span class="form_label"><?php echo TEXT_SELECT_FILE_TO_ATTACH; ?></span><td>

                                <td width="4%" height="30px">:</td>
                                <td width="56%" height="30px"><?php echo html_file_field('file_name'); ?></td>
                            </tr>
                        </table>
                    </td>
                    <td width="33%"></td>
                    <td width="33%"></td>                            
                </tr>       
                <tr>
                    <td colspan="3">
                        <div id="show_attach" style="display:none">
                            <?php echo html_button_field('dn_attach', TEXT_DOWNLOAD_ATTACHMENT, 'onclick="downloadAttachment()"'); ?>
                            <?php echo html_checkbox_field('rm_attach', '1', ($order->del_attach) ? true : false, '', '') . ' ' . TEXT_DELETE_ATTACHMENT; ?>
                        </div>
                    </td>
                                              
                </tr>      
                <tr>
                    <td colspan="3">
                      
                        <?php
                        $recurring_interval = "";
                        $checked = "";

                        if (isset($_GET['oID']) && $_GET['jID'] == 12) {

                            $query = "SELECT * FROM recurring_to_invoice WHERE invoice_id=" . $_GET['oID'];
                            $res = mysql_query($query);
                            if($res){
                            $recurring_info = mysql_fetch_assoc($res);
                            if ($recurring_info) {
                                $checked = 'checked="checked"';

                                $query_2 = "SELECT * FROM invoice_recurring WHERE id=" . $recurring_info['recurring_id'];
                                $result = mysql_query($query_2);
                                $recurring_info = mysql_fetch_assoc($result);
                                $recurring_interval = $recurring_info['recurring_interval'];
                            }}
                        }
                        ?>

                        <?php if ($_GET['jID'] == 12 || $_GET['jID'] == 6) { ?>
                            <div class="recurring_block">
                                Recurring Invoice :
                                <input type="checkbox" <?php echo $checked; ?> name="is_recurring" id="is_recurring" />Repeat this invoice?
                                <span class="recurring_interval_block">Every <input value="<?php echo $recurring_interval; ?>" type="text" name="recurring_interval" id="recurring_interval" /> days</span>
                            <!--    <span class="email_recurring_block">
                                    <br /><br /><input type="checkbox" name="email_recurring_invoice" id="email_recurring_invoice" />Email recurring invoice? (If checked, the recurring invoice will be emailed to the clients email address upon creation.)
                                </span>-->
                                <?php
                                if ($checked != "") {
                                    ?>
                                    <span class="update_recurring_block">
                                        <br /><br /><input type="checkbox" name="update_recurring_info" id="update_recurring_info" />Update recurring info? <span style="color: red;">(Any changes made to the recurring instruction will be lost and it'll match with the invoice)</span>

                                    </span>
                                    <?php
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </td>
                                           
                </tr>   
                        
                </table>
                    <br/><br/><br/>
                </div>
            </td>
        </tr>
        <!--<tr>
            <td>
                <input type="text" onclick="window.open('index.php?module=bank_transfer&page=disc_msg','Description','height=500,width=750')" />
            </td>
        </tr>-->
        
        <tr>
            <td id="productList">
                <table style="border-collapse:collapse;width:100%;">
                    <thead class="ui-widget-header">
                        <?php if (SINGLE_LINE_ORDER_SCREEN) { ?>
                            <tr>
                                <th><?php echo TEXT_ITEM; ?></th>
                                <th><?php echo TEXT_SKU; ?></th>
                                <th><?php echo TEXT_DESCRIPTION; ?></th>
                                <th><?php echo TEXT_COLUMN_1_TITLE; ?></th>
                                <th><?php echo TEXT_UNIT_PRICE; ?></th>
                                <th><?php echo TEXT_GL_ACCOUNT; ?></th>
                                <th><?php echo ORD_TAX_RATE; ?></th>
                                <th><?php echo TEXT_COLUMN_2_TITLE; ?></th>                                
                                <th><?php echo TEXT_AMOUNT; ?></th>
                                <th><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small'); ?></th>
                            </tr>
                        <?php } else { // two line order screen   ?>
                            <tr>
                                <th><?php echo TEXT_ITEM; ?></th>
                                <th><?php echo TEXT_SKU; ?></th>
                                <th ><?php echo TEXT_DESCRIPTION; ?></th>

                                <th><?php echo TEXT_COLUMN_1_TITLE; ?></th>
                                <th><?php echo TEXT_UNIT_PRICE; ?></th>

                                <th ><?php echo TEXT_GL_ACCOUNT; ?></th>
                                <th ><?php echo ORD_TAX_RATE; ?></th>
                                <th ><?php echo TEXT_PROJECT; ?></th>
                                <th><?php echo TEXT_COLUMN_2_TITLE; ?></th>




                                <!--</tr>
                                <tr>-->


                <!--<th><?php echo TEXT_PRICE; ?></th>-->
                <!--<th><?php echo TEXT_DISCOUNT; ?></th>-->


                                <th><?php echo TEXT_AMOUNT; ?></th>
                                <th><?php echo html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small'); ?></th>
                            </tr>
                        <?php } // end if single line order screen   ?>
                    </thead>
                    <tbody id="item_table"  class="ui-widget-content <?php if ($_GET['jID'] == 12 || $_GET['jID'] == 6 && $_GET['action'] == "prc_so") { ?> invoice_table <?php } ?>">
                        <?php
                        if ($order->item_rows) {
                            for ($j = 0, $i = 1; $j < count($order->item_rows); $j++, $i++) {
                                echo '<tr>' . chr(10);
                                // turn off delete icon if required
                                if (($order->item_rows[$j]['so_po_item_ref_id']) || ((JOURNAL_ID == 4 || JOURNAL_ID == 10) && $order->item_rows[$j]['pstd'])) {
                                    echo '  <td align="center">&nbsp;</td>' . chr(10);
                                    $sku_enable = false;
                                } else {
                                    echo '  <td align="center">' . html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick="if (confirm(\'' . TEXT_ROW_DELETE_ALERT . '\')) removeInvRow(' . $i . ');"') . '</td>' . chr(10);
                                    $sku_enable = true;
                                }
                                if (SINGLE_LINE_ORDER_SCREEN) {
                                    echo '  <td>' . html_input_field('item_cnt_' . $i, $order->item_rows[$j]['item_cnt'], 'size="3" maxlength="3" readonly="readonly"') . '</td>' . chr(10);
                                }
                                echo '  <td nowrap="nowrap" align="center">';
                                echo html_input_field('qty_' . $i, $order->item_rows[$j]['qty'], ($item_col_1_enable ? '' : ' readonly="readonly"') . ' size="7" maxlength="6" onchange="updateRowTotal(' . $i . ', true)" style="text-align:right"');
                                echo '</td>' . chr(10);
                                echo '  <td nowrap="nowrap" align="center">';
                                echo html_input_field('pstd_' . $i, $order->item_rows[$j]['pstd'], ($item_col_2_enable ? '' : ' readonly="readonly"') . ' size="7" maxlength="6" onchange="updateRowTotal(' . $i . ', true)" style="text-align:right"');
                                // for serialized items, show the icon
                                if (in_array(JOURNAL_ID, array(6, 7, 12, 13)))
                                    echo html_icon('actions/tab-new.png', TEXT_SERIAL_NUMBER, 'small', 'id="imgSerial_' . $i . '" align="top" style="cursor:pointer; display:none;" onclick="serialList(\'serial_' . $i . '\')"');
                                echo '</td>' . chr(10);
                                echo '  <td nowrap="nowrap" align="center">';
                                echo html_input_field('sku_' . $i, $order->item_rows[$j]['sku'], ($sku_enable ? '' : ' readonly="readonly"') . ' size="' . (MAX_INVENTORY_SKU_LENGTH + 1) . '" maxlength="' . MAX_INVENTORY_SKU_LENGTH . '" onfocus="clearField(\'sku_' . $i . '\', \'' . TEXT_SEARCH . '\')" onblur="setField(\'sku_' . $i . '\', \'' . TEXT_SEARCH . '\'); loadSkuDetails(0, ' . $i . ')"') . chr(10);
                                echo html_icon('status/folder-open.png', TEXT_SEARCH, 'small', 'id="sku_open_' . $i . '" align="top" style="cursor:pointer' . ($sku_enable ? '' : ';display:none') . '" onclick="InventoryList(' . $i . ')"') . chr(10);
                                echo '</td>' . chr(10);
// for textarea uncomment below (NOTE: the field length is set in the db to 255, this choice does not control the users input character count, or ...
//				echo '  <td colspan="' . ((SINGLE_LINE_ORDER_SCREEN) ? 1 : 5) . '">' . html_textarea_field('desc_' . $i, (SINGLE_LINE_ORDER_SCREEN)?50:110, '1', $order->item_rows[$j]['desc'], 'maxlength="255"') . '</td>' . chr(10);
// for standard controlled input, uncomment below (default setting)
                                echo '  <td colspan="' . ((SINGLE_LINE_ORDER_SCREEN) ? 1 : 3) . '">' . html_input_field('desc_' . $i, $order->item_rows[$j]['desc'], 'size="' . ((SINGLE_LINE_ORDER_SCREEN) ? 50 : 75) . '" maxlength="255"') . '</td>' . chr(10);
                                if (SINGLE_LINE_ORDER_SCREEN) {
                                    echo '  <td>' . html_combo_box('acct_' . $i, $gl_array_list, $order->item_rows[$j]['acct'], 'size="10"') . '</td>' . chr(10);
                                } else {
                                    echo '  <td colspan="2">' . html_pull_down_menu('proj_' . $i, $proj_list, $order->item_rows[$j]['proj']) . '</td>' . chr(10);
                                    echo '</tr>' . chr(10) . '<tr>' . chr(10);
                                    echo '  <td>' . html_input_field('item_cnt_' . $i, $order->item_rows[$j]['item_cnt'], 'size="3" maxlength="3" readonly="readonly"') . '</td>' . chr(10);
                                    echo '  <td colspan="3">' . html_pull_down_menu('acct_' . $i, $gl_array_list, $order->item_rows[$j]['acct']) . '</td>' . chr(10);
                                    echo '  <td>' . html_input_field('full_' . $i, '', 'readonly="readonly" size="11" maxlength="10" style="text-align:right"') . '</td>' . chr(10);
                                    echo '  <td>' . html_input_field('disc_' . $i, '', 'readonly="readonly" size="11" maxlength="10" style="text-align:right"') . '</td>' . chr(10);
                                }
                                echo '  <td nowrap="nowrap" align="center">';
                                echo html_input_field('price_' . $i, $currencies->precise($order->item_rows[$j]['price'], true, $order->currencies_code, $order->currencies_value), 'size="8" maxlength="20" onchange="updateRowTotal(' . $i . ', false)" style="text-align:right"') . chr(10);
                                echo html_icon('mimetypes/x-office-spreadsheet.png', TEXT_PRICE_MANAGER, 'small', 'align="top" style="cursor:pointer" onclick="PriceManagerList(' . $i . ')"');
                                echo '</td>' . chr(10);
                                echo '  <td>' . html_pull_down_menu('tax_' . $i, $tax_rates, $order->item_rows[$j]['tax'], 'onchange="updateRowTotal(' . $i . ', false)"') . '</td>' . chr(10);
                                echo '  <td align="center">' . chr(10);
                                // Hidden fields
                                echo html_hidden_field('id_' . $i, $order->item_rows[$j]['id']) . chr(10);
                                echo html_hidden_field('so_po_item_ref_id_' . $i, $order->item_rows[$j]['so_po_item_ref_id']) . chr(10);
                                echo html_hidden_field('weight_' . $i, $order->item_rows[$j]['weight']) . chr(10);
                                echo html_hidden_field('stock_' . $i, $order->item_rows[$j]['stock']) . chr(10);
                                echo html_hidden_field('inactive_' . $i, $order->item_rows[$j]['inactive']) . chr(10);
                                echo html_hidden_field('lead_' . $i, $order->item_rows[$j]['lead_time']) . chr(10);
                                echo html_hidden_field('serial_' . $i, $order->item_rows[$j]['serial']) . chr(10);
                                echo html_hidden_field('date_1_' . $i, $order->item_rows[$j]['date_1']) . chr(10);
                                if (SINGLE_LINE_ORDER_SCREEN) {
                                    echo html_hidden_field('proj_' . $i, $order->item_rows[$j]['proj']) . chr(10);
                                    echo html_hidden_field('full_' . $i, '') . chr(10);
                                    echo html_hidden_field('disc_' . $i, '') . chr(10);
                                }
                                // End hidden fields
                                echo html_input_field('total_' . $i, $currencies->format($order->item_rows[$j]['total'], true, $order->currencies_code, $order->currencies_value), 'size="11" maxlength="20" onchange="updateUnitPrice(' . $i . ')" style="text-align:right"') . chr(10);
                                echo '  </td>' . chr(10);
                                echo '</tr>' . chr(10);
                            }
                        } else {
                            $hidden_fields .= '<script type="text/javascript">addInvRow();</script>' . chr(10);
                        }
                        ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right"><?php echo html_icon('actions/list-add.png', TEXT_ADD, 'medium', 'onclick="addInvRow()"'); ?></td>
        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:100%">
                    <tbody class="ui-widget-content">
                        <tr>
                            <td>&nbsp;</td>
                            <td align="right">
                                <?php echo ORD_SUBTOTAL . ' '; ?>
                                <?php echo html_input_field('subtotal', $currencies->format($order->subtotal, true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="15" maxlength="20" style="text-align:right"'); ?>
                            </td>
                        </tr>

                        <?php if (ENABLE_ORDER_DISCOUNT && in_array(JOURNAL_ID, array(9, 10, 12, 19))) { ?>
                            <tr>
                                <td>
                                    <?php
                                    //echo ORD_DISCOUNT_GL_ACCT . ' ';
                                    //echo html_pull_down_menu('disc_gl_acct_id', $gl_array_list, $order->disc_gl_acct_id, '');
                                    ?>
                                </td>
                                <td align="right">
                                    <?php echo ORD_DISCOUNT_PERCENT . ' ' . html_input_field('disc_percent', ($order->disc_percent ? number_format(100 * $order->disc_percent, 3) : '0'), 'size="7" maxlength="6" onchange="calculateDiscountPercent()" style="text-align:right"') . ' ' . TEXT_DISCOUNT; ?>
                                    <?php echo html_input_field('discount', $currencies->format(($order->discount ? $order->discount : '0'), true, $order->currencies_code, $order->currencies_value), 'size="15" maxlength="20" onchange="calculateDiscount()" style="text-align:right"'); ?>
                                </td>
                            </tr>
                            <?php
                        } else {
                            $hidden_fields .= html_hidden_field('disc_gl_acct_id', '') . chr(10);
                            $hidden_fields .= html_hidden_field('discount', '0') . chr(10);
                            $hidden_fields .= html_hidden_field('disc_percent', '0') . chr(10);
                        }
                        ?>
                        <?php if (defined('MODULE_SHIPPING_STATUS')) { ?>
                            <tr>
                                <td>
                                    <?php
                                    //echo ORD_FREIGHT_GL_ACCT . ' ';
                                    //echo html_pull_down_menu('ship_gl_acct_id', $gl_array_list, $order->ship_gl_acct_id, '');
                                    ?>
                                </td>
                                <td align="right">
                                    <?php
                                    echo html_button_field('estimate', TEXT_ESTIMATE, 'onclick="FreightList()"');
                                    echo ORD_SHIP_CARRIER . ' ' . html_pull_down_menu('ship_carrier', $methods, $default = '', 'onchange="buildFreightDropdown()"');
                                    echo ' ' . ORD_FREIGHT_SERVICE . ' ' . html_pull_down_menu('ship_service', gen_null_pull_down(), '');
                                    echo ' ' . ORD_FREIGHT . ' ';
                                    echo html_input_field('freight', $currencies->format(($order->freight ? $order->freight : '0.00'), true, $order->currencies_code, $order->currencies_value), 'size="15" maxlength="20" onchange="updateTotalPrices()" style="text-align:right"');
                                    ?>
                                </td>
                            </tr>
                            <?php
                        } else {
                            echo '        <tr style="display:none"><td colspan="2">' . chr(10);
                            echo html_pull_down_menu('ship_carrier', gen_null_pull_down(), '', 'style="visibility:hidden"');
                            echo html_pull_down_menu('ship_service', gen_null_pull_down(), '', 'style="visibility:hidden"');
                            echo html_hidden_field('ship_gl_acct_id', '');
                            echo html_hidden_field('freight', '0') . chr(10);
                            echo '        </td></tr>' . chr(10);
                        }
                        ?>
                        <tr>
                            <td></td>
<!--                            <td><?php echo TEXT_SELECT_FILE_TO_ATTACH . ' ' . html_file_field('file_name'); ?></td>-->
                            <td align="right">
                                <?php echo ($account_type == 'v') ? ORD_PURCHASE_TAX . ' ' : ORD_SALES_TAX . ' '; ?>
                                <?php echo ' ' . html_input_field('sales_tax', $currencies->format(($order->sales_tax ? $order->sales_tax : '0.00'), true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="15" maxlength="20" onchange="updateTotalPrices()" style="text-align:right"'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;
                            </td>
                            <td align="right">
                                <?php echo ORD_INVOICE_TOTAL . ' '; ?>
                                <?php echo html_input_field('total', $currencies->format($order->total_amount, true, $order->currencies_code, $order->currencies_value), 'readonly="readonly" size="15" maxlength="20" style="text-align:right"'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        
    </tbody>
</table>
<?php
                                // display the hidden fields that are not used in this rendition of the form

                                echo $hidden_fields;
                                ?>
<div class="bottom_btn">
    <?php
    echo $toolbar->build_toolbar();
    ?> 
</div>



</form>
<iframe style="display: none"  id="print" src="" ></iframe>





