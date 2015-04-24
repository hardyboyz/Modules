<script type="text/javascript">
    var asInitVals = new Array();
    $(document).ready(function() {

        
        $('#example').dataTable({
            "bJQueryUI":true,
            "bStateSave": false,
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
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false },  
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sPaginationType": "full_numbers",
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>',
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "mColumns": [0,1,2,3, 4,5,6,7]
                    },
                    {
                        "sExtends": "csv",
                        "mColumns": [0, 1,2,3, 4,5,6,7]
                    },
                    {
                        "sExtends": "xls",
                        "mColumns": [0, 1,2,3, 4,5,6,7]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1,2,3, 4,5,6,7]
                    },
                    {
                        "sExtends": "print",
                        "mColumns": [0, 1, 3,2,4,5,6,7]
                    },
                ]
            }
        }).columnFilter();
        
        
    });
    
</script>
<?php
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['save']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;


$toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=transfer&amp;action=new&jID=' . JOURNAL_ID.'&sub_jID='.SUB_JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Transfer');

$toolbar->add_help('07.04.02.01');


echo $toolbar->build_toolbar();
// Build the page
?>
<h1><?php echo PAGE_TITLE; ?></h1>



<div id="table-content" style=" margin: 0 auto">
    <table class="ui-widget" id="example" style="border-collapse:collapse;width:100%">

        <thead class="ui-widget-hewidgetader">   
            <tr style="height: 36px; text-align: center !important;" > 


                <th class="ui-state-default">Date</th>
                <th class="ui-state-default">Reference Number</th>
                <th class="ui-state-default">Reason for Transfer</th>
                 <th class="ui-state-default">Transfer Account 	</th>
                <th class="ui-state-default">Transfer From</th>
                <th class="ui-state-default">Transfer To</th>

                <th class="ui-state-default">Edit</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
            <tr>

                <th>Search</th>
                <th>Search</th>
                <th>Search</th>
                <th>Search</th>


                <th>Search</th>
                <th>Search</th>


                <th>N/A</th>
            </tr>
        </tfoot>

    </table>
</div>
