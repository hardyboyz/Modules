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
//  Path: /modules/ucounting/pages/popup_setup/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    // Include translations here as well.

    function init() {
<?php
if ($close_popup) {
    echo 'window.opener.location.reload();' . chr(10);
    echo 'self.close();' . chr(10);
}
?>
    }
    $('#id').live('input', function(event) {
        this.value = this.value.replace(/[^0-9a-z_-]/i, '');
    });
    function check_form() {
        return true;
    }

    // Insert other page specific functions here.

    // -->
</script>