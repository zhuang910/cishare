<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">招生管理</a>
	</li>
	<li class="active">录取阶段</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
         <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	  编辑申请资料
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<iframe src="/master/agencyport/course?userid=<?=$userid?>&s=1" width="100%" height="800" frameborder="no" border="0" marginwidth="0" marginheight="0" allowtransparency="yes"></iframe>
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
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>

<!-- end script -->

<?php $this->load->view('master/public/footer');?>
