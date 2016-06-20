<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">后台</a>
	</li>
	<li class="active">评论管理</li>
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
		评论管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="table-responsive">

			 <div class="dataTables_borderWrap"> 
				<div> 
				<div class="table-header">
			评论管理
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
								<th  width="400">文章标题</th>
								<th  width="50">用户</th>
								<th  width="400">评论内容</th>
								<th  width="100">评论时间</th>
								<th width="50">操作</th>
							</tr>
						</thead>
						<thead>
						<tr>
							<th>
                                <input type="text" id="reply_id" placeholder="ID" style="width:50px;">
                            </th>
							<th>
                                 <input type="text" id="title" placeholder="文章标题">
                            </th>
							<th>
								<input type="text" id="user_name" placeholder="用户" style="width:100px;">
							</th>
							<th></th>
							<th>
								<input type="text" id="add_time" placeholder="评论时间">
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
		pub_alert_confirm('/admin/article/reply/del?id='+id);
	}
</script>

<script type="text/javascript">
$(function(){
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
            "sAjaxSource":'<?=$access_path?>reply/index',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 5 ] }],
            "aaSorting" : [[0,'desc']],
            "aoColumns" : [
                    { "mData": "reply_id" },
					{ "mData": "title" },
					{ "mData": "user_name" },
					{ "mData": "content" },
					{ "mData": "add_time" },
					{ "mData": "operation" }
                ]
        });
		
	
	$('#reply_id').on( 'keyup', function () {
		zjj_datatable_search(0,$("#reply_id").val());
	} );

	$('#title').on( 'keyup', function () {
		zjj_datatable_search(1,$("#title").val());
	} );
	$('#user_name').on( 'keyup', function () {
		zjj_datatable_search(2,$("#user_name").val());
	} );
	$('#add_time').on( 'keyup', function () {
		zjj_datatable_search(3,$("#add_time").val());
	} );

	function zjj_datatable_search(column,val){
		$('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
	}

}
});

</script>

<!-- end script -->
<?php $this->load->view('admin/public/footer');?>