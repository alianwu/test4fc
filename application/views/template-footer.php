<script>
  var cookie_geo = 'geo_position_2';
  var now = new Date();

  $(function(){
    c_g = $.cookie(cookie_geo);
    if(c_g == undefined) {
      if (geo_position_js.init()) {
        geo_position_js.getCurrentPosition( function(p) {
            lat = p.coords.latitude;
            lng = p.coords.longitude;
            localtion = lat + ',' + lng;
            $.getJSON('<?php echo URL::site('api_city/set_city'); ?>?localtion='+localtion, function(data){
              if(data.error == 0) {
                if (data.data != 0) {
                  window.location.reload();
                }
              }
              else {
                alert('没有当前城市记录'+localtion);
              }
            });
          }, function(e) {
              alert(e.message);
          }, { enableHighAccuracy:true }
        );
      }
      $.cookie(cookie_geo, now.getTime(), { expires: 7 });
    }
    else {
      // alert('error');
    }



    var is_mobile  = navigator.platform.match(/(Arm|iPhone|Android|iPod|iPad)/i)?true:false;
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
