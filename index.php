<?php
require_once "common.php";

//Web::debug($responders, "Responders");//$br->isMobile() ? "Yes" : "No", "Is this browser mobile");
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php include DIR_INC."/og_meta.inc.php" ?>
<?php include DIR_INC."/apple_meta.inc.php" ?>
<title><?=($browser['iOS']) ? NAME :TITLE?></title>
<link rel="shortcut icon" href="<?=DIR_IMG?>/favicon.ico">
<!-- normalize.css -->
<link rel="stylesheet" href="<?=DIR_CSS?>/normalize.min.css" type="text/css">
<!-- Console.js -->
<script src="<?=DIR_CON?>/Console.js" type="text/javascript"></script>
<!-- jQuery -->
<script src="<?=DIR_JS?>/jquery-<?=VER_JQ?>.min.js" type="text/javascript"></script>
<!-- jQuery Center Plugin 
<script src="<?php echo DIR_JS; ?>/jquery.center.min.js" type="text/javascript"></script> -->
<!-- jQuery UI -->
<link href="<?=DIR_JQUI?>/css/redmond/jquery-ui-<?=VER_JQUI?>.min.css" rel="stylesheet" type="text/css">
<script src="<?=DIR_JQUI?>/jquery-ui-<?=VER_JQUI?>.min.js" type="text/javascript"></script>
<!-- jQuery-shorty (my shortcut plugin) -->
<script src="<?=DIR_JS?>/shorty/jquery.shorty.min.js" type="text/javascript"></script>
<!-- jQuery-inputevent -->
<script src="<?=DIR_JS?>/jquery.inputevent.min.js" type="text/javascript"></script>
<!-- Leaflet -->
<link rel="stylesheet" href="<?=DIR_LEAF?>/leaflet.css" type="text/css">
<!--[if lte IE 8]><link rel="stylesheet" href="<?=DIR_LEAF?>/leaflet.ie.css" /><![endif]-->
<script src="<?=DIR_LEAF?>/leaflet.js" type="text/javascript"></script>
<!-- Proprietary -->
<link rel="stylesheet" href="<?=DIR_CSS?>/styles.css" type="text/css">
<script src="<?=DIR_JS?>/scripts.js" type="text/javascript"></script>
<script type="text/javascript">
// Set to global for debugging purposes.
var map;
<?php if (!CONFIG_DEBUG): ?>
Console.setOption("enabled", false);
<?php endif; ?>
(function($) {
	$(function() {
		var ua = navigator.userAgent,
			currentBrowser = {
				firefox: /firefox/gi.test(ua),
				ie: /msie/gi.test(ua),
				ios: /i(phone|pad)/gi.test(ua),
				webkit: /webkit/gi.test(ua),
			};
		map = L.map('map', {
			attributionControl: false,
			worldCopyJump: false
		});
		
		L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
			maxZoom: 18,
			minZoom: 4,
			attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery Â© <a href="http://cloudmade.com">CloudMade</a>'
		}).addTo(map);
		
		function onLocationFound(e) {
			var radius = e.accuracy / 2;
			if (radius.hasDecimal())
				radius = radius.toFixed(1);
			L.marker(e.latlng).addTo(map).bindPopup("You are within " + radius + " meters from this point.");//.openPopup();
			L.circle(e.latlng, radius).addTo(map);
			
			$.get("locate.php", function(data) {
				var coords = data.split(", ");
				L.marker(coords).addTo(map).bindPopup("Your device is here!").openPopup();
				map.panTo(coords);
			});
		}
	
		function onLocationError(e) {
			Console.error(e.message.replace("error", "Error"));
		}
		
		map.on('locationfound', onLocationFound);
		map.on('locationerror', onLocationError);
		
		map.locate({setView: true, maxZoom: 16});
	});
})(jQuery);
</script>
</head>
<body<?=$browser['classString']?>>
	<div id="wrapper">
    	<section id="content">
    		<div id="map"></div>
        </section>
    </div>
</body>
</html>