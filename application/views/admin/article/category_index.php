<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">文章管理</a>
	</li>
	<li class="active">分类管理</li>
</ul>
EOD;
?>
<?php $this->load->view('admin/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>admin/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>admin/css/jquery-ui.min.css" />


<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		分类管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			分类管理
			<a class="btn btn-primary btn-sm btn-default btn-sm" title="添加" type="button" href="/admin/article/category/add" style="float:right;">
				<span class="glyphicon  glyphicon-plus"></span>
				添加
			</a>
			</div>
					<div class="widget-body">
						<div class="widget-main">
							<form class="form-inline" id="condition">


							</form>
						</div>
					</div>
									<!-- <div class="table-responsive"> -->
								<form id="checked" method="post" onSubmit="return derive()" action="">
									<!-- <div class="dataTables_borderWrap"> -->
										<div>


											<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
												<thead>
													<tr>
														<th width="90">ID</th>
														<th>分类名称</th>
														<th width="120">操作</th>
													</tr>
												</thead>
												<thead>
													<tr>
													    <th>
															<input type="text" id="cat_id" placeholder="ID" style="width:50px;">
														</th>
														<th>
                                                            <input type="text" id="name" placeholder="名称">
														</th>

														<th width="120"></th>
													</tr>
												</thead>
												<tbody>

												</tbody>

											</table>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/admin/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>admin/js/ace-extra.min.js"></script>
<script src="<?=RES?>/admin/js/ace-elements.min.js"></script>
<script src="<?=RES?>/admin/js/ace.min.js"></script>
<script src="<?=RES?>admin/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>admin/js/jquery.dataTables.bootstrap.js"></script>
<!-- delete -->
<script src="<?=RES?>admin/js/jquery-ui.min.js"></script>

<script type="text/javascript">
	function del(id){
		pub_alert_confirm('/admin/article/category/del?id='+id);
	}
</script>

<script type="text/javascript">


	if($('#sample-table-2').length > 0){

		$("#sample-table-2").dataTable({
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
            },
            "bProcessing":true,
            "bServerSide":true,
            "bStateSave":true,
            "sAjaxSource":'<?=$access_path?>category',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },

            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 0,2 ] }],
            "aaSorting" : [[0,'desc']],
            "aoColumns" : [
                   			   	{ "mData": "cat_id" },
								{ "mData": "category_name" },
								{"mData":"operation"}
                ]
        });

	$('#cat_id').on( 'keyup', function () {
            zjj_datatable_search(0,$("#cat_id").val());
        } );
	$('#name').on( 'keyup', function () {
		zjj_datatable_search(1,$("#name").val());
	} );
} function zjj_datatable_search(column,val){
		$('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
	}

function submit(){
	var id=$('#id').val();
	var majorid = $('#majorid').val();
	var squadid =$('#squadid').val();
	var data = {"id":id,"majorid":majorid,"squadid":squadid};
	$.ajax({
		url: "<?=$access_path?>article/category/addqm",
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			window.location.href="<?=$access_path?>article/category";

		}
	})
	.fail(function() {
		console.log("error");
	})


	//if(r.state==1){
	//	window.location.href="<?=$access_path?>student/student";
	//}
}
</script>

<!-- end script -->
<?php $this->load->view('admin/public/footer');?>