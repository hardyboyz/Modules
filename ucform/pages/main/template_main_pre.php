<script type="text/javascript">
    $(document).ready(function (){ 
        //fetch_doc(0);  
        //showLoading();
        get_dashboard();
    });
    
    function get_dashboard(){
        
        $("#show_dashboard iframe").attr("src", "<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=main&mID=cat_rep', 'SSL') ?>");
        //$("#show_dashboard iframe").height($("#show_dashboard iframe").contents().find("html").height());
        $("#popup_gen").hide();
        $("#report_viewer").hide();
        $("#show_dashboard").css("display","block");
        $(".show_list").hide();
        //$(".content_area").hide();
        $("#frm_msg").hide();
    }
    
</script>


<style type="text/css">
    #gentabs input[type="text"]{width: 104px !important}
    #show_report .footer{display: none;}
    #banner{margin: 0px !important;padding:10px !important;}
</style>

<div class="report" style="margin: -35px 0 0 0px;">
<!--    <div style="float: left; margin: 0 10px 0 0">
    <a href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=main&amp;mID=cat_rep', 'SSL') ?>">
        <a href="javascript:" onclick="get_dashboard()">
           Dashboard 
        </a>
        
    </div>-->
    <?php 
        echo build_dir_html_on_report('dir_tree', $toc_array); 
    ?>
</div>
<br/><br/><br/>
<br/><br/><br/>
<div class="show_list" >
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
//  Path: /modules/ucform/pages/main/template_main.php
//
    echo html_form('ucform', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
    echo html_hidden_field('id', $id) . chr(10);
    //echo html_hidden_field('todo', '') . chr(10);
    echo html_hidden_field('rowSeq', '') . chr(10);
    echo html_hidden_field('newName', '') . chr(10);
// customize the toolbar actions
    $toolbar->icon_list['cancel']['params'] = 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, '', 'SSL') . '\'"';
    $toolbar->icon_list['cancel']['show'] = false;
    $toolbar->icon_list['open']['show'] = false;
    $toolbar->icon_list['save']['show'] = false;
    $toolbar->icon_list['delete']['show'] = false;
    $toolbar->icon_list['print']['show'] = false;
    if ($security_level > 1)
        if($_SESSION['set_security']=='yes')
        $toolbar->add_icon('import', 'onclick="ReportPopup(\'import\')"', $order = 50);
    $toolbar->icon_list['home'] = array(
        'show' => false,
        'icon' => 'actions/go-home.png',
        'params' => 'onclick="location.href = \'' . html_href_link(FILENAME_DEFAULT, gen_get_all_get_params(array('action')), 'SSL') . '\'"',
        'text' => TEXT_HOME,
        'order' => '25',
    );
    if ($security_level > 1) {
        $toolbar->icon_list['new_rpt'] = array(
            'show' => false,
            'icon' => 'mimetypes/text-x-generic.png',
            'params' => 'onclick="ReportPopup(\'new_rpt\')"',
            'text' => TEXT_NEW_REPORT,
            'order' => '30',
        );
        $toolbar->icon_list['new_frm'] = array(
            'show' => false,
            'icon' => 'mimetypes/text-html.png',
            'params' => 'onclick="ReportPopup(\'new_frm\')"',
            'text' => TEXT_NEW_FORM,
            'order' => '35',
        );
    }
    $toolbar->add_help('06.02');
    if ($search_text)
        $toolbar->search_text = $search_text;
//    echo $toolbar->build_toolbar($add_search = true);
    echo $toolbar->build_toolbar();
    ?>

    <h1 ><?php echo TEXT_REPORTS; ?></h1>
    <table class="ui-widget" style="border-style:none;width:100%;">
        <tr>
    <!--        <td width="30%" valign="top">
    
                <h5><?php echo TEXT_DOCUMENTS; ?></h5>
            <?php echo '<a href="javascript:Expand(\'' . 'dc_' . '\');">' . TEXT_EXPAND_ALL . '</a> - <a href="javascript:Collapse(\'' . 'dc_' . '\');">' . TEXT_COLLAPSE_ALL . '</a><br />' . chr(10); ?>
                
    
            </td>-->
            <td width="100%" valign="top">

                <h5><?php// echo TEXT_DETAILS; ?></h5>
                <div id="rightColumn">
                    <?php
                    if (file_exists($div_template)) {
                        include ($div_template);
                        echo $fieldset_content;
                    }
                    ?>
                </div>

            </td>
        </tr>
    </table>
</form>
<br/>


</div>
</div>

<center>
    
    
    <div id="show_report" style="margin: -27px 0 0 0">
        
    </div>

</center>
<style type="text/css">
    .show_report{width: 1100px;  margin: 0 auto; overflow-x:hidden; overflow-y: auto;  margin: -27px 0 0 -2px;}
</style>



<div id="show_dashboard" style="margin: -27px 0 0 0">
        <iframe src="" id='frmid' width="1100px"  style="overflow: hidden;">
        
        </iframe>
    </div>

<script language="javascript" type="text/javascript">
setInterval(function(){
    document.getElementById("frmid").style.height = document.getElementById("frmid").contentWindow.document.body.scrollHeight + 'px';
},1000)
</script>


