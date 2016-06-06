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
	<li class="active">按专业统计</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/lodop/LodopFuncs.js"></script>   

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
							<a href="javascript:OutToFileOneSheet();">导出</a>
						</h3>
					</div>
					<div>
						<div class="col-sm-12">
									<div id="tables" class="widget-box transparent">
										<div class="widget-header widget-header-flat">
											

										
										</div>

										<div class="widget-body" id="gengruifeng">
											<div class="widget-main no-padding">
											<form id='scorearr'>
												<table class="table table-bordered table-striped">
													<thead class="thin-border-bottom" id="stype">
														<tr>
														<?php if(!empty($tou_info)){?>
														<?php foreach($tou_info as $k=>$v){?>
															<th><?=$v?></th>
														<?php }?>
														<?php }?>
														</tr>
													</thead>
													<tbody>
														<?=!empty($html)?$html:''?>
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
function OutToFileOneSheet(){ 
    LODOP=getLodop(); 
    var html=$('#gengruifeng').html(); 
    LODOP.PRINT_INIT(""); 
    LODOP.ADD_PRINT_TABLE(100,20,500,60,html); 
    LODOP.SET_SAVE_MODE("FILE_PROMPT",1); 
    LODOP.SET_SAVE_MODE("RETURN_FILE_NAME",1); 
    if (LODOP.SAVE_TO_FILE('timetable.xls')) pub_alert_success("导出成功！");     
  }; 
	
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>