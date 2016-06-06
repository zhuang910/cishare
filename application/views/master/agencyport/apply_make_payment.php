<?php $this->load->view('public/css_basic')?>
<?php $this->load->view('public/js_basic')?>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<link href="<?=RES?>home/css/applyonline.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=RES?>home/css/chosen.css">
<script src="<?=RES?>home/js/chosen.jquery.min.js"></script>
<link href="<?=RES?>home/css/thumbnail.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/select2.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.sticky.js"></script>

<div class="width_925 clearfix">
	<?php $this->load->view('/master/agencyport/apply_coursename')?>
</div>
<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<?php $this->load->view('/master/agencyport/apply_left')?>
	
	</div>
	<div class="applyonline-4-main">
		<div class="applyonline-4-main-step clearfix">
			<div class="applyonline-4-main-step-btn-now"><i class="jiadahao">01 </i><span class="c1c1"></span></div>
			<div class="applyonline-4-main-step-btn-line"></div>
			<div class="applyonline-4-main-step-btn<?=in_array($apply_info['paystate'],array(1,3))?'-now':''?>"><i class="jiadahao">02 </i><span class="c1c1"></span></div>
			<div class="applyonline-4-main-step-btn-line"></div>
			<div class="applyonline-4-main-step-btn<?=in_array($apply_info['paystate'],array(1))?'-now':''?>"><i class="jiadahao">03 </i><span class="c1c1"></span></div>
		</div>
		<div class="applyonline-4-total">
			<dl>
				<dt>
					<span class="width-4-1"><?=lang('paid_course')?></span><span class="width-4-2"><?=lang('paid_fee')?></span><span class="width-4-3"><?=lang('paid_state')?></span>
				</dt>
				<dd><span class="width-4-1"><em class="color-blue"><a href="/<?=$puri?>/course/detail?id=<?=$course['id']?>" target="_blank"><?=!empty($coursename->langname)?$coursename->langname:''?></a></em></span><span class="width-4-2"><strong><?php if(!empty($apply_info['danwei']) && $apply_info['danwei'] == 1){echo 'USD';}else{echo 'RMB';}?> <?php if($isapplypay == 1){?><?=!empty($apply_info['registration_fee'])?$apply_info['registration_fee']:''?><?php }else{?>0<?php }?></strong></span><span class="width-4-3"><strong><?=lang('paid'.$apply_info['paystate'])?></strong></span></dd>
			</dl>
			<?php if($apply_info['paystate'] != 1){?>
			<dl style="margin-top:10px;">
				<dd><span class="width-4-1"></span><span class="width-4-2"></span><span class="width-4-3"><?=lang('paid_total')?>      <strong><?php if(!empty($apply_info['danwei']) && $apply_info['danwei'] == 1){echo 'USD';}else{echo 'RMB';}?> <?php if($isapplypay == 1){?><?=!empty($apply_info['registration_fee'])?$apply_info['registration_fee']:''?><?php }else{?>0<?php }?></strong></span></dd>
			</dl>
			<?php }?>
		</div>
			
			
			<div class="applyonline-2-btn">

				<div class="redbtn">
				<?php if($apply_info['paystate'] == 1){?>
				<a href="javascript:;" onclick="go_next()"><?=lang('apply_next')?></a>
				<?php }else{?>
				<a href="javascript:;" onclick="pay_next()"><?=lang('apply_next')?></a>
				<?php }?>
				</div>
			</div>
	</div>
	<!-- <div class="TCBox TCWidthB" id="ispayok" style="display:none">
			<div class="TCTitle TCTitleC">Pending for payment confirmation...</div>
			<p class="setzf">In the pop-up payment page to complete your payment, the system will receive the payment information sent by the third party payment platform. </p>
			<p class="setzf">If you encounter any problem during payment, please click the button "Incomplete" and contact  beiwaipeixun@163.com or call 0086-10-8881-7857/8881-7856 
		. </p>
			<p class="setzf">If you finish your payment, please click the button "Complete".</p>
			<table class="TCBtnTab TCBtnTabA">
				<tbody><tr>
					<td>
					<div style="margin-top: 20px; cursor: pointer;" class="apply_btn2 float_l"><a class="White" onclick="pay_in(this);" type="button">Incomplete</a></div>
					<div style="margin: 20px 0 0 20px" class=" jian_btn float_l"><a href="javascript:;" onclick="pay_ok(this);" class="White">Complete</a></div>
				</td>
				</tr>
				</tbody>
			</table>
	</div>-->
	<?=lang('pay_tips')?>
</div>
<script type="text/javascript">
    var userid=<?=$userid?>;
	<!--支付成功 直接到下一步去-->
	function go_next(){
		window.location.href='/master/agencyport/apply/submit_application?userid='+userid+'&applyid=<?=!empty($apply_info['id'])?cucas_base64_encode($apply_info['id']):''?>';
	}
	<!--点击下一步 跳到支付去-->
	function pay_next(){
	   var url = '/master/agencyport/pay?userid='+userid+'&applyid='+'<?=!empty($apply_info['id'])?cucas_base64_encode($apply_info['id']):''?>';
	   window.open(url);
	   var d = dialog({
			id:'cucasdialog',
			content:$('#ispayok').html()
	   });
	   d.showModal();
	}


	function pay_in(div){
		window.location.reload();
    }
	function pay_ok(div){
		 window.location.reload();
		
	} 
</script>
