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
		<a href="javascript:;">评教管理</a>
	</li>
	<li>
		<a href="javascript:;">评教设置</a>
	</li>
	<li>编辑项信息</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>

<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 

$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		编辑项信息
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
				<div class="step-content pos-rel" id="step-container">
					<div class="step-pane active" id="step1">
						<h3 class="lighter block green">编辑项信息
							<a href="/master/evaluate/evaluate_item?classid=<?=$classid?>" title='返回上一级' class="pull-right ">
								<i class="ace-icon fa fa-reply light-green bigger-130"></i>
							</a>
						</h3>
						
						<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>evaluate_item/update_info">
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="title" name="title" value="<?=!empty($info) ? $info->title : ''?>" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>
							<div class="space-2"></div>
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">答案项:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<div class="clearfix">
										<select id="answer_num" onchange="answer_num_change()" class="input-medium valid" name="answer_num" aria-required="true" aria-invalid="false">
											<option value="4" <?=!empty($info->answer_num)&&$info->answer_num==4?'selected':''?>>4</option>
											<option value="5" <?=!empty($info->answer_num)&&$info->answer_num==5?'selected':''?> >5</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="space-2"></div>
							<?php 
								if(!empty($info->answer_score)){
									$answer_score=json_decode($info->answer_score,true);
								}
							?>
							<div class="form-group">
										<label for="platform" class="control-label col-xs-12 col-sm-3 no-padding-right">答案:</label>
										<div class="col-xs-12 col-sm-9">
											<div id="correct_answers" class="clearfix">
												<span class="lbl">非常好</span>
												<input type="text" style="width:50px;"value="<?=!empty($answer_score[1])?$answer_score[1]:''?>" name="answer_score[1]" placeholder="分数" >
												</label>
												&nbsp;&nbsp;&nbsp;
												<label>
												<span class="lbl">好</span>
												<input type="text" style="width:50px;" value="<?=!empty($answer_score[2])?$answer_score[2]:''?>" name="answer_score[2]" placeholder="分数" >
												</label>
												&nbsp;&nbsp;&nbsp;
												<label>
												<span class="lbl">一般</span>
												<input type="text" style="width:50px;" value="<?=!empty($answer_score[3])?$answer_score[3]:''?>" name="answer_score[3]" placeholder="分数" >
												</label>
												&nbsp;&nbsp;&nbsp;
												<label id="insert">
												<span class="lbl">差</span>
												<input type="text" style="width:50px;" value="<?=!empty($answer_score[4])?$answer_score[4]:''?>" name="answer_score[4]" placeholder="分数" >
												</label>
												<?php if(!empty($info->answer_num)&&$info->answer_num==5):?>
													&nbsp;&nbsp;&nbsp;
													<label id="show_hide">
													<span class="lbl">差</span>
													<input type="text" style="width:50px;" value="<?=!empty($answer_score[5])?$answer_score[5]:''?>" name="answer_score[5]" placeholder="分数" >
													</label>
												<?php endif;?>
											</div>
										</div>
									</div>
									<input type="hidden" name="itemid" value="<?=!empty($itemid)?$itemid:''?>">
									<input type="hidden" name="site_language" value="<?=!empty($_SESSION['language']) ? $_SESSION['language'] : 'cn'?>">
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
<!-- script -->
<script type="text/javascript">
function answer_num_change(){
	var answer=$('#answer_num').val();
	var str='&nbsp;&nbsp;&nbsp;<label id="show_hide"><span class="lbl">极差</span><input type="text" style="width:50px;" name="answer_score[5]" placeholder="分数" ></label>';
	if(answer==5){

		$('#insert').after(str);
	}
	if(answer==4){
		$('#show_hide').remove();
	}
}
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						englishname: {
							required: true
						},
						name: {
							required: true
						},
						orderby: {
							required: true
						},
						
						size: {
							required: true,
							
						},
					
						
						
						
						state: 'required',
						
					},
			
					messages: {
						name:{
							required:"请输入教室名称",
						},
						englishname: {
							required: "请输入教室英文名称",
							
						},
						orderby:{
							required:"请输入教室地址",
						},
						size:{
							required:"请输入教室容量",
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

							window.location.href="/master/evaluate/evaluate_item?classid=<?=$classid?>";
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