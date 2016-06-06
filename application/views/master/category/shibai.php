<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">选课失败</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 230px">
						<form class="form-horizontal" id="validation-form" method="post" action="/master/student/electives/save_time" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<textarea name="remark" style="width:300px;height:150px;"><?=!empty($info['remark'])?$info['remark']:''?></textarea>
								</div>
							</div>

						</div>
						<input type="hidden" name="id" value="<?=$id?>">
						<div class="space-2"></div>
						<div class="col-md-offset-3 col-md-9">
							<a href="javascript:;" onclick="save_time()" class="btn btn-info"  data-dismiss="modal" aria-hidden="true" data-action="collapse">
								<i class="ace-icon fa fa-check bigger-110"></i>
									保存
							</a>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function save_time(){
	var data=$('#validation-form').serialize();
	$.ajax({
		url: '/master/student/elestu/shiabi',
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
 			window.location.reload();
		}else{
			pub_alert_error();
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
</script>
