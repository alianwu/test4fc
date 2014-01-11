<?php defined('SYSPATH') or die('No direct script access.'); ?>
<ol>
  <li>
    语言测试
    <ul>
      <li><?php echo HTML::anchor('language/', '语言测试'); ?></li></li>
    </ul>
  <li>
    表单测试
    <ul>
      <li><?php echo HTML::anchor('form/post', '提交表单'); ?></li>
    </ul>
  </li>
  <li>
    Session会话
    <ul>
      <li><?php echo HTML::anchor('session/redis', 'redis会话测试'); ?></li>
    </ul>
  </li>
</ol>
<a href="/">返回 索引</a>