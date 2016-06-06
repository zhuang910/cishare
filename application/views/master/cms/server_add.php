<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑':'添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">后台</a>
	</li>

	<li>
		<a href="javascript:;">内容管理</a>
	</li>
	<li>
		<a href="javascript:;">服务和项目</a>
	</li>
	
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
	服务和项目
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
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>server/save">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">类型:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="type" class="form-control" name="type">
							<option value="">--请选择--</option>
							<?php if(!empty($type)){
								foreach($type as $k => $v){
							?>
							<option value="<?=$k?>" <?=!empty($info->type) && $info->type == $k ?'selected':''?>><?=$v?></option>
							<?php }}?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">国别:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="country" class="form-control" name="country">
							<option value="">--请选择--</option>
							<?php if(!empty($country)){
								foreach($country as $kc => $vc){
							?>
							<option value="<?=$kc?>" <?=!empty($info->country) && $info->country == $kc ?'selected':''?>><?=$vc?></option>
							<?php }}?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">目的:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="purpose" class="form-control" name="purpose">
							<option value="">--请选择--</option>
							<?php if(!empty($purpose)){
								foreach($purpose as $kp => $vp){
							?>
							<option value="<?=$kp?>" <?=!empty($info->purpose) && $info->purpose == $kp ?'selected':''?>><?=$vp?></option>
							<?php }}?>
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
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否跳转:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="checkbox" name="isjump"  id="isjump" value="1" <?=!empty($info->isjump) && $info->isjump == 1?'checked':''?>>	
						</div>
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
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">关键字(用逗号隔开):</label>
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
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">上传主图:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<a href="javascript:swfupload('image_pic','image','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo)">
								<img id="image_pic" src="<?=!empty($info->image)?$info->image:'/resource/master/images/admin_upload_thumb.png'?>" width="135" height="113">
							</a>
								
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">主图介绍:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<textarea name="info" id ="info"  style="width: 345px; height: 86px;"><?=!empty($info->info) ? $info->info : ''?> </textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否在首页显示:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="radio" name="isindex" id="isindex" value="0" <?=empty($info->isindex) || $info->isindex == 0?'checked':''?> onclick="is_index(0)">否
								<input type="radio" name="isindex" id="isindex" value="1" <?=!empty($info->isindex) && $info->isindex == 1?'checked':''?> onclick="is_index(1)">是
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div id="is_index" style="display:<?=!empty($info->isindex) && $info->isindex == 1?'block':'none'?>">
					
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">上传焦点图:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<a href="javascript:swfupload('image_pic_imageindex','imageindex','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo)">
								<img id="image_pic_imageindex" src="<?=!empty($info->imageindex)?$info->imageindex:'/resource/master/images/admin_upload_thumb.png'?>" width="135" height="113">
							</a>
								
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">焦点图介绍:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<textarea name="infoindex" id ="infoindex"  style="width: 345px; height: 86px;"><?=!empty($info->infoindex) ? $info->infoindex : ''?> </textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				
				
				</div>
				
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
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name"></label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="radio" name="begining" id="begining" value="1" <?=empty($info->begining) || $info->begining == 1?'checked':''?>>项目进行中
								<input type="radio" name="begining" id="begining" value="-1" <?=!empty($info->begining) && $info->begining == -1?'checked':''?>>项目已结束
								
								
						</div>
					</div>
				</div>
				
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
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
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
<script type="text/javascript">
	function is_index(s){
		if(s == 1){
			$('#is_index').show('slow');
		}else{
			$('#is_index').hide('slow');
		}
	}
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
								window.location.href="/master/cms/server?columnid=<?=$columnid?>";
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