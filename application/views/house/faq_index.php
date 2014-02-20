 <?php defined('SYSPATH') or die('No direct script access.');?>
  <div class="pure-u-1">
    我的回答
  </div>
  <div class="pure-u-1">
  <?php if(isset($faq)): ?>
  <?php foreach($faq as $v): ?>
    <ul id='faq_list'>
      <li>
          <?php echo $v->body; ?>
          回答：<?php echo $v->count?$v->count:'没有'; ?>
          <?php echo date('Y-m-d H:i', strtotime($v->created)); ?>
          <?php echo HTML::anchor('house_faq/detail/'.$v->fid.'.html', '回答'); ?>
      </li>
    </ul>
  <?php endforeach; ?>
  <?php else: ?>
  <span class="info">没有数据</span>
  <?php endif; ?>
  </div>
  <div class="pure-u-1">
    <?php echo Form::open('api_faq/save');?>
    <?php echo Form::input('question', ''); ?>
    <?php echo Form::hidden('hid', $house->hid); ?>
    <button class="pure-button" id='faq_post'>提问</button>
    </form>
<script>
  $(function(){
    $('#faq_post').click(function(){
      question = $('input[name=question]').val();
      hid = $('input[name=hid]').val();
    });
  });
</script>
  </div>
