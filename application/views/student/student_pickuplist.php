<?php $this->load->view('student/headermy.php')?>
<div class="width_925">
  <h2 class="mt50 mb20"><span><?=lang('my_pickup')?></span> 
  
  <?php if(empty($info)){?>
    <?php if(!empty($function_on_off['pickup']) && $function_on_off['pickup'] == 'no'){?>
  <span class="shenqingbtn"><a href="javascript:;" onclick="no_pickup()"><?=lang('pickup_title')?></a></span>
	<?php }else{?>
	 <span class="shenqingbtn"><a onclick="apply_acc(<?=$flag_accommodation?>)" href="javascript:;"><?=lang('pickup_title')?></a></span>
	<?php }?>
  <?php }?>
  </h2>
  <div class="web_news">
    <div class="connews" style="padding:30px;">
	  <?php if(!empty($info)){?>
      <table width="860" border="0" cellspacing="0" cellpadding="0">
        <tr class="title2">
          <td><?=lang('pickup_userinfo')?></td>
          <td><?=lang('pickup_info')?></td>
          <td><?=lang('book_info')?></td>
          <td><?=lang('pickup_remark')?></td>
          <td><?=lang('pickup_linkinfo')?></td>
          <td><?=lang('pickup_state')?></td>
       
        </tr>
        <tr>
          <td><?=lang('pickup_name')?>: <?=!empty($info['name'])?$info['name']:''?><br />
            <?=lang('pickup_email')?>: <?=!empty($info['email'])?$info['email']:''?><br />
            <?=lang('pickup_sex')?>:<?php if(!empty($info['sex'])){?> <?=lang('sex_'.$info['sex'])?><?php }?><br />
            <?=lang('pickup_nationality')?>:<?php if(!empty($info['nationality'])){?> <?=$nationality[$info['nationality']]?><?php }?></td>
          <td><?=lang('pickup_flightno')?>: <?=!empty($info['flightno'])?$info['flightno']:''?><br />
            <?=lang('pickup_fairport')?>: <?=!empty($info['flightno'])?$info['flightno']:''?><br />
            <?=lang('pickup_tairport')?>:  <?=!empty($info['flightno'])?$info['flightno']:''?><br />
            <?=lang('pickup_arrivetime')?>:  <?=!empty($info['arrivetime'])?date('Y-m-d',$info['arrivetime']):''?></td>
          <td><?=lang('pickup_numbers')?>: <?=!empty($info['numbers'])?$info['numbers']:''?><br />
            <?=lang('pickup_tel')?>: <?=!empty($info['tel'])?$info['tel']:''?><br />
            <?=lang('pickup_mobile')?>: <?=!empty($info['mobile'])?$info['mobile']:''?><br />
            <?=lang('pickup_schoolname')?>: <?=!empty($info['schoolname'])?$info['schoolname']:''?></td>
			<td>
			<?=!empty($info['remark'])?nl2br($info['remark']):''?>
			</td>
			<td>
			<?=!empty($info['linkinfo'])?nl2br($info['linkinfo']):''?>
			</td>
          <td>
		  
			<?php			
				if($flag_isshoufei == 1){
					if( (!empty($orderinfo) && in_array($orderinfo['paystate'],array(0,2)))){
			?>
			<a href="javascript:;" onclick="pay()"><?=lang('apply_4')?></a><br />
                        <?php if($info['state']==0||$info['state']==2){?>
                            <a href="javascript:;" onclick="delete_pickup(<?=$info['id']?>)"><?=lang('message_del')?></a><br />
                        <?php }?>
			<?=lang('pickup_state'.$info['state'])?>

			<?php }else{?>
				<?php if($orderinfo['paystate'] == 3){?>
				<?=lang('paid3')?><br />
				<?php }else if($orderinfo['paystate'] == 1){?>
					<?=lang('paid_completed')?><br />
				<?php }?>
			
				<?=lang('pickup_state'.$info['state'])?>

			<?php }?>
			
			<?php }else{?>
			<?=lang('pickup_state'.$info['state'])?>
               <?php if($info['state']==0||$info['state']==2){?>
                    <a style="color:red" href="javascript:;" onclick="delete_pickup(<?=$info['id']?>)"><?=lang('message_del')?></a><br />
               <?php }?>
			<?php }?>
		  </td>
         
        </tr>
       
      </table>
	<?php }else{ ?>
		  <h2><?=lang('non_apply_pickup')?></h2>
	<?php }?>
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
function no_pickup(){
	var d = dialog({
		content:'<?=lang('no_accommodeation')?>'
	
	});
	d.show();
	setTimeout(function(){
		d.close().remove();
	},3000);
}

function pay(){
	var url = '/<?=$puri?>/pay_pa/index?applyid=<?=!empty($info['id'])?cucas_base64_encode($info['id'].'-3'):''?>';
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
function apply_acc(bi){
  if(bi==0){
     var d = dialog({
            content: "<?=lang('personal_kecheng')?>"
          });
          d.show();
          setTimeout(function () {
            d.close().remove();
          }, 2000);
  }
  if(bi==1){
    window.location.href="/<?=$puri?>/student/student/pickup";
  }
}
function delete_pickup(id){
    var d = dialog({
        content: '<?=lang("acc_sfsc")?>',
        ok: function () {
            var thats = this;
            setTimeout(function () {
                thats.close().remove();
            }, 8000);
            $.get('/<?=$puri?>/student/student/delete_picup?id='+id,{},function(r){
                if(r.state==1){
                    var d = dialog({
                        content: '<?=lang("acc_sccg")?>'
                    });
                    d.show();
                    setTimeout(function () {
                        d.close().remove();
                    }, 2000);
                    window.location.reload();
                }
                if(r.state==0){
                    var d = dialog({
                        content: '<?=lang("acc_scsb")?>'
                    });
                    d.show();
                    setTimeout(function () {
                        d.close().remove();
                    }, 2000);
                }
                if(r.state==2){
                    var d = dialog({
                        content: '<?=lang("electives_quanxian")?>'
                    });
                    d.show();
                    setTimeout(function () {
                        d.close().remove();
                    }, 2000);
                }
            },'json');
        },
        cancel: function () {

            return true;
        }
    });
    d.show();
}
</script>
<?php $this->load->view('student/footer.php')?>