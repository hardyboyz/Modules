<?php

$aColumns = array('asset_id', 'asset_type', 'purch_cond', 'serial_number', 'description_short', 'acquisition_date', 'terminal_date' , 'id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = "assets";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}
    
//$sWhere = "WHERE inactive='0'";
$sWhere = "";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "id") {
            $id = $aRow[7];
            $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img title='Rename Item'  onclick='renameItem($id)'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>
                        <td width='20%' style='padding-top:8px'>
			<img title='Copy Item' onclick='copyItem($id)'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>
			<td width='20%' style='padding-top:8px'>
			<img title='Edit' onclick='submitSeq($id, \"edit\")' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/edit.jpg'>
			
                        </td>
			<td width='20%' style='padding-top:8px'>
                        <img title='Delete' onclick='if (confirm(\"Are you sure you want to delete this asset?\")) deleteItem($id)'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/delete_red.png'>			
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


