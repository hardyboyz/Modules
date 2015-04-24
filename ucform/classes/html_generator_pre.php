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
//  Path: /modules/ucform/classes/html_generator.php
//

class HTML {

    function __construct() {
        $this->output = NULL;
        $this->FillColor = '224:235:255';
        $this->HdColor = '#CCCCCC';
    }

    function AddHeading() {
        global $report;
        // determine the number of columns and build heading
//	foreach ($report->fieldlist as $Temp) $ColBreak[] = ($Temp['break']) ? true : false;
        $html_heading = array();
        $data = NULL;
        if ($report->title == 'General Ledger') {
            if ($report->fieldlist[1]->description == 'Journal Id') {
                unset($report->fieldlist[1]);
            }
        } else if ($report->title == 'Income Statement' || $report->title == 'Income Statement Detail' || $report->title == 'Income Statement w/Budget' || $report->title == 'Income Stmt - 2 Years') {
            if ($report->fieldlist[0]->description == 'Account ID') {
                unset($report->fieldlist[0]);
            }
        }
        foreach ($report->fieldlist as $value) {
            if ($value->visible) {
                $data .= htmlspecialchars($value->description);
                if (!$value->columnbreak) {
                    $data .= '<br />';
                    continue;
                }
                $html_heading[] = array('align' => $align, 'value' => $data);
                $data = NULL;
            }
        }
        if ($data !== NULL) { // catches not checked column break at end of row
            $html_heading[] = array('align' => $align, 'value' => $data);
        }
        $this->numColumns = sizeof($html_heading);

        $rStyle = '';
        if ($report->page->heading->show) { // Show the company name
            $color = $this->convert_hex($report->page->heading->color);
            $dStyle = 'style=" border-right: 2px solid #FFFFFF;font-family:' . $report->page->heading->font . '; color:' . $color . '; font-size:' . $report->page->heading->size . 'pt; font-weight:bold;"';
            $this->writeRow(array(array('align' => $report->page->heading->align, 'value' => htmlspecialchars(COMPANY_NAME))), $rStyle, $dStyle, $heading = true);
        }
        if ($report->page->title1->show) { // Set title 1 heading
            $color = $this->convert_hex($report->page->title1->color);
            $dStyle = 'style=" border-right: 2px solid #FFFFFF;font-family:' . $report->page->title1->font . '; color:' . $color . '; font-size:' . $report->page->title1->size . 'pt;"';
            $this->writeRow(array(array('align' => $report->page->title1->align, 'value' => htmlspecialchars(TextReplace($report->page->title1->text)))), $rStyle, $dStyle, $heading = true);
        }
        if ($report->page->title2->show) { // Set Title 2 heading
            $color = $this->convert_hex($report->page->title2->color);
            $dStyle = 'style=" border-right: 2px solid #FFFFFF;font-family:' . $report->page->title2->font . '; color:' . $color . '; font-size:' . $report->page->title2->size . 'pt;"';
            $this->writeRow(array(array('align' => $report->page->title2->align, 'value' => htmlspecialchars(TextReplace($report->page->title2->text)))), $rStyle, $dStyle, $heading = true);
        }
        // Set the filter heading
        $color = $this->convert_hex($report->page->filter->color);
        $dStyle = 'style=" border-right: 2px solid #FFFFFF;font-family:' . $report->page->filter->font . '; color:' . $color . '; font-size:' . $report->page->filter->size . 'pt;"';
        $this->writeRow(array(array('align' => $report->page->filter->align, 'value' => htmlspecialchars(TextReplace($report->page->filter->text)))), $rStyle, $dStyle, $heading = true);
        // Set the table header
        $color = $this->convert_hex($report->page->data->color);
        $rStyle = 'style="background-color:' . $this->HdColor . '"';
        $dStyle = 'style=" border-right: 2px solid #FFFFFF;font-family:' . $report->page->data->font . '; color:' . $color . '; font-size:' . $report->page->data->size . 'pt;"';
        $align = $report->page->data->align;

        // Ready to draw the column titles in the header
        $this->writeRow($html_heading, $rStyle, $dStyle);
    }

    function writeRow($aData, $rStyle = '', $dStyle = '', $heading = false, $journal_id = false, $id = false, $sub_journal_id = false) {
        $output = '  <tr';
        $output .= (!$rStyle ? '' : ' ' . $rStyle) . '>' . chr(10);
        foreach ($aData as $i => $value) {
            $params = NULL;
            if ($heading)
                $params .= ' colspan="' . $this->numColumns . '"';

            if (array_key_exists('colspan', $value)) {
                $params .= ' colspan="' . $value['colspan'] . '"';
            }
            $output .= '    <td';
            switch ($value['align']) {
                case 'C': $params .= ' align="center"';
                    break;
                case 'R': $params .= ' align="right"';
                    break;
                default:
                case 'L':
                    break;
            }
            $output .= $params . (!$dStyle ? '' : ' ' . $dStyle) . '>';
            if ($value['link'] != NULL) {
                $dynamic_url = explode(':', $value['link']);
                $id = $id ? $id : $aData[0]['value'];
                if (isset($dynamic_url[2])) {
                    if ($journal_id == 18) {
                        $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=bills' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&type=c&action=edit" target="_blank">' . chr(10);
                    } else if ($journal_id == 20) {
                        $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=bills' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&type=v&action=edit" target="_blank">' . chr(10);
                    } else if ($journal_id == 2) {
                        switch ($sub_journal_id) {
                            case 1 :
                                $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=journal' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=2&sub_jID=1&action=edit" target="_blank">' . chr(10);
                                break;
                            case 2 :
                                $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=spend_receive_money' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=2&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            case 3 :
                                $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=receive_spend_money' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=2&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            default :
                                $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=journal' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=2&action=edit" target="_blank">' . chr(10);
                                break;
                        }
                     
                    } else if ($journal_id == 33) {
                        $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=journal' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=2&sub_jID=1&action=edit" target="_blank">' . chr(10);
                    } else if ($journal_id == 34) {
                        $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=spend_receive_money' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=2&sub_jID=2&action=edit" target="_blank">' . chr(10);
                    } else if ($journal_id == 35) {
                        $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=receive_spend_money' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=2&sub_jID=3&action=edit" target="_blank">' . chr(10);
                    } else if ($journal_id == 16) {
                        switch ($sub_journal_id) {
                            case 0 :
                                $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=inventory&page=adjustments' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            case 1 :
                                $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=inventory&page=transfer' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            default :
                                $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=inventory&page=adjustments' . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                        }
                    } else if ($journal_id == 6) {
                        switch ($sub_journal_id) {
                            case 0 :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            case 1 :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            default :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                        }
                    } else if ($journal_id == 13) {
                        switch ($sub_journal_id) {
                            case 0 :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            case 1 :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            default :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                        }
                    }else if ($journal_id == 12) {
                        switch ($sub_journal_id) {
                            case 0 :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            case 1 :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            case 2 :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                            default :
                                $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                                break;
                        }
                    }
                    else
                        $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . $dynamic_url[2] . '=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
                } else {
                    $output .= '<a href="' . DIR_WS_ADMIN . $dynamic_url[0] . $dynamic_url[1] . '=' . $id . '" target="_blank">' . chr(10);
                }
                $output .= ($value['value'] == '') ? '&nbsp;' : $value['value'];
                $output .= '</a></td>' . chr(10);
            } elseif ($value['bal_link'] != NULL && $i != 0) {
                $output .= '<a onclick="goToBalanceSheet(\'' . $value['bal_link'] . '\')" href="javascript:">' . chr(10);
                $output .= ($value['value'] == '') ? '&nbsp;' : $value['value'];
                $output .= '</a></td>' . chr(10);
            } elseif ($value['in_stat'] != NULL && $i != 0) {
                if ($i == 1) {
                    $output .= '<a onclick="goToIncomestatement(\'' . $value['in_stat'] . '\')" href="javascript:">' . chr(10);
                } else {
                    $output .= '<a onclick="goToBalanceSheet(\'' . $value['in_stat'] . '\')" href="javascript:">' . chr(10);
                }

                $output .= ($value['value'] == '') ? '&nbsp;' : $value['value'];
                $output .= '</a></td>' . chr(10);
            } elseif ($value['in_stat_link'] != NULL && $i != 0) {
                $val = explode(',', $value['in_stat_link']);
                if ($i == 1) {
                    $output .= '<a onclick="goToIncomestatementdetail(' . $val[0] . ',' . $val[1] . ')" href="javascript:">' . chr(10);
                } else {
                    $output .= '<a onclick="goToBalanceSheetdetail(' . $val[0] . ',' . $val[1] . ')" href="javascript:">' . chr(10);
                }

                $output .= ($value['value'] == '') ? '&nbsp;' : $value['value'];
                $output .= '</a></td>' . chr(10);
            } else {
                $output .= ($value['value'] == '') ? '&nbsp;' : $value['value'];
                $output .= '</td>' . chr(10);
            }
        }
        $output .= '  </tr>' . chr(10);
        $this->output .= $output;
    }

    function ReportTable($Data) {
        global $report, $Seq, $db;
        $this->output .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . chr(10);
        $this->output .= '<html xmlns="http://www.w3.org/1999/xhtml" ' . HTML_PARAMS . '>' . chr(10);
        $this->output .= '<head>' . chr(10);
        $this->output .= '<meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET . '" />' . chr(10);
        $this->output .= '<title>' . $this->title . '</title>' . chr(10);
        $this->output .= '</head>' . chr(10);
        $this->output .= '<body>' . chr(10);
        if ($report->title == 'Income Statement Detail') {
            $this->output .= '<style type="text/css">#report_viewer{overflow-x:scroll;}</style>' . chr(10);
        }
        $this->output .= '<table width="98%" align="center">' . chr(10);

        ////$this->output .='<tr><td> <img src="my_files/'.$_SESSION['company'].'/company_logo/index.jpg"/> </td></tr>';
        $this->AddHeading();

        if (!is_array($Data)) {
            $this->output .= '<tr><td>' . TEXT_NO_DATA . '</td></tr>' . chr(10);
            $this->output .= '</table>' . chr(10);
            $this->output .= '</body>' . chr(10);
            $this->output .= '</html>' . chr(10);
            return;
        }
        $color = $this->convert_hex($this->FillColor);
        $bgStyle = 'style="background-color:' . $color . '"';

        $color = str_replace(':', '', $report->page->data->color);
        $dStyle = 'style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:' . $report->page->data->font . '; color:' . $color . '; font-size:' . $report->page->data->size . 'pt;"';
        // Fetch the column break array and alignment array
        foreach ($report->fieldlist as $value) {
            if ($value->visible) {
                $ColBreak[] = ($value->columnbreak) ? true : false;
                $Colwidth[] = ($value->columnwidth);
                $align[] = $value->align;
                $link[] = $value->link;
            }
        }
        // Ready to draw the column data
        $fill = false;
        $NeedTop = 'No';
        foreach ($Data as $myrow) {
            if ($report->title == "General Ledger") {
                if (count($myrow) >= 8) {
                    if ($myrow[1] != ' ') {
                        $sub_jID_array = $db->Execute('SELECT sub_journal_id from ' . TABLE_JOURNAL_MAIN . ' where id=' . $myrow[1]);
                    }
                    $journal_id = $myrow[2];
                    $sub_journal_id = $sub_jID_array->fields['sub_journal_id'] ? $sub_jID_array->fields['sub_journal_id'] : 0;

                    unset($myrow[2]);
                }
            } else if ($report->title == "Income Statement" || $report->title == 'Income Statement w/Budget' || $report->title == 'Income Stmt - 2 Years') {
                if (count($myrow) >= 5) {
                    $account_id = $myrow[1];
                    unset($myrow[1]);
                }
            } else if ($report->title == 'Income Statement Detail') {
                $account_id = $myrow[1];
                unset($myrow[1]);
                $idt = end($myrow);
                if ($idt == 'total') {
                    array_pop($myrow);
                }
            } else if ($report->title == "Customer Statement") {

                if (count($myrow) >= 8) {
                    $id = $myrow[1];
                    $journal_id = $myrow[2];
                    unset($myrow[1]);
                    unset($myrow[2]);
                }
            }

            $Action = array_shift($myrow);
            $todo = explode(':', $Action); // contains a letter of the date type and title/groupname
            switch ($todo[0]) {
                case "h": // Heading
                    $this->writeRow(array(array('align' => $report->page->data->align, 'value' => $todo[1])), '', $dStyle);
                    break;
                case "r": // Report Total
                case "g": // Group Total
                    $Desc = ($todo[0] == 'g') ? TEXT_GROUP_TOTAL_FOR : TEXT_REPORT_TOTAL_FOR;
                    $rStyle = 'style="background-color:' . $this->HdColor . '"';
                    $temps = array();
                    //unset($myrow[0]);
                    if ($report->title == "Customer Payments" || $report->title == "Asset List" || $report->title == "Vendor Payments" || $report->title == "Open Purchase Orders" || $report->title == "Customer Invoice History" || $report->title == "Open Sales Orders" || $report->title == "Sales By SKU" || $report->title == "Sales Order Report" || $report->title == "Sales Report") {
                        //for remove 1 column
                        $colspan = $this->numColumns - 1;
                    } else if ($report->title == "Daily Cash Report" || $report->title == "General Ledger Trial Balance" || $report->title == "General Ledger" || $report->title == "GST(Purchase)" || $report->title == "Project Summary Report" || $report->title == "GST(Supply)" || $report->title == "Vendor Ledger" || $report->title == "Customer Ledger") {
                        //for remove 2 column
                        $colspan = $this->numColumns - 2;
                    } else if ($report->title == "Aged Payables Summary" || $report->title == "Aged Payables" || $report->title == "Aged Receivables Summary") {
                        //for remove 4 column
                        $colspan = $this->numColumns - 4;
                    } else if ($report->title == "Aged Receivables") {

                        $colspan = $this->numColumns - 3;

                        //$colspan  = $this->numColumns - $col;
                    } else if ($report->title == "Inventory Aging") {

                        //this is for qty
                        foreach ($myrow as $key => $value) {
                            //$data .= htmlspecialchars($value);
                            if ($value == " ") {
                                continue;
                            }
                            $data .= $value;
                            if (!$ColBreak[$key]) {
                                $data .= '<br />';
                                continue;
                            }
                            if ($key == 2) {
                                $temps[] = array('align' => $align[$key], 'value' => $data, 'colspan' => 3);
                                $temps[] = array('align' => 'C', 'value' => $Desc . $todo[1], 'colspan' => 4);
                                $data = NULL;
                                continue;
                            }

                            $temps[] = array('align' => $align[$key], 'value' => $data);

                            $data = NULL;
                        }
                        //$this->writeRow(array(array('align' => 'C', 'value' => $Desc . $todo[1])), $rStyle, $dStyle, true);
                        $this->writeRow($temps, $rStyle, $dStyle, false);
                        // now fall into the 'd' case to show the data
                        $fill = false;
                        break;
                    } else if ($report->title == "Inventory Valuation - Branch") {
                        //this is for qty
                        foreach ($myrow as $key => $value) {
                            //$data .= htmlspecialchars($value);
                            if ($value == " ") {
                                continue;
                            }
                            $data .= $value;
                            if (!$ColBreak[$key]) {
                                $data .= '<br />';
                                continue;
                            }
                            if ($key == 3) {
                                $temps[] = array('align' => $align[$key], 'value' => $data, 'colspan' => 4);
                                $temps[] = array('align' => 'C', 'value' => $Desc . $todo[1], 'colspan' => 2);
                                $data = NULL;
                                continue;
                            }

                            $temps[] = array('align' => $align[$key], 'value' => $data);

                            $data = NULL;
                        }
                        //$this->writeRow(array(array('align' => 'C', 'value' => $Desc . $todo[1])), $rStyle, $dStyle, true);
                        $this->writeRow($temps, $rStyle, $dStyle, false);
                        // now fall into the 'd' case to show the data
                        $fill = false;
                        break;
                    } else if ($report->title == "Inventory Valuation") {
                        //this is for qty
                        foreach ($myrow as $key => $value) {
                            //$data .= htmlspecialchars($value);
                            if ($value == " ") {
                                continue;
                            }
                            $data .= $value;
                            if (!$ColBreak[$key]) {
                                $data .= '<br />';
                                continue;
                            }
                            if ($key == 1) {
                                $temps[] = array('align' => $align[$key], 'value' => $data, 'colspan' => 2);
                                $temps[] = array('align' => 'C', 'value' => $Desc . $todo[1], 'colspan' => 2);
                                $data = NULL;
                                continue;
                            }

                            $temps[] = array('align' => $align[$key], 'value' => $data);

                            $data = NULL;
                        }
                        $temps[] = array('align' => $align[$key], 'value' => '');
                        //$this->writeRow(array(array('align' => 'C', 'value' => $Desc . $todo[1])), $rStyle, $dStyle, true);
                        $this->writeRow($temps, $rStyle, $dStyle, false);
                        // now fall into the 'd' case to show the data
                        $fill = false;
                        break;
                    } else if ($report->title == "Inventory Re-order Worksheet") {
                        $temps[] = array('align' => 'C', 'value' => $Desc . $todo[1], 'colspan' => 5);
                        foreach ($myrow as $key => $value) {
                            //$data .= htmlspecialchars($value);
                            if ($value == " ") {
                                continue;
                            }
                            $data .= $value;
                            if (!$ColBreak[$key]) {
                                $data .= '<br />';
                                continue;
                            }
//                            if ($key == 5) {
//                                $temps[] = array('align' => $align[$key], 'value' => $data, 'colspan' => 3);
//                                
//                                $data = NULL;
//                                continue;
//                            }

                            $temps[] = array('align' => 'L', 'value' => $data, 'colspan' => 3);

                            $data = NULL;
                        }
                        //$this->writeRow(array(array('align' => 'C', 'value' => $Desc . $todo[1])), $rStyle, $dStyle, true);
                        $this->writeRow($temps, $rStyle, $dStyle, false);
                        // now fall into the 'd' case to show the data
                        $fill = false;
                        break;
                    } else if ($report->title == "Sales By Rep") {
                        $colspan = $this->numColumns - 2;
                    } else if ($report->title == "Customer Statement") {

                        $colspan = 4;
                    } else {

                        $colspan = $this->numColumns;
                    }
                    //$colspn = 0;
                    foreach ($myrow as $index => $val) {
//                        if ($val == " ") {
//                            continue;
//                        }
                        //$colspn++;
                    }
                    if ($report->title == "General Ledger") {
                        $debit = str_replace(",", "", substr($myrow[6], 3));
                        $credit = str_replace(",", "", substr($myrow[7], 3));
                        $final_amount = number_format($debit - $credit, 2);
                        if ($Desc != 'Report Total For:')
                            $temps[] = array('align' => 'C', 'value' => $Desc . $todo[1] . '(Final amount : RM ' . $final_amount . ')', 'colspan' => $colspan);
                        else
                            $temps[] = array('align' => 'C', 'value' => $Desc . $todo[1], 'colspan' => $colspan);
                    } else {
                        $temps[] = array('align' => 'C', 'value' => $Desc . $todo[1], 'colspan' => $colspan);
                    }
                    foreach ($myrow as $key => $value) {
                        //$data .= htmlspecialchars($value);
                        if ($report->title == "Aged Receivables Summary") {
                            if ($value == "" && $value != '0') {
                                continue;
                            }
                        } else {
                            if ($value == " " && $value != '0') {


                                continue;
                            }
                        }
                        $data .= $value;
                        if (!$ColBreak[$key]) {
                            $data .= '<br />';
                            continue;
                        }

                        $temps[] = array('align' => $align[$key], 'value' => $data);

                        $data = NULL;
                    }
                    if ($report->title == "Sales By Rep") {
                        $temps[] = array('align' => $align[$key], 'value' => '');
                    }
                    //$this->writeRow(array(array('align' => 'C', 'value' => $Desc . $todo[1])), $rStyle, $dStyle, true);
                    $this->writeRow($temps, $rStyle, $dStyle, false);
                    // now fall into the 'd' case to show the data
                    $fill = false;
                    break;
                case "d": // data element
                default:
                    $temp = array();
                    $data = NULL;
                    foreach ($myrow as $key => $value) {
                        //$data .= htmlspecialchars($value);
                        $data .= $value;
                        if (!$ColBreak[$key]) {
                            $data .= '<br />';
                            continue;
                        }
                        if ($report->title == "Balance Sheet") {
                            $temp[] = array('align' => $align[$key], 'bal_link' => $myrow[4], 'value' => $data);
                            $data = NULL;
                        } elseif ($report->title == 'Income Statement' || $report->title == 'Income Statement w/Budget' || $report->title == 'Income Stmt - 2 Years' || $report->title == 'Income Statement Detail') {
                            if ($report->title == 'Income Statement') {
                                $temp[] = array('align' => $align[$key], 'in_stat' => $account_id, 'value' => $data);
                            } else if ($report->title == 'Income Statement Detail') {
                                if (isset($idt) && $idt != 'total') {
                                    if ($key != 0) {

                                        $period = explode('-', $data);
                                        $temp[] = array('align' => $align[$key], 'in_stat_link' => $account_id . ',' . $period[0], 'value' => $period[1]);
                                    } else {
                                        $temp[] = array('align' => $align[$key], 'link' => $link[$key], 'value' => $data);
                                    }
                                } else {
                                    $temp[] = array('align' => $align[$key], 'link' => $link[$key], 'value' => $data);
                                }
                            } else {
                                $temp[] = array('align' => $align[$key], 'bal_link' => $account_id, 'value' => $data);
                            }

                            $data = NULL;
                        } else {

                            $temp[] = array('align' => $align[$key], 'link' => $link[$key], 'value' => $data);
                            $data = NULL;
                        }
                    }
                    if ($report->title != "Balance Sheet" || $report->title == 'Income Statement' || $report->title == 'Income Statement w/Budget' || $report->title == 'Income Stmt - 2 Years' || $report->title == 'Income Statement Detail') {
                        if ($data !== NULL) { // catches not checked column break at end of row
                            $temp[] = array('align' => $align[$key], 'value' => $data);
                        }
                    }
                    $rStyle = $fill ? $bgStyle : '';
                    $this->writeRow($temp, $rStyle, $dStyle, '', $journal_id, $id, $sub_journal_id);
                    break;
            }
            $fill = !$fill;
        }
        // send a blank header row
        //  if(!empty($value->columnwidth)){
        //      $rStyle = 'style="background-color:' . $this->HdColor . ' width:' . $value->columnwidth . '"';
        //  }else{
        $rStyle = 'style="background-color:' . $this->HdColor . '"';
        // }

        $this->writeRow(array(array('align' => '', 'value' => '&nbsp;')), $rStyle, '', $heading = true);

        $this->output .= '</table>' . chr(10);
        $this->output .= '</body>' . chr(10);
        $this->output .= '</html>' . chr(10);
    }

    function convert_hex($value) {
        $colors = explode(':', $value);
        $output = NULL;
        foreach ($colors as $decimal) {
            $output .= str_pad(dechex($decimal), 2, "0", STR_PAD_LEFT);
        }
        return '#' . $output;
    }

}

// end class
?>