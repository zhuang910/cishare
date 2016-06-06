<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<link rel="stylesheet" href="/resource/master/css/datepicker.css" />
<script src="/resource/master/js/date-time/bootstrap-datepicker.min.js"></script>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" style="height:400px;">
			<div class="widget-header">
				<h5 class="widget-title">导出成绩</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 230px">
						<form target="_blank" class="form-horizontal" id="validation-form" method="post" action="/master/student/student/get_student_score_download" enctype = 'multipart/form-data'>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开始时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="studystime" class="form-control date-picker" name="studystime" value="<?=date('Y-m-d',time())?>">
									例如：2000-01-01
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">截止时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="studyetime" class="form-control date-picker" name="studyetime" value="<?=date('Y-m-d',time())?>">
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>
						</div>
								
						<div class="modal-footer center">
									<button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="ace-icon fa fa-check"></i> Submit</button>
									<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
								 </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
<script type="text/javascript">

function save(){
	var studystime = $('#studystime').val();
	
	var studyetime = $('#studyetime').val();
	var studentid = <?=$id?>;
	var type = <?=$type?>;
	var term = <?=$term?>;
	window.location.href='/master/student/student/get_student_score_download?studentid='+studentid+'&type='+type+'&term='+term+'&studystime='+studystime+'&studyetime='+studyetime;
}
</script>
