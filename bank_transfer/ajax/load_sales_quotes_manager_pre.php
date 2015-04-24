<?php

$aColumns = array('post_date', 'purchase_invoice_id', 'bill_primary_name', 'purch_order_id', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'id');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = "journal_main";


$sWhere = "WHERE journal_id='" . $_GET['jID'] . "'";

require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "total_amount") {
            $row[] = $currencies->format_full($aRow['total_amount'], true, $aRow['currencies_code'], $aRow['currencies_value']);
            continue;
        }


        if ($aColumns[$i] == 'closed') {
            if ($aRow[4] == 1) {
                $val = "Closed";
            } else {
                $val = "Open";
            }

            $row[] = $val;
            continue;
        }

        if ($aColumns[$i] == "id") {
            $id = $aRow[8];
            switch ($_GET['jID']) {
                case 2:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=journal&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=edit', 'SSL');
                    break;
                case 33:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=journal&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=edit', 'SSL');
                    break;
                 case 34:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=spend_receive_money&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=edit', 'SSL');
                    break;
                 case 35:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=receive_spend_money&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=edit', 'SSL');
                    break;
                case 3:
                case 4:
                case 6:
                case 7:
                case 9:
                case 10:
                case 12:
                case 13:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=orders&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=edit', 'SSL');
                    $delete_link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=status&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=delete', 'SSL');
                    break;
                case 18:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=bills&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;type=c&amp;action=edit', 'SSL');
                    $delete_link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=status&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=delete', 'SSL');
                    break;
                case 20:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=bills&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;type=v&amp;action=edit', 'SSL');
                    $delete_link_page = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=status&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=delete', 'SSL');
                    break;
            }
            if ($_GET['jID'] == 9 || $_GET['jID'] == 10 || $_GET['jID'] == 3) {
                $toggle_icon = "<td width='20%' style='padding-top:8px'>
			<img onclick='submitSeq(" . $id . ", \"toggle\")' title='Toggle Status'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/toggle_icon.png'  >  
			</td>";
            } else if ($_GET['jID'] == 12) {
                $linkpage = html_href_link(FILENAME_DEFAULT, 'module=bank_transfer&amp;page=bills&amp;type=c&amp;oID=' . $id . '&amp;jID=18&amp;action=pmt', 'SSL');
                $toggle_icon = "<td width='20%' style='padding-top:8px'>
			<img onclick='location.href = \"$linkpage\"' title='Payment'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/calculator_icon.png'  >  
			</td>";
            }
            $row[] = "	<table>
			<tr>
                        <!--$toggle_icon-->
                        <!--<td width='20%' style='padding-top:8px'>
			<img onclick='printOrder(" . $id . ")' title='Print'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/print_icon.png'  >  
			</td>-->
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




