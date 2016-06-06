<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">在学管理</a>
	</li>
	<li class="active">活动管理</li>
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
		在学管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			活动管理
			<a type="button" title="添加活动" href="/master/student/activity/add" class="btn btn-primary btn-sm btn-default btn-sm" style="float:right;">
					<i class="ace-icon fa fa-user bigger-110"></i>
					添加活动
			</a>
	
			</div>
				
									<!-- <div class="table-responsive"> -->
								<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
									<!-- <div class="dataTables_borderWrap"> -->
										<div>   
										<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
											<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/index?label_id=1"><h5>待批准活动</h5></a>
											</li>
											<li <?php if($label_id ==5):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/index?label_id=5"><h5>未批准活动</h5></a>
											</li>
											<li <?php if($label_id ==6):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/index?label_id=6"><h5>已批准活动</h5></a>
											</li>
											<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/index?label_id=2"><h5>进行中活动</h5></a>
											</li>
											<li <?php if(!empty($label_id) && $label_id =='3'):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/index?label_id=3"><h5>结束未打分活动</h5></a>
											</li>
											<li <?php if(!empty($label_id) && $label_id =='4'):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/index?label_id=4"><h5>结束已打分活动</h5></a>
											</li>
											
											
										</ul>    
											
											<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
												<thead>
													<tr>
														
														<th width="70">
															ID
														</th>
														<th width="150">标题</th>
														<th>报名截止时间</th>
														<th>结束时间</th>
														<th>发布者</th>
														<th>是否接受报名</th>
														<th>发布时间</th>
													
														<th width="120"></th>
													</tr>
												</thead>
												<thead>
													<tr>
													
														<th>
															 <input type="text" id="student_obj_ID" placeholder="ID" style="width:70px;">
														</th>
														<th><input type="text" id="student_ctitle" placeholder="标题" style="width:110px;"></th>
														<th>
															<input type="text" id="student_starttime" placeholder="开始时间" style="width:120px;">
															
														</th>
														<th><input type="text" id="student_endtime" placeholder="结束时间" style="width:140px;"></th>
														<th>
															 <input type="text" id="student_username" placeholder="发布者" style="width:100px;">
														</th>
														<th>
															<select id="student_isapply" style="width:90px;">
																	
																	<option value="">是否接受报名</option>
																	<option value="1">是</option>
																	<option value="-1">否</option>
																
															</select>
														</th>
														<th><input type="text" id="student_createtime" placeholder="创建时间" style="width:140px;"></th>
													
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
function edit_state(id,state){
	pub_alert_confirm('/master/student/activity/edit_state?id='+id+'&state='+state);

}
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
            "sAjaxSource":'/master/student/activity?label_id=<?=$label_id?>',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 7 ] }],
            "aaSorting" : [[0,'desc']],
            "aoColumns" : [
                   			   	
                   			    { "mData": "id" },
								{ "mData": "ctitle" },
								{ "mData": "starttime" },
								{ "mData": "endtime" },
								{ "mData": "username" },
								{ "mData": "isapply" },
								{ "mData": "createtime" },
								{"mData":"operation"}
                ]
        });


	$('#student_obj_ID').on( 'keyup', function () {
            zjj_datatable_search(0,$("#student_obj_ID").val());
        } );
	$('#student_ctitle').on( 'keyup', function () {
            zjj_datatable_search(1,$("#student_ctitle").val());
        } );
	$('#student_starttime').on( 'keyup', function () {
            zjj_datatable_search(2,$("#student_starttime").val());
        } );
	$('#student_endtime').on( 'keyup', function () {
            zjj_datatable_search(3,$("#student_endtime").val());
        } );
	$('#student_username').on( 'keyup', function () {
            zjj_datatable_search(4,$("#student_username").val());
        } );

		
	$('#student_isapply').on( 'change', function () {
            zjj_datatable_search(5,$("#student_isapply").val());
        } );
	$('#student_createtime').on( 'keyup', function () {
            zjj_datatable_search(6,$("#student_createtime").val());
        } );
  function zjj_datatable_search(column,val){
        $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
    }
}
function handle(){
	var majorid=$('#majorid').val();

	
		$.ajax({
			url: '<?=$zjjp?>student/student/get_squad/'+majorid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {

			$("#squadid").empty();
			 $.each(r.data, function(i, k) { 
			 	 var opt = $("<option/>").text(k.name).attr("value",k.id);
			 	  $("#squadid").append(opt); 
			  	//alert(r.data[i]);  
             });
			//alert(r.data);
		
		})
		.fail(function() {

			alert('error');
		})

}
function submit(){
	var id=$('#id').val();
	var majorid = $('#majorid').val();
	var squadid =$('#squadid').val();
	var data = {"id":id,"majorid":majorid,"squadid":squadid};
	$.ajax({
		url: "<?=$zjjp?>student/student/addqm",
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			window.location.href="<?=$zjjp?>student/student";
			
		}
	})
	.fail(function() {
		console.log("error");
	})
	
	
	//if(r.state==1){
	//	window.location.href="<?=$zjjp?>student/student";
	//}
}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>