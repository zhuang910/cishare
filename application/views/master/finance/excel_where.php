<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<link rel="stylesheet" href="/resource/master/css/datepicker.css" />
<script src="/resource/master/js/date-time/bootstrap-datepicker.min.js"></script>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" style="height:400px;">
			<div class="widget-header">
				<h5 class="widget-title">导出条件</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 230px">
						<form class="form-horizontal" onSubmit="return jiancha()" id="validation-form" method="post" action="/master/finance/budget/export_type" enctype = 'multipart/form-data'>
						<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">支付时间:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="starttime" class="form-control date-picker" name="starttime" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">截止时间:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="endtime" class="form-control date-picker" name="endtime" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">支付类型:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<select id="field_7" name="paytype">
												<option value="">请选择</option>
												<option value="1">paypal</option>
												<option value="2">payease</option>
												<option value="3">汇款</option>
												<option value="4">现金</option>
												<option value="5">刷卡</option>
												<option value="6">奖学金支付</option>
											</select>
										</div>
									</div>
								</div>
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" data-last="Finish">
							<i class="ace-icon fa fa-check bigger-110"></i>
								导出
							</button>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--日期插件-->
<script type="text/javascript">
function jiancha(){
	if($('#field_7').val()==0){
			pub_alert_error('请选择支付类型');
			return false;

	}
	return true;
}
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
