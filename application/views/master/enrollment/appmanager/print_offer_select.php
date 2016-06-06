<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<link rel="stylesheet" href="/resource/master/css/datepicker.css" />
<script src="/resource/master/js/date-time/bootstrap-datepicker.min.js"></script>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content" style="height:400px;">
			<div class="widget-header">
				<h5 class="widget-title">选择模板</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 230px">
						<form class="form-horizontal" id="validation-form" method="post" action="/master/pub_print/sdyinc_print/offer" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">选择模板:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<select id='print_tempid' name="print_tempid">
									<?php if(!empty($info)){?>
											<option value="0">请选择</option>
										<?php foreach($info as $k=>$v){?>
											<option value="<?=$v['id']?>"><?=$v['name']?></option>
										<?php }?>
									<?php }?>
									</select>
								</div>
							</div>

						</div>
						<div class="space-2"></div>
						<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开学时间:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="studystime" class="form-control date-picker" name="studystime" value="">
											例如：2000-01-01
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学习结束时间:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="studyetime" class="form-control date-picker" name="studyetime" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>
								<input type="hidden" name="applyid" value="<?=$applyid?>">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" data-last="Finish">
							<i class="ace-icon fa fa-check bigger-110"></i>
								打印
							</button>
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
