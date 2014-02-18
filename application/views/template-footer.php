<script>
  $(function(){
    var is_mobile  = navigator.platform.match(/(Arm|iPhone|Android|iPod|iPad)/i)?true:false;
    var need_geo_position = $.cookie('need_geo_position');
    if(typeof(need_geo_position) == 'undefined' && geo_position_js.init()) {
      geo_position_js.getCurrentPosition( function(p) {
          lat = p.coords.latitude;
          lng = p.coords.longitude;
          localtion = p.coords.latitude + ','+p.coords.longitude; 
          $.getJSON('<?php echo URL::site('map/getaddress'); ?>?location='+localtion, function(data){
            if(data.error == 0) {
              $.cookie('need_geo_position', '1', { expires: 7 });
              window.location.href = '<?php echo URL::site('home/set_citystr'); ?>?str='+data.city;
            }
            else {
              alert(data.info);
            }
          });
        }, function() {
              $.cookie('need_geo_position', '1', { expires: 1 });
              alert('位置请求被被拒绝');
            }, {enableHighAccuracy:true});
    }
    if (is_mobile == false) {
      $('.phone').click(function(){
        alert( $(this).attr('data-phone'));
        return false;
      });
    }
    $('#city-selector').change(function(){ 
      city_id = $(this).val(); 
      window.location.href='<?php echo URL::site('home/set_cityid'); ?>/'+city_id; 
    });
    $.pjax({
        selector: 'a.pjax', container: '#detail', show: 'fade', cache: false,storage: true
    });
  });
</script>
</body>
</html>
