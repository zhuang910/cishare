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
	<li class="active">模板设置</li>
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
		模板设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green">设置开关(勾选给可用)
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>
					<form class="form-horizontal" method="get">
				<div class="hr hr-dotted"></div>
				<div class="form-group">
				

					<div class="col-xs-12 col-sm-9">
							<label>
								<input checked="checked" name="hour" value="1" type="radio" class="ace" />
								<span class="lbl"> 模板一</span>
							</label>
							<label>
								<span class="lbl"><a href="javascript:;" onclick="pub_alert_html('<?=$zjjp?>template/yulan?s=1')">预览</a></span>
							</label>
						</div>
				
					<div class="col-xs-12 col-sm-9">
							<label>
								<input  name="hour" value="2" type="radio" class="ace" />
								<span class="lbl"> 模板二</span>
							</label>
							<label>
								<span class="lbl"><a href="javascript:;" onclick="upgrade()">预览</a></span>
							</label>
						</div>
					</div>
					
				</div>

				<div class="space-2"></div>
				<a class="btn btn-success" href="javascript:;" onclick="submit()">
					提 交
				</a>	
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
function upgrade(){
	pub_alert_error('您需要先升级!');
}
function submit(){
	var s=$('input[name="hour"]:checked').val();
	if(s==1){
		pub_alert_success();
	}else{
		pub_alert_error('您需要先升级!');
	}
	
}
</script>

<?php $this->load->view('master/public/footer');?>


