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
	<li class="active">考勤管理</li>
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
		学生考勤
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<div class="col-sm">
				<div class="widget-box transparent">
				<!--tabbable-->
				<div class="tabbable">
								<!-- #section:pages/faq -->
								<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
									<li id="major" class="active">
										<a data-toggle="tab" href="#faq-tab-1">
											条件检索
										</a>
									</li>

									<li id="couese">
										<a data-toggle="tab" href="#faq-tab-2">
											精确检索
										</a>
									</li>
								</ul>
				<!--tab-content no-border padding-24-->
				<div class="tab-content no-border padding-24">
				<!--1-->
				<div id="faq-tab-1" class="tab-pane fade in active">
					<div class="widget-box transparent">
						<div class="widget-box">
							<div class="widget-header">
								<h4 class="widget-title">按条件筛选</h4>
							</div>
							<div class="widget-body">
								<div class="widget-main">
									<form class="form-inline" id="condition" method="post" action="<?=$zjjp?>checking/checking/export">
										<label class="control-label" for="platform">专业:</label>
										<select  id="majorid" name="majorid" aria-required="true" aria-invalid="false" onchange="major()">
											<option value="0">—请选择—</option>
											<?php foreach($mdata as $k=>$v):?>
												<option value="<?php echo $v->id?>" <?=!empty($info)&&$v->id==$info->majorid ? 'selected' :''?>><?php echo $v->name?></option>
											<?php endforeach;?>
										</select>

										<label class="control-label" for="platform">学期:</label>
										<select id="nowterm" name="nowterm" aria-required="true" aria-invalid="false" onchange="term()">
											<option value="0">—请选择—</option>
										</select>
										<label class="control-label" for="platform">班级:</label>
										<select id="squad" name="squadid" aria-required="true" aria-invalid="false" onchange="c()">
											<option value="0">—请选择—</option>
										</select>
										<a class="btn btn-primary btn-sm" type="button" onclick="sure()">
											确认条件
										</a>
									</form>
								</div>
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
															<th>中文名</th>
															<th>英文名</th>
															<th>考勤</th>
															<th>出勤</th>
															<th></th>
														</tr>
													</thead>
												</table>
												
											</form>
											</div><!-- /.widget-main -->
										</div><!-- /.widget-body -->
									</div><!-- /.widget-box -->
								</div><!-- /.col -->
					</div>
				<!--1-->
				</div>
				<!--2-->
					<div id="faq-tab-2" class="tab-pane fade">
						<div class="widget-box transparent">
							<div class="widget-box">
								<div class="widget-header">
									<h4 class="widget-title">按条件筛选</h4>
								</div>
								<div class="widget-body">
									<div class="widget-main">
										<form class="form-inline" id="condition">
											<label class="control-label" for="platform">关键词:</label>
											<select id="key" name="key" aria-required="true" aria-invalid="false">
												<option value="0">—请选择—</option>
												<option value="name">姓名</option>
												<option value="email">邮箱</option>
												<option value="studentid">学号</option>
												<option value="passport">护照号</option>
											</select>

											<input id="value" type="text" name="value"/>
											<a class="btn btn-info btn-sm" type="button" onclick="student_quick()">
												确认条件
											</a>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div>
							<div class="col-sm-12">
								<div id="student_quick" class="widget-box transparent collapsed">
									<div class="widget-body">
										<div class="widget-main">
											<form id='scorearr'>
												<table class="table table-bordered table-striped" id="table">
													<thead class="thin-border-bottom" id="stypes">
													<tr>

														<th>学号</th>
														<th>学生姓名</th>
														<th>专业</th>
														<th>班级</th>
														<th>邮箱</th>
														<th>护照</th>
														<th>考勤</th>
														<th>出勤</th>
														<th></th>
													</tr>
													</thead>


												</table>

											</form>
										</div>
										<!-- /.widget-main -->
									</div>
									<!-- /.widget-body -->
								</div>
								<!-- /.widget-box -->
							</div>
							<!-- /.col -->
						</div>
				</div>
				<!--2-->
				</div>
				<!--tab-content no-border padding-24-->
				</div>

				<!--tabbable-->
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
<script src="<?=RES?>master/js/ace-elements.min.js"></script>
<script src="<?=RES?>master/js/ace.min.js"></script>
<!-- delete -->
<script type="text/javascript">


function c(){

	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#tbody').remove();
}
function major(){

	var mid=$('#majorid').val();
		$.ajax({
			url: '<?=$zjjp?>teacher_checking/get_nowterm/'+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#nowterm").empty();
			$("#squad").empty();
			$("#course").empty();
			$("#nowterm").append("<option value='0'>—请选择—</option>"); 
			$("#squad").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm").append(opt); 
			  });
			 $("#course").empty();
			 $("#course").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data.course, function(k, v) { 
			 	var opt = $("<option/>").text(v.cname).attr("value",v.id);
			 	  $("#course").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
			  $('#tbody').remove();
		})
		.fail(function() {
 
			
		})

}
function term(){
	 
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();
	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	$("#squad").empty();
	$("#squad").append("<option value='0'>—请选择—</option>"); 
		$.ajax({
			url: '<?=$zjjp?>teacher_checking/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			if(r.state==1){
					 $.each(r.data, function(k, v) { 
					 	var opt = $("<option/>").text(v.name).attr("value",v.id);
					 	  $("#squad").append(opt); 
					  });
					 $('#tables').attr({
						class: 'widget-box transparent collapsed'
					});
					 $('#tbody').remove();
				 }else{
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
	$.ajax({
		url: '<?=$zjjp?>teacher_checking/get_student',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			$('#tbody').remove();
			var str='<tbody id="tbody">';

			$.each(r.data.stu, function(k, v) {
				var shezhi='';
					shezhi='<a href="javascript:;" onclick="pub_alert_html(\'<?=$zjjp?>teacher_checking/set_checking?studentid='+v.id+'&majorid='+mid+'&squadid='+sid+'&s=1\')" role="button" class="blue" data-toggle="modal"><i class="ace-icon fa fa-cogs bigger-130"></i></a>';
				var xiangxi='';
				if(v.kaoqin!='全勤'){
					xiangxi='<a href="javascript:;" onclick="pub_alert_html(\'<?=$zjjp?>teacher_checking/student_more_checking?studentid='+v.id+'&majorid='+mid+'&squadid='+sid+'&s=1\')" href="javascript:;">详细</a>';
				}
				str+='<tr><td width="100">'+v.studentid+'</td><td width="150">'+v.name+'</td><td>'+v.enname+'</td><td id="kaoqin'+v.id+'" width="200">'+v.kaoqin+xiangxi+'</td><td id="chuqin'+v.id+'" width="150">'+v.chuqin+'</td><td>'+shezhi+'</td></tr>';
			
			}); 
			str+='</tbody>';
			$('#stype').after(str);
			$('#tables').attr({
				class: 'widget-box transparent'
			});

		}else if(r.state==2){
			 pub_alert_error(r.info);
		}
				

	})
	.fail(function() {
		
	})

}


function student_quick(){
	var key=$('#key').val();
	var value=$('#value').val()
	$.ajax({
		url: '<?=$zjjp?>teacher_checking/get_student_quick?key='+key+'&value='+value,
		type: 'POST',
		dataType: 'json',
		data:{}
	})
	.done(function(r) {
		if(r.state==1){
			$('#tbodys').remove();
			var str='<tbody id="tbodys">';

			$.each(r.data.stu, function(k, v) {
				var xiangxi='';
				xiangxi='<a href="javascript:;" onclick="pub_alert_html(\'<?=$zjjp?>teacher_checking/student_more_checking?studentid='+v.id+'&s=1\')" href="javascript:;">详细</a>';
				str+='<tr><td width="100">'+v.studentid+'</td><td width="100">'+v.name+'</td><td width="100">'+v.mname+'</td><td width="100">'+v.sname+'</td><td width="100">'+v.email+'</td><td width="100">'+v.passport+'</td><td id="kaoqin'+v.id+'" width="150">'+v.kaoqin+xiangxi+'</td><td id="chuqin'+v.id+'" width="150">'+v.chuqin+'</td><td><a onclick="pub_alert_html(\'<?=$zjjp?>teacher_checking/quick_checking_page?studentid='+v.id+'&majorid='+v.majorid+'&squadid='+v.squadid+'&s=1\')" role="button" class="blue" data-toggle="modal"><i class="ace-icon fa fa-cogs bigger-130"></i></a></td></tr>';
			
			}); 
			str+='</tbody>';
			$('#stypes').after(str);
			$('#student_quick').attr({
				class: 'widget-box transparent'
			});

		}else if(r.state==2){
			 pub_alert_error(r.info);
		}
				

	})
	.fail(function() {
		
	})

}
function key(){

	$('#student_quick').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#student_quick').remove();
}
function value(){

	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#tbody').remove();
}

</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>