<?php defined('SYSPATH') or die('No direct script access.'); ?>
<ol>
  <li>
    语言测试
    <ul>
      <li><?php echo HTML::anchor('test_language/', '语言测试'); ?></li></li>
    </ul>
  </li>
  <li>
    表单测试
    <ul>
      <li><?php echo HTML::anchor('test_form/post', '提交表单'); ?></li>
    </ul>
  </li>
  <li>
    Session会话
    <ul>
      <li><?php echo HTML::anchor('test_session/redis', 'redis会话测试'); ?></li>
    </ul>
  </li>
  <li>
    缓存测试
    <ul>
      <li><?php echo Model_Test_Date::time(); ?></li></li>
      <li><?php echo HTML::anchor('test_cache/redis', 'redis缓存测试'); ?></li>
    </ul>
  </li>
</ol>