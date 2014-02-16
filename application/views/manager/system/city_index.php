<?php defined('SYSPATH') or die('No direct script access.');?>
<?php if(isset($city)): ?>
<table class="pure-table pure-table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th><?php echo $setting['type'][$type]; ?></th>
      <th>相关数据</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($city as $v) : ?>
    <tr>
      <td><?php echo $v->cid; ?></td>
      <td><?php echo $v->name; ?></td>
      <td>
        <?php echo $type == 1 && $pcid == 0 ? HTML::anchor('manager_system_city'. URL::query(array('type'=>1, 'pcid'=>$v->cid)), '区域') : NULL; ?>
        <?php echo $type == 1 && $pcid == 0 ?   HTML::anchor('manager_system_city/add'. URL::query(array('type'=>1,'pcid'=>$v->cid)), '添加') : NULL; ?>
        <?php echo $type == 1 && $pcid == 0 ? HTML::anchor('manager_system_city'. URL::query(array('type'=>2, 'pcid'=>$v->cid)), '地铁') : NULL; ?>
        <?php echo $type == 1 && $pcid == 0 ? HTML::anchor('manager_system_city/add'. URL::query(array('type'=>2,'pcid'=>$v->cid)), '添加') : NULL; ?>
        <?php echo $type == 2 ?  HTML::anchor('manager_system_city'. URL::query(array('type'=>3, 'pcid'=>$v->cid)), '地铁站') : NULL; ?>
        <?php echo $type == 2 ? HTML::anchor('manager_system_city/add'. URL::query(array('type'=>3,'pcid'=>$v->cid)), '添加') : NULL; ?>
        <?php echo $type == 1 && $pcid <> 0 ? HTML::anchor('manager_system_city'. URL::query(array('type'=>6, 'pcid'=>$v->cid)), '商圈') : NULL; ?>
        <?php echo $type == 1 && $pcid <> 0 ? HTML::anchor('manager_system_city/add'. URL::query(array('type'=>6,'pcid'=>$v->cid)), '添加') : NULL; ?>
      </td>
      <td>
        <?php echo HTML::anchor('manager_system_city/display'. URL::query(array('cid'=>$v->cid)), $v->display?'显示':'隐藏'); ?>
        <?php echo HTML::anchor('manager_system_city/editor'. URL::query(array('cid'=>$v->cid)), '编辑'); ?>
        <?php echo HTML::anchor('manager_system_city/delete'. URL::query(array('cid'=>$v->cid)), '删除', array('onClick'=>'return confirm("确定删除么");')); ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<div class="info">暂无 <?php echo $setting['type'][$type]; ?> 数据</div>
<?php endif; ?>
