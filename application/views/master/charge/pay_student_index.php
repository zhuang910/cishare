<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">收费管理</a>
	</li>
	<li class="active">缴费管理-在学</li>
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
		缴费管理-在学
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			缴费管理-在学
			</div>
					<div class="widget-body">
						<div class="widget-main">
							<form class="form-inline" id="condition">
								<label class="control-label" for="major">专业:</label>
								<select name='edit_type' style="width: 120px" id="major" onchange="major_where()">
                                    <option value="0">--请选择 --</option>>
								    <?php foreach($major_info as $item){ ?>
								    <optgroup label="<?=$item['degree_title']?>">
								    <?php foreach($item['degree_major'] as $item_info){ ?>
                                        <option value="<?=$item_info->id?>" <?php if($item_info->id==$majorid){echo 'selected="selected"';}?>><?=$item_info->name?></option>
                                    <?php } ?>
                                    </optgroup>
                                    <?php } ?>
								</select>
								<label class="control-label" for="squadids">班级:</label>
								<select onchange="squad_where()" id="squadids" class="input-medium valid" name="squadid" aria-required="true" aria-invalid="false">
									<option value="0">--请选择--</option>
									<?php if($squad_info!=0):?>
    <?php foreach($squad_info as $k=>$v):?>
        <option <?php if($v['id']==$squadid){echo 'selected="selected"';}?> value="<?=$v['id']?>"><?=$v['name']?></option>
    <?php endforeach;?>
<?php endif;?>
								</select>
							</form>
						</div>
					</div>
									<!-- <div class="table-responsive"> -->
								<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
									<!-- <div class="dataTables_borderWrap"> -->
										<div>   
										<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
											<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=1"><h5>在校</h5></a>
                                            </li>
                                            <li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=2"><h5>转学</h5></a>
                                            </li>
                                            <li <?php if(!empty($label_id) && $label_id =='3'):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=3"><h5>正常离开</h5></a>
                                            </li>
                                            <li <?php if(!empty($label_id) && $label_id =='4'):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=4"><h5>非正常离开</h5></a>
                                            </li>
                                            <li <?php if(!empty($label_id) && $label_id =='5'):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=5"><h5>休学</h5></a>
                                            </li>
                                            <li <?php if(!empty($label_id) && $label_id =='6'):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=6"><h5>申请</h5></a>
                                            </li>
                                            <li <?php if(!empty($label_id) && $label_id =='7'):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=7"><h5>已报到</h5></a>
                                            </li>
                                            <li <?php if(!empty($label_id) && $label_id =='8'):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=8"><h5>未报到</h5></a>
                                            </li>
											   <li <?php if(!empty($label_id) && $label_id =='9'):?> class="active"<?php endif;?>>
                                            <a href="/master/charge/pay_student/index?&label_id=9"><h5>留级</h5></a>
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
														<th width="150">学号</th>
														<th>基本信息</th>
														<th>专业信息</th>
														<th>护照号</th>
														<th>护照到期时间</th>
														<th>状态</th>
													
														<th width="120"></th>
													</tr>
												</thead>
												<thead>
													<tr>
													<th></th>
														<th>
															 <input type="text" id="student_obj_ID" placeholder="ID" style="width:70px;">
														</th>
														<th><input type="text" id="student_studentid" placeholder="学号" style="width:110px;"></th>
														<th>
															<input type="text" id="student_basic_info" placeholder="基本信息" style="width:120px;">
															<select id="nationality" style="width:90px;">
																<?php foreach($nationality['global_country_cn'] as $k=>$v):?>
    <option value="<?=$k?>"><?=$v?></option>
<?php endforeach;?>
															</select>
														</th>
														<th><input type="text" id="student_major" placeholder="专业信息" style="width:140px;"></th>
														<th>
															 <input type="text" id="student_passport" placeholder="护照号" style="width:100px;">
														</th>
														<th>
															<input type="text" id="student_passport_time" placeholder="到期时间" style="width:80px;">
														</th>
														<th>状态</th>
													
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
														<option value="0" selected="selected">-请选择-</option>
														<?php foreach($major_info as $k=>$v):?>

    <option value="<?=$v->id?>"

        ><?=$v->name?></option>
<?php endforeach;?>
													
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
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>

<script type="text/javascript">
function del(id){
	pub_alert_confirm('/master/student/student/del?id='+id);

}

function set_score_zero(id){
	pub_alert_confirm('/master/student/student/set_zero?id='+id);

}
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

	window.location.href="/master/charge/pay_student?label_id=<?=$label_id?>&major="+majorid;
}
function squad_where(){
	var majorid =$('#major').val();
	 var squadid =$('#squadids').val();
	window.location.href="/master/charge/pay_student?label_id=<?=$label_id?>&major="+majorid+'&squad='+squadid;
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
            "bStateSave":true,
            "sAjaxSource":'/master/charge/pay_student?major=<?=$majorid?>&squad=<?=$squadid?>&label_id=<?=$label_id?>',
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
								{ "mData": "studentid" },
								{ "mData": "basic_info" },
								{ "mData": "major_info" },
								{ "mData": "passport" },
								{ "mData": "passporttime" },
								{ "mData": "state" },
								{"mData":"operation"}
                ]
        });


	$('#student_obj_ID').on( 'keyup', function () {
            zjj_datatable_search(0,$("#student_obj_ID").val());
        } );
	$('#student_studentid').on( 'keyup', function () {
            zjj_datatable_search(1,$("#student_studentid").val());
        } );
	$('#student_basic_info').on( 'keyup', function () {
            zjj_datatable_search(2,$("#student_basic_info").val()+"-zjj-"+$('#nationality').val());
        } );
	$('#nationality').on( 'change', function () {
            zjj_datatable_search(2,$("#student_basic_info").val()+"-zjj-"+$('#nationality').val());
        } );
	$('#student_major').on( 'keyup', function () {
            zjj_datatable_search(3,$("#student_major").val());
        } );
	$('#student_passport').on( 'keyup', function () {
            zjj_datatable_search(4,$("#student_passport").val());
        } );
	$('#student_passport_time').on( 'keyup', function () {
            zjj_datatable_search(5,$("#student_passport_time").val());
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