<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												添加字段
											</div>
										</div>
										<form class="form-horizontal" id="fields" method="post" >
										<input type="hidden" name="print_templateid" value="<?=$print_templateid?>">
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">字段名:</label>
											
												<input type="text" value="<?php echo !empty($fields_info)?$fields_info->name:''?>" name="name">
											
										
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">字段值:</label>
													<input type="text" value="<?php echo !empty($fields_info)?$fields_info->fieldsvalue:''?>" name="fieldsvalue">
										</div>
										<div class="space-2"></div>
													<input type="hidden" name="id" value="<?=$id?>">
                                                    <input type="hidden" name="print_templateid" value="<?=$print_templateid?>">
										<div class="modal-footer no-margin-top">
												
											<div class="space-2"></div>
													<div class="col-md-offset-3 col-md-9">
														
														<a class="btn btn-info" href="javascript:;" onclick="fieldsinsert()">
															<i class="ace-icon fa fa-check bigger-110"></i>
																提交
														</a>
													</div>
									
									
									</div>
									</form>
								</div>
							</div>
			
<script type="text/javascript">
function fieldsinsert(){
	var data=$('#fields').serialize();
		$.ajax({
			url: '/master/print/printsetting/fieldsinsert',
			type: 'post',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state == 1){
				pub_alert_success();
				window.location.reload();
			}else{
				pub_alert_error();
			}

		})
		.fail(function() {
			console.log("error");
		})
}

</script>
