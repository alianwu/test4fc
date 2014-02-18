<?php defined('SYSPATH') or die('No direct script access.');?>
<?php if(isset($house['total']) && $house['total'] ): ?>
<table class="pure-table pure-table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>小区名称</th>
      <th>所在区域</th>
      <th>商圈</th>
      <th>地铁</th>
      <th>地铁站</th>
      <th>小区地址</th>
      <th>增加人</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($house['data'] as $v) : ?>
    <tr>
      <td><?php echo Form::checkbox('hid[]', $v->hid); ?><?php echo $v->hid; ?></td>
      <td><?php echo $v->name; ?></td>
      <td><?php echo $city_cache[$v->city_area]; ?></td>
      <td><?php echo $city_cache[$v->city_area_shop]; ?></td>
      <td><?php echo $city_cache[$v->underground]; ?></td>
      <td><?php echo $city_cache[$v->underground_platform]; ?></td>
      <td><?php echo $v->address; ?></td>
      <td><?php echo $v->author; ?></td>
      <td>
        
        <?php echo HTML::anchor('manager_house_home/attachment'. URL::query(array('hid'=>$v->hid)), '附件管理'); ?>
        <?php echo HTML::anchor('manager_house_home/faq'. URL::query(array('hid'=>$v->hid)), '问答管理'); ?>
        <?php echo HTML::anchor('manager_house_home/display'. URL::query(array('hid'=>$v->hid)), $v->display?'显示':'隐藏'); ?>
        <?php echo HTML::anchor('manager_house_home/editor'. URL::query(array('hid'=>$v->hid)), '编辑'); ?>
        <?php echo HTML::anchor('manager_house_home/delete'. URL::query(array('hid'=>$v->hid)), '删除', array('onClick'=>'return confirm("确定删除么");')); ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php echo Pagination::factory(array(
    'items_per_page' => $pagination['items_per_page'],
    'total_items' => $house['total'], 
  ))->render(); ?>
<?php else: ?>
<div class="info">暂无数据</div>
<?php endif; ?>
