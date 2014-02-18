<?php defined('SYSPATH') or die('No direct script access.');?>
保存成功 。
<?php echo HTML::anchor('manager_house_home/'. URL::query(array('hid'=>$hid)), '返回列表'); ?>  
<?php echo HTML::anchor('manager_house_home/add', '继续添加'); ?>  
<?php echo HTML::anchor('house_home/detail/'.$hid, '前台预览'); ?> 
<?php echo HTML::anchor('manager_house_home/attachment'. URL::query(array('hid'=>$hid)), '管理或者上传附件'); ?>
