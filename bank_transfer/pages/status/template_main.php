<script type="text/javascript">
    var asInitVals = new Array();
    $(document).ready(function() {
               

               
               
               
        $('#example').dataTable({
            "bJQueryUI":true,
            "bStateSave": true,
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
                { "bVisible": false, "bSearchable": false, "bSortable": false },
                { "bVisible": false, "bSearchable": false, "bSortable": false },
                { "bVisible": true, "bSearchable": true, "bSortable": false }, 
                { "bVisible": false, "bSearchable": false, "bSortable": false }, 
                { "bVisible": false, "bSearchable": false, "bSortable": false }, 
                { "bVisible": true, "bSearchable": false, "bSortable": false }
            ],
            "sPaginationType": "full_numbers",
            "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>',
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
                        "mColumns": [0, 1, 2,3]
                    },
                ]
            }
        }).columnFilter();
        
        
        
    });
    
</script>
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
//  Path: /modules/bank_transfer/pages/status/template_main.php
//
//function get_plan($company_code) {
//    if (!mysql_select_db(LANDING_DB))
//        return false;
//    $user_qry = "select * from user_account where company_code = '" . $company_code . "';";
//    $user_result = mysql_query($user_qry);
//    $user_array = mysql_fetch_assoc($user_result);
//
//    $retistered_plan = $user_array['plan'];
//    $query = "SELECT * FROM plan_override WHERE user_id = " . $user_array['id'];
//    $result = mysql_query($query);
//    $array = mysql_fetch_assoc($result);
//
//    if (is_array($array) && count($array)) {
//        $override_plan = $array['plan'];
//    }
//    if (!mysql_select_db(APP_DB)){
//        return false;
//    }
//    if ($override_plan)
//        return $override_plan;
//    else
//        return $retistered_plan;
//}

echo html_form('status', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['save']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;



//This is for "add sales quotation" restriction  coding by 4axiz(apu) date: 21-july-2013
//$get_plan = get_plan(COMPANY_CODE);
//if ($get_plan == SET_PLAN) {
//    $start_date = date("Y-m-01 00:00:00");
//    $end_date = date("Y-m-t 00:00:00");
//    if (TRANSACTION_LIMIT_TYPE == "each_type") {
//       $query = "SELECT COUNT(*) as row_count FROM journal_main  where post_date BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND journal_id = ".$_GET['jID'];
//    } else if (TRANSACTION_LIMIT_TYPE == "all_type") {
//        $query = "SELECT COUNT(*) as row_count FROM journal_main  where post_date BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
//    }
//    $query_res = mysql_query($query);
//    $query_count = mysql_fetch_object($query_res);
//    $query_count = $query_count->row_count;
//    if ($query_count >= TRANSACTION_LIMIT) {
//        $restriction = true;
//    } else {
//        $restriction = false;
//    }
//}



if (JOURNAL_ID == 9) {

    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Quotation');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Quotation');
    }
} else if (JOURNAL_ID == 10) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Order');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Order');
    }
} else if (JOURNAL_ID == 32) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Order');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Order');
    }
} else if (JOURNAL_ID == 12) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Sales/Invoice');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Sales/Invoice');
    }
} else if (JOURNAL_ID == 13) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Credit Memo');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Credit Memo');
    }
} else if (JOURNAL_ID == 3) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Request for Quote');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Request for Quote');
    }
} else if (JOURNAL_ID == 4) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Purchase Order');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Purchase Order');
    }
} else if (JOURNAL_ID == 6) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Purchase');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Purchase');
    }
} else if (JOURNAL_ID == 7) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Credit Memo');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Credit Memo');
    }
} else if (JOURNAL_ID == 2 && SUB_JOURNAL_ID == 0) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Bank Transfer');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=journal', 'SSL') . '\'"', 2, '+ New Bank Transfer');
    }
} else if (JOURNAL_ID == 2 && SUB_JOURNAL_ID == 1) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Bank Transfer');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=journal&jID=' . JOURNAL_ID . ' &sub_jID=' . SUB_JOURNAL_ID . '', 'SSL') . '\'"', 2, '+ New Bank Transfer');
    }
} else if (JOURNAL_ID == 2 && SUB_JOURNAL_ID == 2) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Bank Transfer');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=spend_receive_money&jID=' . JOURNAL_ID . '&sub_jID='.SUB_JOURNAL_ID.'', 'SSL') . '\'"', 2, '+ New Received Money');
    }
} else if (JOURNAL_ID == 2 && SUB_JOURNAL_ID == 3) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Bank Transfer');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=receive_spend_money&jID=' . JOURNAL_ID . '&sub_jID='.SUB_JOURNAL_ID.'', 'SSL') . '\'"', 2, '+ New Spend Money');
    }
} else if (JOURNAL_ID == 33) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Bank Transfer');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=journal&jID=' . JOURNAL_ID . ' &sub_jID=' . SUB_JOURNAL_ID . '', 'SSL') . '\'"', 2, '+ New Bank Transfer');
    }
} else if (JOURNAL_ID == 34) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Bank Transfer');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=spend_receive_money&jID=' . JOURNAL_ID . '', 'SSL') . '\'"', 2, '+ New Received Money');
    }
} else if (JOURNAL_ID == 35) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Bank Transfer');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=receive_spend_money&jID=' . JOURNAL_ID . '', 'SSL') . '\'"', 2, '+ New Spend Money');
    }
} else if (JOURNAL_ID == 18) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Customer Receipt');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=bills&amp;jID=' . JOURNAL_ID . '&amp;type=c', 'SSL') . '\'"', 2, '+ New Customer Receipt');
    }
} else if (JOURNAL_ID == 20) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Pay Bill');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=bills&amp;jID=' . JOURNAL_ID . '&amp;type=v', 'SSL') . '\'"', 2, '+ New Pay Bill');
    }
} else {
    $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2);
}





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
    case 10:
    case 32: $toolbar->add_help('07.03.03.04');
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
<h1><?php echo PAGE_TITLE; ?></h1>



<div id="table-content" style=" margin: 0 auto">
    <table class="ui-widget" id="example" style="border-collapse:collapse;width:100%">
    <!--    <thead class="ui-widget-header">
            <tr><?php echo $list_header; ?></tr>
        </thead>-->
        <thead class="ui-widget-hewidgetader">   
            <tr style="height: 36px;" > 
                <th class="ui-state-default" style="padding: 8px 0 0 0;" >Date</th>
<?php if ($_GET['jID'] == 9) { ?>
                    <th class="ui-state-default">Quote Number</th>
<?php } else if ($_GET['jID'] == 10) { ?>
                    <th class="ui-state-default">Sales Orders Number</th>
                <?php } else if ($_GET['jID'] == 12) { ?>
                    <th class="ui-state-default">Invoice Number</th>

                <?php } else if ($_GET['jID'] == 3) { ?>
                    <th class="ui-state-default">Quotes Number</th>
<?php } else if ($_GET['jID'] == 4) { ?>
                    <th class="ui-state-default">Purchase Orders Number</th>

                <?php } else if ($_GET['jID'] == 6) { ?>
                    <th class="ui-state-default">Purchase/Receive Number</th>
<?php } else { ?>
              <th class="ui-state-default">Transaction No.</th>
                <?php } ?>
                <th class="ui-state-default">Description</th>
                <th class="ui-state-default">Reference</th>
                <th class="ui-state-default">Status</th>
                <th class="ui-state-default">Amount</th> 
                <th class="ui-state-default">Currencies code</th>
                <th class="ui-state-default">Currencies value</th>
                <th class="ui-state-default">Edit</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>Search</th>
                <th>Search</th>
                <th>Search </th>
                <th>N/A </th>
                <th>N/A</th>
                <th>Search </th>
                <th>Search </th>
                <th>Search</th>
                <th>N/A</th>
            </tr>
        </tfoot>

    </table>
</div>

<br/>



