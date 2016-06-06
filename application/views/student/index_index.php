<?php $this->load->view('student/headermy.php')?>
<?php  $uri4_z =  $this->uri->segment(4);
	
?>
<div class="width_925">
  <h2 class="mt50 mb20"><span>
    <?=lang('my_apply')?>
    </span>
      <?php if($is_apply_course==1){?>
          <span class="shenqingbtn"><a href="/<?=$puri?>/course" target="_blank"><?=lang('page_number')?></a></span>
      <?php }?>
  </h2>
  <div class="web_news">
    <?php $this->load->view('student/index_nav')?>
    <div class="connews">
      <div class="con_con2">
        <?php if(!empty($apply_unsub)){ ?>
        <dl class="clearfix">
          <dt class="clearfix cleartit"><span class="a4">
            <?=lang('user_apply_no')?>
            </span> <span class="a2">
            <?=lang('name')?>
            </span> <span class="a3" style="150px">
            <?=lang('user_apply_state')?>
            </span><span class="a4">
            <?=lang('user_apply_lasttime')?>
            </span> <span class="a5" style="width:200px;">
            <?=lang('user_apply_operation')?>
            </span></dt>
          <?php
			foreach($apply_unsub as $k => $v){
		?>
          <dd class="clearfix clearfix_list"> <span class="a4">
            <?=!empty($v['number'])?$v['number']:''?>
            </span> <span class="a2">
            <?=$puri=='cn'?(!empty($v['mname'])?$v['mname']:''):(!empty($v['enmname'])?$v['enmname']:'')?>
            </span> <span class="a3" style="150px">
        	 <?php if($flag == 2){?>
			<?=lang('applystate'.$v['state'])?>

            <?//=lang('personal_accept')?>
            <?=lang('applystate'.$v['state'])?>
            <?php }else if($flag == 3){?>
            <?//=lang('personal_admission')?>
			<?=lang('applystate'.$v['state'])?>
            <?php }else{?>
			
			<?=lang('applystate'.$v['state'])?>
			<br />

			<?=!empty($v['tips'])?'Tips:'.$v['tips']:''?>
			<?php 
				if(!empty($v['pagesend_status']) && $v['pagesend_status'] == 1){
			?>
			(<?=lang('pagesend_status_1')?>)
			<?php }?>
			<?php 
				if(!empty($v['e_offer_status']) && $v['e_offer_status'] == 1 && $v['pagesend_status'] != 1){
			?>
			(<a href="/download?path=<?=!empty($v['e_offer_path'])?$v['e_offer_path']:''?>"><?=lang('e_offer_status')?></a>)
			<?php }?>
			
			
			<?php }?>
            </span> <span class="a4">
            <?=date('Y-m-d',$v['lasttime'])?>
			<br/>
			<?=date('H:i:s',$v['lasttime'])?>
            </span>
			<span class="a2" style="width:190px;">
            <?php 
              if(!empty($v['paystate']) && $v['paystate'] == 1){
				?>
            
            <?php }else{?>
            <?php 
				if($v['state'] == 0){
			?>
			<a href="javascript:;" style="color:red" onclick="del_apply(<?=$v['id']?>)">
            <?=lang('personal_delete')?>
            </a>
            <br />
			<?php }?>
            <?php }?>
			
			<?php if(!empty($pledge_on) && $pledge_on == 1){?>
			
				<?php if($v['deposit_state'] == 1){?>
				<?php }else{?>
					<?php if($v['state'] == 6 && $v['deposit_state']!=1 && $v['deposit_state']!=3){?>
						<a href="javascript:;" onclick="pay('<?=cucas_base64_encode($v['id'].'-5')?>')"><?=lang('deposit_state')?></a>
						
					<?php }?>
				<?php }?>
				<br /><?=lang('deposit_state_'.$v['deposit_state'])?>
				<?php if($v['is_cause']!=0){?>
				<!--凭据失败原因-->
	                 <br /><a href="javascript:;" onclick="look_cause(<?=$v['is_cause']?>)"><?=lang('xiangxi')?></a>
				<?php }?>
            <?php }?>

			<?php if(!empty($v['state']) && $v['state'] >= 7 && !empty($scholarship_on) && $scholarship_on == 1 && $is_student == 0){?>
				<a href="/<?=$puri?>/scholarshipgrf/index?applyid=<?=cucas_base64_encode($v['id'])?>"><?=lang('apply_scholarship')?></a>
			
			<?php }?>
			<?php if($flag == 1 && in_array($v['state'],array(0,2)) ){?>
            <?=!empty($v['isinformation']) && $v['isinformation'] == 1?lang('form_complated'):'<font color=red>'.lang('form_uncomplated').'</font>'?>
            <?php if($v['isinformation'] != 1){?>
            <a href="/<?=$puri?>/student/fillingoutforms/apply?applyid=<?=cucas_base64_encode($v['id'])?>">
            <?=lang('continue')?>
            </a>
            <?php }?>
            <br />
            <?=!empty($v['isatt']) && $v['isatt'] == 1?lang('material_completed'):'<font color=red>'.lang('material_uncompleted').'</font>'?>
            <?php if($v['isatt'] != 1){?>
            <a href="/<?=$puri?>/student/apply/upload_materials?applyid=<?=cucas_base64_encode($v['id'])?>">
            <?=lang('continue')?>
            </a>
            <?php }?>
            <br />
            <?=!empty($v['paystate']) && $v['paystate'] == 1?lang('paid_completed'):'<font color=red>'.lang('paid_uncompleted').'</font>'?>
            <?php if($v['paystate'] != 1){?>
            <a href="/<?=$puri?>/student/apply/make_paymeznt?applyid=<?=cucas_base64_encode($v['id'])?>">
            <?=lang('continue')?>
            </a>

            <?php }?>
            <br />

            <!--判断是否提交-->
            	<?php if(!empty($v['paystate']) && $v['paystate'] == 1&&$v['issubmit']==0):?>
            	<?=lang('apply_wei')?> <a href="/<?=$puri?>/student/apply/make_paymeznt?applyid=<?=cucas_base64_encode($v['id'])?>"><?=lang('continue')?></a>
            	<?php endif;?>
            <!---->
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
			
          <?=lang('non_apply_data');?>
          <a href="/<?=$puri?>/course" target="_blank"> <?=lang('page_number')?> </a></h2>
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
					 $.post('/<?=$puri?>/student/index/del_apply',{id:id},function(msg){
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
	var url = '/<?=$puri?>/pay_pa/index?applyid='+id;
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
	function look_cause(id){
   var d = top.dialog({
//        autoOpen: false,
        // title:"<?=lang('acc_accyuding')?>",
        width:406,
//        height:400,
//        show: "blind",
//        resizable: true,
        id:'win_repairs',
        modal: true, //蒙层
        cancel:true,
        fixed:true,
        url:'/<?=$puri?>/student/accommodation/look_cause?id='+id,
    });
    d.show();
}
</script>
<?php $this->load->view('student/footer.php')?>
