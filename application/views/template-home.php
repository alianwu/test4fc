<?php defined('SYSPATH') or die('No direct script access.');?>
<?php echo View::factory('template-header'); ?>
  <div class="pure-u-1">
    <h3><a class="pure-menu-heading"><?php echo $core->name; ?></a></h3>
    <div class="city-selector"><?php echo $city_pretty[$city_id]; ?></div>
    <div class="city-info">
      <ul>
      <?php foreach($city_pretty as $k => $v) :?>
      <li><?php echo $k, $v; ?></li>
      <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="pure-u-1">
    <div class="pure-u-1-6"><?php echo HTML::anchor('house', '新房'); ?></div>
  </div>
  <div class="pure-u-1">
  <?php if (isset($container)) : ?>
  <?php echo $container; ?>
  <?php else: ?>
    <div class="info">页面不存在哦</div>
  <?php endif; ?>
  </div>
<?php echo View::factory('template-footer'); ?>
