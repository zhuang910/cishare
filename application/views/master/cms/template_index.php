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
	<li class="active">模版设置</li>
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
		模版设置
	</h1>
</div><!-- /.page-header -->
<div class="row">
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->
							<div>
								<ul class="ace-thumbnails clearfix">
									<!-- #section:pages/gallery -->
									<?php if(!empty($template)){
										foreach($template as $k => $v){
									?>
									<li>
										<div>
											<img src="<?=!empty($v['image'])?$v['image']:''?>" alt="150x150" width="150" height="150">
											<div class="text">
												<div class="inner">
													<span><?=!empty($v['title'])?$v['title']:''?></span>

													<br>
													<a data-rel="colorbox" title="预览" href="<?=!empty($v['image'])?$v['image']:''?>" class="cboxElement">
														<i class="ace-icon fa fa-search-plus"></i>
													</a>

													<a href="/master/cms/template/template_list?themeid=<?=$v['id']?>" title="编辑模版">
														<i class="ace-icon fa fa-pencil"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-xs-3" style="margin-top:12px;margin-left:32px;">
												<label>
													<input id="on_off_<?=$v['id']?>" type="checkbox" <?=!empty($v['state']) && $v['state'] == 1?'checked'.' '.'disabled':''?> class="ace ace-switch" name="switch-field-1" onclick="open_close(<?=$v['id']?>,<?=$v['state']?>)">
													<span class="lbl"></span>
												</label>
											</div>
									</li>
									
									<?php }}?>
									
									
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
<script type="text/javascript">
	function open_close(id,state){
		$.ajax({
			type:'GET',
			url:'/master/cms/template/template_on_off?id='+id+'&state='+state,
			success:function(r){
				if(r.state==1){
					pub_alert_success();
					window.location.reload();
				}else{
					pub_alert_error();
				}
			},
			dataType:'json'
		
		});
	
	}

</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>