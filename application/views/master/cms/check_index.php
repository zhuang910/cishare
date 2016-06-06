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
	<li>
		<a href="javascript:;">内容审核</a>
	</li>
	
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
	内容审核
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="table-responsive">

			 <div class="dataTables_borderWrap"> 
				<div> 
				<div class="table-header">
		内容审核
			
<a type="button" title="返回上一级" class="btn btn-primary btn-sm btn-default btn-sm" href="javascript:history.back();" style="float:right;">
					<span class="ace-icon fa fa-reply"></span>
					返回上一级
				</a>			
			</div>
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				    <li <?php if($label_id ==0):?> class="active"<?php endif;?>>
                    <a href="/master/cms/check/index?label_id=0"><h5>待审核</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='1'):?> class="active"<?php endif;?>>
                    <a href="/master/cms/check/index?label_id=1"><h5>通过</h5></a>
                    </li>
					
                   
				</ul>
					<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
						<thead>
							<tr>
								<th class="center" width="50">
									ID
								</th>
								<th  width="300">标题</th>
								<th  width="100">栏目</th>
								<th  width="100">状态</th>
								<th  width="100">创建时间</th>
								<?php if($label_id == 1){?>
								<th  width="100">审核时间</th>
								<th  width="100">审核者</th>
								<?php }?>
								<th>操作</th>
								
							</tr>
						</thead>
						<thead>
						<tr>
							<th>
                                <input type="text" id="ppt_id" placeholder="ID" style="width:50px;">
                            </th>
							<th>
                                 <input type="text" id="ppt_title" placeholder="标题" style="width:300px;">
                            </th>
							
							<th>
                                <select id="ppt_columnid" style="width:100px;">
								<option value="">--栏目-</option>
								<option value="15">新闻</option>
								<option value="16">事件</option>
								</select>
								
                                
                            </th>
							 
							<th>
                                <select id="ppt_state" style="width:100px;">
								<option value="">--状态-</option>
								<option value="1">启用</option>
								<option value="-1">停用</option>
								</select>
								
                                
                            </th>
							<th><input type="text" id="ppt_createtime" placeholder="创建时间" style="width:100px;"></th>
							<?php if($label_id == 1){?>
							<th></th>
							<th></th>
							<?php }?>
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
	function del(columnid,id){
		pub_alert_confirm('/master/cms/check/del?columnid='+columnid+'&id='+id);
	}
</script>
<script type="text/javascript">
	function edit_state(id,state){
		pub_alert_confirm('/master/cms/check/edit_state?id='+id+'&state='+state);
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
            "sAjaxSource":'<?=$zjjp?>check/index?label_id=<?=$label_id?>',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 3 ] }],
            "aaSorting" : [[2,'desc']],
            "aoColumns" : [
                    { "mData": "id" },
					{ "mData": "title" },
					{ "mData": "columnid" },
					{ "mData": "state" },
					{ "mData": "createtime" },
					<?php if($label_id == 1){?>
					{ "mData": "checktime" },
					{ "mData": "checkadmin" },
					
					<?php }?>
					{ "mData": "operation" }
                ]
        });
		
	
	$('#ppt_id').on( 'keyup', function () {
            zjj_datatable_search(0,$("#ppt_id").val());
        } );
		
		$('#ppt_title').on( 'keyup', function () {
            zjj_datatable_search(1,$("#ppt_title").val());
        } );
		$('#ppt_columnid').change( 'keyup', function () {
            zjj_datatable_search(2,$("#ppt_columnid").val());
        } );

        $('#ppt_state').change(function () {
            zjj_datatable_search(3,$("#ppt_state").val());
        } );

		   $('#ppt_createtime').on( 'keyup', function () {
            zjj_datatable_search(4,$("#ppt_createtime").val());
        } );
	

        function zjj_datatable_search(column,val){
            $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
        }
	
}


});


</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>