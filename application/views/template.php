<?php defined('SYSPATH') or die('No direct script access.');?><!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title>测试用例</title>
    <link rel="shortcut icon" href="/media/favicon.ico?ver=0.1" /> 
    <?php echo HTML::script(STATIC_SITE_URL .'js/jquery-2.0.2.min.js'); ?>
    <?php echo HTML::script(STATIC_SITE_URL .'js/jquery.pjax.js'); ?>
    <?php echo HTML::style(STATIC_SITE_URL .'css/pure-min.css'); ?>
    <?php echo HTML::style(STATIC_SITE_URL .'css/awesome/font-awesome.min.css'); ?>
    <?php echo HTML::style('media/default.css'); ?>
</head>
<body>
<div class="pure-g">
  <div class="pure-u">
    <div class="pure-u-1-2">
      <?php if (isset($content)): echo $content; endif; ?> 
    </div><div class="pure-u-1-2" id="detail">
      <?php if (isset($detail)): echo $detail; endif; ?> 
    </div><div class="pure-u-1">
      <?php echo View::factory('profiler/stats'); ?>
    </div>    
  </div>
</div>
<script>
  $(function(){
    $.pjax({
        selector: 'a',
        container: '#detail',
        show: 'fade', 
        cache: false,
        storage: true
    }) 
  });
</script>
</body>
</html>
