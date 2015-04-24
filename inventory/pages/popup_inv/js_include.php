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
//  Path: /modules/inventory/pages/popup_inv/js_include.php
//
?>
<script type="text/javascript">
    <!--
    // pass any php variables generated during pre-process that are used in the javascript functions.
    // Include translations here as well.
    var rID = '<?php echo $rowID; ?>';

    function init() {
<?php if ($auto_close) echo 'self.close();'; ?>
        document.getElementById('search_text').focus();
        document.getElementById('search_text').select();
    }

    function check_form() {
        return true;
    }

    // Insert other page specific functions here.
    function setReturnItem(iID) {
 
        window.opener.loadSkuDetails(iID, rID);
        self.close();
    }

    function setReturnMasterItem(iID,rID) {
        if(iID != undefined || rID != undefined)
            window.opener.loadMasterSkuDetails(iID, rID)
        
        self.close();
    }

    // -->
</script>