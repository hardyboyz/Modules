<?php

$sTable = 'invoice_recurring';
$sIndexColumn = 'id';
//$type = $_GET['type'] == 'purchase'?'purchase':'invoice';

/* Total data set length */
$sQuery = "SELECT COUNT(" . $sIndexColumn . ") FROM $sTable" ;
$rResultTotal = mysql_query($sQuery) or die(mysql_error());
$aResultTotal = mysql_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

$query = "SELECT id,inv_info,journal_id,inv_date,recurring_interval,last_exec_date,parent_inv_id,create_date,update_date FROM $sTable ";

$result = mysql_query($query);

$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iTotal,
    "aaData" => array()
);

while ($row = mysql_fetch_object($result)) {
    $row->inv_info = unserialize($row->inv_info);
    $row->next_exec_date = date('Y-m-d', strtotime($row->last_exec_date . " +" . $row->recurring_interval . " Days"));
    //$row->parent_invoice_link = "<a href='".html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;oID=' . $row->parent_inv_id . '&amp;jID=' . $row->journal_id . '&amp;action=edit', 'SSL')."'>Edit</a>";
    //$row->edit_delete_link = get_edit_link($row->id,$type)."&nbsp;|&nbsp;".get_delete_link($row->id);
    $row->edit_delete_link = "<img  onclick='window.open(\"".html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=recurring_invoice_edit&amp;oID=' . $row->parent_inv_id . '&amp;jID=' . $row->journal_id . '&amp;action=edit&rec_id=' . $row->id, 'SSL')."\",\"_self\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>&nbsp;&nbsp;&nbsp;<img style='display:none;' onclick='if (confirm(\"Are you sure you want to delete this price sheet?\")) deleteRecuring(" . $row->id . ",this)' title='Delete' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>";
    $output['aaData'][] = $row;
}


echo json_encode($output);
















//$aColumns = array('last_exec_date','recurring_interval', 'id');
//
//
///* Indexed column (used for fast and accurate table cardinality) */
//$sIndexColumn = "id";
///* DB table to use */
//$sTable = "invoice_recurring";
//
//
//$sWhere = "";
//
//require('includes/datatable_top.php');
////require('includes/datatable_bottom.php');
//
//
//while ($aRow = mysql_fetch_array($rResult)) {
//    $row = array();
//    for ($i = 0; $i < count($aColumns); $i++) {
//        
//        if ($aColumns[$i] == "id") {
//            $id = $aRow[8];
//            
//               
//               $row[] = "	<table>
//			<tr>
//                        <!--$toggle_icon-->
//                        <!--<td width='20%' style='padding-top:8px'>
//			<img onclick='printOrder(" . $id . ")' title='Print'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/print_icon.png'  >  
//			</td>-->
//			<td width='20%' style='padding-top:8px'>
//			<img  onclick='window.open(\"".$link_page."\",\"_self\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>
//			</td>
//			<!--<td width='20%' style='padding-top:8px'>
//                        <img onclick='if (confirm(\"Are you sure you want to delete this price sheet?\")) deleteItem(" . $id . ")' title='Delete' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>                         			
//			</td>-->
//			</tr>
//			</table>";
//                    continue;
//            }
//            $row[] = $aRow[$i];
//        }
//        $output['aaData'][] = $row;
//    }
//
//
//    $output = json_encode($output);
//
//    echo $output;



    