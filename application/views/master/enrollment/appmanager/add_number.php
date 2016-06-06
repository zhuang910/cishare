
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												添加学号
											</div>
										</div>

										<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/change_app_status/do_add_number" >
										

										<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学号:</label>
											<div class="col-xs-12 col-sm-9">
												<input type="text" name="studentid" id="studentid">
											</div>
										</div>
										<input type="hidden" name="id" value="<?=!empty($id)?$id:''?>">
										<input type="hidden" name="name" value="remark">
										<div class="modal-footer center">
											<button type="button" class="btn btn-sm btn-success" onclick="save()" id="sub"><i class="ace-icon fa fa-check"></i> Submit</button>
											<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
										 </div>
									
									</form>
								</div>
							</div>
</div>
<script type="text/javascript">

function save(){
var h = '<i class="ace-icon fa fa-check">Process...</i>';
	$('#sub').html(h);
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/enrollment/change_app_status/do_add_number',
		type: 'POST',
		dataType: 'json',
		data: data
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
		pub_alert_error();
	})
}
</script>