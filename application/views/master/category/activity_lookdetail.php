<?php

$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">在学管理</a>
	</li>
	<li>
		<a href="javascript:;">活动管理</a>
	</li>
	<li><a href="index">活动审核</a></li>
	
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>

<script src="<?=RES?>master/js/upload.js"></script>	
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
				
			<form class="form-horizontal" id="validation-form" method="post" action="/master/student/activity/docheck">
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">汉语标题:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="ctitle" name="ctitle" value="<?=!empty($info['ctitle']) ? $info['ctitle'] : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文标题:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="etitle" name="etitle" value="<?=!empty($info['etitle']) ? $info['etitle'] : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开始时间:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="starttime" name="starttime" value="<?=!empty($info['starttime']) ? date('Y-m-d H:i',$info['starttime']) : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">结束时间:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="endtime" name="endtime" value="<?=!empty($info['endtime']) ? date('Y-m-d H:i',$info['endtime']) : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发布者:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="username" name="username" value="<?=!empty($info['username']) ? $info['username'] : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否接受报名:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="isapply" name="isapply" value="<?=!empty($info['isapply']) ? '是' : '否'?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">联系人:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="linkname" name="linkname" value="<?=!empty($content['linkname']) ? $content['linkname'] : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">联系电话:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="linktel" name="linktel" value="<?=!empty($content['linktel']) ? $content['linktel'] : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">预算:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="budgeting" name="budgeting" value="<?=!empty($content['budgeting']) ? $content['budgeting'] : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">活动地点:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="address" name="address" value="<?=!empty($content['address']) ? $content['address'] : ''?>" disabled class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<div style="display:none;" id="content_aid_box"></div>
								<textarea name="content" disabled class='content'  id="content"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($content['content']) ? $content['content'] : ''?></textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">审核:</label>
					
					<div class="col-xs-12 col-sm-4">
						<input type="radio" name="state" value="1" <?=!empty($info['state'])&& $info['state'] == 1?'checked':''?>> 通过
						<input type="radio" name="state" value="2" <?=!empty($info['state'])&& $info['state'] == 2?'checked':''?>> 不通过
					</div>
				</div>
				<div class="space-2"></div>
					
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">打回意见:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							
						
					<textarea  rows="4" class="span5 ui-wizard-content" id="auditopinion" name="auditopinion"   style="width: 342px; height: 95px;"><?=!empty($info['auditopinion'])?$info['auditopinion']:''?></textarea>
							
						</div>
					</div>
				</div>
				<input type="hidden" name="id" value="<?=!empty($info['id'])?$info['id']:''?>">
				
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