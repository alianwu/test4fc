<?php defined('SYSPATH') or die('No direct script access.');?>
<h4>新房及其相关参数</h4>
<hr />

<?php echo HTML::anchor('manager_house_home', '列表', array('class'=>'pure-button')); ?> 
<?php echo HTML::anchor('manager_house_home/add', '添加', array('class'=>'pure-button')); ?>
 
<?php echo Form::open('manager_house_home/search', array('class'=>'pure-u', 'method'=>'get')); ?> 
    <div class="pure-control-group">
        <label><?php echo Form::select('city_area', $city_area, Arr::get($_GET, 'city_area')); ?></label>
        <?php echo Form::input('key', Arr::get($_GET, 'key', ''), array('id'=>'key')); ?>
        <button type="submit" class="pure-button pure-button-primary">搜索</button>
        <?php if(isset($error['rate_2'])): ?><span class="info-error"><?php echo $error['rate_2']; ?></span><?php endif; ?>
    </div>
</form>
<br />
<br />
<?php if (isset($detail)) : ?>
  <?php echo $detail; ?>
<?php else: ?>
  页面开发中....
<?php endif; ?>
