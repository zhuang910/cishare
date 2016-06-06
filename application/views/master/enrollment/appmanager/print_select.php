<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
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
						<form class="form-horizontal" id="validation-form" method="post" action="/master/student/electives/save_time" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">选择模板:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<select id='print_tempid'>
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
						<div class="col-md-offset-3 col-md-9">
							<a href="javascript:;" onclick="save_time()" class="btn btn-info"  data-dismiss="modal" aria-hidden="true" data-action="collapse">
								<i class="ace-icon fa fa-check bigger-110"></i>
									打印
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
	var print_tempid=$('#print_tempid').val();
	if(print_tempid==0){
		pub_alert_error('请选择模板');
		return false;
	}
	var url="/master/pub_print/sdyinc_print/apply?print_tempid="+print_tempid+"&applyid=<?=$applyid?>";
	window.open(url);

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
