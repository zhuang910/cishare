<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>


	<li class="active">个人信息</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		个人信息
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<div id="user-profile-2" class="user-profile">
				<div class="tabbable">

					<div class="tab-content no-border padding-24">
						<div id="home" class="tab-pane in active">
							<div class="row">
								<div class="col-xs-12 col-sm-3 center">
									<span class="profile-picture">
										<img id="avatar" class="editable img-responsive" alt="Alex's Avatar" title="点击修改头像" src="<?=!empty($dataadmin->image)?$dataadmin->image:'<?=RES?>/master/avatars/profile-pic.jpg'?>" />
									</span>

									<div class="space space-4"></div>

									<a href="/master/authority/personal/profile" class="btn btn-sm btn-block btn-success">
										<i class="ace-icon glyphicon glyphicon-pencil bigger-120"></i>
										<span class="bigger-110">修改资料(Edit Profile)</span>
									</a>

									<a href="/master/authority/personal/password" class="btn btn-sm btn-block btn-primary">
										<i class="ace-icon glyphicon glyphicon-lock bigger-110"></i>
										<span class="bigger-110">修改密码（Change the Password）</span>
									</a>
								</div><!-- /.col -->
								<div class="col-xs-12 col-sm-9">
									<h4 class="blue">
										<span class="middle"></span>
									</h4>

									<div class="profile-user-info">

										<div class="profile-info-row">
											<div class="profile-info-name"> </div>

											<div class="profile-info-value">
												
												<span>欢迎您：<?=!empty($_SESSION['master_user_info']->nikename)?$_SESSION['master_user_info']->nikename:''?></span>
											</div>
										</div>
									</div>

									<div class="hr hr-8 dotted"></div>

									
								</div><!-- /.col -->
							</div><!-- /.row -->

							<div class="space-20"></div>
						</div><!-- /#home -->

						<div id="feed" class="tab-pane">
							<div class="profile-feed row">
								<div class="col-sm-6">
									<div class="profile-activity clearfix">
										<div>
											<img class="pull-left" alt="Alex Doe's avatar" src="<?=RES?>master/avatars/avatar5.png" />
											<a class="user" href="#"> Alex Doe </a>
											changed his profile photo.
											<a href="#">Take a look</a>

											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												an hour ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>

									<div class="profile-activity clearfix">
										<div>
											<img class="pull-left" alt="Susan Smith's avatar" src="<?=RES?>master/avatars/avatar1.png" />
											<a class="user" href="#"> Susan Smith </a>

											is now friends with Alex Doe.
											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												2 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>

									<div class="profile-activity clearfix">
										<div>
											<i class="pull-left thumbicon fa fa-check btn-success no-hover"></i>
											<a class="user" href="#"> Alex Doe </a>
											joined
											<a href="#">Country Music</a>

											group.
											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												5 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>

									<div class="profile-activity clearfix">
										<div>
											<i class="pull-left thumbicon fa fa-picture-o btn-info no-hover"></i>
											<a class="user" href="#"> Alex Doe </a>
											uploaded a new photo.
											<a href="#">Take a look</a>

											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												5 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>

									<div class="profile-activity clearfix">
										<div>
											<img class="pull-left" alt="David Palms's avatar" src="<?=RES?>master/avatars/avatar4.png" />
											<a class="user" href="#"> David Palms </a>

											left a comment on Alex's wall.
											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												8 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>
								</div><!-- /.col -->

								<div class="col-sm-6">
									<div class="profile-activity clearfix">
										<div>
											<i class="pull-left thumbicon fa fa-pencil-square-o btn-pink no-hover"></i>
											<a class="user" href="#"> Alex Doe </a>
											published a new blog post.
											<a href="#">Read now</a>

											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												11 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>

									<div class="profile-activity clearfix">
										<div>
											<img class="pull-left" alt="Alex Doe's avatar" src="<?=RES?>master/avatars/avatar5.png" />
											<a class="user" href="#"> Alex Doe </a>

											upgraded his skills.
											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												12 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>

									<div class="profile-activity clearfix">
										<div>
											<i class="pull-left thumbicon fa fa-key btn-info no-hover"></i>
											<a class="user" href="#"> Alex Doe </a>

											logged in.
											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												12 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>

									<div class="profile-activity clearfix">
										<div>
											<i class="pull-left thumbicon fa fa-power-off btn-inverse no-hover"></i>
											<a class="user" href="#"> Alex Doe </a>

											logged out.
											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												16 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>

									<div class="profile-activity clearfix">
										<div>
											<i class="pull-left thumbicon fa fa-key btn-info no-hover"></i>
											<a class="user" href="#"> Alex Doe </a>

											logged in.
											<div class="time">
												<i class="ace-icon fa fa-clock-o bigger-110"></i>
												16 hours ago
											</div>
										</div>

										<div class="tools action-buttons">
											<a href="#" class="blue">
												<i class="ace-icon fa fa-pencil bigger-125"></i>
											</a>

											<a href="#" class="red">
												<i class="ace-icon fa fa-times bigger-125"></i>
											</a>
										</div>
									</div>
								</div><!-- /.col -->
							</div><!-- /.row -->

							<div class="space-12"></div>

							<div class="center">
								<button type="button" class="btn btn-sm btn-primary btn-white btn-round">
									<i class="ace-icon fa fa-rss bigger-150 middle orange2"></i>
									<span class="bigger-110">View more activities</span>

									<i class="icon-on-right ace-icon fa fa-arrow-right"></i>
								</button>
							</div>
						</div><!-- /#feed -->

						<div id="friends" class="tab-pane">
							<!-- #section:pages/profile.friends -->
							<div class="profile-users clearfix">
								<div class="itemdiv memberdiv">
									<div class="inline pos-rel">
										<div class="user">
											<a href="#">
												<img src="<?=RES?>master/avatars/avatar4.png" alt="Bob Doe's avatar" />
											</a>
										</div>

										<div class="body">
											<div class="name">
												<a href="#">
													<span class="user-status status-online"></span>
													Bob Doe
												</a>
											</div>
										</div>

										<div class="popover">
											<div class="arrow"></div>

											<div class="popover-content">
												<div class="bolder">Content Editor</div>

												<div class="time">
													<i class="ace-icon fa fa-clock-o middle bigger-120 orange"></i>
													<span class="green"> 20 mins ago </span>
												</div>

												<div class="hr dotted hr-8"></div>

												<div class="tools action-buttons">
													<a href="#">
														<i class="ace-icon fa fa-facebook-square blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-google-plus-square red bigger-150"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="itemdiv memberdiv">
									<div class="inline pos-rel">
										<div class="user">
											<a href="#">
												<img src="<?=RES?>master/avatars/avatar1.png" alt="Rose Doe's avatar" />
											</a>
										</div>

										<div class="body">
											<div class="name">
												<a href="#">
													<span class="user-status status-offline"></span>
													Rose Doe
												</a>
											</div>
										</div>

										<div class="popover">
											<div class="arrow"></div>

											<div class="popover-content">
												<div class="bolder">Graphic Designer</div>

												<div class="time">
													<i class="ace-icon fa fa-clock-o middle bigger-120 grey"></i>
													<span class="grey"> 30 min ago </span>
												</div>

												<div class="hr dotted hr-8"></div>

												<div class="tools action-buttons">
													<a href="#">
														<i class="ace-icon fa fa-facebook-square blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-google-plus-square red bigger-150"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="itemdiv memberdiv">
									<div class="inline pos-rel">
										<div class="user">
											<a href="#">
												<img src="<?=RES?>master/avatars/avatar.png" alt="Jim Doe's avatar" />
											</a>
										</div>

										<div class="body">
											<div class="name">
												<a href="#">
													<span class="user-status status-busy"></span>
													Jim Doe
												</a>
											</div>
										</div>

										<div class="popover">
											<div class="arrow"></div>

											<div class="popover-content">
												<div class="bolder">SEO &amp; Advertising</div>

												<div class="time">
													<i class="ace-icon fa fa-clock-o middle bigger-120 red"></i>
													<span class="grey"> 1 hour ago </span>
												</div>

												<div class="hr dotted hr-8"></div>

												<div class="tools action-buttons">
													<a href="#">
														<i class="ace-icon fa fa-facebook-square blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-google-plus-square red bigger-150"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="itemdiv memberdiv">
									<div class="inline pos-rel">
										<div class="user">
											<a href="#">
												<img src="<?=RES?>master/avatars/avatar5.png" alt="Alex Doe's avatar" />
											</a>
										</div>

										<div class="body">
											<div class="name">
												<a href="#">
													<span class="user-status status-idle"></span>
													Alex Doe
												</a>
											</div>
										</div>

										<div class="popover">
											<div class="arrow"></div>

											<div class="popover-content">
												<div class="bolder">Marketing</div>

												<div class="time">
													<i class="ace-icon fa fa-clock-o middle bigger-120 orange"></i>
													<span class=""> 40 minutes idle </span>
												</div>

												<div class="hr dotted hr-8"></div>

												<div class="tools action-buttons">
													<a href="#">
														<i class="ace-icon fa fa-facebook-square blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-google-plus-square red bigger-150"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="itemdiv memberdiv">
									<div class="inline pos-rel">
										<div class="user">
											<a href="#">
												<img src="<?=RES?>master/avatars/avatar2.png" alt="Phil Doe's avatar" />
											</a>
										</div>

										<div class="body">
											<div class="name">
												<a href="#">
													<span class="user-status status-online"></span>
													Phil Doe
												</a>
											</div>
										</div>

										<div class="popover">
											<div class="arrow"></div>

											<div class="popover-content">
												<div class="bolder">Public Relations</div>

												<div class="time">
													<i class="ace-icon fa fa-clock-o middle bigger-120 orange"></i>
													<span class="green"> 2 hours ago </span>
												</div>

												<div class="hr dotted hr-8"></div>

												<div class="tools action-buttons">
													<a href="#">
														<i class="ace-icon fa fa-facebook-square blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-google-plus-square red bigger-150"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="itemdiv memberdiv">
									<div class="inline pos-rel">
										<div class="user">
											<a href="#">
												<img src="<?=RES?>master/avatars/avatar3.png" alt="Susan Doe's avatar" />
											</a>
										</div>

										<div class="body">
											<div class="name">
												<a href="#">
													<span class="user-status status-online"></span>
													Susan Doe
												</a>
											</div>
										</div>

										<div class="popover">
											<div class="arrow"></div>

											<div class="popover-content">
												<div class="bolder">HR Management</div>

												<div class="time">
													<i class="ace-icon fa fa-clock-o middle bigger-120 orange"></i>
													<span class="green"> 20 mins ago </span>
												</div>

												<div class="hr dotted hr-8"></div>

												<div class="tools action-buttons">
													<a href="#">
														<i class="ace-icon fa fa-facebook-square blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-google-plus-square red bigger-150"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="itemdiv memberdiv">
									<div class="inline pos-rel">
										<div class="user">
											<a href="#">
												<img src="<?=RES?>master/avatars/avatar1.png" alt="Jennifer Doe's avatar" />
											</a>
										</div>

										<div class="body">
											<div class="name">
												<a href="#">
													<span class="user-status status-offline"></span>
													Jennifer Doe
												</a>
											</div>
										</div>

										<div class="popover">
											<div class="arrow"></div>

											<div class="popover-content">
												<div class="bolder">Graphic Designer</div>

												<div class="time">
													<i class="ace-icon fa fa-clock-o middle bigger-120 grey"></i>
													<span class="grey"> 2 hours ago </span>
												</div>

												<div class="hr dotted hr-8"></div>

												<div class="tools action-buttons">
													<a href="#">
														<i class="ace-icon fa fa-facebook-square blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-google-plus-square red bigger-150"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="itemdiv memberdiv">
									<div class="inline pos-rel">
										<div class="user">
											<a href="#">
												<img src="<?=RES?>master/avatars/avatar3.png" alt="Alexa Doe's avatar" />
												<input  type="file" name="imagefile" id="zyj_f" value=""/>
											</a>
										</div>

										<div class="body">
											<div class="name">
												<a href="#">
													<span class="user-status status-offline"></span>
													Alexa Doe
												</a>
											</div>
										</div>

										<div class="popover">
											<div class="arrow"></div>

											<div class="popover-content">
												<div class="bolder">Accounting</div>

												<div class="time">
													<i class="ace-icon fa fa-clock-o middle bigger-120 grey"></i>
													<span class="grey"> 4 hours ago </span>
												</div>

												<div class="hr dotted hr-8"></div>

												<div class="tools action-buttons">
													<a href="#">
														<i class="ace-icon fa fa-facebook-square blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
													</a>

													<a href="#">
														<i class="ace-icon fa fa-google-plus-square red bigger-150"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- /section:pages/profile.friends -->
							<div class="hr hr10 hr-double"></div>

							<ul class="pager pull-right">
								<li class="previous disabled">
									<a href="#">&larr; Prev</a>
								</li>

								<li class="next">
									<a href="#">Next &rarr;</a>
								</li>
							</ul>
						</div><!-- /#friends -->

						<div id="pictures" class="tab-pane">
							<ul class="ace-thumbnails">
								<li>
									<a href="#" data-rel="colorbox">
										<img alt="150x150" src="<?=RES?>master/images/gallery/thumb-1.jpg" />
										<div class="text">
											<div class="inner">Sample Caption on Hover</div>
										</div>
									</a>

									<div class="tools tools-bottom">
										<a href="#">
											<i class="ace-icon fa fa-link"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-paperclip"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-pencil"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-times red"></i>
										</a>
									</div>
								</li>

								<li>
									<a href="#" data-rel="colorbox">
										<img alt="150x150" src="<?=RES?>master/images/gallery/thumb-2.jpg" />
										<div class="text">
											<div class="inner">Sample Caption on Hover</div>
										</div>
									</a>

									<div class="tools tools-bottom">
										<a href="#">
											<i class="ace-icon fa fa-link"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-paperclip"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-pencil"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-times red"></i>
										</a>
									</div>
								</li>

								<li>
									<a href="#" data-rel="colorbox">
										<img alt="150x150" src="<?=RES?>master/images/gallery/thumb-3.jpg" />
										<div class="text">
											<div class="inner">Sample Caption on Hover</div>
										</div>
									</a>

									<div class="tools tools-bottom">
										<a href="#">
											<i class="ace-icon fa fa-link"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-paperclip"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-pencil"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-times red"></i>
										</a>
									</div>
								</li>

								<li>
									<a href="#" data-rel="colorbox">
										<img alt="150x150" src="<?=RES?>master/images/gallery/thumb-4.jpg" />
										<div class="text">
											<div class="inner">Sample Caption on Hover</div>
										</div>
									</a>

									<div class="tools tools-bottom">
										<a href="#">
											<i class="ace-icon fa fa-link"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-paperclip"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-pencil"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-times red"></i>
										</a>
									</div>
								</li>

								<li>
									<a href="#" data-rel="colorbox">
										<img alt="150x150" src="<?=RES?>master/images/gallery/thumb-5.jpg" />
										<div class="text">
											<div class="inner">Sample Caption on Hover</div>
										</div>
									</a>

									<div class="tools tools-bottom">
										<a href="#">
											<i class="ace-icon fa fa-link"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-paperclip"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-pencil"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-times red"></i>
										</a>
									</div>
								</li>

								<li>
									<a href="#" data-rel="colorbox">
										<img alt="150x150" src="<?=RES?>master/images/gallery/thumb-6.jpg" />
										<div class="text">
											<div class="inner">Sample Caption on Hover</div>
										</div>
									</a>

									<div class="tools tools-bottom">
										<a href="#">
											<i class="ace-icon fa fa-link"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-paperclip"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-pencil"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-times red"></i>
										</a>
									</div>
								</li>

								<li>
									<a href="#" data-rel="colorbox">
										<img alt="150x150" src="<?=RES?>master/images/gallery/thumb-1.jpg" />
										<div class="text">
											<div class="inner">Sample Caption on Hover</div>
										</div>
									</a>

									<div class="tools tools-bottom">
										<a href="#">
											<i class="ace-icon fa fa-link"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-paperclip"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-pencil"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-times red"></i>
										</a>
									</div>
								</li>

								<li>
									<a href="#" data-rel="colorbox">
										<img alt="150x150" src="<?=RES?>master/images/gallery/thumb-2.jpg" />
										<div class="text">
											<div class="inner">Sample Caption on Hover</div>
										</div>
									</a>

									<div class="tools tools-bottom">
										<a href="#">
											<i class="ace-icon fa fa-link"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-paperclip"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-pencil"></i>
										</a>

										<a href="#">
											<i class="ace-icon fa fa-times red"></i>
										</a>
									</div>
								</li>
							</ul>
						</div><!-- /#pictures -->
					</div>
				</div>
			</div>
		</div>
		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->
		<!--[if lte IE 8]>
		  <script src="<?=RES?>/master/js/excanvas.min.js"></script>
		<![endif]-->
		<!-- ace scripts -->
		<script src="<?=RES?>master/js/ace-extra.min.js"></script>
		<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
		<script src="<?=RES?>/master/js/ace.min.js"></script>
		<!-- inline scripts related to this page -->
		<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>
		<script src="<?=RES?>master/js/x-editable/ace-editable.min.js"></script>

		<script type="text/javascript">
		jQuery(function($) {
			$.fn.editable.defaults.mode = 'inline';
			$.fn.editableform.loading = "<div class='editableform-loading'><i class='ace-icon fa fa-spinner fa-spin fa-2x light-blue'></i></div>";
			$.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="ace-icon fa fa-check"></i></button>'+
										'<button type="button" class="btn editable-cancel"><i class="ace-icon fa fa-times"></i></button>';    


			try {//ie8 throws some harmless exceptions, so let's catch'em

				//first let's add a fake appendChild method for Image element for browsers that have a problem with this
				//because editable plugin calls appendChild, and it causes errors on IE
				try {
					document.createElement('IMG').appendChild(document.createElement('B'));
				} catch(e) {
					Image.prototype.appendChild = function(el){}
				}

				var last_gritter
				$('#avatar').editable({
					type: 'image',
					name: 'avatar',
					value: null,
					image: {
						//specify ace file input plugin's options here
						btn_choose: 'Change Avatar',
						droppable: true,
						maxSize: 110000,//~100Kb

						//and a few extra ones here
						name: 'avatar',//put the field name here as well, will be used inside the custom plugin
						on_error : function(error_type) {//on_error function will be called when the selected file has a problem
							if(last_gritter) $.gritter.remove(last_gritter);
							if(error_type == 1) {//file format error
								last_gritter = $.gritter.add({
									title: 'File is not an image!',
									text: 'Please choose a jpg|gif|png image!',
									class_name: 'gritter-error gritter-center'
								});
							} else if(error_type == 2) {//file size rror
								last_gritter = $.gritter.add({
									title: 'File too big!',
									text: 'Image size should not exceed 100Kb!',
									class_name: 'gritter-error gritter-center'
								});
							}
							else {//other error
							}
						},
						on_success : function() {
							$.gritter.removeAll();
						}
					},
					url: function(params) {
						// ***UPDATE AVATAR HERE*** //
						var submit_url = '/master/authority/personal/editphoto';//please modify submit_url accordingly
						var deferred = null;
						var avatar = '#avatar';

						//if value is empty (""), it means no valid files were selected
						//but it may still be submitted by x-editable plugin
						//because "" (empty string) is different from previous non-empty value whatever it was
						//so we return just here to prevent problems
						var value = $(avatar).next().find('input[type=hidden]:eq(0)').val();
						if(!value || value.length == 0) {
							deferred = new $.Deferred
							deferred.resolve();
							return deferred.promise();
						}

						var $form = $(avatar).next().find('.editableform:eq(0)')
						var file_input = $form.find('input[type=file]:eq(0)');
						var pk = $(avatar).attr('data-pk');//primary key to be sent to server

						var ie_timeout = null


						if( "FormData" in window ) {
							var formData_object = new FormData();//create empty FormData object
							
							//serialize our form (which excludes file inputs)
							$.each($form.serializeArray(), function(i, item) {
								//add them one by one to our FormData 
								formData_object.append(item.name, item.value);							
							});
							//and then add files
							$form.find('input[type=file]').each(function(){
								var field_name = $(this).attr('name');
								var files = $(this).data('ace_input_files');
								if(files && files.length > 0) {
									formData_object.append(field_name, files[0]);
								}
							});

							//append primary key to our formData
							formData_object.append('pk', pk);

							deferred = $.ajax({
										url: submit_url,
									   type: 'POST',
								processData: false,//important
								contentType: false,//important
								   dataType: 'json',//server response type
									   data: formData_object
							})
						}
						else {
							deferred = new $.Deferred

							var temporary_iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000));
							var temp_iframe = 
									$('<iframe id="'+temporary_iframe_id+'" name="'+temporary_iframe_id+'" \
									frameborder="0" width="0" height="0" src="about:blank"\
									style="position:absolute; z-index:-1; visibility: hidden;"></iframe>')
									.insertAfter($form);
									
							$form.append('<input type="hidden" name="temporary-iframe-id" value="'+temporary_iframe_id+'" />');
							
							//append primary key (pk) to our form
							$('<input type="hidden" name="pk" />').val(pk).appendTo($form);
							
							temp_iframe.data('deferrer' , deferred);
							//we save the deferred object to the iframe and in our server side response
							//we use "temporary-iframe-id" to access iframe and its deferred object

							$form.attr({
									  action: submit_url,
									  method: 'POST',
									 enctype: 'multipart/form-data',
									  target: temporary_iframe_id //important
							});

							$form.get(0).submit();

							//if we don't receive any response after 30 seconds, declare it as failed!
							ie_timeout = setTimeout(function(){
								ie_timeout = null;
								temp_iframe.attr('src', 'about:blank').remove();
								deferred.reject({'status':'fail', 'message':'Timeout!'});
							} , 30000);
						}


						//deferred callbacks, triggered by both ajax and iframe solution
						deferred
						.done(function(result) {//success
							var res = result[0];//the `result` is formatted by your server side response and is arbitrary
						
							//if(result.state == 1) $(avatar).get(0).src = res.url;
							//else alert(res.message);
							if(result.state == 1){
						
							$(avatar).get(0).src = result.data;
								window.location.reload();
							}
						})
						.fail(function(result) {//failure
							alert("There was an error");
						})
						.always(function() {//called on both success and failure
							if(ie_timeout) clearTimeout(ie_timeout)
							ie_timeout = null;	
						});

						return deferred.promise();
						// ***END OF UPDATE AVATAR HERE*** //
					},
						
					success: function(response, newValue) {
					}
				})
			}catch(e) {}
		});

					
		if(location.protocol == 'file:') alert("For uploading to server, you should access this page using 'http' protocal, i.e. via a webserver.");
		</script>

		<!--未知错误<script type="text/javascript"> ace.vars['base'] = '..'; </script>-->
<?php $this->load->view('master/public/footer');?>