<?php

$aColumns = array('post_date', 'shipper_code', 'total_amount', 'bill_primary_name', 'id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = "journal_main";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}
    
$sWhere = "WHERE journal_id in (19,21)";
//$sWhere = "";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "id") {
            $id = $aRow[4];
            $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img onclick='printOrder(".$id.")' title='Print'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>
			<!--<td width='20%' style='padding-top:8px'>
			<img title='Edit' onclick=\"submitSeq(".$id.", 'edit')\" width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/edit.jpg'>  
			</td>-->
			<td width='20%' style='padding-top:8px'>
			<img title='Delete' onclick='if (confirm(\"Are you sure you want to void/delete this POS entry?\")) submitSeq(".$id.", \"delete\")'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/delete_red.png'>			
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


