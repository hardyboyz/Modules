<?php

$aColumns = array( 'womain_wo_title', 'inventory_sku', 'womain_description', 'womain_revision', 'womain_revision_date', 'womain_id','womain_inactive');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "womain_id";
/* DB table to use */
$sTable = "v_work_order_builder";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}
    
$sWhere = "WHERE womain_inactive='0'";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "womain_id") {
            $id = $aRow[5];
            $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img title='Copy To' onclick='copyItem(".$id.")' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>
			<td width='20%' style='padding-top:8px'>
			<img title='Edit'  onclick='submitSeq(".$id.", \"edit\")' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
			<td width='20%' style='padding-top:8px'>
                        <img title='Edit'   onclick='if (confirm(\"Are you sure you want to delete this entry?\")) deleteItem(".$id.")' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/delete_red.png'>                       			
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


