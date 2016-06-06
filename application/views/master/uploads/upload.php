<link rel="stylesheet" href="<?=RES?>master/js/swfupload/swfupload.css" />
<script type="text/javascript" src="<?=RES?>master/js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=RES?>master/js/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="<?=RES?>master/js/swfupload/handlers.js"></script>
<script type="text/javascript">
		$.ajaxSetup ({ cache: false });

		var file_limit = <?=$file_limit?>;
		var swfu;
 		$(function() {
			
			var settings = {
				flash_url : "<?=RES?>master/js/swfupload/swfupload.swf?"+Math.random(),
				upload_url: "/master/core/uploads/upload",
				file_post_name : "filedata",
				post_params: {"PHPSESSID" : "<?=$sessid;?>", "isadmin" : "<?=$isadmin;?>","userid":"<?=$userid?>","swf_auth_key": "<?=$swf_auth_key;?>","isthumb" : "<?=$isthumb;?>","moduleid" : "<?=$moduleid?>","addwater":"1","lang":"cn"},
				file_size_limit : "<?=$file_size;?> MB",
				file_types : "<?=$file_types;?>",
				file_types_description : "All Files",
				file_upload_limit : "<?=$file_limit;?>",
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel",
					tdFilesQueued : document.getElementById("tdFilesQueued"),
					tdFilesUploaded : document.getElementById("tdFilesUploaded"),
					tdErrors : document.getElementById("tdErrors")
				},
				debug: false,
				prevent_swf_caching : false,

				button_image_url : "",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_width: 75,
				button_height: 29,
				button_text : '',
				button_text_style : '',
				button_text_top_padding: 0,
				button_text_left_padding: 0,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,

				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				file_dialog_complete_handler:fileDialogComplete
			};

			
			swfu = new SWFUpload(settings);
			
	     });
 
				
function addwater_enable(){
	if($('#addwater').attr('checked')) {
		swfu.addPostParam('addwater', '1');
	} else {
		swfu.removePostParam('addwater');
	}
}
</script>
<div id="content">
<div id="tabs" class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="javascript:void(0);">文件</a></li>
		<li><a href="javascript:void(0);">链接</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane" style="display:block;">
				<div id="divMovieContainer">
					<div class="selectbut"><span id="spanButtonPlaceHolder"></span></div>
					<input type="button" value="开始上传" id="uploadbut" onclick="swfu.startUpload();"/>
					<div style="color:#959595;line-height:24px;height:24px;background:url() no-repeat;padding-left:20px;"><input type="checkbox" id="addwater" name="addwater" value="1" onclick="addwater_enable();" <font color="green">是否添加水印</font> , 支持 <font color="red"><?=$file_types?></font> 格式。</div><br>
					<div style="color:#454545;clear:both;line-height:24px;height:24px;"> 最多上传 <font color="red"><?=$small_upfile_limit?></font> 个附件,单文件最大 <font color="red"><?=$file_size?></font>	MB</div>
				</div>


				<div id="divStatus">共选择了<span id="tdFilesQueued">0</span>个附件,上传失败<span id="tdErrors">0</span> 个,成功上传<span id="tdFilesUploaded">0</span> 个</div>
				<fieldset  id="swfupload-box">
					<legend>文件列表</legend>
					<ul id="fsUploadProgress"></ul>
					<ul class="attachment-list"  id="thumbnails"></ul>
				</fieldset>
		</div>
		<div class="tab-pane">
		 <br>
        	请输入网络地址: <input type="text" id="fileurl" name="fileurl" class="input-text" value=""  style="width:350px;"  onblur="addfileurl(this)">
			<br><br><br>

		</div>
	</div>
</div>

<div  id="myuploadform" style="display:none;" ></div>
<script>
new Tabs("#tabs",".nav li",".tab-content",".tab-pane","active",1);
function addfileurl(obj) {
	var strs = $(obj).val() ?  $(obj).val() : '';

	if(strs){
		var datas='<div id="uplist_1"><input type="hidden" id="aids" name="aids"  value="0"  /><input type="text"  id="filedata" name="filedata" value="'+strs+'"  /><input type="text"  id="namedata" name="namedata" value=""  /> &nbsp;<a href="javascript:remove_this(\'uplist_1\');">移除</a> </div>';
		$('#myuploadform').html(datas);
		$('#thumbnails   a ').removeClass("on");
		$('.img a ').removeClass("on");
	}else{

		$('#myuploadform').html('');
	}
}
</script>
</div>