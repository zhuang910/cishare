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
	<li ><a href='index'>签证管理</a></li>
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
		签证管理
		<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
			<i class="ace-icon fa fa-reply light-green bigger-130"></i>
		</a>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->

			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
				<h3 class="lighter block green">签证信息</h3>
					<div class="col-xs-12">
						<!-- #section:pages/profile.info -->
						<div class="profile-user-info profile-user-info-striped">
							<div class="profile-info-row">
								<div class="profile-info-name"> 签证类别 </div>

								<div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-visatype" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->visatype)?$info_visa->visatype:''?></a></span>
								</div>
								<div class="profile-info-name"> 签证号码 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-visanumber" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->visanumber)?$info_visa->visanumber:''?></a></span>
								</div>
								<div class="profile-info-name"> 签证有效期 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-visatime" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->visatime)?date('Y-m-d',$info_visa->visatime):''?></a></span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> 所属派出所 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-police" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->police)?$info_visa->police:''?></a></span>
								</div>
								<div class="profile-info-name"> 现在的住址 </div>
								<div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-nowaddress" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->nowaddress)?$info_visa->nowaddress:''?></a></span>
								</div>
								<div class="profile-info-name"> 办理状态</div><div class="profile-info-value">
										<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="manage_state" data-pk="<?=$id?>" data-name="visa-manage_state" href="javascript:;"><?=!empty($info)&&!empty($info->manage_state)&&$info->manage_state==1?'正常':'办理续期中'?></a></span>
								</div>
							</div>
							<div class="profile-info-row">
								
								<div class="profile-info-name"> 原签证有效期 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-oldvisatime" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->oldvisatime)?!date('Y-m-d',$info_visa->oldvisatime):''?></a></span>
								</div>
								<div class="profile-info-name"> 家庭地址 </div>

								<div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-houseaddress" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->houseaddress)?$info_visa->houseaddress:''?></a></span>
								</div>
								<div class="profile-info-name"> 签发有效期 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-issuetime" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->issuetime)?date('Y-m-d',$info_visa->issuetime):''?></a></span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> 工作或学习单位 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-work" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->work)?$info_visa->work:''?></a></span>
								</div>
								<div class="profile-info-name"> 推介单位 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-referrals_unit" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->referrals_unit)?$info_visa->referrals_unit:''?></a></span>
								</div>
								<div class="profile-info-name"> 推介单位电话 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-referrals_tel" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->referrals_tel)?$info_visa->referrals_tel:''?></a></span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> 担保人 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-bondsman" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->bondsman)?$info_visa->bondsman:''?></a></span>
								</div>
								<div class="profile-info-name"> 担保人电话 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-bondsman_tel" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->bondsman_tel)?$info_visa->bondsman_tel:''?></a></span>
								</div>
								<div class="profile-info-name"> 出生地点 </div><div class="profile-info-value">
									<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="true" data-pk="<?=$id?>" data-name="visa-birth_place" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->birth_place)?$info_visa->birth_place:''?></a></span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> 婚否</div><div class="profile-info-value">
										<span style="display: inline;" class="editable editable-click" id="username"><a upload-config="marital" data-pk="<?=$id?>" data-name="visa-marital" href="javascript:;"><?=!empty($info_visa)&&!empty($info_visa->marital)&&$info_visa->marital==1?'是':'否'?></a></span>
								</div>
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
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<script type="text/javascript">
$(function(){
	manage_state='正常';
	$('a[upload-config="true"]').editable({
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>visa/edit_fields',
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
     
    
     $('a[upload-config="manage_state"]').editable({
    	type:'select',
    	 prepend: manage_state,
    	source: [

            {value: 1, text: '正常'},
            {value: 0, text: '办理续期中'}
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>visa/edit_fields',
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
     $('a[upload-config="marital"]').editable({
    	type:'select',
    	 prepend: '是',
    	source: [

            {value: 1, text: '是'},
            {value: 0, text: '否'}
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>visa/edit_fields',
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
<!-- end script -->
<?php $this->load->view('master/public/footer');?>