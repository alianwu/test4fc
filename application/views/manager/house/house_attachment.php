<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pure-u-1">
  <?php echo Form::open('manager_api/upload', array('class'=>'pure-form', 'enctype'=>'multipart/form-data')); ?>
  <fieldset>
    <legend>附件管理</legend>
      <div class="pure-u-1-2">
      <dl>
      <?php foreach($setting->attachment as $k=> $v) :?>
      <dt><?php echo $v; ?><hr /></dt>
      <dd id='attachment-<?php echo $k; ?>'>
      <?php 
        $array = Pretty::postgrs_array($attachment['attachment_'.$k]);
        foreach($array as $v) {
          echo HTML::image($v, array('class'=>'attachment-img', 'data-id'=>$attachment['hid'], 'data-file'=>urlencode($v), 'data-attachment'=>$k));
        }
      ?>
      </dd>
      <?php endforeach; ?>
      </dl>
      </div><div class="pure-u-1-2">
        <div class="fieldset flash" id="fsUploadProgress"></div>
        <div id="divStatus"></div>
        <div>
          <?php echo Form::select('attachment', $setting['attachment'], NULL, array('id'=>'attachment')); ?> <br /> <br />
          <span id="spanButtonPlaceHolder"></span><br />
          <input id="btnCancel" style="" type="button" value="取消上传" onclick="swfu.cancelQueue();" disabled="disabled" />
        </div>
			</div>
  </fieldset>
  </form>
</div>
<script type="text/javascript">
	var swf
	var setting;
  var hid = <?php echo Arr::get($_GET, 'hid', 0); ?>;
  var url_base = '<?php echo URL::base(); ?>';
  var url_attachment_delete = '<?php echo URL::site('manager_api/attachment_delete'); ?>';
  $(function(){
    function attachment_delete() {
      ret = confirm('确定删除么');
      if (ret == false) { return false; }
      file = $(this).attr('data-file'); 
      hid = $(this).attr('data-id'); 
      attachment = $(this).attr('data-attachment'); 
      $.getJSON( url_attachment_delete + '?hid='+hid+'&file='+encodeURIComponent(file)+'&atype='+attachment, function(data){
          $('img[data-file="'+file+'"]').remove();
      });
    }

    $('.attachment-img').click(attachment_delete);


			settings = {
        flash_url :  url_base + "/media/swfupload/swfupload.swf",
        upload_url: "<?php echo URL::site('manager_api/attachment_upload'); ?>",
				file_types : "*.*",
        post_params: {"atype" : '1','hid':hid},
				file_types_description : "All Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
button_image_url: "<?php echo URL::base(); ?>/media/image/TestImageNoText.png",
				button_width: "190",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">选择单个或者多个文件</span>',
				button_text_style: ".theFont { font-size: 16; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadApiSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};
      
      $('#attachment').change(function(){
        atype = $(this).val();
        swf.setPostParams({"atype": atype, 'hid': hid});
      });
      function uploadApiSuccess(file, data) {
        try {
          var progress = new FileProgress(file, this.customSettings.progressTarget);
          progress.setComplete();
          var info = eval('(' + data + ')');
          if(info.error == 1) {
            progress.setStatus("Failed.");
            alert(info.data);
          }
          else {
            progress.setStatus("完成。");
            attachment = $('#attachment').val();
            $('<img src="'+url_base + info.data+'" data-id='+hid+' data-file="'+info.data+'" data-attachment="'+attachment+'" class="attachment-img" />').click(attachment_delete).appendTo('#attachment-'+attachment);
          }
          progress.toggleCancel(false);

        } catch (ex) {
          this.debug(ex);
        }
      }

			swf = new SWFUpload(settings);

});
</script>
