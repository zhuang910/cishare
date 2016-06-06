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
		<a href="javascript:;">申请设置</a>
	</li>
	<li class="active">
		<a href="javascript:;">附件设置</a>
	</li>
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
	附件设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green"><?=!empty($result)?'编辑模版':'添加模版'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>
					<form class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/attachment/attachment_save" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">附件模板名称:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="AttaName" id="AttaName" value="<?=!empty($result['AttaName'])?$result['AttaName']:''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请表模板描述:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<textarea name="des" style="width: 394px; height: 148px;"><?=!empty($result['des'])?$result['des']:''?></textarea>
								</div>
							</div>
						</div>

						<div class="space-2"></div>						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">属性:</label>
							
							<div class="col-xs-12 col-sm-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" class="ace"  name="aKind" id="aKind" <?=!empty($result['aKind']) && $result['aKind'] == 'Y'?'checked':''?>  value="Y" >
										<span class="lbl">设置为默认模版</span>
									</label>
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<input type="hidden" name="atta_id" value="<?=!empty($result['atta_id'])?$result['atta_id']:''?>">
						<div class="col-md-offset-3 col-md-9">
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

			<!-- /section:plugins/fuelux.wizard.container -->
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
<script type="text/javascript">

$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						ClassName: {
							required: true
						}
						
					},
			
					messages: {
						
						
						ClassName: "请填写模版名称",
						
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
						var host=window.location.host;
						
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success()
								//setTimeout(window.location.reload(),3000);
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

<?php $this->load->view('master/public/footer');?>


