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

	
	<li class="active">在学管理</li>
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
	在学管理统计人数
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green">在学管理统计人数
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
				</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="/master/student/student/tjcounts_do">
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业:</label>
					<div class="col-xs-12 col-sm-4">
						<select name='courseid' class="form-control"  id="courseid">
                                    <option value="0">--请选择 --</option>>
								    <?php foreach($major_info as $item){ ?>
								    <optgroup label="<?=$item['degree_title']?>">
								    <?php foreach($item['degree_major'] as $item_info){ ?>
                                        <option value="<?=$item_info->id?>"><?=$item_info->id?>--<?=$item_info->name?></option>
                                    <?php } ?>
                                    </optgroup>
                                    <?php } ?>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开始时间:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" id="stime" class="form-control date-picker" name="stime" value="">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">结束时间:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" id="etime" class="form-control date-picker" name="etime" value="">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>
				
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
			<table border='1' width="1000" height="auto">
			<tr>
			<th>专业名称</th>
			<th>时间段</th>
			
			<th>报到人数</th>
			
			</tr>
			<tbody id="htmls">
			
			</tbody>
			</table>
		</div>
	</div>
</div>
<script src="<?=RES?>master/js/upload.js"></script>
<!-- ace scripts -->
<script src="/resource/master/js/ace-extra.min.js"></script>
<script src="/resource/master/js/ace-elements.min.js"></script>
<script src="/resource/master/js/ace.min.js"></script>
<!--日期插件-->
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
					
						
						courseid: {
							required: true,
						
						},
						
						
					},
			
					messages: {
						
						courseid:{
							required:"请选择专业",
							
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
						
						var data=$(form).serialize();
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								$('#htmls').html(r.data);
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