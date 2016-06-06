<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="widget-header">
								<h5 class="widget-title"><?=$title_h3?>评教类</h5>

								<div class="widget-toolbar">
									<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
										<i class="1 ace-icon bigger-125 fa fa-remove"></i>
									</a>
								</div>
							</div>
							<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>evaluate_set/<?=$form_action?>">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">类名称(汉语):</label>
							<input type="hidden" name="id" value="<?=!empty($info) ? $info->id : ''?>">
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">类名称(英文):</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="enname" name="enname" value="<?=!empty($info) ? $info->enname : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">类型:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<div class="clearfix">
										<select id="type"  class="input-medium valid" name="type" aria-required="true" aria-invalid="false">
											<option value="" >-请选择-</option>
											<option value="1" <?=!empty($info->type)&&$info->type==1?'selected':''?>>单选</option>
											<option value="2" <?=!empty($info->type)&&$info->type==2?'selected':''?> >文本</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="orderby" name="orderby" value="<?=!empty($info) ? $info->orderby : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
					<!-- 	<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">评教开始时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="starttime" class="date-picker" name="starttime" value="<?=!empty($info->starttime)?date('Y-m-d',$info->starttime):''?>">
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>

						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">评教结束时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="endtime" class="date-picker" name="endtime" value="<?=!empty($info->endtime)?date('Y-m-d',$info->endtime):''?>">
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="space-2"></div> -->
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
							<div class="col-xs-12 col-sm-4">
								<select id="state" class="form-control" name="state">
									<option value="1" <?=!empty($info) && $info->state == 1?'selected':''?>>启用</option>
									<option value="0"  <?=!empty($info) && $info->state == 0?'selected':''?>>禁用</option>
									
								</select>
							</div>
						</div>
						<div class="space-2"></div>
						<input type="hidden" name="id" value="<?=!empty($info) ? $info->id : ''?>">
						<div class="modal-footer center">
							<button id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
								提交
							</button>
							<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
								取消
							</button>
						</div>
						</form>
					</div>
				</div>
			</div>
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: false,
		rules: {
			name: {
				required: true,
				
			},

			enname: {
				required: true
			},
			type: {
				required: true,
				
			},
			orderby: {
				required: true,
				
			},
			state: 'required',
		},

		messages: {
			name:{
				required:"请输入汉语标题",
			},
			enname: {
				required: "请输入英文标题",
				
			},
			type:{
				required:"请选择类型",
			},
			orderby:{
				required:"请填入排序",
			},
			state: "请选择状态",
		},
		highlight: function (e) {
			$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
		},

		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
			$(e).remove();
		},

		errorPlacement: function (error, element) {
			if(element.is(':checkbox') || element.is(':radio')) {
				var controls = element.closest('div[class*="col-"]');
				if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
				else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
			}
			else if(element.is('.select2')) {
				error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
			}
			else if(element.is('.chosen-select')) {
				error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
			}
			else error.insertAfter(element.parent());
		},

		submitHandler: function (form) {
			var data=$(form).serialize();

	
			$.ajax({
				beforeSend:function (){
					$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>正在提交');
					$('#tijiao').attr({
						disabled:'disabled',
					});
				},
				url: $(form).attr('action'),
				type: 'POST',
				dataType: 'json',
				data: data,
			})
			.done(function(r) {
				if(r.state==1){
					pub_alert_success();
					window.location.href="<?=$zjjp?>evaluate_set";
				}else{

					pub_alert_error();
				}
			})
			.fail(function() {

				pub_alert_error();
			})
		}
	});
});
</script>
<!--日期插件-->
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

