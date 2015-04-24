<?php

// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011, 2012 UcSoft, LLC       |
// | http://www.UcSoft.com                                        |
// +-----------------------------------------------------------------+
// | This program is free software: you can redistribute it and/or   |
// | modify it under the terms of the GNU General Public License as  |
// | published by the Free Software Foundation, either version 3 of  |
// | the License, or any later version.                              |
// |                                                                 |
// | This program is distributed in the hope that it will be useful, |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of  |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the   |
// | GNU General Public License for more details.                    |
// +-----------------------------------------------------------------+
//  Path: /modules/ucbooks/ajax/load_order.php
//
/* * ************   Check user security   **************************** */
$xml = NULL;
$debug = NULL;
$security_level = validate_ajax_user();
/* * ************  include page specific files    ******************** */
gen_pull_language('contacts');
require_once(DIR_FS_MODULES . 'ucbooks/defaults.php');
require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');
/* * ************   page specific initialization  ************************ */
$oID = db_prepare_input($_GET['oID']);

$check_discount = $db->Execute("select id,description from " . TABLE_JOURNAL_MAIN . " where  so_po_ref_id = " . $oID);
while (!$check_discount->EOF) {
    if($check_discount->fields['description'] == "Customer Credit Memos Entry Discount" || $check_discount->fields['description'] == "Vendor Credit Memos Entry Discount" || $check_discount->fields['description'] == "Customer Debit Memos Entry Discount" || $check_discount->fields['description'] == "Vendor Debit Memos Entry Discount"){
        $id = $check_discount->fields['id'];
        break;
    }else{
        $id = 0;
        }
    $check_discount->MoveNext();
}

echo $id;
die;
?>