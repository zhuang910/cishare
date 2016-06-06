<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;"> 统计管理 </a>
	</li>
	
	<li class="active">在学统计</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />


<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		统计管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<div class="col-sm-10 col-sm-offset-1">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-large">
						<h3 class="widget-title grey lighter">
							<i class="ace-icon fa fa-leaf green"></i>
							统计
						</h3>
					</div>
					<div>
						<div class="col-sm-12">
									<div id="tables" class="widget-box transparent">
										<div class="widget-header widget-header-flat">
											

										
										</div>

										<div class="widget-body">
											<div class="widget-main no-padding">
											<form id='scorearr'>
												<table class="table table-bordered table-striped" id="table">
													<thead class="thin-border-bottom" id="stype">
														<tr>
														<th width="200">学位类型 / 授课类型</th>
														<th>  中文授课 </th>
														<th>  英文授课 </th>
														</tr>
													</thead>
													<tbody>
														<?php foreach($array as $k=>$v):?>
														<tr><td><?=$k?></td><td><?=$v[1]?></td><td><?=$v[2]?></td>

														</tr>
														<?php endforeach;?>
													</tbody>
												</table>
											</form>
											</div><!-- /.widget-main -->
										</div><!-- /.widget-body -->
									</div><!-- /.widget-box -->
								</div><!-- /.col -->
					</div>
				</div>
			</div>
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
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">

function c(){
	$('#tables').attr({
				class: 'widget-box transparent collapsed',
			});
	 $('#tbody').remove();
}

</script>
<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>