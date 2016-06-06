<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑文章':'添加文章';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">信息管理</a>
	</li>
	<li>
		<a href="javascript:;">内容管理</a>
	</li>
	<li><a href="index">文章管理</a></li>
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
<script src="<?=RES?>master/js/upload.js"></script>	
<?php $this->load->view('master/public/js_css_kindeditor');?>
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
				<h3 class="lighter block green"><?=$site_language_admin[$_SESSION['language']]?><?=!empty($info)?'编辑文章':'添加文章'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="/master/cms/news/save">
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">栏目名称:</label>

					<div class="col-xs-12 col-sm-4">
						<select name="columnid" id="columnid">
							<option value="">请选择栏目...</option>
							<?php foreach($column_info as $k => $v){ ?>
							<option value="<?=$v['id']?>" <?=!empty($info->columnid) && $info->columnid == $v['id'] ? 'selected' : ''?>><?=$v['title']?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="title" name="title" value="<?=!empty($info->title) ? $info->title : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div id="msjt" style="display:<?=!empty($info->name) || !empty($info->address) || !empty($info->time)?'':'none'?>;" >
					<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">姓名:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="name" name="name" value="<?=!empty($info->name) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">地点:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="address" name="address" value="<?=!empty($info->address) ? $info->address : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">时间:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" id="time" class="form-control date-picker" name="time" value="<?=!empty($info->time)?date('Y-m-d',$info->time):date('Y-m-d',time())?>">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">外链地址:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="url" name="url" value="<?=!empty($info->url) ? $info->url : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">关键字:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="keywords" name="keywords" value="<?=!empty($info->keywords) ? $info->keywords : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">描述:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							
						
					<textarea placeholder="请输入描述" rows="4" class="span5 ui-wizard-content" id="description" name="description"   style="width: 342px; height: 95px;"><?=!empty($info) ? $info->description : ''?></textarea>
							
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">图片:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<a href="javascript:swfupload('image_pic','image','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo)">
							<img id="image_pic" width="135" height="113" src="<?=!empty($info->image)?$info->image:'/resource/master/images/admin_upload_thumb.png'?>">
						</a>
					</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<div style="display:none;" id="content_aid_box"></div>
								<textarea name="content" class='content'  id="content"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info) ? $info->content : ''?></textarea>
								
						</div>
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
					<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">网站首页:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="import" class="form-control" name="import">
						
							<option value="0"  <?=!empty($info) && $info->import == 0?'selected':''?>>否</option>
							<option value="1" <?=!empty($info) && $info->import == 1?'selected':''?>>是</option>
							
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
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
				<input type="hidden" name="site_language" value="<?=!empty($info) ? $info->site_language : $label_id?>">
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
<script src="/resource/master/js/ace-elements.min.js"></script>
<script src="/resource/master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<?php $this->load->view('master/public/js_kindeditor_create')?>
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
<?php $this->load->view('master/public/footer');?>