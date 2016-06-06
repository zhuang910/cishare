<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑':'添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">活动管理</a>
	</li>

	<li>
		<a href="javascript:;">活动管理</a>
	</li>
	<li class="active">活动管理</li>
	<li>{$r}</li>
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>	

<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php $this->load->view('master/public/js_css_kindeditor');?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	活动管理
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green"><?=!empty($info)?'编辑':'添加'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
				</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="/master/student/activity/editsave">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">中文标题:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="ctitle" name="ctitle" value="<?=!empty($info->ctitle) ? $info->ctitle : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文标题:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="etitle" name="etitle" value="<?=!empty($info->etitle) ? $info->etitle : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开始时间:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd " id="starttime" class="form-control date-picker" name="starttime" value="<?=!empty($info->starttime)?date('Y-m-d H:i',$info->starttime):date('Y-m-d H:i',time())?>">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">结束时间:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" id="endtime" class="form-control date-picker" name="endtime" value="<?=!empty($info->endtime)?date('Y-m-d H:i',$info->endtime):date('Y-m-d H:i',time())?>">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">联系人:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="linkname" name="linkname" value="<?=!empty($content->linkname) ? $content->linkname : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">预算:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="budgeting" name="budgeting" value="<?=!empty($content->budgeting) ? $content->budgeting : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">联系电话:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="linktel" name="linktel" value="<?=!empty($content->linktel) ? $content->linktel : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">地址:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="address" name="address" value="<?=!empty($content->address) ? $content->address : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<!--<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">图片:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<a href="javascript:swfupload('image_pic','image','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo)">
							<img id="image_pic" width="135" height="113" src="<?=!empty($content->image)?$content->image:'/resource/master/images/admin_upload_thumb.png'?>">
						</a>
					</div>
					</div>
				</div>-->
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<div style="display:none;" id="content_aid_box"></div>
								<textarea name="content" class='content'  id="content"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($content->content) ? $content->content : ''?></textarea>
								
						</div>
					</div>
				</div>
				<input type="hidden" name="id" value="<?=$id?>">
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn btn-info">
						<i class="ace-icon fa fa-check bigger-110"></i>
							提交
					</button>
					<button class="btn" type="reset">
						<i class="ace-icon fa fa-undo bigger-110"></i>
							重置
					</button>
				</div>
			</form>

		</div>
	</div>
</div>
<script src="<?=RES?>master/js/upload.js"></script>
<!-- ace scripts -->
<script src="/resource/master/js/ace-extra.min.js"></script>
<script src="/resource/master/js/ace-elements.min.js"></script>
<script src="/resource/master/js/ace.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<!-- page specific plugin scripts editor -->
<?php $this->load->view('master/public/js_kindeditor_create')?>
<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true,
		minuteStep: 1,
		showSeconds: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						ctitle: {
							required: true
						},
						etitle: {
							required: true
						},
						
						
						
					},
			
					messages: {
						ctitle:{
							required:"请输入标题",
						},
						
						etitle:{
							required:"请输入标题",
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
								window.location.href="/master/student/activity/index?label_id=6";
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

<!-- script -->
<?php $this->load->view('master/public/footer');?>