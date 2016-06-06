<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">退房管理</h4></div>
						<div class="alert alert-block alert-success">
							<p>
							    <strong>
									<?=!empty($campus_name)?$campus_name:''?>-<?=!empty($building_name)?$building_name:''?>-第<?=!empty($acc_info->floor)?$acc_info->floor:''?>层的<?=!empty($room_info['name'])?$room_info['name']:''?>
								</strong>
								<form method="post" id="room_out">
								<table class="table table-hover table-nomargin table-bordered">
								<thead>	
									<tr>
										<td colspan='2' style='border-bottom:1px solid #ddd;'>退费信息：</td>
									</tr>	
									<tr>
										<th>
											<table style='background-color:#fff;width:100%'>
												
												<tr>
								
													<td>应退住宿费：</td><td><input type="text" id="ought_acc_money" name="ought_acc_money" value="<?=!empty($acc_money)?$acc_money:0?>" class="col-xs-12 col-sm-5" />RMB<?php if(!empty($acc_money)&&$acc_money<0):?><span style="color:red;">请交完住宿费再申请退房<span><?php endif;?></td>
												</tr>
												<tr>
								
													<td>实退住宿费：</td><td><input type="text" id="ture_acc_money" name="ture_acc_money" class="col-xs-12 col-sm-5" />RMB<?php if(!empty($acc_money)&&$acc_money<0):?><span style="color:red;">请交完住宿费再申请退房<span><?php endif;?></td>
												</tr>
												<tr>
													<td>应退电费：</td><td><input type="text" id="ought_electric_money" name="ought_electric_money" value="<?=!empty($electric_money)?$electric_money:0?>" class="col-xs-12 col-sm-5" />RMB<?php if(!empty($electric_money)&&$electric_money<0):?><span style="color:red;">请交完电费再申请退房<span><?php endif;?></td>
												</tr>
												<tr>
													<td>实退电费：</td><td><input type="text" id="ture_electric_money" name="ture_electric_money" class="col-xs-12 col-sm-5" />RMB<?php if(!empty($acc_info->electric_money)&&$acc_info->electric_money<0):?><span style="color:red;">请交完电费再申请退房<span><?php endif;?></td>
												</tr> 
												<tr>
													<td>应退电费押金：</td><td><input type="text" id="ought_electric_pledge_money" name="ought_electric_pledge_money" value="<?=!empty($dian_pledge)?$dian_pledge:0?>" class="col-xs-12 col-sm-5" />RMB<?php if(!empty($dian_pledge)&&$dian_pledge<0):?><span style="color:red;">请交完电费再申请退房<span><?php endif;?></td>
												</tr>
												<tr>
													<td>实退电费押金：</td><td><input type="text" id="ture_electric_pledge_money" name="ture_electric_pledge_money" class="col-xs-12 col-sm-5" />RMB<?php if(!empty($acc_info->electric_money)&&$acc_info->electric_money<0):?><span style="color:red;">请交完电费再申请退房<span><?php endif;?></td>
												</tr>
												<tr>
													<td>应退住宿押金：</td><td><input type="text" id="ought_acc_pledge_money" name="ought_acc_pledge_money" value="<?=!empty($acc_pledge)?$acc_pledge:0?>" class="col-xs-12 col-sm-5" />RMB<?php if(!empty($acc_pledge)&&$acc_pledge<0):?><span style="color:red;">请交完押金再申请退房<span><?php endif;?></td>
												</tr>
												<tr>
													<td>实退住宿押金：</td><td><input type="text" id="ture_acc_pledge_money" name="ture_acc_pledge_money" class="col-xs-12 col-sm-5" />RMB<?php if(!empty($acc_pledge)&&$acc_pledge<0):?><span style="color:red;">请交完押金再申请退房<span><?php endif;?></td>
												</tr>
												<tr>
													<td>离开日期：</td><td><input type="text" oldtime="<?=date('Y-m-d',time())?>" accid="<?=$acc_info->id?>" data-date-format="yyyy-mm-dd"  id="out_time" class="date-picker" name="out_time" value="<?=date('Y-m-d',time())?>"><i class="fa fa-calendar bigger-110"></i></td>
												</tr>
												<tr>
								
													<td>退房备注：</td><td><textarea name="remark"></textarea></td>
												</tr>
											</table>
										</th>
									</tr>
									</thead>
								</table>
								<input type="hidden" name="userid" value="<?=$userid?>">
								<input type="hidden" name="campusid" value="<?=$campusid?>">
								<input type="hidden" name="buildingid" value="<?=$buildingid?>">
								<input type="hidden" name="floor" value="<?=$floor?>">
								<input type="hidden" name="roomid" value="<?=$roomid?>">
								<input type="hidden" name="accid" value="<?=$acc_info->id?>">
								</form>
							</p>
							
							<p>
								<a id="tijiao" class="btn btn-sm" onclick="out_room(2)" href="javascript:;">校外住宿</a>
								<a id="tijiao" class="btn btn-sm" onclick="out_room(3)" href="javascript:;">离校退房</a>
							</p>
						</div>
				

		</div>
	</div>
</div>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<!--日期插件-->
<script type="text/javascript">
    //datepicker plugin
    //link
    $(function(){
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
         
        })
        .on('changeDate', function(ev){

				var out_time=$('#out_time').val();
				var id=$('#out_time').attr('accid');
				var yuan=$('#ought_acc_money').val();
				if(out_time==undefined){
					return 0;
				}
				$.ajax({
					url: '/master/enrollment/acc_dispose_student/get_acc_fee?out_time='+out_time+'&id='+id+'&yuan='+yuan,
					type: 'POST',
					dataType: 'json',
					data: {},
				})
				.done(function(r) {
					if(r.state==1){
						$('#ought_acc_money').val(r.data);
						$('#out_time').attr({
							oldtime: out_time
						});
					}
					if(r.state==0){
						pub_alert_error(r.info);
						$('#ought_acc_money').val(r.data);
						$('#out_time').val($('#out_time').attr('oldtime'));
					}
				})
				.fail(function() {
					console.log("error");
				})
			})
        // //show datepicker when clicking on the icon
        .next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
            
            
    });

</script>
<script type="text/javascript">
function out_room(r){
	var data=$('#room_out').serialize(); 
	$.ajax({
		beforeSend:function (){
					$('#tijiao').html('正在退房');
					$('#tijiao').attr({
						disabled:'disabled',
					});
				},
		url: '/master/enrollment/acc_dispose_student/ture_out_room?type='+r,
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			window.location.href="/master/enrollment/acc_dispose_student";
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function before(userid,id){
	$.ajax({
		beforeSend:function (){
					$('#be').html('正在打回');
					$('#be').attr({
						disabled:'disabled',
					});
				},
		url: '/master/enrollment/acc_apply/before_student?userid='+userid+'&id='+id,
		type: 'GET',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			window.location.href="/master/enrollment/acc_apply/index?&label_id=2";
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						title: {
							required: true
						},
						desperation: {
							maxlength: 200,
						},
						state: 'required',
						
					},
			
					messages: {
						title:{
							required:"请输入标题",
						},
						desperation: {
							required: "最多输入200个字符",
							
						},
						
						state: "请选择状态",
						
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
							beforeSend:function (){
								$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>正在提交');
								$('#tijiao').attr({
									disabled:'disabled',
								});
							},
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="/master/test/test_paper/group_item";
							}
							if(r.state==2){
								$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>提交');
								$('#tijiao').removeAttr('disabled');
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
<!--日期插件-->
