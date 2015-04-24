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
//  Path: /modules/ucbooks/pages/admin/template_tab_chart_of_accounts.php
//

$chart_of_accounts_toolbar = new toolbar;
$chart_of_accounts_toolbar->icon_list['cancel']['show'] = false;
$chart_of_accounts_toolbar->icon_list['open']['show'] = false;
$chart_of_accounts_toolbar->icon_list['save']['show'] = false;
$chart_of_accounts_toolbar->icon_list['delete']['show'] = false;
$chart_of_accounts_toolbar->icon_list['print']['show'] = false;
if ($security_level > 1)
    $chart_of_accounts_toolbar->add_icon('new', 'onclick="loadPopUp(\'chart_of_accounts_new\', 0)"', $order = 10, '+ New Account');
?>
<style>
    #tb_icon_save{display: none;}
    #tb_icon_cancel{display: none;}
    #tb_icon_new{   margin-left: 450px;
                    margin-top: 15px;
                    position: absolute;
                    text-align: center;
                    z-index: 1;}
    .upDownFile{ padding: 10px; text-align: left; border-radius: 6px;}
    #delete_chart{ width: 15px !important}
</style>
<?php
echo html_form('orders', FILENAME_DEFAULT, '?module=ucbooks&page=chart_of_accounts&action_type=setup_account_defaults&action=save', 'post', 'enctype="multipart/form-data"') . chr(10);
// include hidden fields
//echo html_hidden_field('todo', '','id="todo"') . chr(10);
//echo html_hidden_field('subject', '') . chr(10);
//echo html_hidden_field('rowSeq', '') . chr(10);
?>
<table class="ui-widget" style="border-collapse:collapse;width:100%; margin-top: 10px;">
    <thead class="ui-widget-header">
        <tr>
            <th colspan="4">Default GL Accounts for Sales</th>
        </tr>
    </thead>
    <tbody class="ui-widget-content">
        <tr>
            <td colspan="3">Default Accounts Receivables account. Typically an Accounts Receivable type account.</td>
            <td><?php echo html_pull_down_menu('ar_default_gl_acct', $ar_chart, $_POST['ar_default_gl_acct'] ? $_POST['ar_default_gl_acct'] : AR_DEFAULT_GL_ACCT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3">Default account to use for sales transactions. Typically an Income type account.</td>
            <td><?php echo html_pull_down_menu('ar_def_gl_sales_acct', $inc_chart, $_POST['ar_def_gl_sales_acct'] ? $_POST['ar_def_gl_sales_acct'] : AR_DEF_GL_SALES_ACCT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3">Default account to use for receipts to when customers pay invoices. Typically a Cash type account.</td>
            <td><?php echo html_pull_down_menu('ar_sales_receipts_account', $cash_chart, $_POST['ar_sales_receipts_account'] ? $_POST['ar_sales_receipts_account'] : AR_SALES_RECEIPTS_ACCOUNT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3">Default account to use for discounts to when customers pay on early schedule with a discount applied. Typically a Income type account.</td>
            <td><?php echo html_pull_down_menu('ar_discount_sales_account', $inc_chart, $_POST['ar_discount_sales_account'] ? $_POST['ar_discount_sales_account'] : AR_DISCOUNT_SALES_ACCOUNT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3">Default account to use for freight charges. Typically an Income type account.</td>
            <td><?php echo html_pull_down_menu('ar_def_freight_acct', $inc_chart, $_POST['ar_def_freight_acct'] ? $_POST['ar_def_freight_acct'] : AR_DEF_FREIGHT_ACCT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3">Default account to use for cash receipts on a customer deposits. Typically a Cash type account.</td>
            <td><?php echo html_pull_down_menu('ar_def_deposit_acct', $cash_chart, $_POST['ar_def_deposit_acct'] ? $_POST['ar_def_deposit_acct'] : AR_DEF_DEPOSIT_ACCT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3">Default account to use for the credit holding for a customer deposits. Typically an Other Current Liabilities type account.</td>
            <td><?php echo html_pull_down_menu('ar_def_dep_liab_acct', $ocl_chart, $_POST['ar_def_dep_liab_acct'] ? $_POST['ar_def_dep_liab_acct'] : AR_DEF_DEP_LIAB_ACCT, ''); ?></td>
        </tr>
    </tbody>
</table>
<table class="ui-widget" style="border-collapse:collapse;width:100%; margin-top: 10px;">
    <thead class="ui-widget-header">
        <tr><th colspan="4"><?php echo TEXT_DEFAULT_GL_ACCOUNTS . ' for Purchase'; ?></th></tr>
    </thead>
    <tbody class="ui-widget-content">
        <tr>
            <td colspan="3"><?php echo CD_03_01_DESC; ?></td>
            <td nowrap="nowrap"><?php echo html_pull_down_menu('ap_default_inventory_account', $inv_chart, $_POST['ap_default_inventory_account'] ? $_POST['ap_default_inventory_account'] : AP_DEFAULT_INVENTORY_ACCOUNT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3"><?php echo CD_03_02_DESC; ?></td>
            <td><?php echo html_pull_down_menu('ap_default_purchase_account', $ap_chart, $_POST['ap_default_purchase_account'] ? $_POST['ap_default_purchase_account'] : AP_DEFAULT_PURCHASE_ACCOUNT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3"><?php echo CD_03_03_DESC; ?></td>
            <td><?php echo html_pull_down_menu('ap_purchase_invoice_account', $cash_chart, $_POST['ap_purchase_invoice_account'] ? $_POST['ap_purchase_invoice_account'] : AP_PURCHASE_INVOICE_ACCOUNT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3"><?php echo CD_03_04_DESC; ?></td>
            <td><?php echo html_pull_down_menu('ap_def_freight_acct', $inv_chart, $_POST['ap_def_freight_acct'] ? $_POST['ap_def_freight_acct'] : AP_DEF_FREIGHT_ACCT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3"><?php echo CD_03_05_DESC; ?></td>
            <td><?php echo html_pull_down_menu('ap_discount_purchase_account', $ap_chart, $_POST['ap_discount_purchase_account'] ? $_POST['ap_discount_purchase_account'] : AP_DISCOUNT_PURCHASE_ACCOUNT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3"><?php echo CD_03_06_DESC; ?></td>
            <td><?php echo html_pull_down_menu('ap_def_deposit_acct', $cash_chart, $_POST['ap_def_deposit_acct'] ? $_POST['ap_def_deposit_acct'] : AP_DEF_DEPOSIT_ACCT, ''); ?></td>
        </tr>
        <tr>
            <td colspan="3"><?php echo CD_03_07_DESC; ?></td>
            <td><?php echo html_pull_down_menu('ap_def_dep_liab_acct', $ocl_chart, $_POST['ap_def_dep_liab_acct'] ? $_POST['ap_def_dep_liab_acct'] : AP_DEF_DEP_LIAB_ACCT, ''); ?></td>
        </tr>
    </tbody>
</table>

<div style="float:right; width: 19%; margin-top: 10px;"><input type="submit" value="Save default GL Account"/></div>
</form>