<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">后台</a>
	</li>

	<li>
		<a href="javascript:;">内容管理</a>
	</li>
	<li class="active">图集管理</li>
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
	<?=$site_language_admin[$_SESSION['language']]?>图集管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="table-responsive">

			 <div class="dataTables_borderWrap"> 
				<div> 
				<div class="table-header">
			图集列表
			<a class="btn btn-primary btn-sm btn-default btn-sm" title="添加图集" type="button" href="/master/cms/img/add?label_id=<?=$label_id?>" style="float:right;">
					<span class="glyphicon  glyphicon-plus"></span>
					添加图集
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
								<th  width="400">标题</th>
								<th  width="100">排序</th>
								<th  width="100">创建时间</th>
								<th>
									所属栏目
								</th>
								<th  width="100">状态</th>
								
								<th>操作</th>
								
							</tr>
						</thead>
						<thead>
						<tr>
							<th>
                                <input type="text" id="img_id" placeholder="ID" style="width:50px;">
                            </th>
							<th>
                                 <input type="text" id="img_title" placeholder="标题" style="width:300px;">
                            </th>
							<th>
                                 <input type="text" id="img_orderby" placeholder="排序" style="width:100px;">
                            </th>
							<th><input type="text" id="img_createtime" placeholder="创建时间" style="width:100px;"></th>

							 <th>
                                <select id="img_colum" style="width:100px;">
									<option value="">--栏目-</option>
									<?php foreach($column_info as $k=>$v):?>
										<option value="<?=$v['id']?>"><?=$v['title']?></option>
									<?php endforeach;?>
								</select>
                            </th>
							<th>
                                <select id="img_state" style="width:100px;">
								<option value="">--状态-</option>
								<option value="1">启用</option>
								<option value="-1">停用</option>
								</select>
								
                                
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
		pub_alert_confirm('/master/cms/img/del?id='+id);
	}
</script>
<script type="text/javascript">
	function edit_state(columnid,id,state){
		pub_alert_confirm('/master/cms/gallery/edit_state?columnid='+columnid+'&id='+id+'&state='+state);
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
            "sAjaxSource":'<?=$zjjp?>img?label_id=<?=$label_id?>',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 1,2,3,4,5 ] }],
            "aaSorting" : [[0,'desc']],
            "aoColumns" : [
                   			    { "mData": "id" },
								{ "mData": "title" },
								{ "mData": "orderby" },
								{ "mData": "createtime" },
								{ "mData": "columnid" },
								{ "mData": "state" },
								{ "mData": "operation" }
                ]
        });
		$('#img_id').on( 'keyup', function () {
            zjj_datatable_search(0,$("#img_id").val());
        } );
		
		$('#img_title').on( 'keyup', function () {
            zjj_datatable_search(1,$("#img_title").val());
        } );
		$('#img_orderby').on( 'keyup', function () {
            zjj_datatable_search(2,$("#img_orderby").val());
        } );

        $('#img_state').change(function () {
            zjj_datatable_search(3,$("#img_state").val());
        } );
        $('#img_colum').change(function () {
            zjj_datatable_search(5,$("#img_colum").val());
        } );
		   $('#img_createtime').on( 'keyup', function () {
            zjj_datatable_search(4,$("#img_createtime").val());
        } );
	

        function zjj_datatable_search(column,val){
            $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
        }
	
}

</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>