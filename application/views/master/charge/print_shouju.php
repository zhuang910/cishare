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
						<form target="_blank" onsubmit="location.href='/master/charge/pay';" class="form-horizontal" id="validation-form" method="post" action="/master/pub_print/sdyinc_print/print_shouju" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">选择模板:</label>

							<div class="col-xs-12 col-sm-5">
								<div class="input-group">
									<select id='print_tempid' name="print_tempid">
									<?php if(!empty($info)){?>
											<option value="0">请选择</option>
										<?php foreach($info as $k=>$v){?>
											<option value="<?=$v['id']?>"><?=$v['name']?></option>
										<?php }?>
									<?php }?>
									</select>
                                    <a href="javascript:;" onclick="$('#validation-form').submit();">打印</a>
								</div>
							</div>

						</div>
						<div class="space-2"></div>
						<input type='hidden' name='userid' value="<?=$userid?>">
						<input type='hidden' name='paid_in' value="<?=$paid_in?>">
						<input type='hidden' name='proof_number' value="<?=$proof_number?>">
						<input type='hidden' name='paytype' value="<?=$paytype?>">
						<input type='hidden' name='type' value="<?=$type?>">
						<div class="col-md-offset-3 col-md-9">
                            <a class="btn btn-info" href="/master/charge/pay/" data-last="Finish">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                完成打印
                            </a>
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
