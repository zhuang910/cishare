<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">控制台</a>
	</li>
	<li class="active">修改个人信息</li>
</ul>
EOD;
?>		
<?php $this->load->view('admin/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
 <script src="<?=RES?>admin/js/jquery.validate.min.js"></script>
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		控制台
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green">修改个人信息（Edit profile）
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>

					<form class="form-horizontal" id="validation-form" method="post" action="/admin/authority/personal/saveprofile" enctype = 'multipart/form-data'>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">姓名（name）:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="nikename" name="nikename" value="<?=!empty($info->nikename) ? $info->nikename : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">用户名（ID）:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="username" name="username" value="<?=!empty($info->username) ? $info->username : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">登录邮箱（Email）:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="email" name="email" value="<?=!empty($info->email) ? $info->email : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">电话（Tel）:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="tel" name="tel" value="<?=!empty($info->tel) ? $info->tel : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">手机（Mobile）:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="mobile" name="mobile" value="<?=!empty($info->mobile) ? $info->mobile : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<input type="hidden" name="id"  value="<?=!empty($info->id)?$info->id:''?>">
						
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
<script src="<?=RES?>/admin/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>admin/js/ace-extra.min.js"></script>
<script src="<?=RES?>/admin/js/ace-elements.min.js"></script>
<script src="<?=RES?>/admin/js/ace.min.js"></script>
<!-- script -->
<script src="<?=RES?>admin/js/bootstrap-wysiwyg.min.js"></script>
<!--upload picture-->
<script src="<?=RES?>admin/js/ace-elements.min.js"></script>
<!-- upload picture -->



<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						nikename: {
							required: true
						},
						username: {
							required: true
						},
						email: {
							required: true,
							remote:'/admin/authority/personal/checkemail?id=<?=!empty($info->id)?$info->id:''?>'
						},
						mobile: {
							required: true
						},
						
						state: 'required',
						
					},
			
					messages: {
						
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

<?php $this->load->view('admin/public/footer');?>


