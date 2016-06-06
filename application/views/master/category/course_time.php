<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">选课排课</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body" style="height:500px;">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 330px">
						<!--week_knob-->
						<div id="week_knob">
							<table class="table table-bordered table-striped">
								<thead class="thin-border-bottom">
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
								</thead>
								<tbody>
								<?php foreach($hour['hour'] as $k=>$v):?>
									<tr>
										<td><?php
											echo $v."节课";
											?></td>
										<?php for($i=1;$i<=7;$i++):?>
											<?php
												$str='--';
												$tid='';
												if(!empty($t_time)){
													foreach ($t_time as $kk => $vv) {
														if($v==$vv['knob']&&$i==$vv['week']){
															$tid.='-'.$vv['teacherid'];
														}
													}
													if(!empty($tid)){
														$tid=trim($tid,'-');
														$str='<a href="javascript:;" onclick="keyong('.$i.','.$v.','.$courseid.',\''.$tid.'\')">可用</a>';
													}
													foreach ($electives as $kkk => $vvv) {
														if($v==$vvv['knob']&&$i==$vvv['week']){
															$del='<a class="red" href="javascript:;" onclick="del('.$vvv['id'].','.$i.','.$v.','.$courseid.',\''.$tid.'\')"><i class="ace-icon fa fa-remove bigger-130"></i></a>';
															$str=$vvv['tname'].'-'.$vvv['rname'].$del;
														}
													}
												}
												
												echo '<td id="td'.$i.$v.'">'.$str.'</td>';
											?>
										<?php endfor;?>

									</tr>
								<?php endforeach;?>
								</tbody>
							</table>
						</div>
						<!--end week_knob-->
						<!--form-->
						<div id="teacher_room" style="display:none;">
							<form class="form-horizontal" id="form_electives" method="post" action="" >
								<input id="courseid" type="hidden" value="" name='courseid'>
								<input id="week" type="hidden" value="" name='week'>
								<input id="knob" type="hidden" value="" name='knob'>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">老师:</label>
									<select id="teacherid" class="input-medium valid" name="teacherid" aria-required="true" aria-invalid="false">
										<option value="0" selected="selected">-请选择-</option>
									</select>
								</div>
								<div class="space-2"></div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">教室:</label>
										<select id="classroomid" class="input-medium valid" name="classroomid"  >
											<option value="0" selected="selected">-请选择-</option>
										</select>
								</div>
								<div class="space-2"></div>
								<div class="modal-footer no-margin-top">
									<div class="space-2"></div>
									<div class="col-md-offset-3 col-md-9">
										<a id="tijiaos" href="javascript:;" class="btn btn-info">
											<i class="ace-icon fa fa-check bigger-110"></i>
												提交
										</a>
										<a class="btn" href="javascript:;" onclick="fanhui()">
											<i class="ace-icon fa fa-undo bigger-110"></i>
												返回
										</a>
									</div>
								</div>
							</form>
						</div>
						<!--end form-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function save_time(){
	var form=$('#validation-form');
	var data=form.serialize();
	$.ajax({
		url: form.attr('action'),
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
			window.location.href="/master/student/electives";
		}
	})
	.fail(function() {
		console.log("error");
	})
	
}
function keyong(week,knob,cid,tid){
	$.ajax({
		url: '/master/student/electives/get_teacher_room?tid='+tid+'&week='+week+'&knob='+knob+'&cid='+cid,
		type: 'GET',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		$("#teacherid").empty();
		$("#teacherid").append("<option value='0'>——请选择——</option>");
		$.each(r.data.teacher, function(k, v) {
			var opt = $("<option/>").text(v.name).attr("value",v.id);
			$("#teacherid").append(opt);
		});
		$("#classroomid").empty();
		$("#classroomid").append("<option value='0'>——请选择——</option>");
		$.each(r.data.room, function(k, v) {
			var opt = $("<option/>").text(v.name).attr("value",v.id);
			$("#classroomid").append(opt);
		});
		$('#courseid').val(r.data.courseid);
		$('#week').val(r.data.week);
		$('#knob').val(r.data.knob);
		$('#tijiaos').attr({
			onclick: 'tijiao('+r.data.courseid+',\''+r.data.tid+'\')',
		});
	})
	.fail(function() {
		console.log("error");
	})
	$('#week_knob').attr({
		style: 'display:none',
	});
	$('#teacher_room').removeAttr('style');
}
function tijiao(cid,tid){
	if($('#teacherid').val()==0){
		pub_alert_error('请选择授课老师');
		return false;
	}
	if($('#classroomid').val()==0){
		pub_alert_error('请选择上课教室');
		return false;
	}
	var data=$('#form_electives').serialize();
	$.ajax({
		url: '/master/student/electives/save_electives',
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			var str='<a class="red" href="javascript:;" onclick="del('+r.data.id+','+r.data.week+','+r.data.knob+','+cid+',\''+tid+'\')"><i class="ace-icon fa fa-remove bigger-130"></i></a>'
			$('#td'+r.data.week+r.data.knob).html(r.data.tname+'-'+r.data.rname+str);
		}
	})
	.fail(function() {
		console.log("error");
	})
	$('#teacher_room').attr({
		style: 'display:none',
	});
	$('#week_knob').removeAttr('style');
}
function fanhui(){
	$('#teacher_room').attr({
		style: 'display:none',
	});
	$('#week_knob').removeAttr('style');
}
function del(id,week,knob,cid,tid){
	pub_alert_confirmss('/master/student/electives/del?id='+id,'','确定要执行本次操作吗？',week,knob,cid,tid);
}
function pub_alert_confirmss(url,data,msg,week,knob,cid,tid){
  if(!url) return false;
    msg = msg ? msg : '确定要执行本次操作吗？';
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
                  $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    dataType:'json',
                    success:function(r){
                      if(r.state == 1){
                      	var str='<a href="javascript:;" onclick="keyong('+week+','+knob+','+cid+',\''+tid+'\')">可用</a>';
                      	$('#td'+week+knob).html(str);
                        pub_alert_success(r.info);
                      }else{
                        pub_alert_error(r.info);
                      }
                    }
                   });
              }
            }
            }
          );
}
</script>
<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>
