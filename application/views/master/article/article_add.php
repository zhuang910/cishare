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
	<li class="active">文章管理</li>
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
<?php $this->load->view('master/public/js_css_ueditor1_4_3_3');?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	文章管理
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
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>article/article/save">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-2 no-padding-right" for="name">标题:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="title" name="title" value="<?=!empty($info->title) ? $info->title : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-2 no-padding-right" for="name">是否显示:</label>
					<div class="col-xs-12 col-sm-4">
						<select id="is_show" class="form-control" name="is_show">
							<option value="1" <?=!empty($info) && $info->is_show == 1?'selected':''?>>显示</option>
							<option value="2"  <?=!empty($info) && $info->is_show == 2?'selected':''?>>隐藏</option>

						</select>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-2 no-padding-right" for="name">文章分类:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text"  id="pid_name" value="<?=!empty($info->pid_name) ? $info->pid_name : ''?>" readonly class="col-xs-12 col-sm-3" />
							&nbsp;<label class="control-label"><a id="menuBtn" href="javascript:;" onclick="showMenu(); return false;">选择</a></label>
							<input type="hidden"  id="pid" name="pid" value="<?=!empty($info->pid) ? $info->pid : ''?>" class="col-xs-12 col-sm-3" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-2 no-padding-right" for="name">内容:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<div style="display:none;" id="content_aid_box"></div>
								<textarea name="content" class='content'  id="content"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info) ? $info->content : ''?></textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
				<input type="hidden" name="columnid" value="<?=!empty($columnid)?$columnid:''?>">
				<div class="space-2"></div>
				<div class="col-md-offset-2 col-md-9">
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
<?php $this->load->view('master/public/js_ueditor1_4_3_3_create')?>

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
			is_show: 'required',
		},

		messages: {
			title:{
				required:"请输入标题",
			},
			is_show: "请选择显示类型",

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
					window.location.href="/master/article/article/index";
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