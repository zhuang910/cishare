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
	<li class="active">栏目管理</li>
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
		栏目管理
	</h1>
</div><!-- /.page-header -->
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12">
			<div class="table-header">
			栏目列表
			<a style="float:right;" href='/master/cms/column/add?id=0' type="button" title="添加栏目" class="btn btn-primary btn-sm btn-default btn-sm">
					<span class="glyphicon  glyphicon-plus"></span>
					添加栏目
			</a>	
			</div>
			<form method="post" action="/master/cms/column/orderby" id="myform">
				<table id="sample-table-1" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th class="center" width="40">
								排序
							</th>
							<th width="50">id</th>
							<th width="600">栏目名称</th>
							<th class="hidden-480">所属模型</th>
							<th>
								管理操作
							</th>
						</tr>
					</thead>
					<tbody>
					<?php if(!empty($select_categorys)):?>
						<?=$select_categorys?>
					<?php else:?>
						<tr><td colspan="5"><a href='/master/cms/column/add?id=0' title="添加栏目">还没有栏目,请添加栏目</a></td></tr>
					<?php endif;?>
					</tbody>
				</table>
				</form>
				<?php if(!empty($select_categorys)):?>
				<button class="btn" onclick="orderby()">排序</button>
				<?php endif;?>
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
function del(id){
	pub_alert_confirm('/master/cms/column/del?id='+id);
}
function add_column(id,o){
     pub_alert_html('/master/cms/column/get_html?id='+id+'&o='+o);
 }
 function orderby(){
 	var data=$('#myform').serialize();
		$.ajax({
			url: $('#myform').attr('action'),
			type: 'POST',
			dataType: 'json',
			data: data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
				window.location.href="<?=$zjjp?>column";
			}else{
				pub_alert_error();
			}
			
		})
		.fail(function() {
			
			pub_alert_error();
		})
						
 }
</script>

<?php $this->load->view('master/public/footer');?>	
	

