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


<table class="ui-widget" style="border-collapse:collapse;width:100%; margin-top: 10px;">
    <thead class="ui-widget-header">
        <tr><th colspan="4">Chart of accounts</th></tr>
    </thead>
    <tbody></tbody>
</table>



<div class="upDownFile">
    <div style="float:left; width: 100%; margin-top: 10px;">


        <span style="float: left;"><?php echo 'Upload CSV file' . html_file_field('file_name') . '<br />'; ?></span>
        <span class="button_bg" style="float:left"><a id="import" class="" onclick="submitToDo('import')" href="#">Import Account</a></span>
        <span class="button_bg" style="float: left;"><a href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=chart_of_accounts&amp;action=export', 'SSL') ?>">Sample CSV file</a></span>



        <span class="button_bg" style="float:left">

            <a href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=chart_of_accounts&amp;action=delete_chart', 'SSL') ?>">Delete chart of accounts</a></span> 
        <div style="clear:both"></div>
    </div>
    <div style="clear:both"></div>
</div>


<?php echo $chart_of_accounts_toolbar->build_toolbar(); ?>

<?php
//echo $chart_of_accounts->build_main_html();
$ajax_source_datatable = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_chart_of_acc_data', 'SSL');
?>
<table class="ui-widget" id="chart_of_acc_table" style="float:none;border-collapse:collapse;width:100%">
    <thead class="ui-widget-hewidgetader">
        <tr style="height: 36px;" >            
            <th  class="ui-state-default"> Account ID</th>
            <th  class="ui-state-default"> Account Description</th>
            <th  class="ui-state-default"> Account type(Required)</th>
            <th  class="ui-state-default"> Sub Account</th>
            <th class="ui-state-default">Action</th>
        </tr>
    </thead>

    <tbody>

    </tbody>
    <tfoot>
        <tr>
            <th>Search</th>
            <th>Search</th>
            <th>Search </th>
            <th>Search </th>
            <th>N/A</th>
        </tr>
    </tfoot>

</table>

<script type="text/javascript">
    $(document).ready(function() {
                
        $('#chart_of_acc_table').dataTable({
            "bJQueryUI":true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "GET",
            "sAjaxSource": '<?php echo $ajax_source_datatable ?>',
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "aaSorting": [[0, 'asc']],
            "sPaginationType":"full_numbers",
            "aoColumns": [
                        
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
        }).columnFilter();
            
//        $('#sales_tax_auth_table').dataTable({
//            "bJQueryUI":true,
//            "bProcessing": true,
//            "bServerSide": true,
//            "sServerMethod": "GET",
//            "sAjaxSource": '<?php //echo $sales_tax_auth_table ?>',
//            "iDisplayLength": 10,
//            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
//            "aaSorting": [[0, 'asc']],
//            "sPaginationType":"full_numbers",
//            "aoColumns": [
//                        
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": true, "bSortable": false },
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": false, "bSortable": false }
//            ],
//            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
//        }).columnFilter();
//    
//        $('#purchase_tax_auth_table').dataTable({
//            "bJQueryUI":true,
//            "bProcessing": true,
//            "bServerSide": true,
//            "sServerMethod": "GET",
//            "sAjaxSource": '<?php //echo $purchase_tax_auth_table ?>',
//            "iDisplayLength": 10,
//            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
//            "aaSorting": [[0, 'asc']],
//            "sPaginationType":"full_numbers",
//            "aoColumns": [
//                        
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": true, "bSortable": false },
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": false, "bSortable": false }
//            ],
//            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
//        }).columnFilter();
//        
//        $('#sales_tax_rates_table').dataTable({
//            "bJQueryUI":true,
//            "bProcessing": true,
//            "bServerSide": true,
//            "sServerMethod": "GET",
//            "sAjaxSource": '<?php //echo $sales_tax_rate_table ?>',
//            "iDisplayLength": 10,
//            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
//            "aaSorting": [[0, 'asc']],
//            "sPaginationType":"full_numbers",
//            "aoColumns": [
//                        
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": true, "bSortable": false },
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": false, "bSortable": false }
//            ],
//            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
//        }).columnFilter();
//        
//        $('#purchase_tax_rates_table').dataTable({
//            "bJQueryUI":true,
//            "bProcessing": true,
//            "bServerSide": true,
//            "sServerMethod": "GET",
//            "sAjaxSource": '<?php //echo $purchase_tax_rate_table ?>',
//            "iDisplayLength": 10,
//            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
//            "aaSorting": [[0, 'asc']],
//            "sPaginationType":"full_numbers",
//            "aoColumns": [
//                        
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": true, "bSortable": false },
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": true, "bSortable": true },
//                { "bVisible": true, "bSearchable": false, "bSortable": false }
//            ],
//            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
//        }).columnFilter();
    });
</script>