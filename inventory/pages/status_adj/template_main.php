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
//  Path: /modules/inventory/pages/status_adj/template_main.php
//
echo html_form('search_form', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo',   '') . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['save']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['print']['show']    = false;
$toolbar->add_icon('new', 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT,  'module=inventory&amp;page=transfer', 'SSL') . '\'"', $order = 2);
// pull in extra toolbar overrides and additions
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
// add the help file index and build the toolbar
$toolbar->add_help('07.04.02');
if ($search_text) $toolbar->search_text = $search_text;
$toolbar->search_period = $acct_period;
echo $toolbar->build_toolbar($add_search = true, $add_period = true); 
// Build the page
$list_types = ($adj_type == 'xfr') ?  BOX_INV_TRANSFER : ORD_TEXT_16_WINDOW_TITLE;
?>
<h1><?php echo PAGE_TITLE; ?></h1>
<div style="float:right"><?php echo $query_split->display_links($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['list']); ?></div>
<div><?php echo $query_split->display_count($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['list'], TEXT_DISPLAY_NUMBER . $list_types); ?></div>
<table class="ui-widget" style="border-collapse:collapse;width:100%">
 <thead class="ui-widget-header">
    <tr><?php  echo $list_header; ?></tr>
 </thead>
 <tbody class="ui-widget-content">
    <?php
    $odd = true; 
    while (!$query_result->EOF) { 
    $currentref = $query_result->fields['purchase_invoice_id'];
    if ($previousref <> $currentref) {//if xfr only display first line
$previousref = $currentref;
$oID            = $query_result->fields['id'];
if ($adj_type == 'xfr') {
    $link_page = html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=transfer&amp;oID=' . $oID . '&amp;action=edit', 'SSL');
}else{
    $link_page = html_href_link(FILENAME_DEFAULT, 'module=inventory&amp;page=adjustments&amp;oID=' . $oID . '&amp;action=edit', 'SSL');
}
	  $sku_text = $query_result->fields['sku_cnt'] > 1 ? sprintf(TEXT_ADJ_ITEMS, $query_result->fields['sku_cnt']) : $query_result->fields['sku'];
	  $sku_desc = $query_result->fields['sku_cnt'] > 1 ? TEXT_MULTIPLE_ENTRIES : $query_result->fields['description'];
	?>
   
    <tr class="<?php echo $odd?'odd':'even'; ?>" style="cursor:pointer">
<td onclick="window.open('<?php echo $link_page; ?>','_blank')"><?php echo gen_locale_date($query_result->fields['post_date']); ?></td>
	  <td align="center" onclick="window.open('<?php echo $link_page; ?>','_blank')"><?php echo $query_result->fields['purchase_invoice_id']; ?></td>
	  <td align="center" onclick="window.open('<?php echo $link_page; ?>','_blank')"><?php echo ($adj_type == 'xfr') ? -$query_result->fields['qty'] : $query_result->fields['qty']; ?></td>
	  <td align="center" onclick="window.open('<?php echo $link_page; ?>','_blank')"><?php echo $sku_text; ?></td>
	  <td onclick="window.open('<?php echo $link_page; ?>','_blank')"><?php echo $sku_desc; ?></td>
	  <?php 
	    if (ENABLE_MULTI_BRANCH) {
	  	  $store_name = $query_result->fields['store_id'] == '0' ? COMPANY_ID : gen_get_contact_name($query_result->fields['store_id']);
	      echo '	   <td align="center" onclick="window.open(\'' . $link_page. '\',\'_blank\')">' . $store_name . '</td>' . chr(10); 
		}
	    if (ENABLE_MULTI_BRANCH && $adj_type == 'xfr') {
	  	  $store_name = $query_result->fields['bill_acct_id'] == '0' ? COMPANY_ID : gen_get_contact_name($query_result->fields['bill_acct_id']);
	      echo '	   <td align="center" onclick="window.open(\'' . $link_page. '\',\'_blank\')">' . $store_name . '</td>' . chr(10); 
		}

//actions
?>
         <td align="right">
<?php
         echo html_icon('actions/document-print.png',    TEXT_PRINT,  'small', 'onclick="printOrder('. $oID . ')"') . chr(10);
 echo html_icon('actions/edit-find-replace.png', TEXT_EDIT,   'small', 'onclick="window.open(\'' . $link_page . '\',\'_blank\')"') . chr(10);
?>
         </td>

    </tr>
	  <?php 
if ($adj_type == 'xfr') $odd = !$odd;
} //end if xfr only display first line
$query_result->MoveNext();
if ($adj_type == 'adj')   $odd = !$odd;
	} ?>
  </tbody>
</table>
<div style="float:right"><?php echo $query_split->display_links($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['list']); ?></div>
<div><?php echo $query_split->display_count($query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['list'], TEXT_DISPLAY_NUMBER . $list_types); ?></div>
</form>
