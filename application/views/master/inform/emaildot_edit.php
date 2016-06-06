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
			<form id="form_mail_dot" class="form-horizontal no-margin" action="<?=$zjjp?>emaildot/<?=$action_url?>" method="post" onsubmit="return save_mail_dot()">
				<div class="modal-body">
					<div class="space-4"></div>
					<div class="form-group">
						<label for="theme" class="col-sm-3 control-label no-padding-right"> 主题 </label>
						<div class="col-sm-8">
							<input type="text" name="theme" value="<?=!empty($info->theme) ? $info->theme : ''?>" class="col-xs-8" placeholder="主题" id="smtp_host">
						</div>
					</div>
					<div class="form-group">
						<label for="addresser" class="col-sm-3 control-label no-padding-right"> 发件人 </label>
						<div class="col-sm-8">
							<input type="text" name="addresser" value="<?=!empty($info->addresser) ? $info->addresser : ''?>" class="col-xs-8" placeholder="发件人" id="smtp_user">
						</div>
					</div>
					<div class="form-group">
						<label for="smtp_pass" class="col-sm-3 control-label no-padding-right"> 发件人配置 </label>
						<div class="col-sm-8">
							<select  id="addresserset" name="addresserset" aria-required="true" aria-invalid="false">
									<option value="0">—请选择—</option>
										<?php foreach($addresserset as $k=>$v):?>
										<option value="<?php echo $v['id']?>"><?php echo $v['smtp_user']?></option>
										<?php endforeach;?>
								</select>
						</div>
					</div>
					<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容模板:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor1"><?=!empty($info->content) ? $info->content : ''?></div>
							<input type="hidden" name="content" id="content" value="">
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
</div>
	<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->

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
		
})
		function save_mail_dot(){
			var that_form =  $("#form_mail_dot");
			$('#content').val($('#editor1').html());
			var data = that_form.serialize();
			
			$.ajax({
				type:'post',
				url:that_form.attr('action'),
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

<script type="text/javascript">
jQuery(function($) {
		var cucaseditor = ['#editor1','#editor2','#editor3','#editor4','#editor5'];
		$.each(cucaseditor,function(i,v){
			$(v).ace_wysiwyg({
					toolbar:
					[
						{
							name:'font',
							title:'Custom tooltip',
							values:['Some Font!','Arial','Verdana','Comic Sans MS','Custom Font!']
						},
						null,
						{
							name:'fontSize',
							title:'Custom tooltip',
							values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text'} 
						},
						null,
						{name:'bold', title:'Custom tooltip'},
						{name:'italic', title:'Custom tooltip'},
						{name:'strikethrough', title:'Custom tooltip'},
						{name:'underline', title:'Custom tooltip'},
						null,
						'insertunorderedlist',
						'insertorderedlist',
						'outdent',
						'indent',
						null,
						{name:'justifyleft'},
						{name:'justifycenter'},
						{name:'justifyright'},
						{name:'justifyfull'},
						null,
						{
							name:'createLink',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-purple',
							button_text:'Custom TEXT'
						},
						{name:'unlink'},
						null,
						{
							name:'insertImage',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-inverse',
							//choose_file:false,//hide choose file button
							button_text:'Set choose_file:false to hide this',
							button_insert_class:'btn-pink',
							button_insert:'Insert Image'
						},
						null,
						{
							name:'foreColor',
							title:'Custom Colors',
							values:['red','green','blue','navy','orange'],
							/**
								You change colors as well
							*/
						},
						/**null,
						{
							name:'backColor'
						},*/
						null,
						{name:'undo'},
						{name:'redo'},
						null,
						'viewSource'
					],
					//speech_button:false,//hide speech button on chrome
					
					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
					
				}).prev().addClass('wysiwyg-style2');
		});
				

				
				
	});
			

</script>