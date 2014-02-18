<?php defined('SYSPATH') or die('No direct script access.');?>
<?php echo View::factory('template-header'); ?>
  <div class="pure-u-1">
    <div class="city-selector"><?php echo $city_pretty[$city_id]; ?></div>
    <div class="city-info">
      <ul>
      <?php foreach($city_pretty as $k => $v) :?>
      <li><?php echo $k, $v; ?></li>
      <?php endforeach; ?>
      </ul>
    </div>
    <div class="user-pannel">
    <?php echo HTML::anchor('search', '搜索'); ?>
    <?php echo HTML::anchor('user', '用户'); ?>
    </div>
  </div>
  <div class="pure-u-1">
  <?php if (isset($container)) : ?>
  <?php echo $container; ?>
  <?php else: ?>
    <div class="info">页面不存在哦</div>
  <?php endif; ?>
  </div>
<?php echo View::factory('template-footer'); ?>
