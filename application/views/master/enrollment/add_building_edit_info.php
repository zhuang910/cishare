<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑宿舍楼':'添加宿舍楼';
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
	
	<li class="active">住宿设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit_info' ? '修改' : '添加';
$form_action = $uri4 == 'edit_info' ? 'update' : 'insert_info';

?>
<script src="<?=RES?>master/js/upload.js"></script>	
<?php $this->load->view('master/public/js_css_kindeditor');?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	住宿设置
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green"><?=!empty($info)?'编辑宿舍楼':'添加宿舍楼'?>
					<a href="/master/enrollment/building/index?campid=<?=$campid?>" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/building/insert_info">
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">视频:</label>
	
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  name="video" id="video" value="<?=!empty($info) ? $info->video : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">简介:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<div style="display:none;" id="content_aid_box"></div>
								<textarea name="info" class='content'  id="infos"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info->info)?$info->info:''?></textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<input type="hidden" name="bulidingid" value="<?=!empty($id)?$id:''?>">
				<input type="hidden" name="site_language" value="<?=!empty($_SESSION['language']) ? $_SESSION['language'] : 'cn'?>">
				<div class="space-2"></div>
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
	<!-- ace scripts -->
		<script src="/resource/master/js/ace-extra.min.js"></script>
		<script src="/resource//master/js/ace-elements.min.js"></script>
		<script src="/resource//master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<?php $this->load->view('master/public/js_kindeditor_create')?>

<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						floor_num: {
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
						
						
					},
			
					messages: {
					
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
						$('#info').val($('#editor2').html());
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
								window.location.href="/master/enrollment/building/index?campid=<?=$campid?>";
								
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
<?php $this->load->view('master/public/footer');?>