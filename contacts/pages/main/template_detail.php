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
//  Path: /modules/contacts/pages/main/template_detail.php
//
echo html_form('contacts', FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'post', 'enctype="multipart/form-data"', true) . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('f0', $f0) . chr(10);
echo html_hidden_field('id', $cInfo->id) . chr(10);
echo html_hidden_field('rowSeq', '') . chr(10);
echo html_hidden_field('del_crm_note', '') . chr(10);
echo html_hidden_field('payment_id', '') . chr(10);
// customize the toolbar actions
if ($action == 'properties') {
    $toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=contacts&page=main&type=c&list=1&f0=0&sf=&so=&"';
    $toolbar->icon_list['save']['show'] = false;
} else {
    if ($_GET['type'] == 'e'){
        $toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=contacts&page=main&type=e&list=1&f0=1&sf=&so=&"';
    }else if($_GET['type']='b'){
        $toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=contacts&page=main&type=b&list=1"';
    }else{
      $toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=contacts&page=main&type=c&list=1&f0=0&sf=&so=&"';  
    }
        
    if ((!$cInfo->id && $security_level < 2) || ($cInfo->id && $security_level < 3)) {
        $toolbar->icon_list['save']['show'] = false;
    } else {
        $toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
    }
}
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;

// pull in extra toolbar overrides and additions
if (count($extra_toolbar_buttons) > 0) {
    foreach ($extra_toolbar_buttons as $key => $value)
        $toolbar->icon_list[$key] = $value;
}

// add the help file index and build the toolbar
if (!$cInfo->help == '')
    $toolbar->add_help($cInfo->help);
if ($search_text)
    $toolbar->search_text = $search_text;

$fields->set_fields_to_display($type);
// Build the page

$custom_path = DIR_FS_MODULES . 'contacts/custom/pages/main/extra_tabs.php';
if (file_exists($custom_path)) {
    include($custom_path);
}

function tab_sort($a, $b) {
    if ($a['order'] == $b['order'])
        return 0;
    return ($a['order'] > $b['order']) ? 1 : -1;
}

usort($cInfo->tab_list, 'tab_sort');
?>

<!--Top Error Message Start -->
<div class="bottom_btn" id="bottom_btn">
<?php
echo $toolbar->build_toolbar();
?>
</div>
<!--Top Error Message End -->

<h1><?php echo PAGE_TITLE; ?></h1>
<div id="detailtabs" style="background-color: #eeeeee">
    <ul>
        <?php
        // build the tab list's
        $set_default = false;
        foreach ($cInfo->tab_list as $value) {
            echo add_tab_list('tab_' . $value['tag'], $value['text']);
            $set_default = true;
        }
        if ($_SESSION['set_security'] == 'yes') {
            echo $fields->extra_tab_li . chr(10); // user added extra tabs
        }
        ?>
    </ul>
    <?php
    foreach ($cInfo->tab_list as $value) {
        if (file_exists(DIR_FS_WORKING . 'custom/pages/main/' . $value['file'] . '.php')) {
            include(DIR_FS_WORKING . 'custom/pages/main/' . $value['file'] . '.php');
        } else {
            include(DIR_FS_WORKING . 'pages/main/' . $value['file'] . '.php');
        }
    }
// pull in additional custom tabs
    if (isset($extra_contact_tabs) && is_array($extra_contact_tabs)) {
        foreach ($extra_contact_tabs as $tabs) {
            $file_path = DIR_FS_WORKING . 'custom/pages/main/' . $tabs['tab_filename'] . '.php';
            if (file_exists($file_path)) {
                require($file_path);
            }
        }
    }
    echo $fields->extra_tab_html; // user added extra tabs
    ?>
    <div class="bottom_btn">
<?php
echo $toolbar->build_toolbar();
?>
    </div>
</div>
</form>

