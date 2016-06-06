<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑模版':'添加模版';
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
		<a href="javascript:;">网站设置</a>
	</li>
	<li>
		<a href="javascript:;">模版设置</a>
	</li>
	<li><a href="javascript:;">模版列表</a></li>
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
				<h3 class="lighter block green"><?=!empty($info)?'编辑模版':'添加模版'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
				</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>template/save">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">模型:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="module_id" class="form-control" name="module_id">
							<option value="">--选择模型-</option>
								<?php if(!empty($moudel)){
									foreach($moudel as $k =>$v){
								?>
								<option value="<?=$k?>" <?=!empty($info->module_id) && $info->module_id == $k?'selected':''?>><?=$v?></option>
								<?php }}?>
							
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">名称:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="name" name="name" value="<?=!empty($info->name) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />(字母，数字，下划线的组合)
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							
								<textarea name="content" class=""  id="content"  boxid="content"   style="width:99%;height:300px;"><?=!empty($info->content) ? $info->content : ''?> </textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注(最长200个字 ):</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<textarea name="remark" id ="remark"  style="width: 345px; height: 86px;"><?=!empty($info->remark) ? $info->remark : ''?> </textarea>
								
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
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
				<input type="hidden" name="theme_id" value="<?=!empty($themeid)?$themeid:''?>">
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

<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						module_id: {
							required: true
							
						},
						name: {
							required: true,
							remote:'/master/cms/template/template_checkname?id=<?=!empty($info->id)?$info->id:''?>&themeid=<?=!empty($themeid)?$themeid:''?>'
						},
						
						desperation: {
							maxlength: 200,
						},
						state: 'required',
						
					},
			
					messages: {
						module_id:{
							required:"请选择模型",
						},
						name:{
							required:"请输入名称",
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
								window.location.href="/master/cms/template/template_list?themeid=<?=$themeid?>";
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