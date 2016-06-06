<?php
$r=!empty($result)?'编辑项':'添加项';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">申请设置</a>
	</li>
	<li class="active">
		<a href="/master/enrollment/apply_form">申请表设置</a>
	</li>
	<li class="active">{$r}</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
 <script src="<?=RES?>master/js/jquery.validate.min.js"></script>
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	<?=$r?>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green"><?=!empty($result)?'编辑项':'添加项'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>
					<form class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/apply_form/save_add_group_item" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单类型:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="clearfix">
									
								<select name="formType" id="formType" onchange="create_item()">
									<option value="">--请选择--</option>
									<?php foreach ($formType as $key => $value) { ?>
										<option value="<?=$key?>"><?=$value?></option>
									<?php }?>
								</select>
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单名称:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text"  name="formTitle" id="formTitle" value="" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单ID:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text"  name="formID" id="formID" value="" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<div class="form-group" id="phparray_add" style="display:none;">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">PHP数组:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text"   name="phpArrar" id="phpArrar" value="" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text"  name="line" id="line" value="" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<!--添加-->
						<div class="form-group" id="additem" style="display:none">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">添加值:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="clearfix">
									<div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单分项:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="itemTitle[]" id="itemTitle" value="" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">子项值:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="formValue[]" id="formValue" value="" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="lines[]" id="lines" value="" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="actions"><a href="javascript:;" onclick="del(this)" class="red" title="删除"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>
									</div>
									<div class="actions"  id="addvalue">
										<a href="javascript:add_value();" title="添加"><i class="ace-icon glyphicon glyphicon-plus"></i></a>
									</div>
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">描述:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<textarea name="des" style="width: 342px; height: 150px;"></textarea>
								</div>
							</div>
						</div>

						<div class="space-2"></div>	
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">帮助:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<textarea name="formHelp" style="width: 342px; height: 150px;"></textarea>
								</div>
							</div>
						</div>

						<div class="space-2"></div>	

					<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">属性:</label>
							
							<div class="col-xs-12 col-sm-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" class="ace" name="isHidden" id="isHidden"   value="Y" >
										<span class="lbl">默认隐藏</span>
									</label>
									<label>
										<input type="checkbox" class="ace" name="isInput" id="isInput"  value="Y" >
										<span class="lbl">是否必填</span>
									</label>
								</div>
							</div>
						</div>

						<div class="space-2"></div>
							<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">宽:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="cols" id="cols" value=""  class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
							<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">行:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="rows" id="rows" value="" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<input type="hidden" name="Class_id" value="<?=!empty($Class_id)?$Class_id:''?>">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" data-last="Finish">
								<i class="ace-icon fa fa-check bigger-110"></i>
									Submit
							</button>
							<button class="btn" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
									Reset
							</button>
						</div>
						
						
						</form>
				</div>
			</div>

			<!-- /section:plugins/fuelux.wizard.container -->
		</div>
	</div>
</div>
		<!--[if lte IE 8]>
		  <script src="<?=RES?>/master/js/excanvas.min.js"></script>
		<![endif]-->
		<!-- ace scripts -->
		<script src="<?=RES?>master/js/ace-extra.min.js"></script>
		<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
		<script src="<?=RES?>/master/js/ace.min.js"></script>
<script type="text/javascript">
	function create_item () {
		var formType = $('#formType').val();
		if(formType == 5 || formType == 4 || formType == 6){
			$('#additem').show();
		}else{
			$('#additem').hide();
		}

		if(formType == 6){
			$('#phparray_add').show();
		}else{
			$('#phparray_add').hide();
		}
	}

	function add_value(){
		var html = '';
		html += '<div><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单分项:</label>';
		html +='<div class="col-xs-12 col-sm-4"><div class="clearfix"><input type="text" name="itemTitle[]" id="itemTitle" value="" class="col-xs-12 l-sm-5"/></div></div></div>';
		html += '<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">子项值:</label><div class="col-xs-12 col-sm-9"><div class="clearfix">';
		html += '<input type="text" name="formValue[]" id="formValue" value="" class="col-xs-12 col-sm-5" /></div></div></div>';
		html += '<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label><div class="col-xs-12 col-sm-9"><div class="clearfix">';
		html += '<input type="text" name="lines[]" id="lines" value="" class="col-xs-12 col-sm-5" /></div></div></div>';
		html += '<div class="actions"><a href="javascript:;" onclick="del(this)" class="red" title="删除"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div></div>';
		$('#addvalue').before(html);
		
	}

	function del(t){
		var p = $(t).parent().parent();
		r = confirm('您确定要删除吗？');
		if(r){
			p.hide(600,function(){
						p.remove();
					});
		}
	}
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						formTitle: {
							required: true
						},
						formID: {
							required: true
						},
						
					},
			
					messages: {
						
						
						formTitle: "请填写名称",
						formID:'请指定ID',
						
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
						var host=window.location.host;
						
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success()
								//setTimeout(window.location.reload(),3000);
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

<?php $this->load->view('master/public/footer');?>

