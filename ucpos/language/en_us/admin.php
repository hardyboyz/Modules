<?php
// +-----------------------------------------------------------------+
// |                   UcBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright (c) 2008, 2009, 2010, 2011 UcSoft, LLC             |
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
//  Path: /modules/ucpos/language/en_us/admin.php
//

// Module information
define('MODULE_UCPOS_TITLE','UcPOS Module');
define('MODULE_UCPOS_DESCRIPTION','The UcPOS module provides a Point of Sale interface. This module is a supplement to the ucbooks module and is not a replacement for it.');
// Headings
define('BOX_UCPOS_ADMIN','Point of Sale Administration');
// General Defines
define('UCPOS_REQUIRE_ADDRESS_DESC','Require address information for every POS/POP Sale?');
define('UCPOS_RECEIPT_PRINTER_NAME_DESC','Sets then name of the printer to use for printing receipts as defined in the printer preferences for the local workstation.');
define('UCPOS_RECEIPT_PRINTER_STARTING_LINE_DESC','Here you can add code to that should be in the header paper of the receipt.<br>Seperate the codes by a : and lines by a , like: <i>27:112:48:55:121,27:109</i><br>The codes are a numbers of the chr ie chr(13) is 13<br><b>Only put in code no text this could result in errors.</b> view your printer documentation for the right codes.');
define('UCPOS_RECEIPT_PRINTER_CLOSING_LINE_DESC','Here you can add code to open you drawer and /or cut the receipt.<br>Seperate the codes by a : and lines by a , like: <i>27:112:48:55:121,27:109</i><br>The codes are a numbers of the chr ie chr(13) is 13<br><b>Only put in code no text this could result in errors.</b> ');
?>