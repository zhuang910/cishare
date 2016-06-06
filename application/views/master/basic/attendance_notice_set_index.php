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
	<li class="active">考勤通知设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>


<script src="<?=RES?>master/js/jquery.validate.min.js"></script>



<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		考勤通知设置
	</h1>
</div><!-- /.page-header -->


	<div class="row">
		<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->


				<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>attendance_notice_set/save">
					<div class="form-group">
						<label class="control-label col-xs-4 col-sm-2 no-padding-right" for="name">考勤警告线:</label>
						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="name" name="warning" value="<?=!empty($arr['warning'])?$arr['warning']:20?>" class="col-xs-7 col-sm-3" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<div class="form-group">
						<label class="control-label col-xs-4 col-sm-2 no-padding-right" for="name">考勤开除线:</label>
						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
								<input type="text" id="name" name="dismiss" value="<?=!empty($arr['dismiss'])?$arr['dismiss']:30?>" class="col-xs-7 col-sm-3" />
							</div>
						</div>
					</div>
					<div class="space-2"></div>
					<div class="col-md-offset-1 col-md-9">
						<button class="btn btn-info" data-last="Finish">
							<i class="ace-icon fa fa-check bigger-110"></i>	
								Submit
						</button>
						<button class="btn" type="reset">
							<i class="ace-icon fa fa-undo bigger-110"></i>
								Reset
						</button>
					</div>
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
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
		
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
								window.location.href="<?=$zjjp?>attendance_notice_set";
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
<!-- end script -->
<?php $this->load->view('master/public/footer');?>