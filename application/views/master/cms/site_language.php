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
		<a href="javascript:;">网站设置</a>
	</li>
	<li class="active">多语言设置</li>
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
		多语言设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green">语言开关设置
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>

					<form class="form-horizontal" id="validation-form" method="post" action="/master/cms/configuration/update_site_language" enctype = 'multipart/form-data'>
						
						<div class="col-xs-12 col-sm-5">
										<div class="control-group">
											
											<!-- #section:custom/checkbox -->
											<?php 
												if(!empty($site_language)){
													foreach($site_language as $k => $v){
														if(!empty($open_l)){
															foreach ($open_l as $kk => $vv) {
																if($k==$kk&&strtolower($v)==$vv){
																	$checked='checked="checked"';
																	break;
																}else{
																	$checked='';
																}
															}
														}
														
											?>
											<div class="checkbox">
												<label>
													<input type="checkbox" class="ace" name="site_language[]" id="site_language<?=$k?>" value="<?=$k?>_grf_<?=strtolower($v)?>" <?=$checked?> >
													<span class="lbl"> <?=$v?></span>
													
													
													
												</label>
											</div>
											<?php }}?>
											

											<!-- /section:custom/checkbox -->
										</div>
									</div>

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
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--upload picture-->
<script src="<?=RES?>master/js/ace-elements.min.js"></script>
<!-- upload picture -->



<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						
					},
			
					messages: {
						
						
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
								window.location.reload();
							}else{

								pub_alert_error(r.info);
							}
							
						})
						.fail(function() {

							pub_alert_error('至少选择一种语言');
						})
						
						
					}
			
				});

	
});
</script>

<?php $this->load->view('master/public/footer');?>


