<?php defined('SYSPATH') or die('No direct script access.');?>
<h4>城市及其相关参数</h4>
<hr />

<?php echo HTML::anchor('manager_system_city', '城市', array('class'=>'pure-button')); ?> 
<?php echo HTML::anchor('manager_system_city/add'.URL::query(array('type'=>1)), '添加城市', array('class'=>'pure-button')); ?>

<br />
<br />

<?php if (isset($detail)) : ?>
  <?php echo $detail; ?>
<?php else: ?>
  页面开发中....
<?php endif; ?>
