
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												排课
											</div>
										</div>

										<form class="form-horizontal" id="validation-form" method="post" action="" >
											<input type="hidden" value="<?=$id?>" name='id'>
											<input type="hidden" value="<?=$majorid?>" name='majorid'>
											<input type="hidden" value="<?=$squadid?>" name='squadid'>
											<input type="hidden" value="<?=$nowterm?>" name='nowterm'>
											<input type="hidden" value="<?=$courseid?>" name='courseid'>
											<input type="hidden" value="<?=$week?>" name='week'>
											<input type="hidden" value="<?=$knob?>" name='knob'>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">老师:</label>
											<select onchange="get_hour()" id="teacherid" class="input-medium valid" name="teacherid" aria-required="true" aria-invalid="false">
												<option value="0" selected="selected">-请选择-</option>
												<?php foreach($teacherinfo as $k => $v):?>
													<option value="<?=$v['id']?>"><?=$v['name']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="space-2"></div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">教室:</label>
											<select onchange="get_hour()" id="classroomid" class="input-medium valid" name="classroomid"  >
													<option value="0" selected="selected">-请选择-</option>
													<?php foreach($classroominfo as $k => $v):?>
														<option value='<?=$v['id']?>'><?=$v['name']?>--<?=$v['size']?>人</option>
													<?php endforeach;?>
											</select>
										</div>
										<div class="space-2"></div>
										<div class="form-group" id="keyong" style="display:none;">
												<label id="label_ke" class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">今天可用时间:</label>
												
											
											</div>
										<div class="space-2"></div>
										<div class="modal-footer no-margin-top">
											<div class="space-2"></div>
												<div class="col-md-offset-3 col-md-9">
													
													<a href="javascript:;" onclick="arrange_submit()" class="btn btn-info" data-last="Finish" data-dismiss="modal">
														<i class="ace-icon fa fa-check bigger-110"></i>
															提交
													</a>
													<button class="btn" type="reset">
														<i class="ace-icon fa fa-undo bigger-110"></i>
															重置
													</button>
												</div>
										</div>
									</form>
								</div>
							</div>
<script type="text/javascript">
function get_hour(){
	$('#keyong').css({
		display: 'none'
	});
	var teacherid=$('#teacherid').val();
	var classroomid=$('#classroomid').val();
	if(teacherid!=0 && classroomid!=0){
		var data=$("#validation-form").serialize();
		$.ajax({
			url: '/master/syllabus/arrangement/get_day_hour',
			type: 'POST',
			dataType: 'json',
			data: data,
		})
		.done(function(r) {
			if(r.state==1){
				var str='';
				$.each(r.data, function(k, v) {
					 str+='<label><input class="ace" type="checkbox" value="'+v.knob+'" name="day_knob[]"><span class="lbl">第'+v.knob+'节课</span></label>';
				});
				$("#label_ke").after(str);
				$('#keyong').css({
					display: 'block'
				});
			}
		})
		.fail(function() {
			console.log("error");
		})
		
	}
}
	
function arrange_submit(){
	var data=$("#validation-form").serialize();

	$.ajax({
		url: "<?=$zjjp?>arrangement/add",
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			// $('#at'+r.data.week+r.data.knob).remove();
			// $('#td'+r.data.week+r.data.knob).find('a').eq(0).html('');
			// $('#td'+r.data.week+r.data.knob).find('a').eq(1).remove();
			// var del= r.data.id ? '<button id="at'+r.data.week+r.data.knob+'"  onclick="del('+r.data.id+')" class="red"><i class="ace-icon fa fa-remove bigger-130"></i></button>' :'';
				
			// 	var info='';
			// 	info=r.data.tname+'-';
			// 	info+=r.data.cname+'-';
			// 	info+=r.data.rname;
			// var str='pub_alert_html('+'\'<?=$zjjp?>arrangement/popup?id='+r.data.id+'&knob='+r.data.knob+'&week='+r.data.week+'&courseid='+r.data.courseid+'&termnum='+r.data.nowterm+'&squadid='+r.data.squadid+'&majorid='+r.data.majorid+'\''+')';
			//  $('#td'+r.data.week+r.data.knob).find('a').attr({
			//  	onclick: str
			//  })
			//  $('#td'+r.data.week+r.data.knob).find('a').text(info).attr('class','blue');
			//  $('#td'+r.data.week+r.data.knob).find('a').after(del);
			paike();
		}else{
			
			pub_alert_error(r.info);
		}
	})
	.fail(function() {
		console.log("error");
	})
	
	return false;
}

</script>