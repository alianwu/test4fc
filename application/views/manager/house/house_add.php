<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pure-u-1">
  <?php echo Form::open('manager_house_home/update' . URL::query(NULL, TRUE), array('class'=>'pure-form pure-form-aligned')); ?>
  <fieldset>
    <legend>添加 || 更新</legend>
    <div class="pure-control-group">
        <label>小区名称</label>
        <?php echo Form::input('name', Arr::get($_POST, 'name', ''), array('id'=>'name')); ?>
        状态
        <?php echo Form::input('status', Arr::get($_POST, 'status', ''), array('id'=>'status')); ?>
        热度
        <?php echo Form::checkbox('hot', 1,  Arr::get($_POST, 'hot', 0) == 1, array('id'=>'hot')); ?>
        <?php if(isset($error['name'])): ?><span class="info-error"><?php echo $error['name']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>均价</label>
        <?php echo Form::input('price', Arr::get($_POST, 'price', ''), array('id'=>'price')); ?>
        <?php if(isset($error['price'])): ?><span class="info-error"><?php echo $error['price']; ?></span><?php endif; ?>
        单位：元/平方米
    </div>
    <div class="pure-control-group">
        <label>历史价格</label>
        <?php echo Form::input('price_history', Arr::get($_POST, 'price_history', ''), array('id'=>'price')); ?>
        <?php if(isset($error['price'])): ?><span class="info-error"><?php echo $error['price']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>联系电话</label>
        <?php echo Form::input('phone', str_replace(array('{', '}'), '',  Arr::get($_POST, 'phone', '')), array('id'=>'phone')); ?>
        <?php if(isset($error['phone'])): ?><span class="info-error"><?php echo $error['phone']; ?></span><?php endif; ?>
        以英文半角","分割多个电话
    </div>
    <div class="pure-control-group">
        <label>入住时间</label>
        <?php echo Form::input('house_date', Arr::get($_POST, 'house_date', ''), array('id'=>'house_date')); ?>
        <?php if(isset($error['house_date'])): ?><span class="info-error"><?php echo $error['house_date']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>销售时间</label>
        <?php echo Form::input('house_date_sale', Arr::get($_POST, 'house_date_sale', ''), array('id'=>'house_date_sale')); ?>
        <?php if(isset($error['house_date_sale'])): ?><span class="info-error"><?php echo $error['house_date_sale']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>开发商</label>
        <?php echo Form::input('house_corp', Arr::get($_POST, 'house_corp', ''), array('id'=>'house_corp')); ?>
        <?php if(isset($error['house_corp'])): ?><span class="info-error"><?php echo $error['house_corp']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>投资商</label>
        <?php echo Form::input('house_speculator', Arr::get($_POST, 'house_speculator', ''), array('id'=>'house_speculator')); ?>
        <?php if(isset($error['house_speculator'])): ?><span class="info-error"><?php echo $error['house_speculator']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>物业公司</label>
        <?php echo Form::input('house_service', Arr::get($_POST, 'house_service', ''), array('id'=>'house_service')); ?>
        <?php if(isset($error['house_service'])): ?><span class="info-error"><?php echo $error['house_service']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>物业费</label>
        <?php echo Form::input('house_service_price', Arr::get($_POST, 'house_service_price', ''), array('id'=>'house_service_price')); ?>
        <?php if(isset($error['house_service_price'])): ?><span class="info-error"><?php echo $error['house_service_price']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>占地面积</label>
        <?php echo Form::input('acreage', Arr::get($_POST, 'acreage', ''), array('id'=>'acreage')); ?>
        <?php if(isset($error['acreage'])): ?><span class="info-error"><?php echo $error['acreage']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>建筑面积</label>
        <?php echo Form::input('acreage_building', Arr::get($_POST, 'acreage_building', ''), array('id'=>'acreage_building')); ?>
        <?php if(isset($error['acreage_building'])): ?><span class="info-error"><?php echo $error['acreage_building']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>规划户数</label>
        <?php echo Form::input('copy', Arr::get($_POST, 'copy', ''), array('id'=>'copy')); ?>
        <?php if(isset($error['copy'])): ?><span class="info-error"><?php echo $error['copy']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>车位数</label>
        <?php echo Form::input('car', Arr::get($_POST, 'car', ''), array('id'=>'car')); ?>
        <?php if(isset($error['car'])): ?><span class="info-error"><?php echo $error['car']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>容积率</label>
        <?php echo Form::input('rate_1', Arr::get($_POST, 'rate_1', ''), array('id'=>'rate_1')); ?>
        <?php if(isset($error['rate_1'])): ?><span class="info-error"><?php echo $error['rate_1']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>绿化率</label>
        <?php echo Form::input('rate_2', Arr::get($_POST, 'rate_2', ''), array('id'=>'rate_2')); ?>
        <?php if(isset($error['rate_2'])): ?><span class="info-error"><?php echo $error['rate_2']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>划片学校</label>
        <?php echo Form::input('school', Arr::get($_POST, 'school', ''), array('id'=>'school')); ?>
        <?php if(isset($error['school'])): ?><span class="info-error"><?php echo $error['school']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>周边学校</label>
        <?php echo Form::input('school_near', Arr::get($_POST, 'school_near', ''), array('id'=>'school_near')); ?>
        <?php if(isset($error['school_near'])): ?><span class="info-error"><?php echo $error['school_near']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>区域</label>
        <?php echo Form::select('city_area', $city_area? (array(0 => '请选择') + $city_area) : array(0=>'没有数据'), $ca = Arr::get($_POST, 'city_area', 0), array('id'=>'city_area')); ?>
        <?php if(isset($error['city_area'])): ?><span class="info-error"><?php echo $error['city_area']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>商圈</label>
        <?php echo Form::select('city_area_shop', array(0 => '没有数据'), $cas = Arr::get($_POST, 'city_area_shop', 0), array('id'=>'city_area_shop')); ?>
        <?php if(isset($error['city_area_shop'])): ?><span class="info-error"><?php echo $error['city_area_shop']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>地铁</label>
        <?php echo Form::select('underground', $underground ? (array(0=>'请选择') + $underground) : array('0'=>'没有数据'), $u = Arr::get($_POST, 'underground', 0), array('id'=>'underground')); ?>
        <?php if(isset($error['underground'])): ?><span class="info-error"><?php echo $error['underground']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>地铁站</label>
        <?php echo Form::select('underground_platform', array(0 => '没有数据'), $up =  Arr::get($_POST, 'underground_platform', 0), array('id'=>'underground_platform')); ?>
        <?php if(isset($error['underground_platform'])): ?><span class="info-error"><?php echo $error['underground_platform']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>建筑类型</label>
        <?php echo Form::select('building', $setting->building,  Arr::get($_POST, 'building', 0), array('id'=>'building')); ?>
        <?php if(isset($error['building'])): ?><span class="info-error"><?php echo $error['building']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>装饰类型</label>
        <?php echo Form::select('decorate', $setting->decorate,  Arr::get($_POST, 'decorate', 0), array('id'=>'decorate')); ?>
        <?php if(isset($error['decorate'])): ?><span class="info-error"><?php echo $error['decorate']; ?></span><?php endif; ?>
    </div>

    <div class="pure-control-group">
        <label for="address">小区地址</label>
        <?php echo Form::input('address', Arr::get($_POST, 'address'), array('id'=>'address', 'placeholder'=>'请输入数据')); ?>
        <?php if(isset($error['address'])): ?><span class="info-error"><?php echo $error['address']; ?></span><?php endif; ?> 
        <a href="javascript:return void(0);" id='map-selector' class="pure-button pure-button-primary">标注位置</a>
        
        <div class="pure-control-content">
          <div id="map" style="height: 200px; width: 500px; display: block;"></div>
          <?php echo Form::hidden('lat', $lat = Arr::get($_POST, 'lat', $city_lat), array('id'=>'lat')); ?>
          <?php echo Form::hidden('lng', $lng = Arr::get($_POST, 'lng', $city_lng), array('id'=>'lng')); ?>
        </div>
    </div>
    <div class="pure-control-group">
        <label for="description">简介</label>
        编辑时，请注意保存
        <div class="pure-control-content">
        <?php echo Form::textarea('description', Arr::get($_POST, 'description'), array('id'=>'description', 'placeholder'=>'请输入数据')); ?>
        <?php if(isset($error['description'])): ?><span class="info-error"><?php echo $error['description']; ?></span><?php endif; ?>
        </div>
    </div>
    <div class="pure-control-group">
        <label>权重</label>
        <?php echo Form::input('weight', Arr::get($_POST, 'weight', 0), array('id'=>'weight')); ?>
        <?php if(isset($error['weight'])): ?><span class="info-error"><?php echo $error['weight']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label>显示</label>
        <?php echo Form::checkbox('display', 1,  Arr::get($_POST, 'display')==1 ? TRUE : FALSE, array('id'=>'display')); ?>
        <?php if(isset($error['display'])): ?><span class="info-error"><?php echo $error['display']; ?></span><?php endif; ?>
    </div>
    <div class="pure-controls">
        <?php echo Form::hidden('hid', Arr::get($_POST, 'hid', 0)); ?>
        <?php echo Form::hidden('city_id', $city_id); ?>
        <?php echo Form::hidden('csrf', $token); ?>
        <button type="submit" class="pure-button pure-button-primary">确认</button>
    </div>
  </fieldset>
  </form>
</div>
<script>
$(function(){

  
  var underground = '<?php echo $u; ?>';
  var underground_platform = '<?php echo $up; ?>';
  var city_area = '<?php echo $ca; ?>';
  var city_area_shop = '<?php echo $cas; ?>';

  function city_list_init(c, selected_c, type, domid) {
    $.getJSON('<?php echo URL::site("api_city/get_city"); ?>?type='+type+'&cid='+c, function(data){ 
      if(data.length != 0) {
        $(domid).empty();
        $.each(data, function(k, v){
          selected = '';
          if (selected_c == k) {
            selected = ' selected="selected"';
          }
          $(domid).append('<option value='+k+selected+'>'+v+'</option>');
        });
      }
      else {
        $(domid).empty();
        $(domid).append('<option value=“0”>没有数据返回</option>');
      }
    });
  }

  if ( city_area != '0' && city_area_shop != '0') {
    city_list_init(city_area, city_area_shop, 6, '#city_area_shop');
  }
  if (underground != '0' && underground_platform != '0') {
    city_list_init(underground, underground_platform, 3, '#underground_platform');
  }

  $('#underground').change(function(){
    cid = $(this).val();
    city_list_init(cid, '', 3, '#underground_platform');
  });

  $('#city_area').change(function(){
    cid = $(this).val();
    city_list_init(cid, '', 6, '#city_area_shop');
  });

  var editor;
  KindEditor.ready(function(K) {
    editor = K.create('textarea[name="description"]', {
      resizeType : 1,
      allowPreviewEmoticons : false,
      allowImageUpload : false,
      items : [
        'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
        'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
        'insertunorderedlist', '|', 'emoticons', 'image', 'link']
    });
  });

  var map = new BMap.Map("map");
  var marker;
  var point;
  point = new BMap.Point(<?php echo $lng; ?>, <?php echo $lat; ?>);
  map.centerAndZoom(point, 12);
  map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件

  function bdmap_marker(lng, lat) {
    map.clearOverlays();
    marker = new BMap.Marker(new BMap.Point(lng, lat)); 
    map.addOverlay(marker);  
  }

  bdmap_marker(<?php echo $lng; ?>, <?php echo $lat; ?>);

  function bdmap_click(e) {
    bdgps = e.point.lat + "," + e.point.lng;
    $('#lng').val(e.point.lng);
    $('#lat').val(e.point.lat);
    $.getJSON('<?php echo URL::site('api_map/getgeo'); ?>?localtion=' + bdgps,
        function(data){
          if (data.error) {
            alert(data.info);
            return 0;
          }
          else {
            $('#address').val(data.address);
          }
          bdmap_marker(e.point.lng, e.point.lat);
        }
    );
  }
  map.addEventListener("click", bdmap_click); 
  map.enableScrollWheelZoom(true);

  $('#map-selector').click(function() {
    address = $('#address').val();
    if (address) {
      $.getJSON('<?php echo URL::site('api_map/getgeo'); ?>?reverse=true&localtion='+address, function(data){
        if(data.error == 0) {
          point = new BMap.Point(data.lng, data.lat);
          map.panTo(point);
          bdmap_marker(data.lng, data.lat);
        }
        else {
          alert('无相关结果');
        }
        
      });
    }
  });

 });
</script>
