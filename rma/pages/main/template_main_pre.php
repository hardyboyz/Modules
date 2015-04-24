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
                { "bVisible": false, "bSearchable": false, "bSortable": false },                   
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>'
        }).columnFilter();
    });
    
</script>
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
//  Path: /modules/rma/pages/main/template_main.php
//
echo html_form('rma', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['save']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
if ($security_level > 1) $toolbar->add_icon('new', 'onclick="submitToDo(\'new\')"', $order = 0,'+ New RMA');
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
$toolbar->add_help('');
if ($search_text) $toolbar->search_text = $search_text;
echo $toolbar->build_toolbar(); 
?>
<h1><?php echo BOX_RMA_MAINTAIN; ?></h1>

<div id="table-content" style=" margin: 0 auto">
<table class="ui-widget" id="example" style="border-collapse:collapse;width:100%">
<!--    <thead class="ui-widget-header">
        <tr><?php echo $list_header; ?></tr>
    </thead>-->
    <thead class="ui-widget-hewidgetader">   
        <tr style="height: 36px;" > 
            <th class="ui-state-default" style="padding: 8px 0 0 0;" ><?php echo TEXT_RMA_ID ?></th>
            <th class="ui-state-default"><?php echo TEXT_CREATION_DATE ?></th>
            <th class="ui-state-default"><?php echo TEXT_CALLER_NAME ?></th>
            <th class="ui-state-default"><?php echo TEXT_INVOICE ?></th>
            <th class="ui-state-default"><?php echo TEXT_STATUS ?></th>
            <th class="ui-state-default"><?php echo TEXT_CLOSED ?></th> 
            <th class="ui-state-default">Attachments</th>
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
            <th>Search</th>
            <th>Search </th>
            <th>N/A </th>
            
           <th>N/A</th>
        </tr>
    </tfoot>

</table>
</div>
<br/>



