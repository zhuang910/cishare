<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">后台</a>
	</li>

	
	<li class="active">专题管理</li>
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
		专题管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="table-responsive">

			 <div class="dataTables_borderWrap">
				<div>
				<div class="table-header">
			专题管理
			<a class="btn btn-primary btn-sm btn-default btn-sm" title="添加" type="button" href="/master/article/special/add" style="float:right;">
					<span class="glyphicon  glyphicon-plus"></span>
					添加
			</a>
<a type="button" title="返回上一级" class="btn btn-primary btn-sm btn-default btn-sm" href="javascript:history.back();" style="float:right;">
					<span class="ace-icon fa fa-reply"></span>
					返回上一级
				</a>
			</div>
					<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
						<thead>
							<tr>
								<th class="center" width="50">
									ID
								</th>
								<th  width="400">名称</th>
								<th width="100">操作</th>

							</tr>
						</thead>
						<thead>
						<tr>
							<th>
                                <input type="text" id="id" placeholder="ID" style="width:50px;">
                            </th>
							<th>
                                 <input type="text" id="name" placeholder="名称" style="width:300px;">
                            </th>
							<th></th>


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
	function del(id){
		pub_alert_confirm('/master/article/special/del?id='+id);
	}
</script>

<script type="text/javascript">
$(function(){
if($('#sample-table-2').length > 0) {
	$("#sample-table-2").dataTable({
		"iDisplayLength": 25,
		"sPaginationType": "full_numbers",
		"oLanguage": {
			"sSearch": "<span>搜索:</span> ",
			"sInfo": "<span>_START_</span> - <span>_END_</span> 共 <span>_TOTAL_</span>",
			"sLengthMenu": "_MENU_ <span>条每页</span>",
			"oPaginate": {
				"sFirst": "首页",
				"sLast": "尾页",
				"sPrevious": " 上一页 ",
				"sNext": " 下一页 "
			},
			"sInfoEmpty": "没有记录",
			"sInfoFiltered": "",
			"sZeroRecords": '没有找到想匹配记录'
		},
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": '<?=$zjjp?>special/index',
		"fnServerData": function (sSource, aoData, fnCallback) {
			$.ajax({
				"dataType": 'json',
				"type": "POST",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			});
		},

		"aoColumnDefs": [{"bSortable": false, "aTargets": [2]}],
		"aaSorting": [[0, 'desc']],
		"aoColumns": [
			{"mData": "id"},
			{"mData": "name"},
			{"mData": "operation"}
		]
	});


	$('#id').on('keyup', function () {
		zjj_datatable_search(0, $("#id").val());
	});

	$('#name').on('keyup', function () {
		zjj_datatable_search(1, $("#name").val());
	});

	function zjj_datatable_search(column, val) {
		$('#sample-table-2').DataTable().column(column).search(val, false, true).draw();
	}
}
});
	
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>