 <?php defined('SYSPATH') or die('No direct script access.');?>
  <div class="pure-u-1">
    <?php echo Form::open('house/search/', array('method' => 'get'));?>
    <?php echo Form::input('key', Arr::get($_GET, 'key', ''), array('method'=>'get')); ?>
    <button class="pure-button">搜索</button>
    <?php echo HTML::anchor('house/search', '高级'); ?>
    </form>
  </div>
  <div class="pure-u-1">
    <?php foreach($city_area as $k=>$v) : ?><div class="pure-u-1-4"><?php echo HTML::anchor('home/search/'.$k.'.html', $v); ?></div><?php endforeach; ?>
  </div>
  <div class="pure-u-1">
    <?php echo HTML::anchor('house/search/'.URL::query(array('output'=>'near')), '附近新房'); ?>
    <?php echo HTML::anchor('house/search/'.URL::query(array('output'=>'hot')), '推荐新房'); ?>
    <?php echo HTML::anchor('house/news/', '新房资讯'); ?>
    <?php echo HTML::anchor('house/acitve/', '新房活动'); ?>
    <?php echo HTML::anchor('house/', '列表'); ?>
    <?php echo HTML::anchor('house/'.URL::query(array('result'=>'map')), '地图'); ?>
  </div>
  <div class="pure-u-1">
    <?php if ( isset($house) && $house) : ?> 
      <?php if ( ($result = Arr::get($_GET, 'result', '')) == 'map' ) : ?> 
        <?php echo HTML::script('http://api.map.baidu.com/api?v=2.0&ak='.$core->bd_map_ak); ?>
        <div id="map" style="width: 100%; height: 600px;">地图加载中...</div>
        <script>
          var map = new BMap.Map("map");
          var point;
          var geo = new Array();
          point = new BMap.Point(<?php echo $city_lng; ?>, <?php echo $city_lat; ?>);
          <?php $index= 0; foreach($house as $v) :?>
            geo[<?php echo $index; ?>] = new Array('<?php echo $v->lng; ?>', '<?php echo $v->lat; ?>', '<?php echo $v->name; ?>');
          <?php $index++; endforeach; ?>
          map.centerAndZoom(point, 12);
          $.each(geo, function(k, v){
            bdmap_marker(v[0], v[1], v[2]);
          })
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
      <?php else: ?>
      <?php foreach($house as $v): ?>
        <div class="pure-u-1-3">
          <div class="pure-u">
          <?php echo HTML::image('media/unkown.gif'); ?>
          </div>
          <div class="pure-u">
            <?php echo $v->hid; ?> 
            <?php echo HTML::anchor('house/detail/'.$v->hid.'.html', $v->name); ?> 
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
      <?php endif; ?>
    <?php else: ?>
    <span class="info">没有数据</span>
    <?php endif; ?>
  </div>
