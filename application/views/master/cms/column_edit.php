<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑模型':'添加模型';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">信息管理</a>
	</li>

	<li><a href="index">模型管理</a></li>
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
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	栏目管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green"><?=!empty($info)?'编辑栏目':'添加栏目'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>column/<?=$form_action?>">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">请选择模型:</label>
					<div class="col-xs-12 col-sm-4">
						<select id="module_id" class="form-control" name="module_id" onchange="select_template()">
						<option value="">--请选择模型--</option>
						<?php foreach($module_info as $k=>$v):?>
							<option <?=!empty($info) && $info->module_id == $v['id']?'selected':''?> value="<?=$v['id']?>"><?=$v['title']?></option>
						<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">上级栏目:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="parent_id" class="form-control" name="parent_id">
						<option value="0">作为一级栏目</option>
						<?=$select_categorys?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">模型标题:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="title" name="title" value="<?=!empty($info->title) ? $info->title : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">外链:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="admin_menu" name="admin_menu" value="<?=!empty($info->admin_menu) ? $info->admin_menu : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group" id="template_list" style="display:<?=!empty($info->template_lists) ?'block':'none'?>;">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">选择列表模版:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="template_lists" class="form-control" name="template_lists">
							<option value="">--选择列表模版--</option>
							<?php if(!empty($template_lists)){
								foreach($template_lists as $kl => $vl){
							?>
							<option value="<?=$vl['name']?>" <?=!empty($info->template_lists) && $info->template_lists == $vl['name']?'selected':''?>><?=$vl['name']?></option>
							
							<?php }}?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group" id="template_ppt" style="display:<?=!empty($info->template_partic)&& $info->module_id == 4 ?'block':'none'?>;">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">选择ppt模版:</label>
					
					<div class="col-xs-12 col-sm-4">
					<?php if(!empty($template_ppt)){?>
						<select id="" class="form-control" name="template_partic">
							<option value="">--选择ppt模版--</option>
							<?php 
								foreach($template_ppt as $kt => $vt){
							?>
							<option value="<?=$vt['name']?>" <?=!empty($info->template_partic) && $info->template_partic == $vt['name']?'selected':''?>><?=$vt['name']?></option>
							
							<?php }?>
						</select>
						<?php }?>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group" id="template_images" style="display:<?=!empty($info->template_partic) && $info->module_id == 5 ?'block':'none'?>;">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">选择图集模版:</label>
					
					<div class="col-xs-12 col-sm-4">
					<?php if(!empty($template_images)){?>
						<select id="" class="form-control" name="template_partic">
							<option value="">--选择图集模版--</option>
							<?php 
								foreach($template_images as $kt => $vt){
							?>
							<option value="<?=$vt['name']?>" <?=!empty($info->template_partic) && $info->template_partic == $vt['name']?'selected':''?>><?=$vt['name']?></option>
							
							<?php }?>
						</select>
						<?php }?>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group" id="template_pages" style="display:<?=!empty($info->template_detail) ?'block':'none'?>;">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">选择单页模版:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="template_detail" class="form-control" name="template_detail">
							<option value="">--请选择单页模版--</option>
							<?php if(!empty($template_pages)){
								foreach($template_pages as $kp => $vp){
							?>
							<option value="<?=$vp['name']?>" <?=!empty($info->template_detail) && $info->template_detail == $vp['name']?'selected':''?>><?=$vp['name']?></option>
							
							<?php }}?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否跳转:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="isjump" class="form-control" name="isjump">
							<option value="1" <?=!empty($info) && $info->isjump == 1?'selected':''?>>是</option>
							<option value="0"  <?=!empty($info) && $info->isjump == 0||empty($info)?'selected':''?>>否</option>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">跳转地址:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="jumpurl" name="jumpurl" value="<?=!empty($info->jumpurl) ? $info->jumpurl : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<?php if(!empty($language)){?>
					<?php foreach($language as $k=>$v){?>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name"><?=$v?>:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
										<input type="text"  id="<?=$v?>" name="lang[<?=$v?>]" value="<?=!empty($result->lang[$v])?$result->lang[$v]:''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
				<?php }}?>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否显示:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="isshow" class="form-control" name="isshow">
							<option value="1" <?=!empty($info) && $info->isshow == 1?'selected':''?>>是</option>
							<option value="0"  <?=!empty($info) && $info->isshow == 0?'selected':''?>>否</option>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="orderby" name="orderby" value="<?=!empty($info->orderby) ? $info->orderby : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="state" class="form-control" name="state">
							<option value="1" <?=!empty($info) && $info->state == 1?'selected':''?>>启用</option>
							<option value="0"  <?=!empty($info) && $info->state == 0?'selected':''?>>禁用</option>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
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
						table: {
							required: true
						},
						table_intro: {
							required: true
						},
						
						unique: {
							required: true,
							
						},
						state: 'required',
						
					},
			
					messages: {
						title:{
							required:"请输入模型标题",
						},
						table: {
							required: "请输入模型表名",
							
						},
						table_intro:{
							required:"请输入模型简介",
						},
						unique:{
							required:"请输入唯一标识",
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
								window.location.href="<?=$zjjp?>column";
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

<script type="text/javascript">
function select_template(){
	var module_id = $('#module_id').val();
	if(module_id == 2){
		$('#template_pages').show('slow');
		$('#template_list').hide('slow');
		$('#template_ppt').hide('slow');
		$('#template_images').hide('slow');
	}else if(module_id == 3){
		$('#template_pages').show('slow');
		$('#template_list').show('slow');
		$('#template_ppt').hide('slow');
		$('#template_images').hide('slow');
	}else if(module_id == 4){
		$('#template_pages').show('slow');
		$('#template_list').show('slow');
		$('#template_ppt').show('slow');
		$('#template_images').remove();
	}else if(module_id == 5){
		$('#template_pages').show('slow');
		$('#template_list').show('slow');
		$('#template_ppt').remove();
		$('#template_images').show('slow');
	}
}
</script>

<!-- script -->
<?php $this->load->view('master/public/footer');?>