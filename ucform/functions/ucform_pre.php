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
//  Path: /modules/ucform/functions/ucform.php
//

function default_report() {
    $report = new objectInfo();
    $report->page->margin->top = PF_DEFAULT_MARGIN;
    $report->page->margin->bottom = PF_DEFAULT_MARGIN;
    $report->page->margin->left = PF_DEFAULT_MARGIN;
    $report->page->margin->right = PF_DEFAULT_MARGIN;
    $report->page->title1->text = PF_DEFAULT_TITLE1;
    $report->page->title2->text = PF_DEFAULT_TITLE2;
    $report->page->size = PF_DEFAULT_PAPERSIZE;
    $report->page->orientation = PF_DEFAULT_ORIENTATION;
    $report->security = 'u:0;g:0';
    return $report;
}

function get_mime_image($ext, $folder = false) {
    if ($folder)
        return 'places/folder.png';
    switch ($ext) {
        case 'doc': return 'mimetypes/x-office-document.png';
        case 'xls': return 'mimetypes/x-office-spreadsheet.png';
        case 'ppt': return 'mimetypes/x-office-presentation.png';
        case 'drw':
        case 'png':
        case 'gif':
        case 'jpg':
        case 'jpeg': return 'mimetypes/image-x-generic.png';
        case 'htm':
        case 'html': return 'mimetypes/text-html.png';
        case 'pdf': return 'ucbooks/pdficon_small.gif';
        case 'txt':
        default: return 'mimetypes/text-x-generic.png';
    }
}

function convertPfColor($color = '0:0:0') {
    $colors = explode(':', $color);
    $hexcolor = str_pad(dechex($colors[0]), 2, STR_PAD_LEFT) .
            str_pad(dechex($colors[1]), 2, STR_PAD_LEFT) .
            str_pad(dechex($colors[2]), 2, STR_PAD_LEFT);
    return $hexcolor;
}

function buildToggleList($id, $toggle_list = '') {
    global $db;
    $result = $db->Execute("select parent_id from " . TABLE_UCFORM . " where id = '" . $id . "'");
    if ($result->fields['parent_id'] <> '0')
        $toggle_list = buildToggleList($result->fields['parent_id'], $toggle_list);
    $toggle_list .= "  Toggle('dc_" . $result->fields['parent_id'] . "_" . $id . "');" . chr(10);
    return $toggle_list;
}

function security_check($tokens) {
    $categories = explode(';', $tokens);
    $user_str = ':' . $_SESSION['admin_id'] . ':';
    $emp_str = ':' . ($_SESSION['account_id'] ? $_SESSION['account_id'] : '0') . ':';
    $dept_str = ':' . ($_SESSION['department'] ? $_SESSION['department'] : '0') . ':';
    foreach ($categories as $category) {
        $type = substr($category, 0, 1);
        $approved_ids = substr($category, 1) . ':';
        if (strpos($approved_ids, ':0:') !== false)
            return true; // for 'all' field
        switch ($type) {
            case 'u': if (strpos($approved_ids, $user_str) !== false)
                    return true;
                break;
            case 'e': if (strpos($approved_ids, $emp_str) !== false)
                    return true;
                break;
            case 'd': if (strpos($approved_ids, $dept_str) !== false)
                    return true;
                break;
        }
    }
    return false;
}

function pf_validate_security($security = 'u:-1;g:-1', $include_all = true) {
    $types = explode(';', $security);
    $settings = array();
    foreach ($types as $value) {
        $temp = explode(':', $value);
        $type = array_shift($temp);
        $settings[$type] = $temp;
    }
    if (!is_array($settings['u']) || !is_array($settings['g']))
        return false;
    if (in_array($_SESSION['admin_id'], $settings['u']) || in_array($_SESSION['department'], $settings['g']))
        return true;
    if ($include_all && (in_array(0, $settings['u']) || in_array(0, $settings['g'])))
        return true;
    return false;
}

function build_groups() { // dynamically build report and form groups
    global $db;
    $output = array();
    $result = $db->Execute("select doc_ext, doc_group, doc_title from " . TABLE_UCFORM . " where doc_ext in ('ff','0')");
    while (!$result->EOF) {
        switch ($result->fields['doc_ext']) {
            case 'ff': $output['forms'][$result->fields['doc_group']] = $result->fields['doc_title'];
                break;
            case '0': $output['reports'][$result->fields['doc_group']] = $result->fields['doc_title'];
                break;
        }
        $result->MoveNext();
    }
    return $output;
}

function load_recently_added() {
    global $db;
    $contents = NULL;
    $sql = "select id, security, doc_title from " . TABLE_UCFORM . " where doc_type <> '0' order by create_date desc, id desc limit 20";
    $result = $db->Execute($sql);
    if ($result->RecordCount() == 0) {
        $contents .= TEXT_NO_DOCUMENTS . '<br />';
    } else {
        while (!$result->EOF) {
            if (pf_validate_security($result->fields['security'], true)) {
                $contents .= '  <div>';
                $contents .= '    <a href="javascript:fetch_doc(' . $result->fields['id'] . ');">';
                $contents .= html_icon('mimetypes/text-x-generic.png', $result->fields['doc_title'], 'small') . ' ';
                $contents .= $result->fields['doc_title'] . '</a>';
                $contents .= '  </div>' . chr(10);
            }
            $result->MoveNext();
        }
    }
    return $contents;
}

function load_my_reports() {
    global $db;
    $contents = NULL;
    $sql = "select id, doc_title, security from " . TABLE_UCFORM . " 
	  where doc_type <> '0' order by doc_title";
    $result = $db->Execute($sql);
    if ($result->RecordCount() == 0) {
        $contents .= TEXT_NO_DOCUMENTS . '<br />';
    } else {
        while (!$result->EOF) {
            if (pf_validate_security($result->fields['security'], false)) {
                $contents .= '  <div>';
                $contents .= '    <a href="javascript:fetch_doc(' . $result->fields['id'] . ');">';
                $contents .= html_icon('mimetypes/text-x-generic.png', $result->fields['doc_title'], 'small') . ' ';
                $contents .= $result->fields['doc_title'] . '</a>';
                $contents .= '  </div>' . chr(10);
            }
            $result->MoveNext();
        }
    }
    return $contents;
}

function find_special_class($class_name) {
    global $loaded_modules, $messageStack;
    if (is_array($loaded_modules))
        foreach ($loaded_modules as $module) {
            if (file_exists(DIR_FS_MODULES . $module . '/classes/' . $class_name . '.php')) {
                return DIR_FS_MODULES . $module . '/';
            }
        }
    $messageStack->add('Special class: ' . $class_name . ' was called but could not be found!', 'error');
    return false;
}

function load_special_language($path, $class_name) {
    if (file_exists($path . 'language/' . $_SESSION['language'] . '/classes/' . $class_name . '.php')) {
        require_once ($path . 'language/' . $_SESSION['language'] . '/classes/' . $class_name . '.php');
    } elseif (file_exists($path . 'language/en_us/classes/' . $class_name . '.php')) {
        require_once ($path . 'language/en_us/classes/' . $class_name . '.php');
    }
}

function ReadDefReports($name, $path) {
    if (!$dh = @opendir(DIR_FS_ADMIN . $path))
        return TEXT_NO_DOCUMENTS;
    $i = 0;
    while ($DefRpt = readdir($dh)) {
        $pinfo = pathinfo(DIR_FS_ADMIN . $path . '/' . $DefRpt);
        switch ($pinfo['extension']) {
            case 'txt':
                $FileLines = file(DIR_FS_ADMIN . $path . '/' . $DefRpt);
                foreach ($FileLines as $OneLine) { // find the main reports sql statement, language and execute it
                    if (strpos($OneLine, 'ReportNarr:') === 0)
                        $ReportNarr = substr(trim($OneLine), 12, -1);
                    if (strpos($OneLine, 'ReportData:') === 0) { // then it's the line we're after with description and groupname
                        $GrpPos = strpos($OneLine, "groupname='") + 11;
                        $GrpName = substr($OneLine, $GrpPos, strpos($OneLine, "',", $GrpPos) - $GrpPos);
                        $RptPos = strpos($OneLine, "description='") + 13;
                        $RptName = substr($OneLine, $RptPos, strpos($OneLine, "',", $RptPos) - $RptPos);
                        $ReportList[$GrpName][$i]['RptName'] = $RptName;
                        $ReportList[$GrpName][$i]['RptNarr'] = $ReportNarr;
                        $ReportList[$GrpName][$i]['FileName'] = $pinfo[basename];
                        $i++;
                    }
                }
                break;
            case 'xml':
                $data = file_get_contents(DIR_FS_ADMIN . $path . '/' . $DefRpt);
                $report = xml_to_object($data);
                if (is_object($report->UcformReport))
                    $report = $report->UcformReport; // remove container tag
                $ReportList[$report->groupname][$i]['RptName'] = $report->title;
                $ReportList[$report->groupname][$i]['RptNarr'] = $report->description;
                $ReportList[$report->groupname][$i]['FileName'] = $pinfo[basename];
                $i++;
                break;
            default: continue; // not a report
        }
    }
    closedir($dh);
    $OptionList = '<select name="' . $name . '" size="15">';
    $LstGroup = '';
    $CloseOptGrp = false;
    $i = 0;
    if (is_array($ReportList)) {
        $groups = build_groups();
        ksort($ReportList);
        foreach ($ReportList as $GrpName => $members) {
            $group_split = explode(':', $GrpName); // if it's a form then remove the report from category id
            $GrpMember = $groups['reports'][$group_split[0]];
            if (!$GrpMember)
                $GrpMember = TEXT_MISC;
            if (isset($group_split[1])) {
                $form_group = $groups['forms'][$GrpName];
                if (!$form_group)
                    $form_group = TEXT_UNCAT_FORM;
                $label = $GrpMember . ' - ' . TEXT_FORMS . ' - ' . $form_group;
            } else {
                $label = $GrpMember . ' - ' . TEXT_REPORTS;
            }
            $OptionList .= '<optgroup label="' . $label . '" title="' . $GrpName . '">';
            foreach ($members as $Temp) {
                $OptionList .= '<option value="' . $Temp['FileName'] . '">' . htmlspecialchars($Temp['RptName'] . ' - ' . truncate_string($Temp['RptNarr'], $len = 65)) . '</option>';
            }
            $OptionList .= '</optgroup>';
        }
    }
    return $OptionList . '</select>';
}

function get_report_details($id) {
    global $messageStack;
    if (!$id) {
        $messageStack->add('There was no report or form passed to open!', 'error');
        return false;
    }

    $filename = PF_DIR_MY_REPORTS . 'pf_' . $id;

    if (!file_exists($filename)) {
        $messageStack->add('The report or form requested could not be found in the my_reports directory!', 'error');
        return false;
    }
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    if (!$report = xml_to_object($contents))
        return false;
    if (is_object($report->UcformReport))
        $report = $report->UcformReport; // remove container tag
// fix some special cases
    if (isset($report->tables) && is_object($report->tables))
        $report->tables = array($report->tables);
    if (isset($report->grouplist) && is_object($report->grouplist))
        $report->grouplist = array($report->grouplist);
    if (isset($report->sortlist) && is_object($report->sortlist))
        $report->sortlist = array($report->sortlist);
    if (isset($report->filterlist) && is_object($report->filterlist))
        $report->filterlist = array($report->filterlist);
    if (isset($report->fieldlist) && is_object($report->fieldlist))
        $report->fieldlist = array($report->fieldlist);
    if (is_array($report->fieldlist))
        foreach ($report->fieldlist as $key => $value) {
            if ($value->description == "Logo") {
                $report->fieldlist[$key]->ordinate = 3;
                if ($logo = get_company_logo()) {
                    $report->fieldlist[$key]->filename = 'company_logo/' . $logo;
                }
            }
            if ($value->description == "Company Address") {
                $report->fieldlist[$key]->width = 140;
                $report->fieldlist[$key]->ordinate = 2;
//$report->fieldlist[$key]->height = 140;
            }

            if (is_object($value->boxfield))
                $report->fieldlist[$key]->boxfield = array($value->boxfield);
        }
    return $report;
}

function get_company_logo() {
    $query = "SELECT * FROM configuration WHERE configuration_key = 'COMPANY_LOGO'";
    $result = mysql_query($query);
    $row = mysql_fetch_object($result);
    if ($row) {
        return $row->configuration_value;
    }
    else
        return false;
}

function ImportReport($RptName = '', $RptFileName = '', $import_path = PF_DIR_DEF_REPORTS, $save_path = PF_DIR_MY_REPORTS) {
    global $db, $messageStack;
    $rID = '';
    if ($RptFileName <> '') { // then a locally stored report was chosen
        $path = $import_path . $RptFileName;
    } else if (validate_upload('reportfile')) {
        $path = $_FILES['reportfile']['tmp_name'];
    } else {
        $messageStack->add(UCFORM_IMPORT_ERROR, 'error');
        return false;
    }
    $handle = fopen($path, "r");
    $contents = fread($handle, filesize($path));
    fclose($handle);
    if (strpos($contents, 'Report Builder Export Tool')) { // it's an old style report
        require_once(DIR_FS_MODULES . 'ucform/functions/reportwriter.php');
        if (!$report = import_text_params(file($path)))
            return false;
    } else { // assume it's a new xml type
        if (!$report = xml_to_object($contents))
            return false;
        if (is_object($report->UcformReport))
            $report = $report->UcformReport; // remove container tag
    }
    if ($RptName <> '')
        $report->title = $RptName; // replace the title if provided
// error check
    $result = $db->Execute("select id from " . TABLE_UCFORM . " 
	  where doc_title = '" . addslashes($report->title) . "' and doc_type <> '0'");
    if ($result->RecordCount() > 0) { // the report name already exists, if file exists error, else write 
        $rID = $result->fields['id'];
        if (file_exists($save_path . 'pf_' . $rID)) { // file exists - error and return
            $messageStack->add(sprintf(UCFORM_REPDUP, $report->title), 'error');
            return false;
        }
    }
    if (!$result = save_report($report, $rID, $save_path))
        return false;
    return true;
}

function save_report($report, $rID = '', $save_path = PF_DIR_MY_REPORTS) {
    global $db, $messageStack;
    $error = false;
    $output = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . chr(10);
    $output .= '<UcformReport>' . chr(10);
    $output .= object_to_xml($report);
    $output .= '</UcformReport>' . chr(10);
//echo 'xml output = ' . str_replace(chr(10) , "<br>", htmlspecialchars($output)) . '<br>'; exit();
// see if a folder exists with the group to put it in
    $result = $db->Execute("select id from " . TABLE_UCFORM . " 
	  where doc_group = '" . $report->groupname . "' and doc_type = '0' and doc_ext in ('ff', 'fr')");
    if ($result->RecordCount() == 0) {
        if ($report->reporttype == 'frm') {
            $result = $db->Execute("select id from " . TABLE_UCFORM . " 
	      where doc_group = 'misc:misc' and doc_type = '0'");
        } else {
            $result = $db->Execute("select id from " . TABLE_UCFORM . " 
	      where doc_group = 'misc' and doc_type = '0'");
        }
    }
    $parent_id = $result->fields['id'];
    if ($report->standard_report == '1')
        $report->standard_report = 's';
    $sql_array = array(
        'parent_id' => $parent_id,
        'doc_type' => isset($report->standard_report) ? $report->standard_report : 's',
        'doc_title' => $report->title,
        'doc_group' => $report->groupname,
        'doc_ext' => $report->reporttype,
        'security' => $report->security,
    );
    if ($rID) { // update
        $sql_array['last_update'] = date('Y-m-d');
        db_perform(TABLE_UCFORM, $sql_array, 'update', 'id = ' . $rID);
    } else { // add
        $sql_array['create_date'] = date('Y-m-d');
        db_perform(TABLE_UCFORM, $sql_array);
        $rID = db_insert_id();
    }
    $filename = $save_path . 'pf_' . $rID;
    if (!$handle = @fopen($filename, 'w'))
        $error = true;
    if (!$error) {
        if (!fwrite($handle, $output))
            $error = true;
        @fclose($handle);
    }
    if ($error) {
        $db->Execute("delete from " . TABLE_UCFORM . " where id = " . $rID);
        $messageStack->add(sprintf(UCFORM_WRITE_ERROR, $filename), 'error');
        return false;
    }
    return $rID;
}

function truncate_string($str, $len = 32) {
    if (strlen($str) > $len) {
        return substr($str, 0, $len - 3) . '...';
    } else {
        return $str;
    }
}

function build_dir_path($id) {
    global $db;
    $result = $db->Execute("select parent_id, doc_title from " . TABLE_UCFORM . " where id = '" . $id . "'");
    $title = ($id) ? $result->fields['doc_title'] : '';
    if ($result->fields['parent_id'])
        $title = build_dir_path($result->fields['parent_id']) . '/' . $title;
    return $title;
}

function validate_dir_move($dir_tree, $id, $new_parent) {
    if ($id <> 0 && $new_parent == $id)
        return false;
    if ($dir_tree[$new_parent] <> 0)
        return validate_dir_move($dir_tree, $id, $dir_tree[$new_parent]);
    return true;
}

function BuildForm($report, $delivery_method = 'D', $show_mode = "", $open_inv = "") { // for forms only
    global $db, $messageStack, $FieldValues;
    require_once(DIR_FS_MODULES . 'ucform/classes/form_generator.php');
    $output = array();

// check for at least one field selected to show
    if (!$report->fieldlist) { // No fields are checked to show, that's bad
        return $messageStack->add(UCFORM_NOROWS, 'caution');
    }
// Let's build the sql field list for the general data fields (not totals, blocks or tables)
    $strField = array();
    foreach ($report->fieldlist as $key => $field) { // check for a data field and build sql field list
        if (in_array($field->type, array('Data', 'BarCode', 'ImgLink'))) { // then it's data field make sure it's not empty
            if ($field->boxfield[0]->fieldname) {
                $strField[] = prefixTables($field->boxfield[0]->fieldname) . ' as d' . $key;
            } else { // the field is empty, bad news, error and exit
                return $messageStack->add(UCFORM_EMPTYFIELD . $key, 'error');
            }
        }
    }
    $report->sqlField = implode(', ', $strField);
// fetch the sort order and add to group by string to finish ORDER BY string
    $strSort = array();
    if (is_array($report->sortlist))
        foreach ($report->sortlist as $sortline) {
            if ($sortline->default == '1')
                $strSort[] = prefixTables($sortline->fieldname);
        }
    $sqlSort = implode(', ', $strSort);
// fetch criteria and date filter info
    $strCrit = array();
    if ($report->datedefault) {
        $dates = gen_build_sql_date($report->datedefault, prefixTables($report->datefield));
        if ($dates['sql'])
            $strCrit[] = $dates['sql'];
    }
//echo 'report = '; print_r($report); echo '<br />';
    $criteria = build_criteria($report);
    if ($criteria['sql'])
        $strCrit[] = $criteria['sql'];
    $report->sqlCrit = implode(' and ', $strCrit);
// fetch the tables to query
    $report->sqlTable = '';
    foreach ($report->tables as $table) {
        if (isset($table->relationship)) {
            if (!$table->joinopt)
                $table->joinopt = ' JOIN ';
            $report->sqlTable .= ' ' . $table->joinopt . ' ' . DB_PREFIX . $table->tablename . ' ON ' . prefixTables($table->relationship);
        } else {
            $report->sqlTable .= DB_PREFIX . $table->tablename;
        }
    }
// We now have the sql, find out how many groups in the query (to determine the number of forms)
    $form_field_list = ($report->filenamefield == '') ? (prefixTables($report->formbreakfield)) : (prefixTables($report->formbreakfield) . ', ' . prefixTables($report->filenamefield));
    $sql = 'select ' . $form_field_list . ' from ' . $report->sqlTable;
    if ($report->sqlCrit)
        $sql .= ' where ' . $report->sqlCrit;
    $sql .= ' group by ' . prefixTables($report->formbreakfield);
    if ($strSort)
        $sql .= ' order by ' . $sqlSort;
// execute sql to see if we have data
//echo 'sql = ' . $sql . '<br />'; exit();
    $result = $db->Execute($sql);
    if (!$result->RecordCount())
        return $messageStack->add(UCFORM_NOROWS, 'caution');

// set the filename for download or email
    if ($report->filenameprefix || $report->filenamefield) {
        $report->filename = $report->filenameprefix . $result->fields[strip_tablename($report->filenamefield)] . '.pdf';
    } else {
        $report->filename = ReplaceNonAllowedCharacters($report->title) . '.pdf';
    }
// create an array for each form
    $report->recordID = array();
    while (!$result->EOF) {
        $report->recordID[] = $result->fields[strip_tablename($report->formbreakfield)];
        $result->MoveNext();
    }
// retrieve the company information
    for ($i = 0; $i < sizeof($report->fieldlist); $i++) {
        if ($report->fieldlist[$i]->type == 'CDta') {
            $report->fieldlist[$i]->text = constant($report->fieldlist[$i]->boxfield[0]->fieldname);
        } else if ($report->fieldlist[$i]->type == 'CBlk') {
            if (!$report->fieldlist[$i]->boxfield) {
                return $messageStack->add(UCFORM_EMPTYFIELD . $report->fieldlist[$i]->description, 'error');
            }
            $TextField = '';
            foreach ($report->fieldlist[$i]->boxfield as $entry) {
                $temp = $entry->formatting ? ProcessData(constant($entry->fieldname), $entry->formatting) : constant($entry->fieldname);
                $TextField .= AddSep($temp, $entry->processing);
            }
            $report->fieldlist[$i]->text = $TextField;
        }
    }
// patch for special_reports (forms) where the data file is generated externally from the standard class
    if ($report->special_class) {
        if (!$path = find_special_class($report->special_class))
            return false;
        load_special_language($path, $report->special_class);
        require_once($path . '/classes/' . $report->special_class . '.php');
    }
    if ($report->serialform) {
        $output = BuildSeq($report, $delivery_method); // build sequential form (receipt style)
    } else if ($report->title == 'Customer Statement' && $report->special_class != 'statement_builder') {
        $output = buildCustomerStatments($report, $open_inv);
        $final_output = array();
        foreach ($output as $each) {
            foreach ($each as $item) {
                $final_output[] = $item;
            }
        }
        return $final_output;
    } else {

        $output = BuildPDF($report, $delivery_method, $show_mode, $open_inv); // build standard PDF form, doesn't return if download
    }
    return $output; //if we are here, return the contents for an email attachment
}

function buildCustomerStatments($report, $open_inv) {
    $output = array();
    if ($report->special_class) {
        $form_class = $report->special_class;
        $special_form = new $form_class();
    }

    $output = $special_form->load_query_results('bill_acct_id', $open_inv);

    return $output;
}

function BuildPDF($report, $delivery_method = 'D', $show_mode = "", $open_inv = "") { // for forms only - PDF style
    global $db, $messageStack, $FieldValues, $posted_currencies;

// Generate a form for each group element
    $output = array();
    if ($show_mode == "") {
        $pdf = new PDF();
    }
//    $tcpdf->SetHeaderData("kki.jpg", PDF_HEADER_LOGO_WIDTH, "Application PDF", "Likeslomakkeet - www.likeslomakkeet.net");
    foreach ($report->recordID as $formNum => $Fvalue) {
        if ($show_mode == "") {
            $pdf->StartPageGroup();
        }
// find the single line data from the query for the current form page
        $TrailingSQL = " from " . $report->sqlTable . " where " . ($report->sqlCrit ? $report->sqlCrit . " AND " : '') . prefixTables($report->formbreakfield) . " = '" . $Fvalue . "'";
        $FieldValues = array();
        if ($report->special_class) {
            $form_class = $report->special_class;
            $special_form = new $form_class();
            if ($report->title == 'Customer Statement') {
                $FieldValues = $special_form->load_query_results($report->formbreakfield, $Fvalue, $open_inv);
            } else {
                $FieldValues = $special_form->load_query_results($report->formbreakfield, $Fvalue, $open_inv);
            }
        } else {
            if (strlen($report->sqlField) > 0) {
//echo 'sql = select ' . $report->sqlField . $TrailingSQL . '<br />'; exit();
                $result = $db->Execute("select " . $report->sqlField . $TrailingSQL);
                $FieldValues = $result->fields;
            }
        }
// load the posted currency values
        $posted_currencies = array();
        if (ENABLE_MULTI_CURRENCY && strpos($report->sqlTable, TABLE_JOURNAL_MAIN) !== false) {
            $sql = "select currencies_code, currencies_value " . $TrailingSQL;
            $result = $db->Execute($sql);
            $posted_currencies = array(
                'currencies_code' => $result->fields['currencies_code'],
                'currencies_value' => $result->fields['currencies_value'],
            );
        } else {
            $posted_currencies = array('currencies_code' => DEFAULT_CURRENCY, 'currencies_value' => 1);
        }
        foreach ($report->fieldlist as $key => $field) { // Build the text block strings
            if ($field->type == 'TBlk') {
                if (!$field->boxfield[0]->fieldname) {
                    return $messageStack->add(UCFORM_EMPTYFIELD . $field->fieldname, 'error');
                }
                if ($report->special_class) {
                    $TextField = $special_form->load_text_block_data($field->boxfield);
                } else {
                    $arrTxtBlk = array(); // Build the fieldlist
                    foreach ($field->boxfield as $idx => $entry)
                        $arrTxtBlk[] = prefixTables($entry->fieldname) . ' as r' . $idx;
                    $strTxtBlk = implode(', ', $arrTxtBlk);
                    $result = $db->Execute("select " . $strTxtBlk . $TrailingSQL);
                    $TextField = '';
                    for ($i = 0; $i < sizeof($field->boxfield); $i++) {
                        $temp = $field->boxfield[$i]->formatting ? ProcessData($result->fields['r' . $i], $field->boxfield[$i]->formatting) : $result->fields['r' . $i];
                        $TextField .= AddSep($temp, $field->boxfield[$i]->processing);
                    }
                }
                $report->fieldlist[$key]->text = $TextField;
            }
            if ($field->type == 'LtrData') { // letter template
                if (!$field->boxfield) {
                    return $messageStack->add(UCFORM_EMPTYFIELD . $field->description, 'error');
                }
                $tblField = '';
                $data = array();
                if ($report->special_class) {
                    $report->LetterData = $special_form->load_letter_data($field->boxfield);
                } else {
                    $tblField = array();
                    foreach ($field->boxfield as $key => $TableField)
                        $tblField[] = prefixTables($TableField->fieldname) . ' as `' . $TableField->description . '`';
                    $tblField = implode(', ', $tblField);
                    $result = $db->Execute("select " . $tblField . $TrailingSQL . " LIMIT 1");
                    foreach ($field->boxfield as $key => $TableField) {
                        if ($TableField->processing)
                            $result->fields[$TableField->description] = ProcessData($result->fields[$TableField->description], $TableField->processing);
                    }
                    $report->LetterData = $result->fields;
                }
            }
// Pre-load all total fields with 'Continued' label for multipage
            if ($field->type == 'Ttl')
                $report->fieldlist[$key]->text = TEXT_CONTINUED;
        }
        if ($show_mode == "" && count($special_form->line_items) > 0) {
            $pdf->PageCnt = $pdf->PageNo(); // reset the current page numbering for this new form
            $pdf->AddPage();
        } else if ($report->title == 'Transfer Packing Slip' || $report->title == 'Purchase Order' || $report->title == 'Request for Quote' || $report->title == 'Vendor Credit Memo' || $report->title == 'Credit Memo' || $report->title == 'Customer Quotes' || $report->title == 'Sales Order') {
            $pdf->PageCnt = $pdf->PageNo(); // reset the current page numbering for this new form
            $pdf->AddPage();
        }
// Send the table
        foreach ($report->fieldlist as $TableObject) {
            if ($TableObject->type == 'Tbl') {
                if (!$TableObject->boxfield) {
                    return $messageStack->add(UCFORM_EMPTYFIELD . $TableObject->description, 'error');
                }
// Build the sql
                $tblField = '';
                $tblHeading = array();
                foreach ($TableObject->boxfield as $TableField)
                    $tblHeading[] = $TableField->description;
                $data = array();
                if ($report->special_class) {
                    $data = $special_form->load_table_data($TableObject->boxfield);
                } else {
                    $tblField = array();
                    foreach ($TableObject->boxfield as $key => $TableField)
                        $tblField[] = prefixTables($TableField->fieldname) . ' as r' . $key;
                    $tblField = implode(', ', $tblField);
                    $result = $db->Execute("select " . $tblField . $TrailingSQL);
                    while (!$result->EOF) {
                        $data[] = $result->fields;
                        $result->MoveNext();
                    }
                }
                array_unshift($data, $tblHeading); // set the first data element to the headings
                $TableObject->data = $data;
                $StoredTable = clone $TableObject;

                if ($show_mode == "" && count($TableObject->data) > 1) {
                    $pdf->FormTable($TableObject);
                }
            }
        }
        if ($show_mode == "show_html" || $show_mode == "show_pdf") {
            unset($data[0]);
            foreach ($data as $item_val) {
                $data_info[] = $item_val;
            }
        }
// Send the duplicate data table (only works if each form is contained in a single page [no multi-page])
        foreach ($report->fieldlist as $field) {
            if ($field->type == 'TDup') {
                if (!$StoredTable) {
                    return $messageStack->add(UCFORM_EMPTYTABLE . $field->description, 'error');
                }
// insert new coordinates into existing table
                $StoredTable->abscissa = $field->abscissa;
                $StoredTable->ordinate = $field->ordinate;
//$pdf->FormTable($StoredTable);
            }
        }
        foreach ($report->fieldlist as $key => $field) {
// Set the totals (need to be on last printed page) - Handled in the Footer function in FPDF
            if ($field->type == 'Ttl') {
                if (!$field->boxfield) {
                    return $messageStack->add(UCFORM_EMPTYFIELD . $field->description, 'error');
                }
                $report->fieldlist[$key]->processing = $field->boxfield[0]->processing; // assume first processing setting carries for the total
                if ($report->special_class) {
                    $FieldValues = $special_form->load_total_results($field);
                } else {
                    $ttlField = '';
                    foreach ($field->boxfield as $TotalField)
                        $ttlField[] = prefixTables($TotalField->fieldname);
                    $sql = "select sum(" . implode(' + ', $ttlField) . ") as form_total" . $TrailingSQL;
                    $result = $db->Execute($sql);
                    $FieldValues = $result->fields['form_total'];
                }
                $report->fieldlist[$key]->text = $FieldValues;
            }
// Set the data for the last Page if last page only flag checked, pull from temp save
            if ($field->type == 'Data' && $field->display == '2') {
                $report->fieldlist[$key]->text = $report->fieldlist[$key]->texttemp;
            }
        }

// set the printed flag field if provided
        if ($report->setprintedflag) {
            $id_field = $report->formbreakfield;
            $temp = explode('.', $report->setprintedflag);
            if (sizeof($temp) == 2) { // need the table name and field name
                $sql = "update " . $temp[0] . " set " . $temp[1] . " = " . $temp[1] . " + 1 where " . $report->formbreakfield . " = '" . $Fvalue . "'";
                $db->Execute($sql);
            }
        }
    }

// Add additional headers needed for MSIE and send page
    header('Pragma: cache');
    header('Cache-Control: public, must-revalidate, max-age=0');
    $output['filename'] = $report->filename;
    if ($show_mode == "show_html" || $show_mode == "show_pdf") {
        $show['data'] = $data_info;
        $show['record_id'] = $report->recordID;
        $show['posted_currencies'] = $posted_currencies;
        $show['filename'] = $report->title;
        return $show;
        exit;
    }
    $output['pdf'] = $pdf->Output($report->filename, $delivery_method);
    if ($delivery_method == 'S')
        return $output;
    exit(); // needs to be here to properly render the pdf file if delivery_method = I or D
}

function BuildSeq($report, $delivery_method = 'D') { // for forms only - Sequential mode
    global $db, $messageStack, $FieldValues, $posted_currencies;
// Generate a form for each group element
    $output = NULL;
    foreach ($report->recordID as $formNum => $Fvalue) {
// find the single line data from the query for the current form page
        $TrailingSQL = " from " . $report->sqlTable . " where " . ($report->sqlCrit ? $report->sqlCrit . " AND " : '') . prefixTables($report->formbreakfield) . " = '" . $Fvalue . "'";
        if ($report->special_class) {
            $form_class = $report->special_class;
            $special_form = new $form_class();
            $FieldValues = $special_form->load_query_results($report->formbreakfield, $Fvalue);
        } else {
            $result = $db->Execute("select " . $report->sqlField . $TrailingSQL);
            $FieldValues = $result->fields;
        }
// load the posted currency values
        $posted_currencies = array();
        if (ENABLE_MULTI_CURRENCY && strpos($report->sqlTable, TABLE_JOURNAL_MAIN) !== false) {
            $sql = "select currencies_code, currencies_value " . $TrailingSQL;
            $result = $db->Execute($sql);
            $posted_currencies = array(
                'currencies_code' => $result->fields['currencies_code'],
                'currencies_value' => $result->fields['currencies_value'],
            );
        } else {
            $posted_currencies = array('currencies_code' => DEFAULT_CURRENCY, 'currencies_value' => 1);
        }
        foreach ($report->fieldlist as $key => $field) {
            switch ($field->type) {
                default:
                    $oneline = formatReceipt($field->text, $field->width, $field->align, $oneline);
                    break;
                case 'Data':
                    $value = ProcessData(array_shift($FieldValues), $field->boxfield[0]->processing);
                    $oneline = formatReceipt($value, $field->width, $field->align, $oneline);
                    break;
                case 'TBlk':
                    if (!$field->boxfield[0]->fieldname)
                        return $messageStack->add(UCFORM_EMPTYFIELD . $field->fieldname, 'error');
                    if ($report->special_class) {
                        $TextField = $special_form->load_text_block_data($field->boxfield);
                    } else {
                        $arrTxtBlk = array(); // Build the fieldlist
                        foreach ($field->boxfield as $idx => $entry)
                            $arrTxtBlk[] = prefixTables($entry->fieldname) . ' as r' . $idx;
                        $strTxtBlk = implode(', ', $arrTxtBlk);
                        $result = $db->Execute("select " . $strTxtBlk . $TrailingSQL);
                        $TextField = '';
                        for ($i = 0; $i < sizeof($field->boxfield); $i++) {
                            $temp = $field->boxfield[$i]->formatting ? ProcessData($result->fields['r' . $i], $field->boxfield[$i]->formatting) : $result->fields['r' . $i];
                            $TextField .= AddSep($temp, $field->boxfield[$i]->processing);
                        }
                    }
                    $report->fieldlist[$key]->text = $TextField;
                    $oneline = $report->fieldlist[$key]->text;
                    break;
                case 'Tbl':
                    if (!$field->boxfield)
                        return $messageStack->add(UCFORM_EMPTYFIELD . $field->description, 'error');
//		  $tblHeading = array();
//		  foreach ($field->boxfield as $TableField) $tblHeading[] = $TableField->description;
                    $data = array();
                    if ($report->special_class) {
                        $data = $special_form->load_table_data($field->boxfield);
                    } else {
                        $tblField = array();
                        foreach ($field->boxfield as $key => $TableField)
                            $tblField[] = prefixTables($TableField->fieldname) . ' as r' . $key;
                        $tblField = implode(', ', $tblField);
                        $result = $db->Execute("select " . $tblField . $TrailingSQL);
                        while (!$result->EOF) {
                            $data[] = $result->fields;
                            $result->MoveNext();
                        }
                    }
                    $field->data = $data;
                    $StoredTable = $field;
                    foreach ($data as $key => $value) {
                        $temp = array();
                        foreach ($value as $data_key => $data_element) {
                            $offset = substr($data_key, 1);
                            $value = ProcessData($data_element, $field->boxfield[$offset]->processing);
                            $temp[] .= formatReceipt($value, $field->boxfield[$offset]->width, $field->boxfield[$offset]->align);
                        }
                        $oneline .= implode("", $temp) . "\n";
                    }
                    $field->rowbreak = 1;
                    break;
                case 'TDup':
                    if (!$StoredTable)
                        return $messageStack->add(UCFORM_EMPTYTABLE . $field->description, 'error');
// insert new coordinates into existing table
                    $StoredTable->abscissa = $field->abscissa;
                    $StoredTable->ordinate = $field->ordinate;
                    foreach ($StoredTable->data as $key => $value) {
                        $temp = array();
                        foreach ($value as $data_key => $data_element) {
                            $value = ProcessData($data_element, $report->boxfield[$data_key]->processing);
                            $temp[] .= formatReceipt($value, $field->width, $field->align);
                        }
                        $oneline = implode("", $temp);
                    }
                    $field->rowbreak = 1;
                    break;
                case 'Ttl':
                    if (!$field->boxfield)
                        return $messageStack->add(UCFORM_EMPTYFIELD . $field->description, 'error');
                    if ($report->special_class) {
                        $FieldValues = $special_form->load_total_results($field);
                    } else {
                        $ttlField = '';
                        foreach ($field->boxfield as $TotalField)
                            $ttlField[] = prefixTables($TotalField->fieldname);
                        $sql = "select sum(" . implode(' + ', $ttlField) . ") as form_total" . $TrailingSQL;
                        $result = $db->Execute($sql);
                        $FieldValues = $result->fields['form_total'];
                    }
                    $value = ProcessData($FieldValues, $report->boxfield[0]->processing);
                    $oneline = formatReceipt($value, $field->width, $field->align, $oneline);
                    break;
            }
            if ($field->rowbreak) {
                $output .= $oneline . "\n";
                $oneline = '';
            }
        }
// set the printed flag field if provided
        if ($report->setprintedflag) {
            $id_field = $report->formbreakfield;
            $temp = explode('.', $report->setprintedflag);
            if (sizeof($temp) == 2) { // need the table name and field name
                $sql = "update " . $temp[0] . " set " . $temp[1] . " = " . $temp[1] . " + 1 where " . $report->formbreakfield . " = '" . $Fvalue . "'";
                $db->Execute($sql);
            }
        }
        $output .= "\n\n\n\n"; // page break
    }
    if ($delivery_method == 'S')
        return $output;
    $FileSize = strlen($output);
    header("Content-type: application/text");
    header("Content-disposition: attachment; filename=" . $report->filenameprefix . ".txt; size=" . $FileSize);
    header('Pragma: cache');
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Connection: close');
    header('Expires: ' . date('r', time() + 60 * 60));
    header('Last-Modified: ' . date('r', time()));
    print $output;
    exit();
}

function formatReceipt($value, $width = '15', $align = 'z', $base_string = '', $keep_nl = false) {
    $temp = explode(chr(10), $value);
    $output = NULL;
    foreach ($temp as $key => $value) {
        if ($key > 0)
            $output .= "\n"; // keep the new line chars
        switch ($align) {
            case 'L':
                if (strlen($base_string))
                    $output .= $value . substr($base_string, $width - strlen($value));
                else
                    $output .= str_pad($value, $width, ' ', STR_PAD_RIGHT);
                break;
            case 'R':
                if (strlen($base_string))
                    $output .= substr($base_string, 0, $width - strlen($value)) . $value;
                else
                    $output .= str_pad($value, $width, ' ', STR_PAD_LEFT);
                break;
            case 'C':
                if (strlen($base_string)) {
                    $pad = (($width - strlen($value)) / 2);
                    $output .= substr($base_string, 0, floor($pad)) . $value . substr($base_string, -ceil($pad));
                } else {
                    $num_blanks = (($width - strlen($value)) / 2) + strlen($value);
                    $value = str_pad($value, intval($num_blanks), ' ', STR_PAD_LEFT);
                    $output .= str_pad($value, $width, ' ', STR_PAD_RIGHT);
                }
                break;
        }
//echo 'value = ' . $value . ' and base_string = ' . $base_string . ' and new string = ' . $output . "<br>\n";
    }
    return $output;
}

function prefixTables($field) {
    global $report;
    foreach ($report->tables as $table) {
        $field = str_replace($table->tablename . '.', DB_PREFIX . $table->tablename . '.', $field);
    }
    return $field;
}

function testTables($field, $report) { // tests for the existence of tables in variables (for formulas)
    $result = false;
    foreach ($report->tables as $table) {
        if (strpos($field, $table->tablename . '.') !== false)
            $result = true;
    }
    return $result;
}

function build_criteria($report) {
    global $CritChoices;
    $strCrit = '';
    $filCrit = '';
    $crit_prefs = $report->filterlist;
    if (!is_array($crit_prefs))
        $crit_prefs = array();
    if (is_array($report->xfilterlist))
        $crit_prefs[] = $report->xfilterlist[0];
    while ($FieldValues = array_shift($crit_prefs)) {
        if (!$FieldValues->default) { // if no selection was passed, assume it's the first on the list for that selection menu
            $temp = explode(':', $CritChoices[$FieldValues->type]);
            $FieldValues->default = $temp[1];
        }
        $sc = '';
        $fc = '';
        switch ($FieldValues->default) {
            case 'RANGE':
                if ($FieldValues->min_val) { // a from value entered, check
                    $sc .= prefixTables($FieldValues->fieldname) . " >= '" . $FieldValues->min_val . "'";
                    $fc .= $FieldValues->description . " >= " . $FieldValues->min_val;
                }
                if ($FieldValues->max_val) { // a to value entered, check
                    if (strlen($sc) > 0) {
                        $sc .= ' AND ';
                        $fc .= ' ' . TEXT_AND . ' ';
                    }
                    $sc .= prefixTables($FieldValues->fieldname) . " <= '" . $FieldValues->max_val . "'";
                    $fc .= $FieldValues->description . " <= " . $FieldValues->max_val;
                }
                break;
            case 'YES':
            case 'TRUE':
            case 'INACTIVE':
            case 'PRINTED':
                $sc .= prefixTables($FieldValues->fieldname) . ' = \'1\'';
                $fc .= $FieldValues->description . ' = ' . $FieldValues->default;
                break;
            case 'NO':
            case 'FALSE':
            case 'ACTIVE':
            case 'UNPRINTED':
                $sc .= prefixTables($FieldValues->fieldname) . ' = \'0\'';
                $fc .= $FieldValues->description . ' = ' . $FieldValues->default;
                break;
            case 'EQUAL':
            case 'NOT_EQUAL':
            case 'GREATER_THAN':
            case 'LESS_THAN':
                if ($FieldValues->default == 'EQUAL')
                    $sign = " = ";
                if ($FieldValues->default == 'NOT_EQUAL')
                    $sign = " <> ";
                if ($FieldValues->default == 'GREATER_THAN')
                    $sign = " > ";
                if ($FieldValues->default == 'LESS_THAN')
                    $sign = " < ";
                if (isset($FieldValues->min_val)) { // a from value entered, check
                    $q_field = testTables($FieldValues->min_val, $report) ? prefixTables($FieldValues->min_val) : "'" . prefixTables($FieldValues->min_val) . "'";
                    $sc .= prefixTables($FieldValues->fieldname) . $sign . $q_field;
                    $fc .= $FieldValues->description . $sign . $FieldValues->min_val;
                }
                break;
            case 'IN_LIST':
                if (isset($FieldValues->min_val)) { // a from value entered, check
                    $csv_values = explode(',', $FieldValues->min_val);
                    for ($i = 0; $i < sizeof($csv_values); $i++)
                        $csv_values[$i] = trim($csv_values[$i]);
                    $sc .= prefixTables($FieldValues->fieldname) . " in ('" . implode("','", $csv_values) . "')";
                    $fc .= $FieldValues->description . " in (" . $FieldValues->min_val . ")";
                }
                break;
            case 'ALL': // sql default anyway
            default:
        }
        if ($sc) {
            if (strlen($strCrit) > 0) {
                $strCrit .= ' and ';
                if ($FieldValues->visible)
                    $filCrit .= ' ' . TEXT_AND . ' ';
            }
            $strCrit .= $sc;
            if ($FieldValues->visible)
                $filCrit .= $fc;
        }
    }
    $criteria = array('sql' => $strCrit, 'description' => $filCrit);
    return $criteria;
}

function BuildSQL($report) { // for reports only
    $strField = array();
    $index = 0;
    for ($i = 0; $i < sizeof($report->fieldlist); $i++) {
        if ($report->fieldlist[$i]->visible) {
            $strField[] = prefixTables($report->fieldlist[$i]->fieldname) . " AS c" . $index;
            $index++;
        }
    }
    if (!$strField)
        return array('level' => 'error', 'message' => UCFORM_NOROWS);
    $strField = implode(', ', $strField);

    $filterdesc = UCFORM_RPTFILTER; // Initialize the filter display string
//fetch the groupings and build first level of SORT BY string (for sub totals)
    $strGroup = NULL;
    for ($i = 0; $i < sizeof($report->grouplist); $i++) {
        if ($report->grouplist[$i]->default) {
            $strGroup .= prefixTables($report->grouplist[$i]->fieldname);
            $filterdesc .= UCFORM_GROUPBY . ' ' . $report->grouplist[$i]->description . '; ';
            break;
        }
    }
// fetch the sort order and add to group by string to finish ORDER BY string
    $strSort = $strGroup;
    for ($i = 0; $i < sizeof($report->sortlist); $i++) {
        if ($report->sortlist[$i]->default) {
            $strSort .= ($strSort <> '' ? ', ' : '') . prefixTables($report->sortlist[$i]->fieldname);
            $filterdesc .= UCFORM_SORTBY . ' ' . $report->sortlist[$i]->description . '; ';
            break;
        }
    }
// fetch date filter info
    $dates = gen_build_sql_date($report->datedefault, prefixTables($report->datefield));
    $strDate = $dates['sql'];
    if ($dates['description'])
        $filterdesc .= $dates['description']; // update the filter description string
// Fetch the Criteria
    $criteria = build_criteria($report);
    $strCrit = $criteria['sql'];
    if ($criteria['description'])
        $filterdesc .= UCFORM_CRITBY . ' ' . $criteria['description'] . '; ';
// fetch the tables to query
    $sqlTable = '';
    foreach ($report->tables as $table) {
        if (isset($table->relationship)) {
            if (!$table->joinopt)
                $table->joinopt = 'JOIN';
            $sqlTable .= ' ' . $table->joinopt . ' ' . DB_PREFIX . $table->tablename . ' ON ' . prefixTables($table->relationship);
        } else {
            $sqlTable .= DB_PREFIX . $table->tablename;
        }
    }
// Build query string
    $sql = 'SELECT ' . $strField . ' FROM ' . $sqlTable;
    if ($strCrit && $strDate)
        $sql .= ' WHERE ' . $strDate . ' AND ' . $strCrit;
    if (!$strCrit && $strDate)
        $sql .= ' WHERE ' . $strDate;
    if ($strCrit && !$strDate)
        $sql .= ' WHERE ' . $strCrit;
    if ($strSort)
        $sql .= ' ORDER BY ' . $strSort;
//echo 'sql = '; print_r($sql); echo '<br><br>'; exit();
//echo 'period = '; print_r($report->period); echo '<br><br>';
    return array(
        'level' => 'success',
        'data' => $sql,
        'description' => $filterdesc,
    );
}

function BuildDataArray($sql, $report) { // for reports only
    global $db, $Heading, $Seq, $posted_currencies, $messageStack;
    $posted_currencies = array('currencies_code' => DEFAULT_CURRENCY, 'currencies_value' => 1); // use default currency
// See if we need to group, fetch the group fieldname
    $GrpFieldName = '';
    if (is_array($report->grouplist))
        while ($Temp = array_shift($report->grouplist)) {
            if ($Temp->default) {
                $GrpFieldName = $Temp->fieldname;
                $GrpFieldProcessing = $Temp->processing;
                break;
            }
        }
// Build the sequence map of retrieved fields, order is as user wants it
    $i = 0;
    $GrpField = '';
    foreach ($report->fieldlist as $DataFields) {
        if ($DataFields->visible) {
            if ($DataFields->fieldname == $GrpFieldName)
                $GrpField = 'c' . $i;
            $Heading[] = $DataFields->description;
            $Seq[$i]['break'] = $DataFields->columnbreak;
            $Seq[$i]['fieldname'] = 'c' . $i;
            $Seq[$i]['total'] = $DataFields->total;
            $Seq[$i]['processing'] = $DataFields->processing;
            $Seq[$i]['align'] = $DataFields->align;
            $Seq[$i]['grptotal'] = '';
            $Seq[$i]['rpttotal'] = '';
            $Seq[$i]['link'] = $DataFields->link;
            $i++;
        }
    }
// patch for special_reports where the data file is generated externally from the standard function
    if ($report->special_class) {
        if (!$path = find_special_class($report->special_class))
            return false;
        load_special_language($path, $report->special_class);
        require_once($path . '/classes/' . $report->special_class . '.php');
        $sp_report = new $report->special_class;
        return $sp_report->load_report_data($report, $Seq, $sql, $GrpField); // the special report formats all of the data, we're done
    }

    $result = $db->Execute($sql);
    if ($result->RecordCount() == 0)
        return false; // No data so bail now
// Generate the output data array
    $RowCnt = 0;
    $ColCnt = 1;
    $GrpWorking = false;
    while (!$result->EOF) {
        $myrow = $result->fields;

//this is for GST Tax Return Reports show/hide Field 7 and Field 8 coding by 4axiz(apu) date-06-jan-2014
        if ($report->title == "GST Tax Return Reports") {
            if ($myrow['c0'] == "Field 7" || $myrow['c0'] == "Field 8") {
                if ($myrow['c2'] < 0) {
                    $myrow['c2'] = "";
//                    $result->MoveNext();
//                    continue;
                }
            }
        }
// Check to see if a total row needs to be displayed
        if (isset($GrpField)) { // we're checking for group totals, see if this group is complete
            if (($myrow[$GrpField] <> $GrpWorking) && $GrpWorking !== false) { // it's a new group so print totals
                $OutputArray[$RowCnt][0] = 'g:' . ProcessData($GrpWorking, $GrpFieldProcessing);
                foreach ($Seq as $offset => $TotalCtl) {
                    $OutputArray[$RowCnt][$offset + 1] = ($TotalCtl['total'] == '1') ? ProcessData($TotalCtl['grptotal'], $TotalCtl['processing']) : ' ';
                    $Seq[$offset]['grptotal'] = ''; // reset the total
                }
                $RowCnt++; // go to next row
            }
            $GrpWorking = $myrow[$GrpField]; // set to new grouping value
        }

        foreach ($Seq as $key => $TableCtl) { // 
            if ($report->totalonly <> '1') { // insert data into output array and set to next column
                $OutputArray[$RowCnt][0] = 'd'; // let the display class know its a data element
                $OutputArray[$RowCnt][$ColCnt] = ProcessData($myrow[$TableCtl['fieldname']], $TableCtl['processing']);
            }
            $ColCnt++;
            if ($TableCtl['total']) { // add to the running total if need be
                $Seq[$key]['grptotal'] += $myrow[$TableCtl['fieldname']];
                $Seq[$key]['rpttotal'] += $myrow[$TableCtl['fieldname']];
            }
        }
        $RowCnt++;
        $ColCnt = 1;
        $result->MoveNext();
    }
    if ($GrpWorking !== false) { // if we collected group data show the final group total
        $OutputArray[$RowCnt][0] = 'g:' . ProcessData($GrpWorking, $GrpFieldProcessing);
        foreach ($Seq as $TotalCtl) {
            $OutputArray[$RowCnt][$ColCnt] = ($TotalCtl['total'] == '1') ? ProcessData($TotalCtl['grptotal'], $TotalCtl['processing']) : ' ';
            $ColCnt++;
        }
        $RowCnt++;
        $ColCnt = 1;
    }
// see if we have a total to send
    $ShowTotals = false;
    foreach ($Seq as $TotalCtl)
        if ($TotalCtl['total'] == '1')
            $ShowTotals = true;
    if ($ShowTotals) {
        $OutputArray[$RowCnt][0] = 'r:' . $report->title;
        foreach ($Seq as $TotalCtl) {
            if ($TotalCtl['total'])
                $OutputArray[$RowCnt][$ColCnt] = ProcessData($TotalCtl['rpttotal'], $TotalCtl['processing']);
            else
                $OutputArray[$RowCnt][$ColCnt] = ' ';
            $ColCnt++;
        }
    }
//echo 'output array = '; print_r($OutputArray); echo '<br />'; exit();
    return $OutputArray;
}

function ReplaceNonAllowedCharacters($String) {
    $DodgyCharactersArray = array('"', ' ', '&', "'");
    $ContainsDodgyCharacters = true;
    while ($ContainsDodgyCharacters == true) {
        $ContainsDodgyCharacters = false; //assume all dodgy characters are replaced on the last pass
        foreach ($DodgyCharactersArray as $DodgyCharacter) {
            if (strpos($String, $DodgyCharacter, 0)) {
                $StrPointer = strpos($String, $DodgyCharacter, 0);
                $String = substr($String, 0, $StrPointer) . '_' . substr($String, $StrPointer + 1);
                $ContainsDodgyCharacters = true;
            }
        }
    }
    return $String;
}

function GeneratePDFFile($Data, $report, $delivery_method = 'D') { // for pdf reports only
    require_once(DIR_FS_MODULES . 'ucform/classes/report_generator.php');
    $pdf = new PDF();
    $pdf->ReportTable($Data);
    $ReportName = ReplaceNonAllowedCharacters($report->title) . '.pdf';
    if ($delivery_method == 'S')
        return array('filename' => $ReportName, 'pdf' => $pdf->Output($ReportName, 'S'));
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename=' . $ReportName);
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    $pdf->Output($ReportName, $delivery_method);
    exit(); // needs to be here to properly render the pdf file.
}

function GenerateHTMLFile($Data, $report, $delivery_method = 'D') { // for html reports only
    require_once(DIR_FS_MODULES . 'ucform/classes/html_generator.php');

//this is for remove "Group Total For:" in Report: GST Tax Return Reports 
    if ($report->title == "GST Tax Return Reports") {
        array_pop($Data);
    }

    $html = new HTML();
    $html->title = ReplaceNonAllowedCharacters($report->title);
    $html->ReportTable($Data);
    $ReportName = ReplaceNonAllowedCharacters($report->title) . '.html';
    if ($delivery_method == 'S')
        return array('filename' => $ReportName, 'pdf' => $html->output);
    echo $html->output;
    exit();
}

function isdGeneratePDFFile($ReportData, $report, $company_name) {
    require_once(DIR_FS_ADMIN . '/includes/mpdf/mpdf.php');
    $mpdf = new mPDF();
    $totalField = count($report->fieldlist);
    $output = '';
//  for ($i = 2; $i < $totalField; $i++) {
    unset($ReportData[0]);
    $output .= '<div style=" text-align:center; font-size:10px;"><span style="font-size:14px; color:blue;">' . $company_name . '</span><br>';
    $output .= '<span style="color:red">Income Statement Detail</span><br>';
    $output .= 'Report Generated on ' . date('d/m/Y') . '<br>';
    $output .= '<table style="width:100%; border:2px solid"><tr>';
    foreach ($report->fieldlist as $key => $field) {
        if ($key >= 14) {
            $extra_field[] = $field->description;
            continue;
        }
        if ($key != 0)
            $output .='<th>' . $field->description . '</th>';
    }
    $output .= '</tr>';

    foreach ($ReportData as $data) {
        if (empty($data[2]))
            continue;

        $idt = end($data);
        if ($idt == 'total') {
            array_pop($data);
        }
        $row = count($data);
        if ($row >= 14) {
            $row = 12;
        } else {
            $row = $row - 3;
        }
        if ($idt != 'total') {
            $output .= '<tr>';
        } else {
            $output .= '<tr bgcolor="#e2e4ff">';
        }
        for ($i = 0; $i <= $row; $i++) {
            if ($i == 0) {
                $output .= '<td>' . $data[2] . '</td>';
            } else {
                $value = explode('-', $data[$i + 2]);
                if ($idt == 'total')
                    $output .= '<td>' . $value[0] . '</td>';
                else
                    $output .= '<td>' . $value[1] . '</td>';
            }
        }
        $output .= '</tr>';
    }
    $output .= '</table><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';

    if (!empty($extra_field)) {
        $output = get_extra_field($extra_field, $output, $ReportData, $company_name, $report);
    }


    ini_set("max_execution_time", '5000');
    $mpdf->useSubstitutions = true; // optional - just as an example
    $mpdf->CSSselectMedia = 'mpdf'; // assuming you used this in the document header
    $mpdf->SetFooter('pag. {PAGENO} di {nb} - {DATE d/m/Y}');
    $mpdf->AddPage('L', // L - landscape, P - portrait
            '', '', '', '', 10, // margin_left
            10, // margin right
            22, // margin top
            15, // margin bottom
            5, // margin header
            5); // margin footer
    $mpdf->WriteHTML($output);
    $mpdf->Output();
    exit;
}

function get_extra_field($extra_field, $output, $ReportData, $company_name, $report) {
    $output .= '<div style=" text-align:center; font-size:10px;"><span style="font-size:14px; color:blue;">' . $company_name . '</span><br>';
    $output .= '<span style="color:red">Income Statement Detail</span><br>';
    $output .= 'Report Generated on ' . date('d/m/Y') . '<br>';
    $output .= '<table style="width:100%; border:2px solid"><tr>';
    foreach ($extra_field as $key => $field) {
        if ($key >= 14)
            $extra_field[] = $field;
        if ($key == 0) {
            $output .='<th>' . $report->fieldlist[1]->description . '</th>';
            $output .='<th>' . $field . '</th>';
        } else {
            $output .='<th>' . $field . '</th>';
        }
    }
    $output .= '</tr>';
    foreach ($ReportData as $data) {
        $idt = end($data);
        if ($idt == 'total') {
            array_pop($data);
        }

        if ($idt != 'total') {
            $output .= '<tr>';
        } else {
            $output .= '<tr bgcolor="#e2e4ff">';
        }

        for ($i = 0; $i <= count($extra_field); $i++) {
            if ($i == 0) {
                $output .= '<td>' . $data[2] . '</td>';
            } else {
                $value = explode('-', $data[$i + 14]);
                if ($idt == 'total')
                    $output .= '<td>' . $value[0] . '</td>';
                else
                    $output .= '<td>' . $value[1] . '</td>';
            }
        }
        $output .= '</tr>';
    }
    return $output .= '</table><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
}

function isdGenerateCSVFile($ReportData, $report, $delivery_method) {
    $CSVOutput = '';
    $CSVLine = '';
    foreach ($report->fieldlist as $key => $mycolumn) { // check for embedded commas and enclose in quotes
        if ($key != 0) {
            $CSVLine .= $mycolumn->description . ',';
        }
    }
    $CSVOutput .= $CSVLine . chr(10);
    $CSVLine = '';
    foreach ($ReportData as $key => $mycolumn) { // check for embedded commas and enclose in quotes
        $CSVLine = '';
        $idt = end($mycolumn);
        if ($idt == 'total') {
            array_pop($mycolumn);
        }
        foreach ($mycolumn as $key => $each) {
            if ($key > 1) {
                $value = explode('-', $each);
                if (isset($value[1])) {
                    $CSVLine .= '"' . $value[1] . '",';
                } else {
                    $CSVLine .= '"' . $value[0] . '",';
                }
            }
        }
        $CSVOutput .= $CSVLine . chr(10);
    }

    $ReportName = ReplaceNonAllowedCharacters($report->title) . '.csv';
    if ($delivery_method == 'S')
        return array('filename' => $ReportName, 'pdf' => $CSVOutput);
    header("Content-type: application/csv");
    header("Content-disposition: attachment; filename=" . $report->title . ".csv; size=" . strlen($CSVOutput));
    header('Pragma: cache');
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Connection: close');
    header('Expires: ' . date('r', time() + 60 * 60));
    header('Last-Modified: ' . date('r'));
    print $CSVOutput;
    exit();
}

function GenerateCSVFile($Data, $report, $delivery_method = 'D') { // for csv reports only
    global $Heading, $posted_currencies;
    $posted_currencies = array('currencies_code' => DEFAULT_CURRENCY, 'currencies_value' => 1); // use default currency
    $CSVOutput = '';
//codeing by shaheen for remove extra fields

    if ($report->title == 'General Ledger') {
        if ($report->fieldlist[1]->description == 'Journal Id') {
            unset($report->fieldlist[1]);
            unset($Heading[1]);
            $Heading = array_values($Heading);
            $report->fieldlist = array_values($report->fieldlist);
        }
    }

// Write the column headings
    foreach ($Heading as $mycolumn) { // check for embedded commas and enclose in quotes
        $CSVOutput .= (strpos($mycolumn, ',') === false) ? ($mycolumn . ',') : ('"' . $mycolumn . '",');
    }
    $CSVOutput = substr($CSVOutput, 0, -1) . chr(10); // Strip the last comma off and add line feed
// Now write each data line and totals
    foreach ($Data as $myrow) {
        $Action = array_shift($myrow);
//codeing by shaheen for remove extra fields
        if ($report->title == 'Balance Sheet') {
            unset($myrow[4]);
            $myrow = array_values($myrow);
        }
        if ($report->title == "General Ledger") {
            if (count($myrow) >= 8) {
                unset($myrow[1]);
                $myrow = array_values($myrow);
            }
        }
        $todo = explode(':', $Action); // contains a letter of the date type and title/groupname
        switch ($todo[0]) {
            case "r": // Report Total
            case "g": // Group Total
                $Desc = ($todo[0] == 'g') ? TEXT_GROUP_TOTAL_FOR : TEXT_REPORT_TOTAL_FOR;
                $CSVOutput .= $Desc . $todo[1] . chr(10);
// Now write the total data like any other data row
            case "d": // Data
            default:
                $CSVLine = '';
                foreach ($myrow as $mycolumn) { // check for embedded commas and enclose in quotes
                    $CSVLine .= (strpos($mycolumn, ',') === false) ? ($mycolumn . ',') : ('"' . $mycolumn . '",');
                }
                $CSVLine = substr($CSVLine, 0, -1); // Strip the last comma off
        }
        $CSVOutput .= $CSVLine . chr(10);
    }
    $ReportName = ReplaceNonAllowedCharacters($report->title) . '.csv';
    if ($delivery_method == 'S')
        return array('filename' => $ReportName, 'pdf' => $CSVOutput);
    header("Content-type: application/csv");
    header("Content-disposition: attachment; filename=" . $report->title . ".csv; size=" . strlen($CSVOutput));
    header('Pragma: cache');
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Connection: close');
    header('Expires: ' . date('r', time() + 60 * 60));
    header('Last-Modified: ' . date('r'));
    print $CSVOutput;
    exit();
}

function GenerateXMLFile($Data, $report, $delivery_method = 'D') { // for csv reports only
    global $Heading, $posted_currencies;
// Now write each data line and totals
//print_r($Data);
    foreach ($Data as $myrow) {
        $xml .= '<Row>' . chr(10);
        $Action = array_shift($myrow);
        $todo = explode(':', $Action); // contains a letter of the date type and title/groupname
        switch ($todo[0]) {
            case "r": // Report Total
            case "g": // Group Total
                $Desc = ($todo[0] == 'g') ? TEXT_GROUP_TOTAL_FOR : TEXT_REPORT_TOTAL_FOR;
                $xml .= '<' . $Desc . '>' . $todo[1] . '</' . $Desc . '>' . chr(10);
// Now write the total data like any other data row
            case "d": // Data
            default:
                $i = 0;
                foreach ($Heading as $title) {
//foreach ($myrow as $mycolumn) { // check for embedded commas and enclose in quotes
                    $xml .= '<' . $title . '>' . $myrow[$i] . '</' . $title . '>' . chr(10);
                    $i++;
                }
        }
        $xml .= '</Row>' . chr(10);
    }
    $ReportName = ReplaceNonAllowedCharacters($report->title) . '.csv';
    if ($delivery_method == 'S')
        return array('filename' => $ReportName, 'pdf' => $CSVOutput);
    global $db, $messageStack;
    $error = false;
    $output = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . chr(10);
    $output .= '<UcformReport>' . chr(10);
    $output .= $xml;
    $output .= '</UcformReport>' . chr(10);

    print $output;
    exit();
    echo createXmlHeader() . $xml . createXmlFooter();
    die;
}

function CreateFieldArray($report) {
    global $db;
    $output = array(array('id' => '', 'text' => TEXT_SELECT));
    if (is_array($report->tables))
        foreach ($report->tables as $tObj) {
            if ($tObj->tablename) {
                $result = $db->Execute("describe " . DB_PREFIX . $tObj->tablename);
                while (!$result->EOF) {
                    $fieldname = $tObj->tablename . '.' . $result->fields['Field'];
                    $output[] = array('id' => $fieldname, 'text' => DB_PREFIX . $fieldname);
                    $result->MoveNext();
                }
            }
        }
    return $output;
}

function strip_tablename($value) {
    if (strpos($value, '.') !== false) {
        return substr($value, strpos($value, '.') + 1);
    } else {
        return $value;
    }
}

function CreateTableList($report) {
    global $db;
    $output = array(array('id' => '', 'text' => TEXT_SELECT));
    $result = $db->Execute("show tables");
    while (!$result->EOF) {
        $tablename = array_shift($result->fields);
        $BaseTableName = (DB_PREFIX) ? substr($tablename, strlen(DB_PREFIX)) : $tablename;
        $output[] = array('id' => $BaseTableName, 'text' => $tablename);
        $result->MoveNext();
    }
    return $output;
}

function CreateSpecialDropDown($report) {
    if ($report->special_class) {
        if (!$path = find_special_class($report->special_class))
            return false;
        load_special_language($path, $report->special_class);
        require_once($path . '/classes/' . $report->special_class . '.php');
        $sp_report = new $report->special_class;
        return $sp_report->build_selection_dropdown();
    }
    return CreateFieldArray($report);
}

function CreateFieldTblDropDown($report) {
    if ($report->special_class) {
        if (!$path = find_special_class($report->special_class))
            return false;
        load_special_language($path, $report->special_class);
        require_once($path . '/classes/' . $report->special_class . '.php');
        $sp_report = new $report->special_class;
        return $sp_report->build_table_drop_down();
    }
    return CreateFieldArray($report);
}

function crit_build_pull_down($keyed_array) {
    $values = array();
    if (is_array($keyed_array)) {
        foreach ($keyed_array as $key => $value) {
            $value = substr($value, 2);
            $words = explode(':', $value);
            foreach ($words as $idx => $word)
                $words[$idx] = constant('TEXT_' . $word);
            $values[] = array('id' => $key, 'text' => implode(':', $words));
        }
    }
    return $values;
}

function ReadImages() {
    $OptionList = array();
    $dh = opendir(PF_DIR_MY_REPORTS . 'images/');
    while ($DefRpt = readdir($dh)) {
        $pinfo = pathinfo(PF_DIR_MY_REPORTS . 'images/' . $DefRpt);
        $Ext = strtoupper($pinfo['extension']);
        if ($Ext == 'JPG' || $Ext == 'JPEG' || $Ext == 'PNG') { //fpdf only supports JPG and PNG formats!!!
            $OptionList[] = array('id' => $pinfo['basename'], 'text' => $pinfo['basename']);
        }
    }
    closedir($dh);
    return $OptionList;
}

function CreateCompanyArray() {
    $company_array = array(
        array('id' => '', 'text' => TEXT_SLCTFIELD),
        array('id' => 'COMPANY_NAME', 'text' => COMPANY_NAME),
        array('id' => 'COMPANY_ADDRESS1', 'text' => COMPANY_ADDRESS1),
        array('id' => 'COMPANY_ADDRESS2', 'text' => COMPANY_ADDRESS2),
        array('id' => 'COMPANY_CITY_TOWN', 'text' => COMPANY_CITY_TOWN),
        array('id' => 'COMPANY_ZONE', 'text' => COMPANY_ZONE),
        array('id' => 'COMPANY_POSTAL_CODE', 'text' => COMPANY_POSTAL_CODE),
        array('id' => 'COMPANY_COUNTRY', 'text' => COMPANY_COUNTRY),
        array('id' => 'COMPANY_TELEPHONE1', 'text' => COMPANY_TELEPHONE1),
        array('id' => 'COMPANY_TELEPHONE2', 'text' => COMPANY_TELEPHONE2),
        array('id' => 'COMPANY_FAX', 'text' => COMPANY_FAX),
        array('id' => 'COMPANY_EMAIL', 'text' => COMPANY_EMAIL),
        array('id' => 'COMPANY_WEBSITE', 'text' => COMPANY_WEBSITE),
        array('id' => 'TAX_ID', 'text' => TAX_ID),
        array('id' => 'COMPANY_ID', 'text' => COMPANY_ID),
        array('id' => 'AR_CONTACT_NAME', 'text' => AR_CONTACT_NAME),
        array('id' => 'AP_CONTACT_NAME', 'text' => AP_CONTACT_NAME),
    );
    return $company_array;
}

?>