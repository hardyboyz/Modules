<?php

if ($_GET['jID'] == 6 || ($_GET['jID'] == 12 && $_GET['sub_jID'] != 2) || $_GET['jID'] == 36) {
    $aColumns_temp = array('post_date', 'purchase_invoice_id', 'bill_primary_name', 'product_desc', 'closed', 'total_amount', 'paid_amount', 'unpaid_amount', 'currencies_code', 'currencies_value', 'short_name', 'id');
} else if ($_GET['jID'] == 18) {
    $aColumns_temp = array('post_date', 'purchase_invoice_id', 'bill_primary_name', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'short_name', 'serialize_number', 'id');
} else if ($_GET['jID'] == 2) {
    $aColumns_temp = array('post_date', 'purchase_invoice_id', 'bill_primary_name', 'product_desc', 'total_amount', 'short_name', 'id');
} else {
    if ($_GET['jID'] == 32) {
        $aColumns_temp = array('selection', 'post_date', 'days', 'purchase_invoice_id', 'bill_primary_name', 'product_desc', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'short_name', 'id');
        //$aColumns_temp = array('post_date', 'days', 'purchase_invoice_id', 'bill_primary_name', 'product_desc', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'short_name', 'id');
    } else {
        $aColumns_temp = array('post_date', 'purchase_invoice_id', 'bill_primary_name', 'product_desc', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'short_name', 'id');
    }
}

/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
//$sTable = "journal_main";
if ($_GET['jID'] == 18) {
    $aColumns = array('post_date', 'purchase_invoice_id', 'bill_primary_name', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'short_name', 'serialize_number', 'id');
    $sTable = "v_customer_receipt";
} else if ($_GET['jID'] == 32) {
    $aColumns = array('post_date', 'post_date', 'purchase_invoice_id', 'purchase_invoice_id', 'bill_primary_name', 'product_desc', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'short_name', 'id','journal_id', 'sub_journal_id');
    $sTable = "v_sales_invoice";
} else if ($_GET['jID'] == 2) {
    $aColumns = array('post_date', 'sub_journal_id', 'post_date', 'purchase_invoice_id', 'purchase_invoice_id', 'bill_primary_name', 'product_desc', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'short_name', 'id','journal_id', 'sub_journal_id');
    $sTable = "v_sales_invoice";
} else {

    $aColumns = array('post_date', 'purchase_invoice_id', 'bill_primary_name', 'product_desc', 'closed', 'total_amount', 'currencies_code', 'currencies_value', 'short_name', 'id', 'journal_id', 'sub_journal_id');
    $sTable = "v_sales_invoice";
}
// changed by shaheen
//if ($_GET['jID'] == 2) {
//  $sWhere = "WHERE journal_id in(2,33,34,35)";
//}else{
//}
// Codeing by shaheen for get previous POS order with new POS order by sub journal id

if ($_GET['jID'] == 12 && $_GET['sub_jID'] == 1) {
    $sWhere = "WHERE ((journal_id='" . $_GET['jID'] . "' AND sub_journal_id='" . $_GET['sub_jID'] . "') or journal_id=36)";
} else if ($_GET['jID'] == 12 && $_GET['sub_jID'] == 2) {
    $sWhere = "WHERE ((journal_id='" . $_GET['jID'] . "' AND sub_journal_id='" . $_GET['sub_jID'] . "') or journal_id=30)";
} else if ($_GET['jID'] == 19 && $_GET['sub_jID'] == 1) {
    $sWhere = "WHERE ((journal_id='" . $_GET['jID'] . "' AND sub_journal_id='" . $_GET['sub_jID'] . "') or (journal_id=13 AND sub_journal_id='" . $_GET['sub_jID'] . "'))";
} else if ($_GET['jID'] == 18) {
    $sWhere = "WHERE journal_id='" . $_GET['jID'] . "'";
} else {
    $sWhere = "WHERE journal_id='" . $_GET['jID'] . "' AND sub_journal_id='" . $_GET['sub_jID'] . "'";
}

if (isset($_GET['status'])) {
    $sWhere .= " and closed='" . $_GET['status'] . "'";
}

if (isset($_GET['post_date'])) {
    $sWhere .= " and post_date like'%" . $_GET['post_date'] . "%'";
}
//
//if ($_GET['jID'] == 2) {
//    $sWhere .= " and sub_journal_id = 0 ";
//}
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
            if ($_GET['jID'] == 18) {
                $journal_id = $_GET['jID'];
            } else {
                $journal_id = $aRow['journal_id'];
            }

            $sub_journal_id = $aRow['sub_journal_id'];

            switch ($journal_id) {
                case 2:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=journal&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=edit', 'SSL');
                    break;
                case 3:
                case 4:
                case 6:
                case 7:
                case 9:
                case 10:
                case 32:
                case 12:
                case 36:
                case 30:
                case 31:

                case 19:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;sub_jID=' . $_GET['sub_jID'] . '&amp;action=edit', 'SSL');

                    $delete_link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;sub_jID=' . $_GET['sub_jID'] . '&amp;action=delete', 'SSL');
                    break;
                case 13:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;oID=' . $id . '&amp;jID=13&amp;sub_jID=' . $_GET['sub_jID'] . '&amp;action=edit', 'SSL');

                    $delete_link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;oID=' . $id . '&amp;jID=13amp;sub_jID=' . $_GET['sub_jID'] . '&amp;action=delete', 'SSL');
                    break;
                case 18:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;type=c&amp;action=edit', 'SSL');
                    $delete_link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=delete', 'SSL');
                    break;
                case 20:
                    $link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;type=v&amp;action=edit', 'SSL');
                    $delete_link_page = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=status&amp;oID=' . $id . '&amp;jID=' . $_GET['jID'] . '&amp;action=delete', 'SSL');
                    break;
            }
            if ($_GET['jID'] == 9 || $_GET['jID'] == 10 || $_GET['jID'] == 32 || $_GET['jID'] == 3) {
                $toggle_icon = "<td width='20%' style='padding-top:8px'>
			<img onclick='submitSeq(" . $id . ", \"toggle\")' title='Toggle Status'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/toggle_icon.png'  >  
			</td>";
            } else if ($_GET['jID'] == 12 || $_GET['jID'] == 36) {
                $linkpage = html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=bills&amp;type=c&amp;oID=' . $id . '&amp;jID=18&amp;action=pmt', 'SSL');
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