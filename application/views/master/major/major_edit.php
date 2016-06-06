<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑专业':'添加专业';
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
	<li><a href="index">专业设置</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>	
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<link rel="stylesheet" href="<?=RES?>master/css/chosen.css">
<script src="<?=RES?>master/js/chosen.jquery.min.js"></script>
<?php 

$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		专业设置
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
				<h3 class="lighter block green"><?=!empty($info)?'编辑专业':'添加专业'?>
						<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
							<i class="ace-icon fa fa-reply light-green bigger-130"></i>
						</a>
					</h3>
					
					<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>major/major/<?=$form_action?>">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业名称(汉语):</label>
							<input type="hidden" name="id" value="<?=!empty($info) ? $info->id : ''?>">
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业名称（英文）:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="englishname" name="englishname" value="<?=!empty($info) ? $info->englishname : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						
						<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">所属院系:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<select id="facultyid" class="input-medium valid" name="facultyid" aria-required="true" aria-invalid="false">
										<option value="0">——请选择——</option>
											<?php foreach($fdata as $k=>$v):?>
											<option value="<?php echo $v->id?>" <?=!empty($info)&&$v->id==$info->facultyid ? 'selected' :''?>><?php echo $v->name?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
						</div>
						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">编号:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="major_number" name="major_number" value="<?=!empty($info) ? $info->major_number : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">学位类型:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<select id="degree" class="input-medium valid" name="degree" aria-required="true" aria-invalid="false">
										<option value="0">——请选择——</option>
											<?php foreach($degree as $v):?>
												<option value="<?=$v['id']?>" <?=!empty($info)&&$v['id']==$info->degree ? 'selected' :''?>><?=$v['title']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group" id="t">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学期数:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input onchange="tuition()" type="text" id="termnum" name="termnum" value="<?=!empty($info) ? $info->termnum : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<?php if(!empty($info)&&!empty($info_tuition)&&!empty($info->termnum)):?>
							<?php for($i=1;$i<=$info->termnum;$i++):?>
								<?php 
									$tuition='';
									foreach ($info_tuition as $k => $v) {
										if($v['term']==$i){
											$tuition=$v['tuition'];
										}
									}
								?>
								<div class="xuefei">
									<div class="space-2"></div>
									<div class="form-group" id="t">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">第<?=$i?>学期学费:</label>

										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" id="total_tuition<?=$i?>" name="total_tuition[<?=$i?>]" value="<?=$tuition?>"class="col-xs-12 col-sm-5" />
											</div>
										</div>
									</div>
								</div>
							<?php endfor;?>
						<?php endif;?>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">学期跨度:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" name="termdays" id="termdaysl" value="<?=!empty($info) ? $info->termdays : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
					
						<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">关联奖学金:</label>
					
					<div class="col-xs-12 col-sm-4">
					<select multiple="" class="chosen-select" id="scholarship" data-placeholder="" name="scholarship[]">
					<?php foreach($scholarship as $k => $v){?>
						<option value="<?=$k?>" <?=!empty($info->scholarship) && in_array($k, explode(',', $info->scholarship)) ? 'selected' : '' ?>><?=$v?></option>
					<?php }?>
					</select>
					
					
					
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right">是否是精品课程:</label>
								<div class="col-xs-12 col-sm-9">
									<div>
										<label class="line-height-1 blue">
											<input class="ace" type="radio" value="1"  <?=!empty($info) && $info->ispriority == 1 ? 'checked' : ''?> name="ispriority">
											<span class="lbl"> 是</span>
										</label>
									</div>
									<div>
										<label class="line-height-1 blue">
											<input class="ace" type="radio" <?=!empty($info) && $info->ispriority == 0 ? 'checked' : ''?> value="0" name="ispriority">
											<span class="lbl"> 否</span>
										</label>
									</div>
								</div>
							</div>
				<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right">招生季节:</label>
								<div class="col-xs-12 col-sm-9">
									<div>
										<label class="line-height-1 blue">
											<input class="ace" type="radio" checked="checked" value="1"  <?=!empty($info) && $info->stage == 1 ? 'checked' : ''?> name="stage">
											<span class="lbl"> 秋季</span>
										</label>
									</div>
									<div>
										<label class="line-height-1 blue">
											<input class="ace" type="radio" <?=!empty($info) && $info->stage == 2 ? 'checked' : ''?> value="0" name="stage">
											<span class="lbl"> 春季</span>
										</label>
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
						
						<div class="col-md-offset-3 col-md-9">
							<button id="tijiao" class="btn btn-info" data-last="Finish">
								<i class="ace-icon fa fa-check bigger-110"></i>
									提交
							</button>
							<button class="btn" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
									重置
							</button>
						</div>
						
						
						</form>
				</div>
			</div>

			<!-- /section:plugins/fuelux.wizard.container -->
		</div>
	</div>
</div>
		
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!-- script -->
<script type="text/javascript">
$('.chosen-select').chosen({allow_single_deselect:true}); 
				//resize the chosen on window resize
				$(window).on('resize.chosen', function() {
					var w = $('.chosen-select').parent().width();
					$('.chosen-select').next().css({'width':w});
				}).trigger('resize.chosen');
			
				$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#type').addClass('tag-input-style');
					 else $('#type').removeClass('tag-input-style');
				});
				
				$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#country').addClass('tag-input-style');
					 else $('#country').removeClass('tag-input-style');
				});
				
				$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#purpose').addClass('tag-input-style');
					 else $('#purpose').removeClass('tag-input-style');
				});
				
				$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#participant').addClass('tag-input-style');
					 else $('#participant').removeClass('tag-input-style');
				});
</script>
<script type="text/javascript">
function alll(){
	if($("#all").attr("checke") == "true"){
		  alert(1);
	  }else{
	  	  alert(2);
	  }
}
function tuition(){
	$('.xuefei').remove();
	var termnum=$('#termnum').val();
	for(termnum;0<termnum;termnum--){
		var html='<div class="xuefei"><div class="space-2"></div><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">第'+termnum+'学期学费:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text" id="total_tuition'+termnum+'" name="total_tuition['+termnum+']" "class="col-xs-12 col-sm-5" /></div></div></div></div>';
		// var num =i-1;
		// var xufei=$('#xufei'+num);
		// if(xufei!=undefined){
		// 	xufei.after(html);
		// }else{
			$('#t').after(html);
		// }
	}

}
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						major_number: {
							required: true,
							
						},
	
						englishname: {
							required: true
						},
						name: {
							required: true
						},
						
						degree: {
							required: true,
							
						},
						facultyid: {
							required: true
						},
						
						
						
						state: 'required',
						
					},
			
					messages: {
						name:{
							required:"请输入专业名称",
						},
						englishname: {
							required: "请输入英文名称",
							
						},
						major_number:{
							required:"请输入专业编号",
						},
						degree:{
							required:"请选择学位类型",
						},
					
						
						facultyid: {
							required: "请输入该专业所属院系",
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
							// beforeSend:function (){
							// 	$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>正在提交');
							// 	$('#tijiao').attr({
							// 		disabled:'disabled',
							// 	});
							// },
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								// window.location.href="<?=$zjjp?>major/major";
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



<!-- end script -->
<?php $this->load->view('master/public/footer');?>