<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">导入电费</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main">
						<form class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/acc_dispose_electric/save_room_electric" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">电表记录开始时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="starttime" class="date-picker" name="starttime" value="">
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>

						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">电表记录结束时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="endtime" class="date-picker" name="endtime" value="">
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">度数:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input id="spend" class="col-xs-12 col-sm-5" type="text" value="" name="spend">
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">金额:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input id="money" class="col-xs-12 col-sm-5" type="text" value="" name="money">
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<input type="hidden" name="roomid" value="<?=!empty($roomid)?$roomid:0?>">
						<input type="hidden" name="bulidingid" value="<?=!empty($bulidingid)?$bulidingid:0?>">
						<input type="hidden" name="floor" value="<?=!empty($floor)?$floor:0?>">
						<input type="hidden" name="campusid" value="<?=!empty($campusid)?$campusid:0?>">
						<div class="modal-footer center">
							<a id="tijiao" onclick="save_time()" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
								提交
							</a>
							<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
								取消
							</button>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function save_time(){
	var form=$('#validation-form');
	var data=form.serialize();
	$.ajax({
		url: form.attr('action'),
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
			window.location.href="/master/enrollment/acc_dispose_electric";
		}
		if(r.state==0){
			pub_alert_error(r.info);
		}
	})
	.fail(function() {
		console.log("error");
	})
	
}
</script>
<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>
