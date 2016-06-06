<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												打印通知书
											</div>
										</div>

										<form id="pub_form" class="form-horizontal" id="validation-form" method="get" action="/master/enrollment/change_offer_status/print_offer" target="_blank">
										

								<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">请选择模版:</label>
									<div class="col-xs-12 col-sm-9">
										<select name="m" id="m">
										<option value="">--请选择--</option>
										<?php if(!empty($result)){?>
											<?php foreach($result as $k => $v){?>
											<option value="<?=$v['id']?>"><?=$v['name']?></option>
											<?php }?>
										
										<?php }?>
										</select>
									</div>
								</div>
								<!--
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学习时间开始:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="studystime" class="form-control date-picker" name="studystime" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学习时间结束:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="studyetime" class="form-control date-picker" name="studyetime" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">报到时间开始:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="rollstime" class="form-control date-picker" name="rollstime" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">报到时间结束:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="rolletime" class="form-control date-picker" name="rolletime" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>-->
								<input type="hidden" name="id" value="<?=!empty($id)?$id:''?>">
								<div class="modal-footer center">
									<button type="submit" class="btn btn-sm btn-success" id="sub"><i class="ace-icon fa fa-check"></i> Submit</button>
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
		
		url: '/master/enrollment/change_offer_status/print_z',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state == 1){
			
			window.open('/master/enrollment/change_offer_status/print_offer?id='+r.data.id+'&m='+r.data.m);
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