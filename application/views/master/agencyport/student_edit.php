<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? 'Edit' : 'Add';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>HOME</a>
	</li>

	
	<li>
		Students
	</li>
	<li>{$title_h3}Student</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>

<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<?php 

$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		<?=$title_h3?>Student
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
<!-- #section:plugins/fuelux.wizard.container -->
					<div class="step-content pos-rel" id="step-container">
						<div class="step-pane active" id="step1">
							<h3 class="lighter block green"><?=!empty($info)?'Edit':'Add'?>
								<a href="javascript:history.back();" title='Back' class="pull-right ">
									<i class="ace-icon fa fa-reply light-green bigger-130"></i>
								</a>
							</h3>
							
					<form class="form-horizontal" id="validation-form" method="post" action="/master/agencyport/student_apply/save" enctype = 'multipart/form-data'>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email"><font color="red">*</font> Account(E-mail):</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="email" name="email" value="<?=!empty($info->email) ? $info->email : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="enname"><font color="red">*</font> Name:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="enname" name="enname" value="<?=!empty($info->enname) ? $info->enname : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password"><font color="red">*</font>Password:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="password" id="password" name="password" value="" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">Nationality:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<select id="nationality" class="input-medium valid" name="nationality" aria-required="true" aria-invalid="false">
											<?php if(!empty($nationality)){?>	
											<?php foreach($nationality as $k=>$v):?>
											<option value="<?=$k?>" <?=!empty($info)&&$k==$info->nationality ? 'selected' :''?>><?=$v?></option>
											<?php endforeach;?>
											<?php }?>
										</select>
									</div>
								</div>
						</div>
						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Passport No.:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="passport" name="passport" value="<?=!empty($info->passport) ? $info->passport : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						
						<div class="space-2"></div>
<!--
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Major:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
										<select name='courseid' style="width: 500px" id="courseid">
											<option value="0">--请选择 --</option>>
											<?php foreach($major_info as $item){ ?>
											<optgroup label="<?=$item['degree_title']?>">
											<?php foreach($item['degree_major'] as $item_info){ ?>
												<option value="<?=$item_info->id?>" <?//php if($item_info->id==$info->majorid){echo 'selected="selected"';}?>><?=$item_info->id?>--<?=$item_info->name?></option>
											<?php } ?>
											</optgroup>
											<?php } ?>
										</select>
								</div>
							</div>
						</div>
						
						
					
						
                        <div class="space-2"></div>
						
						
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="firstname"><font color="red">*</font>Last Name in English:</label>

                            <div class="col-xs-12 col-sm-9">
                                <div class="clearfix">
                                    <input type="text" id="firstname" name="firstname" value="<?=!empty($info->firstname) ? $info->firstname : ''?>" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>

                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="lastname"><font color="red">*</font>First Name in English:</label>

                            <div class="col-xs-12 col-sm-9">
                                <div class="clearfix">
                                    <input type="text" id="lastname" name="lastname" value="<?=!empty($info->lastname) ? $info->lastname : ''?>" class="col-xs-12 col-sm-5" />
                                </div>
                            </div>
                        </div>
						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="chfirstname">Last Name in Chinese:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="chfirstname" name="chfirstname" value="<?=!empty($info->chfirstname) ? $info->chfirstname : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="chlastname">First Name in Chinese:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="chlastname" name="chlastname" value="<?=!empty($info->chlastname) ? $info->chlastname : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Mobile Phone:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="mobile" name="mobile" value="<?=!empty($info->mobile) ? $info->mobile : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Telephone:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="tel" name="tel" value="<?=!empty($info->tel) ? $info->tel : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Passport Starting Date:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="validfrom" class="form-control date-picker" name="validfrom" value="<?=!empty($info->validfrom)?date('Y-m-d',$info->validfrom):''?>">
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Passport Expire Date:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="validuntil" class="form-control date-picker" name="validuntil" value="<?=!empty($info->validuntil)?date('Y-m-d',$info->validuntil):''?>">
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Age:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="age" name="age" value="<?=!empty($info->age) ? $info->age : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Sex:</label>
							
							<div class="col-xs-12 col-sm-4">
								<select id="sex" class="form-control" name="sex">
									<option value="0" <?=!empty($info) && $info->sex == 0?'selected':''?>>-Please Select-</option>
									<option value="1" <?=!empty($info) && $info->sex == 1?'selected':''?>>Male</option>
									<option value="2"  <?=!empty($info) && $info->sex == 2?'selected':''?>>Female</option>
									
								</select>
							</div>
						</div>

						<div class="space-2"></div>
						

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Birth Place:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="birthplace" name="birthplace" value="<?=!empty($info->birthplace) ? $info->birthplace : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Birth Date:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="birthday" class="form-control date-picker" name="birthday" value="<?=!empty($info->birthday)?date('Y-m-d',$info->birthday):''?>">
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Martial Status:</label>
							
							<div class="col-xs-12 col-sm-4">
								<select id="marital" class="form-control" name="marital">
									<option value="0" <?=!empty($info) && $info->marital == 0?'selected':''?>>-Please Select-</option>
									<option value="1" <?=!empty($info) && $info->marital == 1?'selected':''?>>Single</option>
									<option value="2"  <?=!empty($info) && $info->marital == 2?'selected':''?>>Married</option>
									
								</select>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Region:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="religion" name="religion" value="<?=!empty($info->religion) ? $info->religion : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">Permanent Address:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="address" name="address" value="<?=!empty($info->address) ? $info->address : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						-->
						<input type="hidden" name="id"  value="<?=!empty($info->id)?$info->id:''?>">
						<input type="hidden" name="agencyid"  value="<?=$_SESSION['master_user_info']->id?>">
						<input type="hidden" name="oldpass" value="<?=!empty($info) ? $info->password : ''?>">
						<div class="col-md-offset-3 col-md-9">
							<button id="tijiao" class="btn btn-info" data-last="Finish">
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


<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<!-- script -->
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
                        <?php if(empty($info)){ ?>
                        password:{
                            required:true
                        },
                        <?php } ?>
                        firstname: {
							required: true
						},
						email: {
							required: true,
							remote:'/master/agencyport/student_apply/checkemail?id=<?=!empty($info->id)?$info->id:''?>'
						},
                        lastname: {
							required: true
						}
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
								$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>Loadding');
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

							window.location.href="/master/agencyport/student_apply";
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