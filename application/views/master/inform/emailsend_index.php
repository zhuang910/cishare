<?php $this->load->view('master/public/header');?>

 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />


<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		发送邮件
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				
			<form class="form-horizontal" id="validation-form" method="get">
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="name" name="name" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发件人:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="name" name="name" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
		
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容模板:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<select id="platform" class="input-medium" name="platform">
								<option value="">------------------</option>
								<option value="linux">Linux</option>
								<option value="windows">Windows</option>
								<option value="mac">Mac OS</option>
								<option value="ios">iOS</option>
								<option value="android">Android</option>
							</select>
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="col-md-offset-3 col-md-9">
					<button class="btn btn-info" data-last="Finish">
					<i class="ace-icon fa fa-check bigger-110"></i>
					发送
					</button>
					<button class="btn" type="reset">
					<i class="ace-icon fa fa-undo bigger-110"></i>
					重置
					</button>
				</div>
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

</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>