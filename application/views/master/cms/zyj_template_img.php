<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">信息管理</a>
	</li>
	<li>
		<a href="javascript:;">模块管理</a>
	</li>
	<li class="active">广告管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
         <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
		 <!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=RES?>master/css/colorbox.css" />
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		广告管理
	</h1>
</div><!-- /.page-header -->
<div class="row">
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->
							<div>
								<ul class="ace-thumbnails clearfix">
									<!-- #section:pages/gallery -->
									<li>
										<a data-rel="colorbox" title="Photo Title" href="/resource/master/images/gallery/image-1.jpg" class="cboxElement">
											<img src="/resource/master/images/gallery/thumb-1.jpg" alt="150x150">
										</a>

										<div class="tags">
											<span class="label-holder">
												<span class="label label-info">breakfast</span>
											</span>

											<span class="label-holder">
												<span class="label label-danger">fruits</span>
											</span>

											<span class="label-holder">
												<span class="label label-success">toast</span>
											</span>

											<span class="label-holder">
												<span class="label label-warning arrowed-in">diet</span>
											</span>
										</div>

										<div class="tools">
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
										<a data-rel="colorbox" title="aaaaaaaaaaaaaa" href="/resource/master/images/gallery/image-1.jpg" class="cboxElement">
											<img src="/resource/master/images/gallery/thumb-1.jpg" alt="150x150">
										</a>

										<div class="tags">
											<span class="label-holder">
												<span class="label label-info">breakfast</span>
											</span>

											<span class="label-holder">
												<span class="label label-danger">fruits</span>
											</span>

											<span class="label-holder">
												<span class="label label-success">toast</span>
											</span>

											<span class="label-holder">
												<span class="label label-warning arrowed-in">diet</span>
											</span>
										</div>

										<div class="tools">
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
										<a data-rel="colorbox" title="Photo Title" href="/resource/master/images/gallery/image-1.jpg" class="cboxElement">
											<img src="/resource/master/images/gallery/thumb-1.jpg" alt="150x150">
										</a>

										<div class="tags">
											<span class="label-holder">
												<span class="label label-info">breakfast</span>
											</span>

											<span class="label-holder">
												<span class="label label-danger">fruits</span>
											</span>

											<span class="label-holder">
												<span class="label label-success">toast</span>
											</span>

											<span class="label-holder">
												<span class="label label-warning arrowed-in">diet</span>
											</span>
										</div>

										<div class="tools">
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
							</div><!-- PAGE CONTENT ENDS -->
						</div><!-- /.col -->
					</div><!-- /.row -->

	
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.bootstrap.js"></script>
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?=RES?>master/css/ace.onpage-help.css" />

<!-- page specific plugin scripts -->
		<script src="<?=RES?>master/js/jquery.colorbox-min.js"></script>
<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
	var $overflow = '';
	var colorbox_params = {
		rel: 'colorbox',
		reposition:true,
		scalePhotos:true,
		scrolling:false,
		previous:'<i class="ace-icon fa fa-arrow-left"></i>',
		next:'<i class="ace-icon fa fa-arrow-right"></i>',
		close:'&times;',
		current:'{current} of {total}',
		maxWidth:'100%',
		maxHeight:'100%',
		onOpen:function(){
			$overflow = document.body.style.overflow;
			document.body.style.overflow = 'hidden';
		},
		onClosed:function(){
			document.body.style.overflow = $overflow;
		},
		onComplete:function(){
			$.colorbox.resize();
		}
	};

	$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
	$("#cboxLoadingGraphic").append("<i class='ace-icon fa fa-spinner orange'></i>");//let's add a custom loading icon
})
		</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>