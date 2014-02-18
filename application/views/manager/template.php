<?php defined('SYSPATH') or die('No direct script access.');?><!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title><?php echo $core->name; ?></title>
    <link rel="shortcut icon" href="/media/favicon.ico?ver=0.1" /> 
    <?php echo HTML::script('media/jquery-2.0.2.min.js'); ?>
    <?php echo HTML::script('media/jquery.pjax.js'); ?>
    <?php echo HTML::script('http://api.map.baidu.com/api?v=2.0&ak='.$core->bd_map_ak); ?>
    <?php echo HTML::script('media/editor/kindeditor-min.js'); ?>
    <?php echo HTML::script('media/swfupload/swfupload.js'); ?>
    <?php echo HTML::script('media/swfupload/swfupload.queue.js'); ?>
    <?php echo HTML::script('media/swfupload/fileprogress.js'); ?>
    <?php echo HTML::script('media/swfupload/handlers.js'); ?>
    <?php echo HTML::style('media/pure-min.css'); ?>
    <?php echo HTML::style('media/awesome/font-awesome.min.css'); ?>
    <?php echo HTML::style('media/manager.css'); ?>
</head>
<body>
<div class="pure-g">
  <div class="main-menu">
    <div class="pure-u-1">
      <h3><a class="pure-menu-heading"><?php echo $core->name; ?></a></h3>
      <dl>
        <dt>城市</dt>
        <dd>
        当前选择：<?php echo  Form::select('city', $city_pretty, $city_id, array('id'=>'city-selector')); ?>
        </dd>
        <dt>用户</dt>
        <dd>
          管理员: <?php echo $user['name'] <> ''?$user['name']:'未设定'; ?><br />
          <?php echo HTML::anchor('manager_user', '资料'); ?>
          <?php echo HTML::anchor('manager_user/logout', '注销'); ?> <br />
        </dd>
        <dt>新房</dt>
        <dd>
          <?php echo HTML::anchor('manager_house_home', '列表'); ?>
          <?php echo HTML::anchor('manager_house_home/add', '增加'); ?>
          <?php echo HTML::anchor('manager_house_faq', '问答'); ?>
        </dd>
        <dt>资讯</dt>
        <dd>
          <?php echo HTML::anchor('manager_article_home', '列表'); ?>
          <?php echo HTML::anchor('manager_article_home/add', '增加'); ?>
          <?php echo HTML::anchor('manager_article_category', '分类'); ?>
        </dd>
        <dt>系统</dt>
        <dd>
          <?php echo HTML::anchor('manager_system_setting', '设置'); ?>
          <?php echo HTML::anchor('manager_system_city', '城市'); ?>
          <?php echo HTML::anchor('manager_system_user', '用户'); ?>
        </dd>
      </dl>
      <p><?php echo $core->version; ?></p>    
    </div>
  </div>
  <div class="main-container">
    <div class="pure-u-1">

      <?php if (isset($message)) :?>
        <?php if (isset($message['error']) ) :?>
        <div class="info info-<?php echo $message['error'] ? 'error': 'success'; ?>">
          <?php  echo ( isset($message['info']) && $message['info'] <> '' ) ? $message['info'] : ( $message['error'] ? '执行错误' : '执行成功'  ); ?>
        </div>
        <?php else: ?>
        <div class="info info-warning"><?php  echo $message; ?></div>
        <?php endif;?>
      <?php elseif (isset($error['csrf'])) :?>
        <div class="info info-error">程序禁止此类数据提交</div>
      <?php endif;?>

      <?php if (isset($container)): echo $container; endif; ?> 
    </div>
  </div>
</div>
<script>
  $(function(){
    $('#city-selector').change(function(){ 
      city_id = $(this).val(); 
      window.location.href='<?php echo URL::site('manager_home/set_cityid'); ?>/'+city_id; 
    });
    $.pjax({
        selector: 'a.pjax', container: '#detail', show: 'fade', cache: false,storage: true
    });
  });
</script>
</body>
</html>
