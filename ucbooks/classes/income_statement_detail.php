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
//  Path: /modules/ucform/custom/classes/is_budget.php
//

gen_pull_language('ucbooks', 'admin'); // need coa types defines

require_once(DIR_FS_MODULES . 'ucbooks/functions/ucbooks.php');
define('RW_FIN_REVENUES', 'Revenues');
define('RW_FIN_COST_OF_SALES', 'Cost of Sales');
define('RW_FIN_GROSS_PROFIT', 'Gross Profit');
define('RW_FIN_EXPENSES', 'Expenses');
define('RW_FIN_NET_INCOME', 'Net Income');

// drop down headings
define('IS_BUDGET_ACCOUNT', 'Account');
define('IS_BUDGET_CUR_MONTH', 'Current Month');
define('IS_BUDGET_YTD', 'Year To Date');
define('IS_BUDGET_LY_CUR', 'LY Curernt');
define('IS_BUDGET_LAST_YTD', 'Last YTD');
define('IS_BUDGET_CUR_BUDGET', 'Cur Budget');
define('IS_BUDGET_YTD_BUDGET', 'Budget YTD');

// this file contains special function calls to generate the data array needed to build reports not possible
// with the current ucform structure.
class income_statement_detail {

    function __construct() {
        global $currencies;
        $this->zero = $currencies->format(0);
        $this->coa_types = load_coa_types();
        $this->inc_stmt_data = array();
    }

    function load_report_data($report, $Seq) {
        global $db;
        $todate = $_POST['date_to'];
        $fromdate = $_POST['date_from'];

        $todateArray = explode('/', $todate);
        $todate_month = $todateArray[0];
        $todate_year = $todateArray[1];

        $totalday_to = cal_days_in_month(CAL_GREGORIAN, $todate_month, $todate_year);

        $fromdateArray = explode('/', $fromdate);
        $fromdate_month = $fromdateArray[0];
        $fromdate_year = $fromdateArray[1];

        $startdate = $fromdate_year . '-' . $fromdate_month . '-01';
        $enddate = $todate_year . '-' . $todate_month . '-' . $totalday_to;
        $period_values = $db->Execute("select period, start_date, end_date from " . TABLE_ACCOUNTING_PERIODS . " WHERE start_date >= '" . $startdate . "' and end_date <= '" . $enddate . "' order by period");
        //$period_values = $db->Execute("select period, start_date, end_date from " . TABLE_ACCOUNTING_PERIODS . " order by period");   
        $total_filedlist = count($report->fieldlist);
        while (!$period_values->EOF) {
            $report->fieldlist[$total_filedlist - 1]->fieldname = date('M-Y', strtotime($period_values->fields['start_date']));
            $report->fieldlist[$total_filedlist - 1]->description = date('M-Y', strtotime($period_values->fields['start_date']));
            $report->fieldlist[$total_filedlist - 1]->processing = 'def_cur';
            $report->fieldlist[$total_filedlist - 1]->columnbreak = 1;
            $report->fieldlist[$total_filedlist - 1]->align = 'R';
            $report->fieldlist[$total_filedlist - 1]->visible = 1;
            $report->fieldlist[$total_filedlist - 1]->columnwidth = 15;
            $total_filedlist++;
            $period_values->MoveNext();
        }

        $running_total = array();
        // Revenues
        $this->add_heading_line(RW_FIN_REVENUES);
        $total = $this->add_income_stmt_data(30, $negate = true); // Income account_type
        foreach ($total as $key => $value) {
            if ($key != 'current') {
                $grand_total[$key] = $value;
            } else {
                foreach ($value as $key => $value) {
                    $grand_total[$key] = $value;
                }
            }
        }
        // Less COGS
        $this->add_heading_line();
        $this->add_heading_line(RW_FIN_COST_OF_SALES);
        $total = $this->add_income_stmt_data(32, $negate = false); // Cost of Sales account_type
        foreach ($total as $key => $value) {
            if ($key != 'current') {
                $grand_total[$key] = $value;
            } else {
                foreach ($value as $key => $value) {
                    $grand_total[$key] -= $value;
                }
            }
        }
        $line = array(0 => 'd');
        foreach ($report->fieldlist as $key => $value) {
            $line[] = ProcessData($temp[$value->fieldname], $value->processing);
        }
        $line[] = 'total';
        $this->inc_stmt_data[] = $line;
        // Gross Profit
        $grand_total['description'] = RW_FIN_GROSS_PROFIT;
        $line = array(0 => 'd');
        foreach ($report->fieldlist as $key => $value) {
            $line[] = ProcessData($grand_total[$value->fieldname], $value->processing);
        }
        $line[] = 'total';
        $this->inc_stmt_data[] = $line;
        // Less Expenses
        $this->add_heading_line();
        $this->add_heading_line(RW_FIN_EXPENSES);
        $total = $this->add_income_stmt_data(34, $negate = false); // Expenses account_type
        foreach ($total as $key => $value) {
            if ($key != 'current') {
                $grand_total[$key] = $value;
            } else {
                foreach ($value as $key => $value) {
                    $grand_total[$key] -= $value;
                }
            }
        }
        // Net Income
        $this->add_heading_line();
        $grand_total['description'] = RW_FIN_NET_INCOME;
        $line = array(0 => 'd');
        foreach ($report->fieldlist as $key => $value) {

            $line[] = ProcessData($grand_total[$value->fieldname], $value->processing);
        }
        $line[] = 'total';
        $this->inc_stmt_data[] = $line;

        return $this->inc_stmt_data;
    }

    function add_income_stmt_data($type, $negate = false) {
        global $db, $report;

        $todate = $_POST['date_to'];
        $fromdate = $_POST['date_from'];

        $todateArray = explode('/', $todate);
        $todate_month = $todateArray[0];
        $todate_year = $todateArray[1];

        $totalday_to = cal_days_in_month(CAL_GREGORIAN, $todate_month, $todate_year);

        $fromdateArray = explode('/', $fromdate);
        $fromdate_month = $fromdateArray[0];
        $fromdate_year = $fromdateArray[1];

        $startdate = $fromdate_year . '-' . $fromdate_month . '-01';
        $enddate = $todate_year . '-' . $todate_month . '-' . $totalday_to;
        $period_values = $db->Execute("select period, start_date, end_date from " . TABLE_ACCOUNTING_PERIODS . " WHERE start_date >= '" . $startdate . "' and end_date <= '" . $enddate . "' order by period");
        $total = array('description' => TEXT_TOTAL . ' ' . $this->coa_types[$type]['text']);
        $i = 0;
        $temp = array();
        while (!$period_values->EOF) {
            $account_array = array();
            // current period
            $sql = "select c.id,h.period, c.description, h.debit_amount - h.credit_amount as balance, budget,h.account_id as account_id   
		from " . TABLE_CHART_OF_ACCOUNTS . " c inner join " . TABLE_CHART_OF_ACCOUNTS_HISTORY . " h on c.id = h.account_id
		where h.period = " . $period_values->fields['period'] . " and c.account_type = " . $type . " 
		order by c.id";
            $cur_period = $db->Execute($sql);

            $cur_total_1 = 0;



            while (!$cur_period->EOF) {
                $factor = $negate ? -1 : 1;
                if ($i == 1)
                    $temp[$cur_period->fields['id']]['description'] = $cur_period->fields['description'];
                $temp[$cur_period->fields['id']]['account_id'] = $cur_period->fields['id'];
                $temp[$cur_period->fields['id']]['period'][] = $cur_period->fields['period'];
                $temp[$cur_period->fields['id']]['current'][] = $factor * $cur_period->fields['balance'];
                $total['current'] += $factor * $cur_period->fields['balance'];
                $cur_period->MoveNext();
            }
            $total1['current'][date('M-Y', strtotime($period_values->fields['start_date']))] = $total['current'];
            $total['current'] = '';
            $i++;
            $period_values->MoveNext();
        }
        
        foreach ($temp as $acct) {
            $line = array(0 => 'd');
            $x = 0;
            $y = 0;
            foreach ($report->fieldlist as $key => $value) {

                if ($key == 1) {
                    $line[] = ProcessData($acct[$value->fieldname], $value->processing);
                } else if ($key == 0) {
                    $line[] =  ProcessData($acct[$value->fieldname], $value->processing);
                   
                } else {
                    $line[] = $acct['period'][$y].'-'.ProcessData($acct['current'][$x], $value->processing);
                    $x++;
                    $y++;
                }
            }

            $this->inc_stmt_data[] = $line;
        }
        $this->add_heading_line();
        $line = array(0 => 'd');
        foreach ($report->fieldlist as $key => $value) {
            if ($key == 1) {
                $line[] = ProcessData($total[$value->fieldname], $value->processing);
            } else if ($key == 0) {
                $line[] = ProcessData($acct[$value->fieldname], $value->processing);
            }  else {
                $line[] = ProcessData($total1['current'][$value->fieldname], $value->processing);
            }
        }
        $line[] = 'total';
        $this->inc_stmt_data[] = $line;
        $total['current'] = $total1['current'];
        return $total;
    }

    function add_heading_line($title = '') {
        global $report;
        $line = array('d', $title);
        for ($i = 0; $i < sizeof($report->fieldlist) - 1; $i++)
            $line[] = '';
        $this->inc_stmt_data[] = $line;
    }

    function build_table_drop_down() {
        $output = array();
        return $output;
    }

    function build_selection_dropdown() {
        // build user choices for this class with the current and newly established fields
        $output = array();
        $output[] = array('id' => 'description', 'text' => IS_BUDGET_ACCOUNT);
        $output[] = array('id' => 'current', 'text' => IS_BUDGET_CUR_MONTH);
        $output[] = array('id' => 'current_ytd', 'text' => IS_BUDGET_YTD);
        $output[] = array('id' => 'ly_current', 'text' => IS_BUDGET_LY_CUR);
        $output[] = array('id' => 'ly_ytd', 'text' => IS_BUDGET_LAST_YTD);
        $output[] = array('id' => 'budget_cur', 'text' => IS_BUDGET_CUR_BUDGET);
        $output[] = array('id' => 'budget_ytd', 'text' => IS_BUDGET_YTD_BUDGET);
        return $output;
    }

}

?>