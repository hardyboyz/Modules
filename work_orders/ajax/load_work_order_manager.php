<?php

$aColumns = array( 'wo_journalmain_wo_num', 'wo_journalmain_priority', 'wo_journalmain_wo_title', 'inventory_sku', 'wo_jurnalmain_qty', 'wo_journalmain_post_date','wo_journalmain_closed','wo_journalmain_close_date','wo_journalmain_id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "wo_journalmain_id";
/* DB table to use */
$sTable = "v_work_order_manager";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}
    
//$sWhere = "WHERE womain_inactive='0'";
$sWhere = "";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "wo_journalmain_id") {
            $id = $aRow[8];
            $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img onclick='submitSeq(".$id.", \"build\")' title='Build Work Order'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>
                        <td width='20%' style='padding-top:8px'>
			<img  onclick='printWOrder(".$id.")' title='Print'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>
			<td width='20%' style='padding-top:8px'>
			<img onclick='submitSeq(".$id.", \"edit\")' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/edit.jpg'> </a>   
			</td>
                        <td>
                        <img width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/delete_red.png' title='Delete' onclick='if (confirm(\"Are you sure you want to delete this inventory item?\")) deleteWO(".$id.")'>
                        </td>
			
			</td>
			</tr>
			</table>";
            continue;
        }
        $row[] = $aRow[$i];
    }
    $output['aaData'][] = $row;
}


$output = json_encode($output);

echo $output;


