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
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />

<?php
$k=date('w',time());
if($k==0){
	$k=6;
}else{
	$k=$k-1;
}
$stime=time()-$k*3600*24;
$etime=$stime+6*24*3600;
?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		教务管理
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
									<li id="couese" class="active">
										<a data-toggle="tab" href="#faq-tab-3">
											考勤录入
										</a>
									</li>
									<li id="major">
										<a data-toggle="tab" href="#faq-tab-1">
											考勤查询
										</a>
									</li>
									<li id="couese">
										<a data-toggle="tab" href="#faq-tab-2">
											精确检索
										</a>
									</li>
									<!-- <li id="couese">
										<a data-toggle="tab" href="#faq-tab-4">
											打印报表
										</a>
									</li> -->
		
								</ul>
				<!--tab-content no-border padding-24-->
				<div class="tab-content no-border padding-24">
				<!--1-->
				<div id="faq-tab-1" class="tab-pane fade">
					<div class="widget-box transparent">
						<div class="widget-box">
							<div class="widget-header">
								<h4 class="widget-title">按条件筛选</h4>
								<div class="widget-toolbar no-border">
									<a href="javascript:;" onclick="pub_alert_html('<?=$zjjp?>checking/checking/tochanel?s=1')" class="btn btn-xs bigger btn-danger">
										导入考勤
									</a>
								</div>
							</div>
							<div class="widget-body">
								<div class="widget-main">
									<form class="form-inline" id="condition" method="post" action="<?=$zjjp?>checking/checking/export">
										<label class="control-label" for="platform">专业:</label>
										<select  id="majorid" name="majorid" aria-required="true" aria-invalid="false" onchange="major()">
											<option value="0">--请选择 --</option>>
												<?php foreach($mdata as $item){ ?>
																				<optgroup label="<?=$item['degree_title']?>">
																				<?php foreach($item['degree_major'] as $item_info){ ?>
																					<option value="<?=$item_info->id?>"><?=$item_info->name?></option>
																				<?php } ?>
																				</optgroup>
																				<?php } ?>
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
										<button class="btn btn-info btn-sm" type="submit">
											按条件导出
										</button>
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
				<!--4-->
					<div id="faq-tab-4" class="tab-pane fade">
						<div class="widget-box transparent">
							<div class="widget-box">
								<div class="widget-header">
									<h4 class="widget-title">按条件筛选</h4>
								</div>
								<div class="widget-body">
									<div class="widget-main">
									<form class="form-inline" onSubmit="return dayinbaobiao()" id="condition_report" method="post" action="<?=$zjjp?>checking/checking/checking_report">
										<label class="control-label" for="platform">专业:</label>
										<select  id="majorid_report" name="majorid" aria-required="true" aria-invalid="false" onchange="major_report()">
											<option value="0">—请选择—</option>
											<?php foreach($mdata as $k=>$v):?>
												<option value="<?php echo $v->id?>" <?=!empty($info)&&$v->id==$info->majorid ? 'selected' :''?>><?php echo $v->name?></option>
											<?php endforeach;?>
										</select>

										<label class="control-label" for="platform">学期:</label>
										<select id="nowterm_report" name="nowterm" aria-required="true" aria-invalid="false" onchange="term_report()">
											<option value="0">—请选择—</option>
										</select>
										<label class="control-label" for="platform">教室:</label>
										<select id="croom_report" name="classroomid" aria-required="true" aria-invalid="false" onchange="classroom_report()">
											<option value="0">—请选择—</option>
										</select>
										<label class="control-label" for="platform">老师:</label>
										<select id="teacher_report" name="teacherid" aria-required="true" aria-invalid="false">
											<option value="0">—请选择—</option>
										</select>
										<label class="control-label" for="platform">周数:</label>
										<select id="num" name="num" aria-required="true" aria-invalid="false">
											<option value="0">—请选择—</option>
										</select>
										<button class="btn btn-info" data-last="Finish">
												<span class="bigger-110">打印报表</span>
										</button>
									</form>
								</div>
								</div>
							</div>
						</div>
				</div>
				<!--4-->
				<!--3-->
					<div id="faq-tab-3" class="tab-pane fade in active">
						<div class="widget-box transparent">
							<div class="widget-box">
								<div class="widget-header">
									<h4 class="widget-title">按条件筛选</h4>
								</div>
								<div class="widget-body">
									<div class="widget-main">
										<form class="form-inline" id="conditions" method="post" action="<?=$zjjp?>checking/checking/print_report">
											<label class="control-label" for="platform">专业:</label>
											<select  id="majorids" name="majorid" aria-required="true" aria-invalid="false" onchange="majors()">
												<option value="0">—请选择—</option>
												<?php foreach($mdata as $item){ ?>
																				<optgroup label="<?=$item['degree_title']?>">
																				<?php foreach($item['degree_major'] as $item_info){ ?>
																					<option value="<?=$item_info->id?>"><?=$item_info->name?></option>
																				<?php } ?>
																				</optgroup>
																				<?php } ?>
											</select>

											<label class="control-label" for="platform">学期:</label>
											<select id="nowterms" name="nowterm" aria-required="true" aria-invalid="false" onchange="terms()">
												<option value="0">—请选择—</option>
											</select>
											<label class="control-label" for="platform">班级:</label>
											<select id="squads" name="squadid" aria-required="true" aria-invalid="false" onchange="cs()">
												<option value="0">—请选择—</option>
											</select>
											<label class="control-label" for="platform">课程:</label>
											<select id="courses" name="courseid" aria-required="true" aria-invalid="false" onchange="cs()">
												<option value="0">—请选择—</option>
											</select>
											<label class="control-label" for="platform">关键词:</label>
											<select id="key" name="key" aria-required="true" aria-invalid="false">
												<option value="0">—请选择—</option>
												<option value="name">姓名</option>
												<option value="email">邮箱</option>
												<option value="studentid">学号</option>
												<option value="passport">护照号</option>
											</select>

											<input id="value" type="text" name="value"/>
											<a class="btn btn-primary btn-sm" type="button" onclick="sures()">
												确认条件
											</a>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div>
							<div class="col-sm-12">
								<div id="tables-3" class="widget-box transparent collapsed">
									<div class="widget-body">
										<div class="widget-main">
											<div style="margin:0 auto;">
												<a class="btn btn-sm btn-success" type="button" onclick="up()">
													<i class="ace-icon fa fa-angle-double-left"></i>
												</a>
													<input onblur="changedate()" id="starttime" style="width:100px;"  class="date-picker valid" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d',$stime)?>">-
													<input id="overtime" style="width:100px;" type="text" style="border:0px;" value="<?php echo date('Y-m-d',$etime)?>">
												<a class="btn btn-sm btn-success" type="button" onclick="next()">
													<i class="ace-icon fa fa-angle-double-right"></i>
												</a>
											</div>
											<form id='scorearr'>
												<table class="table table-bordered table-striped" id="table">
													<thead class="thin-border-bottom" id="stypes-3">
													<tr>
														<th>学号</th>
														<th width="150">中文名</th>
														<th width="200">英文名</th>
														<th>星期一<br /><span id="mo"><?php echo date('m-d',$stime)?></span></th>
														<th>星期二<br /><span id="tu"><?php echo date('m-d',$stime+3600*24)?></span></th>
														<th>星期三<br /><span id="we"><?php echo date('m-d',$stime+3600*24*2)?></span></th>
														<th>星期四<br /><span id="th"><?php echo date('m-d',$stime+3600*24*3)?></span></th>
														<th>星期五<br /><span id="fr"><?php echo date('m-d',$stime+3600*24*4)?></span></th>
														<th>星期六<br /><span id="sa"><?php echo date('m-d',$stime+3600*24*5)?></span></th>
														<th>星期日<br /><span id="su"><?php echo date('m-d',$stime+3600*24*6)?></span></th>
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
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>	
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<!-- delete -->
<script type="text/javascript">
function dayinbaobiao(){
	var majorid=$('#majorid_report').val();
	var nowterm=$('#nowterm_report').val();
	var croom=$('#croom_report').val();
	var teacher=$('#teacher_report').val();
	var num=$('#num').val();
	if(majorid==0){
		pub_alert_error('请选择专业');
		return false;
	}else if(nowterm==0){
		pub_alert_error('请选择学期');
		return false;
	}else if(croom==0){
		pub_alert_error('请选择教室');
		return false;
	}else if(teacher==0){
		pub_alert_error('请选择教师');
		return false;
	}else if(num==0){
		pub_alert_error('请选择周数');
		return false;
	}else{
		return true;
	}	
	return false;
}
var c_type_config = {"1":"旷课","2":"请假","3":"迟到"};
(function ($) {
    var JJimgconfig = function (options) {
        this.init('JJimgconfig', options, JJimgconfig.defaults);
    };
    $.fn.editableutils.inherit(JJimgconfig, $.fn.editabletypes.abstractinput);
    $.extend(JJimgconfig.prototype, {
        render: function() {
           this.$input = this.$tpl.find('.form-control');
        },
        value2html: function(value, element) {
            if(!value) {
	            $(element).empty();
	            return;
            }
	        var c_is_remark = value.c_remark == '' ? '' : '&sbquo;备注&brvbar;'+ $('<div>').text(value.c_remark).html();
            var html = $('<div>').text(c_type_config[value.c_type]).html() + c_is_remark;
            $(element).html(html); 
        },
       
        html2value: function(html) {
        	html = html.replace(/\‚/, "&sbquo;");
        	html = html.replace(/\¦/, "&brvbar;");
        	var c_type_cn_config = {"缺勤":"1","早退":"2","迟到":"3","请假":"4"};
        	var html = html.split('&sbquo;');
        	var c_type_val = c_type_cn_config[html[0]] == undefined ? null : c_type_cn_config[html[0]];
          var obj = new Object();
          if(html && html.length > 1){
              for (var i = 0; html.length > i; i++) {
                obj[i] = html[i].split('&brvbar;');
              };
              
              c_type_val = c_type_cn_config[obj[0]] == undefined ? null : c_type_cn_config[html[0]];
            obj = {c_type:c_type_val,c_remark:obj[1][1]};
          }else{
          	obj = {c_type:c_type_val,c_remark:''};
          }
           return obj; 
        },
       value2str: function(value) {
           var str = '';
           if(value) {
               for(var k in value) {
                   str = str + k + ':' + value[k] + ';';  
               }
           }
           return str;
       }, 
       
       str2value: function(str) {
           return str;
       },    
       value2input: function(value) {
           if(!value) {
             return;
           }
           this.$input.filter('[name="c_type"]').val(value.c_type);
           this.$input.filter('[name="c_remark"]').val(value.c_remark);
       },    
       input2value: function() { 
           return {
              c_type: this.$input.filter('[name="c_type"]').val(),
              c_remark: this.$input.filter('[name="c_remark"]').val()
           };
       },    
       activate: function() {
            this.$input.filter('[name="c_type"]').focus();
       },    
       autosubmit: function() {
           this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
           });
       }       
    });

    JJimgconfig.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="widget-main">\
		<div>\
			<label for="c_type">类型</label>\
			<select  name="c_type" id="c_type" class="form-control">\
				<option value="">请选择...</option>\
				<option value="1">旷课</option>\
				<option value="2">请假</option>\
				<option value="3">迟到</option>\
			</select>\
		</div>\
	\
		<hr>\
		<div>\
			<label for="c_remark">备注</label>\
			<textarea maxlength="50" name="c_remark" id="c_remark" class="form-control limited"></textarea>\
		</div>\
	</div>',
             
        inputclass: ''
    });

    $.fn.editabletypes.jjimgconfig = JJimgconfig;

}(window.jQuery));

$( "#starttime" ).datepicker({
				showOtherMonths: true,
				selectOtherMonths: false,
			OnCloseUp:function(e){
				alert(213);
			}
		});

function c(){

	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#tbody').remove();
}
function c_report(){

	$('#tables_report').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#tbody_report').remove();
}
function cs(){

	$('#tables-3').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#tbody-3').remove();
}
function major(){

	var mid=$('#majorid').val();
		$.ajax({
			url: '<?=$zjjp?>checking/checking/get_nowterm/'+mid,
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
function majors(){

	var mid=$('#majorids').val();
		$.ajax({
			url: '<?=$zjjp?>checking/checking/get_nowterm/'+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#nowterms").empty();
			$("#squads").empty();
			$("#courses").empty();
			$("#nowterms").append("<option value='0'>—请选择—</option>"); 
			$("#squads").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterms").append(opt); 
			  });
			 $("#courses").empty();
			 $("#courses").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data.course, function(k, v) { 
			 	var opt = $("<option/>").text(v.cname).attr("value",v.id);
			 	  $("#courses").append(opt); 
			  });
			 $('#tables-3').attr({
				class: 'widget-box transparent collapsed'
			});
			  $('#tbody-3').remove();
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
			url: '<?=$zjjp?>checking/checking/get_squad?term='+term+'&mid='+mid,
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
function terms(){
	 
	var term=$('#nowterms').val();
	var mid=$('#majorids').val();
	$('#tables-3').attr({
				class: 'widget-box transparent collapsed'
			});
	$("#squads").empty();
	$("#squads").append("<option value='0'>—请选择—</option>"); 
		$.ajax({
			url: '<?=$zjjp?>checking/checking/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			if(r.state==1){
					 $.each(r.data, function(k, v) { 
					 	var opt = $("<option/>").text(v.name).attr("value",v.id);
					 	  $("#squads").append(opt); 
					  });
					 $('#tables-3').attr({
						class: 'widget-box transparent collapsed'
					});
					 // $('#tbody-3').remove();
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
		url: '<?=$zjjp?>checking/checking/get_student',
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
					// shezhi='<a onclick="pub_alert_html(\'<?=$zjjp?>checking/checking/set_checking?studentid='+v.id+'&majorid='+mid+'&squadid='+sid+'&s=1\')" role="button" class="blue" data-toggle="modal"><i class="ace-icon fa fa-cogs bigger-130"></i></a>';
				var xiangxi='';
				if(v.kaoqin!='全勤'){
					xiangxi='<a onclick="pub_alert_html(\'<?=$zjjp?>checking/checking/student_more_checking?studentid='+v.id+'&majorid='+mid+'&squadid='+sid+'&s=1\')" href="javascript:;">详细</a>';
				}
				str+='<tr><td width="100">'+v.studentid+'</td><td width="150">'+v.name+'</td><td width="250">'+v.enname+'</td><td id="kaoqin'+v.id+'" width="200">'+v.kaoqin+xiangxi+'</td><td id="chuqin'+v.id+'" width="150">'+v.chuqin+'</td></tr>';
			
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
function sures(time){
	var data=$('#conditions').serialize();
	var term=$('#nowterms').val();
	var mid=$('#majorids').val();
	var sid=$('#squads').val();
	var cid=$('#courses').val();
	var time=$('#starttime').val();

	$.ajax({
		url: '<?=$zjjp?>checking/checking/get_students?time='+time,
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			$('#add').remove();
			$('#tbody-3').remove();
			var str='<tbody id="tbody-3">';

			$.each(r.data.stu, function(kk, vv) {
				var yi=er=san=si=wu=liu=ri='';
				$.each(r.data.pdata,function(k,v){
					knob=' '+v.knob+'节课';
					var	pk=vv.id+'-'+vv.email+'-'+v.majorid+'-'+v.teacherid+'-'+v.courseid+'-'+v.squadid+'-'+v.knob+'-'+v.nowterm
					if(v.week==1){
						pk+='-'+v.week;
						var ckinfo='考勤';
						$.each(r.data.checking,function(kkk,vvv){
							if(vv.id==vvv.studentid && v.week==vvv.week && v.knob==vvv.knob && v.majorid==vvv.majorid && v.squadid==vvv.squadid &&v.nowterm==vvv.nowterm && v.courseid==vvv.courseid){
								ckinfo= vvv.type+'&sbquo;备注&brvbar;'+vvv.remark;
								pk+='-'+vvv.id;
							}
						})
						yi+=knob+'<a do-checking="true" data-type="jjimgconfig" data-pk="'+pk+'" data-original-title="录入考勤" data-placement="right" href="javascript:;">'+ckinfo+'</a>';
					}else if(v.week==2){
						pk+='-'+v.week;
						var ckinfo='考勤';
						$.each(r.data.checking,function(kkk,vvv){
							if(vv.id==vvv.studentid && v.week==vvv.week && v.knob==vvv.knob && v.majorid==vvv.majorid && v.squadid==vvv.squadid &&v.nowterm==vvv.nowterm && v.courseid==vvv.courseid){
								ckinfo= vvv.type+'&sbquo;备注&brvbar;'+vvv.remark;
								pk+='-'+vvv.id;
							}
						})
						er+=knob+'<a do-checking="true" data-type="jjimgconfig" data-pk="'+pk+'" data-original-title="录入考勤" data-placement="right" href="javascript:;">'+ckinfo+'</a>';
					}else if(v.week==3){
						var ckinfo='考勤';
						pk+='-'+v.week;
						$.each(r.data.checking,function(kkk,vvv){
							if(vv.id==vvv.studentid && v.week==vvv.week && v.knob==vvv.knob && v.majorid==vvv.majorid && v.squadid==vvv.squadid &&v.nowterm==vvv.nowterm && v.courseid==vvv.courseid){
								ckinfo= vvv.type+'&sbquo;备注&brvbar;'+vvv.remark;
								pk+='-'+vvv.id;
							}
						})
						san+=knob+'<a do-checking="true" data-type="jjimgconfig" data-pk="'+pk+'" data-original-title="录入考勤" data-placement="right" href="javascript:;">'+ckinfo+'</a>';
					}else if(v.week==4){
						var ckinfo='考勤';
						pk+='-'+v.week;
						$.each(r.data.checking,function(kkk,vvv){
							if(vv.id==vvv.studentid && v.week==vvv.week && v.knob==vvv.knob && v.majorid==vvv.majorid && v.squadid==vvv.squadid &&v.nowterm==vvv.nowterm && v.courseid==vvv.courseid){
								ckinfo= vvv.type+'&sbquo;备注&brvbar;'+vvv.remark;
								pk+='-'+vvv.id;
							}
						})
						si+=knob+'<a do-checking="true" data-type="jjimgconfig" data-pk="'+pk+'" data-original-title="录入考勤" data-placement="right" href="javascript:;">'+ckinfo+'</a>';
					}else if(v.week==5){
						var ckinfo='考勤';
						pk+='-'+v.week;
						$.each(r.data.checking,function(kkk,vvv){
							if(vv.id==vvv.studentid && v.week==vvv.week && v.knob==vvv.knob && v.majorid==vvv.majorid && v.squadid==vvv.squadid &&v.nowterm==vvv.nowterm && v.courseid==vvv.courseid){
								ckinfo= vvv.type+'&sbquo;备注&brvbar;'+vvv.remark;
								pk+='-'+vvv.id;
							}
						})
						wu+=knob+'<a do-checking="true" data-type="jjimgconfig" data-pk="'+pk+'" data-original-title="录入考勤" data-placement="right" href="javascript:;">'+ckinfo+'</a>';
					}else if(v.week==6){
						var ckinfo='考勤';
						pk+='-'+v.week;
						$.each(r.data.checking,function(kkk,vvv){
							if(vv.id==vvv.studentid && v.week==vvv.week && v.knob==vvv.knob && v.majorid==vvv.majorid && v.squadid==vvv.squadid &&v.nowterm==vvv.nowterm && v.courseid==vvv.courseid){
								ckinfo= vvv.type+'&sbquo;备注&brvbar;'+vvv.remark;
								pk+='-'+vvv.id;
							}
						})
						liu+=knob+'<a do-checking="true" data-type="jjimgconfig" data-pk="'+pk+'" data-original-title="录入考勤" data-placement="right" href="javascript:;">'+ckinfo+'</a>';
					}else if(v.week==7){
						var ckinfo='考勤';
						pk+='-'+v.week;
						$.each(r.data.checking,function(kkk,vvv){
							if(vv.id==vvv.studentid && v.week==vvv.week && v.knob==vvv.knob && v.majorid==vvv.majorid && v.squadid==vvv.squadid &&v.nowterm==vvv.nowterm && v.courseid==vvv.courseid){
								ckinfo= vvv.type+'&sbquo;备注&brvbar;'+vvv.remark;
								pk+='-'+vvv.id;
							}
						})
						ri+=knob+'<a do-checking="true" data-type="jjimgconfig" data-pk="'+pk+'" data-original-title="录入考勤" data-placement="right" href="javascript:;">'+ckinfo+'</a>';
					}
				});
				var week='<td>'+yi.substring(0,yi.length-1) +'</td><td>'+er.substring(0,er.length-1)+'</td><td>'+san.substring(0,san.length-1)+'</td><td>'+si.substring(0,si.length-1)+'</td><td>'+wu.substring(0,wu.length-1)+'</td><td>'+liu.substring(0,liu.length-1)+'</td><td>'+ri.substring(0,ri.length-1)+'</td>';
				str+='<tr><td width="100">'+vv.studentid+'</td><td width="150">'+vv.name+'</td><td>'+vv.enname+'</td>'+week+'</tr>';
			
			}); 
			str+='</tbody>';

			var checking_info = $(str);
			var checking_do_as = checking_info.find('a[do-checking="true"]');
			$(checking_do_as).editable({
		        url: function(params) {
					var d = new $.Deferred;
					var date=$('#starttime').val();
					$.ajax({
						type:'POST',
						url:'/master/checking/checking/zjj_test?date='+date,
						data:$.param(params),
						dataType:'json',
						success: function(r) {
							if(r.state == 1){
								pub_alert_success(r.info);
								d.resolve();
							}else{
								pub_alert_error(r.info);
								return d.reject(r.info);
							}
						}
					});
					return d.promise();
				},
		        validate: function(value) {
		            if(value.c_type == '') return '类型不能为空';
		        }       
		    }); 

			$('#stypes-3').after(checking_info);
			$('#tables-3').attr({
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
		url: '<?=$zjjp?>checking/checking/get_student_quick?key='+key+'&value='+value,
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
				xiangxi='<a onclick="pub_alert_html(\'<?=$zjjp?>checking/checking/student_more_checking?studentid='+v.id+'&s=1\')" href="javascript:;">详细</a>';
				str+='<tr><td width="100">'+v.studentid+'</td><td width="100">'+v.name+'</td><td width="100">'+v.mname+'</td><td width="100">'+v.sname+'</td><td width="100">'+v.email+'</td><td width="100">'+v.passport+'</td><td id="kaoqin'+v.id+'" width="150">'+v.kaoqin+xiangxi+'</td><td id="chuqin'+v.id+'" width="150">'+v.chuqin+'</td><td><a onclick="pub_alert_html(\'<?=$zjjp?>checking/checking/quick_checking_page?studentid='+v.id+'&majorid='+v.majorid+'&squadid='+v.squadid+'&s=1\')" role="button" class="blue" data-toggle="modal"><i class="ace-icon fa fa-cogs bigger-130"></i></a></td></tr>';
			
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
function next(){
	var starttime=$('#starttime').val();
	var overtime=$('#overtime').val();
	
	$.ajax({
		url: "<?=$zjjp?>checking/checking/next_time?starttime="+starttime+'&overtime='+overtime,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
	
		if(r.state=1){
			
			$('#starttime').val(r.data.start);
			$('#overtime').val(r.data.over);
				$('#mo').text(r.data.mo);
				$('#tu').text(r.data.tu);
				$('#we').text(r.data.we);
				$('#th').text(r.data.th);
				$('#fr').text(r.data.fr);
				$('#sa').text(r.data.sa);
				$('#su').text(r.data.su);
			sures();

		}

	})
}
function up(){
	var starttime=$('#starttime').val();
	var overtime=$('#overtime').val();
	
	$.ajax({
		url: "<?=$zjjp?>checking/checking/up_time?starttime="+starttime+'&overtime='+overtime,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
	
		if(r.state==1){
			$('#starttime').val(r.data.start);
			$('#overtime').val(r.data.over);
			$('#mo').text(r.data.mo);
			$('#tu').text(r.data.tu);
			$('#we').text(r.data.we);
			$('#th').text(r.data.th);
			$('#fr').text(r.data.fr);
			$('#sa').text(r.data.sa);
			$('#su').text(r.data.su);
			sures();
		}else if(r.state==0){
			
			pub_alert_error(r.info);

		}

	})
}
function changedate(){
	var starttime=$('#starttime').val();
	var overtime=$('#overtime').val();
	$.ajax({
		url: "<?=$zjjp?>checking/checking/change_date?starttime="+starttime+'&overtime='+overtime,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
	
		if(r.state==1){
			$('#starttime').val(r.data.start);
			$('#overtime').val(r.data.over);
			$('#mo').text(r.data.mo);
			$('#tu').text(r.data.tu);
			$('#we').text(r.data.we);
			$('#th').text(r.data.th);
			$('#fr').text(r.data.fr);
			$('#sa').text(r.data.sa);
			$('#su').text(r.data.su);
			sures();
		}else if(r.state==0){
			
			pub_alert_error(r.info);
			$('#starttime').val(r.data.start);
			$('#overtime').val(r.data.over);
			$.each(r.data.ckdata, function(k, v) {
					var id='type'+v.week+'and'+v.knob
					$("#"+id).val(v.type);
			});
		}

	})
	
}
function print(){
	var data=$('#condition').serialize();
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();
	var sid=$('#squad').val();
	var cid=$('#course').val();
	$.ajax({
		url: '<?=$zjjp?>checking/checking/print_report',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){

		}else if(r.state==2){
			 pub_alert_error(r.info);
		}
				

	})
	.fail(function() {
		
	})
}
//报表
function major_report(){

	var mid=$('#majorid_report').val();
		$.ajax({
			url: '<?=$zjjp?>checking/checking/get_nowterm/'+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#nowterm_report").empty();
			$("#squad_report").empty();
			$("#nowterm_report").append("<option value='0'>—请选择—</option>"); 
			$("#squad_report").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm_report").append(opt); 
			  });
			 $.each(r.data.num, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.num[i]+'周').attr("value",r.data.num[i]);
			 	  $("#num").append(opt); 
			  });
			 $('#tables_report').attr({
				class: 'widget-box transparent collapsed'
			});
			  $('#tbody_report').remove();
		})
		.fail(function() {
 
			
		})

}
function term_report(){
	 
	var term=$('#nowterm_report').val();
	var mid=$('#majorid_report').val();
	$('#tables_report').attr({
				class: 'widget-box transparent collapsed'
			});
	$("#squad_report").empty();
	$("#squad_report").append("<option value='0'>—请选择—</option>"); 
		$.ajax({
			url: '<?=$zjjp?>checking/checking/get_class_room?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			if(r.state==1){
					 $.each(r.data, function(k, v) { 
					 	var opt = $("<option/>").text(v.name).attr("value",v.classroomid);
					 	  $("#croom_report").append(opt); 
					  });
					 $('#tables_report').attr({
						class: 'widget-box transparent collapsed'
					});
					 $('#tbody_report').remove();
				 }else{
				 	 pub_alert_error(r.info);
				 }
		})
		
						
}


function classroom_report(){
	 
	var term=$('#nowterm_report').val();
	var mid=$('#majorid_report').val();
	var classroom=$("#croom_report").val();
	$('#tables_report').attr({
				class: 'widget-box transparent collapsed'
			});
	$("#teacher_report").empty();
	$("#teacher_report").append("<option value='0'>—请选择—</option>"); 
		$.ajax({
			url: '<?=$zjjp?>checking/checking/get_class_room_teacher?term='+term+'&mid='+mid+'&classroomid='+classroom,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			if(r.state==1){
					 $.each(r.data, function(k, v) { 
					 	var opt = $("<option/>").text(v.name).attr("value",v.teacherid);
					 	  $("#teacher_report").append(opt); 
					  });
					 $('#tables_report').attr({
						class: 'widget-box transparent collapsed'
					});
					 $('#tbody_report').remove();
				 }else{
				 	 pub_alert_error(r.info);
				 }
		})
		
						
}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>