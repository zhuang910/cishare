<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">调换奖学金</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">

					<div class="widget-main" style="height: 330px">
						<form class="form-horizontal" id="jiangxuejin" method="post">
	                   		<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">奖学金列表:</label>
								
								<div class="col-xs-12 col-sm-4">
									<select id="scholarshipid" class="form-control" name="scholarshipid">
										<option value="0">-请选择-</option>
										<?php if(!empty($scholarship_info)){?>
											<?php foreach($scholarship_info as $k=>$v){?>
												<option value="<?=$v['id']?>"><?=$v['title']?></option>
											<?php }?>
										<?php }?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>
								
								<div class="col-xs-12 col-sm-4">
									<textarea name="remark"></textarea>
								</div>
							</div>
							<input type="hidden" name="id" value="<?=$id?>">
							<div class="col-md-offset-3 col-md-9">
								<a class="btn btn-info" onclick="save()" href="javascript:;">
									<i class="ace-icon fa fa-check bigger-110"></i>
									提交
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
	function save(){
		var data=$('#jiangxuejin').serialize();
		$.ajax({
			url: '/master/scholarship/change_scholarship_status/update_jiangxuejin',
			type: 'POST',
			dataType: 'json',
			data: data,
		})
		.done(function(r) {
			if(r.state==1){
				var sub={'id':r.data,'state':5};
				$.ajax({
					url: '/master/scholarship/change_scholarship_status/submit_app_status',
					type: 'POST',
					dataType: 'json',
					data: sub,
				})
				.done(function(r) {
					if(r.state==1){
						pub_alert_success();	
						window.location.reload();
					}
				})
				.fail(function() {
					console.log("error");
				})
			}
			if(r.state==0){
				pub_alert_error(r.info);
			}
		})
		.fail(function() {
			console.log("error");
		})
		
	}
</script>

