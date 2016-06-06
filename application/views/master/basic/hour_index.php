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
	<li class="active">时间设置</li>
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
		时间设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="col-xs-12 col-sm-9">
		<label class="line-height-1 blue">
		<h4>设置每天可用课时（勾选为可用）</h4>
		</label>
			<form class="form-horizontal" id="hour" method="get" action="<?=$zjjp?>hour/hoursave">
				<div class="hr hr-dotted"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right">请选择：</label>

					<div class="col-xs-12 col-sm-9">
					<?php for($i=1;$i<11;$i++):?>
						<div>
							<label>
							<?php 
								$checked='';
								if(!empty($hour)){
									
									foreach($hour as $k=>$v){
										if($v==$i){
											$checked="checked='checked'";
										}
									}         
								}
								
							?>
								<input <?php echo $checked?> name="hour" value="<?=$i?>" type="checkbox" class="ace" />
								<span class="lbl"> <?=$i?>小节课</span>
							</label>
						</div>
					<?php endfor;?>
				
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
<!-- end script -->
<?php $this->load->view('master/public/footer');?>