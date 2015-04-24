<?php

$aColumns = array('rma_num', 'creation_date', 'caller_name', 'purchase_invoice_id', 'status', 'closed_date','attachments' , 'id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = "rma_module";
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
             if ($aRow['attachments']) {
                $add_icon = "<td width='20%' style='padding-top:8px'>
                        <img  onclick='submitSeq(" . $id . ", \"dn_attach\", \"true\")' title='Download Attachmenst' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/attachment.png'>   
                    </td>";
            }
            $row[] = "	<table>
			<tr>
                        
			<td width='20%' style='padding-top:8px'>
			<img  onclick='submitSeq(".$id.", \"edit\")' title='Edit' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
                        $add_icon
			<td width='20%' style='padding-top:8px'>
                        <img  onclick='if (confirm(\"Are you sure you want to delete this inventory item?\")) deleteItem(".$id.")' width='20' height='20' src='". DIR_WS_THEMES . "datatables/images/delete_red.png'>                       
			</td>
			</tr>
			</table>";
            continue;
        }
        $row[] = $aRow[$i];
    }
    $add_icon = "";
    $output['aaData'][] = $row;
}


$output = json_encode($output);

echo $output;


