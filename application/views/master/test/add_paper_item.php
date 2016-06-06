<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑':'添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">测试管理</a>
	</li>
	<li>
		<a href="javascript:;">试卷管理</a>
	</li>
	<li><a href="index">试题项管理</a></li>
	<li>{$r}试题项</li>
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
$title_h3 = $uri4 == 'edit_paper_item' ? '修改' : '添加';
$form_action = $uri4 == 'edit_paper_item' ? 'update_paper_item' : 'insert_paper_item';

?>
<script src="<?=RES?>master/js/upload.js"></script>	
<?php $this->load->view('master/public/js_css_kindeditor');?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	添加试题项
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green">添加试题项
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="/master/test/topic/<?=$form_action?>">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">试题标题:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">考试题及选项:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<div style="display:none;" id="content_aid_box"></div>
								<textarea name="topic_answer" class='content'  id="topic_answer"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info) ? $info->topic_answer : ''?></textarea>
								
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">分数:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="score" name="score" value="<?=!empty($info) ? $info->score : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">试题类型:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<select onchange="chaanges()" id="topic_type" class="input-medium valid" name="topic_type" aria-required="true" aria-invalid="false">
								<option value="1" <?=!empty($info->topic_type)&&$info->topic_type==1?'selected':''?>>单选</option>
								<option value="2" <?=!empty($info->topic_type)&&$info->topic_type==2?'selected':''?>>多选</option>
							</select>
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">选项数量:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<select onchange="chaanges()" id="answer_num" class="input-medium valid" name="answer_num" aria-required="true" aria-invalid="false">
								<?php for($i=4;$i<=10;$i++):?>
									<option value="<?=$i?>" <?=!empty($info->answer_num)&&$info->answer_num==$i?'selected':''?>><?=$i?></option>
								<?php endfor;?>
							</select>
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">正确答案:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix" id="correct_answers">
						
						<?php if(!empty($info)&&$info->topic_type==1&&!empty($info->answer_num)&&!empty($info->one_correct_answer)):?>
							<?php 
								$allnum=65+$info->answer_num;
							?>
							<?php for($i=65;$i<$allnum;$i++):?>
								<?php
									$value=Chr($i);
								?>
								<label>
								<input class="ace" <?=!empty($info->one_correct_answer)&&$info->one_correct_answer==$value?'checked':''?> type="radio" value="<?=$value?>" name="one_correct_answer">
								<span class="lbl"> <?=$value?></span>
								</label>
								&nbsp;&nbsp;&nbsp;
							<?php endfor;?>
						<?php endif;?>
						<?php if(!empty($info)&&$info->topic_type==2&&!empty($info->answer_num)&&!empty($info->more_correct_answer)):?>
							<?php 
								$allnum=65+$info->answer_num;
							?>
							<?php for($i=65;$i<$allnum;$i++):?>
								<?php
									$value=Chr($i);
									$arr=json_decode($info->more_correct_answer);
									$checked="";
									foreach ($arr as $k => $v) {
										if($v==$value){
											$checked="checked";
										}
									}
								?>
								<label>
								<input class="ace" <?=$checked?> type="checkbox" value="<?=$value?>" name="more_correct_answer[]">
								<span class="lbl"> <?=$value?></span>
								</label>
								&nbsp;&nbsp;&nbsp;
							<?php endfor;?>
						<?php endif;?>
						<?php if(empty($info)):?>
								<label>
								<input class="ace" checked type="radio" value="A" name="one_correct_answer">
								<span class="lbl">A</span>
								</label>
								&nbsp;&nbsp;&nbsp;
								<label>
								<input class="ace" type="radio" value="B" name="one_correct_answer">
								<span class="lbl">B</span>
								</label>
								&nbsp;&nbsp;&nbsp;
								<label>
								<input class="ace" type="radio" value="C" name="one_correct_answer">
								<span class="lbl">C</span>
								</label>
								&nbsp;&nbsp;&nbsp;
								<label>
								<input class="ace" type="radio" value="D" name="one_correct_answer">
								<span class="lbl">D</span>
								</label>
								&nbsp;&nbsp;&nbsp;
								
						<?php endif;?>
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
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn btn-info">
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
<!-- ace scripts -->
<script src="/resource/master/js/ace-extra.min.js"></script>
<script src="/resource/master/js/ace-elements.min.js"></script>
<script src="/resource/master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<?php $this->load->view('master/public/js_kindeditor_create')?>
<script type="text/javascript">
function chaanges(){
	// alert(String.fromCharCode(65));
	var is=$('#topic_type').val();
	var num=parseInt($('#answer_num').val());
	var str='';
	$('#correct_answers').empty();
	var all_num=65+num;
	if(is==1){
		for(i=65;i<all_num;i++){
			var v=String.fromCharCode(i)
			str+='<label><input class="ace" type="radio" value="'+v+'" name="one_correct_answer"><span class="lbl">'+v+'</span></label>&nbsp;&nbsp;&nbsp;';
		}
		$('#correct_answers').append(str);
	}else if(is==2){
		for(i=65;i<all_num;i++){
			var v=String.fromCharCode(i)
			str+='<label><input class="ace" type="checkbox" value="'+v+'" name="more_correct_answer[]"><span class="lbl">'+v+'</span></label>&nbsp;&nbsp;&nbsp;';
		}
		$('#correct_answers').append(str);
	}
}
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						title: {
							required: true
						},
						desperation: {
							maxlength: 200,
						},
						state: 'required',
						
					},
			
					messages: {
						title:{
							required:"请输入标题",
						},
						desperation: {
							required: "最多输入200个字符",
							
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
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.history.back();
							}
							if(r.state==2){
								pub_alert_error(r.info);
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