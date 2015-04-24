<?php

$aColumns = array('cat_name', 'description','key_code', 'id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = "product_category";

$sWhere = "where 1=1";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "id") {
            $id = $aRow[3];

            $row[] = "	<table>
			<tr>
			<td width='20%' style='padding-top:8px'>
			<img  onclick='submitSeq(" . $id . ", \"edit\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
			<td width='20%' style='padding-top:8px'>
                        <img  onclick='if (confirm(\"Are you sure you want to delete this category ?\")) deletecategory(" . $id . ")' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>			
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


