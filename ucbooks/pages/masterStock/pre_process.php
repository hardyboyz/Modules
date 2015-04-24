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
//  Path: /modules/inventory/pages/popup_inv/pre_process.php
//
$security_level = validate_user(0, true);
/* * ************  include page specific files    ******************** */
require(DIR_FS_ADMIN . '/modules/inventory/defaults.php');
require( DIR_FS_ADMIN . '/modules/inventory/functions/inventory.php');
require( DIR_FS_ADMIN . '/modules/inventory/language/en_us/language.php');
/* * ************   page specific initialization  ************************ */

/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_ADMIN . '/modules/inventory/custom/pages/popup_inv/extra_actions.php';
if (file_exists($custom_path)) {
    include($custom_path);
}
$action = isset($_GET['action']) ? $_GET['action'] : $_POST['todo'];
/* * *************   Act on the action request   ************************ */


/* * ***************   prepare to display templates  ************************ */
// build the type filter list

$skuid = $_GET['skuID'];
$skuDetails = $db->Execute("select * from " . TABLE_INVENTORY . " where id = '" . $skuid . "'");
$sku = $skuDetails->fields['sku'];

switch ($action) {
    case 'new':
        $result = $db->Execute("select * from " . TABLE_INVENTORY_MS_LIST . " where sku = '" . $sku . "'");
        $cInfo->ms_attr_0 = ($result->RecordCount() > 0) ? $result->fields['attr_0'] : '';
        $cInfo->attr_name_0 = ($result->RecordCount() > 0) ? $result->fields['attr_name_0'] : '';
        $cInfo->ms_attr_1 = ($result->RecordCount() > 0) ? $result->fields['attr_1'] : '';
        $cInfo->attr_name_1 = ($result->RecordCount() > 0) ? $result->fields['attr_name_1'] : '';
        $attr_array0 = array();
        if ($cInfo->ms_attr_0) {
            $temp = explode(',', $cInfo->ms_attr_0);
            for ($i = 0; $i < count($temp); $i++) {
                $code = substr($temp[$i], 0, strpos($temp[$i], ':'));
                $desc = substr($temp[$i], strpos($temp[$i], ':') + 1);
                $attr_array0[] = array('id' => $code . ':' . $desc, 'text' => $code . ' : ' . $desc);
            }
        }
        $attr_array1 = array();
        if ($cInfo->ms_attr_1) {
            $temp = explode(',', $cInfo->ms_attr_1);
            for ($i = 0; $i < count($temp); $i++) {
                $code = substr($temp[$i], 0, strpos($temp[$i], ':'));
                $desc = substr($temp[$i], strpos($temp[$i], ':') + 1);
                $attr_array1[] = array('id' => $code . ':' . $desc, 'text' => $code . ' : ' . $desc);
            }
        }
        $include_header = false;
        $include_footer = true;
        $include_template = 'template_tab_ms.php';
        define('PAGE_TITLE', 'Master Stock Item');
        break;
    case 'save' :
        $output_array = array();
        foreach ($skuDetails->fields as $key => $value) {
            switch ($key) {
                case 'id': // Remove from write list fields
                case 'last_journal_date':
                case 'item_cost':
                case 'upc_code':
                case 'image_with_path':
                case 'quantity_on_hand':
                case 'quantity_on_order':
                case 'quantity_on_sales_order':
                    break;
                case 'sku': // set the new sku
                    $output_array[$key] = $sku;
                    break;
                case 'creation_date':
                case 'last_update':
                    $output_array[$key] = date('Y-m-d H:i:s');
                    break;
                default:
                    $output_array[$key] = $value;
            }
        }
        $ms_result = $db->Execute("select * from " . TABLE_INVENTORY_MS_LIST . " where sku = '" . $sku . "'");
        $ms_attr_0 = ($ms_result->RecordCount() > 0) ? $ms_result->fields['attr_0'] : '';
        $att_name_0 = ($ms_result->RecordCount() > 0) ? $ms_result->fields['attr_name_0'] : '';
        $attr_1 = ($ms_result->RecordCount() > 0) ? $ms_result->fields['attr_1'] : '';
        $attr_name_1 = ($ms_result->RecordCount() > 0) ? $ms_result->fields['attr_name_1'] : '';
        $attributes = array(
            'attr_name_0' => db_prepare_input($_POST['attr_name_0']),
            'ms_attr_0' => substr(db_prepare_input($_POST['ms_attr_0']), 0, -1),
            'attr_name_1' => db_prepare_input($_POST['attr_name_1']),
            'ms_attr_1' => substr(db_prepare_input($_POST['ms_attr_1']), 0, -1),
        );
        save_ms_items($output_array, $attributes);
     //   echo '<script>self.close(); </script>';
      //  break;
    default :
        $where = '"' . $sku . '"';
        if (!empty($sku)) {
            $code1 = '';
            $code2 = '';
            $subSku = '';
            $result = $db->Execute("select * from " . TABLE_INVENTORY_MS_LIST . " where sku = '" . $sku . "'");
            $ms_attr_0 = ($result->RecordCount() > 0) ? $result->fields['attr_0'] : '';
            $attr_name_0 = ($result->RecordCount() > 0) ? $result->fields['attr_name_0'] : '';
            $ms_attr_1 = ($result->RecordCount() > 0) ? $result->fields['attr_1'] : '';
            $attr_name_1 = ($result->RecordCount() > 0) ? $result->fields['attr_name_1'] : '';
            $code1_array = array();
            $code2_array = array();
            if ($ms_attr_0) {
                $temp = explode(',', $ms_attr_0);
                for ($i = 0; $i < count($temp); $i++) {
                    $code1 = substr($temp[$i], 0, strpos($temp[$i], ':'));
                    $code1_array[] = $code1;
                }
            }
            if ($ms_attr_1) {
                $temp = explode(',', $ms_attr_1);
                for ($i = 0; $i < count($temp); $i++) {
                    $code2 = substr($temp[$i], 0, strpos($temp[$i], ':'));
                    $code2_array[] = $code2;
                }
            }
        }
        if (!empty($code1_array) && !empty($code2_array)) {
            foreach ($code1_array as $key => $firstcode) {
                if (is_array($code2_array)) {
                    foreach ($code2_array as $seconddcode) {
                        if (!empty($firstcode) && !empty($seconddcode)) {
                            $subSku = $sku . '-' . $firstcode . $seconddcode;
                            $where .= ',"' . $subSku . '"';
                        }
                    }
                }
            }
        } else if (!empty($code1_array)) {
            foreach ($code1_array as $key => $firstcode) {
                if (!empty($firstcode)) {
                    $subSku = $sku . '-' . $firstcode;
                    $where .= ',"' . $subSku . '"';
                }
            }
        } else if (!empty($code2_array)) {
            foreach ($code2_array as $key => $seconddcode) {
                if (!empty($seconddcode)) {
                    $subSku = $sku . '-' . $seconddcode;
                    $where .= ',"' . $subSku . '"';
                }
            }
        }

        $field_list = array('id', 'sku', 'inactive', 'inventory_type', 'quantity_on_hand', 'quantity_on_order',
            'description_short', 'full_price', 'item_cost');


        $query_raw = "select " . implode(', ', $field_list) . " from " . TABLE_INVENTORY . ' where sku in (' . $where . ')';

        $query_result = $db->Execute($query_raw);

        $include_header = false;
        $include_footer = true;
        $include_template = 'template_main.php';
        define('PAGE_TITLE', 'Master Stock Item');
        break;
}
?>