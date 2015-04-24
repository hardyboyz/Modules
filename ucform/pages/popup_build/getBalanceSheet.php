<center><h1>Group wise Balance sheet</h1></center>
<!--<br>
<br>
<br>
<table width="100%" align="center">
    <tr style="background-color:#CCCCCC">
        <th>Beginning Balance</th>
        <th><?php echo 'RM ' . number_format(round($final_balance->fields['beginning_balance'], 2), 2); ?></th>
    </tr>
</table>-->
<br>
<br>
<br>
<table width="100%" align="center">
    <tr style="background-color:#CCCCCC">
        <th>Record</th>
        <th>Period</th>
        <th>Post Date</th>
        <th>Account</th>
        <th>Description</th>
        <th>Transaction</th>
        <th>Debit Amount</th>
        <th>Credit Amount</th>
        <th>Balance</th>
    </tr>
    <?php
    $i = 0;
    $total_debit = 0;
    $total_credit = 0;
    $period = 0;
    $str = '';
    $balance = 0;
    $j = 0;
    while (!$balance_sheet->EOF) {
        $output = '';

        $journal_id = $balance_sheet->fields['c7'];
        $sub_journal_id = $balance_sheet->fields['c9'];
        if ($journal_id == 18) {
            $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=bills&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&type=c&action=edit" target="_blank">' . chr(10);
        } else if ($journal_id == 20) {
            $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=bills&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&type=v&action=edit" target="_blank">' . chr(10);
        } else if ($journal_id == 2) {
            switch ($sub_journal_id) {
                case 1 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=journal&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                case 2 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=spend_receive_money&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                case 3 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=receive_spend_money&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                default :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=journal&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jid=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
            }
        } else if ($journal_id == 33) {

            $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=journal&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
        } else if ($journal_id == 34) {
            $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=spend_receive_money&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
        } else if ($journal_id == 35) {
            $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=bank_transfer&page=receive_spend_money&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
        } else if ($journal_id == 16) {
            switch ($sub_journal_id) {
                case 0 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=inventory&page=adjustments&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                case 1 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=inventory&page=transfer&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                default :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=inventory&page=adjustments&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
            }
        } else if ($journal_id == 6) {
            switch ($sub_journal_id) {
                case 0 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                case 1 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                default :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
            }
        }else if ($journal_id == 13) {
            switch ($sub_journal_id) {
                case 0 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                case 1 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                default :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
            }
        } else if ($journal_id == 19) {
            switch ($sub_journal_id) {
                case 0 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                case 1 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                default :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
            }
        }else if ($journal_id == 12) {
            switch ($sub_journal_id) {
                case 0 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                case 1 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                case 2 :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
                default :
                    $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&sub_jID=' . $sub_journal_id . '&action=edit" target="_blank">' . chr(10);
                    break;
            }
        } else {

            $output .= '<a href="' . DIR_WS_ADMIN . 'index.php?module=ucbooks&page=orders&oID=' . $balance_sheet->fields['c0'] . '&jID=' . $journal_id . '&action=edit" target="_blank">' . chr(10);
        }
        $output .= ($balance_sheet->fields['c8'] == '') ? '&nbsp;' : $balance_sheet->fields['c8'];
        $output .= '</a>' . chr(10);
        $account_name = $balance_sheet->fields['c3'];
        if (!empty($str)) {
            if ($period != $balance_sheet->fields['c1']) {
                echo $str;
            }
        }
        foreach ($group_total_array as $key => $group) {
            if ($balance_sheet->fields['c1'] == $group['period']) { // for Group total row
                $net_balance = ($group['beginning_balance'] + $group['debit_amount']) - $group['credit_amount'];
                $str = "<tr style='background-color:#CCCCCC;'><th colspan='2'>Group Total for period : " . $group['period'] . "</th><th colspan='2'>" . $account_name . "</th><th colspan='2'>(Net Balance : " . 'RM ' . number_format(round($net_balance, 2), 2) . ")</th><th colspan='1'> " . 'RM ' . number_format(round($group['debit_amount'], 2), 2) . "</th><th colspan='1'>" . 'RM ' . number_format(round($group['credit_amount'], 2), 2) . "</th><th colspan='1'>" . 'RM ' . number_format(round($net_balance, 2), 2) . "</th></tr>";
                $period = $group['period'];
                unset($group_total_array[$key]);

                $balance = 0; // For initializ Balance again
                $j = 0; // For initialize first row after a group total for synchronous
                break;
            }
        }
        if ($j == 0) { // For first row of a group total
            $balance += $balance_sheet->fields['balance'] + ($balance_sheet->fields['c5'] - $balance_sheet->fields['c6']);
            $j++;
        } else {
            $balance += $balance_sheet->fields['c5'] - $balance_sheet->fields['c6'];
        }

        if ($i == 0) {
            echo '<tr>';
            ?>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c0']; ?></td>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c1']; ?></td>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c2']; ?></td>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c3']; ?></td>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo 'Begining Balance'; ?></td>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo ''; ?></td>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo 'RM 0'; ?></td>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo 'RM 0'; ?></td>
            <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo 'RM ' . number_format(round($balance_sheet->fields['balance'], 2), 2); ?></td>
        </tr>
    <?php
    }

    if ($i % 2 == 0) {

        echo '<tr style="background-color:#e0ebff;">';
        ?>
        <td style="padding: 2px 5px 0px 10px; height: 18px; font-family: helvetica; font-size: 10pt; border-right: 2px solid rgb(255, 255, 255);"><?php echo $balance_sheet->fields['c0']; ?></td>
        <td style="padding: 2px 5px 0px 10px; height: 18px; font-family: helvetica; font-size: 10pt; border-right: 2px solid rgb(255, 255, 255);"><?php echo $balance_sheet->fields['c1']; ?></td>
        <td style="padding: 2px 5px 0px 10px; height: 18px; font-family: helvetica; font-size: 10pt; border-right: 2px solid rgb(255, 255, 255);"><?php echo $balance_sheet->fields['c2']; ?></td>
        <td style="padding: 2px 5px 0px 10px; height: 18px; font-family: helvetica; font-size: 10pt; border-right: 2px solid rgb(255, 255, 255);"><?php echo $balance_sheet->fields['c3']; ?></td>
        <td style="padding: 2px 5px 0px 10px; height: 18px; font-family: helvetica; font-size: 10pt; border-right: 2px solid rgb(255, 255, 255);"><?php echo $balance_sheet->fields['c4']; ?></td>
        <td style="padding: 2px 5px 0px 10px; height: 18px; font-family: helvetica; font-size: 10pt; border-right: 2px solid rgb(255, 255, 255);"><?php echo $output; ?></td>
        <td style="padding: 2px 5px 0px 10px; height: 18px; font-family: helvetica; font-size: 10pt; border-right: 2px solid rgb(255, 255, 255);"><?php echo 'RM ' . number_format(round($balance_sheet->fields['c5'], 2), 2); ?></td>
        <td style="padding: 2px 5px 0px 10px; height: 18px; font-family: helvetica; font-size: 10pt; border-right: 2px solid rgb(255, 255, 255);"><?php echo 'RM ' . number_format(round($balance_sheet->fields['c6'], 2), 2); ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo 'RM ' . number_format(round($balance, 2), 2); ?></td>

        <?php
    } else {
        echo '<tr>';
        ?>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c0']; ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c1']; ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c2']; ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c3']; ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $balance_sheet->fields['c4']; ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo $output; ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo 'RM ' . number_format(round($balance_sheet->fields['c5'], 2), 2); ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo 'RM ' . number_format(round($balance_sheet->fields['c6'], 2), 2); ?></td>
        <td style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;"><?php echo 'RM ' . number_format(round($balance, 2), 2); ?></td>

        <?php
    }
    ?>


    </tr>

    <?php
    $i++;
    //$account_name = $balance_sheet->fields['c3'];
    $total_debit += $balance_sheet->fields['c5'];
    $total_credit += $balance_sheet->fields['c6'];
    //$balance = $balance_sheet->fields['balance'];
    $balance_sheet->MoveNext();
}
echo $str;
while (!$final_balance->EOF) {
    $total = $final_balance->fields['beginning_balance'] + $final_balance->fields['debit_amount'] - $final_balance->fields['credit_amount'];
    $final_balance->MoveNext();
}
if (empty($account_name)) {
    ?>

    <tr>
        <td align="center" style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;" colspan="8">There have no records</td>

    </tr>
    <tr style="background-color:#CCCCCC">
        <td align="center" style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;" colspan="7">Net Balance :RM  <?php echo number_format(round(abs($total), 2), 2); ?></td>
        <td align="right" style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;">RM <?php echo number_format(round($total_debit, 2), 2); ?> </td>
        <td align="right" style=" border-right: 2px solid #FFFFFF; padding:2px 5px 0 10px; height:18px; font-family:helvetica; color:000; font-size:10pt;">RM <?php echo number_format(round($total_credit, 2), 2); ?> </td>
    </tr>
<?php } ?>
</table>