<?php

// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/classes/opencart.php
//
/* * *************   hook for custom actions  ************************** */
$custom_path = DIR_FS_MODULES . 'opencart/custom/pages/main/extra_actions.php';
if (file_exists($custom_path)) {
    include_once($custom_path);
}

class opencart {

    var $arrOutput = array();
    var $resParser;
    var $strXML;

    function __construct() {
        
    }

    function submitXML($id, $action = '', $hide_success = false, $inc_image = true) {
        global $messageStack;
        switch ($action) {
            case 'product_ul': if (!$this->buildProductUploadXML($id, $inc_image))
                    return false; break;
            case 'product_sync': if (!$this->buildProductSyncXML())
                    return false; break;
            case 'confirm': if (!$this->buildConfirmXML())
                    return false; break;
            case 'download_inv': if (!$this->buildDownloadInvXML($id))
                    return false; break;
            default: $messageStack->add(OPENCART_INVALID_ACTION, 'error');
                return false;
        }
//echo 'Submit to url: ' . OPENCART_URL . ' and XML string = <pre>' . htmlspecialchars($this->strXML) . '</pre><br />';
        $this->response = doCURLRequest('POST', OPENCART_URL, $this->strXML);
//echo 'XML response (at the PhreeBooks side from OpenCart) => <pre>' . htmlspecialchars($this->response) . '</pre><br />' . chr(10);
        if (!$this->response)
            return false;
        if (!$results = xml_to_object($this->response))
            return false;
//echo 'Parsed string = '; print_r($results); echo '<br />';
        $this->result = $results->Response->Result;
        $this->code = $results->Response->Code;
        $this->text = $results->Response->Text;
        if ($this->code == 0) {
            if (!$hide_success)
                $messageStack->add($this->text, strtolower($this->result));
            return true;
        } else {
            $messageStack->add(OPENCART_TEXT_ERROR . $this->code . ' - ' . $this->text, strtolower($this->result));
            return false;
        }
    }

    /*     * ******************************** codeing by shaheen for download invoince ************************************************* */

    function buildDownloadInvXML($id) {
        global $db, $currencies, $messageStack;
        $result = $db->Execute("select * from " . TABLE_JOURNAL_MAIN . " where id = " . $id);
        $result1 = $db->Execute("select * from " . TABLE_JOURNAL_ITEM . " where gl_type in('sos','tax','ttl') and ref_id = " . $id);
        $taxrate = $db->Execute(' select description_short from tax_rate where tax_rate_id='.$result1->fields['taxable']);
        if ($result->RecordCount() <> 1) {
            $messageStack->add(OPENCART_INVALID_SKU, 'error');
            return false;
        }
        $request = new objectInfo();
        $request->Version = '2.00';
        $request->UserName = OPENCART_USERNAME;
        $request->UserPassword = OPENCART_PASSWORD;
        $request->Language = $_SESSION['language'];
        $request->Action = 'InvoiceDownload';
        $request->Reference = 'Download Invoice: ' . $result->fields['purchase_invoice_id'];
        $request->Contacts = new ObjectInfo(array(
                    'purchase_invoice_id' => $result->fields['purchase_invoice_id'],
                    'bill_primary_name' => $result->fields['bill_primary_name'],
                    'bill_contact' => $result->fields['bill_contact'],
                    'bill_address1' => $result->fields['bill_address1'],
                    'bill_address2' => $result->fields['bill_address2'],
                    'bill_city_town' => $result->fields['bill_city_town'],
                    'bill_state_province' => $result->fields['bill_state_province'],
                    'bill_postal_code' => $result->fields['bill_postal_code'],
                    'bill_country_code' => $result->fields['bill_country_code'],
                    'bill_telephone1' => $result->fields['bill_telephone1'],
                    'bill_email' => $result->fields['bill_email'],
                    'ship_contact' => $result->fields['ship_contact'],
                    'ship_address1' => $result->fields['ship_address1'],
                    'ship_address2' => $result->fields['ship_address2'],
                    'ship_city_town' => $result->fields['ship_city_town'],
                    'ship_state_province' => $result->fields['ship_state_province'],
                    'ship_postal_code' => $result->fields['ship_postal_code'],
                    'ship_country_code' => $result->fields['ship_country_code'],
                    'ship_telephone1' => $result->fields['ship_telephone1'],
                    'ship_email' => $result->fields['ship_email'],
                    'total' => $result->fields['total_amount']
                ));
        while (!$result1->EOF) {
            $request->Item[] = new ObjectInfo(array(
                        'gl_type' => $result1->fields['gl_type'],
                        'sku' => $result1->fields['sku'],
                        'qty' => $result1->fields['qty'],
                        'debit_amount' => $result1->fields['debit_amount'],
                        'credit_amount' => $result1->fields['credit_amount'],
                        'full_price' => $result1->fields['full_price'],
                        'description' => $result1->fields['description']
                    ));
            $result1->MoveNext();
        }
        $this->strXML = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $this->strXML .= "<Request>\n";
        $this->strXML .= object_to_xml($request);
        $this->strXML .= "</Request>\n";
        return true;
    }

    /*     * ******************************************************************************** */
    /*     * ********************************************************************************** */

//                           Product Upload XML string generation
    /*     * ********************************************************************************** */
    function buildProductUploadXML($id, $inc_image = true) {
        global $db, $currencies, $messageStack;
        $result = $db->Execute("select * from " . TABLE_INVENTORY . " where id = " . $id);
        if ($result->RecordCount() <> 1) {
            $messageStack->add(OPENCART_INVALID_SKU, 'error');
            return false;
        }
        $this->sku = $result->fields['sku'];
        $price_discounts = array();
        if (OPENCART_USE_PRICE_SHEETS == '1') {
            $sql = "select id, default_levels from " . TABLE_PRICE_SHEETS . " 
		where '" . date('Y-m-d') . "' >= effective_date and sheet_name = '" . OPENCART_PRICE_SHEET . "' and inactive = '0'";
            $default_levels = $db->Execute($sql);
            if ($default_levels->RecordCount() == 0) {
                $messageStack->add(OPENCART_ERROR_NO_PRICE_SHEET . OPENCART_PRICE_SHEET, 'error');
                return false;
            }
            $sql = "select price_levels from " . TABLE_INVENTORY_SPECIAL_PRICES . " 
		where inventory_id = " . $id . " and price_sheet_id = " . $default_levels->fields['id'];
            $special_levels = $db->Execute($sql);
            if ($special_levels->RecordCount() > 0) {
                $price_levels = $special_levels->fields['price_levels'];
            } else {
                $price_levels = $default_levels->fields['default_levels'];
            }
            $prices = inv_calculate_prices($result->fields['item_cost'], $result->fields['full_price'], $price_levels);
            foreach ($prices as $level => $amount) {
                $price_discounts[] = new objectInfo(array(
                            'DiscountLevel' => $level + 1,
                            'Quantity' => $amount['qty'],
                            'Amount' => $currencies->clean_value($amount['price']),
                        ));
            }
        }
        if ($inc_image && $result->fields['image_with_path']) { // image file
            if (strpos($result->fields['image_with_path'], '/') !== false) { // path exists, extract
                $image_path = substr($result->fields['image_with_path'], 0, strrpos($result->fields['image_with_path'], '/'));
                $image_filename = substr($result->fields['image_with_path'], strrpos($result->fields['image_with_path'], '/') + 1);
            } else {
                $image_path = '/';
                $image_filename = $result->fields['image_with_path'];
            }
            $filename = DIR_FS_MY_FILES . $_SESSION['company'] . '/inventory/images/' . $result->fields['image_with_path'];
            if (file_exists($filename)) {
                if ($handle = fopen($filename, "rb")) {
                    $contents = fread($handle, filesize($filename));
                    fclose($handle);
                    $image_data = base64_encode($contents);
                }
            } else {
                unset($image_data);
            }
        }
        // url encode all of the values to avoid upload bugs
//	foreach ($result->fields as $key => $value) $result->fields[$key] = urlencode($result->fields[$key]);
        $request = new objectInfo();
        $request->Version = '2.00';
        $request->UserName = OPENCART_USERNAME;
        $request->UserPassword = OPENCART_PASSWORD;
        $request->Language = $_SESSION['language'];
        $request->Action = 'ProductUpload';
        $request->Reference = 'Product Upload SKU: ' . $result->fields['sku'];
        $request->Product = new ObjectInfo(array(
                    'SKU' => $result->fields['sku'],
                    'ProductCategory' => $result->fields['category_id'],
                    'ProductSortOrder' => $result->fields['id'],
                    'ProductName' => $result->fields['description_short'],
                    'ProductModel' => $result->fields['description_short'],
                    'ProductDescription' => $result->fields['description_sales'],
                    'ProductURL' => $result->fields['spec_file'],
                    'ProductImageDirectory' => $image_path,
                    'ProductImageFileName' => $image_filename,
                    'ProductImageData' => $image_data,
                    'ProductTaxable' => $result->fields['item_taxable'] ? 'True' : 'False',
                    'TaxClass' => OPENCART_PRODUCT_TAX_CLASS,
                    'ProductWeight' => $result->fields['item_weight'],
                    'DateAdded' => $result->fields['creation_date'],
                    'DateUpdated' => $result->fields['last_update'],
                    'DateAvailable' => $result->fields['creation_date'],
                    'StockLevel' => $result->fields['quantity_on_hand'],
                    'Manufacturer' => $result->fields['manufacturer'],
                    'ProductPrice' => new objectInfo(array(
                        'MSRPrice' => $result->fields['full_price'],
                        'RetailPrice' => $result->fields['full_price'],
                        'PriceDiscounts' => $price_discounts,
                    )),
                ));
        // Hook for including customization of product information
        if (function_exists('opencart_product_upload'))
            $request = opencart_product_upload($request, $result->fields);

        $this->strXML = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $this->strXML .= "<Request>\n";
        $this->strXML .= object_to_xml($request);
        $this->strXML .= "</Request>\n";
        return true;
    }

    /*     * ********************************************************************************** */

//                           Product Syncronizer string generation
    /*     * ********************************************************************************** */
    function buildProductSyncXML() {
        global $db, $messageStack;
        $result = $db->Execute("select sku from " . TABLE_INVENTORY . " where catalog = '1'");
        if ($result->RecordCount() == 0) {
            $messageStack->add(OPENCART_ERROR_NO_ITEMS, 'error');
            return false;
        }
        $this->strXML = '<?xml version="1.0" encoding="UTF-8" ?>' . chr(10);
        $this->strXML .= '<Request>' . chr(10);
        $this->strXML .= xmlEntry('Version', '2.00');
        $this->strXML .= xmlEntry('UserName', OPENCART_USERNAME);
        $this->strXML .= xmlEntry('UserPassword', OPENCART_PASSWORD);
        $this->strXML .= xmlEntry('Language', $_SESSION['language']);
        $this->strXML .= xmlEntry('Action', 'ProductSync');
        $this->strXML .= xmlEntry('Reference', 'Product Syncronizer');
        $this->strXML .= xmlEntry('AutoDelete', $this->delete_opencart ? 'true' : 'false');
        $this->strXML .= '  <Product>' . chr(10);
        while (!$result->EOF) {
            $this->strXML .= '    ' . xmlEntry('SKU', urlencode($result->fields['sku']));
            $result->MoveNext();
        }
        $this->strXML .= '  </Product>' . chr(10);
        $this->strXML .= '</Request>' . chr(10);
//echo 'sending xml = '.htmlspecialchars($this->strXML).'<br>';
        return true;
    }

    /*     * ********************************************************************************** */

//                           Product Shipping Confirmation String Generation
    /*     * ********************************************************************************** */
    function buildConfirmXML() {
        global $db, $messageStack;
        $methods = $this->loadShippingMethods();
        $this->strXML = '<?xml version="1.0" encoding="UTF-8" ?>' . chr(10);
        $this->strXML .= '<Request>' . chr(10);
        $this->strXML .= xmlEntry('Version', '2.00');
        $this->strXML .= xmlEntry('UserName', OPENCART_USERNAME);
        $this->strXML .= xmlEntry('UserPassword', OPENCART_PASSWORD);
        $this->strXML .= xmlEntry('Language', $_SESSION['language']);
        $this->strXML .= xmlEntry('Operation', 'ShipConfirm');
        $this->strXML .= xmlEntry('Action', 'OrderConfirm');
        $this->strXML .= xmlEntry('Reference', 'Order Ship Confirmation');
        // fetch every shipment for the given post_date
        $result = $db->Execute("select ref_id, carrier, method, tracking_id from " . TABLE_SHIPPING_LOG . " 
	  where ship_date like '" . $this->post_date . " %'");
        if ($result->RecordCount() == 0) {
            $messageStack->add(OPENCART_ERROR_CONFRIM_NO_DATA, 'caution');
            return false;
        }
        // foreach shipment, fetch the PO Number (it is the OpenCart order number)
        while (!$result->EOF) {
            if (strpos($result->fields['ref_id'], '-') !== false) {
                $purchase_invoice_id = substr($result->fields['ref_id'], 0, strpos($result->fields['ref_id'], '-'));
            } else {
                $purchase_invoice_id = $result->fields['ref_id'];
            }
            $details = $db->Execute("select so_po_ref_id from " . TABLE_JOURNAL_MAIN . " 
	    where journal_id = 12 and purchase_invoice_id = '$purchase_invoice_id' order by id desc limit 1");
            // check to see if the order is complete
            if ($details->fields['so_po_ref_id']) {
                $details = $db->Execute("select closed, purchase_invoice_id from " . TABLE_JOURNAL_MAIN . " 
	        where id = '" . $details->fields['so_po_ref_id'] . "'");
                if ($details->RecordCount() == 1) {
                    $message = sprintf(OPENCART_CONFIRM_MESSAGE, $this->post_date, $methods[$result->fields['carrier']]['title'], $methods[$result->fields['carrier']][$result->fields['method']], $result->fields['tracking_id']);
                    $this->strXML .= '<Order>' . chr(10);
                    $this->strXML .= xmlEntry('ID', $details->fields['purchase_invoice_id']);
                    $this->strXML .= xmlEntry('Status', $details->fields['closed'] ? OPENCART_STATUS_CONFIRM_ID : OPENCART_STATUS_PARTIAL_ID);
                    $this->strXML .= xmlEntry('Message', $message);
                    $this->strXML .= '</Order>' . chr(10);
                }
            }
            $result->MoveNext();
        }
        $this->strXML .= '</Request>' . chr(10);
        return true;
    }

    /*     * ********************************************************************************** */

//                           Support Functions
    /*     * ********************************************************************************** */
    function loadShippingMethods() {
        global $shipping_defaults;
        $method_array = array();
        // load standard shipping methods
        $methods = scandir(DIR_FS_MODULES . 'shipping/methods/');
        foreach ($methods as $method) {
            if ($method <> '.' && $method <> '..' && defined('MODULE_SHIPPING_' . strtoupper($method) . '_STATUS')) {
                $method_array[] = $method;
            }
        }
        $output = array();
        $choices = array_keys($shipping_defaults['service_levels']);
        if (sizeof($method_array) > 0)
            foreach ($method_array as $method) {
                load_method_language(DIR_FS_MODULES . 'shipping/methods/', $method);
                $output[$method]['title'] = constant('MODULE_SHIPPING_' . strtoupper($method) . '_TEXT_TITLE');
                foreach ($choices as $value) {
                    $output[$method][$value] = defined($method . '_' . $value) ? constant($method . '_' . $value) : "";
                }
            }
        return $output;
    }

}

?>