<?php defined('SYSPATH') or die('No direct script access.');?><!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <?php echo HTML::script('media/jquery-2.0.2.min.js'); ?>
    <?php echo HTML::script('media/jquery.pjax.js'); ?>
    <?php echo HTML::script('media/geo-min.js'); ?>
    <?php echo HTML::script('media/jquery.cookie.js'); ?>
</head>
<body>
<div class="pure-g">
<div class="pure-u-1" id="map">
</div>
<script>
  $(function(){
    var is_mobile  = navigator.platform.match(/(Arm|iPhone|Android|iPod|iPad)/i)?true:false;
    // var need_geo_position = $.cookie('geo_position');
    if(typeof(need_geo_position) == 'undefined' && geo_position_js.init()) {
      geo_position_js.getCurrentPosition( function(p) {
          lat = p.coords.latitude;
          lng = p.coords.longitude;
          localtion = lat + ','+lng; 
          $.getJSON('<?php echo URL::site('api_map/getgeo'); ?>?localtion='+localtion, function(data){
            if(data.error == 0) {
              $.cookie('geo_position', '1', { expires: 7 });
              $.each(data, function(v){
                alert(v+': '+data[v]);
              })
            }
            else {
              alert('获取失败');
            }
          });
        }, function() {
              alert('位置请求被拒绝');
            }, {enableHighAccuracy:true});
    }
  });
</script>
</body>
</html>
