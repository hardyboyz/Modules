

<?php

$keyword = $_GET['keyword'];
$str = '';

//$sql = "SELECT * FROM ".TABLE_CONTACTS." WHERE country_name LIKE (:keyword) ORDER BY country_id ASC LIMIT 0, 10";
if (!empty($keyword)) {
    if ($_GET['action'] == 'item') {
        $query_raw = "select id, sku,description_short from inventory where (sku like '%" . $keyword . "%' or description_short like '%" . $keyword . "%') and inactive = '0' order by sku ASC";
    } else {
        $type = $_GET['type'];
        if ($_GET['journalID'] == 18) {
            $query_raw = "select m.bill_acct_id, m.bill_primary_name, m.bill_city_town, m.bill_state_province, m.bill_postal_code, sum(m.total_amount) as ztotal_amount 
	from journal_main m inner join contacts c on m.bill_acct_id = c.id
	where c.type = '" . $type . "' 
	and m.journal_id in (12,36, 13) and m.closed = '0' and (c.short_name like '%" . $keyword . "%' or m.bill_primary_name like '%" . $keyword . "%' or m.bill_contact like '%" . $keyword . "%' or m.bill_address1 like '%" . $keyword . "%' or m.bill_address2 like '%" . $keyword . "%' or m.bill_city_town like '%" . $keyword . "%' or m.bill_postal_code like '%" . $keyword . "%' or m.purchase_invoice_id like '%" . $keyword . "%') 
	group by m.bill_acct_id order by m.bill_primary_name ASC";
        } else if ($_GET['journalID'] == 20) {

            $query_raw = "select m.bill_acct_id, m.bill_primary_name, m.bill_city_town, m.bill_state_province, m.bill_postal_code, sum(m.total_amount) as ztotal_amount 
	from journal_main m inner join contacts c on m.bill_acct_id = c.id
	where c.type = '" . $type . "' 
	and m.journal_id in (6, 7) and m.closed = '0' and (c.short_name like '%" . $keyword . "%' or m.bill_primary_name like '%" . $keyword . "%' or m.bill_contact like '%" . $keyword . "%' or m.bill_address1 like '%" . $keyword . "%' or m.bill_address2 like '%" . $keyword . "%' or m.bill_city_town like '%" . $keyword . "%' or m.bill_postal_code like '%" . $keyword . "%' or m.purchase_invoice_id like '%" . $keyword . "%') 
	group by m.bill_acct_id order by m.bill_primary_name ASC";
        } else {

            $query_raw = "select c.id,a.primary_name 
	from " . TABLE_CONTACTS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.id = a.ref_id 
	where a.type='" . $type . "' and (c.id like '%" . $keyword . "%' or a.primary_name like '%" . $keyword . "%') order by a.primary_name";
        }
    }
    $query_result = $db->Execute($query_raw);

    while (!$query_result->EOF) {
        // put in bold the written text
        //$country_name = str_replace($_POST['keyword'], '<b>' . $_POST['keyword'] . '</b>', $rs['country_name']);
        // add new option
        if ($_GET['action'] == 'item') {
            $str .= '<li onclick="setReturnItem(' . $query_result->fields['id'] . ',' . $_GET['row'] . ')">' . $query_result->fields['sku'] . '</li>';
        } else {
            if ($_GET['journalID'] == 18 || $_GET['journalID'] == 20) {
                $str .= '<li onclick="setReturnEntry(' . $query_result->fields['bill_acct_id'] . ')">' . $query_result->fields['bill_primary_name'] . '</li>';
            } else {
                $str .= '<li onclick="setReturnAccount(' . $query_result->fields['id'] . ')">' . $query_result->fields['primary_name'] . '</li>';
            }
        }
        $query_result->MoveNext();
    }
}

echo $str;
?>