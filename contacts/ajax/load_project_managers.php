<?php

$aColumns = array('contacts_short_name', 'address_primary_name', 'address_address1', 'address_city', 'address_state', 'address_zipcode', 'address_telephone', 'contacts_attachments', 'contacts_id');

/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "contacts_id";
/* DB table to use */
$sTable = "v_project_manager";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}elseif($_GET['type'] == "c"){
//    $where_val = "jm";
//}

$sWhere = "WHERE address_type='" . $_GET['type'] . "m'";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {


    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {

        if ($aColumns[$i] == "contacts_id") {
            $id = $aRow[8];
            if ($aRow['contacts_attachments']) {
                $add_icon = "<td width='20%' style='padding-top:8px'>
                        <img  onclick='submitSeq(" . $id . ", \"dn_attach\", \"true\")' title='Download Attachmenst' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/attachment.png'>   
                    </td>";
            }
            $row[] = "	<table>
			<tr>
                        <!--<td width='20%' style='padding-top:8px'>
			<img onclick='contactChart(\"annual_sales\"," . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>-->
			<td width='20%' style='padding-top:8px'>
                        <img  onclick='submitSeq(" . $id . ", \"edit\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>   
			</td>
                        $add_icon
			<td width='20%' style='padding-top:8px'>
			<img  onclick='if (confirm(\"Are you sure you want to delete this account?\")) submitSeq(" . $id . ", \"delete\")' title='Delete' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>                           
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
