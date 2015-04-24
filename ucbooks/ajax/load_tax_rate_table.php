<?php

/* Indexed column (used for fast and accurate table cardinality) */
require_once('modules/ucbooks/functions/ucbooks.php');
$sIndexColumn = "tax_rate_id";
/* DB table to use */
//$sTable = "journal_main";

$aColumns = array('tax_rate_id', 'description_short', 'description_long', 'rate_accounts', 'freight_taxable');
$aColumns_temp = array('tax_rate_id','description_short', 'description_long', 'rate_accounts', 'freight_taxable', 'action');
$sTable = 'tax_rates';

// changed by shaheen

$sWhere = "where type='".$_GET['type']."'";

if($_GET['type']=='c'){
    $edit = 'tax_rates_edit';
}else{
    $edit = 'tax_rates_vend_edit';
}


//$sWhere = "WHERE journal_id='" . $_GET['jID'] . "'";

require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');
$tax_authorities_array = gen_build_tax_auth_array();
require_once('modules/bank_transfer/language/en_us/admin.php');
while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns_temp); $i++) {

        if ($aColumns_temp[$i] == "tax_rate_id") {
            $row[] = $aRow['tax_rate_id'];
        }
        if ($aColumns_temp[$i] == "description_short") {
            $row[] = $aRow['description_short'];
        }
        if ($aColumns_temp[$i] == "description_long") {
            $row[] = htmlspecialchars($aRow['description_long']);
        }

        if ($aColumns_temp[$i] == "rate_accounts") {
            $row[] = gen_calculate_tax_rate($aRow['rate_accounts'], $tax_authorities_array);
        }
        if ($aColumns_temp[$i] == "freight_taxable") {

            $row[] = $aRow['freight_taxable'] ? TEXT_YES : TEXT_NO;
        }

        if ($aColumns_temp[$i] == "action") {
            $id = $aRow['tax_rate_id'];

            $row[] = "	<table>
			<tr>
			<td width='20%' style='padding-top:8px'>
                        <a href='javascript:' style='cursor:pointer' onclick=loadPopUp('".$edit."',\"" . $id . "\")> <img  title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'></a>
			</td>
			<td width='20%' style='padding-top:8px;'>
                        <a href='javascript:' style='cursor:pointer' onclick=if(confirm('Confirm?'))subjectDelete('tax_rates',\"" . $id . "\")> <img title='Delete' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'></a>
                        
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
