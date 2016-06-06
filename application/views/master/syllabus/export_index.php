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
	<li>
		<a href="javascript:;">排课管理</a>
	</li>
	<li class="active">查看</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>

 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
 <script src="<?=RES?>master/js/jquery.validate.min.js"></script>
 <link rel="stylesheet" href="<?=RES?>master/js/lodop/PrintSample10.css" />

<script src="<?=RES?>master/js/lodop/LodopFuncs.js"></script>   

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		排课管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<!-- PAGE CONTENT BEGINS -->
							<div class="tabbable">
								<!-- #section:pages/faq -->
								<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
									<li class="active">
										<a data-toggle="tab" href="#faq-tab-1">
											
											按班级查看
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-2">
										
											按老师查看
										</a>
									</li>
								</ul>

								<!-- /section:pages/faq -->
								<div class="tab-content no-border padding-24">
									<div id="faq-tab-1" class="tab-pane fade in active">
										<h4 class="blue">
											<i class="ace-icon fa fa-user bigger-110"></i>
											班级
										</h4>

										<div class="space-8"></div>

										<div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">

											<div class="panel panel-default">
												<div class="col-sm offset-1">
													<div class="widget-box transparent">

														<div class="widget-box transparent">
															<div class="widget-box">
																<div class="widget-header">
																	<h4 class="widget-title">按条件筛选</h4>
																</div>
																<div class="widget-body">
																	<div class="widget-main">
																		<form class="form-inline" id="condition" method="post">
																			<label class="control-label" for="platform">专业:</label>
																			<select id="majorid" class="input-medium valid" name="majorid" aria-required="true" aria-invalid="false" onchange="major()">
																				<option value="0">--请选择 --</option>>
																				<?php foreach($mdata as $item){ ?>
																				<optgroup label="<?=$item['degree_title']?>">
																				<?php foreach($item['degree_major'] as $item_info){ ?>
																					<option value="<?=$item_info->id?>"><?=$item_info->name?></option>
																				<?php } ?>
																				</optgroup>
																				<?php } ?>
																			</select>

																			<label class="control-label" for="platform">当前学期:</label>
																			<select id="nowterm" class="input-medium valid" name="nowterm" aria-required="true" aria-invalid="false" onchange="term()">
																				<option value="0">——请选择——</option>
																			</select>
																			<label class="control-label" for="platform">班级:</label>
																			<select id="squad" class="input-medium valid" name="squadid" aria-required="true" aria-invalid="false" onchange="s()">
																				<option value="0">——请选择——</option>
																			</select>
																			<label class="control-label" for="platform">查看学期:</label>
																			<select id="seeterm" class="input-medium valid" name="seeterm" aria-required="true" aria-invalid="false" onchange="s()">
																				<option value="0">——请选择——</option>
																			</select>
																			<button class="btn btn-info btn-sm" type="button" onclick="paike()">
																				确认条件
																			</button>
																			<a class="btn btn-info btn-sm" type="button" href="javascript:OutToFileOneSheet();">
																				按条件导出
																			</a>
																		</form>
																	</div>
																</div>
															</div>
														</div>

														<div id="dayin">
															<div class="col-sm-12" id="pp">
																		<div id="tables" class="widget-box transparent collapsed" >

																			<div class="widget-body">
																				<div class="widget-main" dayin="no" id="excel_table">
																					<table class="table table-bordered table-striped gengruifeng" id="table">
																							<tr>
																								<td colspan="8"  style="text-align:center;"><div style="font-size:18px;font-weight:bold;"><span id="majorname"></span>-<span id="termname"></span>-<span id="squadname"></span>课程表</div></td>
																							</tr>
																							<tr>
																								<td>课时 / 星期</td>
																								<td>星期一</td>
																								<td>星期二</td>
																								<td>星期三</td>
																								<td>星期四</td>
																								<td>星期五</td>
																								<td>星期六</td>
																								<td>星期日</td>
																							</tr>
																						
																						<tbody>
																								<?php foreach($hour['hour'] as $k=>$v):?>
																									<tr> 
																										<td><?php 
																										echo $v."节课 <br />";
																										echo $time['hour'][$v];	
																										?></td>
																									<?php for($i=1;$i<=7;$i++):?>
																										<?php
																											echo '<td id="td'.$i.$v.'"><a role="button" class="blue" data-toggle="modal">---</a></td>';
																										?>
																									<?php endfor;?>
																									
																									</tr>
																							<?php endforeach;?>
																						</tbody>
																					</table>
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

									<div id="faq-tab-2" class="tab-pane fade">
										<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											老师
										</h4>

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
												<div class="col-sm offset-1">
													<div class="widget-box transparent">

														<div class="widget-box transparent">
															<div class="widget-box">
																<div class="widget-header">
																	<h4 class="widget-title">按条件筛选</h4>
																</div>
																<div class="widget-body">
																	<div class="widget-main">
																		<form class="form-inline" id="tform" method="post">
																			<label class="control-label" for="platform">老师:</label>
																			<select id="teacherid" class="input-medium valid" name="teacherid" aria-required="true" aria-invalid="false" onchange="t()">
																				<option value="0">——请选择——</option>
																				<?php foreach($tdata as $k=>$v):?>
																					<option value="<?=$v['id']?>" ><?=$v['name']?></option>
																				<?php endforeach;?>
																			</select>
																			<label class="control-label" for="platform">学期:</label>
																			<select id="sterm" class="input-medium valid" name="sterm" aria-required="true" aria-invalid="false">
																				<option value="0">——请选择——</option>
																			</select>
																			<button class="btn btn-info btn-sm" type="button" onclick="teacher()">
																				确认条件
																			</button>
																			<a class="btn btn-info btn-sm" type="button" href="javascript:OutToFileOneSheets();">
																				按条件导出
																			</a>
																		</form>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-sm-12" id="elcel_two">
																		<div id="t2" class="widget-box transparent collapsed">
																			

																			<div class="widget-body">
																				<div class="widget-main" id="dayin2">
																					<table class="table table-bordered table-striped" id="table">
																							<tr>
																								<th>课时 / 星期</th>
																								<th>星期一</th>
																								<th>星期二</th>
																								<th>星期三</th>
																								<th>星期四</th>
																								<th>星期五</th>
																								<th>星期六</th>
																								<th>星期日</th>
																							</tr>
																						<tbody>
																								<?php foreach($hour['hour'] as $k=>$v):?>
																									<tr> 
																										<td><?php 
																										echo $v."节课 <br />";
																										echo $time['hour'][$v];	
																										?></td>
																									<?php for($i=1;$i<=7;$i++):?>
																										<?php
																											echo '<td id="tdd'.$i.$v.'"><a role="button" class="blue" data-toggle="modal">---</a></td>';
																										?>
																									<?php endfor;?>
																									
																									</tr>
																							<?php endforeach;?>
																						</tbody>
																					</table>
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
<script type="text/javascript">
function OutToFileOneSheet(){ 
    LODOP=getLodop(); 
    var html=$('#dayin').html(); 
    LODOP.PRINT_INIT(""); 
    LODOP.ADD_PRINT_TABLE(100,20,500,60,html); 
    LODOP.SET_SAVE_MODE("FILE_PROMPT",1); 
    LODOP.SET_SAVE_MODE("RETURN_FILE_NAME",1); 
    if (LODOP.SAVE_TO_FILE('timetable.xls')) pub_alert_success("导出成功！");     
  }; 
  function OutToFileOneSheets(){ 
    LODOP=getLodop(); 
    var html=$('#elcel_two').html(); 
    LODOP.PRINT_INIT(""); 
    LODOP.ADD_PRINT_TABLE(100,20,500,60,html); 
    LODOP.SET_SAVE_MODE("FILE_PROMPT",1); 
    LODOP.SET_SAVE_MODE("RETURN_FILE_NAME",1); 
    if (LODOP.SAVE_TO_FILE('timetable.xls')) pub_alert_success("导出成功！");     
  }; 
function s(){
	//$('#table').find('a').eq(1).remove();
	$('#info').html('')
	$('#numdays').html('')
	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	$('#oo').remove();
}

function major(){
	var mid=$('#majorid').val();
		$.ajax({
			url: '<?=$zjjp?>arrangement/get_nowterm/'+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#nowterm").empty();
			$("#squad").empty();
			$("#course").empty();
			$("#seeterm").empty();
			$("#nowterm").append("<option value='0'>——请选择——</option>"); 
			$("#squad").append("<option value='0'>——请选择——</option>"); 
			$("#seeterm").append("<option value='0'>——请选择——</option>"); 
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm").append(opt); 
			  });
			  $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#seeterm").append(opt); 
			  });
			 $("#course").empty();
			 $("#course").append("<option value='0'>——请选择——</option>"); 
			 $.each(r.data.course, function(k, v) { 
			 	var opt = $("<option/>").text(v.cname).attr("value",v.id);
			 	  $("#course").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
			 $('#oo').remove();
		})
		.fail(function() {

			
		})
						
}
function term(){
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();

		$.ajax({
			url: '<?=$zjjp?>arrangement/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#squad").empty();
			$("#squad").append("<option value='0'>——请选择——</option>"); 
			 $.each(r.data, function(k, v) { 
			 	var opt = $("<option/>").text(v.name).attr("value",v.id);
			 	  $("#squad").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
			 $('#oo').remove();
		})
		
						
}
function paike(){
	var data=$('#condition').serialize();
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();
	var sid=$('#squad').val();
	
	$.ajax({
		url: '<?=$zjjp?>export/get_condition',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			$('#majorname').text(r.data.majorname);
			$('#termname').text("第"+r.data.term+"学期");
			$('#squadname').text(r.data.squadname);
				$.each(r.data.hour, function(k, v) {
					for(var i=1;i<=7;i++){
						 $('#td'+i+v).find('a').text('---');
					}
				});

				if(r.data.scheduling){
					$.each(r.data.scheduling,function(kk,vv){
						var info='';
						info=vv.tname+'-';
						info+=vv.cname+'-';
						info+=vv.rname;
						$('#td'+vv.week+vv.knob).find('a').text(info);

					})
				}
				$('#tables').attr({
					class: 'widget-box transparent'
				});
			}else if(r.state==0){
				pub_alert_error(r.info);
			}
			if(r.state==2){
				$('#oo').remove();
				var str='<div id="oo">';
				$.each(r.data.scheduling, function(kk, vv) {
					//alert(r.data.sinfo[kk].name);
					str+='<div class="alert alert-info">'+r.data.sinfo[kk].name+'</div>';
					 str+='<div class="col-sm-12"><div id="table2" class="widget-box transparent"><div class="widget-body"><div class="widget-main"><table class="table table-bordered table-striped"><thead class="thin-border-bottom"><tr><th>课时 / 星期</th><th>星期一</th><th>星期二</th><th>星期三</th><th>星期四</th><th>星期五</th><th>星期六</th><th>星期日</th></tr></thead><tbody>';
					$.each(r.data.hour, function(k, v) {
						str+='<tr>';
						str+='<td>'+(v*2-1)+','+(v*2)+'节课</td>';
						for(var i=1;i<=7;i++){
								var strr;
								$.each(vv, function(kkk, vvv) {
									
									if(vvv.week==i&&vvv.knob==v){
										strr=vvv.tname+'-'+vvv.cname+'-'+vvv.rname;
										return false;
									}else{
										strr='---';
									}
								});
								str+='<td>'+strr+'</td>';
							}
						
						str+='</tr>';
					});
					str+='</tbody></table></div></div></div></div>';
					
				});
				str+='</div>';
				$('#pp').after(str);
			}
	})
	.fail(function() {
		
	})

}
function teacher(){
$('#oo').remove();
	var tid=$('#teacherid').val();
	var term=$('#sterm').val();
	$.ajax({
		url: '<?=$zjjp?>export/select_teacher?tid='+tid+'&term='+term,
		type: 'GET',
		dataType: 'json',
		data: {}
	})
	.done(function(r) {
		if(r.state==1){
				$.each(r.data.hour, function(k, v) {
					for(var i=1;i<=7;i++){
						 $('#tdd'+v+i).find('a').text('---');
					}
				});

				if(r.data.scheduling){
					$.each(r.data.scheduling,function(kk,vv){
						var info='';
						info=vv.mname+'-';
						info+=vv.sname+'-';
						info+=vv.cname+'-';
						info+=vv.rname;
						$('#tdd'+vv.week+vv.knob).find('a').text(info);

					})
				}

		 $('#t2').attr({
				class: 'widget-box transparent'
			});
		}else{
			pub_alert_error(r.info);
		}
	
	})
	.fail(function() {
		console.log("error");
	})
	
}
function t(){
	var tid=$('#teacherid').val();
	$.ajax({
			url: '<?=$zjjp?>export/get_s_nowterm?tid='+tid,
			type: 'GET',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#sterm").empty();
			$("#sterm").append("<option value='0'>——请选择——</option>"); 
			 $.each(r.data, function(k, v) { 
			 	 var opt = $("<option/>").text('第'+v.nowterm+'学期').attr("value",v.nowterm);
			 	  $("#sterm").append(opt); 
			  });
			
			 $('#t2').attr({
				class: 'widget-box transparent collapsed'
			});
			 $('#oo').remove();
		})
		.fail(function() {

			
		})
}
function dayin(){
  // var dayin=$('#dayin').attr('dayin');
 	// if(dayin=='no'){
 		 document.body.innerHTML=document.getElementById('dayin').innerHTML;
  		window.print();
 	// }else{
 	// 	pub_alert_error('还没有打印的内容');
 	// }
 
}
function dayin2(){

  document.body.innerHTML=document.getElementById('dayin2').innerHTML;
  window.print();
}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>