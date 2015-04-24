<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/language/en_us/language.php
//

// Headings 
define('BOX_OPENCART_ADMIN','OpenCart Configuration');
// General Defines
define('OPENCART_CONFIRM_MESSAGE','Your order shipped %s via %s %s, tracking number: %s');
define('OPENCART_BULK_UPLOAD_SUCCESS','Successfully uploaded %s item(s) to the OpenCart e-store.');
define('OPENCART_TEXT_ERROR','Error # ');
define('OPENCART_IVENTORY_UPLOAD','OpenCart Upload');
define('OPENCART_BULK_UPLOAD_TITLE','Bulk Upload');
define('OPENCART_BULK_UPLOAD_INFO','Bulk upload all products selected to be displayed in the OpenCart e-commerce site. Images are not included unless the checkbox is checked.');
define('OPENCART_BULK_UPLOAD_TEXT','Bulk upload products to e-store');
define('OPENCART_INCLUDE_IMAGES','Include Images');
define('OPENCART_BULK_UPLOAD_BTN','Bulk Upload');
define('OPENCART_PRODUCT_SYNC_TITLE','Synchronize Products');
define('OPENCART_PRODUCT_SYNC_INFO','Synchronize active products from the PhreeBooks database (set to show in the catalog and active) with current listings from OpenCart. Any SKUs that should not be listed on OpenCart are displayed. They need to be removed from OpenCart manually through the OpenCart admin interface.');
define('OPENCART_PRODUCT_SYNC_TEXT','Synchronize products with e-store');
define('OPENCART_DELETE_OPENCART','Also delete products in OpenCart that are not flagged to be there.');
define('OPENCART_PRODUCT_SYNC_BTN','Synchronize');
define('OPENCART_SHIP_CONFIRM_TITLE','Confirm Shipments');
define('OPENCART_SHIP_CONFIRM_INFO','Confirms all shipments on the date selected from the Shipping Manager and sets the status in OpenCart. Completed orders and partially shipped orders are updated. Email notifications to the customer are not sent.');
define('OPENCART_SHIP_CONFIRM_TEXT','Send shipment confirmations');
define('OPENCART_TEXT_CONFIRM_ON','For orders shipped on');
define('OPENCART_SHIP_CONFIRM_BTN','Confirm Shipments');
// Error Messages
define('OPENCART_ERROR_NO_ITEMS','No inventory items were selected to upload to the OpenCart catalog. Looking for the checkbox field named catalog to identify items to be uploaded.');
define('OPENCART_ERROR_CONFRIM_NO_DATA','No records were found for this date to confirm with OpenCart.');
define('OPENCART_ERROR_NO_PRICE_SHEET','Couldn\'t find a default price level for price sheet: ');
define('OPENCART_INVALID_ACTION','Invalid action requested in OpenCart interface class. Aborting!');
define('OPENCART_INVALID_SKU','Error in inventory item id, could not find the record in the database');
// Javascrpt Defines
// Audit Log Messages
define('OPENCART_UPLOAD_PRODUCT','OpenCart Product Upload');
define('OPENCART_BULK_UPLOAD','OpenCart Bulk Upload');
define('OPENCART_PRODUCT_SYNC','OpenCart Product Sync');
define('OPENCART_SHIP_CONFIRM','OpenCart Ship Confirmation');

?>