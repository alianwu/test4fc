<?php defined('SYSPATH') or die('No direct script access.');?>
<?php if(isset($faq['total']) && $faq['total'] ): $fid = Arr::get($_GET, 'fid', 0); ?>
<table class="pure-table pure-table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>用户名称</th>
      <th><?php echo $fid?'回答':'问题'; ?></th>
      <th>时间</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($faq['data'] as $v) : ?>
    <tr>
      <td><?php echo Form::checkbox('fid[]', $v->fid); ?><?php echo $v->fid; ?></td>
      <td><?php echo $v->username; ?></td>
      <td><?php echo $v->body; ?></td>
      <td><?php echo $v->created; ?></td>
      <td>
        <?php echo $fid?NULL:HTML::anchor('manager_house_faq/detail'. URL::query(array('fid'=>$v->fid)), '回答'); ?>
        <?php echo HTML::anchor('manager_house_faq/display'.($fid?'_detail':NULL). URL::query(array('fid'=>$v->fid)), $v->display?'显示':'隐藏'); ?>
        <?php echo HTML::anchor('manager_house_faq/delete'.($fid?'_detail':NULL). URL::query(array('fid'=>$v->fid)), '删除', array('onClick'=>'return confirm("确定删除么");')); ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php echo Pagination::factory(array(
    'items_per_page' => $pagination['items_per_page'],
    'total_items' => $faq['total'], 
  ))->render(); ?>
<?php else: ?>
<div class="info">暂无数据</div>
<?php endif; ?>
