<script type="text/javascript">
    var asInitVals = new Array();
    $(document).ready(function() {
        var journalID = '<?php echo $_GET['jID']; ?>';
        var sub_journalID = '<?php echo $_GET['sub_jID']; ?>';
        var condition = false;
        if('<?php echo $_GET['jID'] ?>' == 6){
            condition  = true;
        }else if('<?php echo $_GET['jID'] ?>' == 12 && sub_journalID == 0){
            condition = true;
        }else if(journalID == 36 ||(journalID==12 && sub_journalID==1)){
            condition = true;
        }else if('<?php echo $_GET['jID'] ?>' == 18){
            condition = 'customer_receipt';   
        }else if('<?php echo $_GET['jID'] ?>' == 32){
            condition = 'deliveryOrder';   
        }else if('<?php echo $_GET['jID'] ?>' == 2){
            condition = 'generalJournal';   
        }
        if(condition==true){
        
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
                    { "bVisible": true, "bSearchable": false, "bSortable": false }, 
                    { "bVisible": true, "bSearchable": false, "bSortable": false }, 
                    { "bVisible": false, "bSearchable": false, "bSortable": false }, 
                    { "bVisible": false, "bSearchable": false, "bSortable": false }, 
                    { "bVisible": false, "bSearchable": true, "bSortable": true }, 
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
        
        }else if(condition =='customer_receipt'){
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
                    { "bVisible": true, "bSearchable": false, "bSortable": false }, 
                    { "bVisible": true, "bSearchable": true, "bSortable": false }, 
                    { "bVisible": true, "bSearchable": true, "bSortable": true }, 
                    { "bVisible": true, "bSearchable": true, "bSortable": true }, 
                    { "bVisible": true, "bSearchable": false, "bSortable": false }
                ],
                "sPaginationType": "full_numbers",
                "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>',
                "oTableTools": {
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "mColumns": [0,1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "csv",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "xls",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "pdf",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "print",
                            "mColumns": [0, 1, 3,2,4,5,6,7,8]
                        },
                    ]
                }
            }).columnFilter();
        }else if(condition =='deliveryOrder'){
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
                        
                    { "bVisible": true, "bSearchable": false, "bSortable": false },   
                    { "bVisible": true, "bSearchable": true, "bSortable": true },   
                    { "bVisible": true, "bSearchable": true, "bSortable": true },   
                    { "bVisible": true, "bSearchable": true, "bSortable": true },
                    { "bVisible": true, "bSearchable": true, "bSortable": false },
                    { "bVisible": true, "bSearchable": true, "bSortable": false },
                    { "bVisible": true, "bSearchable": true, "bSortable": false },
                    { "bVisible": true, "bSearchable": true, "bSortable": false },  
                    { "bVisible": true, "bSearchable": false, "bSortable": false }, 
                    { "bVisible": true, "bSearchable": true, "bSortable": false }, 
                    { "bVisible": true, "bSearchable": true, "bSortable": true }, 
                    { "bVisible": true, "bSearchable": false, "bSortable": false }
                ],
                "sPaginationType": "full_numbers",
                "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>',
                "oTableTools": {
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "mColumns": [0,1,2,3, 4,5,6,7,8,9]
                        },
                        {
                            "sExtends": "csv",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8,9]
                        },
                        {
                            "sExtends": "xls",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8,9]
                        },
                        {
                            "sExtends": "pdf",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8,9]
                        },
                        {
                            "sExtends": "print",
                            "mColumns": [0, 1, 3,2,4,5,6,7,8,9]
                        },
                    ]
                }
               
            }).columnFilter();
            $('div.ui-toolbar:first').append('Post to invoice : <a href="javascript: doPostToInv()"><button type="button">POST</button></a>');
            //  $('div.ui-toolbar:first').append('Post to invoice automatically ? : <a href="index.php?module=ucbooks&page=status&jID=32&list=1&auto_post=1"><button type="button">ON</button></a><a href="index.php?module=ucbooks&page=status&jID=32&list=1&auto_post=0"><button type="button">OFF</button></a>');
        }else if(condition =='generalJournal'){
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
                    { "bVisible": true, "bSearchable": false, "bSortable": false }, 
                    { "bVisible": true, "bSearchable": false, "bSortable": false }
                ],
                "sPaginationType": "full_numbers",
                "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>',
                "oTableTools": {
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "mColumns": [0,1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "csv",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "xls",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "pdf",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "print",
                            "mColumns": [0, 1, 3,2,4,5,6,7,8]
                        },
                    ]
                }
            }).columnFilter();
        }else{
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
                    { "bVisible": true, "bSearchable": false, "bSortable": false }, 
                    { "bVisible": true, "bSearchable": true, "bSortable": false }, 
                    { "bVisible": true, "bSearchable": true, "bSortable": true }, 
                    { "bVisible": true, "bSearchable": false, "bSortable": false }
                ],
                "sPaginationType": "full_numbers",
                "sDom": '<"clear">T<"H"Cr><"clear">lfrt<"F"ip>',
                "oTableTools": {
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "mColumns": [0,1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "csv",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "xls",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "pdf",
                            "mColumns": [0, 1,2,3, 4,5,6,7,8]
                        },
                        {
                            "sExtends": "print",
                            "mColumns": [0, 1, 3,2,4,5,6,7,8]
                        },
                    ]
                }
            }).columnFilter();
        }
        
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
//echo html_form('status', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
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
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Quotation');
    }
} else if (JOURNAL_ID == 10) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Order');
    } else {
      //  if (isset($_SESSION['set_security']) && $_SESSION['set_security'] == 'yes')
       //     $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Job Sheet');
      //  else
            $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Order');
    }
} else if (JOURNAL_ID == 32) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Order');
    } else {

        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Order');
    }
} else if (JOURNAL_ID == 12 && SUB_JOURNAL_ID == 0) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Sales/Invoice');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID . '&sub_jID=' . SUB_JOURNAL_ID . '', 'SSL') . '\'"', 2, '+New Sales/Invoice');
    }
} else if (JOURNAL_ID == 36 || (JOURNAL_ID == 12 && SUB_JOURNAL_ID == 1)) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New POS');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID . '&sub_jID=' . SUB_JOURNAL_ID . '', 'SSL') . '\'"', 2, '+New POS');
    }
} else if (JOURNAL_ID == 13 && SUB_JOURNAL_ID == 0) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Credit Memo');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Credit Memo');
    }
} else if (JOURNAL_ID == 13 && SUB_JOURNAL_ID == 1) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Customer Deposit');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=13&sub_jID=' . SUB_JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Customer Deposit');
    }
} else if (JOURNAL_ID == 19 && SUB_JOURNAL_ID == 1) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Customer Deposit');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID . '&sub_jID=' . SUB_JOURNAL_ID, 'SSL') . '\'"', 2, '+ New Customer Deposit');
    }
} else if (JOURNAL_ID == 3) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Request for Quote');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Request for Quote');
    }
} else if (JOURNAL_ID == 4) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Purchase Order');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Purchase Order');
    }
} else if (JOURNAL_ID == 6 && SUB_JOURNAL_ID == 0) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Purchase');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Purchase');
    }
} else if (JOURNAL_ID == 6 && SUB_JOURNAL_ID == 1) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Expense');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID . '&amp;sub_jID=' . SUB_JOURNAL_ID, 'SSL') . '\'"', 2, '+New Expense');
    }
} else if (JOURNAL_ID == 7) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Credit Memo');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Credit Memo');
    }
} else if (JOURNAL_ID == 2) {
    if ($restriction == true) {
        if (isset($_GET['goto']) && $_GET['goto'] == 'receivedMoney') {
            $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Received Money');
        } else if (isset($_GET['goto']) && $_GET['goto'] == 'spendMoney') {
            $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Send Money');
        } else {
            $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New General Journal');
        }
    } else {
        if (isset($_GET['goto']) && $_GET['goto'] == 'receivedMoney') {
            $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=journal&goto=receivedMoney', 'SSL') . '\'"', 2, '+ New Received Money');
        } else if (isset($_GET['goto']) && $_GET['goto'] == 'spendMoney') {
            $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=journal&goto=spendMoney', 'SSL') . '\'"', 2, '+ New Send Money');
        } else {
            $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=journal', 'SSL') . '\'"', 2, '+ New General Journal');
        }
    }
} else if (JOURNAL_ID == 18) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Customer Receipt');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;jID=' . JOURNAL_ID . '&amp;type=c', 'SSL') . '\'"', 2, '+ New Customer Receipt');
    }
} else if (JOURNAL_ID == 20) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Pay Bill');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;jID=' . JOURNAL_ID . '&amp;type=v', 'SSL') . '\'"', 2, '+ New Pay Bill');
    }
} else if (JOURNAL_ID == 30 || (JOURNAL_ID == 12 && SUB_JOURNAL_ID == 2)) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Debit Memo');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID . '&amp;sub_jID=' . SUB_JOURNAL_ID, 'SSL') . '\'"', 2, '+New Dedit Memo');
    }
} else if (JOURNAL_ID == 31) {
    if ($restriction == true) {
        $toolbar->add_icon('new', 'onclick="alert(\'You have reached your maximum transaction limit for this month.\')"', 2, '+ New Debit Memo');
    } else {
        $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2, '+New Dedit Memo');
    }
} else {
    $toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=' . JOURNAL_ID, 'SSL') . '\'"', 2);
}





if (count($extra_toolbar_buttons) > 0)
    foreach ($extra_toolbar_buttons as $key => $value)
        $toolbar->icon_list[$key] = $value;
switch (JOURNAL_ID) {
    case 2: $toolbar->add_help('07.06.02');
        break;
    case 3:
        if ($_GET['action'] == 'edit') {
            $toolbar->add_help('07.02.04.01');
        } else {
            $toolbar->add_help('07.02.04');
        }

        break;
    case 4: $toolbar->add_help('07.02.03.04');
        break;
    case 6:
        if ($_GET['action'] == 'edit') {
            $toolbar->add_help('07.02.05.02');
        } else {
            $toolbar->add_help('07.02.05.01');
        }
        $toolbar->add_help('07.02.03.04');
        break;
    case 7: $toolbar->add_help('07.02.07');
        break;
    case 9: $toolbar->add_help('07.03.04');
        break;
    case 10: $toolbar->add_help('07.03.03.04');
        break;
    case 32: $toolbar->add_help('07.03.03.04');
        break;
    case 12:
    case 36: $toolbar->add_help('07.03.03.04');
        break;
    case 13:
    case 19: $toolbar->add_help('07.03.07');
        break;
    case 18: $toolbar->add_help('07.05.02.01');
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
            <tr style="height: 36px; text-align: center !important;" > 
                <?php if ($_GET['jID'] == 32) { ?>
                    <th class="ui-state-default" style="width: 10px !important">Select</th>
                <?php } ?>
                <th class="ui-state-default" style="width: 90px !important">Date</th>
                <?php if ($_GET['jID'] == 9) { ?>
                    <th class="ui-state-default">Quote Number</th>
                <?php } else if ($_GET['jID'] == 10) { ?>

                    <th class="ui-state-default">Sales Orders Number</th>
                <?php } else if ($_GET['jID'] == 32) { ?>
                    <th class="ui-state-default">Days</th>
                    <th class="ui-state-default">Delivery Orders Number</th>
                <?php } else if (($_GET['jID'] == 12 && $_GET['sub_jID'] != 2) || $_GET['jID'] == 36 && (SUB_JOURNAL_ID != 2)) { ?>
                    <th class="ui-state-default">Invoice Number</th>

                <?php } else if ($_GET['jID'] == 3) { ?>
                    <th class="ui-state-default">Quotes Number</th>
                <?php } else if ($_GET['jID'] == 4) { ?>
                    <th class="ui-state-default">Purchase Orders Number</th>

                <?php } else if ($_GET['jID'] == 6 && SUB_JOURNAL_ID == 0) { ?>
                    <th class="ui-state-default">Purchase/Receive Number</th>
                <?php } else if ($_GET['jID'] == 6 && SUB_JOURNAL_ID == 1) { ?>
                    <th class="ui-state-default">Expenses Number</th>
                <?php } else if ($_GET['jID'] == 13 && SUB_JOURNAL_ID == 0) { ?>
                    <th class="ui-state-default">Customer Credit Number</th>
                <?php } else if ($_GET['jID'] == 13 && SUB_JOURNAL_ID == 1) { ?>
                    <th class="ui-state-default">Customer Deposit Number</th>
                <?php }else if ($_GET['jID'] == 19 && SUB_JOURNAL_ID == 1) { ?>
                    <th class="ui-state-default">Customer Deposit Number</th>
                <?php } else if ($_GET['jID'] == 30 || ($_GET['jID'] == 12 && SUB_JOURNAL_ID == 2)) { ?>
                    <th class="ui-state-default">Customer Debit Number</th>
                    <?php
                } else if ($_GET['jID'] == 7) {
                    ?>
                    <th class="ui-state-default">Supplier Credit Number</th>
                <?php } else if ($_GET['jID'] == 31) { ?>
                    <th class="ui-state-default">Supplier Debit Number</th>
                <?php } else if ($_GET['jID'] == 18) { ?>
                    <th class="ui-state-default">Customer Receipts Number</th>

                <?php } else if ($_GET['jID'] == 20) { ?>
                    <th class="ui-state-default">Pay Bills Number</th>
                <?php } else if ($_GET['jID'] == 2) { ?>
                    <th class="ui-state-default">Reference</th>
                <?php } else { ?>
                    <th class="ui-state-default">None</th>
                    <?php
                }
                if ($_GET['jID'] == 9 || $_GET['jID'] == 10 || $_GET['jID'] == 32 || ($_GET['jID'] == 12 && $_GET['sub_jID'] != 2) || $_GET['jID'] == 36 || $_GET['jID'] == 13 || $_GET['jID'] == 18 || $_GET['jID'] == 19) {
                    ?>

                    <th class="ui-state-default">Customer Name</th>
                    <?php
                } else if ($_GET['jID'] == 3 || $_GET['jID'] == 4 || $_GET['jID'] == 6 || $_GET['jID'] == 7 || $_GET['jID'] == 20) {
                    ?>
                    <th class="ui-state-default">Supplier Name</th>
                <?php } else { ?>
                    <th class="ui-state-default">Supplier Name</th>
                <?php } ?>
                <?php if ($_GET['jID'] != 18) { ?>
                    <th width="20%" class="ui-state-default">Description</th>
                <?php } ?>
                <?php if ($_GET['jID'] != 2) { ?>
                    <th width="8%" class="ui-state-default">Status</th>
                <?php } ?>
                <th width="10%" class="ui-state-default">Amount</th> 
                <?php if ($_GET['jID'] == 6 || ($_GET['jID'] == 12 && $_GET['sub_jID'] != 2) || $_GET['jID'] == 36) { ?>
                    <th class="ui-state-default">Unpaid amount</th>
                    <th  class="ui-state-default">Paid Amount</th>
                <?php } ?>
                <?php if ($_GET['jID'] != 2) { ?>
                    <th class="ui-state-default">Currencies code</th>
                    <th width="8%" class="ui-state-default">Currencies value</th>
                <?php } ?>
                <th class="ui-state-default">Branch</th>

                <?php if ($_GET['jID'] == 18) { ?>
                    <th width="15%" class="ui-state-default">Invoice</th>
                <?php } ?>
                <th class="ui-state-default">Edit</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <?php if ($_GET['jID'] == 32) { ?>
                    <th>N/A</th>
                <?php } ?>
                <th>Search</th>
                <?php if ($_GET['jID'] == 32) { ?>
                    <th>Search</th>
                <?php } ?>
                <th>Search </th>
                <th>Search </th>
                <?php if ($_GET['jID'] != 18) { ?>
                    <th>Search</th>
                <?php } ?>
                <?php if ($_GET['jID'] != 2) { ?>
                    <th>Open=0;Close=1</th>
                <?php } ?>
                <th>Search</th>
                <?php if ($_GET['jID'] == 6 || ($_GET['jID'] == 12 && $_GET['sub_jID'] != 2) || $_GET['jID'] == 36) { ?>

                    <th>N/A</th>
                    <th>N/A </th>
                <?php } ?>
                <?php if ($_GET['jID'] != 2) { ?>
                    <th>Search </th>
                    <th>Search</th>
                <?php } ?>
                <th>Search</th>
                <?php if ($_GET['jID'] == 18) { ?>
                    <th>Search</th>
                <?php } ?>
                <th>N/A</th>
            </tr>
        </tfoot>

    </table>
</div>

<br/>
<style>
    .popbox {
        background: none repeat scroll 0 0 white;
        border: 1px solid #4d4f53;
        box-shadow: 0 0 5px 0 rgba(164, 164, 164, 1);
        color: #000000;
        display: none;
        height: 200px;
        left: 454px;
        margin: 0;
        padding: 10px;
        position: absolute;
        top: 194px;
        width: 400px;
        z-index: 99999;
    }
    .popbox h2
    {
        background-color: #4D4F53;
        color:  #E3E5DD;
        font-size: 14px;
        display: block;
        width: 100%;
        margin: -10px 0px 8px -10px;
        padding: 5px 10px;
    }
    #abc{background-color: #313131;
         display: none;
         height: 100%;
         left: 0;
         opacity: 0.95;
         overflow: auto;
         position: fixed;
         top: 0;
         width: 100%;
    }
    #close{
        float:right;margin-top:-20px;margin-right:-20px;cursor: pointer; 
    }
</style>
<div style="display:none" id="abc">
    <div id="pop1" class="popbox">
        <img src="themes/default/fancybox/images/fancybox_images/fancy_close.png" id="close"/>

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <form id="doPost" action="<?php echo 'index.php?module=ucbooks&amp;page=orders&amp;jID=12&amp;action=prc_do'; ?>" method="post">
            Please select customer :<select style="margin-left:10px;width:236px; font-size: 18px;" name="customer" id="customer">

            </select>
            <input type="hidden" name="idarray" id="idarray" value=""/>
            <input style="margin-left: 165px; margin-top: 15px;" type="submit" value="submit"/>
        </form>
    </div>
</div>





