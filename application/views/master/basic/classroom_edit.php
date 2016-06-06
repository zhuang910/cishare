<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
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
	<li><a href="index">教室设置</a></li>
	<li>{$title_h3}教室</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>

<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 

$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		<?=$title_h3?>教室
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
										<div class="step-content pos-rel" id="step-container">
											<div class="step-pane active" id="step1">
												<h3 class="lighter block green"><?=!empty($info)?'编辑教室':'添加教室'?>
													<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
														<i class="ace-icon fa fa-reply light-green bigger-130"></i>
													</a>
												</h3>
												
												<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>classroom/<?=$form_action?>">
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">教室名称:</label>
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
																<input type="text" id="englishname" name="englishname" value="<?=!empty($info) ? $info->englishname : ''?>" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">教室地址:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" id="address" name="address" value="<?=!empty($info) ? $info->address : ''?>" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
													
													
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">教室大小:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" id="size" name="size" value="<?=!empty($info) ? $info->size : ''?>" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
											
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right">是否可用</label>
															<div class="col-xs-12 col-sm-9">
																<div>
																	<label class="line-height-1 blue">
																		<input class="ace" type="radio" value="1"  <?=!empty($info) && $info->state == 1 ? 'checked' : ''?> name="state">
																		<span class="lbl"> 可用</span>
																	</label>
																</div>
																<div>
																	<label class="line-height-1 blue">
																		<input class="ace" type="radio" <?=!empty($info) && $info->state == 0 ? 'checked' : ''?> value="0" name="state">
																		<span class="lbl"> 停用</span>
																	</label>
																</div>
															</div>
														</div>
													
													<div class="col-md-offset-3 col-md-9">
														<button class="btn btn-info" data-last="Finish">
															<i class="ace-icon fa fa-check bigger-110"></i>
																Submit
														</button>
														<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
																Reset
														</button>
													</div>
													
													
													</form>
											</div>
										</div>

										<!-- /section:plugins/fuelux.wizard.container -->
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
<!-- script -->
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						englishname: {
							required: true
						},
						name: {
							required: true
						},
						address: {
							required: true
						},
						
						size: {
							required: true,
							
						},
					
						
						
						
						state: 'required',
						
					},
			
					messages: {
						name:{
							required:"请输入教室名称",
						},
						englishname: {
							required: "请输入教室英文名称",
							
						},
						address:{
							required:"请输入教室地址",
						},
						size:{
							required:"请输入教室容量",
						},

						
						
						state: "请选择教室可用状态",
						
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

							window.location.href="/master/basic/classroom";
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