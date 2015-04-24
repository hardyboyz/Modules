

<table class="ui-widget" style="border-collapse:collapse;width:100%; margin-top: 10px;">
    <thead class="ui-widget-header">
        <tr><th colspan="4">Sales Tax Authorities</th></tr>
    </thead>
    <tbody>

        <?php
        //echo $chart_of_accounts->build_main_html();
        $sales_tax_auth_table = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_sales_tax_auth_table&type=c', 'SSL');
        ?>


    <table class="ui-widget" id="sales_tax_auth_table" style="float:none;border-collapse:collapse;width:100%">
        <div id="tb_main_0" class="action_btn" style="border:0px;">
            <span class="button_bg" onclick="loadPopUp('tax_auths_new', 0)" style="cursor:pointer;">+New</span>
            <span id="tb_icon_help" class="button_bg" onclick="window.open('index.php?module=uchelp&page=main&idx=07.08.03.01','help','width=1150,height=600,resizable=1,scrollbars=1,top=100,left=100')" style="cursor:pointer;">Help</span>
        </div>
        <thead class="ui-widget-hewidgetader">
            <tr style="height: 36px;" >            
                <th  class="ui-state-default">ID</th>
                <th  class="ui-state-default">Short Name</th>
                <th  class="ui-state-default">Description</th>
                <th  class="ui-state-default"> GL Account ID</th>
                <th  class="ui-state-default">Tax Rate (percent)</th>
                <th class="ui-state-default">Action</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>Search</th>
                <th>Search</th>
                <th>Search</th>
                <th>Search </th>
                <th>Search </th>
                <th>N/A</th>
            </tr>
        </tfoot>

    </table>

</tbody>
</table>
<br>

<table class="ui-widget" style="border-collapse:collapse;width:100%; margin-top: 10px;">
    <thead class="ui-widget-header">
        <tr><th colspan="4">Purchase Tax Authorities</th></tr>
    </thead>
    <tbody>

        <?php
        //echo $chart_of_accounts->build_main_html();
        $purchase_tax_auth_table = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_sales_tax_auth_table&type=v', 'SSL');
        ?>


    <table class="ui-widget" id="purchase_tax_auth_table" style="float:none;border-collapse:collapse;width:100%">
        <div id="tb_main_0" class="action_btn" style="border:0px;">
            <span class="button_bg" onclick="loadPopUp('tax_auths_vend_new', 0)" style="cursor:pointer;">+New</span>

        </div>
        <thead class="ui-widget-hewidgetader">
            <tr style="height: 36px;" >            
                <th  class="ui-state-default">ID</th>
                <th  class="ui-state-default">Short Name</th>
                <th  class="ui-state-default">Description</th>
                <th  class="ui-state-default"> GL Account ID</th>
                <th  class="ui-state-default">Tax Rate (percent)</th>
                <th class="ui-state-default">Action</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>Search</th>
                <th>Search</th>
                <th>Search</th>
                <th>Search </th>
                <th>Search </th>
                <th>N/A</th>
            </tr>
        </tfoot>

    </table>

</tbody>
</table>

<table class="ui-widget" style="border-collapse:collapse;width:100%; margin-top: 10px;">
    <thead class="ui-widget-header">
        <tr><th colspan="4">Sales Tax Rates</th></tr>
    </thead>
    <tbody>

        <?php
        //echo $chart_of_accounts->build_main_html();
        $sales_tax_rate_table = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_tax_rate_table&type=c', 'SSL');
        ?>


    <table class="ui-widget" id="sales_tax_rates_table" style="float:none;border-collapse:collapse;width:100%">
        <div id="tb_main_0" class="action_btn" style="border:0px;">
            <div id="tb_main_0" class="action_btn" style="border:0px;">
                <span class="button_bg" onclick="loadPopUp('tax_rates_new', 0)" style="cursor:pointer;">+New</span>
                <span id="tb_icon_help" class="button_bg" onclick="window.open('index.php?module=uchelp&page=main&idx=07.08.03.02','help','width=1150,height=600,resizable=1,scrollbars=1,top=100,left=100')" style="cursor:pointer;">Help</span>
            </div>
        </div>
        <thead class="ui-widget-hewidgetader">
            <tr style="height: 36px;" >            
                <th  class="ui-state-default">ID</th>
                <th  class="ui-state-default">Short Name</th>
                <th  class="ui-state-default">Description</th>
                <th  class="ui-state-default"> Total Tax (percent)</th>
                <th  class="ui-state-default">Tax Freight</th>
                <th class="ui-state-default">Action</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>Search</th>
                <th>Search</th>
                <th>Search</th>
                <th>Search </th>
                <th>Search </th>
                <th>N/A</th>
            </tr>
        </tfoot>

    </table>

</tbody>
</table>

<table class="ui-widget" style="border-collapse:collapse;width:100%; margin-top: 10px;">
    <thead class="ui-widget-header">
        <tr><th colspan="4">Purchase Tax Rates</th></tr>
    </thead>
    <tbody>

        <?php
        //echo $chart_of_accounts->build_main_html();
        $purchase_tax_rate_table = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=ajax&op=load_tax_rate_table&type=v', 'SSL');
        ?>


    <table class="ui-widget" id="purchase_tax_rates_table" style="float:none;border-collapse:collapse;width:100%">
        <div id="tb_main_0" class="action_btn" style="border:0px;">
            <div id="tb_main_0" class="action_btn" style="border:0px;">
                <span class="button_bg" onclick="loadPopUp('tax_rates_vend_new', 0)" style="cursor:pointer;">+New</span>
            </div>
        </div>
        <thead class="ui-widget-hewidgetader">
            <tr style="height: 36px;" >            
                <th  class="ui-state-default">ID</th>
                <th  class="ui-state-default">Short Name</th>
                <th  class="ui-state-default">Description</th>
                <th  class="ui-state-default"> Total Tax (percent)</th>
                <th  class="ui-state-default">Tax Freight</th>
                <th class="ui-state-default">Action</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>Search</th>
                <th>Search</th>
                <th>Search</th>
                <th>Search </th>
                <th>Search </th>
                <th>N/A</th>
            </tr>
        </tfoot>

    </table>

</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
   $('#sales_tax_auth_table').dataTable({
            "bJQueryUI":true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "GET",
            "sAjaxSource": '<?php echo $sales_tax_auth_table ?>',
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "aaSorting": [[0, 'asc']],
            "sPaginationType":"full_numbers",
            "aoColumns": [
                        
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
        }).columnFilter();
    
        $('#purchase_tax_auth_table').dataTable({
            "bJQueryUI":true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "GET",
            "sAjaxSource": '<?php echo $purchase_tax_auth_table ?>',
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "aaSorting": [[0, 'asc']],
            "sPaginationType":"full_numbers",
            "aoColumns": [
                        
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
        }).columnFilter();
        
        $('#sales_tax_rates_table').dataTable({
            "bJQueryUI":true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "GET",
            "sAjaxSource": '<?php echo $sales_tax_rate_table ?>',
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "aaSorting": [[0, 'asc']],
            "sPaginationType":"full_numbers",
            "aoColumns": [
                        
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
        }).columnFilter();
        
        $('#purchase_tax_rates_table').dataTable({
            "bJQueryUI":true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "GET",
            "sAjaxSource": '<?php echo $purchase_tax_rate_table ?>',
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "aaSorting": [[0, 'asc']],
            "sPaginationType":"full_numbers",
            "aoColumns": [
                        
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
        }).columnFilter(); 
});
</script>