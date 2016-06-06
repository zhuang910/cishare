<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '编辑' : '添加';
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
		<a href="javascript:;">在学设置</a>
	</li>
	<li><a href="index">课程设置</a></li>
	<li>{$title_h3}课程</li>
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
$title_h3 = $uri4 == 'edit' ? '编辑' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	<?=$title_h3?>课程
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<h3 class="lighter block green"><?=!empty($info)?'编辑课程':'添加课程'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>course/<?=$form_action?>">
			
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">课程名称:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文名称:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="englishname" name="englishname" value="<?=!empty($info) ? $info->englishname : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>



				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">课时:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="hour" name="hour" value="<?=!empty($info) ? $info->hour : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				

				

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">缺勤通知线:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="absenteeism" name="absenteeism" value="<?=!empty($info) ? $info->absenteeism : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开除通知线:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="expel" name="expel" value="<?=!empty($info) ? $info->expel : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学分:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="credit" name="credit" value="<?=!empty($info) ? $info->credit : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right">是否选课</label>
					<div class="col-xs-12 col-sm-9">
						<div>
							<label class="line-height-1 blue">
								<input class="ace" onchange="bixiu()" type="radio" value="1"  <?=!empty($info) && $info->variable == 1 ? 'checked' : ''?> name="variable">
								<span class="lbl"> 必修</span>
							</label>
						</div>
						<div>
							<label class="line-height-1 blue">
								<input class="ace" onchange="xuanxiu()" type="radio" <?=!empty($info) && $info->variable == 0 ? 'checked' : ''?> value="0" name="variable">
								<span class="lbl"> 选修</span>
							</label>
						</div>
					</div>
				</div>
				
				<div class="space-2"></div>
				
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">第几个学期开课:</label>
						
						<div class="col-xs-12 col-sm-7">
							<table class="table table-striped table-bordered">
								<tbody>
									<tr>
										<?php for($i=1;$i<=10;$i++):?>
											<?php
												if(!empty($info)&&!empty($info->term_start)){
													$arr=json_decode($info->term_start);
													$checked='';
													foreach ($arr as $k => $v) {
														if($v==$i){
															$checked='checked';
														}
													}
												}
											?>
											<?php if($i==6):?>
												</tr>
												<tr>
												<td>
													<label class="line-height-1 blue">
														<input class="ace" type="checkbox" <?=!empty($checked)?$checked:''?> value="<?=$i?>" name="term_start[]">
														<span class="lbl"> 第<?=$i?>学期</span>
													</label>
												</td>
											<?php else:?>
												<td>
													<label class="line-height-1 blue">
														<input class="ace" type="checkbox" <?=!empty($checked)?$checked:''?> value="<?=$i?>" name="term_start[]">
														<span class="lbl"> 第<?=$i?>学期</span>
													</label>
												</td>
												
											<?php endif;?>
										<?php endfor;?>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="space-2"></div>
				<div id='xuanxiu_div' <?=!empty($info)&&$info->variable==0?'':'style="display:none"'?> >
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">人数上限:</label>
						<div class="col-xs-12 col-sm-4">
							<input type="text" id="size" name="size" value="<?=!empty($info) ? $info->size : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
					<div class="space-2"></div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="state" class="form-control" name="state">
							<option value="1" <?=!empty($info) && $info->state == 1?'selected':''?>>启用</option>
							<option value="0"  <?=!empty($info) && $info->state == 0?'selected':''?>>禁用</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否评教:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="is_evaluate" class="form-control" name="is_evaluate">
							<option value="1" <?=!empty($info) && $info->is_evaluate == 1?'selected':''?>>是</option>
							<option value="0"  <?=!empty($info) && $info->is_evaluate == 0?'selected':''?>>否</option>
						</select>
					</div>
				</div>
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
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
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
function xuanxiu(){
	$('#xuanxiu_div').removeAttr('style');
}
function bixiu(){
	$('#xuanxiu_div').attr({
		'style':'display:none;'
	});
}
	function sel_majorid(){
		var degreeid = $('#degreeid').val();
		
		if(degreeid != ''){
			$.ajax({
				url: '/master/basic/course/sel_majorid?degreeid='+degreeid,
				type: 'GET',
				dataType: 'json',
			})
			.done(function(r) {
				if(r.state == 1){
					$('#majorid').html('');
					$('#majorid').html(r.data);
				}
			})
			.fail(function() {
				console.log("error");
			})
			
			
		}
	}

</script>
<!-- script -->
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						englishname: {
							required: true
						},
						name: {
							required: true
						},
						programid: {
							required: true
						},
						
						absenteeism: {
							required: true,
							
						},
						expel: {
							required: true,
							
						},
						hour: {
							required: true,
							
						},
						
						
						
						state: 'required',
						
					},
			
					messages: {
						name:{
							required:"请输入课程名称",
						},
						englishname: {
							required: "请输入课程英文名称",
							
						},
						programid:{
							required:"请输入专业名称",
						},
						absenteeism:{
							required:"请输入缺勤通知线",
						},
						expel:{
							required:"请输入开除通知线",
						},
						hour:{
							required:"请输入课程课时",
						},
						
						state: "请选择教室可用状态",
						
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
		var cucaseditor = ['#editor1','#editor2','#editor3','#editor4'];
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

<script type="text/javascript">
<!--添加删除s-->
	function schooling_xzunit(){
		var schooling = $('#schooling').val();
		if(schooling != '' && schooling > 1){
			var xzunit = $("[data='xzunit']");
			var ss = '<span class="zyj">s</span>';
			xzunit.each(function(i,v){
				var s = $(v).find(".zyj")
				
				$(s).remove();
				$(v).append(ss);
			});
		}else{
			var xzunit = $("[data='xzunit']");
			xzunit.each(function(i,v){
				
				 var s = $(v).find(".zyj")
				
				$(s).remove();
			});
		}
	}
</script>

<?php $this->load->view('master/public/footer');?>