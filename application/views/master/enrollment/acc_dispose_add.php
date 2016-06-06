<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<script src="<?=RES?>master/js/upload.js"></script>	


<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>
					<div class="modal-dialog" style="width:800px;">
						<div class="modal-content">
							<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">添加报修</h4></div>
							<!---->
								<form class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/acc_dispose_repair/insert">
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">校区:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select  id="campusid" name="campusid" aria-required="true" aria-invalid="false" onchange="campus()">
														<option value="0">—请选择—</option>
														<?php foreach($campus_info as $k=>$v):?>
															<option value="<?=$v['id']?>"><?=$v['name']?></option>
														<?php endforeach;?>
												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">楼宇:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select  id="bulidingid" name="buildingid" aria-required="true" aria-invalid="false" onchange="buliding()">
														<option value="0">—请选择—</option>
												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">层数:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select onchange="floors()" id="floor" name="floor" aria-required="true" aria-invalid="false">
														<option value="0">—请选择—</option>
												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">房间:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select id="roomid" class="input-medium valid" name="roomid" aria-required="true" aria-invalid="false">
													<option value="0">-请选择-</option>
												</select>
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
													<div style="display:none;" id="content_aid_box"></div>
													<textarea name="remark" class='content'  id="remark"  boxid="content" style="width:100%;height:350px;resize: none;"></textarea>
													
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="modal-footer center">
										<button id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
											提交
										</button>
										<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
											取消
										</button>
									</div>
								</form>
							<!---->
					</div>
				</div>
			</div>
<?php $this->load->view('master/public/js_kindeditor_create')?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script type="text/javascript">
function floors(){
	$("#roomid").empty();
	var cid=$('#campusid').val();
	var bid=$('#bulidingid').val();
	var floor=$('#floor').val();
		$.ajax({
			url: '/master/enrollment/acc_dispose_repair/get_room?cid='+cid+'&bid='+bid+'&floor='+floor,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			if(r.state==1){
				$("#roomid").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data, function(i, k) { 
				 	 var opt = $("<option/>").text(k.name).attr("value",k.id);
				 	  $("#roomid").append(opt); 
				  });
				 $('#tables').attr({
					class: 'widget-box transparent collapsed'
				});
			}
			if(r.state==0){
				pub_alert_error(r.info);
			}
			
		})
		.fail(function() {
 
			
		})
}
function c(){

	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#add').remove();
}
function campus(){

	var cid=$('#campusid').val();
		$.ajax({
			url: '/master/enrollment/acc_apply/get_buliding?cid='+cid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#bulidingid").empty();
			$("#roomid").empty();
			$("#bulidingid").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data, function(i, k) { 
			 	 var opt = $("<option/>").text(k.name).attr("value",k.id);
			 	  $("#bulidingid").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
		})
		.fail(function() {
 
			
		})

}
function buliding(){

	var bid=$('#bulidingid').val();
		$.ajax({
			url: '/master/enrollment/acc_apply/get_buliding_floor?bid='+bid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#floor").empty();
			$("#roomid").empty();

			$("#floor").append("<option value='0'>—请选择—</option>"); 
			 for(i=1;i<=r.data;i++){
			 	 var opt = $("<option/>").text("第"+i+"层").attr("value",i);
			 	  $("#floor").append(opt); 
			  }
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
		})
		.fail(function() {
 
			
		})

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
								window.location.href="/master/enrollment/acc_dispose_repair";
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
