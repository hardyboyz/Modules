<script type="text/javascript">
    $(document).ready(function() {
                
        $('#example').dataTable({
            "bJQueryUI":true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "GET",
            "sAjaxSource": '<?php echo $ajax_source_datatable?>',
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "aaSorting": [[0, 'asc']],
            "sPaginationType":"full_numbers",
            "aoColumns": [
                        
                { "bVisible": true, "bSearchable": true, "bSortable": true },                
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>',
             "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "mColumns": [0,1,2,3, 4,5,6]
                    },
                    {
                        "sExtends": "csv",
                        "mColumns": [0, 1,2,3, 4,5,6]
                    },
                    {
                        "sExtends": "xls",
                        "mColumns": [0, 1,2,3, 4,5,6]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2,3, 4,5,6]
                    },
                    {
                        "sExtends": "print",
                        "mColumns": [0, 1, 2,3,4,5,6]
                    },
                ]
            }
        }).columnFilter();
    });
    
</script>
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
//  Path: /modules/inventory/pages/main/template_main.php
//
echo html_form('inventory', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
echo html_hidden_field('todo', '')   . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['save']['show']     = false;
$toolbar->icon_list['print']['show']    = false;
if ($security_level > 1) $toolbar->add_icon('new', 'onclick="submitToDo(\'new\')"', $order = 0);
$toolbar->icon_list['new']['text'] = "+ New Inventory"; 
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
$toolbar->add_help('07.04.01');
if ($search_text) $toolbar->search_text = $search_text;
echo $toolbar->build_toolbar(); 
?>
<h1><?php echo MENU_HEADING_INVENTORY; ?></h1>



<div id="table-content" style=" margin: 0 auto">
<table class="ui-widget" id="example" style="border-collapse:collapse;width:100%">
<!--    <thead class="ui-widget-header">
        <tr><?php echo $list_header; ?></tr>
    </thead>-->
    <thead class="ui-widget-hewidgetader">   
        <tr style="height: 36px;" > 
            <th class="ui-state-default" style="padding: 8px 0 0 0;" ><?php echo TEXT_SKU ?></th>
            <th class="ui-state-default"><?php echo TEXT_INACTIVE ?></th>
            <th class="ui-state-default"><?php echo TEXT_DESCRIPTION ?></th>
            <th class="ui-state-default"><?php echo INV_HEADING_QTY_ON_HAND ?></th>
            <th class="ui-state-default"><?php echo INV_HEADING_QTY_ON_SO ?></th>
            <th class="ui-state-default"><?php echo INV_HEADING_QTY_ON_ALLOC ?></th>
            <th class="ui-state-default"><?php echo INV_HEADING_QTY_ON_ORDER ?></th>
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
            <th>Search</th>
            <th>Search </th>
            <th>Search </th>
            
           <th>N/A</th>
        </tr>
    </tfoot>
</table>
</div>

<br/>







