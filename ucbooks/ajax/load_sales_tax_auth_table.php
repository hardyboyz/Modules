<?php

/* Indexed column (used for fast and accurate table cardinality) */

$sIndexColumn = "tax_auth_id";
/* DB table to use */
//$sTable = "journal_main";

$aColumns = array('tax_auth_id', 'description_short', 'description_long', 'account_id', 'tax_rate');
$aColumns_temp = array('tax_auth_id','description_short', 'description_long', 'account_id', 'tax_rate', 'action');
$sTable = 'tax_authorities';

// changed by shaheen

$sWhere = "where type='".$_GET['type']."'";

if($_GET['type']=='c'){
    $edit = 'tax_auths_edit';
}else{
    $edit = 'tax_auths_vend_edit';
}

//$sWhere = "WHERE journal_id='" . $_GET['jID'] . "'";

require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');

require_once('modules/bank_transfer/language/en_us/admin.php');
while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns_temp); $i++) {

        if ($aColumns_temp[$i] == "description_short") {
            $row[] = $aRow['description_short'];
        }

        if ($aColumns_temp[$i] == "description_long") {
            $row[] = htmlspecialchars($aRow['description_long']);
        }

        if ($aColumns_temp[$i] == "account_id") {
            $row[] = gen_get_type_description(TABLE_CHART_OF_ACCOUNTS, $aRow['account_id']);
        }
        if ($aColumns_temp[$i] == "tax_rate") {

            $row[] = $aRow['tax_rate'];
        }
        if ($aColumns_temp[$i] == "tax_auth_id") {

            $row[] = $aRow['tax_auth_id'];
        }

        if ($aColumns_temp[$i] == "action") {
            $id = $aRow['tax_auth_id'];

            $row[] = "	<table>
			<tr>
			<td width='20%' style='padding-top:8px'>
                        <a href='javascript:' style='cursor:pointer' onclick=loadPopUp('".$edit."',\"" . $id . "\")> <img  title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'></a>
			</td>
			<td width='20%' style='padding-top:8px;'>
                        <a href='javascript:' style='cursor:pointer' onclick=if(confirm('Confirm?'))subjectDelete('tax_auths',\"" . $id . "\")> <img title='Delete' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'></a>
                        
			</td>
			</tr>
			</table>";
            continue;
        }
        // $row[] = $aRow[$i];
    }
    $output['aaData'][] = $row;
}


$output = json_encode($output);

echo $output;
