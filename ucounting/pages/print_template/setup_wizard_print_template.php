<script type="text/javascript">
    function submitThis(obj) {
        $(obj).parents('#print_template').submit();
    }
    function submit_upload_type(obj) {
        //alert($(obj).find(".up").html());
        var submitval = $(obj).attr('rel');
        $('#upload_type').val($(obj).find(".up").html());
        $(obj).parents('#import_export').find('#todo').val(submitval);
        $(obj).parents('#import_export').submit();
    }
</script>
<link href="includes/flexslider/css/flexslider.css" rel="stylesheet">
<script src="includes/flexslider/js/jquery.flexslider.js"></script>
<div  id="wizard" class="swMain">
    <ul class="anchor">
        <li><a class="done <?php
            if ($setupwizard_menu == 'step_1') {
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=first_dashboard', 'SSL') ?>"  isdone="1" rel="1">
                <label class="stepNumber">1</label>
                <span class="stepDesc">
                    Step 1<br>
                    <small>Setup Company</small>
                </span>
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_2') {
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=chart_of_account', 'SSL') ?>"   isdone="1" rel="4">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                    Step 2<br>
                    <small>Setup Chart of Accounts</small>
                </span>                   
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_7') { //used for step3
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=setup_account_defaults', 'SSL') ?>"   isdone="1" rel="4">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                    Step 3<br>
                    <small>Setup Account Defaults</small>
                </span>                   
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_8') { //used for step3
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&page=chart_of_accounts&action_type=setup_tax_codes', 'SSL') ?>"   isdone="1" rel="4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                    Step 4<br>
                    <small>Setup Tax Codes</small>
                </span>                   
            </a>
        </li>
        <?php
        echo html_form('import_export', 'index.php?module=ucounting&page=import_export', '') . chr(10);
        echo html_hidden_field('todo', '') . chr(10);
        echo html_hidden_field('subject', $subject) . chr(10);
        ?>
        <input type="hidden" id="upload_type" name="upload_type" value="" />
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_3') {
                echo 'selected';
                //submit_upload_type go_contacts
            }
            ?>" href="javascript:" onclick="submit_upload_type(this)" rel="go_contacts"  isdone="1" rel="2">
                <label class="stepNumber">5</label>
                <span class="stepDesc">
                    Step 5<br>
                    <small class="up">Upload Contacts</small>
                </span>
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_4') {
                echo 'selected';
            }
            ?>" href="javascript:" onclick="submit_upload_type(this)" rel="go_inventory"  isdone="1" rel="3">
                <label class="stepNumber">6</label>
                <span class="stepDesc">
                    Step 6<br>
                    <small class="up">Upload Inventory</small>
                </span>                   
            </a>

        </li>
        </form>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_5') {
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=print_template&action_type=print_template', 'SSL') ?>"   isdone="1" rel="5">
                <label class="stepNumber">7</label>
                <span class="stepDesc">
                    Step 7<br>
                    <small>Setup Print Template</small>
                </span>                   
            </a>
        </li>
        <li>
            <a class="done <?php
            if ($setupwizard_menu == 'step_6') {
                echo 'selected';
            }
            ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&page=tools&action_type=transaction_sequence', 'SSL') ?>"   isdone="1" rel="6">
                <label class="stepNumber">8</label>
                <span class="stepDesc">
                    Step 8<br>
                    <small>Setup Transaction Sequence</small>
                </span>                   
            </a>
        </li>
        <!--        <li><a class="done <?php
        if ($setupwizard_menu == 'step_4') {
            echo 'selected';
        }
        ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=fourth_dashboard', 'SSL') ?>"  isdone="1" rel="4">
                        <label class="stepNumber">4</label>
                        <span class="stepDesc">
                            Step 4<br>
                            <small>Create Transactions</small>
                        </span>                   
                    </a></li>-->

        <!--<li><a class="done <?php
        if ($setupwizard_menu == 'step_5') {
            echo 'selected';
        }
        ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=fifth_dashboard', 'SSL') ?>"  isdone="1" rel="4">
            <label class="stepNumber">5</label>
            <span class="stepDesc">
                Step 5<br>
                <small>Create Transactions</small>
            </span>                   
        </a></li>-->
    </ul>
</div>
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
//echo html_form('print_template', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
echo html_form('print_template', FILENAME_DEFAULT, 'module=ucounting&page=print_template&sf=&so=&action=save', 'post', 'enctype="multipart/form-data"') . chr(10);
// include hidden fields
echo html_hidden_field('todo', '') . chr(10);
echo html_hidden_field('rowSeq', $uom_id) . chr(10);
// customize the toolbar actions
$toolbar->icon_list['cancel']['params'] = 'onclick="index.php?module=ucounting&page=print_template&action_type=print_template"';
$toolbar->icon_list['open']['show'] = false;
$toolbar->icon_list['delete']['show'] = false;
$toolbar->icon_list['print']['show'] = false;
if ($security_level > 2) {
    //$toolbar->icon_list['save']['params'] = 'onclick="submitToDo(\'save\')"';
    $toolbar->icon_list['save']['params'] = 'onclick="submitThis(this)"';
} else {
    $toolbar->icon_list['save']['show'] = false;
}
if (count($extra_toolbar_buttons) > 0)
    foreach ($extra_toolbar_buttons as $key => $value)
        $toolbar->icon_list[$key] = $value;
$toolbar->add_help('07.08.07');
//echo $toolbar->build_toolbar();
// Build the page
$print_template_list = array(
    array('id' => '1', 'text' => 'Template Style 1'),
    array('id' => '2', 'text' => 'Template Style 2'),
    array('id' => '3', 'text' => 'Template Style 3'),
    array('id' => '4', 'text' => 'Template Style 4'),
    array('id' => '5', 'text' => 'Template Style 5'),
    array('id' => '6', 'text' => 'Template Style 6'),
    array('id' => '7', 'text' => 'Template Style 7'),
    array('id' => '8', 'text' => 'Template Style 8'),
    array('id' => '9', 'text' => 'Template Style 9'),
    array('id' => '10', 'text' => 'Template Style 10'),
    array('id' => '11', 'text' => 'Template Style 11'),
    array('id' => '12', 'text' => 'Template Style 12')
);
?>
<style type="text/css">
    .radio_btn{width: 20px}
</style>
<div class="bottom_btn" id="bottom_btn">
    <?php echo $toolbar->build_toolbar(); ?>
</div>
<h1>Print Template Style</h1>
<input type="hidden" name="setup_wizard_action" value="print_template" />

<table class="ui-widget" style="border-style:none;margin-left:auto;margin-right:auto; width: 100%;">
    <tbody class="ui-widget-content">
        <tr>
            <td width="33">&nbsp;</td>
            <td width="33">&nbsp;</td>
            <td width="33">&nbsp;</td>
        </tr>
        <tr>
            <td width="33">
                <span class="form_label">Template Design :</span>
                <?php echo html_pull_down_menu('print_template', $print_template_list, PRINT_INVOICE_STYLE); ?>
            </td>
            <td width="33">
            </td>
            <td width="33">
                <br/><br/><br/>
            </td>
        </tr>
        <tr>
            <td width="33">&nbsp;</td>
            <td width="33">&nbsp;</td>
            <td width="33">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="tamplate_area">
                    <?php
                    $path = 'images/template_image';
                    $cdir = scandir($path);
                    $final_path = array();
                    foreach ($cdir as $key => $value) {
                        if (!in_array($value, array(".", ".."))) {
                            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                            } else {

                                $final_path[] = $value;
                            }
                        }
                    }

                    foreach ($final_path as $each_path) {
                        ?>
                        <div style="display:none;" class="each_template <?php echo $each_path; ?>">
                            <?php
                            $full_path = 'images/template_image/' . $each_path;
                            $tem_folder = scandir($full_path);
                            $i = 0;
                            foreach ($tem_folder as $each_image) {
                                if (!in_array($each_image, array(".", ".."))) {
                                    ?>
                                                    <!--a style="cursor:pointer;" id="example_<?php //echo $i;   ?>"--> <div class="each_image"> <img width="300" height="400" src="images/template_image/<?php
                                        echo $each_path;
                                        echo '/';
                                        echo $each_image;
                                        ?>"/></div><!--/a-->
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                            <div style="clear: both"></div>

                        </div>
                    <?php } ?>
                    <div class="slideshow_wrapper">
                        <div class="slideshow">
                            <a href="javascript:" onclick="$(this).parents('.slideshow_wrapper').hide('slow')" class="close_btn"></a>
                            <div id="slider" class="flexslider">
                                <ul class="slides">

                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
        </tr>


    </tbody>

</table>



<div class="bottom_btn">
    <?php echo $toolbar->build_toolbar(); ?>
</div>




</form>
<br/>

<style type="text/css">
    .each_templete{width: 100%; height: 200px; margin: 15px;}
    .each_image{ /*width: 100px; height: 100px;*/ float: left; margin: 5px;}

    .slideshow_wrapper {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0.5);
        display: none;
        height: 100%;
        left: 0;
        padding: 1% 0 0;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 100;
    }
    .slideshow_wrapper .slideshow {
        margin: 3% auto;
        position: relative;
        width: 50%;
        background-color: whitesmoke;
        border-radius: 5px;
        padding-left:50px; 
        padding-bottom:20px;
        padding-top:20px;
        padding-right: 50px;
    }
    .slideshow_wrapper .flexslider {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        border: medium none;
        border-radius: 0;
        box-shadow: none;
        margin: 0;
    }
    .slideshow_wrapper #carousel {
        bottom: 6%;
        margin: 0 10%;
        position: absolute;
        width: 80%;
    }
    .slideshow_wrapper #carousel .slides img {
        opacity: 0.5;
    }
    .slideshow_wrapper #carousel .slides .flex-active-slide img {
        opacity: 1;
    }
    .slideshow_wrapper .flex-direction-nav a:before {
        font-size: 24px;
    }
</style>
<script type="text/javascript">
    var sliderInitiated = false;
    $(document).ready(function () {

        var option = $('#print_template option:selected').val();
        $('.tamplate_area').find('.activated').hide();
        $('.tamplate_area').find('.activated').removeClass('activated');
        $('.Template' + option).show();
        $('.Template' + option).addClass('activated');

    })




    $('#print_template').change(function () {
        var option = $('#print_template option:selected').val();
        $('.tamplate_area').find('.activated').hide();
        $('.tamplate_area').find('.activated').removeClass('activated');
        $('.Template' + option).show();
        $('.Template' + option).addClass('activated');

    });

    $('div.activated a[id*="example_"]').live('click', function () {
        $('#slider').removeData("flexslider");
        $('.slides').html('');
        var $obj = $(this);
        $.ajaxSetup({'async': false});
        $obj.siblings().each(function (i, v) {
            var image = $('div.activated #example_' + i).find('img').attr('src');
            if (i == 0) {
                $('.slides').append('<li class="flex-active-slide" style="width: 675px; float: left; display: block;"><img height="500" src="' + image + '" /></li>');
            } else {
                $('.slides').append('<li style="width: 675px; float: left; display: block;"><img height="500" src="' + image + '" /></li>');
            }


        });

        $('.slideshow_wrapper').show('slow', function () {
            $('#slider').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                start: function (slider) {
                    $('body').removeClass('loading');
                }
            });

        });

        $('.slideshow_wrapper').css("top", $(window).scrollTop());

        $(window).scroll(function () {
            $('.slideshow_wrapper').css("top", $(window).scrollTop());
        });
    });
</script>