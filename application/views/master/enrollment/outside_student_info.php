<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<script src="<?=RES?>master/js/upload.js"></script>	
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'ajax_edit_item' ? '修改' : '添加';
$form_action = $uri4 == 'ajax_edit_item' ? 'update_paper_item' : 'insert_paper_item';

?>
					<div class="modal-dialog" style="width:800px;">
						<div class="modal-content">
							<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">添加题目</h4></div>
							<!---->
								<form class="form-horizontal" id="validation-form" method="post" action="/master/test/test_paper/<?=$form_action?>">
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">房东姓名:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">房屋合同:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<a href="javascript:none();swfupload('contract_pic','contract_pic','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo);">
													<img id="contract_pic" width="135" height="113" src="<?=!empty($info->contract_pic)?$info->contract_pic:'/resource/master/images/admin_upload_thumb.png'?>">
												</a>
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">电话:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="mobile" name="mobile" value="<?=!empty($info) ? $info->mobile : ''?>" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">地址:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="address" name="address" value="<?=!empty($info) ? $info->address : ''?>" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									
									<input type="hidden" name="userid" value="<?=!empty($userid)?$userid:''?>">
									<div class="modal-footer center">
										<button id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
											提交
										</button>
										<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
											取消
										</button>
									</div>
								</form>
							<!---->
					</div>
				</div>
			</div>
<?php $this->load->view('master/public/js_kindeditor_create')?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script type="text/javascript">
function none(){
	$('#pub_edit_bootbox').css({
		display: 'none',
	});
}
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						title: {
							required: true
						},
						desperation: {
							maxlength: 200,
						},
						state: 'required',
						
					},
			
					messages: {
						title:{
							required:"请输入标题",
						},
						desperation: {
							required: "最多输入200个字符",
							
						},
						
						state: "请选择状态",
						
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
					submitHandler: function (form) {
						
						var data=$(form).serialize();
						$.ajax({
							beforeSend:function (){
								$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>正在提交');
								$('#tijiao').attr({
									disabled:'disabled',
								});
							},
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="/master/test/test_paper/group_item";
							}
							if(r.state==2){
								$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>提交');
								$('#tijiao').removeAttr('disabled');
								pub_alert_error(r.info);
							}
							
						})
						.fail(function() {
							
							pub_alert_error();
						})
						
						
					}
			
				});

});
</script>
<!--日期插件-->
