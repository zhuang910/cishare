<?php
$r=!empty($info)?'编辑':'添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">基础设置</a>
	</li>
	<li><a href="index">课程管理</a></li>
	<li>{$r} {$site_language_admin[$site_language]} 课程 </li>
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
	课程管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<h3 class="lighter block green"><?=!empty($info)?'编辑':'添加'?><?=$site_language_admin[$site_language]?>&nbsp;课程
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>		
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>course/savecourse">
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">课程名称:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text" id="langname" name="langname" value="<?=!empty($info) ? $info->langname : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">课程亮点:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor1"><?=!empty($info) ? $info->feature : ''?></div>
							<input type="hidden" name="feature" id="feature" value="">
					</div>
				</div>

				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">课程概述:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor2"><?=!empty($info) ? $info->introduce : ''?></div>
							<input type="hidden" name="introduce" id="introduce" value="">
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">入学要求:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor3"><?=!empty($info) ? $info->requirement : ''?></div>
							<input type="hidden" name="requirement" id="requirement" value="">
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请材料:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor4"><?=!empty($info) ? $info->applymaterial : ''?></div>
							<input type="hidden" name="applymaterial" id="applymaterial" value="">
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实训基地:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor5"><?=!empty($info) ? $info->kctd : ''?></div>
							<input type="hidden" name="kctd" id="kctd" value="">
					</div>
				</div>

				<div class="space-2"></div>
			<input type="hidden" name="courseid" value="<?=!empty($id)?$id:''?>">
			<input type="hidden" name="site_language" value="<?=!empty($site_language)?$site_language:''?>">
				<div class="space-2"></div>
				<div class="col-md-offset-3 col-md-9">
					<button class="btn btn-info" data-last="Finish">
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
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>


<!-- script -->
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						langname: {
							required: true
						},
						
						
						
						
						
					},
			
					messages: {
						langname:{
							required:"请输入课程名称",
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
						$('#feature').val($('#editor1').html());
						$('#introduce').val($('#editor2').html());
						$('#requirement').val($('#editor3').html());
						$('#applymaterial').val($('#editor4').html());
						$('#kctd').val($('#editor5').html());
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
								window.location.href="<?=$zjjp?>course";
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
jQuery(function($) {
		var cucaseditor = ['#editor1','#editor2','#editor3','#editor4','#editor5'];
		$.each(cucaseditor,function(i,v){
			$(v).ace_wysiwyg({
					toolbar:
					[
						{
							name:'font',
							title:'Custom tooltip',
							values:['Some Font!','Arial','Verdana','Comic Sans MS','Custom Font!']
						},
						null,
						{
							name:'fontSize',
							title:'Custom tooltip',
							values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text'} 
						},
						null,
						{name:'bold', title:'Custom tooltip'},
						{name:'italic', title:'Custom tooltip'},
						{name:'strikethrough', title:'Custom tooltip'},
						{name:'underline', title:'Custom tooltip'},
						null,
						'insertunorderedlist',
						'insertorderedlist',
						'outdent',
						'indent',
						null,
						{name:'justifyleft'},
						{name:'justifycenter'},
						{name:'justifyright'},
						{name:'justifyfull'},
						null,
						{
							name:'createLink',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-purple',
							button_text:'Custom TEXT'
						},
						{name:'unlink'},
						null,
						{
							name:'insertImage',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-inverse',
							//choose_file:false,//hide choose file button
							button_text:'Set choose_file:false to hide this',
							button_insert_class:'btn-pink',
							button_insert:'Insert Image'
						},
						null,
						{
							name:'foreColor',
							title:'Custom Colors',
							values:['red','green','blue','navy','orange'],
							/**
								You change colors as well
							*/
						},
						/**null,
						{
							name:'backColor'
						},*/
						null,
						{name:'undo'},
						{name:'redo'},
						null,
						'viewSource'
					],
					//speech_button:false,//hide speech button on chrome
					
					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
					
				}).prev().addClass('wysiwyg-style2');
		});
				

				
				
	});
			

</script>