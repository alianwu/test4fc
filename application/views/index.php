  <div class="pure-u-1">
    <?php echo Form::open('house/search/');?>
    <?php echo Form::input('key', Arr::get($_GET, 'key', ''), array('method'=>'get')); ?>
    <button class="pure-button">搜索</button>
    <?php echo HTML::anchor('house_home/search', '高级'); ?>
    </form>
  </div>
  <div class="pure-u-1">
    <?php foreach($city_area as $k=>$v) : ?><div class="pure-u-1-4"><?php echo HTML::anchor('search'.URL::query(array('city_area'=> $k, 'type'=>1), TRUE), $v); ?></div><?php endforeach; ?>
  </div>
  <div class="pure-u-1">
    <?php echo HTML::anchor('search'.URL::query(array('output'=>'near'), TRUE), '附近新房'); ?>
    <?php echo HTML::anchor('search'.URL::query(array('output'=>'hot'), TRUE), '推荐新房'); ?>
  </div>
  <div class="pure-u-1">
    <?php if ( isset($house) && $house) : ?> 
      <?php foreach($house as $v): ?>
        <div class="pure-u-1-3">
          <div class="pure-u">
          <?php echo HTML::image('media/unkown.gif'); ?>
          </div>
          <div class="pure-u">
            <?php echo $v->hid; ?> 
            <?php echo HTML::anchor('house_home/detail/'.$v->hid.'.html', $v->name); ?> 
            <?php echo HTML::anchor('#', '周边学校', array('class'=>'school', 'data'=>$v->school_near)); ?> <br />
            地址：<?php echo $v->address; ?> <br />
            划片学校：<?php echo $v->school; ?> <br />
          </div>
        </div><div class="pure-u-1-3">
            开盘：<?php echo $v->house_date; ?> <br />
            交房：<?php echo $v->house_date_sale; ?> <br />
        </div><div class="pure-u-1-3">
            <?php echo HTML::anchor('tel://'.$v->phone_1, '电话', array('class'=>'phone', 'data-phone'=>$v->phone_1)); ?> <br />
            均价：<?php echo $v->price; ?> <br />
        </div>
      <?php endforeach; ?>
    <?php else: ?>
    <span class="info">没有数据</span>
    <?php endif; ?>
  </div>
  <div class="pure-u-1">
    <?php echo HTML::anchor('#', '精准房源', array('id'=>'house_near')); ?>
  </div>
<script>
$(function(){
  $('#house_near').click(function(){
    if (geo_position_js.init()) {
      geo_position_js.getCurrentPosition( function(p) {
          var lat = p.coords.latitude;
          var lng = p.coords.longitude;
          localtion = lat + ',' + lng;
          $.getJSON('<?php echo URL::site('api_city/set_city'); ?>?localtion='+localtion, function(data){
            if(data.error == 0) {
              window.location.href = '<?php echo URL::site('house_home/near'); ?>?lat=' + lat + '&lng=' + lng + '&radius=2500';
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
    else {
      alert('error');
    }
    return false;
  });
});
</script>
</div>
