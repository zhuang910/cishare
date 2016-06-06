<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<script src="<?=RES?>master/js/upload.js"></script>	
<div id="upload_file" type="upload_file" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">	
			<div class="widget-header">
				<h5 class="widget-title">上传202表</h5>
				<div class="widget-toolbar">
					<a id="closes" href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">

					<div class="widget-main" style="height: 130px">
						<form class="form-horizontal" id="jiangxuejin" method="post">
	                   		<div class="form-group">
								<label for="name" class="control-label col-xs-12 col-sm-3 no-padding-right">上传202:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" class="col-xs-12 col-sm-5" value="" id="file_path" name="file_path">
										<a href="javascript:swfupload('file_paths','file_path','文件上传',0,3,'doc,docx,jpg,png,gif',3,0,yesdo,nodo)" class="btn btn-warning btn-xs">
											<i class="ace-icon glyphicon glyphicon-search bigger-180 icon-only"></i>
										</a>
									</div>
								</div>
							</div>
							<input type="hidden" name="appid" value="<?=$appid?>">
							<div class="modal-footer center">
									<a id="send" onclick="save()" class="btn btn-sm btn-success" type="button"><i class="ace-icon fa fa-check"></i> Submit</a>
									<a data-dismiss="modal" class="btn btn-sm" type="button"><i class="ace-icon fa fa-times"></i> Cancel</a>
								 </div>
						<!-- 	<div class="col-md-offset-3 col-md-9">
								<a id="send" class="btn btn-info" onclick="save()" href="javascript:;">
									<i class="ace-icon fa fa-check bigger-110"></i>
									确认发送
								</a>
							</div> -->
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function save(){
		var data=$('#jiangxuejin').serialize();
		$.ajax({
			beforeSend:function (){
					$('#send').html('<i class="ace-icon fa fa-check"></i>Process...');
					$('#send').attr({
						disabled:'disabled',
					});
				},
			url: '/master/enrollment/change_offer_status/send_table_yes',
			type: 'POST',
			dataType: 'json',
			data: data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success();
				window.location.reload();

			}
			if(r.state==0){
				$('#send').html('<i class="ace-icon fa fa-check"></i>Submit');
					$('#send').removeAttr('disabled');
				pub_alert_error(r.info);
			}
		})
		.fail(function() {
			console.log("error");
		})
		
	}
</script>

