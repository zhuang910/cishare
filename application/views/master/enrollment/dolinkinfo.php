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
												添加接机人信息
											</div>
										</div>

										<form class="form-horizontal" id="pay-form" method="post" action="<?=$url?>" >
											<input type="hidden" name='id' value="<?=$id?>">
										
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">添加接机人信息:</label>
											<div class="col-xs-12 col-sm-9">
												<div class="clearfix">
													<textarea name="linkinfo"><?=!empty($info->linkinfo)?$info->linkinfo:''?></textarea>
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
