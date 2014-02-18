<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pure-u-1">
  名称：<?php echo $house->name; ?>
  状态：<?php echo $house->status; ?>
  浏览量：<?php echo $house->hit; ?>
</div>
<div class="pure-u-1">
<?php 
  $localtion = $house->lng.','.$house->lat;
  $map_url = 'http://api.map.baidu.com/staticimage?center='.$localtion.'&width=800&height=300&zoom=11&markers='.$localtion;
  $attachment_1 = Pretty::postgrs_array($house->attachment_1);
  $attachment_2 = Pretty::postgrs_array($house->attachment_2);
  $attachment_3 = Pretty::postgrs_array($house->attachment_3);
  $attachment_4 = Pretty::postgrs_array($house->attachment_4);
  echo HTML::image($map_url);
  echo $attachment_1 ? HTML::image($attachment_1[0], array('style'=>'height: 200px;')):NULL;
  echo $attachment_2 ? HTML::image($attachment_2[0], array('style'=>'height: 200px;')):NULL;
  echo $attachment_3 ? HTML::image($attachment_3[0], array('style'=>'height: 200px;')):NULL;
  echo $attachment_4 ? HTML::image($attachment_4[0], array('style'=>'height: 200px;')):NULL;
?>
</div>
<div class="pure-u-1">
售价：<?php echo $house->price; ?>
<br /> 
历史：<?php echo $house->price_history; ?> 
<br /> 
区域：<?php echo $city_cache[$house->city_id]; ?> - <?php echo $city_cache[$house->city_area]; ?>
<br /> 
地铁：<?php echo $city_cache[$house->underground]; ?> - <?php echo $city_cache[$house->underground_platform]; ?>
<br /> 
开发商：<?php echo $house->house_corp; ?> 
<br /> 
开盘：<?php echo $house->house_date_sale; ?> 
<br /> 
交房：<?php echo $house->house_date; ?> 
<br /> 
划片学区：<?php echo $house->school; ?> 
<br /> 
周边学校：<?php echo $house->school_near; ?> 
</div>
<div class="pure-u-1">
简介：<?php echo $house->description; ?> 
<br /> 
地址：<?php echo $house->address; ?> 

<?php echo HTML::anchor($map_url, '查看地图'); ?>
<?php echo HTML::image($map_url); ?>
<br /> 
户型：
<?php foreach($attachment_1 as $v): ?>
  <?php echo HTML::image($v, array('class'=>'image-demo')); ?>
<?php endforeach; ?>
<br /> 
实景
<?php foreach($attachment_2 as $v): ?>
  <?php echo HTML::image($v, array('class'=>'image-demo')); ?>
<?php endforeach; ?>
<br /> 
样板
<?php foreach($attachment_3 as $v): ?>
  <?php echo HTML::image($v, array('class'=>'image-demo')); ?>
<?php endforeach; ?>
<br /> 
规划
<?php foreach($attachment_4 as $v): ?>
  <?php echo HTML::image($v, array('class'=>'image-demo')); ?>
<?php endforeach; ?>
</div>
<div class="pure-u-1">
首页>新房> <?php echo $house->name; ?> > 正文
</div>
<div class="pure-u-1">
<div class="pure-u-1-5">
  <?php 
  $phone = Pretty::postgrs_array($house->phone);
  echo HTML::anchor(isset($phone[0])?$phone[0]:'#', '电话'); ?>
</div><div class="pure-u-1-5">
  <?php echo HTML::anchor('', '导航'); ?>
</div><div class="pure-u-1-5">
  <?php echo HTML::anchor('house_faq/index/'.$house->hid.'.html', '问答'); ?>
</div><div class="pure-u-1-5">
  <?php echo HTML::anchor(isset($phone[1])?$phone[1]:'#', '电话'); ?>
</div><div class="pure-u-1-5">
  <?php echo HTML::anchor(isset($phone[2])?$phone[2]:'#', '电话'); ?>
</div>
</div>
