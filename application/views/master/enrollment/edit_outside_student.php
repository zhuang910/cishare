<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑房东信息':'添加房东信息';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">住宿管理</a>
	</li>
	<li>
		<a href="javascript:;">住宿处理</a>
	</li>
	<li><a href="index">学生管理</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>	
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script src="<?=RES?>master/js/upload.js"></script>	
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		学生管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
				<h3 class="lighter block green"><?=!empty($info)?'编辑房东信息':'添加房东信息'?>
						<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
							<i class="ace-icon fa fa-reply light-green bigger-130"></i>
						</a>
					</h3>
					<form class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/acc_dispose_student/insert_landlord_info">
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">房东姓名:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">身份证号码:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="id_number" name="id_number" value="<?=!empty($info) ? $info->id_number : ''?>" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
							<!-- 		<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">房屋合同:</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<a href="javascript:swfupload('contract','contract_pic','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo);">
													<img id="contract" width="135" height="113" src="<?=!empty($info->contract_pic)?$info->contract_pic:'/resource/master/images/admin_upload_thumb.png'?>">
												</a>
											</div>
										</div>
									</div>
									<div class="space-2"></div> -->
							<div class="form-group">
								<label for="name" class="control-label col-xs-12 col-sm-3 no-padding-right">房屋合同:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" class="col-xs-12 col-sm-5" value="<?=!empty($info->contract_pic)?$info->contract_pic:''?>" id="contract_pic" name="contract_pic">
										<a href="javascript:swfupload('contract_pics','contract_pic','文件上传',0,3,'doc,docx,jpg,png,gif',3,0,yesdo,nodo)" class="btn btn-warning btn-xs">
											<i class="ace-icon glyphicon glyphicon-search bigger-180 icon-only"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">电话:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="mobile" name="mobile" value="<?=!empty($info) ? $info->mobile : ''?>" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">地址:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="address" name="address" value="<?=!empty($info) ? $info->address : ''?>" class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
									
									<input type="hidden" name="userid" value="<?=!empty($userid)?$userid:''?>">
									<div class="modal-footer center">
										<button id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
											提交
										</button>
										<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
											取消
										</button>
									</div>
								</form>
					
				</div>
			</div>

			<!-- /section:plugins/fuelux.wizard.container -->
		</div>
	</div>
</div>
		
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						alias: {
							required: true,
							
						},
	
						englishname: {
							required: true
						},
						name: {
							required: true
						},
						
						degree: {
							required: true,
							
						},
						facultyid: {
							required: true
						},
						
						
						
						state: 'required',
						
					},
			
					messages: {
						name:{
							required:"请输入专业名称",
						},
						englishname: {
							required: "请输入英文名称",
							
						},
						alias:{
							required:"请输入专业别名",
						},
						degree:{
							required:"请选择学位类型",
						},
					
						
						facultyid: {
							required: "请输入该专业所属院系",
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
								window.location.href="/master/enrollment/acc_dispose_student/index?&label_id=2";
							}else{

								pub_alert_error();
							}
							
						})
						.fail(function() {

							pub_alert_error();
						})
						
						
					}
			
				});
			
});
</script>



<!-- end script -->
<?php $this->load->view('master/public/footer');?>