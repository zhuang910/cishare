<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-8-21
 * Time: 下午8:02
 */

$action_url = $action == 'add' ? 'insert' : 'update';
$action_title = $action == 'add' ? '添加' : '修改';
?>

<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="blue"><?=$action_title?>发送点设置</h4>
			</div>
			<form id="form_mail_dot" class="form-horizontal no-margin" action="<?=$zjjp?>messagedot/<?=$action_url?>" method="post" onsubmit="return save_message_dot()">
				<div class="modal-body">
					<div class="space-4"></div>
					<div class="form-group">
						<label for="title" class="col-sm-3 control-label no-padding-right"> 标题 </label>
						<div class="col-sm-8">
							<input type="text" name="title" value="<?=!empty($info->title) ? $info->title : ''?>" class="col-xs-8" placeholder="标题" id="smtp_host">
						</div>
					</div>
					<div class="form-group">
						<label for="addresser" class="col-sm-3 control-label no-padding-right"> 发件人 </label>
						<div class="col-sm-8">
							<input type="text" name="addresser" value="<?=!empty($info->addresser) ? $info->addresser : ''?>" class="col-xs-8" placeholder="发件人" id="smtp_user">
						</div>
					</div>
					
					<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容模板:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor1"><?=!empty($info->content) ? $info->content : ''?></div>
					</div>
				</div>
				
				</div>
				<div class="modal-footer center">
				<input type="hidden" name="id" value="<?=!empty($info->id) ? $info->id : ''?>">
					<button class="btn btn-sm btn-success" type="submit"><i class="ace-icon fa fa-check"></i>
						提交
					</button>
					<button data-dismiss="modal" class="btn btn-sm" type="button"><i class="ace-icon fa fa-times"></i>
						取消
					</button>
				</div>
			</form>
		</div>
	</div>
	<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
	<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
	<script type="text/javascript">
	jQuery(function($){
	function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			//console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	}
		$('#editor1').ace_wysiwyg({
				toolbar:
				[
					'font',
					null,
					'fontSize',
					null,
					{name:'bold', className:'btn-info'},
					{name:'italic', className:'btn-info'},
					{name:'strikethrough', className:'btn-info'},
					{name:'underline', className:'btn-info'},
					null,
					{name:'insertunorderedlist', className:'btn-success'},
					{name:'insertorderedlist', className:'btn-success'},
					{name:'outdent', className:'btn-purple'},
					{name:'indent', className:'btn-purple'},
					null,
					{name:'justifyleft', className:'btn-primary'},
					{name:'justifycenter', className:'btn-primary'},
					{name:'justifyright', className:'btn-primary'},
					{name:'justifyfull', className:'btn-inverse'},
					null,
					{name:'createLink', className:'btn-pink'},
					{name:'unlink', className:'btn-pink'},
					null,
					{name:'insertImage', className:'btn-success'},
					null,
					'foreColor',
					null,
					{name:'undo', className:'btn-grey'},
					{name:'redo', className:'btn-grey'}
				],
				'wysiwyg': {
					fileUploadError: showErrorAlert
				}
			}).prev().addClass('wysiwyg-style2');
})
		function save_message_dot(){
			var that_form =  $("#form_mail_dot");
			var data = that_form.serialize();
			var content=$('#editor1').html();
			$.ajax({
				type:'post',
				url:that_form.attr('action')+'?content='+content,
				data:data,
				dataType:'json',
				success:function(r){
					if(r.state == 1){
						pub_alert_success(r.info);
						setTimeout('window.location.reload()',800);
					}else{
						pub_alert_error(r.info);
					}
				}
			});
			return false;
		}
	</script>
</div>