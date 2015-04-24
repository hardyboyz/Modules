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
//  Path: /modules/contacts/pages/main/template_detail.php
//
echo html_form('contacts', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"', true) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('f0', $f0) . chr(10);
echo html_hidden_field('id', $cInfo->id) . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
echo html_hidden_field('del_crm_note', '') . chr(10);
echo html_hidden_field('payment_id', '') . chr(10);
// customize the toolbar actions
if ($action == 'properties') {
    $toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=contacts&page=main&type=c&list=1&f0=0&sf=&so=&"';
    $toolbar->icon_list['save']['show'] = false;
} else {
    if ($_GET['type'] == 'e') {
        $toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=contacts&page=main&type=e&list=1&f0=1&sf=&so=&"';
    } else if ($_GET['type'] = 'b') {
        $toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=contacts&page=main&type=b&list=1"';
    } else {
        $toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=contacts&page=main&type=c&list=1&f0=0&sf=&so=&"';
    }

    if ((!$cInfo->id && $security_level < 2) || ($cInfo->id && $security_level < 3)) {
        $toolbar->icon_list['save']['show'] = false;
    } else {
        $toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
    }
}
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
if (!$cInfo->help == '')
    $toolbar->add_help($cInfo->help);
?>
<h1><?php echo PAGE_TITLE; ?></h1>
<table  class="ui-widget" style="width:100%;">
    <tbody>
        <tr>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Account Number</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Currency Code</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Customer Name</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Bus. Reg No.</td>
                        <td width="4%"> :</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">&nbsp;</td>
                        <td width="4%">&nbsp;</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">GST No.</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:90%;">
                    <tr>
                        <td width="44%">Address</td>
                        <td width="5%">:</td>
                        <td width="51%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Credit Limits</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>

        </tr>

        <tr>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">&nbsp;</td>
                        <td width="4%">&nbsp;</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Credit Terms</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:90%;">
                    <tr>
                        <td width="44%">&nbsp;</td>
                        <td width="5%">&nbsp;</td>
                        <td width="51%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Disbursement Limits</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:90%;">
                    <tr>
                        <td width="44%">Tell. No.</td>
                        <td width="5%">:</td>
                        <td width="51%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Disbursement Terms</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Fax No.</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Email Address</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Date.</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Branch</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Job No.</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="40%">Job Ref No.</td>
                        <td width="4%">:</td>
                        <td width="56%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'size="33"', false); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="18%">Job Description</td>
                        <td width="3%">:</td>
                        <td width="79%"><?php echo html_input_field('contact_first', $cInfo->contact_first, 'style="width:88%"size="33"', false); ?></td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td colspan="2">
                <table class="ui-widget" style="width:100%;">
                    <tr>
                        <td width="18%">Type</td>
                        <td width="3%">:</td>
                        <td width="79%"><?php echo html_pull_down_menu('ship_carrier', array('Sea', 'Air', 'Crew', 'Immigral', 'Vessel', 'Other'), '', 'style="width:88%"'); ?></td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Cost</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="ui-widget border" border="2" style="width:100%;">
                    <tr>
                        <th class="border">No.</th>   
                        <th class="border">C.Code</th>   
                        <th class="border">Indicator</th>   
                        <th class="border">Description</th>   
                        <th class="border">Supplier ID</th>   
                        <th class="border">Name</th>   
                        <th class="border">Inv. No.</th>   
                        <th class="border">Curr</th>   
                        <th class="border">Amount</th>   
                        <th class="border">Exc.Rates</th>   
                        <th class="border">Amount</th>   
                        <th class="border">GL.No</th>   
                        <th class="border">Attachment</th> 
                    </tr>
                    <tr>
                        <td class="border">1</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">XXX</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="border">2</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">XXY</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="border">3</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">XXZ</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="border">4</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">XZZ</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                        <td class="border" colspan="3">&nbsp;</td>
                        <td class="border" colspan="2">Total (a)</td>
                        <td class="border">&nbsp;</td>
                        <td class="border">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<style>
    .border{
        border: 2px solid #333333 !important;
    }

</style>

<div class="bottom_btn">
    <?php
    echo $toolbar->build_toolbar();
    ?>
</div>
</div>
</form>

