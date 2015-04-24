<!--<script type="text/javascript">
    $("#show_report").load("<?php echo HTTP_SERVER ?>/index.php?module=ucform&page=popup_gen&rID="+rID+"&todo=open",function (){                
               
            });
</script>-->

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
//  Path: /modules/ucounting/pages/main/template_main.php
//
// display alerts/error messages, if any since the toolbar is not shown
//error_reporting(E_ALL );
if ($messageStack->size > 0)
    echo $messageStack->output();
$column = 1;
?>
<?php if ($_SESSION['set_security'] == 'yes') { ?>
    <div class="button_area">
        <a class="button_bg" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=ctl_panel&amp;mID=' . $menu_id, 'SSL'); ?>">+ <?php echo CP_CHANGE_PROFILE; ?></a>
    </div>
    <style type="text/css">
        .delete_icon{display: block !important;}
    </style>
<?php } ?>

<script language="javascript"> DetectFlashVer = 0; </script>
<script src="charts/AC_RunActiveContent.js" language="javascript"></script>
<script language="JavaScript" type="text/javascript">

    var requiredMajorVersion = 10;
    var requiredMinorVersion = 0;
    var requiredRevision = 45;

</script>
<?php
//echo $menu_select;
if ($menu_select == "home") {
    ?>

    <?php
//include charts.php to access the InsertChart function
    //include (DIR_FS_ADMIN . "charts_back/charts.php");
    ?>
    <!--    <div style="width: 1070px; border: 1px solid #cccccc; padding: 5px 0 5px 5px;">
            <div style="float: left">
                <h3>Sales </h3>
    <?php
    //echo InsertChart(DIR_WS_ADMIN . "charts_back/charts.swf", DIR_WS_ADMIN . "charts_back/charts_library", DIR_WS_ADMIN . "charts_back/invoice.php", 530, 300, "e6e6fa");
    ?>     
            </div>
            <div style="float: left; margin-left: 2px;">
                <h3>Purchase </h3>
    <?php
    //echo InsertChart(DIR_WS_ADMIN . "charts_back/charts.swf", DIR_WS_ADMIN . "charts_back/charts_library", DIR_WS_ADMIN . "charts_back/purchase.php", 530, 300, "e6e6fa");
    ?>
            </div>
            <div style="clear: both"></div>


        </div>   -->
    <div style="width: 1070px; border: 1px solid #cccccc; padding: 5px 0 5px 5px;">
        <div style="float: left">
            <h3>Sales </h3>

            <script type="text/javascript">
                var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
                if(hasRightVersion){
                    document.write('<iframe src="/charts/invoice.html" width="530" height="400"></iframe>'); 
                }else{
                    document.write('<div id="invoice" style="margin-top:7px; width:520px; height: 382px;"></div>');
                }
            </script>


        </div>
        <div style="float: left; margin-left: 2px;">
            <h3>Purchase </h3>

            <script type="text/javascript">
                if(hasRightVersion){
                    document.write('<iframe src="/charts/purchase.html" width="530" height="400"></iframe>'); 
                }else{
                    document.write('<div id="purchase" style="margin-top:7px; width:520px; height: 382px;"></div>');
                }
            </script>

        </div>
        <div style="clear: both"></div>


    </div>   


    <!--    <div style="margin: 0 auto; width: 998px;">   
            <iframe src="http://demo.ucounting.com/charts/sample.html" width="998" height="480" style="overflow: hidden;" scrolling="no">

            </iframe> 

        </div>-->
<?php } else if ($menu_select == "customers") { ?>
    <div style="width: 1070px; border: 1px solid #cccccc; padding: 5px 0 5px 5px;">
        <div style="float: left">
            <h3>Top 5 items Sold in terms of Total Value</h3>
            <script type="text/javascript">
                var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
                //hasRightVersion = false;
                if(hasRightVersion){
                    document.write('<iframe src="/charts/sales_by_sku_total.html" width="530" height="400"></iframe>'); 
                }else{
                    document.write('<div id="sku_total" style="margin-top:7px; width:520px; height: 382px;"></div>');
                }
            </script>
    <!--<iframe src="/charts/sales_by_sku_total.html" width="530" height="400"></iframe>-->

        </div>
        <div style="float: left; margin-left: 2px;">
            <h3>Top 5 Items Sold in terms of Total Quantity</h3>
            <script type="text/javascript">
                var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
                //hasRightVersion = false;
                if(hasRightVersion){
                    document.write('<iframe src="/charts/sales_by_sku_qty.html" width="530" height="400"></iframe>'); 
                }else{
                    document.write('<div id="sku_qty" style="margin-top:7px; width:520px; height: 382px;"></div>');
                }
            </script>
    <!--<iframe src="/charts/sales_by_sku_qty.html" width="530" height="400"></iframe>-->
        </div>
        <div style="clear: both"></div>
    </div>   

<?php } else if ($menu_select == "vendors") { ?>
    <div style="width: 1070px; border: 1px solid #cccccc; padding: 5px 0 5px 5px;">
        <div style="float: left">
            <h3>Top 5 items purchased in terms of Total Value</h3>
            <script type="text/javascript">
                var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
                //hasRightVersion = false;
                if(hasRightVersion){
                    document.write('<iframe src="/charts/purchase_by_sku_total.html" width="530" height="400"></iframe>'); 
                }else{
                    document.write('<div id="sku_total_purchase" style="margin-top:7px; width:520px; height: 382px;"></div>');
                }
            </script>
    <!--<iframe src="/charts/sales_by_sku_total.html" width="530" height="400"></iframe>-->

        </div>
        <div style="float: left; margin-left: 2px;">
            <h3>Top 5 items purchased in terms of Total Quantity</h3>
            <script type="text/javascript">
                var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
                //hasRightVersion = false;
                if(hasRightVersion){
                    document.write('<iframe src="/charts/purchase_by_sku_qty.html" width="530" height="400"></iframe>'); 
                }else{
                    document.write('<div id="sku_qty_purchase" style="margin-top:7px; width:520px; height: 382px;"></div>');
                }
            </script>
    <!--<iframe src="/charts/sales_by_sku_qty.html" width="530" height="400"></iframe>-->
        </div>
        <div style="clear: both"></div>
    </div>   


<?php } ?>
<br/>
<br/>
<br/>

<table style="width:100%;margin-left:auto;margin-right:auto;">
    <tr>
    </tr>
    <tr>
        <td width="33%" valign="top">
            <div id="col_<?php echo $column; ?>" style="position:relative;">
                <?php
                while (!$cp_boxes->EOF) {
                    if ($cp_boxes->fields['column_id'] <> $column) {
                        while ($cp_boxes->fields['column_id'] <> $column) {
                            $column++;
                            echo '      </div>' . chr(10);
                            echo '    </td>' . chr(10);
                            echo '    <td width="33%" valign="top">' . chr(10);
                            echo '      <div id="col_' . $column . '" style="position:relative;">' . chr(10);
                        }
                    }
                    $dashboard_id = $cp_boxes->fields['dashboard_id'];
                    $module_id = $cp_boxes->fields['module_id'];
                    $column_id = $cp_boxes->fields['column_id'];
                    $row_id = $cp_boxes->fields['row_id'];
                    $params = unserialize($cp_boxes->fields['params']);
                    if (file_exists(DIR_FS_MODULES . $module_id . '/dashboards/' . $dashboard_id . '/' . $dashboard_id . '.php')) {
                        load_method_language(DIR_FS_MODULES . $module_id . '/dashboards/', $dashboard_id);
                        require_once (DIR_FS_MODULES . $module_id . '/dashboards/' . $dashboard_id . '/' . $dashboard_id . '.php');
                        $new_box = new $dashboard_id;
                        $new_box->menu_id = $menu_id;
                        $new_box->module_id = $module_id;
                        $new_box->dashboard_id = $dashboard_id;
                        $new_box->column_id = $cp_boxes->fields['column_id'];
                        $new_box->row_id = $cp_boxes->fields['row_id'];
                        echo $new_box->Output($params);
                    }
                    $cp_boxes->MoveNext();
                }

                while (MAX_CP_COLUMNS <> $column) { // fill remaining columns with blank space
                    $column++;
                    echo '      </div>' . chr(10);
                    echo '    </td>' . chr(10);
                    echo '    <td width="33%" valign="top">' . chr(10);
                    echo '      <div id="col_' . $column . '" style="position:relative;">' . chr(10);
                }
                ?>
            </div>
        </td>
    </tr>

</table>

<br>
<br>

<?php if ($menu_select == "customers") { ?>	
    <div style="display:none" class="mxgraph" style="position:relative;overflow:hidden;width:900px;height:317px;display:inline-block;_display:inline;"><div style="width:1px;height:1px;overflow:hidden;">7Zldb5swFIZ/TaTtohVgYuCyYe22i6pdI+3j0osdYhVwZEw++utnYjuJIaloSkdUlYsqfv2Bc97z+FAyAHG2+srRfHbLMEkHnoNXA/Bl4Hmh48i/lbBWghvBSCkJp1hrWigpJoUlCcZSQee2OGF5TibC0qYstRebo4Q0hPEEpU31F8ViprfrwZ3+jdBkZm7jmm0XYm3WwGSKylRcbCRPda8c1ReFqr3W7Qvom5VzawdPjGVK0JHipKBPxJKmVFjtv4xjwi0ppfnjfpjA9QDEnDGhPmWrmKSVMSbmatrNkd5tdDjJRZsJOjgLlJZ663FZCJYRXjTCxlmZY1JNdAdgtJxRQcZzNKl6lzKHpJZwhKm8c8xSpr4muJGXzCUwKmYIs6WePGXWIHVpfazvWI0rBGePxBoJoVpu02NSwMy9QRlNq4z9SThGOZKyICtxJ7dJRaUHjrmL8sr1ZXtBuKAyxa5SmlQmZxRjuYVGKHV0q+FkVfdMgkRk3ARfV9mkew1Fa5NMeonlLnWhyYTZXtoOdVYinRrJdumdl/KDtvOwtX7T27HESPrq/CiZIB0ZfAWunfCsDe7Cx9DyEcCGje7wgI2+/3obzbp7NjaMI1iekbqZs5wcD1Y9/icHr7rls6Hbj41zIDZbkZMUCbqwz/dDEdP3uGdU7mXnTejYUwpW8gnRo/aPvmMTtaledBntXUEA7HUF4gkRjXU3/m2/dztL3Yalhsy7qkB0RGbkxVEA3jeZwKDYB5neWZKp8t+uASp37ezrn2DtWRieCHC9urq1qtkhseAosd/zBaOTrqqp48TxeT8uvZ7ZYeD1x6x/9swaPi1mwQezL2Y2aFh9j9aZDP0Hqy+or4Hll+tGLWENj6dUa1jDXmEFo3a8ng2bxlHbslNR9V0bVdetPWh3iOqB1xCcYFrt5pZkrLN3EZrFd11ch/0BC/t9IgYjsqLit2w4l0Pd+rOZ3PK/2ENg98cxNFi8EmTo185w781Ahs3n5IfbKyl8eiCi5HnxuRuSq8L7/kuv/dLJM2n1X0g+5Tm5JWX9ESVL2iWMIseLXHlWuQDWzkoYdFMqt1a9AWHDXo/Y87e4fri19tCreRi9mYXww8Ln696JFm4ndm2hbO5+klPDdz+agut/</div></div>
    <script type="text/javascript" src="includes/embed.js"></script>
<?php } ?>

<br>
<br>


<?php
if ($menu_select == "home") {
    ?>

    <?php
    error_reporting(E_ALL);

    $link = mysql_connect(DB_SERVER_HOST, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
    if (!$link) {
        die('Not connected : ' . mysql_error());
    }

// make foo the current db
    $db_selected = mysql_select_db(APP_DB, $link);
    if (!$db_selected) {
        die('Can\'t use foo : ' . mysql_error());
    }

    $year = date('Y-m-01', strtotime("-1 year"));



    $month_start = array();
    for ($i = 1; $i < 13; $i++) {
        $month_start[] = date('Y-m-d', strtotime($year . ' + ' . $i . ' month'));
        $month_end[] = date('Y-m-t', strtotime($year . ' + ' . $i . ' month'));
    }

    function fetch_partially_paid($id) {

        $sql = "select sum(i.debit_amount) as debit, sum(i.credit_amount) as credit 
	from journal_main m inner join journal_item i on m.id = i.ref_id 
	where i.so_po_item_ref_id = " . $id . " and m.journal_id in (18, 20) and i.gl_type in ('chk', 'pmt') 
	group by m.journal_id";

        $res = mysql_query($sql);
        $balance = mysql_fetch_assoc($res);

        if ($balance['debit'] || $balance['credit']) {
            return $balance['debit'] + $balance['credit'];
        } else {
            return 0;
        }
    }

    $paid_purchases_amount = array();
    $unpaid_purchases_amount = array();

    foreach ($month_start as $key => $val) {
        //this is invoice total for one month
        //$query = "SELECT sum(total_amount) as invoice_total from journal_main WHERE journal_id = 12 and (post_date BETWEEN '" . $val . "' AND '" . $month_end[$key] . "') ";
        $query = "select id, purchase_invoice_id, total_amount, bill_primary_name, currencies_code, currencies_value  from journal_main  where journal_id = 6  and (post_date BETWEEN '" . $val . "' AND '" . $month_end[$key] . "')";
        $res = mysql_query($query);
        $purchase = array();
        while ($purchase_total = mysql_fetch_assoc($res)) {
            $purchase[] = $purchase_total;
        }

        $pur_balance = 0;
        $pur_total = 0;
        foreach ($purchase as $single_purchase) {
            $pur_balance = $pur_balance + ($single_purchase['total_amount'] - fetch_partially_paid($single_purchase['id']));
            $pur_total = $pur_total + $single_purchase['total_amount'];
        }
        $unpaid_purchases_amount[] = $pur_balance;
        $paid_purchases_amount[] = $pur_total - $pur_balance;
    }



    $month_text = array();
    foreach ($month_start as $month) {
        $month_text[] = date('M-y', strtotime($month));
    }

    $month = array();
    foreach ($month_text as $month_print) {
        $month[] = $month_print;
    }
    $month = json_encode($month);
    $paid = array();
    foreach ($paid_purchases_amount as $pur_paid_amount) {
        $paid[] = $pur_paid_amount;
    }
    $paid = json_encode($paid);

    $unpaid = array();
    foreach ($unpaid_purchases_amount as $pur_unpaid_amount) {
        $unpaid[] = $pur_unpaid_amount;
    }
    $unpaid = json_encode($unpaid);
    ?>

    <script src="charts/highchart.js" type="text/javascript"></script>
    <script src="charts/exporting.js" type="text/javascript"></script>


    <script language="JavaScript" type="text/javascript">
        var month = new Array();
        month = jQuery.parseJSON('<?php echo $month; ?>');
        var unpaid = jQuery.parseJSON('<?php echo $unpaid; ?>');
                                                                    
        var unpaid_array = new Array();
        $(unpaid).each(function(i,v){
            unpaid_array.push(v);
        })
        var paid = jQuery.parseJSON('<?php echo $paid; ?>');
        var paid_array = new Array();
        $(paid).each(function(i,v){
            paid_array.push(v);
        })
        $(function () {
            $('#purchase').highcharts({
                chart: {
                    type: 'column',
                    marginBottom: 120,
                    backgroundColor: {
                        linearGradient: [0, 0, 500, 500],
                        stops: [
                            [0, '#E6E6FA'],
                            [1, '#F2F2FC']
                        ]
                    },
                    borderWidth: 0,
                    plotShadow: true,
                    plotBorderWidth: 1
                },
                title: {
                    text: ' purchase'
                },
                xAxis: {
                    categories: month,
                    startOnTick: true,
                    gridLineWidth: 1, 
                    lineColor: '#000', 
                    tickColor: '#000',
                    tickWidth: 2,
                    labels: {
                        style: {
                            fontSize:'9px',
                            marginLeft:'-10px'
                                                                                        
                        }
                    }
                }, yAxis: {
                    labels: {
                        useHTML: true,
                        formatter:  function(){
                            return '<div class="labels" style="margin-top:20px; font-weight:bold;font-family:Open Sans;">' +formatRM(this.value)+ '</div>';
                        }
                    },
                    title:{
                        text:''
                    }
                },
                legend: {
                    align: 'bottom',
                    x: 10,
                    verticalAlign: 'bottom',
                    y: 0,
                    width:480,
                    height:40,
                    paddingTop:10,
                    paddingBottom:10,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#D2D2E4',
                    borderColor: '#CCC',
                    borderWidth: 2,
                    shadow: true
                },
                tooltip: {
                    formatter: function() {
                        if( this.series.name=='Total amount to paid purchase'){
                            return '<b>'+ this.x +'</b><br/>'+
                                ' Paid: '+ this.y +'<br/>'+
                                'Total: '+ this.point.stackTotal;
                        }else{
                            return '<b>'+ this.x +'</b><br/>'+
                                ' Unpaid: '+ this.y +'<br/>'+
                                'Total: '+ this.point.stackTotal;
                        }
                                                                                    
                    }
                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                                                                                    
                    }
                },
                series: [
                    {
                        name: 'Total amount to unpaid purchase',
                        color:'#FE8B61',
                        data: unpaid_array
                                                                                    
                    },
                    {
                        name: 'Total amount to paid purchase',
                        color:'#A3D146',
                        data: paid_array
                                                                                    
                    }]
            });
        });
                                                                    
        function formatRM(num) {
            var p = num.toFixed(2).split(".");
            return "RM" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
                return  num + (i && !(i % 3) ? "," : "") + acc;
            }, "") + "." + p[1];
        }
    </script>


    <!--  invoice start here -->

    <?php
    $year = date('Y-m-01', strtotime("-1 year"));



    $month_start = array();
    for ($i = 1; $i < 13; $i++) {
        $month_start[] = date('Y-m-d', strtotime($year . ' + ' . $i . ' month'));
        $month_end[] = date('Y-m-t', strtotime($year . ' + ' . $i . ' month'));
    }



    $paid_invoice_amount = array();
    $unpaid_invoice_amount = array();

    foreach ($month_start as $key => $val) {
        //this is invoice total for one month
        //$query = "SELECT sum(total_amount) as invoice_total from journal_main WHERE journal_id = 12 and (post_date BETWEEN '" . $val . "' AND '" . $month_end[$key] . "') ";
        $query = "select id, purchase_invoice_id, total_amount, bill_primary_name, currencies_code, currencies_value  from journal_main  where journal_id = 12  and (post_date BETWEEN '" . $val . "' AND '" . $month_end[$key] . "')";
        $res = mysql_query($query);
        $invoice = array();
        while ($invoice_total = mysql_fetch_assoc($res)) {
            $invoice[] = $invoice_total;
        }

        $inv_balance = 0;
        $inv_total = 0;
        foreach ($invoice as $single_invoice) {
            $inv_balance = $inv_balance + ($single_invoice['total_amount'] - fetch_partially_paid($single_invoice['id']));
            $inv_total = $inv_total + $single_invoice['total_amount'];
        }
        $unpaid_invoice_amount[] = $inv_balance;
        $paid_invoice_amount[] = $inv_total - $inv_balance;
    }

    $month_text = array();
    foreach ($month_start as $month) {
        $month_text[] = date('M-y', strtotime($month));
    }

    $invoice_month = array();
    foreach ($month_text as $month_print) {
        $invoice_month[] = $month_print;
    }
    $invoice_month = json_encode($invoice_month);

    $inv_paid_am = array();
    foreach ($paid_invoice_amount as $inv_paid_amount) {
        $inv_paid_am[] = $inv_paid_amount;
    }
    $inv_paid_am = json_encode($inv_paid_am);

    $inv_unpaid_am = array();
    foreach ($unpaid_invoice_amount as $inv_unpaid_amount) {
        $inv_unpaid_am[] = $inv_unpaid_amount;
    }
    $inv_unpaid_am = json_encode($inv_unpaid_am);
    ?>

    <script language="JavaScript" type="text/javascript">
        var month_invoice = new Array();
        month_invoice = jQuery.parseJSON('<?php echo $invoice_month; ?>');

        var inv_unpaid_am = jQuery.parseJSON('<?php echo $inv_unpaid_am; ?>');
                                                                  
        var unpaid_inv_array = new Array();
        $(inv_unpaid_am).each(function(i,v){
            unpaid_inv_array.push(v);
        })
        var inv_paid_am = jQuery.parseJSON('<?php echo $inv_paid_am; ?>');
                                                                
        var paid_inv_array = new Array();
                                                                
        $(inv_paid_am).each(function(i,v){
            paid_inv_array.push(v);
        })
        $(function () {
            $('#invoice').highcharts({
                chart: {
                    type: 'column',
                    marginBottom: 120,
                    backgroundColor: {
                        linearGradient: [0, 0, 500, 500],
                        stops: [
                            [0, '#E6E6FA'],
                            [1, '#F2F2FC']
                        ]
                    },
                    borderWidth: 0,
                    plotShadow: true,
                    plotBorderWidth: 1
                },
                title: {
                    text: 'Invoice'
                },
                xAxis: {
                    categories: month_invoice,
                    startOnTick: true,
                    gridLineWidth: 1, 
                    lineColor: '#000', 
                    tickColor: '#000',
                    tickWidth: 2,
                    labels: {
                        style: {
                            fontSize:'9px',
                            marginLeft:'-10px'
                                                                                    
                        }
                    }
                }, yAxis: {
                    labels: {
                        useHTML: true,
                        formatter:  function(){
                            return '<div class="labels" style="margin-top:20px; font-weight:bold;font-family:Open Sans;">' +formatRM(this.value)+ '</div>';
                        }
                    },
                    title:{
                        text:''
                    }
                },
                legend: {
                    align: 'bottom',
                    x: 10,
                    verticalAlign: 'bottom',
                    y: 0,
                    width:480,
                    height:40,
                    paddingTop:10,
                    paddingBottom:10,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#D2D2E4',
                    borderColor: '#CCC',
                    borderWidth: 2,
                    shadow: true
                },
                tooltip: {
                    formatter: function() {
                        if( this.series.name=='Total amount to paid Invoice'){
                            return '<b>'+ this.x +'</b><br/>'+
                                ' Paid: '+ this.y +'<br/>'+
                                'Total: '+ this.point.stackTotal;
                        }else{
                            return '<b>'+ this.x +'</b><br/>'+
                                ' Unpaid: '+ this.y +'<br/>'+
                                'Total: '+ this.point.stackTotal;
                        }
                                                                                
                    }
                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                                                                                
                    }
                },
                series: [{
                        name: 'Total amount to paid Invoice',
                        color:'#A3D146',
                        data: paid_inv_array
                                                                                
                    }, {
                        name: 'Total amount to unpaid Invoice',
                        color:'#FE8B61',
                        data: unpaid_inv_array
                                                                                
                    }]
            });
        });
                                                             
    </script>

    <?php
} else if ($menu_select == "customers") {



    $link = mysql_connect(DB_SERVER_HOST, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
    if (!$link) {
        die('Not connected : ' . mysql_error());
    }

// make foo the current db
    $db_selected = mysql_select_db(APP_DB, $link);
    if (!$db_selected) {
        die('Can\'t use foo : ' . mysql_error());
    }
    $query = "SELECT  journal_item.sku AS sku, 
  journal_item.qty , SUM( journal_item.credit_amount - journal_item.debit_amount) AS sku_amount
 FROM journal_main JOIN journal_item ON journal_main.id = journal_item.ref_id 
WHERE journal_main.journal_id in ('12','13') and journal_item.sku <> '' GROUP BY sku ORDER BY sku_amount DESC";

    $res = mysql_query($query);
    $salesBySkuTotal = array();
    while ($sales_by_sku_total = mysql_fetch_assoc($res)) {
        $salesBySkuTotal[] = $sales_by_sku_total;
    }
    $i = 0;
    $single_amount = array();

    $other_total = 0;
    $jsvalue = '';
    foreach ($salesBySkuTotal as $sku_total) {
        if ($i <= 4) {

            $single_amount[] = array($sku_total['sku'], round($sku_total['sku_amount'],2));
        } else {
            $other_total = $other_total + $sku_total['sku_amount'];
        }
        $i++;
    }
    $single_amount[] = array('Others', round($other_total,2));
    $sku_total_amount = json_encode($single_amount);






    $query = "SELECT  journal_item.sku AS sku, 
  SUM(journal_item.qty) as qty 
 FROM journal_main JOIN journal_item ON journal_main.id = journal_item.ref_id 
WHERE journal_main.journal_id in ('12','13') and journal_item.sku <> '' GROUP BY sku ORDER BY qty desc";

    $res = mysql_query($query);
    $salesBySkuQty = array();
    while ($sales_by_sku_qty = mysql_fetch_assoc($res)) {
        $salesBySkuQty[] = $sales_by_sku_qty;
    }

    $i = 0;
    $single_amount = array();

    $other_qty = 0;
    foreach ($salesBySkuQty as $sku_total) {
        if ($i <= 4) {
            //$single_amount[$sku_total['sku']] = $sku_total['qty'];
            $single_amount[] = array($sku_total['sku'], floatval($sku_total['qty']));
        } else {
            $other_qty = $other_qty + $sku_total['qty'];
        }
        $i++;
    }

    $single_amount[] = array('Others', floatval($other_qty));
    $sku_qty_amount = json_encode($single_amount);
    ?>




    <input type="hidden" id="sku_total_amount" value="<?php if (!empty($sku_total_amount)) echo htmlspecialchars($sku_total_amount); ?>"/>
    <input type="hidden" id="sku_qty_amount" value="<?php if (!empty($sku_qty_amount)) echo htmlspecialchars($sku_qty_amount); ?>"/>
    <script src="charts/highchart.js" type="text/javascript"></script>
    <script src="charts/exporting.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript">
        var sku_total_amount = new Array();
        var sku_total_amount_data =  $("#sku_total_amount").val();
        sku_total_amount = jQuery.parseJSON(sku_total_amount_data);

        $(function () {
            $('#sku_total').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: 'SKU Total'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}% ( RM {point.y} )</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} % <br/> ( RM {point.y} )',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        },
                        point: {
                            events: {
                                click: function(e) {
                                    window.open( e.point.series.options.url+'category='+e.point.name,'_newtab'); //proper path 2)
                                    e.preventDefault();
                                }
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                        type: 'pie',
                        name: 'SKU TOTAL',
                        url:'index.php?module=inventory&page=main&list=1&',  
                        data: sku_total_amount
                                        
                    }]
            });
        });
                            
        var sku_qty_amount = new Array();
        var sku_qty_amount_data =  $("#sku_qty_amount").val();
        sku_qty_amount = jQuery.parseJSON(sku_qty_amount_data);

                            
                            
        $(function () {
            $('#sku_qty').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: 'SKU Quantity'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}% ( {point.y} )</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true, 
                            format: '<b>{point.name}</b>: {point.percentage:.1f} % <br/>( {point.y} )',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        },
                        point: {
                            events: {
                                click: function(e) {
                                    window.open( e.point.series.options.url+'category='+e.point.name,'_newtab'); //proper path 2)
                                    e.preventDefault();
                                }
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                        type: 'pie',
                        name: 'SKU QTY',
                        url:'index.php?module=inventory&page=main&list=1&', 
                        data: 
                            sku_qty_amount
                                        
                    }]
            });
        });


    </script>

    <?php
} else if ($menu_select == "vendors") {

    $link = mysql_connect(DB_SERVER_HOST, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
    if (!$link) {
        die('Not connected : ' . mysql_error());
    }

// make foo the current db
    $db_selected = mysql_select_db(APP_DB, $link);
    if (!$db_selected) {
        die('Can\'t use foo : ' . mysql_error());
    }
    $query = "SELECT  journal_item.sku AS sku, 
  journal_item.qty , SUM( journal_item.debit_amount - journal_item.credit_amount) AS sku_amount
 FROM journal_main JOIN journal_item ON journal_main.id = journal_item.ref_id 
WHERE journal_main.journal_id in ('6','7') and journal_item.sku <> '' GROUP BY sku ORDER BY sku_amount DESC";

    $res = mysql_query($query);
    $salesBySkuTotal = array();
    while ($purchase_by_sku_total = mysql_fetch_assoc($res)) {
        $purchaseBySkuTotal[] = $purchase_by_sku_total;
    }
    $i = 0;
    $single_amount = array();

    $other_total = 0;
    $jsvalue = '';
    if(!empty($purchaseBySkuTotal))
    foreach ($purchaseBySkuTotal as $sku_total) {
        if ($i <= 4) {

            $single_amount[] = array($sku_total['sku'], round($sku_total['sku_amount'],2));
        } else {
            $other_total = $other_total + $sku_total['sku_amount'];
        }
        $i++;
    }
    $single_amount[] = array('Others', round($other_total,2));
    $sku_total_amount = json_encode($single_amount);






    $query = "SELECT  journal_item.sku AS sku, 
  SUM(journal_item.qty) as qty 
 FROM journal_main JOIN journal_item ON journal_main.id = journal_item.ref_id 
WHERE journal_main.journal_id in ('6','7') and journal_item.sku <> '' GROUP BY sku ORDER BY qty desc";

    $res = mysql_query($query);
    $purchaseBySkuQty = array();
    while ($purchase_by_sku_qty = mysql_fetch_assoc($res)) {
        $purchaseBySkuQty[] = $purchase_by_sku_qty;
    }

    $i = 0;
    $single_amount = array();

    $other_qty = 0;
if(!empty($purchaseBySkuQty))
    foreach ($purchaseBySkuQty as $sku_total) {
        if ($i <= 4) {
            //$single_amount[$sku_total['sku']] = $sku_total['qty'];
            $single_amount[] = array($sku_total['sku'], floatval($sku_total['qty']));
        } else {
            $other_qty = $other_qty + $sku_total['qty'];
        }
        $i++;
    }

    $single_amount[] = array('Others', floatval($other_qty));
    $sku_qty_amount = json_encode($single_amount);
}
?>
<input type="hidden" id="sku_total_amount" value="<?php if (!empty($sku_total_amount)) echo htmlspecialchars($sku_total_amount); ?>"/>
<input type="hidden" id="sku_qty_amount" value="<?php if (!empty($sku_qty_amount)) echo htmlspecialchars($sku_qty_amount); ?>"/>
<script src="charts/highchart.js" type="text/javascript"></script>
<script src="charts/exporting.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
    var sku_total_amount = new Array();
    var sku_total_amount_data =  $("#sku_total_amount").val();
    sku_total_amount = jQuery.parseJSON(sku_total_amount_data);

    $(function () {
        $('#sku_total_purchase').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'SKU Total'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}% ( RM {point.y} )</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} % <br/> ( RM {point.y} )',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    point: {
                            events: {
                                click: function(e) {
                                    window.open( e.point.series.options.url+'category='+e.point.name,'_newtab'); //proper path 2)
                                    e.preventDefault();
                                }
                            }
                        },
                    showInLegend: true
                }
            },
            series: [{
                    type: 'pie',
                    name: 'SKU TOTAL',
                    url:'index.php?module=inventory&page=main&list=1&',
                    data: sku_total_amount
                            
                }]
        });
    });
                
    var sku_qty_amount = new Array();
    var sku_qty_amount_data =  $("#sku_qty_amount").val();
    sku_qty_amount = jQuery.parseJSON(sku_qty_amount_data);

                
                
    $(function () {
        $('#sku_qty_purchase').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'SKU Quantity'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}% ( {point.y} )</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true, 
                        format: '<b>{point.name}</b>: {point.percentage:.1f} % <br/>( {point.y} )',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    point: {
                            events: {
                                click: function(e) {
                                    window.open( e.point.series.options.url+'category='+e.point.name,'_newtab'); //proper path 2)
                                    e.preventDefault();
                                }
                            }
                        },
                    showInLegend: true
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Browser share',
                   url:'index.php?module=inventory&page=main&list=1&',
                    data: 
                        sku_qty_amount
                            
                }]
        });
    });


</script>