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
//  Path: /modules/payment/methods/moneyorder/moneyorder.php
//
// Revision history
// 2011-07-01 - Added version number for revision control
define('MODULE_PAYMENT_CREDITCARD_VERSION','3.3');
require_once(DIR_FS_MODULES . 'payment/classes/payment.php');
class creditcard extends payment {
  public $code        = 'creditcard'; // needs to match class name
  public $title 	  = MODULE_PAYMENT_CREDITCARD_TEXT_TITLE;
  public $description = MODULE_PAYMENT_CREDITCARD_TEXT_DESCRIPTION;
  public $sort_order  = 60;
  
  public function __construct(){
  	parent::__construct();
    $this->payment_fields = implode(':', array($this->field_0));
    $this->key[] = array('key' => 'MODULE_PAYMENT_CREDITCARD_PAYTO', 'default' => COMPANY_NAME, 'text' => MODULE_PAYMENT_CREDITCARD_PAYTO_DESC);
  }

  function selection() {
    global $order;
    return array(
	  'id'     => $this->code,
      'page'   => $this->title,
	  'fields' => array(
		array(
		  'title' => MODULE_PAYMENT_CREDITCARD_TEXT_REF_NUM,
		  'field' => html_input_field($this->code . '_field_0', $this->field_0, 'size="33" maxlength="32"'),
		)
	  ),
	);
  }
}
?>