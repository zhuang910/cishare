<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑教师':'添加教师';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">在学设置</a>
	</li>
	<li><a href="index">教师设置</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!-- bootstrap & fontawesome -->
		
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 

$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '编辑' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		<?=$r?>
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
		

					<!-- #section:plugins/fuelux.wizard.container -->
										<div class="step-content pos-rel" id="step-container">
											<div class="step-pane active" id="step1">
											<h3 class="lighter block green"><?=$title_h3?>教师
											<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
														<i class="ace-icon fa fa-reply light-green bigger-130"></i>
											</a>
											</h3>

											

												<div style="clear:both;"></div>
												<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>teacher/teacher/<?=$form_action?>">
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">教师名称:</label>
														<input type="hidden" name="id" value="<?=!empty($info) ? $info->id : ''?>">
														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

												
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文名称:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" id="englishname" name="englishname" value="<?=!empty($info) ? $info->englishname :''?> "class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">老师邮箱:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="email" name="email" id="email" value="<?=!empty($info) ? $info->email : ''?>" class="col-xs-12 col-sm-6" />
															</div>
														</div>
													</div>
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="phone">老师手机:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="input-group">
																<span class="input-group-addon">
																	<i class="ace-icon fa fa-phone"></i>
																</span>

																<input type="tel" id="phone" value="<?=!empty($info) ? $info->phone : ''?>" name="phone" />
															</div>
														</div>
													</div>
												
												
												
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">职称:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" id="post" name="post" value="<?=!empty($info) ? $info->post :''?> "class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>
													
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">介绍:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<textarea name="content" data-provide="markdown" style="width: 500px;height: 200px;"><?=!empty($info) ? $info->content :''?></textarea>
															</div>
														</div>
													</div>
	
													<div class="space-2"></div>
													<div class="col-md-offset-3 col-md-9">
														<button class="btn btn-info" data-last="Finish">
															<i class="ace-icon fa fa-check bigger-110"></i>
																提交
														</button>
														<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
																重置
														</button>
													</div>

													
		</div>
	</div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script src="<?=RES?>master/js/markdown/markdown.min.js"></script>
<script src="<?=RES?>master/js/markdown/bootstrap-markdown.min.js"></script>
		

<script type="text/javascript">
$(document).ready(function(){

		
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						email: {
							required: true,
							
						},
	
						englishname: {
							required: true
						},
						name: {
							required: true
						},
						phone: {
							required: true
						},
						post: {
							required: true
						},
						
					
						
						
					
						
					},
			
					messages: {
						name:{
							required:"请输入专业名称",
						},
						englishname: {
							required: "请输入英文名称",
							
						},
						email:{
							required:"请输入正确的邮箱格式",
						},
						phone:{
							required:"请输入手机号码",
						},
					
						
						
						post: {
							required: "请输入职位",
						},
					
						
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
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="<?=$zjjp?>teacher/teacher";
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
$('#back').click(function() {
	window.location.href="<?=$zjjp?>teacher/teacher";
});
</script>


	
		
	
<!-- end script -->
<?php $this->load->view('master/public/footer');?>