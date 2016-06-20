<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Admin - <?=APPNAME?></title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<?php $this->load->view('admin/public/css_basic');?>
		<?php $this->load->view('admin/public/js_basic')?>
	</head>
	<body class="no-skin">
	<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left" style="width: 167px">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="navbar-brand">
						<small>
							享得管理
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
					<!--语言切换-->
					<?php 
						$uri3 = $this->uri->segment(3);
						$arr=array('img','news','page','major','majorimg','majorpl','acc_camp','building','buildingprice','buildingimg','question','advance','evaluate_item','activity','notice');
						if(in_array($uri3, $arr)){
					?>
					<li class="light-blue" style="background-color:#438EB9">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown">
							<i class="fa fa-flag red bigger-130"></i>
						
							<span class="badge badge-important" id='yuyan'><?=!empty($_SESSION['language'])&&!empty($site_language_admin[$_SESSION['language']])?$site_language_admin[$_SESSION['language']]:'chinese'?></span>
						</a>
						<ul class="dropdown-menu-right dropdown-menu dropdown-caret dropdown-close">
							<?php foreach($url_grf as $k=>$v):?>
								<li>
									<a href="javascript:;" onclick="language('<?=$k?>','<?=$v?>')">
										<i class="fa fa-flag blue bigger-130"></i>
										<?=$k?>
									</a>
								</li>
							<?php endforeach;?>
						</ul>
					</li>
					<?php }?>
						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?=!empty($_SESSION['master_user_info']->image) ? $_SESSION['master_user_info']->image : RES.'admin/avatars/avatar2.png'?>" />
								<span class="user-info">
									<small>您好,</small>
									<?=$_SESSION["master_user_info"]->nikename;?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="/admin/authority/personal/profile">
										<i class="ace-icon fa fa-user"></i>
										修改个人信息
									</a>
								</li>

								<li>
									<a href="/admin/authority/personal/password">
										<i class="ace-icon fa fa-key"></i>
										修改密码
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="/admin/core/login/logout">
										<i class="ace-icon fa fa-power-off"></i>
										退出登录
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>
				<script type="text/javascript">
					function language(v,url){

						$.ajax({
							url: '/admin/authority/personal/set_language?v='+v,
							type: 'GET',
							dataType: 'json',
							data: {},
						})
						.done(function(r) {
							if(r.state==1){
								window.location.href=url;
							}
						})
						.fail(function() {
							console.log("error");
						})
					}
				</script>
				<?php $this->load->view('admin/public/navigation/settings_basic');?>
				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->

		</div>
		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<?php include 'left.php'?>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						function jumpmaster(){
							window.location.href="/admin/core/index";
						}
					
					</script>
					<?=!empty($breadcrumb)?$breadcrumb:''?>
					

					<!-- /section:basics/content.searchbox -->
				</div>

				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">
					<!-- #section:settings.box -->
					<div class="ace-settings-container" id="ace-settings-container">
						<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
							<i class="ace-icon fa fa-cog bigger-150"></i>
						</div>

						<div class="ace-settings-box clearfix" id="ace-settings-box">
							<div class="pull-left width-50">
								<!-- #section:settings.skins -->
								<div class="ace-settings-item">
									<div class="pull-left">
										<select id="skin-colorpicker" class="hide">
											<option data-skin="no-skin" value="#438EB9">#438EB9</option>
											<option data-skin="skin-1" value="#222A2D">#222A2D</option>
											<option data-skin="skin-2" value="#C6487E">#C6487E</option>
											<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
										</select>
									</div>
									<span>&nbsp; Choose Skin</span>
								</div>

								<!-- /section:settings.skins -->

								<!-- #section:settings.navbar -->
								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
									<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
								</div>

								<!-- /section:settings.navbar -->

								<!-- #section:settings.sidebar -->
								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
									<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
								</div>

								<!-- /section:settings.sidebar -->

								<!-- #section:settings.breadcrumbs -->
								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
									<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
								</div>

								<!-- /section:settings.breadcrumbs -->

								<!-- #section:settings.rtl -->
								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
									<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
								</div>

								<!-- /section:settings.rtl -->

								<!-- #section:settings.container -->
								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
									<label class="lbl" for="ace-settings-add-container">
										Inside
										<b>.container</b>
									</label>
								</div>

								<!-- /section:settings.container -->
							</div><!-- /.pull-left -->

							<div class="pull-left width-50">
								<!-- #section:basics/sidebar.options -->
								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
									<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
									<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
								</div>

								<div class="ace-settings-item">
									<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
									<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
								</div>

								<!-- /section:basics/sidebar.options -->
							</div><!-- /.pull-left -->
						</div><!-- /.ace-settings-box -->
					</div><!-- /.ace-settings-container -->