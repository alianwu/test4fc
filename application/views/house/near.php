 <?php defined('SYSPATH') or die('No direct script access.');?>
<div id="map_address">位置信息加载中...</div>
<div id="map_info" style="color:red;"></div>
<div id="map" style="width: 100%; height: 800px;">地图信息加载中...</div>
<script>
  var map = new BMap.Map("map");
  var point;
  var here;
  var house;
  var zoom = 15;
  var zoom_min = 10;
  var lat = '<?php echo Arr::get($_GET, 'lat', $city_lat); ?>';
  var lng = '<?php echo Arr::get($_GET, 'lng', $city_lng); ?>';
  var radius = <?php echo (int) Arr::get($_GET, 'radius', 2500); ?>;

  function initialize_map(lng, lat, radius, zoom) {
    if (zoom < zoom_min) {
      $('#map_info').html('继续扩大搜索已没有意义。特禁止继续');
      return 0;
    }
    point = new BMap.Point(lng, lat);
    marker_here(point);
    map.centerAndZoom(point, zoom);

    $.getJSON('<?php echo URL::site('api_house/near'); ?>?lat='+lat+'&lng='+lng+'&radius='+radius, function(data){
      if (data.error == 0){
        map.clearOverlays();
        num = data.data.length;
        $('#map_info').html('当前位置方圆 ' + radius+ ' 米内发现房源 '+ num +' 处, 点击可成倍扩大搜索范围');
        $('#map_info').click(function(){
          zoom = zoom - 1;
          radius = radius * 2;
          initialize_map(lng, lat, radius, zoom);
        });
        $.each(data.data, function(k, v){
           bdmap_marker(v.lng, v.lat, v.name, false);
        }); 
      }
      else {
        $('#map_info').html('当前位置方圆 ' + radius+ ' 米内没有房源存在, 点击错误可成倍扩大搜索范围');
        $('#map_info').click(function(){
          zoom = zoom - 1;
          radius = radius * 2;
          initialize_map(lng, lat, radius, zoom);
        });
      }
    })
  }
  
  function bdmap_marker(lng, lat, name, ishere) {
    point = new BMap.Point(lng, lat);
    marker = new BMap.Marker(point); 
    if (ishere) {
      if (here) {
        map.removeOverlay(here); 
      }
      here = marker;
    }
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

  function marker_here(point)
  {
    BMap.Convertor.translate(point, 0, function(p){
      $.getJSON('<?php echo URL::site('api_map/getgeo'); ?>?localtion='+p.lat+','+p.lng, function(data){
        if (data.error == 0) {
          $('#map_address').html('当前所在位置: '+data.address+' 街道:'+data.street);
             bdmap_marker(data.lng, data.lat, '当前所在位置: <br />'+data.address, true);
        }
        else {
          alert('位置信息获取失败');
        }
      });
    });
  }

  initialize_map(lng, lat, radius, zoom);
  map.enableScrollWheelZoom(true);

  isgeo = geo_position_js.init();
  if (isgeo) {
    window.setInterval(function(){
      geo_position_js.getCurrentPosition( function(p) {
          if (lat != p.coords.latitude || lng != p.coords.longitude)
          {
            lat = p.coords.latitude;
            lng = p.coords.longitude;
            point  = new BMap.Point(lng, lat);
            marker_here(point);
          }
        }, function(e) {
            alert(e.message);
        }, { enableHighAccuracy:true }
      );
    }, 5000);
  }
</script>
