<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">后台</a>
	</li>

	<li>
		<a href="javascript:;">收费管理</a>
	</li>
	<li>
		<a href="javascript:;">凭据管理</a>
	</li>
	<li class="active">学费凭据</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />


<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		凭据管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="table-responsive">

			 <div class="dataTables_borderWrap"> 
				<div> 
					<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
						<li <?php if($label_id ==3):?> class="active"<?php endif;?>>
						<a href="/master/finance/credentials_tuition/index?&label_id=3"><h5>待确认</h5></a>
						</li>
						<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
						<a href="/master/finance/credentials_tuition/index?&label_id=1"><h5>支付成功</h5></a>
						</li>
						<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
						<a href="/master/finance/credentials_tuition/index?&label_id=2"><h5>支付失败</h5></a>
						</li>
						
					</ul>
					<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
						<thead>
							<tr>
								<th class="center">
									ID
								</th>
								<th>用户信息</th>
								<th>订单号</th>
								<th>总钱数</th>
								<th>支付时间</th>
								<th>支付状态</th>
								<th>操作</th>
							</tr>
						</thead>

						<tbody>
							
						</tbody>
					</table>






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
<script src="<?=RES?>master/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.bootstrap.js"></script>
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<script type="text/javascript">
	if($('#sample-table-2').length > 0){
	$('#sample-table-2').each(function(){
		var opt = {
			"iDisplayLength" : 25,
			"sPaginationType": "full_numbers",
			"oLanguage":{
				"sSearch": "<span>搜索:</span> ",
				"sInfo": "<span>_START_</span> - <span>_END_</span> 共 <span>_TOTAL_</span>",
				"sLengthMenu": "_MENU_ <span>条每页</span>",
				"oPaginate": {
					"sFirst" : "首页",
					"sLast" : "尾页",
			   		"sPrevious": " 上一页 ",
			   		"sNext":     " 下一页 "
		   		},
				"sInfoEmpty" : "没有记录",
				"sInfoFiltered" : "",
				"sZeroRecords" : '没有找到想匹配记录'
			}
		};

		 opt.bAutoWidth=true; 
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "/master/finance/credentials_tuition/index?label_id=<?=$label_id?>";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "userid" },
								{ "mData": "ordernumber" },
								{ "mData": "amount" },
								{ "mData": "createtime" },
								{ "mData": "state" },
								{ "mData": "operation" },
							
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 6 ] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}
function end_apply(id,state){
	pub_alert_html('/master/finance/credentials_acc/add_shibai?id='+id+'&type=credentials_tuition');

}
function end_applys(id,state){
		pub_alert_confirm('/master/finance/credentials_tuition/doproof?id='+id+'&state='+state);
}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>