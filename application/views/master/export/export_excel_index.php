<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Created by CUCAS TEAM.
 * User: JunJie
 * E-Mail:zhangjunjie@cucas.cn
 * Date: 15-1-14
 * Time: 下午12:23
 */

?>
<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="/master">后台</a>
	</li>
	<li>
		<a href="javascript:;">报表管理</a>
	</li>
	<li class="active">报表导出</li>
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
 <link rel="stylesheet" href="<?=RES?>master/js/lodop/PrintSample10.css" />

<script src="<?=RES?>master/js/lodop/LodopFuncs.js"></script>   
	<!-- /section:settings.box -->
	<div class="page-header">
		<h1>
			报表管理
		</h1>
	</div><!-- /.page-header -->



	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="table-header">
				可选类型
				
			<button style="float:right;" onclick="t_z(1)" class="btn btn-primary btn-sm btn-default btn-sm" title="按学费比例进行分配" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					按学费比例进行分配
			</button>
			<button style="float:right;" onclick="t_z(2)" class="btn btn-primary btn-sm btn-default btn-sm" title="按申请费比例进行分配" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					按申请费比例进行分配
			</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs" id="myTab">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							财务 &nbsp;
							<i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
						</a>
						<ul class="dropdown-menu dropdown-info">
							<li><a data-toggle="tab" href="#insurance_info">保险费</a></li>
							<li><a data-toggle="tab" href="#budget">学生收费信息登记表</a></li>
							<li><a data-toggle="tab" href="#credentials">汇款明细</a></li>
							<li><a data-toggle="tab" href="#student_budget">按学生导出收费信息</a></li>
							<li><a data-toggle="tab" href="#student_refresh">按学生刷卡记录导出收费信息</a></li>
							<li><a data-toggle="tab" href="#fees_export">费用收取表格</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							教学 &nbsp;
							<i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
						</a>
						<ul class="dropdown-menu dropdown-info">
							<li><a data-toggle="tab" href="#course_elective">选课名单</a></li>
							<li><a data-toggle="tab" href="#books_fee">教材导出清单</a></li>
							<li><a data-toggle="tab" href="#student">成绩统计总表-简单</a></li>
							<li><a data-toggle="tab" href="#checking">考勤汇总-按月</a></li>
							<li><a data-toggle="tab" href="#squad">学生评教汇总表-班级</a></li>
							<li><a data-toggle="tab" href="#evaluat_student">学生评教汇总表-教师</a></li>
							<li><a data-toggle="tab" onclick="" href="#score">语言生成绩统计详细</a></li>
							<li><a data-toggle="tab" onclick="" href="#teacher_score">成绩统计教师输入</a></li>
							<li><a data-toggle="tab" href="#student_online">在学导出</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							住宿 &nbsp;
							<i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
						</a>
						<ul class="dropdown-menu dropdown-info">
							<li><a data-toggle="tab" href="#accommodation_info">住宿费退费清单</a></li>
							<!-- <li><a data-toggle="tab" href="#room_electric_record">电费核算</a></li> -->
						</ul>
					</li>
				</ul>
				<div class="tab-content">
					<?php if(!empty($field_lists)) { foreach($field_lists as $key => $fields) {?>
					<div id="<?=$fields['field']?>" class="tab-pane fade<?=$key == 0 ? 'in active' : ''?>">
						<h4 class="widget-title smaller">
							<i class="ace-icon fa fa-check-square-o bigger-110"></i>
							<?=$fields['table']?>
						</h4>
						<form id="form_<?=$fields['field']?>" method="post">
						<?php if($fields['field'] === 'credentials' || $fields['field'] === 'accommodation_info'):?>
								开始日期：<input type="text" data-date-format="yyyy-mm-dd" id="update_opentime_<?php echo $fields['fix']; ?>" class="date-picker" name="update_opentime" value="">
								截止日期：<input type="text" data-date-format="yyyy-mm-dd" id="update_endtime_<?php echo $fields['fix']; ?>" class="date-picker" name="update_endtime" value="">
						<?php endif;?>
						<?php if($fields['field']==='student_budget' || $fields['field'] == 'student_refresh'){ ?>
							<select name="s_field" id="s_field" style="width:90px;">
								<option value="0">请选择</option>
								<option value="chname">汉语名</option>
								<option value="enname">英语名</option>
								<option value="email">邮箱</option>
								<option value="passport">护照号</option>
								<option value="studentid">学号</option>
							</select>
							<input type="text" value="" name="s_name" id="s_name" style="height:30px;">
							<select id="pay_type" class="col-sm-8" name="pay_type" aria-required="true" aria-invalid="false" style="width:180px;">
								<option value="0">—支付方式—</option>
								<option value="1">paypal</option>
								<option value="2">payease</option>
								<option value="3">汇款</option>
								<option value="4">现金</option>
								<option value="5">刷卡</option>
							</select>
							开始日期：<input type="text" data-date-format="yyyy-mm-dd" id="s_opentime" class="date-picker" name="s_opentime" value="">
							截止日期：<input type="text" data-date-format="yyyy-mm-dd" id="s_endtime" class="date-picker" name="s_endtime" value="">
							<?php }?>
							<?php if($fields['field'] == 'fees_export'){?>
							<select id="pay_type" class="col-sm-8" name="pay_type" aria-required="true" aria-invalid="false" style="width:180px;">
								<option value="0">—支付方式—</option>
								<option value="1">paypal</option>
								<option value="2">payease</option>
								<option value="3">汇款</option>
								<option value="4">现金</option>
								<option value="5">刷卡</option>
							</select>
							开始日期：<input type="text" data-date-format="yyyy-mm-dd" id="s_opentime" class="date-picker" name="s_opentime" value="">
							截止日期：<input type="text" data-date-format="yyyy-mm-dd" id="s_endtime" class="date-picker" name="s_endtime" value="">
							<select id="studenttype" class="col-sm-8" name="studenttype" aria-required="true" aria-invalid="false" style="width:200px;">
								<option value="0">学生类型</option>
								<option value="1">本科学历生--汉语授课</option>
								<option value="2">本科学历生--英文授课</option>
								<option value="3">研究生学历生--英语授课</option>
								<option value="4">研究生学历生--中文授课</option>
								<option value="5">长期语言进修生--汉语授课</option>
								<option value="6">短期培训团--汉语、英语授课</option>
							</select>
							
							<?php }?>
						<?php
							$un_lock = array('student','checking','score','teacher_score','budget');
							if(in_array($fields['field'],$un_lock)){
							?>

							<hr>
							
							<?php if($fields['field']==='budget'){?>
							<select id="degreeid" onchange="change_degree()" id="majorid_<?php echo $fields['fix']; ?>" class="col-sm-8" name="majorid" aria-required="true" aria-invalid="false" onchange="major_<?php echo $fields['fix']; ?>(this)" style="width:150px;">
								<option value="0">—请选择学历—</option>
								<?php foreach($degree_info as $k=>$v):?>
									<option value="<?php echo $v->id?>"><?php echo $v->title?></option>
								<?php endforeach;?>
							</select>
							<?php }?>
							<select  id="majorid_<?php echo $fields['fix']; ?>" class="col-sm-8" name="majorid" aria-required="true" aria-invalid="false" onchange="major_<?php echo $fields['fix']; ?>(this)" style="width:150px;">
								<option value="0">—请选择专业—</option>
								<?php if($fields['field']!='budget'){?>
									<?php foreach($mdata as $k=>$v):?>
										<option value="<?php echo $v->id?>" <?=!empty($info)&&$v->id==$info->majorid ? 'selected' :''?>><?php echo $v->name?></option>
									<?php endforeach;?>
								<?php }?>
							</select>
							<select id="nowterm_<?php echo $fields['fix']; ?>" class="col-sm-8" name="nowterm" aria-required="true" aria-invalid="false" onchange="term_<?php echo $fields['fix']; ?>()" style="width:150px;">
								<option value="0">—请选择学期—</option>
							</select>
							<select id="squad_<?php echo $fields['fix']; ?>" onchange="change_course()" class="col-sm-8" name="squadid" aria-required="true" aria-invalid="false" style="width:150px;">
								<option value="0">—请选择班级—</option>
							</select>
							<?php if($fields['field'] === 'student'):?>
							<select id="scoretype" class="col-sm-8" name="scoretype" aria-required="true" aria-invalid="false" style="width:180px;">
								<option value="0">—请选择考试类型—</option>
								<?php foreach($scoretype as $k=>$v):?>
									<option value="<?=$v['id']?>"><?=$v['name']?>-<?=$v['enname']?></option>
								<?php endforeach;?>
							</select>
							<?php endif;?>
							<?php if($fields['field'] === 'teacher_score'):?>
							<select id="courseid" class="col-sm-8" name="courseid" aria-required="true" aria-invalid="false" style="width:180px;">
								<option value="0">—请选择课程名称—</option>
							</select>
							<?php endif;?>
							<?php if($fields['field'] === 'budget'):?>
							<select id="term" class="col-sm-8" name="term" aria-required="true" aria-invalid="false" style="width:180px;">
								<option value="0">—查看学期—</option>
							</select>
							<select id="pay_type" class="col-sm-8" name="pay_type" aria-required="true" aria-invalid="false" style="width:180px;">
								<option value="0">—支付方式—</option>
								<option value="1">paypal</option>
								<option value="2">payease</option>
								<option value="3">汇款</option>
								<option value="4">现金</option>
								<option value="5">刷卡</option>
							</select>
							<input type="hidden" name="budget_type" value="1">
							<!-- 交费：<input type="radio" name="budget_type" value="1" checked="checked">&nbsp;&nbsp;
							退费：<input type="radio" name="budget_type" value="2"> -->
							<?php endif;?>
							<?php if($fields['field'] === 'checking'):?>
								开始日期：<input type="text" data-date-format="yyyy-mm" id="opentime" class="date-picker" name="opentime" value="">
								截止日期：<input type="text" data-date-format="yyyy-mm" id="endtime" class="date-picker" name="endtime" value="">
							<?php endif;?>
							<hr>
						<?php } ?>
						<?php 
							$sq_lock = array('squad','evaluat_student');
							$sc_lock = array('evaluat_student');
							if(in_array($fields['field'],$sq_lock)):
						?>
							<hr>
							<select  id="majorid_<?php echo $fields['fix']; ?>" class="col-sm-8" name="majorid" aria-required="true" aria-invalid="false" onchange="major_<?php echo $fields['fix']; ?>(this)" style="width:150px;">
								<option value="0">—请选择专业—</option>
								<?php foreach($mdata as $k=>$v):?>
									<option value="<?php echo $v->id?>" <?=!empty($info)&&$v->id==$info->majorid ? 'selected' :''?>><?php echo $v->name?></option>
								<?php endforeach;?>
							</select>
							<?php if($fields['field'] == 'squad'):?>
							<select id="nowterm_<?php echo $fields['fix']; ?>" class="col-sm-8" name="nowterm" aria-required="true" aria-invalid="false" onchange="term_<?php echo $fields['fix']; ?>()" style="width:150px;">
								<option value="0">—请选择学期—</option>
							</select>
							<?php endif;?>
							<select name="year" id="year_<?php echo $fields['fix'];?>" style="width:80px;">
								<option value="0">清选择</option>
								<option value="2013">2013</option>
								<option value="2014">2014</option>
								<option value="2015">2015</option>
							</select>
							<hr>
							<?php endif;?>
						<table class="table table-bordered table-striped">
							<tbody>
							<?php
								$count_field = count($fields['config']);
							?>
							<?php	
							$tr = '';
							$td = '';
							$loop = 0;
							if(!empty($fields['config']) && in_array($fields['field'],$sc_lock)){
								foreach($fields['config'] as $k => $item){
									$td .= '<td>
									<label>
										<span class="lbl">'.$item['name'].'</span>
									</label>
								</td>';
									if($k%3==2){
										$tr .= "<tr>{$td}</tr>";
										$td = '';
										$loop ++;
									}
								}

								echo $tr;

								if($count_field-$loop*3>0){
									echo $count_field-$loop*3 == 1 ? "<tr>{$td}<td class='td_null'></td><td class='td_null'></td></tr>" : "<tr>{$td}<td class='td_null'></td></tr>";
								}
							}else{
								if(!empty($fields['config'])){
									foreach($fields['config'] as $k => $item){
									$td .= '<td>
									<label>
										<input name="set_field[]" value="'.$item['field'].'" type="checkbox" checked class="ace" />
										<span class="lbl">'.$item['name'].'</span>
									</label>
								</td>';
									if($k%3==2){
										$tr .= "<tr>{$td}</tr>";
										$td = '';
										$loop ++;
									}
								}
								}

								echo $tr;

								if($count_field-$loop*3>0){
									echo $count_field-$loop*3 == 1 ? "<tr>{$td}<td class='td_null'></td><td class='td_null'></td></tr>" : "<tr>{$td}<td class='td_null'></td></tr>";
								}
							}
							?>
							</tbody>
						</table>
						</form>
					</div>
					<?php }} ?>
					<hr>
					<button class="btn btn-success btn-sm tooltip-success" id="do_submit" title="确认导出">确认导出</button>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>
	<div id="dayin" style="display:none">
		
	</div>
	<!--[if lte IE 8]>
	<script src="<?=RES?>master/js/excanvas.min.js"></script>
	<![endif]-->
	<!-- ace scripts -->
	<script src="<?=RES?>master/js/ace-extra.min.js"></script>
	<script src="<?=RES?>master/js/ace-elements.min.js"></script>
	<script src="<?=RES?>master/js/ace.min.js"></script>
		<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
	<script>
	function alert_close(){
    $("#pub_edit_bootbox").remove();$("div.modal-backdrop").remove();$("body").removeClass('modal-open');
}

/****************************************************************************/
function change_degree(){
	var degreeid=$('#degreeid').val();
	if(degreeid!=undefined){
		$.ajax({
			url: '/master/export/export_excel/get_major?degreeid='+degreeid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#majorid_bug").empty();
				$("#majorid_bug").append("<option value='0'>—请选择专业—</option>");
			 $.each(r.data, function(k, v) { 
			 	var opt = $("<option/>").text(v.name).attr("value",v.id);
			 	  $("#majorid_bug").append(opt); 
			  });
			 
			}
			if(r.state==2){
				pub_alert_error('该学历下还没有专业');
			}

		})
		.fail(function() {
		})
	}
}
function change_course(){
	var squadid=$('#squad_tea').val();
	if(degreeid!=undefined){
		$.ajax({
			url: '/master/export/export_excel/get_teacher_score_course?squadid='+squadid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#courseid").empty();
				$("#courseid").append("<option value='0'>—请选择课程—</option>");
			 $.each(r.data, function(k, v) {
					var shh = $("<option/>").text(v.name).attr("value",v.id);
			 	  	$("#courseid").append(shh); 
			  });
			 
			}
			if(r.state==2){
				pub_alert_error('该班级下没有课程');
				$("#courseid").empty();
				$("#courseid").append("<option value='0'>—请选择课程—</option>");
			}

		})
		.fail(function() {
		})
	}
}
function major_score(that){
$("#nowterm").empty();
	var mid=$('#majorid_score').val();
	var id = $(".tab-content div.active").attr('id');
		$.ajax({
			url: '/master/export/export_excel/get_nowterm/'+mid+'/'+id,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#nowterm_score").empty();
				$("#nowterm_score").append("<option value='0'>—请选择—</option>"); 
				$("#squad_score").empty();
				$("#squad_score").append("<option value='0'>—请选择—</option>"); 
			$.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_score").append(opt); 
			});
			
			$('#tbody').remove();
			if(r.data.ta_info === 'student'){
				create_corese_td(r.data,that);
			}

			}else if(r.state==2){
				$("#squad_score").empty();
				$("#squad_score").append("<option value='0'>——请选择——</option>"); 
				 $("#nowterm_score").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_score").append(opt); 
			  });
				 pub_alert_error(r.info);


			}

		})
		.fail(function() {
		})

}

function create_corese_td(data,that){
	if(data){
		var parent_div = $(that).parents().eq(1);
		var get_corese_field = parent_div.find("tr.get_corese_field");
		get_corese_field.remove();

		// 检测有几个空的 td可以使用
		var td_null = parent_div.find("td.td_null");
		var td_null_length = td_null.length;
		

		td_null.html('');

		// 检测返回课程数
		var r_course = data.course;
		var r_corese_length = r_course.length;

		// 补充空td
		var differ = r_corese_length - td_null_length;

		var table = parent_div.find("table.table tbody");

		if(differ > 0){
			td_null.each(function(i,v){
				var $item = r_course[i];
				$(v).html(
					'<label>\
						<input name="set[]" value="'+$item.id+'" type="checkbox" checked class="ace" />\
						<span class="lbl">'+$item.cname+'</span>\
					</label>'
				);
			});

			var $tr = '';
			var $td = '';
			var $loop = 0;
			var $j = 0;

			for (var j = td_null_length; j < r_corese_length; j++) {
				var $item = r_course[j];
					$td += '<td>\
					<label>\
						<input name="set[]" value="'+$item.id+'" type="checkbox" checked class="ace" />\
						<span class="lbl">'+$item.cname+'</span>\
					</label>\
				</td>';
					if($j%3==2){
						$tr = "<tr class='get_corese_field'>"+$td+"</tr>";
						$td = '';
						$loop ++;
						table.append($tr);
					}
				$j ++;
			}
			
			if(differ-$loop*3>0){
				table.append(differ-$loop*3 == 1 ? "<tr class='get_corese_field'>"+$td+"<td class='td_null'></td><td class='td_null'></td></tr>" : "<tr class='get_corese_field'>"+$td+"<td class='td_null'></td></tr>");
			}
			
		}else{


			td_null.each(function(i,v){
				var $item = r_course[i];
				$(v).html(
					'<label>\
						<input name="set[]" value="'+$item.id+'" type="checkbox" checked class="ace" />\
						<span class="lbl">'+$item.cname+'</span>\
					</label>'
				);
			});
			
		}
	}
}

function term_score(){
	 
	var term=$('#nowterm_score').val();
	var mid=$('#majorid_score').val();

		$.ajax({
			url: '/master/export/export_excel/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#squad_score").empty();
				$("#squad_score").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data, function(k, v) { 
				 	var opt = $("<option/>").text(v.name).attr("value",v.id);
				 	  $("#squad_score").append(opt); 
				  });
			
				
				 }else if(r.state==0){
				 	$("#squad_score").empty();
				 	$("#squad_score").append("<option value='0'>—请选择—</option>"); 
				 	
				 	  pub_alert_error(r.info);

				 }
		})
		
						
}


/****************************************************************************/


/****************************************************************************/

function major_check(that){
$("#nowterm").empty();
	var mid=$('#majorid_check').val();
	var id = $(".tab-content div.active").attr('id');
		$.ajax({
			url: '/master/export/export_excel/get_nowterm/'+mid+'/'+id,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#nowterm_check").empty();
				$("#nowterm_check").append("<option value='0'>—请选择—</option>"); 
				$("#squad_check").empty();
				$("#squad_check").append("<option value='0'>—请选择—</option>"); 
			$.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_check").append(opt); 
			});
		

			}else if(r.state==2){
				$("#squad_check").empty();
				$("#squad_check").append("<option value='0'>——请选择——</option>"); 
				 $("#nowterm_check").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_check").append(opt); 
			  });
				
				 pub_alert_error(r.info);


			}

		})
		.fail(function() {
 
			
		})

}


function term_check(){
	 
	var term=$('#nowterm_check').val();
	var mid=$('#majorid_check').val();

		$.ajax({
			url: '/master/export/export_excel/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#squad_check").empty();
				$("#squad_check").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data, function(k, v) { 
				 	var opt = $("<option/>").text(v.name).attr("value",v.id);
				 	  $("#squad_check").append(opt); 
				  });
				
				 }else if(r.state==0){
				 	$("#squad_check").empty();
				 	$("#squad_check").append("<option value='0'>—请选择—</option>"); 
				 
				 	  pub_alert_error(r.info);

				 }
		})
		
						
}
/****************************************************************************/

function major_squ(that){
$("#nowterm").empty();
	var mid=$('#majorid_squ').val();
	var id = $(".tab-content div.active").attr('id');
		$.ajax({
			url: '/master/export/export_excel/get_nowterm/'+mid+'/'+id,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#nowterm_squ").empty();
				$("#nowterm_squ").append("<option value='0'>—请选择—</option>"); 
				$("#squad_squ").empty();
				$("#squad_squ").append("<option value='0'>—请选择—</option>"); 
			$.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_squ").append(opt); 
			});
		

			}else if(r.state==2){
				$("#squad_squ").empty();
				$("#squad_squ").append("<option value='0'>——请选择——</option>"); 
				 $("#nowterm_squ").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_squ").append(opt); 
			  });
				
				 pub_alert_error(r.info);


			}

		})
		.fail(function() {
 
			
		})

}


function term_squ(){
	 
	var term=$('#nowterm_squ').val();
	var mid=$('#majorid_squ').val();

		$.ajax({
			url: '/master/export/export_excel/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#squad_squ").empty();
				$("#squad_squ").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data, function(k, v) { 
				 	var opt = $("<option/>").text(v.name).attr("value",v.id);
				 	  $("#squad_squ").append(opt); 
				  });
				
				 }else if(r.state==0){
				 	$("#squad_squ").empty();
				 	$("#squad_squ").append("<option value='0'>—请选择—</option>"); 
				 
				 	  pub_alert_error(r.info);

				 }
		})
		
						
}


/****************************************************************************/
/****************************************************************************/

function major_sco(that){
$("#nowterm").empty();
	var mid=$('#majorid_sco').val();
	var id = $(".tab-content div.active").attr('id');
		$.ajax({
			url: '/master/export/export_excel/get_nowterm/'+mid+'/'+id,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#nowterm_sco").empty();
				$("#nowterm_sco").append("<option value='0'>—请选择—</option>"); 
				$("#squad_sco").empty();
				$("#squad_sco").append("<option value='0'>—请选择—</option>"); 
			$.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_sco").append(opt); 
			});

			}else if(r.state==2){
				$("#squad_sco").empty();
				$("#squad_sco").append("<option value='0'>——请选择——</option>"); 
				 $("#nowterm_sco").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_sco").append(opt); 
			  });
				
				 pub_alert_error(r.info);


			}

		})
		.fail(function() {
 
			
		})

}


function term_sco(){
	 
	var term=$('#nowterm_sco').val();
	var mid=$('#majorid_sco').val();

		$.ajax({
			url: '/master/export/export_excel/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#squad_sco").empty();
				$("#squad_sco").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data, function(k, v) { 
				 	var opt = $("<option/>").text(v.name).attr("value",v.id);
				 	  $("#squad_sco").append(opt); 
				  });
				
				 }else if(r.state==0){
				 	$("#squad_sco").empty();
				 	$("#squad_sco").append("<option value='0'>—请选择—</option>"); 
				 
				 	  pub_alert_error(r.info);

				 }
		})
		
						
}


/****************************************************************************/
/****************************************************************************/

function major_tea(that){
$("#nowterm").empty();
	var mid=$('#majorid_tea').val();
	var id = $(".tab-content div.active").attr('id');
		$.ajax({
			url: '/master/export/export_excel/get_nowterm/'+mid+'/'+id,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#nowterm_tea").empty();
				$("#nowterm_tea").append("<option value='0'>—请选择—</option>"); 
				$("#squad_tea").empty();
				$("#squad_tea").append("<option value='0'>—请选择—</option>"); 
			$.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_tea").append(opt); 
			});

			}else if(r.state==2){
				$("#squad_tea").empty();
				$("#squad_tea").append("<option value='0'>——请选择——</option>"); 
				 $("#nowterm_tea").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_tea").append(opt); 
			  });
				
				 pub_alert_error(r.info);


			}

		})
		.fail(function() {
 
			
		})

}


function term_tea(){
	 
	var term=$('#nowterm_tea').val();
	var mid=$('#majorid_tea').val();

		$.ajax({
			url: '/master/export/export_excel/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#squad_tea").empty();
				$("#squad_tea").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data, function(k, v) { 
				 	var opt = $("<option/>").text(v.name).attr("value",v.id);
				 	  $("#squad_tea").append(opt); 
				  });
				
				 }else if(r.state==0){
				 	$("#squad_tea").empty();
				 	$("#squad_tea").append("<option value='0'>—请选择—</option>"); 
				 
				 	  pub_alert_error(r.info);

				 }
		})
		
						
}


/****************************************************************************/
/****************************************************************************/

function major_bug(that){
$("#nowterm").empty();
	var mid=$('#majorid_bug').val();
	var id = $(".tab-content div.active").attr('id');
		$.ajax({
			url: '/master/export/export_excel/get_nowterm/'+mid+'/'+id,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#nowterm_bug").empty();
				$("#nowterm_bug").append("<option value='0'>—请选择学期—</option>");
				$("#term").empty();
				$("#term").append("<option value='0'>—请选择学期—</option>");  
				$("#paytype").empty();
				$("#squad_bug").append("<option value='0'>—请选择班级—</option>"); 
			$.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_bug").append(opt); 
			});
			$.each(r.data.nowterm, function(i, k) { 
			 	 	var op = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	 	 $("#term").append(op); 
				});
			}else if(r.state==2){
				$("#squad_bug").empty();
				$("#squad_bug").append("<option value='0'>——请选择班级——</option>");
				$("#term").empty();
				$("#term").append("<option value='0'>—请选择学期—</option>");  
				$("#nowterm_bug").empty();
				 $("#nowterm_bug").append("<option value='0'>—请选择学期—</option>"); 
				 $.each(r.data.nowterm, function(i, k) { 
			 	 	var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  	$("#nowterm_bug").append(opt); 
			  });
				$.each(r.data.nowterm, function(i, k) { 
			 	 	var op = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	 	 $("#term").append(op); 
				});
				 // pub_alert_error(r.info);


			}

		})
		.fail(function() {
 
			
		})

}


function term_bug(){
	 
	var term=$('#nowterm_bug').val();
	var mid=$('#majorid_bug').val();

		$.ajax({
			url: '/master/export/export_excel/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				$("#squad_bug").empty();
				$("#squad_bug").append("<option value='0'>—请选择班级—</option>"); 
				 $.each(r.data, function(k, v) { 
				 	var opt = $("<option/>").text(v.name).attr("value",v.id);
				 	  $("#squad_bug").append(opt); 
				  });
				

				 }else if(r.state==0){
				 	$("#squad_bug").empty();
				 	$("#squad_bug").append("<option value='0'>—请选择班级—</option>"); 
				 
				 	  pub_alert_error(r.info);

				 }
		})
		
						
}


/****************************************************************************/
/****************************************************************************/

function major_eva(that){
// $("#nowterm").empty();
// 	var mid=$('#majorid_eva').val();
// 	var id = $(".tab-content div.active").attr('id');
// 		$.ajax({
// 			url: '/master/export/export_excel/get_nowterm/'+mid+'/'+id,
// 			type: 'POST',
// 			dataType: 'json',
// 			data:{},
// 		})
// 		.done(function(r) {
// 			if(r.state==1){
// 				$("#nowterm_eva").empty();
// 				$("#nowterm_eva").append("<option value='0'>—请选择—</option>"); 
// 				$("#squad_eva").empty();
// 				$("#squad_eva").append("<option value='0'>—请选择—</option>"); 
// 			$.each(r.data.nowterm, function(i, k) { 
// 			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
// 			 	  $("#nowterm_eva").append(opt); 
// 			});

// 			}else if(r.state==2){
// 				$("#squad_eva").empty();
// 				$("#squad_eva").append("<option value='0'>——请选择——</option>"); 
// 				 $("#nowterm_eva").append("<option value='0'>—请选择—</option>"); 
// 				 $.each(r.data.nowterm, function(i, k) { 
// 			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
// 			 	  $("#nowterm_eva").append(opt); 
// 			  });
				
// 				 pub_alert_error(r.info);


// 			}

// 		})
// 		.fail(function() {
 
			
// 		})

}
/********************************************************************************/

/****************************************************************************/


		$("#do_submit").click(function(){
			var active=$('.tab-content').find('div.active').attr('id');
			
			if(active=='student'){
				if($("#majorid_score").val() == 0){
				pub_alert_error('请选择专业！');
				return;
				}
				if($("#nowterm_score").val() == 0){
					pub_alert_error('请选择学期！');
					return;
				}
				if($("#squad_score").val() == 0){
					pub_alert_error('请选择班级！');
					return;
				}
				if($("#scoretype").val() == 0){
					pub_alert_error('请选择考试类型！');
					return;
				}
			}
			if(active == 'score'){
				if($("#majorid_sco").val() == 0){
				pub_alert_error('请选择专业！');
				return;
				}
				if($("#nowterm_sco").val() == 0){
					pub_alert_error('请选择学期！');
					return;
				}
				if($("#squad_sco").val() == 0){
					pub_alert_error('请选择班级！');
					return;
				}
			}
			if(active == 'budget'){
				if($("#majorid_bug").val() == 0){
				pub_alert_error('请选择学历！');
				return;
				}
				if($("#nowterm_bug").val() == 0){
					pub_alert_error('请选择学期！');
					return;
				}
				if($("#squad_bug").val() == 0){
					pub_alert_error('请选择班级！');
					return;
				}
				if($("#term").val() == 0){
					pub_alert_error('请选择学期！');
					return;
				}
			}
			if(active=='squad'){
				if($("#majorid_squ").val() == 0){
				pub_alert_error('请选择专业！');
				return;
				}
				if($("#nowterm_squ").val() == 0){
					pub_alert_error('请选择学期！');
					return;
				}
				if($("#year_squ").val() == 0){
					pub_alert_error('请选择日期！');
					return;
				}
				// //另类方法
				// OutToFileOneSheet();
				// return 0;
			}
			if(active=='evaluat_student'){
				if($("#majorid_eva").val() == 0){
					pub_alert_error('请选择专业！');
					return;
				}
				if($("#year_eva").val() == 0){
					pub_alert_error('请选择日期！');
					return;
				}
			}
			if(active=='checking'){
				if($("#majorid_check").val() == 0){
					pub_alert_error('请选择专业！');
					return;
				}
				if($("#nowterm_check").val() == 0){
					pub_alert_error('请选择学期！');
					return;
				}
				if($("#squad_check").val() == 0){
					pub_alert_error('请选择班级！');
					return;
				}
				if($("#opentime").val() == ''){
					pub_alert_error('请选择开始日期');
					return;
				}
				if($("#endtime").val() == ''){
					pub_alert_error('请选择截止日期！');
					return;
				}
			}
			if(active == 'credentials'){
				if($("#update_opentime_cre").val() == ''){
					pub_alert_error('请选择开始日期');
					return;
				}
				if($("#update_endtime_cre").val() == ''){
					pub_alert_error('请选择截止日期！');
					return;
				}
			}
			if(active == 'accommodation_info'){
				if($("#update_opentime_acc").val() == ''){
					pub_alert_error('请选择开始日期');
					return;
				}
				if($("#update_endtime_acc").val() == ''){
					pub_alert_error('请选择截止日期！');
					return;
				}
			}
			
			var obj = $(".tab-content div.active");
			var form = $("#form_"+obj.attr('id'));
			var id = $(".tab-content div.active").attr('id');
			var data = form.serialize();
			if(data==''){
				pub_alert_error('请选择字段');
				return false;
			}
			if(active == 'student_refresh' || active=="fees_export"){
				$.ajax({
					type:'post',
					url:'<?=$zjjp?>export_excel/export_go_refresh',
					data:data+'&table_id='+id,
					dataType:'json',
					beforeSend:function(){
						alert_loading();
					},
					success:function(r){
						if(r.state == 1){
							$(".modal-dialog .modal-content h3").html('<a href="/download?path='+ r.data +'&file='+ r.info +'" onclick="alert_close()">点击下载</a>');
						}else{
							$(".modal-dialog .modal-content h3").css({color:'red'}).removeClass('green').html(r.info+'<br />按ESC退出');
						}
					}
				});
			}else{
				$.ajax({
					type:'post',
					url:'<?=$zjjp?>export_excel/export_go',
					data:data+'&table_id='+id,
					dataType:'json',
					beforeSend:function(){
						alert_loading();
					},
					success:function(r){
						if(r.state == 1){
							$(".modal-dialog .modal-content h3").html('<a href="/download?path='+ r.data +'&file='+ r.info +'" onclick="alert_close()">点击下载</a>');
						}else{
							$(".modal-dialog .modal-content h3").css({color:'red'}).removeClass('green').html(r.info+'<br />按ESC退出');
						}
					}
				});
			}
		});
	</script>

<!--日期插件-->
<script type="text/javascript">
function OutToFileOneSheet(){ 
	alert(21);
    LODOP=getLodop(); 
    var html=$('#dayin').html(); 
    LODOP.PRINT_INIT(""); 
    LODOP.ADD_PRINT_TABLE(100,20,500,60,html); 
    LODOP.SET_SAVE_MODE("FILE_PROMPT",1); 
    LODOP.SET_SAVE_MODE("RETURN_FILE_NAME",1); 
    if (LODOP.SAVE_TO_FILE('timetable.xls')) pub_alert_success("导出成功！");     
  }; 
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true,
		// dateMode:dmUpDown
		})
		// //show datepicker when clicking on the icon
		// .next().on(ace.click_event, function(){
		// 	$(this).prev().focus();
		// });

	});

	
	
</script>
<script type="text/javascript">
function t_z(type){
	window.location.href="/master/export/export_excel/export_fees_type?type="+type;
}
</script>
<?php $this->load->view('master/public/footer');?>