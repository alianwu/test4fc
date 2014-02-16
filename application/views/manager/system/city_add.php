<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pure-u-1">
  <?php echo Form::open('manager_system_city/update' . URL::query(NULL, TRUE), array('class'=>'pure-form pure-form-aligned')); ?>
  <fieldset>
    <legend><?php echo $setting['type'][$type]; ?> -> 添加 || 更新</legend>
    <div class="pure-control-group">
        <label for="name">内容</label>
        <?php echo Form::textarea('name', Arr::get($_POST, 'name'), array('id'=>'name', 'placeholder'=>'请输入数据')); ?>
        <?php if(isset($error['name'])): ?><span class="info-error"><?php echo $error['name']; ?></span><?php endif; ?>
        每行一条数据，空行不计算在内
    </div>
    <div class="pure-control-group">
        <label for="value">关键词</label>
        <?php echo Form::textarea('value', Arr::get($_POST, 'value'), array('id'=>'value', 'placeholder'=>'请输入数据')); ?>
        <?php if(isset($error['value'])): ?><span class="info-error"><?php echo $error['value']; ?></span><?php endif; ?>
        每行一条数据，空行不计算在内
    </div>
    <div class="pure-control-group">
        <label for="email">权重</label>
        <?php echo Form::input('weight', Arr::get($_POST, 'weight', 0), array('id'=>'weight')); ?>
        <?php if(isset($error['weight'])): ?><span class="info-error"><?php echo $error['weight']; ?></span><?php endif; ?>
    </div>
    <div class="pure-control-group">
        <label for="email">显示</label>
        <?php echo Form::checkbox('display', 1,  Arr::get($_POST, 'display')==1 ? TRUE : FALSE, array('id'=>'display')); ?>
        <?php if(isset($error['display'])): ?><span class="info-error"><?php echo $error['display']; ?></span><?php endif; ?>
    </div>
    <div class="pure-controls">
        <?php echo Form::hidden('cid', Arr::get($_POST, 'cid', $cid)); ?>
        <?php echo Form::hidden('parent_cid', Arr::get($_POST, 'parent_cid', $pcid)); ?>
        <?php echo Form::hidden('type', Arr::get($_POST, 'type', $type)); ?>
        <?php echo Form::hidden('csrf', $token); ?>
        <button type="submit" class="pure-button pure-button-primary">确认</button>
    </div>
  </fieldset>
  </form>

</div>
