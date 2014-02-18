<?php defined('SYSPATH') or die('No direct script access.');?><!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title><?php echo $core->name; ?></title>
    <link rel="shortcut icon" href="/media/favicon.ico?ver=0.1" /> 
    <?php echo HTML::script('media/jquery-2.0.2.min.js'); ?>
    <?php echo HTML::script('media/jquery.pjax.js'); ?>
    <?php echo HTML::script('media/geo-min.js'); ?>
    <?php echo HTML::script('media/jquery.cookie.js'); ?>
    <?php echo HTML::script('http://api.map.baidu.com/api?v=2.0&ak='.$core->bd_map_ak); ?>
    <?php echo HTML::style('media/pure-min.css'); ?>
    <?php echo HTML::style('media/awesome/font-awesome.min.css'); ?>
    <?php echo HTML::style('media/default.css'); ?>
</head>
<body>
<div class="pure-g">
  <?php if (isset($alert) && empty($alert) == FALSE): ?>
  <div class="pure-u-1"><?php echo $alert; ?></div>
  <?php endif; ?>
