<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/functions/zencart.php
//

function doCURLRequest($method = 'GET', $url, $vars) {
  global $messageStack;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_VERBOSE, 0);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 30 seconds 
  if (strtoupper($method) == 'POST') {
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
  }
  $data  = curl_exec($ch);
  $error = curl_error($ch);
  curl_close($ch);
  if ($data != '') {
	return $data;
  } else {
	$messageStack->add('cURL error: ' . $error, 'error');
	return false; 
  }
}

function pull_down_price_sheet_list() {
  global $db;
  $output = array(array('id' => '0', 'text' => TEXT_NONE));
  $sql = "select distinct sheet_name from " . TABLE_PRICE_SHEETS . " 
	where '" . date('Y-m-d',time()) . "' >= effective_date and inactive = '0'";
  $result = $db->Execute($sql);
  while(!$result->EOF) {
    $output[] = array('id' => $result->fields['sheet_name'], 'text' => $result->fields['sheet_name']);
    $result->MoveNext();
  }
  return $output;
}

?>