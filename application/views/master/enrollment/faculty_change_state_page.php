
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">&times;</span>
						</button>
						修改状态
					</div>
				</div>

				<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/appmanager/faculty_change_state" >
				<!-- 申请状态修改下拉列表是否显示 -->
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请状态修改:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<select name="faculty_state"> 
								<option value="0">-请选择-</option>
								<option <?=!empty($info['faculty_state'])&&$info['faculty_state']==1?'selected="selected"':''?> value="1">通过</option>
								<option <?=!empty($info['faculty_state'])&&$info['faculty_state']==2?'selected="selected"':''?> value="2">拒绝</option>
							</select>
						</div>
					</div>
				</div>

				<div class="space-2"></div>
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>
					<div class="col-xs-12 col-sm-9">
						<textarea name="faculty_remark" cols="25" rows="5" style="width: 262px; height: 131px;"><?=!empty($info['faculty_remark'])?$info['faculty_remark']:''?></textarea>
					</div>
				</div>
				<input type="hidden" name="id" value="<?=$appid?>">
				<div class="modal-footer center">
					<button type="button" class="btn btn-sm btn-success" onclick="save_s()" id="sub"><i class="ace-icon fa fa-check"></i> Submit</button>
					<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
				 </div>
			
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
function save_s(){
	var h = '<i class="ace-icon fa fa-check">Process...</i>';
	$('#sub').html(h);
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/enrollment/appmanager/faculty_change_state',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state == 1){
			pub_alert_success();
			window.location.reload();
		}else{
			pub_alert_error(r.info);
		}
	})
	.fail(function() {
		pub_alert_error();
	})
}
</script>