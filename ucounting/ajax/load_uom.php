<?php

$aColumns = array('uom','uom_id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "uom_id";
/* DB table to use */
$sTable = "uom";
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
        if ($aColumns[$i] == "uom_id") {
            $id = $aRow[1];
            $row[] = "	<table>
			<tr>
                        
                        
			<td width='20%' style='padding-top:8px'>
			<img title='Edit' onclick='submitSeq($id, \"edit\")' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/edit.jpg'>
			
                        </td>
			<td width='20%' style='padding-top:8px'>
                            <img onclick='if (confirm(\"Are you sure you want to delete this user account?\")) submitSeq(".$id.", \"delete\")'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/delete_red.png'>                            
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


