<?php

$aColumns_temp = array('post_date', 'purchase_invoice_id', 'description', 'gl_account', 'from_store','to_store', 'id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "journal_main.id";
/* DB table to use */
//$sTable = "journal_main";

$aColumns = array('journal_main.post_date', 'journal_main.purchase_invoice_id as reference','journal_item.description','journal_item.gl_account as transfer_account','ifnull((select contacts.short_name from contacts where contacts.id = journal_main.store_id),(select `configuration`.`configuration_value` AS `configuration_value` 
from `configuration` where 
(`configuration`.`configuration_key` = "COMPANY_ID"))) AS `from_store`',
    ', ifnull((select contacts.short_name from contacts where contacts.id = inventory_history.store_id),(select `configuration`.`configuration_value` AS `configuration_value` 
from `configuration` where 
(`configuration`.`configuration_key` = "COMPANY_ID"))) as to_store','journal_main.id');
$sTable = TABLE_JOURNAL_MAIN;

$sWhere = "JOIN inventory_history on journal_main.so_po_ref_id = inventory_history.ref_id 
    join journal_item on journal_main.id = journal_item.ref_id WHERE journal_item.gl_type = 'ttl'
and journal_main.journal_id=" . $_GET['jID'];

if (!empty($_GET['sub_jID'])) {
    $sWhere .= ' and journal_main.sub_journal_id=' . $_GET['sub_jID'];
}
$sWhere .= ' GROUP BY inventory_history.ref_id';

//$sWhere = "WHERE journal_id='" . $_GET['jID'] . "'";

require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns_temp); $i++) {
        $j = false;
        if ($aColumns_temp[$i] == "selection" && $j == false) {
            $id = $aRow['id'];
            $row[] = '<input style="width:10px !important;" type="checkbox" name="check" id="select_' . $id . '"/>';
            //$i--;
            $j = true;
            continue;
        }
        if ($aColumns_temp[$i] == "total_amount") {
            $row[] = $currencies->format_full($aRow['total_amount'], true, $aRow['currencies_code'], $aRow['currencies_value']);
            continue;
        }
        if ($aColumns_temp[$i] == "days") {
            $post_date = strtotime($aRow['post_date']);
            $current_date = strtotime(date('Y-m-d'));
            $actual_time = $current_date - $post_date;
            $days = floor($actual_time / (60 * 60 * 24));
            if ($aRow['closed'] == 1 || $days <= 0) {
                $row[] = '';
            } else {
                $row[] = $days;
            }
            // $i--;
            continue;
        }
        if ($aColumns_temp[$i] == 'closed') {
            if ($aRow['closed'] == 1) {
                $val = "Closed";
            } else {
                $val = "Open";
            }

            $row[] = $val;
            continue;
        }
        if ($aColumns_temp[$i] == "paid_amount") {
            $paidAmount = $aRow['total_amount'] - fetch_partially_paid($aRow['id']);

            $row[] = $currencies->format_full($paidAmount, true, $aRow['currencies_code'], $aRow['currencies_value']);

            continue;
        }
        if ($aColumns_temp[$i] == "unpaid_amount") {
            $unpaidAmount = fetch_partially_paid($aRow['id']);

            $row[] = $currencies->format_full($unpaidAmount, true, $aRow['currencies_code'], $aRow['currencies_value']);

            continue;
        }

        if ($aColumns_temp[$i] == "currencies_code") {
            $row[] = $aRow['currencies_code'];
            continue;
        }
        if ($aColumns_temp[$i] == "currencies_value") {
            $row[] = $aRow['currencies_value'];
            continue;
        }

        if ($aColumns_temp[$i] == "short_name") {
            if ($aRow['short_name'] == '') {
                $row[] = COMPANY_ID;
            } else {
                $row[] = $aRow['short_name'];
            }
            continue;
        }




        if ($aColumns_temp[$i] == "id") {
            $id = $aRow['id'];
            $link_page = html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=transfer&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&sub_jID=1&amp;type=c&amp;action=edit', 'SSL');
            $delete_link_page = html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=transfer&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&sub_jID=1&amp;action=delete', 'SSL');


            $row[] = "	<table>
			<tr>
                   
			<td width='20%' style='padding-top:8px'>
			<img  onclick='window.open(\"" . $link_page . "\",\"_self\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
                         
			<td width='20%' style='padding-top:8px; display:none;'>
                        <img onclick='if (confirm(\"Are you sure you want to delete this price sheet?\")) window.open(\"" . $delete_link_page . "\",\"_self\")' title='Delete' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>                         			
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