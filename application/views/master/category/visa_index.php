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
	<li class="active">签证管理</li>
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
			签证列表
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
											<a href="/master/student/visa?&label_id=1"><h5>两周预警</h5></a>
											</li>
											<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
											<a href="/master/student/visa?&label_id=2"><h5>一周预警</h5></a>
											</li>
											<li <?php if(!empty($label_id) && $label_id =='3'):?> class="active"<?php endif;?>>
											<a href="/master/student/visa?&label_id=3"><h5>已到期</h5></a>
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
														<th>英文名字</th>
														<th>专业-班级</th>
														<th>电话</th>
														<th>到期时间</th>
														<th>办理状态</th>
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
		action: '/master/student/visa/send_message'
	});
}
function exports(){
	$('#checked').attr({
		action: '/master/student/student/derive_part'
	});
}
function email(){
	$('#checked').attr({
		action: '/master/student/student/send_email'
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

	window.location.href="/master/student/visa?label_id=<?=$label_id?>&major="+majorid;
}
function squad_where(){
	var majorid =$('#major').val();
	 var squadid =$('#squadids').val();
	window.location.href="/master/student/visa?label_id=<?=$label_id?>&major="+majorid+'&squad='+squadid;
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
            "sAjaxSource":'<?=$zjjp?>/visa?major=<?=$majorid?>&squad=<?=$squadid?>&label_id=<?=$label_id?>',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 0,7 ] }],
            "aaSorting" : [[2,'desc']],
            "aoColumns" : [
            					{ "mData": "checkbox" },
                   			    { "mData": "id" },
                   			    { "mData": "enname" },
                   			    { "mData": "major_class" },
                   			    { "mData": "mobile" },
								{ "mData": "visatime" },
								{ "mData": "manage_state" },
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