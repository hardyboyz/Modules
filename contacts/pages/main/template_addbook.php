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
//  Path: /modules/contacts/pages/main/template_addbook.php
//
?>
<div id="tab_addbook">
<?php // *********************** SHIPPING ADDRESSES  *************************************
  if (defined('MODULE_SHIPPING_STATUS')) { // show shipping address for customers and vendors
    
    echo '    <h5>' . ACT_CATEGORY_S_ADDRESS . '</h5><br/><br/>';
    echo '    <table id="'.$type.'s_address_form" class="ui-widget" style="border-style:none;width:100%;">';
    echo '      <tr><td>' . ACT_SHIPPING_MESSAGE . '</td></tr>';
    echo draw_address_fields($cInfo, $type.'s',false, true,false);
    echo '    </table>';
    
  }
  // *********************** BILLING/BRANCH ADDRESSES  *********************************
   
    echo '  <h5>' . ACT_CATEGORY_B_ADDRESS . '</h5> <br/><br/>'; 
    echo '  <table id="'.$type.'b_address_form" class="ui-widget" style="border-collapse:collapse;width:100%;">';
    echo '    <tr><td>' . ACT_BILLING_MESSAGE . '</td></tr>';
    echo draw_address_fields($cInfo, $type.'b',false,true,false);
    echo '  </table>';
   
?>
</div>