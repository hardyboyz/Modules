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
//  Path: /modules/contacts/pages/main/template_c_payment.php
//

?>
<div id="tab_payment">
  
    <h5><?php echo ACT_CATEGORY_P_ADDRESS; ?></h5><br/><br/>
    <table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto; width: 100%">
     <tbody id="pmt_table" class="ui-widget-content">
<?php
	if (sizeof($cInfo->payment_data) > 0) {
		$field  = '<tr><td colspan="2">';
		$field .= '<table class="ui-widget" style="border-collapse:collapse;width:600px;margin-left:auto;margin-right:auto;">';
		$field .= ' <thead class="ui-widget-header">';
		$field .= '<tr>' . chr(10);
		$field .= '  <th>' . ACT_CARDHOLDER_NAME . '</th>' . chr(10);
		$field .= '  <th>' . ACT_CARD_HINT       . '</th>' . chr(10);
		$field .= '  <th>' . ACT_EXP             . '</th>' . chr(10);
		$field .= '  <th>' . TEXT_ACTION         . '</th>' . chr(10);
		$field .= '</tr>' . chr(10);
		$field .= ' </thead>';
		$field .= ' <tbody id="pmt_table" class="ui-widget-content">';
		$odd = true;
		foreach ($cInfo->payment_data as $payment) {
			$field .= '<tr id="tr_pmt_'.$payment['id'].'" class="'.($odd?'odd':'even').'" style="cursor:pointer">';
			$field .= '  <td onclick="getPayment(' . $payment['id'] . ')">' . $payment['name'] . '</td>' . chr(10);
			$field .= '  <td onclick="getPayment(' . $payment['id'] . ')">' . $payment['hint'] . '</td>' . chr(10);
			$field .= '  <td onclick="getPayment(' . $payment['id'] . ')">' . $payment['exp'] . '</td>' . chr(10);
			$field .= '  <td align="center">';
			$field .= html_icon('actions/edit-find-replace.png', TEXT_EDIT, 'small', 'onclick="getPayment(' . $payment['id'] . ')"') . chr(10);
			$field .= '&nbsp;' . html_icon('emblems/emblem-unreadable.png', TEXT_DELETE, 'small', 'onclick="if (confirm(\'' . ACT_WARN_DELETE_PAYMENT . '\')) deletePayment(' . $payment['id'] . ');"') . chr(10);
			$field .= '  </td>' . chr(10);
			$field .= '</tr>' . chr(10);
			$odd = !$odd;
		}
		$field .= ' </thead>';
		$field .= '</table></td></tr>' . chr(10);
		echo $field;
	}
    if (!$_SESSION['admin_encrypt']) { ?>
      <tr><td colspan="2" class="ui-state-highlight"><?php echo ACT_NO_ENCRYPT_KEY_ENTERED; ?></td></tr>
<?php } ?>
      
      <tr><th colspan="2"><br/><br/><?php echo ACT_PAYMENT_MESSAGE; ?> <br/><br/></th></tr>
      <tr>
          <td >
              <table style="width: 100%">
                  <tr>
                      <td width="50%">
                          <table style="width: 100%">
                            <tr>
                                <td width="40%"><span class="form_label" ><?php echo ACT_CARDHOLDER_NAME; ?> </span></td>
                                <td width="4%">:</td>
                                <td width="56%">
                                    <?php echo html_input_field('payment_cc_name', $cInfo->payment_cc_name, 'size="50"'); ?>
                                    <?php echo html_icon('actions/view-refresh.png', TEXT_RESET, 'small', 'onclick="clearPayment()"'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%"><span class="form_label" ><?php echo ACT_PAYMENT_CREDIT_CARD_EXPIRES; ?> </span></td>
                                <td width="4%">:</td>
                                <td width="56%"><?php echo html_pull_down_menu('payment_exp_month', $expires_month, $cInfo->payment_exp_month) . '&nbsp;'  
		    		    ?></td>
                            </tr>
                            <tr>
                                <td width="40%"><span class="form_label" ><?php echo ACT_PAYMENT_CREDIT_CARD_CVV2; ?> </span></td>
                                <td width="4%">:</td>
                                <td width="56%"> <?php echo html_input_field('payment_cc_cvv2', $cInfo->payment_cc_cvv2, 'size="5" maxlength="4"'); ?> </td>
                            </tr>
                            
                          </table>
                      </td>
                      <td width="50%">
                          <table style="width: 100%">
                              
                              <tr>
                                <td width="40%"><span class="form_label" ><?php echo ACT_PAYMENT_CREDIT_CARD_NUMBER; ?> </span></td>
                                <td width="4%">:</td>
                                <td width="56%"><?php echo html_input_field('payment_cc_number', $cInfo->payment_cc_number, 'size="20"'); ?></td>
                            </tr>
                            <tr>
                                <td width="40%"></td>
                                <td width="4%"></td>
                                <td width="56%"> <?php echo html_pull_down_menu('payment_exp_year', $expires_year, $cInfo->payment_exp_year); ?> </td>
                            </tr>
                          </table>
                      </td>
                      
                  </tr>
              </table>
             
          </td>
          
      </tr>
      <tr><td colspan="2"><?php echo '&nbsp;'; ?></td></tr>
      
<!--	  <tr>
	    <td align="right"><?php echo ACT_CARDHOLDER_NAME; ?></td>
            <td><?php echo html_input_field('payment_cc_name', $cInfo->payment_cc_name, 'size="50"'); ?>
		<?php echo html_icon('actions/view-refresh.png', TEXT_RESET, 'small', 'onclick="clearPayment()"'); ?></td>
	  </tr>
	  <tr>
	    <td align="right"><?php echo ACT_PAYMENT_CREDIT_CARD_NUMBER; ?></td>
		<td><?php echo html_input_field('payment_cc_number', $cInfo->payment_cc_number, 'size="20"'); ?></td>
	  </tr>-->
<!--	  <tr>
	    <td align="right"><?php echo ACT_PAYMENT_CREDIT_CARD_EXPIRES; ?></td>
		<td><?php echo html_pull_down_menu('payment_exp_month', $expires_month, $cInfo->payment_exp_month) . '&nbsp;' . 
		    		   html_pull_down_menu('payment_exp_year', $expires_year, $cInfo->payment_exp_year); ?></td>
	  </tr>-->
<!--	  <tr>
	    <td align="right"><?php echo ACT_PAYMENT_CREDIT_CARD_CVV2; ?></td>
		<td><?php echo html_input_field('payment_cc_cvv2', $cInfo->payment_cc_cvv2, 'size="5"'); ?></td>
	  </tr>-->
	 </tbody>
    </table>
  
</div>
