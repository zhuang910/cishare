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
		活动管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			查看用户
			
	
			</div>
				
									<!-- <div class="table-responsive"> -->
								<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
									<input type="hidden" name="is_userid" value="yes">
									<!-- <div class="dataTables_borderWrap"> -->
										<div>   
										<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
											<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/ckuser?label_id=1&id=<?=$id?>"><h5>待审核用户</h5></a>
											</li>
											
											<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/ckuser?label_id=2&id=<?=$id?>"><h5>通过用户</h5></a>
											</li>
											<li <?php if(!empty($label_id) && $label_id =='3'):?> class="active"<?php endif;?>>
											<a href="/master/student/activity/ckuser?label_id=3&id=<?=$id?>"><h5>未通过用户</h5></a>
											</li>
											<li style="float:right;">
											<button onclick="message()" class="btn btn-info" data-last="Finish">
												<i class="ace-icon fa fa-comment "></i>
												<span class="bigger-110">批量发站内信</span>
											</button>
											<button onclick="email()" class="btn btn-info" data-last="Finish">
												<i class="ace-icon fa fa-envelope"></i>
												<span class="bigger-110">批量发邮件</span>
											</button>
											</li>
										</ul>    
											
											<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
												<thead>
													<tr>
														<th>
															<input id="all" checke="true" type="checkbox" onclick="alll()">
														</th>
														<th width="70">
															ID
														</th>
														<th>姓名</th>
														<th>护照</th>
														<th>专业</th>
														<th>班级</th>
														<th>性别</th>
														<th>国籍</th>
														<th>打分</th>
													
														<th width="120"></th>
													</tr>
												</thead>
												<thead>
													<tr>
														<th></th>
														<th>
															 <input type="text" id="student_obj_ID" placeholder="ID" style="width:70px;">
														</th>
														<th><input type="text" id="student_name" placeholder="姓名" style="width:110px;"></th>
														<th>
															<input type="text" id="student_passport" placeholder="护照" style="width:120px;">
															
														</th>
														<th><input type="text" id="student_major" placeholder="专业" style="width:140px;"></th>
														<th>
															 <input type="text" id="student_classid" placeholder="班级" style="width:100px;">
															 
														</th>
														<th>
															 <select id="student_sex" style="width:90px;">
																	
																	<option value="">性别</option>
																	<option value="1">男</option>
																	<option value="2">女</option>
																
															</select>
															 
														</th>
														
														<th>
															<select id="student_nationality" style="width:90px;">
																	
																	<option value="">国籍</option>
																	<?php if(!empty($nationality)){
																		foreach($nationality as $k => $v){
																	?>
																		<option value="<?=$k?>"><?=$v?></option>
																	<?php }}?>
																
															</select>
														</th>
														<th>
														<select id="student_score" style="width:90px;">
																	
																	<option value="">打分</option>
																	
																		<option value="1">非常差 </option>
																		<option value="2">差</option>
																		<option value="3">一般</option>
																		<option value="4">好<option>
																		<option value="5">非常好</option>
																	
																
															</select>
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
function message(){
	$('#checked').attr({
		action: '/master/student/student/send_message',
	});
}
function email(){
	$('#checked').attr({
		action: '/master/student/student/send_email',
	});
}
function alll(){
  	 if($("#all").attr("checke") == "true"){
		  $("input[name='sid[]']").each(function(){
		 	 this.checked=true;
		  });
		   $("#all").attr("checke","flase")
	  }else{
	  		$("input[name='sid[]']").each(function(){
			   this.checked=false;
		 	 });
		  	 $("#all").attr("checke","true");
	  }
}
function derive(){
	var is_subimt = false;
	 $("input[name='sid[]']").each(function(){
		 	 if(this.checked==true){
		 	 	 is_subimt = true;
		 	 }
		  });

	 if(is_subimt === false){
	 	pub_alert_error('请选择学生');
	 }
	 
	 return is_subimt;
}
function edit_user_state(id,state){
	pub_alert_confirm('/master/student/activity/edit_user_state?id='+id+'&state='+state);

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
            "sAjaxSource":'/master/student/activity/ckuser?label_id=<?=$label_id?>&id=<?=$id?>',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 0,8 ] }],
            "aaSorting" : [[2,'desc']],
            "aoColumns" : [
                   			   	{ "mData": "checkbox" },
                   			    { "mData": "id" },
								{ "mData": "name" },
								{ "mData": "passport" },
								{ "mData": "major" },
								{ "mData": "classid" },
								{ "mData": "sex" },
								{ "mData": "nationality" },
								{ "mData": "score" },
								{"mData":"operation"}
                ]
        });


	$('#student_obj_ID').on( 'keyup', function () {
            zjj_datatable_search(0,$("#student_obj_ID").val());
        } );
	$('#student_name').on( 'keyup', function () {
            zjj_datatable_search(1,$("#student_name").val());
        } );
	$('#student_passport').on( 'keyup', function () {
            zjj_datatable_search(2,$("#student_passport").val());
        } );
	$('#student_major').on( 'keyup', function () {
            zjj_datatable_search(3,$("#student_major").val());
        } );
	$('#student_classid').on( 'keyup', function () {
            zjj_datatable_search(4,$("#student_classid").val());
        } );

		
	$('#student_sex').on( 'change', function () {
            zjj_datatable_search(5,$("#student_sex").val());
        } );
		$('#student_nationality').on( 'change', function () {
            zjj_datatable_search(6,$("#student_nationality").val());
        } );
		$('#student_score').on( 'change', function () {
            zjj_datatable_search(7,$("#student_score").val());
        } );
	
  function zjj_datatable_search(column,val){
        $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
    }
}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>