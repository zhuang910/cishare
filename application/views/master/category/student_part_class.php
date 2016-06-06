<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">教务管理</a>
	</li>
	<li class="active">分班管理</li>
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
		分班管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			批量分班
			<a style="float:right;" href="javascript:history.back()" class="btn btn-primary btn-sm btn-default btn-sm" title="返回" type="button">
					<span class="ace-icon fa fa-mail-reply"></span>
					返回
			</a>
			<!-- <button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>student/student/export_where?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="导出" type="button">
					<span class="ace-icon fa fa-mail-forward"></span>
					导出
			</button> -->
	
			</div>
								
								<div class="widget-body">
									<div class="widget-main">
										<form class="form-inline" id="condition">
											<label class="control-label" for="major">专业:</label>
											<!--<select name='majorid' style="width: 120px" id="major" onchange="majors()">
												<option value="0">--请选择 --</option>>
												<?php// foreach($major_info as $k=>$v):?>
													<option value="<?//=$v->id?>"><?//=$v->name?></option>
												<?php// endforeach;?>
											</select>-->
											<select name='edit_type' style="width: 120px" id="major" onchange="majors()">
												<option value="0">--请选择 --</option>>
												<?php foreach($major_info as $item){ ?>
												<optgroup label="<?=$item['degree_title']?>">
												<?php foreach($item['degree_major'] as $item_info){ ?>
													<option value="<?=$item_info->id?>"><?=$item_info->name?></option>
												<?php } ?>
												</optgroup>
												<?php } ?>
											</select>
											<label class="control-label" for="squadids">班级:</label>
											<select id="squad" class="input-medium valid" name="squadid" aria-required="true" aria-invalid="false">
												<option value="0">--请选择--</option>
											</select>
											<a class="btn btn-info btn-sm" type="button" onclick="student_part_class()">
												分班
											</a>
										</form>
									</div>
								</div>
									<!-- <div class="table-responsive"> -->

									<!-- <div class="dataTables_borderWrap"> -->
										<div> 
								     <form class="form-inline" id="conditions" method="post" onSubmit="return derive()">                         
										<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
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
														<th width="50"><input id="all" checke="true" type="checkbox" onclick="alll()"></th>
														<th width="60" class="center">
															ID
														</th>
														<th width="80">学号</th>
														<th width="100">中文名</th>
														<th width="100">英文名</th>
														<th width="100">护照号</th>
														<th width="100">专业</th>
														<th width="100">国籍</th>
														<th width="100">汉语水平</th>
														<th width="150">测试分数</th>
														<th class="right"></th>
													</tr>
												</thead>
												<thead>
													<tr>
														<th width="50"></th>
														<th width="60" class="center">
															 <input type="text" id="student_obj_ID" placeholder="ID" style="width:60px;">
														</th>
														<th width="80">
															<input type="text" id="student_studentid" placeholder="学号" style="width:80px;;">
														</th>
														<th width="100">
															<input type="text" id="student_name" placeholder="中文名" style="width:100px;">
														</th>
														<th width="100">
															<input type="text" id="student_lastname" placeholder="英文名" style="width:100px;">
														</th>
														
														<th width="100">
															<input type="text" id="student_passport" placeholder="护照号" style="width:100px;">
														</th>
														<th width="100">
															<select id="student_major" style="width:90px;">
																	<option value="">-请选择-</option>
																	<?php foreach($major_info as $item){ ?>
														<optgroup label="<?=$item['degree_title']?>">
														<?php foreach($item['degree_major'] as $item_info){ ?>
															<option value="<?=$item_info->id?>"><?=$item_info->name?></option>
														<?php } ?>
														</optgroup>
														<?php } ?>
															</select>
														</th>
														<th width="100">
														
															<select id="student_nationality" style="width:90px;">
																<option value="">-请选择-</option>
																<?php if(!empty($nationality['global_country_cn'])){?>
																<?php foreach($nationality['global_country_cn'] as $k=>$v){?>
																	<option value="<?=$k?>"><?=$v?></option>

																<?php }?>
																<?php }?>
															</select>
														</th>
														<th>
															<select id="language_level" style="width:90px;">
																	<option value="">-请选择-</option>
																	<option value="None">None</option>
																	<option value="Primary">Primary</option>
																	<option value="Intermediate">Intermediate</option>
																	<option value="Advanced">Advanced</option>
																	<option value="Beginner">Beginner</option>
															</select>
														</th>
														<th width="140"><input type="text" id="student_score" placeholder="分数" style="width:100px;"></th>
														<th class="right"></th>
													</tr>
												</thead>
												<tbody id="tbody">
													
												
												</tbody>

											</table>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
								
						<!--分班-->
						<div id="modal-table" class="modal fade" tabindex="-1">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												选择班级
											</div>
										</div>

										
										<div class="form-group" id="m">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">专业:</label>
											<input type="hidden" id='id' name="id" />
												
													<select id="majorid" class="input-medium valid" name="majorid" aria-required="true" aria-invalid="false"  onchange="handle()">
														<option value="0">--请选择 --</option>>
														<?php foreach($major_info as $item){ ?>
														<optgroup label="<?=$item['degree_title']?>">
														<?php foreach($item['degree_major'] as $item_info){ ?>
															<option value="<?=$item_info->id?>"><?=$item_info->name?></option>
														<?php } ?>
														</optgroup>
														<?php } ?>
													
													</select>
											
										
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">班级:</label>
											
												
													<select id="squadid" class="input-medium valid" name="squadid" aria-required="true" aria-invalid="false">
														
														
													
													</select>
											
										
										
										<div class="modal-footer no-margin-top">
											
											<div class="space-2"></div>
													<div class="col-md-offset-3 col-md-9">
														<button class="btn btn-info" data-last="Finish" onclick="submit()">
															<i class="ace-icon fa fa-check bigger-110"></i>
																提交
														</button>
														<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
																重置
														</button>
													</div>
									
									
									</div>
								</div>
							</div>
						<!--end分班-->
			
					

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

<script type="text/javascript">
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
function message(){
	$('#conditions').attr({
		action: '/master/student/student/send_message',
	});
}
function email(){
	$('#conditions').attr({
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
function student_part_class(){
	var is_subimt = false;
	 $("input[name='sid[]']").each(function(){
		 	 if(this.checked==true){
		 	 	 is_subimt = true;
		 	 }
		  });

	 if(is_subimt === false){
	 	pub_alert_error('请选择学生');
	 }
	var majorid=$("#major").val();
	var squadid=$("#squad").val();
	var data=$("#conditions").serialize();
	$.ajax({
		url: "<?=$zjjp?>student/student/do_student_part_class?majorid="+majorid+"&squadid="+squadid,
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success(r.info);
			setTimeout(jump,1000)
		}
	})
	.fail(function() {
		console.log("error");
	})

}
function jump(){
	window.location.href="/master/student/student/part_class";
}
function checkbox(){
	var s=$('#all').attr();
	alert(s);
}
function majors(){
	var majorid=$('#major').val();
	$("#squad").empty();
	$("#squad").append("<option value='0'>—请选择—</option>"); 
	$.ajax({
		url: "<?=$zjjp?>student/student/get_squad/"+majorid,
		type: 'POST',
		dataType: 'json',
		data: {}
	})
	.done(function(r) {
		if(r.state==1){
			 $.each(r.data, function(k, v) { 
			 	var opt = $("<option/>").text(v.name).attr("value",v.id);
			 	  $("#squad").append(opt); 
			  });
		}
	})
	.fail(function() {
		console.log("error");
	})
}
function input(id,h){

	$('#id').attr({
		value: id
	});
	
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
            "sAjaxSource":'<?=$zjjp?>student/student/part_class',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 0,9,10 ] }],
            "aaSorting" : [[2,'desc']],
            "aoColumns" : [
                    { "mData": "checkbox" },
					{ "mData": "id" },
					{ "mData": "studentid" },
					{ "mData": "name" },
					{ "mData": "enname" },
					{ "mData": "passport" },
					{ "mData": "majorid" },
					{ "mData": "nationality" },
					{ "mData": "language_level" },	
					{ "mData": "score" },
					{"mData":"operation"}
                ]
        });


	$('#student_obj_ID').on( 'keyup', function () {
            zjj_datatable_search(1,$("#student_obj_ID").val());
        } );
	$('#student_studentid').on( 'keyup', function () {
            zjj_datatable_search(2,$("#student_studentid").val());
        } );
	$('#student_name').on( 'keyup', function () {
            zjj_datatable_search(3,$("#student_name").val());
        } );
	$('#student_firstname').on( 'keyup', function () {
            zjj_datatable_search(4,$("#student_firstname").val());
        } );
	$('#student_lastname').on( 'keyup', function () {
            zjj_datatable_search(5,$("#student_lastname").val());
        } );
	$('#student_passport').on( 'keyup', function () {
            zjj_datatable_search(6,$("#student_passport").val());
        } );
	$('#student_major').on( 'change', function () {
            zjj_datatable_search(7,$("#student_major").val());
        } );
	$('#student_nationality').on( 'change', function () {
            zjj_datatable_search(8,$("#student_nationality").val());
        } );
	$('#language_level').on( 'change', function () {
            zjj_datatable_search(9,$("#language_level").val());
        } );
	$('#student_score').on( 'keyup', function () {
            zjj_datatable_search(10,$("#student_score").val());
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