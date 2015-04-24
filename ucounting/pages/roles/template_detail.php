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
//  Path: /modules/ucounting/pages/roles/template_roles.php
//
echo html_form('roles', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
// include hidden fields
echo html_hidden_field('todo',   '')        . chr(10);
echo html_hidden_field('rowSeq', $admin_id) . chr(10);
// customize the toolbar actions
//$toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL') . '\'"';
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=ucounting&page=roles&list=1&sf=&so=&"';
$toolbar->icon_list['open']['show']     = false;
$toolbar->icon_list['delete']['show']   = false;
$toolbar->icon_list['print']['show']    = false;
if ($security_level > 2) {
  $toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
} else {
  $toolbar->icon_list['save']['show']   = false;
}
if (count($extra_toolbar_buttons) > 0) foreach ($extra_toolbar_buttons as $key => $value) $toolbar->icon_list[$key] = $value;
$toolbar->add_help('07.08.07');
//echo $toolbar->build_toolbar();
// Build the page
?>
<style type="text/css">
    .radio_btn{width: 20px}
</style>
<div class="bottom_btn" id="bottom_btn">
    <?php echo $toolbar->build_toolbar();  ?>
</div>
<h1><?php echo PAGE_TITLE; ?></h1>
  
  
  <table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto; width: 100%;">
   <tbody class="ui-widget-content">
       <tr>
           <td colspan="3" align="left">
               <h5><?php echo TEXT_PROFILE; ?></h5>
           </td>
       </tr>
       <tr>
           <td width="33">
               <br/><br/>
               <span class="form_label"><?php echo TEXT_ROLE_NAME  ?> :</span><br/>
               <?php echo  html_input_field('admin_name', $uInfo->admin_name, 'size="25"'); ?>
               <br/><br/>
               <span class="form_label"><?php echo GEN_DEFAULT_STORE ?> :</span><br/>
               <?php echo  html_pull_down_menu('def_store_id',  gen_get_store_ids(), $error ? $_POST['def_store_id'] : $uInfo->def_store_id, ''); ?>
               <br/> <br/>
               <span class="form_label"><?php echo GEN_RESTRICT_STORE ?></span>
               <?php echo html_checkbox_field('restrict_store', '1', (($error && $_POST['restrict_store']) || $uInfo->restrict_store) ? true : false); ?>
               <br/><br/>
               <span class="form_label"><?php echo GEN_RESTRICT_PERIOD ?></span>
               <?php echo html_checkbox_field('restrict_period', '1', (($error && $_POST['restrict_period']) || $uInfo->restrict_period) ? true : false); ?><br/><br/>
               
               
           </td>
           <td width="33">
               <br/><br/>
               <span class="form_label"><?php echo TEXT_INACTIVE; ?> :</span>
               <?php echo  html_checkbox_field('inactive', '1', ($uInfo->inactive ? true : false)); ?>
               <br/><br/>
               <br/>
               <span class="form_label"><?php echo GEN_DEF_CASH_ACCT ?> :</span><br/>
               <?php echo   html_pull_down_menu('def_cash_acct', gen_coa_pull_down(), $error ? $_POST['def_cash_acct'] : $uInfo->def_cash_acct, ''); ?>
               <br/><br/>
               <span class="form_label"><?php echo GEN_DEF_AR_ACCT ?> :</span><br/>
               <?php echo  html_pull_down_menu('def_ar_acct', gen_coa_pull_down(), $error ? $_POST['def_ar_acct'] : $uInfo->def_ar_acct, ''); ?>
               <br/><br/>
               <span class="form_label"><?php echo GEN_DEF_AP_ACCT ?></span><br/>
               <?php echo html_pull_down_menu('def_ap_acct', gen_coa_pull_down(), $error ? $_POST['def_ap_acct'] : $uInfo->def_ap_acct, ''); ?>
               <br/><br/>
               
           </td>
           <td width="33">
               
           </td>
       </tr>
       <tr>
           <td colspan="3" align="left">
               <h5><?php echo TEXT_SECURITY_SETTINGS; ?></h5>
           </td>
       </tr>
       <tr>
           <td colspan="3">
               <span class="form_label"><?php echo TEXT_FILL_ALL_LEVELS; ?> :</span><br/>
               <?php echo html_pull_down_menu('fill_all', $fill_all_values, '-1', 'onchange="submitToDo(\'fill_all\')"'); ?>
               <br/><br/>
               <div id="accesstabs">
                    <ul>
                <?php 
                foreach ($pb_headings as $key => $value) {
                if ($value['text'] == TEXT_HOME || $value['text'] == TEXT_LOGOUT) continue;
                echo add_tab_list('tab_' . $key, $value['text']) . chr(10);
                }
                ?>
                    </ul>
                <?php
                $settings     = gen_parse_permissions($uInfo->admin_security);
                $column_break = true;
                // array pb_headings is defined in /includes/header_navigation.php
                foreach ($pb_headings as $key => $menu_heading) {
                if ($menu_heading['text'] == TEXT_HOME || $menu_heading['text'] == TEXT_LOGOUT) continue;
                        echo '<div id="tab_' . $key . '">' . chr(10);
                        echo '<table class="ui-widget" style="border-collapse:collapse;margin-left:auto;margin-right:auto; width:100%">' . chr(10);
                        echo '<thead class="ui-widget-header">' . chr(10);
                        echo '<tr>' . chr(10);
                        echo '<th width="50%">&nbsp;</th>' . chr(10);
                        echo '<th width="10%" nowrap="nowrap">' . TEXT_FULL      . '</th>' . chr(10);
                        echo '<th width="10%" nowrap="nowrap">' . TEXT_EDIT      . '</th>' . chr(10);
                        echo '<th width="10%" nowrap="nowrap">' . TEXT_ADD       . '</th>' . chr(10);
                        echo '<th width="10%" nowrap="nowrap">' . TEXT_READ_ONLY . '</th>' . chr(10);
                        echo '<th width="10%" nowrap="nowrap">' . TEXT_NONE      . '</th>' . chr(10);
                        echo '</tr>' . chr(10);
                        echo '</thead><tbody class="ui-widget-content">' . chr(10);
                        $odd = true;
                        foreach ($menu as $item)  {
                        if (isset($item['heading'])) {
                                if ($item['heading'] == $menu_heading['text']) {
                                if ($item['text'] == TEXT_REPORTS && $item['heading'] <> MENU_HEADING_TOOLS) continue;  // special case for reports listings not in Tools menu
                                $checked = array();
                                if ($item['hide'] === true) {
                                        continue; // skip if menu only item
                                } elseif (isset($settings[$item['security_id']])) {
                                        $checked[0] = false;
                                        $checked[$settings[$item['security_id']]] = true;
                                } elseif ($error) {
                                        $checked[0] = false;
                                        $checked[$_POST['sID_' . $item['security_id']]] = true;
                                } else {
                                        $checked[0] = true;	// default to no access
                                }
                                echo '<tr valign="top" class="' . ($odd?'odd':'even') . '" style="cursor:pointer">';
                                echo '  <td>' . $item['text'] . '</td>' . chr(10);
                                echo '  <td align="center">' . html_radio_field('sID_' . $item['security_id'], '4', $checked[4]) . '</td>' . chr(10);
                                echo '  <td align="center">' . html_radio_field('sID_' . $item['security_id'], '3', $checked[3]) . '</td>' . chr(10);
                                echo '  <td align="center">' . html_radio_field('sID_' . $item['security_id'], '2', $checked[2]) . '</td>' . chr(10);
                                echo '  <td align="center">' . html_radio_field('sID_' . $item['security_id'], '1', $checked[1]) . '</td>' . chr(10);
                                echo '  <td align="center">' . html_radio_field('sID_' . $item['security_id'], '0', $checked[0]) . '</td>' . chr(10);
                                echo '</tr>' . chr(10);
                                $odd = !$odd;
                                }
                        }
                        }
                        echo '</tbody></table></div>' . chr(10);
                } ?>
              </div>
           </td>
       </tr>

   </tbody>
  </table>

<div class="bottom_btn">
    <?php echo $toolbar->build_toolbar();  ?>
</div>
  
  
	
  
</form>
<br/>
