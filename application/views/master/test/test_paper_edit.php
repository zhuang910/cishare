<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">测试管理</a>
	</li>
	<li><a href="index">试卷管理</a></li>
	<li>{$title_h3}试卷</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	<?=$title_h3?>试卷
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<h3 class="lighter block green"><?=!empty($info)?'编辑试卷':'添加试卷'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>test_paper/<?=$form_action?>">
			
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">试卷名称:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文名称:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="enname" name="enname" value="<?=!empty($info) ? $info->enname : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right">适用范围</label>
					<div class="col-xs-12 col-sm-9">
						<div>
							<label class="line-height-1 blue">
								<input class="ace" onchange="bixiu()" checked type="radio" value="1"  <?=!empty($info) && $info->scope_all == 1 ? 'checked' : ''?> name="scope_all">
								<span class="lbl"> 全部</span>
							</label>
						</div>
						<div>
							<label class="line-height-1 blue">
								<input class="ace" onchange="xuanxiu()" type="radio" <?=!empty($info) && $info->scope_all == 0 ? 'checked' : ''?> value="0" name="scope_all">
								<span class="lbl"> 部分</span>
							</label>
						</div>
					</div>
				</div>
				
				<div class="space-2"></div>
				<div id='xuanxiu_div' <?=!empty($info)&&$info->scope_all==0?'':'style="display:none"'?> >
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学位:</label>
						
						<div class="col-xs-12 col-sm-4">
							<select id="degreeid" class="form-control" name="degreeid" onchange="degree()">
								<option value="0">-请选择-</option>
								<?php foreach($degree_info as $k=>$v):?>
									<?php 
										if(!empty($info) && $info->scope_all==0 &&!empty($info->degreeid) && $info->degreeid == $v['id']){
											$selected='selected';
										}else{
											$selected='';
										}
									?>
									<option value="<?=$v['id']?>" <?=$selected?>><?=$v['title']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="space-2"></div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业:</label>
						
						<div class="col-xs-12 col-sm-4">
							<select id="majorid" class="form-control" name="majorid">
							<option value="0">-请选择-</option>
								<?php if(!empty($info) && $info->scope_all==0 && !empty($info->majorid) && !empty($major_info)){?>
									<?php foreach($major_info as $k=>$v):?>
										<?php 
										if(!empty($info) && $info->scope_all==0 &&!empty($info->majorid) && $info->majorid == $v['id']){
											$selected='selected';
										}else{
											$selected='';
										}
									?>
										<option value="<?=$v['id']?>" <?=$selected?>><?=$v['name']?></option>
									<?php endforeach;?>
								<?php }?>
							</select>
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="state" class="form-control" name="state">
							<option value="1" <?=!empty($info) && $info->state == 1?'selected':''?>>启用</option>
							<option value="0"  <?=!empty($info) && $info->state == 0?'selected':''?>>禁用</option>
						</select>
					</div>
				</div>
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
				<div class="space-2"></div>
				<div class="col-md-offset-3 col-md-9">
					<button class="btn btn-info" data-last="Finish">
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
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
function xuanxiu(){
	$('#xuanxiu_div').removeAttr('style');
	$('#add_fanwei').removeAttr('style');
}
function bixiu(){
	$('#xuanxiu_div').attr({
		'style':'display:none;'
	});
}
	function sel_majorid(){
		var degreeid = $('#degreeid').val();
		
		if(degreeid != ''){
			$.ajax({
				url: '/master/basic/course/sel_majorid?degreeid='+degreeid,
				type: 'GET',
				dataType: 'json',
			})
			.done(function(r) {
				if(r.state == 1){
					$('#majorid').html('');
					$('#majorid').html(r.data);
				}
			})
			.fail(function() {
				console.log("error");
			})
			
			
		}
	}
function degree(){
	 
	var degreeid=$('#degreeid').val();
		 $("#majorid").empty();
		 $("#majorid").append("<option value='0'>—请选择—</option>"); 
		$.ajax({
			url: '/master/test/test_paper/get_maojor/'+degreeid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			if(r.state==1){
				 $.each(r.data, function(k, v) { 
				 	var opt = $("<option/>").text(v.name).attr("value",v.id);
				 	  $("#majorid").append(opt); 
				  });
			 }
			 if(r.state==0){
			 	pub_alert_error(r.info);
			 }
		})
		
						
}
</script>
<!-- script -->
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						enname: {
							required: true
						},
						name: {
							required: true
						},
						programid: {
							required: true
						},
						
						absenteeism: {
							required: true,
							
						},
						expel: {
							required: true,
							
						},
						hour: {
							required: true,
							
						},
						
						
						
						state: 'required',
						
					},
			
					messages: {
						name:{
							required:"请输入课程名称",
						},
						enname: {
							required: "请输入课程英文名称",
							
						},
						programid:{
							required:"请输入专业名称",
						},
						absenteeism:{
							required:"请输入缺勤通知线",
						},
						expel:{
							required:"请输入开除通知线",
						},
						hour:{
							required:"请输入课程课时",
						},
						
						state: "请选择教室可用状态",
						
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
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="<?=$zjjp?>test_paper";
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