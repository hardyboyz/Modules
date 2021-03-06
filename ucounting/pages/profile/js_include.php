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
//  Path: /modules/ucounting/pages/profile/js_include.php
//
?>
<script type="text/javascript">
function init() {
}

function check_form() {
  return true;
}

function updateColors() {
  $.ajax({
    type: "GET",
    url: 'index.php?module=ucounting&page=ajax&op=ucounting&action=pull_colors&theme='+document.getElementById('theme').value,
    dataType: ($.browser.msie) ? "text" : "xml",
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
    },
	success: fillColors
  });
}

function fillColors(sXml) {
	var xml = parseXml(sXml);
	while (document.getElementById('menu').options.length)   document.getElementById('menu').remove(0);
	while (document.getElementById('colors').options.length) document.getElementById('colors').remove(0);
	// fill the menu orientation options
    var iIndex = 0;
    $(xml).find("menu").each(function() {
      newOpt = document.createElement("option");
	  newOpt.text = $(this).find("text").text();
	  document.getElementById('menu').options.add(newOpt);
	  document.getElementById('menu').options[iIndex].value = $(this).find("id").text();
	  iIndex++;
    });
	//now fill the color choices
    var iIndex = 0;
    $(xml).find("color").each(function() {
      newOpt = document.createElement("option");
	  newOpt.text = $(this).find("text").text();
	  document.getElementById('colors').options.add(newOpt);
	  document.getElementById('colors').options[iIndex].value = $(this).find("id").text();
	  iIndex++;
    });
}

</script>
