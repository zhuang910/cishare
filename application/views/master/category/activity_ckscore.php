
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												打分
											</div>
										</div>

										<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/student/activity/do_score" >
										

								<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">打分:</label>
									<div class="col-xs-12 col-sm-9">
										<select name="score" id="score">
										<option value="">打分</option>
										<?php foreach($scoreA as $k => $v){?>
										<option value="<?=$k?>"><?=$v?></option>
										<?php }?>
										</select>
									</div>
								</div>
								<input type="hidden" name="id" value="<?=$id?>">
								<input type="hidden" name="activityid" value="<?=$activityid?>">
								<div class="modal-footer center">
									<button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="ace-icon fa fa-check"></i> Submit</button>
									<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
								 </div>
									
									</form>
								</div>
							</div>
</div>
<script type="text/javascript">

function save(){
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/student/activity/do_score',
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