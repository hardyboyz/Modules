<?php
// +-----------------------------------------------------------------+
// |                   PhreeBooks Open Source ERP                    |
// +-----------------------------------------------------------------+
// | Copyright(c) 2008-2013 PhreeSoft, LLC (www.PhreeSoft.com)       |
// +-----------------------------------------------------------------+
//  Path: /modules/opencart/pages/main/js_include.php
//
?>
<script type="text/javascript">
<!--
// pass some php variables
var skuList = new Array();
var cnt     = 0;
var cntTotal= 0;
var cntCur  = 0;
var runaway = 0;

<?php echo js_calendar_init($cal_zc); ?>
// required function called with every page load
function init() {
	$("#bulk_upload").progressbar({ disabled: true });
}

function check_form() {
  return true;
}

function bulkUpload() { // fetch the sku count
	$.ajax({
    type: "GET",
    url: 'index.php?module=opencart&page=ajax&op=opencart&action=product_cnt',
    dataType: ($.browser.msie) ? "text" : "xml",
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      alert ("Ajax Error: " + XMLHttpRequest.responseText + "\nTextStatus: " + textStatus + "\nErrorThrown: " + errorThrown);
    },
	success: bulkUploadResp
  });
}

function bulkUploadResp(sXml) {
  var xml = parseXml(sXml);
  if (!xml) return;
  cnt = 0;
  $(xml).find("Product").each(function() {
	skuList[cnt] = $(this).find("iID").text();
	cnt++;
  });
  cntTotal = cnt;
  var id = skuList.shift();
  if (id) {
	$("#bulk_upload").progressbar("enable");
	cntCur = 1;
	productUpload(id); // start the upload
  }
}

function productUpload(id) {
  if (!id) return;
  var image = document.getElementById("include_images").checked ? '1' : '0';
  $.ajax({
    type: "GET",
    url: 'index.php?module=opencart&page=ajax&op=opencart&action=product_upload&iID='+id+'&image='+image,
    dataType: ($.browser.msie) ? "text" : "xml",
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      alert ("Ajax Error: "+XMLHttpRequest.responseText + "\nTextStatus: "+textStatus+"\nErrorThrown: "+errorThrown);
    },
	success: productUploadResp
  });
}

function productUploadResp(sXml) {
  var xml = parseXml(sXml);
  if (!xml) return;
  if ($(xml).find("message").text()) alert($(xml).find("message").text());
  if ($(xml).find("error").text()) return;
  var id = skuList.shift();
  $("#bulk_upload").progressbar({ value: ((cntCur/cntTotal)*100) });
  if (id) {
	cntCur++;
//	if (runaway++ > 10) return alert ('runaway count reached');
	productUpload(id); // start the upload
  } else {
	alert('Bulk Upload Complete! '+cntTotal+' products uploaded');
  }
}
// -->
</script>