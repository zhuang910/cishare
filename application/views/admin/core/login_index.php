<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>登陆</title>
		<meta name="description" content="登陆" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!-- 加载基础css -->
		<?php $this->load->view('admin/public/css_basic');?>
	</head>

	<body class="login-layout light-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<span class="grey" id="id-text2" style="font-size:28px;">享得 管理系统登录</span>
								</h1>
								<h4 class="blue" id="id-company-text"></h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												请输入您的登录信息
											</h4>

											<div class="space-6"></div>

											<form id="form_login">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" id="username" name="username" class="form-control" placeholder="用户名" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" id="password" name="password" class="form-control" placeholder="密码" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>
													<label class="red clearfix" id="error"></label>
													<div class="space"></div>

													<div class="clearfix">

														<button type="button" class="width-35 pull-right btn btn-sm btn-primary" onclick="dologin()">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">登 录</span>
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->
							</div><!-- /.position-relative -->
						</div>
							<div class="center">

								<h4 class="blue" id="id-company-text">技术支持：&copy;<a target="_blank" href="http://share.6655.la" title="点击查看">享得科技</a></h4>
							</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->
		<?php $this->load->view('admin/public/js_basic');?>
		<script type="text/javascript">
		function dologin(){
			var data = $("#form_login").serialize();
			$.ajax({
				url: '<?=$access_path?>login/dologin',
				type: 'POST',
				dataType: 'json',
				data: data,
				beforeSend:function(){
					$("#error").html('<img src="<?=RES?>admin/img/loading.gif">');
				}
			})
			.done(function(r) {
				if(r.state == 1){
					$("#error").html('<span class="green">'+r.info+'</span>');
					setTimeout('window.location.href="<?=$access_path?>index"',1000);
				}else{
					$("#error").html(r.info);
				}
			})
			.fail(function() {
				$("#error").html('未知错误');
			});
			
		}
		</script>
	</body>
</html>