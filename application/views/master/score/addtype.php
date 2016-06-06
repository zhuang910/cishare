<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="widget-header">
								<h5 class="widget-title"><?=$title_h3?>考试</h5>

								<div class="widget-toolbar">
									<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
										<i class="1 ace-icon bigger-125 fa fa-remove"></i>
									</a>
								</div>
							</div>
					<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>itemsetting/<?=$form_action?>">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">代号:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="code" name="code" value="<?=!empty($info) ? $info->code : ''?> " class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">考试名称(汉语):</label>
							<input type="hidden" name="id" value="<?=!empty($info) ? $info->id : ''?>">
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">考试名称(英文):</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="enname" name="enname" value="<?=!empty($info) ? $info->enname : ''?> "class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">考试占比:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="scores_of" name="scores_of" value="<?=!empty($info) ? $info->scores_of : ''?> "class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right">是否可用</label>
								<div class="col-xs-12 col-sm-9">
									<div>
										<label class="line-height-1 blue">
											<input class="ace" type="radio" value="1"  <?=!empty($info) && $info->state == 1 ? 'checked' : ''?> name="state">
											<span class="lbl"> 可用</span>
										</label>
									</div>
									<div>
										<label class="line-height-1 blue">
											<input class="ace" type="radio" <?=!empty($info) && $info->state == 0 ? 'checked' : ''?> value="0" name="state">
											<span class="lbl"> 停用</span>
										</label>
									</div>
								</div>
							</div>
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
			price: {
				required: true,
				
			},
			state: 'required',
		},

		messages: {
			name:{
				required:"请输入书籍汉语名称",
			},
			enname: {
				required: "请输入书籍英文名称",
				
			},
			price:{
				required:"请输入书籍单价",
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
					window.location.href="<?=$zjjp?>itemsetting";
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
