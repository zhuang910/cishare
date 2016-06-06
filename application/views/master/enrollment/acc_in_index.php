<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">住宿管理</a>
	</li>
	<li class="active">入住办理</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
         <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		住宿管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		
					<div class="widget-body">
						<div class="widget-main">
						</div>
					</div>
									<!-- <div class="table-responsive"> -->
								<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
									<!-- <div class="dataTables_borderWrap"> -->
										<div>   
										<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
											<li <?php if($label_id ==3):?> class="active"<?php endif;?>>
											<a href="/master/enrollment/acc_in/index?&label_id=3"><h5>预订成功</h5></a>
											</li>
											<li <?php if(!empty($label_id) && $label_id =='1'):?> class="active"<?php endif;?>>
											<a href="/master/enrollment/acc_in/index?&label_id=1"><h5>预订失败</h5></a>
											</li>
											<li <?php if(!empty($label_id) && $label_id =='6'):?> class="active"<?php endif;?>>
											<a href="/master/enrollment/acc_in/index?&label_id=6"><h5>已入住</h5></a>
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
											<input type="hidden" name="is_userid" value="1">
											<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
												<thead>
													<tr>
														<th>
															<input id="all" checke="true" type="checkbox" onclick="alll()">
														</th>
														<th width="70">
															ID
														</th>
														<th width="100">英文名</th>
														<th>邮箱</th>
														<th>性别</th>
														<th>国籍</th>
														<th>护照号</th>
														<th>申请时间</th>
														<th width="200"></th>
													</tr>
												</thead>
												<thead>
													<tr>
													<th></th>
														<th>
															 <input type="text" id="student_obj_ID" placeholder="ID" style="width:70px;">
														</th>
														<th><input type="text" id="user_name" placeholder="姓名" style="width:110px;"></th>
														<th>
															<input type="text" id="user_email" placeholder="邮箱" style="width:120px;">
															
														</th>
														<th>
															<select id="user_sex" style="width:90px;">
																	<option value="0">请选择----</option>
																	<option value="1">男</option>
																	<option value="2">女</option>
															</select>
														</th>
														<th>
															<select id="nationality" style="width:90px;">
																<?php foreach($nationality['global_country_cn'] as $k=>$v):?>
																	<option value="<?=$k?>"><?=$v?></option>
																<?php endforeach;?>
															</select>
														</th>
														<th>
															<input type="text" id="user_passport" placeholder="护照号" style="width:80px;">
														</th>
														<th></th>
													
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

<script type="text/javascript">
function message(){
	$('#checked').attr({
		action: '/master/student/student/send_message',
	});
}
function exports(){
	$('#checked').attr({
		action: '/master/student/student/derive_part',
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
function part_class(){
	window.location.href="/master/student/student/part_class";
}
function major_where(){
	var majorid =$('#major').val();

	window.location.href="/master/student/student?major="+majorid;
}
function squad_where(){
	var majorid =$('#major').val();
	 var squadid =$('#squadids').val();
	window.location.href="/master/student/student?major="+majorid+'&squad='+squadid;
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
            "sAjaxSource":'<?=$zjjp?>acc_in?label_id=<?=$label_id?>',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 0,8] }],
            "aaSorting" : [[1,'desc']],
            "aoColumns" : [
                   			   	{ "mData": "checkbox" },
                   			    { "mData": "id" },
								{ "mData": "enname" },
								{ "mData": "email" },
								{ "mData": "sex" },
								{ "mData": "nationality" },
								{ "mData": "passport" },
								{ "mData": "applytime" },
								{"mData":"operation"}
                ]
        });


	$('#student_obj_ID').on( 'keyup', function () {
            zjj_datatable_search(0,$("#student_obj_ID").val());
        } );
	$('#user_name').on( 'keyup', function () {
            zjj_datatable_search(1,$("#user_name").val());
        } );
	$('#user_email').on( 'keyup', function () {
            zjj_datatable_search(2,$("#user_email").val());
        } );

	$('#user_sex').on( 'change', function () {
            zjj_datatable_search(3,$("#user_sex").val());
        } );
	$('#nationality').on( 'change', function () {
            zjj_datatable_search(4,$('#nationality').val());
        } );
	$('#user_passport').on( 'keyup', function () {
            zjj_datatable_search(5,$("#user_passport").val());
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