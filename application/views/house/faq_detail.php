 <?php defined('SYSPATH') or die('No direct script access.');?>
  <div class="pure-u-1">
    我的回答
  </div>
  <div class="pure-u-1">
  <?php if(isset($faqd)): ?>
  <?php foreach($faqd as $v): ?>
    <ul id='faq_list'>
      <li>
          <?php echo $v->body; ?>
          <?php echo $v->username; ?>
          <?php echo date('Y-m-d H:i', strtotime($v->created)); ?>
      </li>
    </ul>
  <?php endforeach; ?>
  <?php else: ?>
  <span class="info">没有数据</span>
  <?php endif; ?>
  </div>
  <div class="pure-u-1">
    <?php echo Form::open('api_faq/detail_save');?>
    <?php echo Form::input('body', ''); ?>
    <?php echo Form::hidden('fid', $faq->fid); ?>
    <button class="pure-button" id='faq_post_detail'>回答</button>
    </form>
<script>
  $(function(){
    $('#faq_post_post').click(function(){
      question = $('input[name=question]').val();
      hid = $('input[name=hid]').val();
    });
  });
</script>
  </div>
