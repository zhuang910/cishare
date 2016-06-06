<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<script src="<?=RES?>master/js/upload.js"></script>	


<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'ajax_edit_item' ? '修改' : '添加';
$form_action = $uri4 == 'ajax_edit_item' ? 'update_paper_item' : 'insert_paper_item';

?>
					<div class="modal-dialog" style="width:800px;">
						<div class="modal-content">
							<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">添加题目</h4></div>
							<!---->
								<form class="form-horizontal" id="validation-form" method="post" action="/master/test/test_paper/<?=$form_action?>">
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
									<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
									<input type="hidden" name="groupid" value="<?=!empty($groupid)?$groupid:''?>">
									<div class="modal-footer center">
										<button id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
											提交
										</button>
										<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
											取消
										</button>
									</div>
								</form>
							<!---->
					</div>
				</div>
			</div>
<?php $this->load->view('master/public/js_kindeditor_create')?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
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
								window.location.href="/master/test/test_paper/group_item?id=<?=$jump_id?>";
							}
							if(r.state==2){
								$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>提交');
								$('#tijiao').removeAttr('disabled');
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
<!--日期插件-->
