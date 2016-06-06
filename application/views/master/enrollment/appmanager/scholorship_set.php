
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												设置中国政府奖学金
											</div>
										</div>

										<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/change_app_status/do_scholorship_set" >
										
										<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">中国政府奖学金种类:</label>
											<div class="col-xs-12 col-sm-9">
												<select name="scholorshipid">
													<option value="">--请选择--</option>
													<?php if($scholarship){
														foreach($scholarship as $k => $v){
													?>
													<option value="<?=$v['id']?>" <?=!empty($applyinfo->scholorshipid) && $applyinfo->scholorshipid == $v['id']?'selected':''?>><?=$v['title']?></option>
													
													<?php }}?>
												</select>
											</div>
										</div>
										<?php if($applyinfo->scholorstate == 0){
											$scholorstate = -1;
										}else{
										$scholorstate = $applyinfo->scholorstate;
										}
										?>
										<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
											<div class="col-xs-12 col-sm-9">
												<select name="scholorstate">
													<option value="">--请选择--</option>
													<option value="-1" <?=!empty($scholorstate) && $scholorstate == -1?'selected':''?>>待审核</option>
													<option value="5" <?=!empty($scholorstate) && $scholorstate == 1?'selected':''?>>通过</option>
													<option value="4" <?=!empty($scholorstate) && $scholorstate == 2?'selected':''?>>不通过</option>
												</select>
											</div>
										</div>
										
										<div class="form-group">
												<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>
											<div class="col-xs-12 col-sm-9">
												<textarea name="remark"  style="width: 199px; height: 108px;"></textarea>
											</div>
										</div>
										<input type="hidden" name="id" value="<?=!empty($id)?$id:''?>">
										
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
		
		url: '/master/enrollment/change_app_status/do_scholorship_set',
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