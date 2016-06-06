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
		<a href="javascript:;">服务和项目</a>
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
		服务和项目
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="table-responsive">

			 <div class="dataTables_borderWrap"> 
				<div> 
				<div class="table-header">
			服务和项目
			<a class="btn btn-primary btn-sm btn-default btn-sm" title="添加内容" type="button" href="/master/cms/server/add?columnid=<?=$columnid?>" style="float:right;">
					<span class="glyphicon  glyphicon-plus"></span>
					添加内容
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
								<th  width="400">项目名称</th>
								<th  width="100">类型</th>
								<th  width="100">国别</th>
								<th  width="100">目的</th>
								<th  width="100">状态</th>
								<th>操作</th>
								
							</tr>
						</thead>
						<thead>
						<tr>
							<th>
                                <input type="text" id="ppt_id" placeholder="ID" style="width:50px;">
                            </th>
							<th>
                                 <input type="text" id="ppt_title" placeholder="项目名称" style="width:300px;">
                            </th>
							
							 
							<th>
                                <select id="ppt_type" style="width:100px;">
								<option value="">--类型-</option>
								<?php if(!empty($type)){
									foreach($type as $k => $v){
								?>
								<option value="<?=$k?>"><?=$v?></option>
								<?php }}?>
								</select>
								
                                
                            </th>
							<th>
                                <select id="ppt_country" style="width:100px;">
								<option value="">--国别-</option>
								<?php if(!empty($country)){
									foreach($country as $kc => $vc){
								?>
								<option value="<?=$kc?>"><?=$vc?></option>
								<?php }}?>
								</select>
								
                                
                            </th>
							<th>
                                <select id="ppt_purpose" style="width:100px;">
								<option value="">--目的-</option>
								<?php if(!empty($purpose)){
									foreach($purpose as $kp => $vp){
								?>
								<option value="<?=$kp?>"><?=$vp?></option>
								<?php }}?>
								</select>
								
                                
                            </th>
							
								<th>
                                <select id="ppt_state" style="width:100px;">
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
	function del(columnid,id){
		pub_alert_confirm('/master/cms/server/del?columnid='+columnid+'&id='+id);
	}
</script>
<script type="text/javascript">
	function edit_state(columnid,id,state){
		pub_alert_confirm('/master/cms/server/edit_state?columnid='+columnid+'&id='+id+'&state='+state);
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
            "sAjaxSource":'<?=$zjjp?>server/index?columnid=<?=$columnid?>',
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
					{ "mData": "type" },
					{ "mData": "country" },
					{ "mData": "purpose" },
					{ "mData": "state" },
					{ "mData": "operation" }
                ]
        });
		
	
	$('#ppt_id').on( 'keyup', function () {
            zjj_datatable_search(0,$("#ppt_id").val());
        } );
		
		$('#ppt_title').on( 'keyup', function () {
            zjj_datatable_search(1,$("#ppt_title").val());
        } );
		 $('#ppt_type').change(function () {
            zjj_datatable_search(2,$("#ppt_type").val());
        } );
		$('#ppt_country').change(function () {
            zjj_datatable_search(3,$("#ppt_country").val());
        } );
		$('#ppt_purpose').change(function () {
            zjj_datatable_search(4,$("#ppt_purpose").val());
        } );
        $('#ppt_state').change(function () {
            zjj_datatable_search(5,$("#ppt_state").val());
        } );

		  

        function zjj_datatable_search(column,val){
            $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
        }
	
}


});


</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>