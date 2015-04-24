<?php

/* Indexed column (used for fast and accurate table cardinality) */

$sIndexColumn = "id";
/* DB table to use */
//$sTable = "journal_main";

$aColumns = array('id', 'description', 'heading_only', 'primary_acct_id', 'account_type', 'account_inactive');
$aColumns_temp = array('id', 'description', 'heading_only', 'primary_acct_id', 'action');
$sTable = 'chart_of_accounts';

// changed by shaheen

$sWhere = "";



//$sWhere = "WHERE journal_id='" . $_GET['jID'] . "'";

require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');

require_once('modules/bank_transfer/language/en_us/admin.php');
while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns_temp); $i++) {
        if ($aColumns_temp[$i] == "id") {
            $row[] = $aRow['id'];
        }

        if ($aColumns_temp[$i] == "description") {
            $row[] = $aRow['description'];
        }

        if ($aColumns_temp[$i] == "heading_only") {
            $aa = 'COA_' . str_pad($aRow['account_type'], 2, "0", STR_PAD_LEFT) . '_DESC';
            $account_type_desc = constant($aa);
            if ($aRow['heading_only']) {
                $account_type_desc = TEXT_HEADING;
            }
            $row[] = $account_type_desc;
        }

        if ($aColumns_temp[$i] == "primary_acct_id") {

            $row[] = $aRow['primary_acct_id'] ? TEXT_YES . ' - ' . htmlspecialchars($aRow['primary_acct_id']) : TEXT_NO;
        }

        if ($aColumns_temp[$i] == "action") {
            $id = $aRow['id'];

            $row[] = "	<table>
			<tr>
			<td width='20%' style='padding-top:8px'>
                        <a href='javascript:' style='cursor:pointer' onclick=loadPopUp('chart_of_accounts_edit'," . $aRow['id'] . ")> <img  title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'></a>
			</td>
			<td width='20%' style='padding-top:8px;'>
                        <a href='javascript:' style='cursor:pointer' onclick=if(confirm('Confirm?'))subjectDelete('chart_of_accounts'," . $aRow['id'] . ")> <img title='Delete' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'></a>
                        
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

function fetch_partially_paid($id) {

    $sql = "select sum(i.debit_amount) as debit, sum(i.credit_amount) as credit 
	from journal_main m inner join journal_item i on m.id = i.ref_id 
	where i.so_po_item_ref_id = " . $id . " and m.journal_id in (18, 20) and i.gl_type in ('chk', 'pmt') 
	group by m.journal_id";

    $res = mysql_query($sql);
    $balance = mysql_fetch_assoc($res);

    if ($balance['debit'] || $balance['credit']) {
        return $balance['debit'] + $balance['credit'];
    } else {
        return 0;
    }
}