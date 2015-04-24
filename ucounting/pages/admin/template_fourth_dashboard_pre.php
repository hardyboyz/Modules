<script type="text/javascript">
    function submit_wizard(obj){       
        $(obj).parents('#turnoff').submit();
    }
</script>
<?php 
        $setupwizard_status = mysql_query("select configuration_key, configuration_value from configuration where configuration_key = 'SETUP_WIZARD_STATUS'");
        $status = mysql_fetch_object($setupwizard_status);
        if($status->configuration_value == 'true'){
            $show_text = "Turn off Wizard On Login";
            $class = 'sgreen';
        }else{
            $show_text = "Turn on Wizard On Login";
            $class = 'sgray';
        }
?>

<?php echo html_form('turnoff', 'index.php?module=ucounting&page=admin', '') . chr(10); ?>
<a style="float: right" class="<?php echo $class ?>" href="javascript:" onclick="submit_wizard(this)" ><?php echo $show_text ?></a>    
    <input type="hidden" value="<?php echo $status->configuration_value ?>" name="wizard">	
</form>
<div class="clear"></div>

<div  id="wizard" class="swMain">
    <ul class="anchor">
        <li><a class="done <?php if ($setupwizard_menu == 'step_1') {
    echo 'selected';
} ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=first_dashboard', 'SSL') ?>"  isdone="1" rel="1">
                <label class="stepNumber">1</label>
                <span class="stepDesc">
                    Step 1<br>
                    <small>Setup Company</small>
                </span>
            </a>
        </li>
        <?php
        echo html_form('import_export', 'index.php?module=ucounting&page=import_export', '') . chr(10);
        echo html_hidden_field('todo', '') . chr(10);
        echo html_hidden_field('subject', $subject) . chr(10);
        ?>
        <li>

            <input type="hidden" name="second_dashboard" value="second_dashboard" />
            <a class="done <?php if ($setupwizard_menu == 'step_2') {
            echo 'selected';
        } ?>" href="javascript:" onclick="submitToDo('go_contacts',true)"   isdone="1" rel="2">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                    Step 2<br>
                    <small>Upload Contacts</small>
                </span>
            </a>

        </li>

        <li>
            <?php
//                echo html_form('import_export', 'index.php?module=ucounting&page=import_export', '') . chr(10); 
//                echo html_hidden_field('todo', '') . chr(10);
//                echo html_hidden_field('subject', $subject) . chr(10);
            ?>
<!--            <input type="hidden" name="third_dashboard" value="third_dashboard" />-->
            <a class="done <?php if ($setupwizard_menu == 'step_3') {
                echo 'selected';
            } ?>" href="javascript:" onclick="submitToDo('go_inventory',true)"  isdone="1" rel="3">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                    Step 3<br>
                    <small>Upload Items</small>
                </span>                   
            </a>

        </li>
        </form>
        <li><a class="done <?php if ($setupwizard_menu == 'step_4') {
                echo 'selected';
            } ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=fourth_dashboard', 'SSL') ?>"  isdone="1" rel="4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                    Step 4<br>
                    <small>Create Transactions</small>
                </span>                   
            </a></li>
            
            <!--<li><a class="done <?php if ($setupwizard_menu == 'step_5') {
                echo 'selected';
            } ?>" href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucounting&amp;page=admin&amp;action=fifth_dashboard', 'SSL') ?>"  isdone="1" rel="4">
                <label class="stepNumber">5</label>
                <span class="stepDesc">
                    Step 5<br>
                    <small>Create Transactions</small>
                </span>                   
            </a></li>-->


    </ul>

</div>

<div class="action_are">
    <center>
    <a href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=12', 'SSL') ?>">
        <img alt="" src="images/enter-new-invoice.png" />
    </a>
    <br/>
    <br/>
    <br/>
    <a href="<?php echo html_href_link(FILENAME_DEFAULT, 'module=ucbooks&amp;page=orders&amp;jID=6', 'SSL') ?>">
        <img alt="" src="images/enter-new-purchase.png" />
    </a>
    </center>
    
</div>
<br/><br/>
