<?php

$aColumns = array( 'admin_name','inactive', 'display_name', 'admin_email', 'admin_id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "admin_id";
/* DB table to use */
$sTable = "users";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}
    
$sWhere = "WHERE is_role='0'";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "admin_id") {
            $id = $aRow[4];
            $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img onclick='copyItem(".$id.")'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/icon-success.png'  >                              
                            
                        </td>
			<td width='20%' style='padding-top:8px'>
			<img onclick='submitSeq(".$id.",\"edit\")'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/edit.jpg'>
                            
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


