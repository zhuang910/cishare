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
	<li class="active">发书管理</li>
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
		教务管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			学生列表
			<a type="button" href="javascript:;" onclick="pub_alert_html('/master/student/send_book/excel_page')" title="导出发书学生" class="btn btn-primary btn-sm btn-default btn-sm"  style="float:right;">
					<i class="ace-icon fa fa-user bigger-110"></i>
					导出页面
			</a>
			
		</div>
					<div class="widget-body">
					
					</div>
									<!-- <div class="table-responsive"> -->
								<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
									<!-- <div class="dataTables_borderWrap"> -->
										<div>   
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
														<th>
															<input id="all" checke="true" type="checkbox" onclick="alll()">
														</th>
														<th>
															<label class="position-relative">
															ID
															</label>
														</th>
														<th>学生名</th>
														<th>英文名称</th>
														<th>护照</th>
														<th>所属专业->班级</th>
														<th>学期</th>
														<th>发书状态</th>
														<th width="200"></th>
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
		action: '/master/student/send_book/send_message',
	});
}
function email(){
	$('#checked').attr({
		action: '/master/student/send_book/send_email',
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
            "sAjaxSource":'<?=$zjjp?>send_book',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 0,6,7,8 ] }],
            "aaSorting" : [[2,'desc']],
            "aoColumns" : [
            					{ "mData": "checkbox" },
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "enname" },
								{ "mData": "passport" },
								{ "mData": "majorsquad" },
								{ "mData": "nowterm" },
								{ "mData": "send_book_state" },
								{"mData":"operation"}
                ]
        });
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