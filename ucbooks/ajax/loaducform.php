<?php

$aColumns_temp = array('doc_title');
$aColumns = array('doc_title', 'id', 'doc_ext');

/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = TABLE_UCFORM;



$sWhere ="where doc_type <> '0'";

require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns_temp); $i++) {

        if ($aColumns_temp[$i] == "doc_title") {
             $id = $aRow['id'];

            $row[] = '	<table>
			<tr style="cursor:pointer">
			<td onclick="ReportGenPopup('.$id.', \''.$aRow['doc_ext'].'\')">
                        <img height="16" title="" style="border:none;" alt="" src="themes/default/icons/16x16/mimetypes/text-x-generic.png">
                        </td>
                        <td onclick="ReportGenPopup('.$id.', \''.$aRow['doc_ext'].'\')">'.$aRow['doc_title'].'</td>
                        <td align="right">      </td>
			</tr>
			</table>';
            continue;
        }
        $row[] = $aRow[$i];
    }
    $output['aaData'][] = $row;
}


$output = json_encode($output);

echo $output;
