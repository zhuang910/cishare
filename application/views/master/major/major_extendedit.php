<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑专业属性':'添加专业属性';
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
		<a href="javascript:;">申请设置</a>
	</li>
	<li><a href="index">专业设置</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<?php 

$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		专业设置
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
										<div class="step-content pos-rel" id="step-container">
											<div class="step-pane active" id="step1">
											<h3 class="lighter block green">属性编辑
													<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
														<i class="ace-icon fa fa-reply light-green bigger-130"></i>
													</a>
												</h3>
												
												<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>major/major/save_extend">
													<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开学日期:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" id="opentime" class="form-control date-picker" name="opentime" value="<?=!empty($info->opentime)?date('Y-m-d',$info->opentime):''?>">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请截止日期:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" id="endtime" class="form-control date-picker" name="endtime" value="<?=!empty($info->endtime)?date('Y-m-d',$info->endtime):''?>">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学制:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="schooling" name="schooling" value="<?=!empty($info) ? $info->schooling : ''?>" class="col-xs-12 col-sm-1" onkeyup="schooling_xzunit()"/> 
							<?php if(!empty($publics['program_unit'])){
								foreach($publics['program_unit'] as $k => $v){
							?>
							<label class="line-height-1 blue">
								<input class="ace" type="radio" value="<?=$k?>"  <?=!empty($info) && $info->xzunit == $k ? 'checked' : ''?> name="xzunit">
								<span class="lbl" data="xzunit"> <?=$v?><?php if(!empty($info->schooling) && $info->schooling > 1){?><span class="zyj">s</span><?php }?></span>
							</label>

							<?php }}?>
							
						</div>

					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学费:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="tuition" name="tuition" value="<?=!empty($info) ? $info->tuition : ''?> "class="col-xs-12 col-sm-5" /> RMB
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">指定申请费:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="applytuition" name="applytuition" value="<?=!empty($info) ? $info->applytuition : ''?> "class="col-xs-12 col-sm-5" /> 
							RMB<!-- <input type="radio" name="danwei" value="1" <?=empty($info->danwei) || $info->danwei == 1?'checked':''?>>USD -->
							<!-- <input type="radio" name="danwei" value="2" <?=!empty($info->danwei) && $info->danwei == 2?'checked':''?>>RMB -->
							<input type="hidden" name="danwei" value="2">
						</div>
					</div>
				</div>

				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">授课语言:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="language" class="form-control" name="language">
							<option value="">--请选择--</option>
							<?php if(!empty($publics['language'])){
								foreach ($publics['language'] as $key => $value) {
							?>
								<option value="<?=$key?>" <?=!empty($info) && $info->language== $key?'selected':''?>><?=$value?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">HSK要求:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="hsk" class="form-control" name="hsk">
							<option value="">--请选择--</option>
							<?php if(!empty($publics['hsk'])){
								foreach ($publics['hsk'] as $h => $s) {
							?>
								<option value="<?=$h?>" <?=!empty($info) && $info->hsk== $h?'selected':''?>><?=$s?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">最低学历要求:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="minieducation" class="form-control" name="minieducation">
							<option value="">--请选择--</option>
							<?php if(!empty($publics['education'])){
								foreach ($publics['education'] as $e => $d) {
							?>
								<option value="<?=$e?>" <?=!empty($info) && $info->minieducation== $e?'selected':''?>><?=$d?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否可申请:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="isapply" class="form-control" name="isapply">
							<option value="">--请选择--</option>
							<?php if(!empty($publics['isapply'])){
								foreach ($publics['isapply'] as $i => $p) {
							?>
								<option value="<?=$i?>" <?=!empty($info) && $info->isapply== $i?'selected':''?>><?=$p?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">录取难度:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="difficult" class="form-control" name="difficult">
							<option value="">--请选择--</option>
							<?php if(!empty($publics['difficult'])){
								foreach ($publics['difficult'] as $f => $t) {
							?>
								<option value="<?=$f?>" <?=!empty($info) && $info->difficult== $f?'selected':''?>><?=$t?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请模版:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="applytemplate" class="form-control" name="applytemplate">
							<option value="">--请选择--</option>
							<?php if(!empty($applytemplate)){
								foreach ($applytemplate as $f => $t) {
							?>
								<option value="<?=$f?>" <?=!empty($info) && $info->applytemplate== $f?'selected':''?>><?=$t?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">附件模版:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="attatemplate" class="form-control" name="attatemplate">
							<option value="">--请选择--</option>
							<?php if(!empty($attatemplate)){
								foreach ($attatemplate as $f => $t) {
							?>
								<option value="<?=$f?>" <?=!empty($info) && $info->attatemplate== $f?'selected':''?>><?=$t?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="orderby" name="orderby" value="<?=!empty($info) ? $info->orderby : ''?> "class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>

			<!-- 	<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">视频:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="video" name="video" value="<?=!empty($info) ? $info->video : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div> -->
				<input type="hidden" name="id"  value="<?=!empty($info->id)?$info->id:''?>">
				<div class="space-2"></div>
													
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
		
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>

<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){	
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						opentime: {
							required: true,
							
						},
	
						endtime: {
							required: true
						},
						schooling: {
							required: true
						},
						tuition: {
							required: true
						},
						applytuition: {
							required: true
						},
						language: {
							required: true,
							
						},
						isapply: {
							required: true
						},
						
					},
			
					messages: {
						opentime:{
							required:"请选择开始时间",
						},
						endtime: {
							required: "请选择结束时间",
							
						},
						schooling:{
							required:"请输入学制",
						},
						tuition:{
							required:"请输入学费",
						},
						applytuition:{
							required:"请输入申请费",
						},
						language: {
							required: "请输入授课语言",
							
						},
						
						isapply: {
							required: "是否可申请",
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
								window.location.href="<?=$zjjp?>major/major";
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

<script type="text/javascript">
<!--添加删除s-->
	function schooling_xzunit(){
		var schooling = $('#schooling').val();
		var flag = false;
		if(schooling != ''){
			if(schooling > 1){
				flag = true;
			}
			
			if(schooling.indexOf('-') > 0){
				flag = true;
			}
			
			
			if(flag){
					var xzunit = $("[data='xzunit']");
					var ss = '<span class="zyj">s</span>';
					xzunit.each(function(i,v){
						var s = $(v).find(".zyj")
						
						$(s).remove();
						$(v).append(ss);
					});
			}else{
				var xzunit = $("[data='xzunit']");
			xzunit.each(function(i,v){
				
				 var s = $(v).find(".zyj")
				
				$(s).remove();
			});
			}
		}else{
			var xzunit = $("[data='xzunit']");
			xzunit.each(function(i,v){
				
				 var s = $(v).find(".zyj")
				
				$(s).remove();
			});
		}
	}
</script>



<!-- end script -->
<?php $this->load->view('master/public/footer');?>