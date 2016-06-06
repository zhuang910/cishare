<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												现场缴费
											</div>
										</div>

										<form class="form-horizontal" id="pay-form" method="post" action="<?=$url?>" >
										<input type="hidden" name='way' value="4">
										<input type="hidden" name='item' value="<?=$type?>">
										<input type="hidden" name='ordertype' value="<?=$type?>">
										<input type="hidden" name='userid' value="<?=$userid?>">
										<input type="hidden" name='id' value="<?=$id?>">
										<div class="space-2"></div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">费用单位:</label>
											<div class="col-xs-12 col-sm-9">
												<div class="clearfix">
													<select id="currency" class="input-medium valid" aria-invalid="false" aria-required="true" name="currency">
														<option value="1">美元</option>
														<option value="2" selected>人民币</option>
													</select>
												</div>
											</div>
										</div>
										<div class="space-2"></div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">额&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;度:</label>
											<div class="col-xs-12 col-sm-7">
												<div class="clearfix">
													<input id="amount" class="col-xs-12 col-sm-5" type="text" value="" name="amount">
												</div>
											</div>
										</div>
										<div class="space-2"></div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">缴费备注:</label>
											<div class="col-xs-12 col-sm-9">
												<div class="clearfix">
													<input id="remark" class="col-xs-12 col-sm-5" type="text" value="" name="remark">
												</div>
											</div>
										</div>
										<div class="space-2"></div>				
										<div class="modal-footer no-margin-top">
												
											<div class="space-2"></div>
													<div class="col-md-offset-3 col-md-9">
														
														<button class="btn btn-info" data-last="Finish">
															<i class="ace-icon fa fa-check bigger-110"></i>
																提交
														</button>
														<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
																重置
														</button>
													</div>
									
									
									</div>
									</form>
								</div>
							</div>
			
<script type="text/javascript">
$(document).ready(function(){
	$('#pay-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						amount: {
							required: true,
							number:true
						},
						pay_type:'required',
						
					},
			
					messages: {
						amount:{
							required:"请输入费用额度",
							number:'请输入数字',
						},
						pay_type:'请选择缴费类型',
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
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){

								pub_alert_success();

							window.location.href="<?=$jump_url?>";
							}else{
								
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
