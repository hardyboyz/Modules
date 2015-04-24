<script type="text/javascript">
    //    $(document).ready(function() {
    //                
    //        $('#example').dataTable({
    //            "bJQueryUI":true,
    //            "bProcessing": true,
    //            "bServerSide": true,
    //            "sServerMethod": "GET",
    //            "sAjaxSource": '<?php echo $ajax_source_datatable ?>',
    //            "iDisplayLength": 10,
    //            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    //            "aaSorting": [[0, 'asc']],
    //            "sPaginationType":"full_numbers",
    //            "aoColumns": [
    //                        
    //                { "bVisible": true, "bSearchable": true, "bSortable": true },                
    //                { "bVisible": true, "bSearchable": false, "bSortable": false }, 
    //                { "bVisible": true, "bSearchable": false, "bSortable": false }
    //
    //                        
    //            ]
    //        });
    //    });
    //    


    function deleteRecuring(id,obj){
        
           
        url = root_path_axiz+"/index.php?module=ucbooks&page=recurring_invoice_list&action_type=delete";
        postData = {"id":id}
        $.post(url,postData,
        function(data){
            if(data == 'success'){
                //$("#"+item_id).fadeOut().remove();
               $(obj).parent("td").parent("tr").fadeOut().remove();
            }
            else{
                alert(data);
            }
        }
        ,"text"
        );
    }


    $(document).ready(function() {
        $.datepicker.regional[""].dateFormat = 'yy-mm-dd';
        $.datepicker.setDefaults($.datepicker.regional['']);
        otable = $('#example').dataTable( {
            "aaSorting": [[2,'desc']],
            "bStateSave": true,
            "bProcessing": true,
            "bServerSide": true,
            "bFilter": false,
            "bJQueryUI": true,
            "aoColumnDefs": [
                { 
                    "bSortable": false,
                    "aTargets": [ 0,1,2,3,4] 
                },
                //                { 
                //                    "bVisible": false, 
                //                    "aTargets": [ 2 ] 
                //                },
            ],

            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>',
            "sPaginationType": "full_numbers",
            "sAjaxSource": "<?php echo $ajax_source_datatable ?>",
            "aoColumns": [ 
                //                { 'mDataProp':'inv_info.invoice_number','type':'text' },
                //                { 'mDataProp':'inv_info.user_number','type':'text' },
                //                { 'mDataProp':'inv_info.contact_id','type':'text' },
                { 'mDataProp':'inv_info.description','type':'text' },
                { 'mDataProp':'inv_info.total_amount','type':'text' },
                { 'mDataProp':'next_exec_date','type':'text' },
               
                { 'mDataProp':'recurring_interval','type':'text' },
                { 'mDataProp':'edit_delete_link','type': 'text'}
            ],
                 "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "mColumns": [0,1,2,3]
                    },
                    {
                        "sExtends": "csv",
                        "mColumns": [0, 1,2,3]
                    },
                    {
                        "sExtends": "xls",
                        "mColumns": [0, 1,2,3]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2,3]
                    },
                    {
                        "sExtends": "print",
                        "mColumns": [0, 1, 3,2]
                    },
                ]
            }
        } ).columnFilter({
            aoColumns: [ 
                null
            ]
        });
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
//  Path: /modules/ucbooks/pages/status/template_main.php
//
echo html_form('status', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['save']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;


if (count($extra_toolbar_buttons) > 0)
    foreach ($extra_toolbar_buttons as $key => $value)
        $toolbar->icon_list[$key] = $value;
switch (JOURNAL_ID) {
    case 2: $toolbar->add_help('');
        break;
    case 3: $toolbar->add_help('07.02.03.04');
        break;
    case 4: $toolbar->add_help('07.02.03.04');
        break;
    case 6: $toolbar->add_help('07.02.03.04');
        break;
    case 7: $toolbar->add_help('07.02.03.04');
        break;
    case 9: $toolbar->add_help('07.03.03.04');
        break;
    case 10: $toolbar->add_help('07.03.03.04');
        break;
    case 12: $toolbar->add_help('07.03.03.04');
        break;
    case 13: $toolbar->add_help('07.03.03.04');
        break;
    case 18: $toolbar->add_help('');
        break;
    case 20: $toolbar->add_help('');
        break;
}
if ($search_text)
    $toolbar->search_text = $search_text;
$toolbar->search_period = $acct_period;
echo $toolbar->build_toolbar();
// Build the page
?>
<h1>Recurring Invoice Manager</h1>



<div id="table-content" style=" margin: 0 auto">
    <table class="ui-widget" id="example" style="border-collapse:collapse;width:100%">
    <!--    <thead class="ui-widget-header">
            <tr><?php echo $list_header; ?></tr>
        </thead>-->
        <thead class="ui-widget-hewidgetader">   
            <tr>
    <!--                                    <th width="12%"><?php echo $type == 'purchase' ? 'PUR' : 'IVN'; ?> No</th>
                                        <th width="12%">Manual No</th>
                                        <th width="25%">Contact</th>-->
                <th width="28%">Description</th>
                <th width="8%">Amount</th>
                <th width="9%">Next Execution Date</th>
<!--                <th width="9%">Parent</th>-->
                <th width="6%">Interval</th>
                <th width="12%">Edit/Delete</th>
            </tr>
        </thead>

        <tbody>

        </tbody>

    </table>
</div>

<br/>






