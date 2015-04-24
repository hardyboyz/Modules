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
//  Path: /modules/work_orders/pages/main/template_new.php
//
echo html_form('work_orders', FILENAME_DEFAULT, gen_get_all_get_params(array('action', 'id')), 'post', '');
// include hidden fields
echo html_hidden_field('todo', '')                . chr(10);
echo html_hidden_field('id', $id)                 . chr(10);
echo html_hidden_field('wo_id', $wo_id)           . chr(10);
echo html_hidden_field('sku_id', $sku_id)         . chr(10);
echo html_hidden_field('store_id', $store_id)     . chr(10);
echo html_hidden_field('close_date', $close_date) . chr(10);
// customize the toolbar actions
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL') . '\'"';
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=work_orders&page=main&"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
if (!$hide_save && $security_level > 1) {
  $toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
} else {
  $toolbar->icon_list['save']['show'] = false;
}
$toolbar->icon_list['print']['params'] = 'onclick="submitToDo(\'print\')"';
if ($security_level > 1) $toolbar->add_icon('new', 'onclick="submitToDo(\'new\')"', $order = 2);
$toolbar->add_help('07.04.WO.04');
//echo $toolbar->build_toolbar(); 
if($_SESSION['set_security'] != 'yes'){
    $toolbar->icon_list['new']['show'] = false;
    $toolbar->icon_list['open']['show'] = false;
}
?>
<style>
        .input_container1 {

    }
    .input_container1 input {
        width: 200px;
        padding: 3px;
        border: 1px solid #cccccc;
        border-radius: 0;
    }
    .input_container1 ul {
        background: none repeat scroll 0 0 #b7f3f3;
        border: 1px solid #000000;
        max-height: 200px;
        list-style: none outside none;
        overflow-y: scroll;
        position: absolute;
        width: 205px;
        z-index: 1;
        margin: 0px;
        padding: 0px;

    }
    .input_container1 ul li {
        padding: 2px;
        cursor: pointer;
    }
    .input_container1 ul li:hover {
        background: #eaeaea;
    }
    #item_list {
        display: none;
    }
</style>
<!--Top Error Message Start -->
<div class="bottom_btn" id="bottom_btn">
    <?php
    echo $toolbar->build_toolbar();
    ?>
</div>
<!--Top Error Message End -->
<h1><?php echo PAGE_TITLE; ?></h1>
<table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto; width: 100%">
    <tr>
        <td width="50%">
            <table width="100%">
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo TEXT_SKU; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%">
                       <span class="input_container1"> <?php echo html_input_field('sku', $sku, ($id ? 'readonly="readonly" ' : '') . 'size="' . (MAX_INVENTORY_SKU_LENGTH + 1) . '"autocomplete="off" maxlength="' . MAX_INVENTORY_SKU_LENGTH . '"') . '&nbsp;';
                            if (!$id) echo html_icon('actions/system-search.png', TEXT_SEARCH, 'small', 'align="top" style="cursor:pointer" onclick="InventoryList(1)"'); 
                        ?>
                    <ul id="item_list"></ul></span></td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo TEXT_POST_DATE; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"><?php echo html_calendar_field($cal_date); ?></td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo TEXT_WO_TITLE; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"><?php echo html_input_field('wo_title', $wo_title, 'readonly="readonly" size="33"') . '&nbsp;'; ?></td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo TEXT_SPECIAL_NOTES; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"><?php echo html_textarea_field('notes', 40, 3, $notes, $params = ''); ?></td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
            </table>
        </td>
        <td width="50%">
            <table width="100%">
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo TEXT_QUANTITY; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"><?php echo html_input_field('qty', $qty, 'size="7" maxlength="5" onchange="fetchBOMList()"'); ?></td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo TEXT_PRIORITY; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"> <?php echo html_pull_down_menu('priority', $priority_list, $priority); ?></td>
                </tr>
                <tr>
                    <td width="40%"><span class="form_label"><?php echo TEXT_CLOSE; ?> </span></td>
                    <td width="4%">:</td>
                    <td width="56%"><?php echo html_checkbox_field('closed', '1', $closed ? true : false) . ($closed ? (' ' . gen_locale_date($close_date)) : ''); ?></td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto; width:100%">
 <thead class="ui-widget-header">
	<tr>
	  <th><?php echo TEXT_STEP; ?></th>
	  <th><?php echo TEXT_TASK_NAME; ?></th>
	  <th><?php echo TEXT_DESCRIPTION; ?></th>
	</tr>
 </thead>
 <tbody id="table_tasks" class="ui-widget-content">
<?php
  $odd = true;
  if ($action == 'edit') {
    foreach ($step_list as $value) {
	  $mfg_id = ($value['mfg'] == '1') ? get_user_name($value['mfg_id']) : '-';
	  $qa_id  = ($value['qa'] == '1')  ? get_user_name($value['qa_id'])  : '-';
	  echo '  <tr class="' . ($odd?'odd':'even') . '">' . chr(10);
	  echo '    <td align="center">' . $value['step'] . '</td>' . chr(10);
	  echo '    <td>' . $value['task_name']    . '</td>' . chr(10);
	  echo '    <td>' . $value['description']  . '</td>' . chr(10);
	  echo '    <td align="center">' . $mfg_id . '</td>' . chr(10);
	  echo '    <td align="center">' . $qa_id  . '</td>' . chr(10);
	  echo '    <td align="center">' . ($value['complete'] == '1' ? TEXT_YES : TEXT_NO) . '</td>' . chr(10);
	  echo '  </tr>' . chr(10);
	  $odd = !$odd;
	}
  } else {
    echo '<tr><td>&nbsp;</td></tr>';
  }
?>
 </tbody>
</table>
 <div class="bottom_btn">
      <?php echo $toolbar->build_toolbar();  ?>
  </div>
</form>

<br/>


