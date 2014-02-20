<?php defined('SYSPATH') or die('No direct script access.');?><!DOCTYPE html>
<div class="pure-u-1">
新房
</div>
<script>  
  $.ajax({
    url:'<?php echo URL::site('api_search');?> ',
    type : 'POST',
    dataType: 'json',
    timeout : 5,
    data: {},
    beforeSend: function() { },
    success : function() {},
    error: function() {}
  });
</script>
