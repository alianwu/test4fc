<?php defined('SYSPATH') or die('No direct script access.');?><!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title>用户中心</title>
    <link rel="shortcut icon" href="/public/favicon.ico?ver=0.1" /> 
    <?php echo HTML::script('public/js/jquery-2.0.2.min.js'); ?>
    <?php echo HTML::style('public/css/pure-min.css'); ?>
    <?php echo HTML::style('public/css/awesome/font-awesome.min.css'); ?>
    <?php echo HTML::style('public/css/accounts.css'); ?>
</head>
<body>
<div class="pure-g">
  <div class="pure-u-1 pannel"></div>
  <div class="pure-u-1 header">
    <h1>普华网</h1>
  </div>
  <?php if (isset($content)): echo $content; endif; ?> 
  <div class="pure-u-1 footer">
  版权所有 (C) 2013
  </div>    
</div>
</body>
</html>
