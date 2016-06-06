<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r='编辑内容';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">内容管理</a>
	</li>

	<li>
		<a href="javascript:;">$bread_3</a>
	</li>
	<li class="active">$bread_2</li>
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
	<?=$bread_1?>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green">编辑内容
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
				</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>ppt/pages_save">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="title" name="title" value="<?=!empty($info->title) ? $info->title : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序（倒序排列）:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="orderby" name="orderby" value="<?=!empty($info->orderby) ? $info->orderby : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否置顶:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="istop" class="form-control" name="istop">
							<option value="0"  <?=empty($info->istop) || $info->istop == 0?'selected':''?>>不置顶</option>
							<option value="1" <?=!empty($info->istop) && $info->istop == 1?'selected':''?>>置顶</option>
							
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">摘要(最长200个字 ):</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<textarea name="desperation" id ="desperation"  style="width: 345px; height: 86px;"><?=!empty($info->desperation) ? $info->desperation : ''?> </textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">关键字(最长200个字 ):</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<textarea name="keywords" id ="keywords"  style="width: 345px; height: 86px;"><?=!empty($info->keywords) ? $info->keywords : ''?> </textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<div style="display:none;" id="content_aid_box"></div>
								<textarea name="content" class=""  id="content"  boxid="content"   style="width:99%;height:300px;visibility:hidden;"><?=!empty($info->content) ? $info->content : ''?> </textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">启用视频:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="checkbox" name="isvideo"  id="isvideo" value="1" <?=!empty($info->isvideo) && $info->isvideo == 1?'checked':''?>>	
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">视频地址:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="video" name="video" value="<?=!empty($info->video) ? $info->video : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="state" class="form-control" name="state">
							<option value="1" <?=!empty($info) && $info->state == 1?'selected':''?>>启用</option>
							<option value="0"  <?=!empty($info) && $info->state == 0?'selected':''?>>停用</option>
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发布时间:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" id="createtime" class="form-control date-picker" name="createtime" value="<?=!empty($info->createtime)?date('Y-m-d',$info->createtime):date('Y-m-d',time())?>">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<input type="hidden" name="columnid" value="<?=!empty($columnid)?$columnid:''?>">
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
<script src="<?=RES?>master/js/upload.js"></script>
<!-- ace scripts -->
<script src="/resource/master/js/ace-extra.min.js"></script>
<script src="/resource/master/js/ace-elements.min.js"></script>
<script src="/resource/master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<?php $this->load->view('master/public/js_kindeditor_create')?>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>

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
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								//window.location.href="/master/cms/ppt?columnid=<?=$columnid?>";
								window.history.back();
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