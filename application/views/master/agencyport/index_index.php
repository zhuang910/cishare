<?php $this->load->view('public/css_basic')?>
<?php $this->load->view('public/js_basic')?>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<link href="<?=RES?>home/css/applyonline.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=RES?>home/css/chosen.css">
<script src="<?=RES?>home/js/chosen.jquery.min.js"></script>
<?php  $uri4_z =  $this->uri->segment(4);
	
?>
<div class="width_925">
  <h2 class="mt50 mb20"><span>
    学生申请
    </span>
      <?php if($is_apply_course==1){?>
          <span class="shenqingbtn"><a href="/master/agencyport/course?userid=<?=$userid?>">申请专业</a></span>
      <?php }?>
  </h2>
  <div class="web_news">
    <?php $this->load->view('/master/agencyport/index_nav')?>
    <div class="connews">
      <div class="con_con2">
        <?php if(!empty($apply_unsub)){ ?>
        <dl class="clearfix">
          <dt class="clearfix cleartit"><span class="a1">
            编号
            </span> <span class="a2">
            课程名
            </span> <span class="a3">
            状态
            </span><span class="a4">
            最后操作时间
            </span> <span class="a5">
            操作
            </span></dt>
          <?php
			foreach($apply_unsub as $k => $v){
		?>
          <dd class="clearfix clearfix_list"> <span class="a1">
            <?=!empty($v['number'])?$v['number']:''?>
            </span> <span class="a2">
            <?=!empty($v['mname'])?$v['mname']:''?>
            </span> <span class="a3">
            <?php if($flag == 1 && in_array($v['state'],array(0,2)) ){?>
            <?=!empty($v['isinformation']) && $v['isinformation'] == 1?'表单填写完成':'<font color=red>表单填写未完成</font>'?>
            <?php if($v['isinformation'] != 1){?>
            <a href="/master/agencyport/fillingoutforms/apply?userid=<?=$userid?>&applyid=<?=cucas_base64_encode($v['id'])?>">
            继续
            </a>
            <?php }?>
            <br />
            <?=!empty($v['isatt']) && $v['isatt'] == 1?lang('material_completed'):'<font color=red>资料提交未完成</font>'?>
            <?php if($v['isatt'] != 1){?>
            <a href="/master/agencyport/apply/upload_materials?userid=<?=$userid?>&applyid=<?=cucas_base64_encode($v['id'])?>">
            继续
            </a>
            <?php }?>
            <br />
            <?=!empty($v['paystate']) && $v['paystate'] == 1?'已支付':'<font color=red>未支付</font>'?>
            <?php if($v['paystate'] != 1){?>
            <a href="/master/agencyport/apply/make_paymeznt?userid=<?=$userid?>&applyid=<?=cucas_base64_encode($v['id'])?>">
            继续
            </a>
            <?php }?>
            <br />
              <!--判断是否提交-->
            	<?if(!empty($v['paystate']) && $v['paystate'] == 1&&$v['issubmit']==0){?>
                    未提交申请 <a href="/master/agencyport/apply/make_paymeznt?userid=<?=$userid?>&applyid=<?=cucas_base64_encode($v['id'])?>">继续</a>
                <?php }?>
                <!---->
            <?php }else if($flag == 2){?>
            <?//=lang('personal_accept')?>
            <?=lang('applystate'.$v['state'])?>
            <?php }else if($flag == 3){?>
            <?//=lang('personal_admission')?>
			<?=lang('applystate'.$v['state'])?>
            <?php }else{?>
			
			<?=lang('applystate'.$v['state'])?>
			<?php 
				if(!empty($v['pagesend_status']) && $v['pagesend_status'] == 1){
			?>
			(<?=lang('pagesend_status_1')?>)
			<?php }?>
			<?php 
				if(!empty($v['e_offer_status']) && $v['e_offer_status'] == 1 && $v['pagesend_status'] != 1){
			?>
			(<?=lang('e_offer_status')?>)
			<?php }?>
			
			
			<?php }?>
            </span> <span class="a4">
            <?=date('Y-m-d',$v['lasttime'])?>
			<br/>
			<?=date('H:i:s',$v['lasttime'])?>
            </span>
			<span class="a5">
            <?php 
              if(!empty($v['paystate']) && $v['paystate'] == 1){
				?>
            
            <?php }else{?>
            <?php 
				if($v['state'] == 0){
			?>
			<a href="javascript:;" onclick="del_apply(<?=$v['id']?>)">
            <?=lang('personal_delete')?>
            </a>
			<?php }?>
            <?php }?>
			
			<?php if(!empty($pledge_on) && $pledge_on == 1){?>
				<?php if($v['deposit_state'] == 1){?>
					<?=lang('deposit_state_1')?>
				<?php }else{?>
				<br/>
					<?php if($v['state'] == 6){?>
						<a href="javascript:;" onclick="pay('<?=cucas_base64_encode($v['id'].'-5')?>')"><?=lang('deposit_state_0')?></a>
					<?php }?>
				<?php }?>
            <?php }?>
			
            </span> </dd>
          <?php }?>
        </dl>
        <?php }else{ ?>
		<?php if(!empty($uri4_z)){?>
			 <h2>
			
          <?=lang('no_data');?>
     </h2>
			<?php }else{?>
			 <h2>
			
          你还没有申请专业
          <a href="/master/agencyport/course?userid=<?=$userid?>"> 开始申请 </a></h2>
			<?php }?>
       
        <?php }?>
      </div>
        </div>
  </div>
</div>
</div>
<!--<div class="TCBox TCWidthB" id="ispayok" style="display:none">
			<div class="TCTitle TCTitleC">Pending for payment confirmation...</div>
			<p>In the pop-up payment page to complete your payment, the system will receive the payment information sent by the third party payment platform. </p>
			<p>If you encounter any problem during payment, please click the button "Incomplete" and contact  beiwaipeixun@163.com or call 0086-10-8881-7857/8881-7856 
		. </p>
			<p>If you finish your payment, please click the button "Complete".</p>
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
<script type="text/javascript">
    var userid=<?=$userid?>;
    function del_apply (id) {
		var d = dialog({
				title: '<?=lang('welcome')?>',
				content: '<?=lang('del_confirm')?>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					 $.post('/master/agencyport/index/del_apply',{id:id,userid:userid},function(msg){
						  if(msg.state == 1){
							var d = dialog({
								content: '<?=lang('del_success')?>'
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
							}, 5000);
							window.location.reload();
							window.location.reload();
						  }else{
							  alert('<?=lang('update_error')?>');
						  }
					  },'json');
				},
				cancel: function () {
					
					return true;
				}
			});
			d.show();
			setTimeout(function () {
				d.close().remove();
			}, 5000);
	
   /* art.dialog.confirm('Are you sure?',function(){
        $.ajax({
          type:'GET',
          url:'/user/index/del_apply?id='+id,
          success:function(r){
              if(r.state == 1){
                  art.dialog.tips('OK');
                  window.location.reload();
              }else{
                art.dialog.tips('Error');
              }
          },
          dataType:'json'
        });
    });*/
  }

</script>
	<script type="text/javascript">
function pay(id){
	var url = '/master/agencyport/pay_pa/index?applyid='+id+'&userid='+userid;
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

