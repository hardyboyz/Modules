<?php
    echo html_form('set_security', FILENAME_DEFAULT, gen_get_all_get_params(array('action'))) . chr(10);
    
?>
<input type="password" name="set_security"/>
<input type="submit" value="Set Security" />
</form>