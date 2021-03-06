<?php
// +-----------------------------------------------------------------+
// |                   bank_transfer Open Source ERP                    |
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
//  Path: /modules/bank_transfer/classes/tax_rates_vend.php
//
require_once(DIR_FS_MODULES . 'bank_transfer/classes/tax_rates.php');

class tax_rates_vend extends tax_rates {
	public $code        = 'tax_rates_vend'; // needs to match class name
    public $help_path   = '07.08.03.02';
    public $type        = 'v'; // choices are c for customers and v for vendors
  
}
?>