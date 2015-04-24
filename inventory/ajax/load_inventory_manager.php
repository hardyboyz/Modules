<?php

//if(isset($_SESSION['set_security']) && $_SESSION['set_security']=='yes'){ 
//$aColumns = array('sku', 'inactive', 'description_short', 'quantity_on_hand', 'quantity_on_sales_order', 'quantity_on_allocation', 'quantity_on_order','item_cost','full_price', 'id');
//
//}else{
$aColumns = array('sku', 'inactive', 'description_short', 'quantity_on_hand', 'quantity_on_sales_order', 'quantity_on_allocation', 'quantity_on_order', 'id');

//}
/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";
/* DB table to use */
$sTable = "inventory";
//$sWhere = "WHERE contacts_type='" . $_SESSION["user_id"] . "'";
//if($_GET['type'] == "c"){
//    $where_val = "cm";
//}elseif($_GET['type'] == "j"){
//    $where_val = "jm";
//}

$sWhere = "WHERE inactive='0'";
require('includes/datatable_top.php');
//require('includes/datatable_bottom.php');


while ($aRow = mysql_fetch_array($rResult)) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "id") {
            $id = $aRow[7];
            $sql = " select id, short_name from " . TABLE_CONTACTS . " where type = 'b'";
            $result = $db->Execute($sql);
            $store_stock = array();
            $store_stock[] = array('store' => COMPANY_ID, 'qty' => load_store_stock($aRow[0], 0));
            while (!$result->EOF) {
                $store_stock[] = array(
                    'store' => $result->fields['short_name'],
                    'qty' => load_store_stock($aRow[0], $result->fields['id']),
                );
                $result->MoveNext();
            }
            $sql = $db->Execute("SELECT * FROM " . TABLE_INVENTORY . " where id=" . $id . "");
            if ($sql->fields['catalog'] == 1) {
                $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img onclick='submitSeq(" . $id . ",\"upload_oc\")' title='OpenCart Upload'  width='20' height='20' alt='OpenCart Upload' src='" . DIR_WS_THEMES . "datatables/images/opencarticon.png'  >  
			</td>
                        <td width='20%' style='padding-top:8px'>
			<img title='Rename' onclick='renameItem(" . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/rename.png'  >  
			</td>
                        <td width='20%' style='padding-top:8px'>
			<img title='Copy To' onclick='copyItem(" . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/copy.png'  >  
			</td>
                        <!--<td width='20%' style='padding-top:8px'>
			<img title='Sales Price Sheets' onclick='priceMgr(19, 0, 0, 'c')'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>-->
			<td width='20%' style='padding-top:8px'>
			<img  onclick='submitSeq(" . $id . ", \"edit\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
			<td width='20%' style='padding-top:8px'>
                        <img  onclick='if (confirm(\"Are you sure you want to delete this inventory item?\")) deleteItem(" . $id . ")' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>
                        <input type='hidden' id='storeToQty' value='".json_encode($store_stock)."'>
			
			</td>
			</tr>
			</table>";
            } else {
                $row[] = "	<table>
			<tr>
                        <td width='20%' style='padding-top:8px'>
			<img title='Rename' onclick='renameItem(" . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/rename.png'  >  
			</td>
                        <td width='20%' style='padding-top:8px'>
			<img title='Copy To' onclick='copyItem(" . $id . ")'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/copy.png'  >  
			</td>
                        <!--<td width='20%' style='padding-top:8px'>
			<img title='Sales Price Sheets' onclick='priceMgr(19, 0, 0, 'c')'  width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/icon-success.png'  >  
			</td>-->
			<td width='20%' style='padding-top:8px'>
			<img  onclick='submitSeq(" . $id . ", \"edit\")' title='Edit' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/edit.jpg'>
			</td>
			<td width='20%' style='padding-top:8px'>
                        <img  onclick='if (confirm(\"Are you sure you want to delete this inventory item?\")) deleteItem(" . $id . ")' width='20' height='20' src='" . DIR_WS_THEMES . "datatables/images/delete_red.png'>
                        
			<input type='hidden' id='storeToQty' value='".json_encode($store_stock)."'/>
			</td>
			</tr>
			</table>";
            }
            continue;
        }
        $row[] = $aRow[$i];
    }
    $output['aaData'][] = $row;
}


$output = json_encode($output);

echo $output;


 function load_store_stock($sku, $store_id) {
	global $db, $currencies;
	$sql = "select sum(remaining) as remaining from " . TABLE_INVENTORY_HISTORY . " 
		where store_id = '" . $store_id . "' and sku = '" . $sku . "'";
	$result = $db->Execute($sql);
	$store_bal = $result->fields['remaining'];
	$sql = "select sum(qty) as qty from " . TABLE_INVENTORY_COGS_OWED . " 
		where store_id = '" . $store_id . "' and sku = '" . $sku . "'";
	$result = $db->Execute($sql);
	$qty_owed = $result->fields['qty'];
        $qty = $store_bal - $qty_owed;
        if(is_int($qty)==false){
            $qty = $currencies->format($qty);
        }
	return $qty;
  }