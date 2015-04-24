<?php

$aColumns = array('sheet_name', 'inactive', 'revision', 'effective_date', 'expiration_date', 'default_sheet',  'id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = "price_sheets";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}
    
$sWhere = "WHERE type='".$_GET['type']."'";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "id") {
            $id = $aRow[6];
            $row[] = "	<table>
			<tr>
                        <!--<td width='20%' style='padding-top:8px'>
			<img onclick='contactChart(\"annual_sales\",".$id.")'  width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>-->
			<td width='20%' style='padding-top:8px'>
			<img onclick='submitSeq(".$id.", \"edit\")' title='Edit' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
			<td width='20%' style='padding-top:8px'>
                        <img onclick='if (confirm(\"Are you sure you want to delete this price sheet?\")) deleteItem(".$id.")' title='Delete' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/delete_red.png'>
                         			
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


