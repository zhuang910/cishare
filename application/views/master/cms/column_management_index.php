<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">内容管理</a>
	</li>
	<li>
		<a href="javascript:;">基础栏目发布</a>
	</li>
	<li class="active">$name</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
      
		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		<?=!empty($name)?$name:''?>
	</h1>
</div><!-- /.page-header -->
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12">
			<div class="table-header">
			<?=!empty($name)?$name:''?>
				
			</div>
			<form method="post" action="/master/cms/column/orderby" id="myform">
				<table id="sample-table-1" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>id</th>
							<th>栏目名称</th>
							<th class="hidden-480">所属模型</th>
							<th>是否跳转</th>
							<th>状态</th>
							<th>
								管理操作
							</th>
						</tr>
					</thead>
					<tbody>
					<?=$select_categorys?>
					</tbody>
				</table>
				</form>
				
			</div><!-- /.span -->
		</div><!-- /.row -->
<!--tree js-->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<script src="<?=RES?>master/js/fuelux/fuelux.wizard.min.js"></script>
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!--confirm del js   ace.min.css  css -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<script type="text/javascript">

function edit_state_column(id,state){
     pub_alert_confirm('/master/cms/column_management/edit_state_column?columnid='+id+'&state='+state);
 }

</script>

<?php $this->load->view('master/public/footer');?>	
	

