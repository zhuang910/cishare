<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">成绩管理</a>
	</li>
	<li class="active">学生成绩</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script src="<?=RES?>master/js/jqGrid/jquery.jqGrid.min.js"></script>
<script src="<?=RES?>master/js/jqGrid/i18n/grid.locale-cn.js"></script>


<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		学生成绩
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<div class="col-sm offset-1">
				<div class="widget-box transparent">
					<div class="widget-box">
						<div class="widget-header">
							<h4 class="widget-title">按条件筛选</h4>
							<div class="widget-toolbar">
								<a data-action="collapse" href="#">
									<i class="ace-icon fa fa-chevron-up"></i>
								</a>
							</div>
							<div class="widget-toolbar no-border">
								<a href="javascript:;" onclick="pub_alert_html('<?=$zjjp?>stuscore/tochanel?s=1')" class="btn btn-xs bigger btn-danger">
									导入成绩
								</a>
							</div>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<form id="condition" class="form-horizontal" action="/master/score/stuscore/export" method="post">
									<div class="form-group">
										<label class="col-xs-12 col-sm-1 control-label no-padding-right" for="majorid">专业</label>
										<div class="col-xs-12 col-sm-5">
											<select  id="majorid" class="col-sm-8" name="majorid" aria-required="true" aria-invalid="false" onchange="major()">
												<option value="0">—请选择—</option>
												<?php foreach($mdata as $k=>$v):?>
													<option value="<?php echo $v->id?>" <?=!empty($info)&&$v->id==$info->majorid ? 'selected' :''?>><?php echo $v->name?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-12 col-sm-1 control-label no-padding-right" for="nowterm">学期</label>
										<div class="col-xs-12 col-sm-5">
											<select id="nowterm" class="col-sm-8" name="nowterm" aria-required="true" aria-invalid="false" onchange="term()">
												<option value="0">—请选择—</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-12 col-sm-1 control-label no-padding-right" for="squad">班级</label>
										<div class="col-xs-12 col-sm-5">
											<select id="squad" class="col-sm-8" name="squadid" aria-required="true" aria-invalid="false" onchange="c()">
												<option value="0">—请选择—</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-12 col-sm-1 control-label no-padding-right" for="scoretype">考试类型</label>
										<div class="col-xs-12 col-sm-5">
											<select id="scoretype" class="col-sm-8" name="scoretype" aria-required="true" aria-invalid="false" onchange="c()">
												<option value="0">—请选择—</option>
												<?php foreach($scoretype as $k=>$v):?>
													<option value="<?=$v['id']?>"><?=$v['typename']?>-<?=$v['englishtypename']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-12 col-sm-1 control-label no-padding-right" for="course">课程</label>
										<div class="col-xs-12 col-sm-5">
											<select id="course" class="col-sm-8" name="courseid" aria-required="true" aria-invalid="false" onchange="c()">
												<option value="0">—请选择—</option>
											</select>
										</div>
									</div>
									<hr>
									<div class="form-group">
										<label class="col-xs-12 col-sm-1 control-label no-padding-right" for="key">用户类型</label>
										<div class="col-xs-12 col-sm-5">
											<select id="key" class="col-sm-8" onchange="c()" name="key" aria-required="true" aria-invalid="false">
												<option value="0">—请选择—</option>
												<option value="name">姓名</option>
												<option value="email">邮箱</option>
												<option value="studentid">学号</option>
												<option value="passport">护照号</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-12 col-sm-1 control-label no-padding-right" for="key">关键词</label>
										<div class="col-xs-12 col-sm-5">
											<input id="value" class="col-sm-8" type="text" name="value" />
										</div>
									</div>
									<div class="form-actions center">
										<a class="btn btn-primary btn-sm" type="button" onclick="sure()">
											确认条件
										</a>
										<button class="btn btn-info btn-sm" type="submit">
											按条件导出
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div>
						<div class="col-sm-12">
							<div id="tables" class="widget-box transparent collapsed">
								<div class="widget-body">
									<div class="widget-main">
									<form id='scorearr'>
										<table class="table table-bordered table-striped" id="table">
											<thead class="thin-border-bottom" id="stype">
												<tr>

													<th>学号</th>
													<th>学生姓名</th>
													<th>成绩</th>
												</tr>
											</thead>
										</table>
										<a onclick="score_submit()" class="btn btn-info" type="button" >
											<i class="ace-icon fa fa-check bigger-110"></i>
											提交
										</a>
									</form>
									</div><!-- /.widget-main -->
								</div><!-- /.widget-body -->
							</div><!-- /.widget-box -->
						</div><!-- /.col -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<script src="<?=RES?>master/js/fuelux/fuelux.wizard.min.js"></script>
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<script type="text/javascript">

function c(){
	$('#tables').attr({
				class: 'widget-box transparent collapsed',
			});
	 $('#tbody').remove();
}
function major(){
$("#nowterm").empty();
	var mid=$('#majorid').val();
		$.ajax({
			url: '<?=$zjjp?>stuscore/get_nowterm/'+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#nowterm").empty();
				$("#nowterm").append("<option value='0'>—请选择—</option>"); 
				$("#squad").empty();
				$("#squad").append("<option value='0'>—请选择—</option>"); 
				$("#course").empty();
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm").append(opt); 
			  });
			 $("#course").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data.course, function(k, v) { 
			 	var opt = $("<option/>").text(v.cname).attr("value",v.id);
			 	  $("#course").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed',
			});
			  $('#tbody').remove();
			}else if(r.state==2){
				$("#squad").empty();
				$("#squad").append("<option value='0'>——请选择——</option>"); 
				 $("#course").empty();
				 $("#course").append("<option value='0'>—请选择—</option>"); 
				 $("#nowterm").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm").append(opt); 
			  });
				$('#tables').attr({
					class: 'widget-box transparent collapsed',
				});
				  $('#tbody').remove();
				 pub_alert_error(r.info);


			}

		})
		.fail(function() {
 
			
		})

}
function term(){
	 
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();

		$.ajax({
			url: '<?=$zjjp?>stuscore/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#squad").empty();
				$("#squad").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data, function(k, v) { 
				 	var opt = $("<option/>").text(v.name).attr("value",v.id);
				 	  $("#squad").append(opt); 
				  });
				 $('#tables').attr({
					class: 'widget-box transparent collapsed',
				});
				 $('#tbody').remove();
				 }else if(r.state==0){
				 	$("#squad").empty();
				 	$("#squad").append("<option value='0'>—请选择—</option>"); 
				 	 $('#tables').attr({
						class: 'widget-box transparent collapsed',
					});
				 	 $('#tbody').remove();
				 	  pub_alert_error(r.info);

				 }
		})
		
						
}
function sure(){

					var data=$('#condition').serialize();
					var term=$('#nowterm').val();
					var mid=$('#majorid').val();
					var sid=$('#squad').val();
					var cid=$('#course').val();
					var type=$('#scoretype').val();
					$.ajax({
						url: '<?=$zjjp?>stuscore/get_student',
						type: 'POST',
						dataType: 'json',
						data: data,
					})
					.done(function(r) {
						if(r.state==1){
							$('#tbody').remove();
							var str='<tbody id="tbody">';
							$.each(r.data.stu, function(k, v) {
								var strr='';

								strr='<tr><td width="200">'+v.studentid+'</td><td width="200">'+v.name+'</td><td><input name="'+v.id+'" type="text" data-score="true"></td></tr>';
								$.each(r.data.scoreinfo, function(kk, vv) {
									 if(v.id==vv.studentid&&vv.courseid==cid&&vv.term==term&&vv.squadid==sid&&vv.scoretype==type){
									 	strr='<tr><td width="200">'+v.studentid+'</td><td width="200">'+v.name+'</td><td>'+vv.score+'<a class="red" onclick="del('+vv.id+')" href="javascript:;"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></td></tr>';
									 }
								});
								str+=strr;
							}); 
							str+='</tbody>';
							$('#stype').after(str);
							$('#tables').attr({
								class: 'widget-box transparent',
							});

						}else if(r.state==0){
							pub_alert_error(r.info);
						}
								

					})
					.fail(function() {
						
					})

}


$(document).ready(function(){
	var input = "input[data-score='true']";
	$("body").on('keyup',input, function(event) {
		if(event.keyCode == 13){
			var dom_input = $(input);
			var input_length = dom_input.length;
			var that = dom_input.index(this);
			dom_input.eq(that+1).focus();
		}
	});
});
function score_submit(){
	var dataone=$('#condition').serialize();
	var datatwo=$('#scorearr').serialize();
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();
	var sid=$('#squad').val();
	var cid=$('#course').val();
	var type=$('#scoretype').val();

	$.ajax({
		url: '<?=$zjjp?>stuscore/save_score?'+dataone,
		type: 'POST',
		dataType: 'json',
		data: datatwo,
	})
	.done(function(r) {
		if(r.state==1){
			 $('#tbody').remove();
			var str='<tbody id="tbody">';
			$.each(r.data.stu, function(k, v) {
				var strr='';

				strr='<tr><td width="200">'+v.studentid+'</td><td width="200">'+v.name+'</td><td><input name="'+v.id+'" type="text" data-score="true"></td></tr>';
				$.each(r.data.scoreinfo, function(kk, vv) {
					 if(v.id==vv.studentid&&vv.courseid==cid&&vv.term==term&&vv.squadid==sid&&vv.scoretype==type){
					 	strr='<tr><td width="200">'+v.studentid+'</td><td width="200">'+v.name+'</td><td>'+vv.score+'<a class="red" onclick="del('+vv.id+')" href="javascript:;"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></td></tr>';
					 }
				});
				str+=strr;
			}); 
			str+='</tbody>';
			$('#stype').after(str);
			$('#tables').attr({
				class: 'widget-box transparent',
			});
			pub_alert_success();
		}
	})
	.fail(function() {
		console.log("error");
	})

	
}
function del(id){

	 var msg = msg ? msg : '确定要执行本次操作吗？';
    bootbox.confirm({
            message: msg,
            buttons: {
              confirm: {
               label: "确定",
               className: "btn-primary btn-sm"
              },
              cancel: {
               label: "取消",
               className: "btn-sm"
              }
            },
            
            callback: function(result) {

              if(result){

              	var term=$('#nowterm').val();
				var mid=$('#majorid').val();
				var sid=$('#squad').val();
				var cid=$('#course').val();
				var type=$('#scoretype').val();
				var key=$('#key').val();
				var value=$('#value').val();
                  $.ajax({
                    type:'POST',
                    url:'<?=$zjjp?>stuscore/del?id='+id+'&sid='+sid+'&key='+key+'&value='+value,
                    data:{},
                    dataType:'json',
                    success:function(r){
                       	if(r.state==1){
							 $('#tbody').remove();
						var str='<tbody id="tbody">';
						$.each(r.data.stu, function(k, v) {
							var strr='';

							strr='<tr><td width="200">'+v.studentid+'</td><td width="200">'+v.name+'</td><td><input name="'+v.id+'" type="text" data-score="true"></td></tr>';
							$.each(r.data.scoreinfo, function(kk, vv) {
								 if(v.id==vv.studentid&&vv.courseid==cid&&vv.term==term&&vv.squadid==sid&&vv.scoretype==type){
								 	strr='<tr><td width="200">'+v.studentid+'</td><td width="200">'+v.name+'</td><td>'+vv.score+'<a class="red" onclick="del('+vv.id+')" href="javascript:;"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></td></tr>';
								 }
							});
							str+=strr;
						}); 
						str+='</tbody>';
						$('#stype').after(str);
						$('#tables').attr({
							class: 'widget-box transparent',
						});
						pub_alert_success();
				

                      }else{
                        pub_alert_error(r.info);
                      }
                    }
                   });
              }
            }
            }
          );








// $( "#dialog-confirm" ).removeClass('hide').dialog({
// 	resizable: false,
// 	modal: true,
// 	title: "",
// 	title_html: true,
// 	buttons: [
// 		{
// 			html: "<i class='ace-icon fa fa-trash-o bigger-110'></i>&nbsp; Delete",
// 			"class" : "btn btn-danger btn-xs",
// 			click: function() {
// 				$( this ).dialog( "close" );
// 				var term=$('#nowterm').val();
// 				var mid=$('#majorid').val();
// 				var sid=$('#squad').val();
// 				var cid=$('#course').val();
// 				var type=$('#scoretype').val();
// 				$.ajax({
// 					url: '<?=$zjjp?>stuscore/del?id='+id+'&sid='+sid,
// 					type: 'POST',
// 					dataType: 'json',
// 					data:{},
// 				})
// 				.done(function(r) {
// 					if(r.state==1){
// 							 $('#tbody').remove();
// 						var str='<tbody id="tbody">';
// 						$.each(r.data.stu, function(k, v) {
// 							var strr='';

// 							strr='<tr><td width="200">'+v.studentid+'</td><td width="200">'+v.name+'</td><td><input name="'+v.id+'" type="text" data-score="true"></td></tr>';
// 							$.each(r.data.scoreinfo, function(kk, vv) {
// 								 if(v.id==vv.studentid&&vv.courseid==cid&&vv.term==term&&vv.squadid==sid&&vv.scoretype==type){
// 								 	strr='<tr><td width="200">'+v.studentid+'</td><td width="200">'+v.name+'</td><td>'+vv.score+'<a class="red" onclick="del('+vv.id+')" href="javascript:;"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></td></tr>';
// 								 }
// 							});
// 							str+=strr;
// 						}); 
// 						str+='</tbody>';
// 						$('#stype').after(str);
// 						$('#tables').attr({
// 							class: 'widget-box transparent',
// 						});
// 						pub_alert_success();
// 					}	
// 				})
// 				.fail(function() {
// 					console.log("error");
// 				})
// 				}
// 					}
// 					,
// 					{
// 						html: "<i class='ace-icon fa fa-times bigger-110'></i>&nbsp; Cancel",
// 						"class" : "btn btn-xs",
// 						click: function() {
// 							$( this ).dialog( "close" );
// 						}
// 					}
// 				]
// 			});

}

</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>