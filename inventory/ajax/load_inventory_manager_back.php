<?php

$aColumns = array('sku', 'inactive', 'description_short', 'quantity_on_hand', 'quantity_on_sales_order', 'quantity_on_allocation', 'quantity_on_order', 'id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = "inventory";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}

$sWhere = "WHERE inactive='0'";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "id") {
            $id = $aRow[7];
            $sql = $db->Execute("SELECT * FROM " . TABLE_INVENTORY . " where id=" . $id . "");
            if ($sql->fields['catalog'] == 1) {
                $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img onclick='submitSeq(" . $id . ",\"upload_oc\")' title='OpenCart Upload'  width='20' height='20' alt='OpenCart Upload' src='" . DIR_WS_THEMES . "datatables/images/opencarticon.png'  >  
			</td>
                        <td width='20%' style='padding-top:8px'>
			<img title='Rename' onclick='renameItem(" . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/rename.png'  >  
			</td>
                        <td width='20%' style='padding-top:8px'>
			<img title='Copy To' onclick='copyItem(" . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/copy.png'  >  
			</td>
                        <!--<td width='20%' style='padding-top:8px'>
			<img title='Sales Price Sheets' onclick='priceMgr(19, 0, 0, 'c')'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>-->
			<td width='20%' style='padding-top:8px'>
			<img  onclick='submitSeq(" . $id . ", \"edit\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
			<td width='20%' style='padding-top:8px'>
                        <img  onclick='if (confirm(\"Are you sure you want to delete this inventory item?\")) deleteItem(" . $id . ")' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>
                        
			
			</td>
			</tr>
			</table>";
            } else {
                $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img title='Rename' onclick='renameItem(" . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/rename.png'  >  
			</td>
                        <td width='20%' style='padding-top:8px'>
			<img title='Copy To' onclick='copyItem(" . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/copy.png'  >  
			</td>
                        <!--<td width='20%' style='padding-top:8px'>
			<img title='Sales Price Sheets' onclick='priceMgr(19, 0, 0, 'c')'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>-->
			<td width='20%' style='padding-top:8px'>
			<img  onclick='submitSeq(" . $id . ", \"edit\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
			<td width='20%' style='padding-top:8px'>
                        <img  onclick='if (confirm(\"Are you sure you want to delete this inventory item?\")) deleteItem(" . $id . ")' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>
                        
			
			</td>
			</tr>
			</table>";
            }
            continue;
        }
        $row[] = $aRow[$i];
    }
    $output['aaData'][] = $row;
}


$output = json_encode($output);

echo $output;


