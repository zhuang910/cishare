<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">学生排课</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 430px">
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
												foreach ($info as $kk => $vv) {
													if($vv['week']==$i&&$vv['knob']==$v){
														$form='<form id="form'.$i.$v.'">';
														
														$caozuo='<input id="grf'.$i.$v.'" type="checkbox" onclick="scheduling('.$i.','.$v.')" checke="flase">';
														$idstr='<input id="id'.$i.$v.'" type="hidden" name="id" value="">';
															if(!empty($user_info)){
																foreach ($user_info as $key => $value) {
																	if($value['week']==$i&&$value['knob']==$v){
																		$caozuo='<input id="grf'.$i.$v.'" type="checkbox" checked checke="true" onclick="scheduling('.$i.','.$v.')" checke="flase">';
																		$idstr='<input id="id'.$i.$v.'" type="hidden" name="id" value="'.$value['id'].'">';
																	}
																}
															}
															
														$form.=$idstr;
														$form.='<input type="hidden" name="userid" value="'.$userid.'">';
														$form.='<input type="hidden" name="nowterm" value="'.$nowterm.'">';
														$form.='<input type="hidden" name="courseid" value="'.$vv['courseid'].'">';
														$form.='<input type="hidden" name="teacherid" value="'.$vv['teacherid'].'">';
														$form.='<input type="hidden" name="classroomid" value="'.$vv['classroomid'].'">';
														$form.='<input type="hidden" name="week" value="'.$i.'">';
														$form.='<input type="hidden" name="knob" value="'.$v.'">';
														$form.='</form>';
														
														
														$str=$vv['tname'].'-'.$vv['rname'].$caozuo.$form;

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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function scheduling(week,knob){
	if($("#grf"+week+knob).attr("checke") == "true"){
		 $("#grf"+week+knob).attr("checke","flase");
		 var data=$('#form'+week+knob).serialize();
		 $.ajax({
		 	url: '/master/student/elestu/del',
		 	type: 'POST',
		 	dataType: 'json',
		 	data: data,
		 })
		 .done(function(r) {
		 	if(r.state==1){
		 		pub_alert_success('删除成功');
		 	}
		 })
		 .fail(function() {
		 	console.log("error");
		 })
	  
	  }else{
		  $("#grf"+week+knob).attr("checke","true");
	 	 var data=$('#form'+week+knob).serialize();
		 $.ajax({
		 	url: '/master/student/elestu/edit_scheduling',
		 	type: 'POST',
		 	dataType: 'json',
		 	data: data,
		 })
		 .done(function(r) {
		 	if(r.state==1){
		 		$('#id'+week+knob).val(r.data);
		 		pub_alert_success();
		 	}
		 })
		 .fail(function() {
		 	console.log("error");
		 })
	  }
}
</script>
