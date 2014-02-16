<?php defined('SYSPATH') or die('No direct script access.');?><!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title><?php echo $core->name; ?></title>
    <link rel="shortcut icon" href="/media/favicon.ico?ver=0.1" /> 
    <?php echo HTML::script('media/jquery-2.0.2.min.js'); ?>
    <?php echo HTML::script('media/jquery.pjax.js'); ?>
    <?php echo HTML::script('media/geo-min.js'); ?>
    <?php echo HTML::script('http://api.map.baidu.com/api?v=2.0&ak='.$core->bd_map_ak); ?>
    <?php echo HTML::style('media/pure-min.css'); ?>
    <?php echo HTML::style('media/awesome/font-awesome.min.css'); ?>
    <?php echo HTML::style('media/default.css'); ?>
</head>
<body>
<div class="pure-g">
  <?php if (isset($alert)): ?>
  <div class="pure-u-1"><?php echo $alert; ?></div>
  <?php endif; ?>
  <div class="pure-u-1">
    <h3><a class="pure-menu-heading"><?php echo $core->name; ?></a></h3>
    <div class="city-selector"><?php echo $city_pretty[$city_id]; ?></div>
  </div>
  <div class="pure-u-1">
    <div class="pure-u-1-6"><?php echo HTML::anchor('home', '新房'); ?></div><div class="pure-u-1-6"><?php echo HTML::anchor('home', '新房'); ?></div><div class="pure-u-1-6"><?php echo HTML::anchor('home', '新房'); ?></div><div class="pure-u-1-6"><?php echo HTML::anchor('home', '新房'); ?></div><div class="pure-u-1-6"><?php echo HTML::anchor('home', '新房'); ?></div><div class="pure-u-1-6"><?php echo HTML::anchor('home', '新房'); ?></div>
  </div>
  <div class="pure-u-1">
    <?php echo Form::open('home/search/');?>
    <?php echo Form::input('key', Arr::get($_GET, 'key', ''), array('method'=>'get')); ?>
    <button class="pure-button">搜索</button>
    <?php echo HTML::anchor('home/search', '高级'); ?>
    </form>
  </div>
  <div class="pure-u-1">
    <?php foreach($city_area as $k=>$v) : ?><div class="pure-u-1-4"><?php echo HTML::anchor('home/search/'.$k.'.html', $v); ?></div><?php endforeach; ?>
  </div>
  <div class="pure-u-1">
    <?php echo HTML::anchor('home/search/'.URL::query(array('output'=>'near'), TRUE), '附近新房'); ?>
    <?php echo HTML::anchor('home/search/'.URL::query(array('output'=>'hot'), TRUE), '推荐新房'); ?>
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
            <?php echo $v->name; ?> 
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
</div>
<script>
  $(function(){
    var is_mobile  = navigator.platform.match(/(iPhone|Android|iPod|iPad)/i)?true:false;
    var need_geo_position = true;
    
    if(need_geo_position && geo_position_js.init()) {
      geo_position_js.getCurrentPosition( function(p) {
          lat = p.coords.latitude;
          lng = p.coords.longitude;
          localtion = p.coords.latitude + ','+p.coords.longitude; 
          $.getJSON('<?php echo URL::site('map/get_city'); ?>?location='+localtion, function(data){
            if(data.error == 0) {
              alert(data.address);
              // window.location.href = '<?php echo URL::site('home/set_citystr'); ?>?str='+data.info;
            }
            else {
              alert(data.info);
            }
          });
        }, function() {
              alert('被拒绝');
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
