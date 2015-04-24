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
//  Path: /modules/ucbooks/functions/ucbooks.php
//

function fetch_item_description($id) {
    global $db;
    $result = $db->Execute("select description from " . TABLE_JOURNAL_ITEM . " where ref_id = " . $id . " limit 1");
    return $result->fields['description'];
}

function validate_fiscal_year($next_fy, $next_period, $next_start_date, $num_periods = 12) {
    global $db;
    for ($i = 0; $i < $num_periods; $i++) {
        $fy_array = array(
            'period' => $next_period,
            'fiscal_year' => $next_fy,
            'start_date' => $next_start_date,
            'end_date' => gen_specific_date($next_start_date, $day_offset = -1, $month_offset = 1),
            'date_added' => date('Y-m-d'),
        );
        db_perform(TABLE_ACCOUNTING_PERIODS, $fy_array, 'insert');
        $next_period++;
        $next_start_date = gen_specific_date($next_start_date, $day_offset = 0, $month_offset = 1);
    }
    return $next_period--;
}

function modify_account_history_records($id, $add_acct = true) {
    global $db;
    $result = $db->Execute("select max(period) as period from " . TABLE_ACCOUNTING_PERIODS);
    $max_period = $result->fields['period'];
    if (!$max_period)
        die('table: ' . TABLE_ACCOUNTING_PERIODS . ' is not set, run setup.');
    if ($add_acct) {
        $result = $db->Execute("select heading_only from " . TABLE_CHART_OF_ACCOUNTS . " where id = '" . $id . "'");
        if ($result->fields['heading_only'] <> '1') {
            for ($i = 0, $j = 1; $i < $max_period; $i++, $j++) {
                $db->Execute("insert into " . TABLE_CHART_OF_ACCOUNTS_HISTORY . " (account_id, period) values('" . $id . "', '" . $j . "')");
            }
        }
    } else {
        $result = $db->Execute("delete from " . TABLE_CHART_OF_ACCOUNTS_HISTORY . " where account_id = '" . $id . "'");
    }
}

function build_and_check_account_history_records() {
    global $db;
    $result = $db->Execute("select max(period) as period from " . TABLE_ACCOUNTING_PERIODS);
    $max_period = $result->fields['period'];
    if (!$max_period)
        die('table: ' . TABLE_ACCOUNTING_PERIODS . ' is not set, run setup.');
    $result = $db->Execute("select id, heading_only from " . TABLE_CHART_OF_ACCOUNTS . " order by id");
    while (!$result->EOF) {
        if ($result->fields['heading_only'] <> '1') {
            $account_id = $result->fields['id'];
            for ($i = 0, $j = 1; $i < $max_period; $i++, $j++) {
                $record_found = $db->Execute("select id from " . TABLE_CHART_OF_ACCOUNTS_HISTORY . " where account_id = '" . $account_id . "' and period = " . $j);
                if (!$record_found->RecordCount()) {
                    $db->Execute("insert into " . TABLE_CHART_OF_ACCOUNTS_HISTORY . " (account_id, period) values('" . $account_id . "', '" . $j . "')");
                }
            }
        }
        $result->MoveNext();
    }
}

function get_fiscal_year_pulldown() {
    global $db;
    $fy_values = $db->Execute("select distinct fiscal_year from " . TABLE_ACCOUNTING_PERIODS . " order by fiscal_year");
    $fy_array = array();
    while (!$fy_values->EOF) {
        $fy_array[] = array('id' => $fy_values->fields['fiscal_year'], 'text' => $fy_values->fields['fiscal_year']);
        $fy_values->MoveNext();
    }
    return $fy_array;
}

function load_coa_types() {
    global $coa_types_list;
    if (!is_array($coa_types_list)) {
        require_once(DIR_FS_MODULES . 'ucbooks/defaults.php');
    }
    $coa_types = array();
    foreach ($coa_types_list as $value) {
        $coa_types[$value['id']] = array(
            'id' => $value['id'],
            'text' => $value['text'],
            'asset' => $value['asset'],
        );
    }
    return $coa_types;
}

function load_coa_info($types = array()) { // includes inactive accounts
    global $db;
    $coa_data = array();
    $sql = "select * from " . TABLE_CHART_OF_ACCOUNTS;
    if (sizeof($types > 0))
        $sql .= " where account_type in (" . implode(", ", $types) . ")";
    $result = $db->Execute($sql);
    while (!$result->EOF) {
        $coa_data[$result->fields['id']] = array(
            'id' => $result->fields['id'],
            'description' => $result->fields['description'],
            'heading_only' => $result->fields['heading_only'],
            'primary_acct_id' => $result->fields['primary_acct_id'],
            'account_type' => $result->fields['account_type'],
        );
        $result->MoveNext();
    }
    return $coa_data;
}

function fill_paid_invoice_array($id, $account_id, $type = 'c') {
    // to build this data array, all current open invoices need to be gathered and then the paid part needs
    // to be applied along with discounts taken by row.
    global $db, $currencies;
    $negate = ((JOURNAL_ID == 20 && $type == 'c') || (JOURNAL_ID == 18 && $type == 'v')) ? true : false;
    // first read all currently open invoices and the payments of interest and put into an array
    $paid_indeces = array();
    if ($id > 0) {
        $result = $db->Execute("select distinct so_po_item_ref_id from " . TABLE_JOURNAL_ITEM . " where ref_id = " . $id);
        while (!$result->EOF) {
            if ($result->fields['so_po_item_ref_id'])
                $paid_indeces[] = $result->fields['so_po_item_ref_id'];
            $result->MoveNext();
        }
    }
    switch ($type) {
        case 'c': $search_journal = '(12,36, 13)';
            break;
        case 'v': $search_journal = '(6, 7)';
            break;
        default: return false;
    }
    $open_invoices = array();
    $sql = "select id, journal_id, post_date, terms, purch_order_id, purchase_invoice_id, total_amount, gl_acct_id 
	  from " . TABLE_JOURNAL_MAIN . " 
	  where (journal_id in " . $search_journal . " and closed = '0' and bill_acct_id = " . $account_id . ")";
    if (sizeof($paid_indeces) > 0)
        $sql .= " or (id in (" . implode(',', $paid_indeces) . ") and closed = '0')";
    $sql .= " order by post_date";
    $result = $db->Execute($sql);
    while (!$result->EOF) {
        if ($result->fields['journal_id'] == 7 || $result->fields['journal_id'] == 13) {
            $result->fields['total_amount'] = -$result->fields['total_amount'];
        }
        $result->fields['total_amount'] -= fetch_partially_paid($result->fields['id']);
        $result->fields['description'] = $result->fields['purch_order_id'];
        $result->fields['discount'] = '';
        $result->fields['amount_paid'] = '';
        $open_invoices[$result->fields['id']] = $result->fields;
        $result->MoveNext();
    }
    // next read the record of interest and add/adjust open invoice array with amounts
    $sql = "select id, ref_id, so_po_item_ref_id, gl_type, description, debit_amount, credit_amount, gl_account 
	  from " . TABLE_JOURNAL_ITEM . " where ref_id = " . $id;
    $result = $db->Execute($sql);
    while (!$result->EOF) {
        $amount = ($result->fields['debit_amount']) ? $result->fields['debit_amount'] : $result->fields['credit_amount'];
        if ($negate)
            $amount = -$amount;
        $index = $result->fields['so_po_item_ref_id'];
        switch ($result->fields['gl_type']) {
            case 'dsc': // it's the discount field
                $open_invoices[$index]['discount'] = $amount;
                $open_invoices[$index]['amount_paid'] -= $amount;
                break;
            case 'chk':
            case 'pmt': // it's the payment field
                $open_invoices[$index]['total_amount'] += $amount;
                $open_invoices[$index]['description'] = $result->fields['description'];
                $open_invoices[$index]['amount_paid'] = $amount;
                break;
            case 'ttl':
                $payment_fields = $result->fields['description']; // payment details
            default:
        }
        $result->MoveNext();
    }
    ksort($open_invoices);

    $balance = 0;
    $index = 0;
    $item_list = array();
    foreach ($open_invoices as $key => $line_item) {
        // fetch some information about the invoice
        $sql = "select id, post_date, terms, purchase_invoice_id, purch_order_id, gl_acct_id, waiting  
		from " . TABLE_JOURNAL_MAIN . " where id = " . $key;
        $result = $db->Execute($sql);
        $due_dates = calculate_terms_due_dates($result->fields['post_date'], $result->fields['terms'], ($type == 'v' ? 'AP' : 'AR'));
        if ($negate) {
            $line_item['total_amount'] = -$line_item['total_amount'];
            $line_item['discount'] = -$line_item['discount'];
            $line_item['amount_paid'] = -$line_item['amount_paid'];
        }
        $balance += $line_item['total_amount'];
        $item_list[] = array(
            'id' => $result->fields['id'],
            'waiting' => $result->fields['waiting'],
            'purchase_invoice_id' => $result->fields['purchase_invoice_id'],
            'purch_order_id' => $result->fields['purch_order_id'],
            'percent' => $due_dates['discount'],
            'post_date' => $result->fields['post_date'],
            'early_date' => gen_locale_date($due_dates['early_date']),
            'net_date' => gen_locale_date($due_dates['net_date']),
            'total_amount' => $currencies->format($line_item['total_amount']),
            'gl_acct_id' => $result->fields['gl_acct_id'],
            'description' => $line_item['description'],
            'discount' => $line_item['discount'] ? $currencies->format($line_item['discount']) : '',
            'amount_paid' => $line_item['amount_paid'] ? $currencies->format($line_item['amount_paid']) : '',
        );
        $index++;
    }
    switch (UCBOOKS_DEFAULT_BILL_SORT) {
        case 'due_date': // sort the open invoice array to order by preference
            foreach ($item_list as $key => $row) {
                $net_date[$key] = $row['net_date'];
                $invoice_id[$key] = $row['purchase_invoice_id'];
            }
            array_multisort($net_date, SORT_ASC, $invoice_id, SORT_ASC, $item_list);
        default: // sort by invoice number
    }
    return array('balance' => $balance, 'payment_fields' => $payment_fields, 'invoices' => $item_list);
}

function fetch_partially_paid($id) {
    global $db;
    $sql = "select sum(i.debit_amount) as debit, sum(i.credit_amount) as credit 
	from " . TABLE_JOURNAL_MAIN . " m inner join " . TABLE_JOURNAL_ITEM . " i on m.id = i.ref_id 
	where i.so_po_item_ref_id = " . $id . " and m.journal_id in (18, 20) and i.gl_type in ('chk', 'pmt') 
	group by m.journal_id";
    $result = $db->Execute($sql);
    if ($result->fields['debit'] || $result->fields['credit']) {
        return $result->fields['debit'] + $result->fields['credit'];
    } else {
        return 0;
    }
}

function calculate_terms_due_dates($post_date, $terms_encoded, $type = 'AR') {
    $terms = explode(':', $terms_encoded);
    $date_details = gen_get_dates($post_date);
    $result = array();
    switch ($terms[0]) {
        default:
        case '0': // Default terms
            $result['discount'] = constant($type . '_PREPAYMENT_DISCOUNT_PERCENT') / 100;
            $result['net_date'] = gen_specific_date($post_date, constant($type . '_NUM_DAYS_DUE'));
            if ($result['discount'] <> 0) {
                $result['early_date'] = gen_specific_date($post_date, constant($type . '_PREPAYMENT_DISCOUNT_DAYS'));
            } else {
                $result['early_date'] = gen_specific_date($post_date, 1000); // move way out
            }
            break;
        case '1': // Cash on Delivery (COD)
        case '2': // Prepaid
            $result['discount'] = 0;
            $result['early_date'] = $post_date;
            $result['net_date'] = $post_date;
            break;
        case '3': // Special terms
            $result['discount'] = $terms[1] / 100;
            $result['early_date'] = gen_specific_date($post_date, $terms[2]);
            $result['net_date'] = gen_specific_date($post_date, $terms[3]);
            break;
        case '4': // Due on day of next month
            $result['discount'] = $terms[1] / 100;
            $result['early_date'] = gen_specific_date($post_date, $terms[2]);
            $result['net_date'] = gen_db_date($terms[3]);
            break;
        case '5': // Due at end of month
            $result['discount'] = $terms[1] / 100;
            $result['early_date'] = gen_specific_date($post_date, $terms[2]);
            $result['net_date'] = date('Y-m-d', mktime(0, 0, 0, $date_details['ThisMonth'], $date_details['TotalDays'], $date_details['ThisYear']));
            break;
    }
    return $result;
}

function load_cash_acct_balance($post_date, $gl_acct_id, $period) {
    global $db, $messageStack;
    $acct_balance = 0;
    if (!$gl_acct_id)
        return $acct_balance;
    $sql = "select beginning_balance from " . TABLE_CHART_OF_ACCOUNTS_HISTORY . " 
	where account_id = '" . $gl_acct_id . "' and period = " . $period;
    $result = $db->Execute($sql);
    $acct_balance = $result->fields['beginning_balance'];

    // load the payments and deposits for the current period
    $bank_list = array();
    $sql = "select i.debit_amount, i.credit_amount
	from " . TABLE_JOURNAL_MAIN . " m inner join " . TABLE_JOURNAL_ITEM . " i on m.id = i.ref_id
	where m.period = " . $period . " and i.gl_account = '" . $gl_acct_id . "' and m.post_date <= '" . $post_date . "' 
	order by m.post_date, m.journal_id";
    $result = $db->Execute($sql);
    while (!$result->EOF) {
        $acct_balance += $result->fields['debit_amount'] - $result->fields['credit_amount'];
        $result->MoveNext();
    }
    return $acct_balance;
}

function gen_build_tax_auth_array() {
    global $db;
    $tax_auth_values = $db->Execute("select tax_auth_id, description_short, account_id , tax_rate
      from " . TABLE_TAX_AUTH . " order by description_short");
    if ($tax_auth_values->RecordCount() < 1) {
        return false;
    } else {
        while (!$tax_auth_values->EOF) {
            $tax_auth_array[$tax_auth_values->fields['tax_auth_id']] = array(
                'description_short' => $tax_auth_values->fields['description_short'],
                'account_id' => $tax_auth_values->fields['account_id'],
                'tax_rate' => $tax_auth_values->fields['tax_rate'],
            );
            $tax_auth_values->MoveNext();
        }
        return $tax_auth_array;
    }
}

function gen_calculate_tax_rate($tax_authorities_chosen, $tax_auth_array) {
    $chosen_auth_array = explode(':', $tax_authorities_chosen);
    $total_tax_rate = 0;
    while ($chosen_auth = array_shift($chosen_auth_array)) {
        $total_tax_rate += $tax_auth_array[$chosen_auth]['tax_rate'];
    }
    return $total_tax_rate;
}

function ord_calculate_tax_drop_down($type = 'c') {
    global $db;
    $tax_auth_array = gen_build_tax_auth_array();
    $sql = "select tax_rate_id, description_short, rate_accounts from " . TABLE_TAX_RATES;
    switch ($type) {
        default:
        case 'c':
        case 'v': $sql .= " where type = '" . $type . "'";
            break;
        case 'b': // both
    }
    $tax_rates = $db->Execute($sql);
    $tax_rate_drop_down = array();
    $tax_rate_drop_down[] = array('id' => '0', 'rate' => '0', 'text' => TEXT_NONE, 'auths' => '');
    while (!$tax_rates->EOF) {
        $tax_rate_drop_down[] = array(
            'id' => $tax_rates->fields['tax_rate_id'],
            'rate' => gen_calculate_tax_rate($tax_rates->fields['rate_accounts'], $tax_auth_array),
            'text' => $tax_rates->fields['description_short'],
            'auths' => $tax_rates->fields['rate_accounts'],
        );
        $tax_rates->MoveNext();
    }
    return $tax_rate_drop_down;
}

function uom_droup_down() { // this funtion for create uom droup down menu
    global $db;
    $sql = "select uom from  uom";
    $uom = $db->Execute($sql);
    $uom_droup_down = array();
    $uom_droup_down[] = array('id' => 'none', 'text' => 'None');
    while (!$uom->EOF) {
        $uom_droup_down[] = array(
            'id' => strtolower($uom->fields['uom']),
            'text' => $uom->fields['uom']
        );
        $uom->MoveNext();
    }
    return $uom_droup_down;
}

function ord_calculate_tax_drop_down_with_type($type = 'c') {
    global $db;
    $tax_auth_array = gen_build_tax_auth_array();
    $sql = "select tax_rate_id, description_short, rate_accounts,type from " . TABLE_TAX_RATES;
    switch ($type) {
        default:
        case 'c':
        case 'v': $sql .= " where type = '" . $type . "'";
            break;
        case 'b': // both
    }
    $tax_rates = $db->Execute($sql);
    $tax_rate_drop_down = array();
    $tax_rate_drop_down[] = array('id' => '0', 'rate' => '0', 'text' => TEXT_NONE, 'auths' => '');
    while (!$tax_rates->EOF) {
        $tax_rate_drop_down[] = array(
            'id' => $tax_rates->fields['tax_rate_id'],
            'rate' => gen_calculate_tax_rate($tax_rates->fields['rate_accounts'], $tax_auth_array),
            'text' => $tax_rates->fields['description_short'],
            'auths' => $tax_rates->fields['rate_accounts'],
            'type' => $tax_rates->fields['type'],
        );
        $tax_rates->MoveNext();
    }
    return $tax_rate_drop_down;
}

function ord_get_so_po_num($id = '') {
    global $db;
    $result = $db->Execute("select purchase_invoice_id from " . TABLE_JOURNAL_MAIN . " where id = " . $id);
    return ($result->RecordCount()) ? $result->fields['purchase_invoice_id'] : '';
}

function ord_get_projects() {
    global $db;
    $result_array = array();
    $result_array[] = array('id' => '', 'text' => TEXT_NONE);
    // fetch cost structure
    $costs = array();
    $result = $db->Execute("select cost_id, description_short from " . TABLE_PROJECTS_COSTS . " where inactive = '0'");
    while (!$result->EOF) {
        $costs[$result->fields['cost_id']] = $result->fields['description_short'];
        $result->MoveNext();
    }
    // fetch phase structure
    $phases = array();
    $result = $db->Execute("select phase_id, description_short, cost_breakdown from " . TABLE_PROJECTS_PHASES . " where inactive = '0'");
    while (!$result->EOF) {
        $phases[$result->fields['phase_id']] = array(
            'text' => $result->fields['description_short'],
            'detail' => $result->fields['cost_breakdown'],
        );
        $result->MoveNext();
    }
    // fetch projects
    $result = $db->Execute("select id, short_name, account_number from " . TABLE_CONTACTS . " where type = 'j' and inactive <> '1'");
    while (!$result->EOF) {
        $base_id = $result->fields['id'];
        $base_text = $result->fields['short_name'];
        if ($result->fields['account_number'] == '1' && sizeof($phases) > 0) { // use phases
            foreach ($phases as $phase_id => $phase) {
                $phase_base = $base_id . ':' . $phase_id;
                $phase_text = $base_text . ' -> ' . $phase['text'];
                if ($phase['detail'] == '1' && sizeof($costs) > 0) {
                    foreach ($costs as $cost_id => $cost) {
                        $result_array[] = array('id' => $phase_base . ':' . $cost_id, 'text' => $phase_text . ' -> ' . $cost);
                    }
                } else {
                    $result_array[] = array('id' => $phase_base, 'text' => $phase_text);
                }
            }
        } else {
            $result_array[] = array('id' => $base_id, 'text' => $base_text);
        }
        $result->MoveNext();
    }
    return $result_array;
}

function gen_auto_update_period($show_message = true) {
    global $db, $messageStack;
    $period = gen_calculate_period(date('Y-m-d'), true);
    if ($period == CURRENT_ACCOUNTING_PERIOD)
        return; // we're in the current period
    if (!$period) { // we're outside of the defined fiscal years
        if ($show_message)
            $messageStack->add(ERROR_MSG_POST_DATE_NOT_IN_FISCAL_YEAR, 'error');
    } else { // update CURRENT_ACCOUNTING_PERIOD constant with this new period
        $result = $db->Execute("select start_date, end_date from " . TABLE_ACCOUNTING_PERIODS . " where period = " . $period);
        write_configure('CURRENT_ACCOUNTING_PERIOD', $period);
        write_configure('CURRENT_ACCOUNTING_PERIOD_START', $result->fields['start_date']);
        write_configure('CURRENT_ACCOUNTING_PERIOD_END', $result->fields['end_date']);
        gen_add_audit_log(GEN_LOG_PERIOD_CHANGE);
        if ($show_message) {
            $messageStack->add(sprintf(ERROR_MSG_ACCT_PERIOD_CHANGE, $period), 'success');
        }
    }
}

function build_search_sql($fields, $id, $id_from = '', $id_to = '') {
    $crit = array();
    foreach ($fields as $field) {
        $output = '';
        switch ($id) {
            default:
            case 'all': break;
            case 'eq': if ($id_from)
                    $output .= $field . " = '" . $id_from . "'"; break;
            case 'neq': if ($id_from)
                    $output .= $field . " <> '" . $id_from . "'"; break;
            case 'like': if ($id_from)
                    $output .= $field . " like '%" . $id_from . "%'"; break;
            case 'rng':
                if ($id_from)
                    $output .= $field . " >= '" . $id_from . "'";
                if ($output && $id_to)
                    $output .= " and ";
                if ($id_to)
                    $output .= $field . " <= '" . $id_to . "'";
        }
        if ($output)
            $crit[] = $output;
    }
    return ($crit) ? ('(' . implode(' or ', $crit) . ')') : '';
}

function repost_journals($journals, $start_date, $end_date) {
    global $db, $messageStack;
    if (sizeof($journals) > 0) {
        $sql = "select id from " . TABLE_JOURNAL_MAIN . " 
		where journal_id in (" . implode(',', $journals) . ") 
		and post_date >= '" . $start_date . "' and post_date < '" . gen_specific_date($end_date, 1) . "' 
		order by id";
        $result = $db->Execute($sql);
        $cnt = 0;
        $db->transStart();
        while (!$result->EOF) {
            $gl_entry = new journal($result->fields['id']);
            $gl_entry->remove_cogs_rows(); // they will be regenerated during the re-post
            if (!$gl_entry->Post('edit', true)) {
                $db->transRollback();
                $messageStack->add('<br /><br />Failed Re-posting the journals, try a smaller range. The record that failed was # ' . $gl_entry->id, 'error');
                return false;
            }
            $cnt++;
            $result->MoveNext();
        }
        $db->transCommit();
        return $cnt;
    }
}

function calculate_aging($id, $type = 'c', $special_terms = '0') {
    global $db;
    $output = array();
    if (!$id)
        return $output;
    $today = date('Y-m-d');
    $terms = explode(':', $special_terms);
    $credit_limit = $terms[4] ? $terms[4] : constant(($type == 'v' ? 'AP' : 'AR') . '_CREDIT_LIMIT_AMOUNT');
    $due_days = $terms[3] ? $terms[3] : constant(($type == 'v' ? 'AP' : 'AR') . '_NUM_DAYS_DUE');
    $due_date = gen_specific_date($today, -$due_days);
    $late_30 = gen_specific_date($today, ($type == 'v') ? -AP_AGING_DATE_1 : -AR_AGING_PERIOD_1);
    $late_60 = gen_specific_date($today, ($type == 'v') ? -AP_AGING_DATE_2 : -AR_AGING_PERIOD_2);
    $late_90 = gen_specific_date($today, ($type == 'v') ? -AP_AGING_DATE_3 : -AR_AGING_PERIOD_3);
    $output = array(
        'balance_0' => '0',
        'balance_30' => '0',
        'balance_60' => '0',
        'balance_90' => '0',
    );
    $inv_jid = ($type == 'v') ? '6, 7' : '12,36, 13';
    $pmt_jid = ($type == 'v') ? '20' : '18';
    $total_outstanding = 0;
    $past_due = 0;
    $sql = "select id from " . TABLE_JOURNAL_MAIN . " 
		where bill_acct_id = " . $id . " and journal_id in (" . $inv_jid . ") and closed = '0'";
    $open_inv = $db->Execute($sql);
    while (!$open_inv->EOF) {
        $sql = "select m.post_date, sum(i.debit_amount) as debits, sum(i.credit_amount) as credits 
	    from " . TABLE_JOURNAL_MAIN . " m inner join " . TABLE_JOURNAL_ITEM . " i on m.id = i.ref_id
	    where m.id = " . $open_inv->fields['id'] . " and journal_id in (" . $inv_jid . ") and i.gl_type <> 'ttl' group by m.id";
        $result = $db->Execute($sql);
        $total_billed = $result->fields['credits'] - $result->fields['debits'];
        $post_date = $result->fields['post_date'];
        $sql = "select sum(i.debit_amount) as debits, sum(i.credit_amount) as credits 
	    from " . TABLE_JOURNAL_MAIN . " m inner join " . TABLE_JOURNAL_ITEM . " i on m.id = i.ref_id
	    where i.so_po_item_ref_id = " . $open_inv->fields['id'] . " and m.journal_id = " . $pmt_jid . " and i.gl_type = 'pmt'";
        $result = $db->Execute($sql);
        $total_paid = $result->fields['credits'] - $result->fields['debits'];
        $balance = $total_billed - $total_paid;
        if ($type == 'v')
            $balance = -$balance;
        // start the placement in aging array
        if ($post_date < $due_date)
            $past_due += $balance;
        if ($post_date < $late_90) {
            $output['balance_90'] += $balance;
            $total_outstanding += $balance;
        } elseif ($post_date < $late_60) {
            $output['balance_60'] += $balance;
            $total_outstanding += $balance;
        } elseif ($post_date < $late_30) {
            $output['balance_30'] += $balance;
            $total_outstanding += $balance;
        } elseif ($post_date <= $today) {
            $output['balance_0'] += $balance;
            $total_outstanding += $balance;
        } // else it's in the future
        $open_inv->MoveNext();
    }
    $output['total'] = $total_outstanding;
    $output['past_due'] = $past_due;
    $output['credit_limit'] = $credit_limit;
    $output['terms_lang'] = gen_terms_to_language($special_terms, false, ($type == 'v' ? 'AP' : 'AR'));
    return $output;
}

function insertdata($oID, $order) {
    global $db,$currencies,$order;
    $jID = 12;
    $result = $db->Execute("select * from " . TABLE_JOURNAL_MAIN . " 
	    where journal_id = '32' and id=" . $oID . "");
    while (!$result->EOF) {
// currency values (convert to DEFAULT_CURRENCY to store in db)
        $order->currencies_code = db_prepare_input($result->fields['currencies_code']);
        $order->currencies_value = db_prepare_input($result->fields['currencies_value']);
// load bill to and ship to information
        $order->short_name = db_prepare_input(($_POST['search'] <> TEXT_SEARCH) ? $_POST['search'] : '');
        $order->bill_add_update = isset($_POST['bill_add_update']) ? $_POST['bill_add_update'] : 0;
        $order->account_type = 'c';
        $order->bill_acct_id = db_prepare_input($result->fields['bill_acct_id']);
        $order->bill_address_id = db_prepare_input($result->fields['bill_address_id']);
        $order->bill_primary_name = db_prepare_input(($result->fields['bill_primary_name'] <> GEN_PRIMARY_NAME) ? $result->fields['bill_primary_name'] : '', true);
        $order->bill_contact = db_prepare_input(($result->fields['bill_contact'] <> GEN_CONTACT) ? $result->fields['bill_contact'] : '', ADDRESS_BOOK_CONTACT_REQUIRED);
        $order->bill_address1 = db_prepare_input(($result->fields['bill_address1'] <> GEN_ADDRESS1) ? $result->fields['bill_address1'] : '', ADDRESS_BOOK_ADDRESS1_REQUIRED);
        $order->bill_address2 = db_prepare_input(($result->fields['bill_address2'] <> GEN_ADDRESS2) ? $result->fields['bill_address2'] : '', ADDRESS_BOOK_ADDRESS2_REQUIRED);
        $order->bill_city_town = db_prepare_input(($result->fields['bill_city_town'] <> GEN_CITY_TOWN) ? $result->fields['bill_city_town'] : '', ADDRESS_BOOK_CITY_TOWN_REQUIRED);
        $order->bill_state_province = db_prepare_input(($result->fields['bill_state_province'] <> GEN_STATE_PROVINCE) ? $result->fields['bill_state_province'] : '', ADDRESS_BOOK_STATE_PROVINCE_REQUIRED);
        $order->bill_postal_code = db_prepare_input(($result->fields['bill_postal_code'] <> GEN_POSTAL_CODE) ? $result->fields['bill_postal_code'] : '', ADDRESS_BOOK_POSTAL_CODE_REQUIRED);
        $order->bill_country_code = db_prepare_input($result->fields['bill_country_code']);
        $order->bill_telephone1 = db_prepare_input(($result->fields['bill_telephone1'] <> GEN_TELEPHONE1) ? $result->fields['bill_telephone1'] : '', ADDRESS_BOOK_TELEPHONE1_REQUIRED);
        $order->bill_email = db_prepare_input(($result->fields['bill_email'] <> GEN_EMAIL) ? $result->fields['bill_email'] : '', ADDRESS_BOOK_EMAIL_REQUIRED);
        $order->message = db_prepare_input($result->fields['message']);
        $order->product_desc = db_prepare_input($result->fields['product_desc']);
        $order->note = db_prepare_input($result->fields['note']);
        if (defined('MODULE_SHIPPING_STATUS')) {
            $order->ship_short_name = db_prepare_input($_POST['ship_search']);
            $order->ship_add_update = isset($result->fields['ship_add_update']) ? $_POST['ship_add_update'] : 0;
            $order->ship_acct_id = db_prepare_input($result->fields['ship_acct_id']);
            $order->ship_address_id = db_prepare_input($result->fields['ship_address_id']);
            $order->ship_primary_name = db_prepare_input(($result->fields['ship_primary_name'] <> GEN_PRIMARY_NAME) ? $result->fields['ship_primary_name'] : '', true);
            $order->ship_contact = db_prepare_input(($result->fields['ship_contact'] <> GEN_CONTACT) ? $result->fields['ship_contact'] : '', ADDRESS_BOOK_SHIP_CONTACT_REQ);
            $order->ship_address1 = db_prepare_input(($result->fields['ship_address1'] <> GEN_ADDRESS1) ? $result->fields['ship_address1'] : '', ADDRESS_BOOK_SHIP_ADD1_REQ);
            $order->ship_address2 = db_prepare_input(($result->fields['ship_address2'] <> GEN_ADDRESS2) ? $result->fields['ship_address2'] : '', ADDRESS_BOOK_SHIP_ADD2_REQ);
            $order->ship_city_town = db_prepare_input(($result->fields['ship_city_town'] <> GEN_CITY_TOWN) ? $result->fields['ship_city_town'] : '', ADDRESS_BOOK_SHIP_CITY_REQ);
            $order->ship_state_province = db_prepare_input(($result->fields['ship_state_province'] <> GEN_STATE_PROVINCE) ? $result->fields['ship_state_province'] : '', ADDRESS_BOOK_SHIP_STATE_REQ);
            $order->ship_postal_code = db_prepare_input(($result->fields['ship_postal_code'] <> GEN_POSTAL_CODE) ? $result->fields['ship_postal_code'] : '', ADDRESS_BOOK_SHIP_POSTAL_CODE_REQ);
            $order->ship_country_code = db_prepare_input($result->fields['ship_country_code']);
            $order->ship_telephone1 = db_prepare_input(($result->fields['ship_telephone1'] <> GEN_TELEPHONE1) ? $result->fields['ship_telephone1'] : '', ADDRESS_BOOK_TELEPHONE1_REQUIRED);
            $order->ship_email = db_prepare_input(($result->fields['ship_email'] <> GEN_EMAIL) ? $result->fields['ship_email'] : '', ADDRESS_BOOK_EMAIL_REQUIRED);
            $order->shipper_code = implode(':', array(db_prepare_input($result->fields['ship_carrier']), db_prepare_input($result->fields['ship_service'])));
            $order->drop_ship = isset($result->fields['drop_ship']) ? $result->fields['drop_ship'] : 0;
            $order->freight = $currencies->clean_value(db_prepare_input($result->fields['freight']), $order->currencies_code) / $order->currencies_value;
        }
// load journal main data
        $order->id = ''; // will be null unless opening an existing purchase/receive
        $order->journal_id = 12;
        $order->post_date = date('Y-m-d');
        $order->period = gen_calculate_period($order->post_date);
        if (!$order->period)
            break; // bad post_date was submitted
        if ($_SESSION['admin_prefs']['restrict_period'] && $order->period <> CURRENT_ACCOUNTING_PERIOD) {
            $error = $messageStack->add(ORD_ERROR_NOT_CUR_PERIOD, 'error');
            break;
        }
        $order->so_po_ref_id = db_prepare_input($result->fields['so_po_ref_id']); // Internal link to reference po/so record
        $order->purchase_invoice_id = '';//db_prepare_input($result->fields['purchase_invoice_id']); // UcBooks order/invoice ID



        $order->purch_order_id = db_prepare_input($result->fields['purch_order_id']);  // customer PO/Ref number
        $order->store_id = db_prepare_input($result->fields['store_id']);
        if ($order->store_id == '')
            $order->store_id = 0;
        if (isset($result->fields['discount_check']) && $result->fields['discount_check'] == 1) {
            if ($jID == 13) {
                $order->description = 'Customer Credit Memos Entry Discount';
            } else if ($jID == 7) {
                $order->description = 'Vendor Credit Memos Entry Discount';
            } else if ($jID == 30) {
                $order->description = 'Customer Debit Memos Entry Discount';
            } else if ($jID == 31) {
                $order->description = 'Vendor Debit Memos Entry Discount';
            }
        } else {
            $order->description = sprintf(TEXT_JID_ENTRY, constant('ORD_TEXT_12_WINDOW_TITLE'));
        }

        $order->recur_id = db_prepare_input($result->fields['recur_id']);
        $order->recur_frequency = db_prepare_input($result->fields['recur_frequency']);
//	$order->sales_tax_auths     = db_prepare_input($_POST['sales_tax_auths']);
        $order->admin_id = $_SESSION['admin_id'];
        $order->rep_id = db_prepare_input($result->fields['rep_id']);
        $order->gl_acct_id = db_prepare_input($result->fields['gl_acct_id']);
        $order->terms = db_prepare_input($result->fields['terms']);
        $order->waiting = ($jID == 6 || $jID == 7) ? (isset($result->fields['waiting']) ? 1 : 0) : ($result->fields['waiting'] ? 1 : 0);
        $order->closed = ($result->fields['closed'] == "1") ? 1 : 0;
        $order->terminal_date = gen_db_date($result->fields['terminal_date']);
        $order->item_count = db_prepare_input($result->fields['item_count']);
        $order->weight = db_prepare_input($result->fields['weight']);
        $order->printed = db_prepare_input($result->fields['printed']);
        $order->subtotal = $currencies->clean_value(db_prepare_input($result->fields['subtotal']), $order->currencies_code) / $order->currencies_value; // don't need unless for verification
        $order->disc_gl_acct_id = db_prepare_input($result->fields['disc_gl_acct_id']);
        $order->discount = $currencies->clean_value(db_prepare_input($result->fields['discount']), $order->currencies_code) / $order->currencies_value;
        $order->disc_percent = ($order->subtotal) ? (1 - (($order->subtotal - $order->discount) / $order->subtotal)) : 0;
        $order->ship_gl_acct_id = db_prepare_input($result->fields['ship_gl_acct_id']);
        $order->rm_attach = isset($result->fields['rm_attach']) ? true : false;
        $order->sales_tax = $currencies->clean_value(db_prepare_input($result->fields['sales_tax']), $order->currencies_code) / $order->currencies_value;
        $order->total_amount = $currencies->clean_value(db_prepare_input($result->fields['total']), $order->currencies_code) / $order->currencies_value;
        $order->message = db_prepare_input($result->fields['message']);
        $order->product_desc = db_prepare_input($result->fields['product_desc']);
        $order->note = db_prepare_input($result->fields['note']);
        if (($jID == 7 || $jID == 13 || $jID == 30 || $jID == 31 ) && (isset($result->fields['is_discount']) && $result->fields['is_discount'] == 1)) {//we need to make item qty 0 for credait memo discount we assign distinct value
            $order->is_discount = 1;
        }
        $result->MoveNext();
    }

    $items = $db->Execute("select * from " . TABLE_JOURNAL_ITEM . " 
	    where ref_id=" . $oID . "");
    $x = 1;
    while (!$items->EOF) { // while there are item rows to read in
        $order->item_rows[] = array(
            'so_po_item_ref_id' => db_prepare_input($items->fields['so_po_item_ref_id']),
            'item_cnt' => db_prepare_input($items->fields['item_cnt']),
            'gl_type' => ($items->fields['gl_type']=='doo') ? 'sos':$items->fields['gl_type'],
            'qty' => $currencies->clean_value(db_prepare_input($items->fields['qty']), $order->currencies_code),
            'uom' => db_prepare_input($items->fields['uom']),
            'uom_qty' => $currencies->clean_value(db_prepare_input($items->fields['uom_qty']), $order->currencies_code),
            'pstd' => $currencies->clean_value(db_prepare_input($items->fields['qty']), $order->currencies_code),
            'sku' => ($items->fields['sku'] == TEXT_SEARCH) ? '' : db_prepare_input($items->fields['sku']),
//                    //'desc' => db_prepare_input($_POST['desc_' . $x]),
            'desc' => db_prepare_input($items->fields['description']),
            'proj' => db_prepare_input($items->fields['project_id']),
            'date_1' => db_prepare_input(date('Y-m-d')),
            'price' => $currencies->clean_value(db_prepare_input($items->fields['full_price']), $order->currencies_code) / $order->currencies_value,
            'full' => $currencies->clean_value(db_prepare_input($items->fields['full_price']), $order->currencies_code) / $order->currencies_value,
            'acct' => db_prepare_input($items->fields['gl_account']),
            'tax' => db_prepare_input($items->fields['taxable']),
            'total' => $currencies->clean_value(db_prepare_input($items->fields['full_price']), $order->currencies_code) / $order->currencies_value,
//                    'weight' => db_prepare_input($_POST['weight_' . $x]),
            'serial' => db_prepare_input($items->fields['serialize_number']),
//                    'stock' => db_prepare_input($_POST['stock_' . $x]),
//                    'inactive' => db_prepare_input($_POST['inactive_' . $x]),
//                    'lead_time' => db_prepare_input($_POST['lead_' . $x]),
        );
        $items->MoveNext();
    }

    if ($post_success = $order->post_ordr('save')) {
        return true;
    }else{
        return false;
    }
}

?>