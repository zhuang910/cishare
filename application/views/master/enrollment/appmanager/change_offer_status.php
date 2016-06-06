
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

					<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/change_offer_status/update" >
						
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请ID:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
									<?=!empty($result->number) ? $result->number : ''?>
							</div>
						</div>
					</div>

					<div class="space-2"></div>
					
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">邮寄方式:</label>

						<div class="col-xs-12 col-sm-4">
							<select id='sendtype' name='sendtype'>
								<option value=''>请选择</option>
								<option value='1'>DHL发送</option>
								<option value='2'>EMS</option>
							</select>
						</div>
					</div>
					<div class="space-2"></div>
							
					<div class="form-group" id='NO' style='display:none;'>
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">请输入快递单号:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id='sendproofid' name='sendproofid' value="" class="col-xs-12 col-sm-5" />
						</div>
					</div>
					</div>

					<div class="space-2"></div>	
					
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请状态修改:</label>

						<div class="col-xs-12 col-sm-4">
							<select id='status' name='status'>
								<option value='17'>发送纸质OFFER</option>
							</select>
						</div>
					</div>
					<div class="space-2"></div>
			
			
			<!-- 补充说明部分是否显示-如果状态操作下拉列表不存在，则不显示  -->
			<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">补充说明:</label>
				<div class="col-xs-12 col-sm-9">
					<label for="insertetips"><input type="checkbox" name="insertetips" id="insertetips" value="Y"/> 将已下内容插入邮件</label><br />
					<textarea  cols="25" rows="5" style="width: 262px; height: 131px;" name="sendrmark" ></textarea>
				</div>
			</div>

			<input type="hidden" name="app_id" value="<?=!empty($app_id) ? $app_id : ''?>">
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
	if($('#sendtype').val()==''){
		alert("未选择邮寄方式");
		return false;
	}
	
	if($('#status').val()==''){
		alert("未选择状态");
		return false;
	}
	
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/enrollment/change_offer_status/update',
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

$('#sendtype').change(function(){
	if($(this).val()=='1'){
		$('#NO').show();
	}else{
		$('#NO').hide();
	}
});
/*
$('#pub_form').ajaxForm({
	success:function(r){
		if(r.state == 1){
			location.reload();
		}else{
			pub_alert_error(r.info);
		}
	}, 
	dataType: 'json'
});*/
</script>