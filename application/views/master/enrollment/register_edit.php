<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '编辑' : '添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">在学管理</a>
	</li>
	<li ><a href='index'>学生管理</a></li>
	<li class="active">{$title_h3}学生</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<?php 


$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		学生管理
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->

										<div class="step-content pos-rel" id="step-container">
											<div class="step-pane active" id="step1">
												<h3 class="lighter block green">基本信息
													<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
														<i class="ace-icon fa fa-reply light-green bigger-130"></i>
													</a>
												</h3>	

												<div class="col-xs-12">
													<!-- #section:pages/profile.info -->
													<div class="profile-user-info profile-user-info-striped">
														<div class="profile-info-row">
															<div class="profile-info-name"> 中文的姓 </div>

															<div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="chfirstname" href="javascript:;"><?=$info->chfirstname?></a></span>
															</div>
															<div class="profile-info-name"> 中文的名 </div><div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="chlastname" href="javascript:;"><?=$info->chlastname?></a></span>
															</div>
															<div class="profile-info-name"> 汉语名 </div><div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="chname" href="javascript:;"><?=$info->chname?></a></span>
															</div>
														</div>
														<div class="profile-info-row">
															<div class="profile-info-name"> 英文的姓 </div>

															<div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="firstname" href="javascript:;"><?=!empty($info->firstname)?$info->firstname:''?></a></span>
															</div>
															<div class="profile-info-name"> 英语的名 </div><div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="lastname" href="javascript:;"><?=!empty($info->lastname)?$info->lastname:''?></a></span>
															</div>
															<div class="profile-info-name"> 英文名 </div><div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="enname" href="javascript:;"><?=!empty($info->enname)?$info->enname:''?></a></span>
															</div>
														</div>
														<div class="profile-info-row">
															<div class="profile-info-name"> 性别 </div>

															<div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="sex" data-pk="<?=$id?>" data-name="sex" href="javascript:;"><?=!empty($info)&&!empty($info->sex)&&$info->sex==1?'男':'女'?></a></span>
															</div>
															<div class="profile-info-name"> 出生日期 </div><div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="birthday" href="javascript:;"><?=!empty($info->birthday)?date('Y-m-d',$info->birthday):''?></a> 格式：2013-05-09</span>
															</div>
															<div class="profile-info-name"> 国家 </div><div class="profile-info-value">
																<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="nationality" data-pk="<?=$id?>" data-name="nationality" href="javascript:;"><?=$info->nationality?></a></span>
															</div>
														</div>
														<div class="profile-info-row">
																<div class="profile-info-name"> 宗教信仰 </div>
																<div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="religion" href="javascript:;"><?=$info->religion?></a></span>
																</div>
																<div class="profile-info-name"> 电话 </div><div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="tel" href="javascript:;"><?=$info->tel?></a></span>
																</div>
																<!--<div class="profile-info-name"> 是否黑人</div><div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="isnegro" data-pk="<?=$id?>" data-name="isnegro" href="javascript:;"><?=!empty($info)&&!empty($info->isnegro)&&$info->isnegro==1?'是':'否'?></a></span>
																</div>-->
														</div>
														<div class="profile-info-row">
																<div class="profile-info-name"> 手机 </div>
																<div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="mobile" href="javascript:;"><?=$info->mobile?></a></span>
																</div>
																<div class="profile-info-name"> Email </div><div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="email" href="javascript:;"><?=$info->email?></a></span>
																</div>
																<div class="profile-info-name">护照</div><div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="passport" href="javascript:;"><?=!empty($info)&&!empty($info->passport)?$info->passport:''?></a></span>
																</div>
														</div>
														<div class="profile-info-row">
																<div class="profile-info-name">专业 </div>
																<div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="major" data-pk="<?=$id?>" data-name="major" href="javascript:;"><?=$s_m?></a></span>
																</div>
																<div class="profile-info-name"> 学习期限开始 </div><div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="studystarttime" href="javascript:;"><?=!empty($info->studystarttime)?date('Y-m',$info->studystarttime):''?> </a> 格式：2013-05</span>
																</div>
																<div class="profile-info-name">学习期限结束</div><div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="studyendtime" href="javascript:;"><?=!empty($info->studyendtime)?date('Y-m',$info->studyendtime):''?></a> 格式：2013-05</span>
																</div>
														</div>
														<div class="profile-info-row">
																<div class="profile-info-name">学生类型 </div>
																<div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="studenttype" href="javascript:;"><?=$info->studenttype?></a></span>
																</div>
																<div class="profile-info-name">负责老师 </div>
																<div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="dutyteacher" href="javascript:;"><?=$info->dutyteacher?></a></span>
																</div>
																<div class="profile-info-name">202表序号 </div>
																<div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="ordernumber_202" href="javascript:;"><?=$info->ordernumber_202?></a></span>
																</div>
														</div>
														<div class="profile-info-row">
																<div class="profile-info-name">备注 </div>
																<div class="profile-info-value">
																	<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="remark" href="javascript:;"><?=$info->remark?></a></span>
																</div>
																
														</div>
													</div>
														
													<!-- /section:pages/profile.info -->
												</div>
											
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
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<script type="text/javascript">
$(function(){
	sex="<?=!empty($info)&&!empty($info->sex)&&$info->sex==1?'男':'女'?>";
	select_nationlity="<?=$select_nationlity?>";
	s_m="<?=$s_m?>";
	$('a[upload-config="true"]').editable({
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>register/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    }); 
       $('a[upload-config="nationality"]').editable({
    	type:'select',
    	 prepend: select_nationlity,
    	source: [
    		<?php foreach($nationality as $k=>$v):?>
            {value: <?=empty($k)?0:$k?>, text: '<?=$v?>'},
            <?php endforeach;?>
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>register/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });
	
	    $('a[upload-config="major"]').editable({
    	type:'select',
    	 prepend: s_m,
    	source: [
    		<?php foreach($major as $k=>$v):?>
            {value: <?=empty($k)?0:$k?>, text: '<?=$v?>'},
            <?php endforeach;?>
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>register/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });
  $('a[upload-config="sex"]').editable({
    	type:'select',
    	 prepend: sex,
    	source: [
    		{value: 1, text: '男'},
            {value: 2, text: '女'}
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>register/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });
  
})
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						mail: {
							required: true,
							email:true
						},
					
						paperstype: {
							required: true
						},
						teachername: {
							required: true
						},
						phone: {
							required: true,
							
						},
			
						
					},
			
					messages: {
						
						mail: {
							required: "请输入一个有效的电子邮箱.",
							email: "Please provide a valid email."
						},
						paperstype: {
							required: "请输入一个护照类型",
						},
					
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
								window.location.href="<?=$zjjp?>student/student";
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
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.input-sm').datepicker({
		autoclose: true,
		todayHighlight: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>
<script type="text/javascript">
jQuery(function($) {
		var cucaseditor = ['#editor1'];
		$.each(cucaseditor,function(i,v){
			$(v).ace_wysiwyg({
					toolbar:
					[
						{
							name:'font',
							title:'Custom tooltip',
							values:['Some Font!','Arial','Verdana','Comic Sans MS','Custom Font!']
						},
						null,
						{
							name:'fontSize',
							title:'Custom tooltip',
							values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text'} 
						},
						null,
						{name:'bold', title:'Custom tooltip'},
						{name:'italic', title:'Custom tooltip'},
						{name:'strikethrough', title:'Custom tooltip'},
						{name:'underline', title:'Custom tooltip'},
						null,
						'insertunorderedlist',
						'insertorderedlist',
						'outdent',
						'indent',
						null,
						{name:'justifyleft'},
						{name:'justifycenter'},
						{name:'justifyright'},
						{name:'justifyfull'},
						null,
						{
							name:'createLink',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-purple',
							button_text:'Custom TEXT'
						},
						{name:'unlink'},
						null,
						{
							name:'insertImage',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-inverse',
							//choose_file:false,//hide choose file button
							button_text:'Set choose_file:false to hide this',
							button_insert_class:'btn-pink',
							button_insert:'Insert Image'
						},
						null,
						{
							name:'foreColor',
							title:'Custom Colors',
							values:['red','green','blue','navy','orange'],
							/**
								You change colors as well
							*/
						},
						/**null,
						{
							name:'backColor'
						},*/
						null,
						{name:'undo'},
						{name:'redo'},
						null,
						'viewSource'
					],
					//speech_button:false,//hide speech button on chrome
					
					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
					
				}).prev().addClass('wysiwyg-style2');
		});
				

				
				
	});
			

</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>