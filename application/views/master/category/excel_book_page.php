<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<link rel="stylesheet" href="/resource/master/css/datepicker.css" />
<script src="/resource/master/js/date-time/bootstrap-datepicker.min.js"></script>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" style="height:400px;">
			<div class="widget-header">
				<h5 class="widget-title">发书</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 230px">
						<div class="widget-body">
							<div class="widget-main">
								<form id="condition" class="form-horizontal" action="/master/student/send_book/export" method="post">
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
											<select id="squad" class="col-sm-8" name="squadid" aria-required="true" aria-invalid="false">
												<option value="0">—请选择—</option>
											</select>
										</div>
									</div>
									<div class="form-actions center">
										<button class="btn btn-info btn-sm" type="submit">
											导出
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--日期插件-->
<script type="text/javascript">
function major(){
$("#nowterm").empty();
	var mid=$('#majorid').val();
		$.ajax({
			url: '/master/score/stuscore/get_nowterm/'+mid,
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
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm").append(opt); 
			  });
			
			}else if(r.state==2){
				$("#squad").empty();
				$("#squad").append("<option value='0'>——请选择——</option>"); 
				 $("#nowterm").append("<option value='0'>—请选择—</option>"); 
				 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm").append(opt); 
			  });
				
				  $('#tbody').remove();
				//  pub_alert_error(r.info);


			}

		})
		.fail(function() {
 
			
		})

}
function term(){
	 
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();

		$.ajax({
			url: '/master/score/stuscore/get_squad?term='+term+'&mid='+mid,
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
