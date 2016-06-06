<?php
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
	<li class="active">评教时间设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/upload.js"></script>	
<?php $this->load->view('master/public/js_css_kindeditor');?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		评教时间设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="col-xs-12 col-sm-9">
		<label class="line-height-1 blue">
		</label>
			<form class="form-horizontal" id="hour" method="get" action="<?=$zjjp?>settime/save">
				<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">评教开始时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="starttime" class="date-picker" name="starttime" value="<?=!empty($evaluate_time['starttime'])?date('Y-m-d',$evaluate_time['starttime']):''?>">
								</div>
							</div>

						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">评教结束时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="endtime" class="date-picker" name="endtime" value="<?=!empty($evaluate_time['endtime'])?date('Y-m-d',$evaluate_time['endtime']):''?>">
								</div>
							</div>
						</div>
				
					<div class="space-2"></div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">注意事项:</label>
						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
									<div style="display:none;" id="content_aid_box"></div>
									<textarea name="matters" class='content'  id="infos"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($evaluate_time['matters'])?$evaluate_time['matters']:''?></textarea>
									
							</div>
						</div>
					</div>
					<div class="space-2"></div>
				<button class="btn btn-success btn-next" data-last="Finish">
					提 交
				<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
				</button>	
			</form>
		</div>
	</div>
</div>
		
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<?php $this->load->view('master/public/js_kindeditor_create')?>
<script type="text/javascript">
	$(document).ready(function(){

$('#hour').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						hour: {
							required: true
						},
			
					},
			
					messages: {

						hour: "请选择课时。",
	
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
					submitHandler:function(form){

						var data=$(form).serialize();
						data=data.replace(/ur/g,'ur[]');
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data
						})
						.done(function(r) {
							if(r.state == 1){
								pub_alert_success();
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
<!-- end script -->
<?php $this->load->view('master/public/footer');?>