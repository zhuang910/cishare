<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑班级':'添加班级';
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
	<li><a href="/master/major/major">专业设置</a></li>
	<li><a href="javascript:history.back();">班级管理</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!-- bootstrap & fontawesome -->
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
		班级管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
										<div class="step-content pos-rel" id="step-container">
											<div class="step-pane active" id="step1">
												<h3 class="lighter block green">添加班级
												<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
													<i class="ace-icon fa fa-reply light-green bigger-130"></i>
												</a>
												</h3>
												<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>squad/<?=$form_action?>">
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">班级名称:</label>
														<input type="hidden" name="id" value="<?=!empty($info) ? $info->id : ''?>">
														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" id="name" name="name" value="<?=!empty($info) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
													<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">所属专业:</label>
															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<select id="majorid" class="input-medium valid" name="majorid" aria-required="true" aria-invalid="false" onchange="handle()">
																	<option value="<?=$mdata->id?>"><?=$mdata->name?></option>
																	</select>
																</div>
															</div>
													</div>
													<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">班主任:</label>
															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<select id="teacher" class="input-medium valid" name="teacher" aria-required="true" aria-invalid="false">
																	<option value="0">请选择</option>
																	<?php foreach ($teacher_name as $key => $value):?>
																	<option value="<?=$value['id']?>" <?=!empty($info) && $value['id']==$info->teacher ? 'selected':'';?>><?=$value['name']?></option>
																	<?php endforeach;?>
																	</select>
																</div>
															</div>
													</div>
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文名称:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" id="englishname" name="englishname" value="<?=!empty($info) ? $info->englishname : ''?>" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
														<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">当前学期:</label>
															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<select id="nowterm" class="input-medium valid" name="nowterm" aria-required="true" aria-invalid="false">
																	
																		<?php for($i=1;$i<=$mdata->termnum;$i++):?>
																		<option value="<?php echo $i?>" <?=!empty($info)&&$i==$info->nowterm ? 'selected' :''?>><?php echo '第'.$i.'学期'?></option>
																		<?php endfor?>
																	</select>
																</div>
															</div>
													</div>
												
												
													<div class="space-2"></div>
										<!-- 			<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学期跨度:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" id="spacing" name="spacing" value="<?=!empty($info) ? $info->spacing : ''?>" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>
													<div class="space-2"></div> -->
													<!--
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开班时间:</label>
														<div class="input-group">
															<!--<input id="classtime" style="width:200px;" value="<?=!empty($info) ? date('Y-m-d',$info->classtime) : ''?>" name="classtime" class="form-control date-picker" type="text" data-date-format="dd-mm-yyyy">
														

																<input type="text" data-date-format="yyyy-mm-dd" id="createtime" class="form-control date-picker" name="createtime" value="">
															<span class="input-group-addon">
																<i class="fa fa-calendar bigger-110"></i>
															</span>
														</div>
													</div>
													-->
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开班时间:</label>

														<div class="col-xs-12 col-sm-4">
															<div class="input-group">
																<input type="text" data-date-format="yyyy-mm-dd" id="classtime" class="form-control date-picker" name="classtime" value="<?=!empty($info) ? date('Y-m-d',$info->classtime) : ''?>">
																<span class="input-group-addon">
																	<i class="fa fa-calendar bigger-110"></i>
																</span>
															</div>
														</div>
													</div>
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">人数上限:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" name="maxuser" id="maxuser" value="<?=!empty($info) ? $info->maxuser : ''?>" class="col-xs-12 col-sm-6" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right">是否已结束</label>
															<div class="col-xs-12 col-sm-9">
																<div>
																	<label class="line-height-1 blue">
																		<input class="ace" type="radio" value="1"  <?=!empty($info) && $info->state == 1 ? 'checked' : ''?> name="state">
																		<span class="lbl"> 未结束</span>
																	</label>
																</div>
																<div>
																	<label class="line-height-1 blue">
																		<input class="ace" type="radio" <?=!empty($info) && $info->state == 0 ? 'checked' : ''?> value="0" name="state">
																		<span class="lbl"> 已结束</span>
																	</label>
																</div>
															</div>
														</div>
													
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

													<div class="datepicker datepicker-dropdown dropdown-menu datepicker-orient-left datepicker-orient-bottom" style="display: none; top: 1966.47px; left: 222.917px;">
													<div class="datepicker-days" style="display: block;">
													<table class=" table-condensed">
													<thead>
													<tr>
													<th class="prev" style="visibility: visible;">«</th>
													<th class="datepicker-switch" colspan="5">August 2014</th>
													<th class="next" style="visibility: visible;">»</th>
													</tr>
													<tr>
													<th class="dow">Su</th>
													<th class="dow">Mo</th>
													<th class="dow">Tu</th>
													<th class="dow">We</th>
													<th class="dow">Th</th>
													<th class="dow">Fr</th>
													<th class="dow">Sa</th>
													</tr>
													</thead>
													<tbody>
													<tr>
													<td class="old day">27</td>
													<td class="old day">28</td>
													<td class="old day">29</td>
													<td class="old day">30</td>
													<td class="old day">31</td>
													<td class="day">1</td>
													<td class="day">2</td>
													</tr>
													<tr>
													<td class="day">3</td>
													<td class="today day">4</td>
													<td class="day">5</td>
													<td class="day">6</td>
													<td class="day">7</td>
													<td class="day">8</td>
													<td class="day">9</td>
													</tr>
													<tr>
													<td class="day">10</td>
													<td class="day">11</td>
													<td class="day">12</td>
													<td class="day">13</td>
													<td class="day">14</td>
													<td class="day">15</td>
													<td class="day">16</td>
													</tr>
													<tr>
													<td class="day">17</td>
													<td class="day">18</td>
													<td class="day">19</td>
													<td class="day">20</td>
													<td class="day">21</td>
													<td class="day">22</td>
													<td class="day">23</td>
													</tr>
													<tr>
													<td class="day">24</td>
													<td class="day">25</td>
													<td class="day">26</td>
													<td class="day">27</td>
													<td class="day">28</td>
													<td class="active day">29</td>
													<td class="day">30</td>
													</tr>
													<tr>
													<td class="day">31</td>
													<td class="new day">1</td>
													<td class="new day">2</td>
													<td class="new day">3</td>
													<td class="new day">4</td>
													<td class="new day">5</td>
													<td class="new day">6</td>
													</tr>
													</tbody>
													<tfoot>
													<tr>
													<th class="today" colspan="7" style="display: none;">Today</th>
													</tr>
													<tr>
													<th class="clear" colspan="7" style="display: none;">Clear</th>
													</tr>
													</tfoot>
													</table>
													</div>
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
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>	

<script type="text/javascript">
$(document).ready(function(){

		
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						alias: {
							required: true,
							
						},
	
						englishname: {
							required: true
						},
						name: {
							required: true
						},
						termdays: {
							required: true
						},
						termnum: {
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
						alias:{
							required:"请输入专业别名",
						},
						degree:{
							required:"请选择学位类型",
						},
						termnum:{
							required:"请输入学期届数",
						},
						termdays: {
							required: "请输入学期的总天数",
							
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
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="<?=$zjjp?>squad?id=<?=$majorid?>";
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
function handle(){
	var data=$('#majorid').val();


		$.ajax({
			url: '<?=$zjjp?>squad/get_nowterm/'+data,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			$("#nowterm").empty();
			 $.each(r.data, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data[i]+'学期').attr("value",r.data[i]);
			 	  $("#nowterm").append(opt); 
			  	//alert(r.data[i]);  
             });
			//alert(r.data);
		
		})
		.fail(function() {

			alert('error');
		})
						

	
}
</script>

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
	
<!-- end script -->
<?php $this->load->view('master/public/footer');?>