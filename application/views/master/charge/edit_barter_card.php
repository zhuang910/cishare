<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<script src="<?=RES?>master/js/upload.js"></script>	
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog" style="width:800px;">
						<div class="modal-content">
							<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">添加题目</h4></div>
							<!---->
								<form class="form-horizontal" id="validation-form" method="post" action="/master/charge/barter_card/update">
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">支付学期:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select id="term" class="input-medium valid" name="term" aria-required="true" aria-invalid="false">
												
													<?php for($i=1;$i<=$mdata['termnum'];$i++):?>
													<option value="<?php echo $i?>" <?=!empty($info->term)&&$info->term==$i?'selected="selected"':''?> ><?php echo '第'.$i.'学期'?></option>
													<?php endfor?>
												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">金额:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" name="money" value="<?=!empty($info) ? $info->money : ''?>" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
													<div style="display:none;" id="content_aid_box"></div>
													<textarea name="remark" class='content'  id="remark"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info) ? $info->remark : ''?></textarea>
											</div>
										</div>
									</div>
									<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
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
								window.location.href="/master/charge/barter_card";
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
