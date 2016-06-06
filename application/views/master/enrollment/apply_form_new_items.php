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
	申请表设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green">项的修改
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>
					<form class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/apply_form/save_items" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单类型:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="clearfix">
								<?=$formType[$result['formType']]?>
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单名称:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="formTitle" id="formTitle" value="<?=!empty($result['formTitle'])?$result['formTitle']:''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单ID:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="formID" id="formID" value="<?=!empty($result['formID'])?$result['formID']:''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<?php if(!empty($result['formType']) && $result['formType'] == 6){?>
						<div class="form-group" >
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">PHP数组:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="phpArrar" id="phpArrar" value="<?=!empty($result['phpArrar'])?$result['phpArrar']:''?>"  class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<?php }?>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="line" id="line" value="<?=!empty($result['line'])?$result['line']:''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<!--添加-->
						<div class="form-group" id="additem" style="display:<?=!empty($result['formType']) && in_array($result['formType'], array(5,4,6))?'':'none'?>">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">添加值:</label>
							
							<div class="col-xs-12 col-sm-4">
								<div class="clearfix">
								<?php if(!empty($formitem)){
									foreach($formitem as $key => $val){
								?>
									<div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单分项:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="itemTitle[]" id="itemTitle" value="<?=!empty($val['itemTitle'])?$val['itemTitle']:''?>" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">子项值:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="formValue[]" id="formValue" value="<?=!empty($val['formValue'])?$val['formValue']:''?>" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="lines[]" id="lines" value="<?=!empty($val['line'])?$val['line']:'0'?>" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">onclick 控制项选择后自动生成（群组）:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<select name="ControlIDG[]" id="ControlIDG">
													<option value="">--Please Select--</option>
													<?php if(!empty($group)){
														foreach ($group as $keyg => $valueg) {									
														?>
															<option value="<?=$keyg?>" <?=!empty($val['ControlID']) && $val['ControlID'] == $keyg?'selected':''?>><?=$valueg?></option>

													<?php }}?>
													</select>
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">onclick 控制项选择后自动生成（项）:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<select name="ControlIDF[]" id="ControlIDF">
													<option value="">--Please Select--</option>
													<?php if(!empty($form)){
														foreach ($form as $keyf => $valuef) {									
														?>
															<option value="<?=$keyf?>"  <?=!empty($val['ControlID']) && $val['ControlID'] == $keyf?'selected':''?>><?=$valuef?></option>

													<?php }}?>
													</select>
													</div>
												</div>
										</div>
										<div class="actions"><a href="javascript:;" onclick="del(this)" class="red" title="删除"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>
									</div>
								<?php }}?>
									<div class="actions"  id="addvalue">
										<a href="javascript:add_value(<?=!empty($itemsid)?$itemsid:''?>,<?=!empty($tClass_id)?$tClass_id:''?>);" title="添加"><i class="ace-icon glyphicon glyphicon-plus"></i></a>
									</div>
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">描述:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<textarea name="des" style="width: 342px; height: 150px;"><?=!empty($result['des'])?$result['des']:''?></textarea>
								</div>
							</div>
						</div>

						<div class="space-2"></div>	
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">帮助:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<textarea name="formHelp" style="width: 342px; height: 150px;"><?=!empty($result['formHelp'])?$result['formHelp']:''?></textarea>
								</div>
							</div>
						</div>

						<div class="space-2"></div>	

					<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">属性:</label>
							
							<div class="col-xs-12 col-sm-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" class="ace" name="isHidden" id="isHidden" value="Y"  <?=!empty($result['isHidden']) && $result['isHidden'] == 'Y'?'checked':''?>>
										<span class="lbl">默认隐藏</span>
									</label>
									<label>
										<input type="checkbox" class="ace" name="isInput" id="isInput"  value="Y" <?=!empty($result['isInput']) && $result['isInput'] == 'Y'?'checked':''?> >
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
									<input type="text" name="cols" id="cols" value="<?=!empty($result['cols'])?$result['cols']:''?>"  class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
							<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">行:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="rows" id="rows" value="<?=!empty($result['rows'])?$result['rows']:''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<input type="hidden" name="topic_id" value="<?=!empty($result['topic_id'])?$result['topic_id']:''?>">
						<input type="hidden" name="Class_id" value="<?=!empty($itemsid)?$itemsid:''?>">
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
		var type = $('#type').val();
		if(type == 5 || type == 4 || type == 6){
			$('#additem').show();
		}else{
			$('#additem').hide();
		}
	}

	function add_value(gid,tClass_id){
		$.ajax({
			type:'GET',
			url:'/master/enrollment/apply_form/add_item?gid='+gid+'&tClass_id='+tClass_id,
			success:function(r){
				if(r.state == 1){

					$('#addvalue').before(r.data);
				}
			},
			dataType:'json'
		});
		
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


