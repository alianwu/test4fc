 <?php defined('SYSPATH') or die('No direct script access.');?>
<div id="map" style="width: 100%; height: 600px;">地图加载中...</div>
<script>
  var map = new BMap.Map("map");
  var point;
  point = new BMap.Point(<?php echo $city_lng; ?>, <?php echo $city_lat; ?>);
  map.centerAndZoom(point, 12);
  function bdmap_marker(lng, lat, name) {
    marker = new BMap.Marker(new BMap.Point(lng, lat)); 
    map.addOverlay(marker);  
    marker.addEventListener("click", function(){          
      info = name;
      infoWindow = new BMap.InfoWindow(info); 
      this.openInfoWindow(infoWindow);
      document.getElementByTagName('test').onload = function (){
         infoWindow.redraw(); 
     };
    });
  }
  map.enableScrollWheelZoom(true);
</script>
