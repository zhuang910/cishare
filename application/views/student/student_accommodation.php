<?php $this->load->view('student/headermy.php')?>
<div class="width_925">
  <h2 class="mt50 mb20"><span><?=lang('my_accommdation')?></span>

  <?php if(empty($info)||$shibai==1){?>


  <?php if(!empty($function_on_off['accommaction']) && $function_on_off['accommaction'] == 'no'){?>
    <span class="shenqingbtn"><a href="javascript:;" onclick="no_pickup()"><?=lang('book_now')?></a></span>

  <?php }else{?>
  <span class="shenqingbtn"><a href="javascript:;" onclick="apply_acc(<?=$flag_accommodation?>)"><?=lang('book_now')?></a></span>
  <?php }?>

  <?php }?>
   <span class="shenqingbtn"><a href="/<?=$puri?>/student/accommodation/repairs_page"><?=lang('acc_myreacc')?></a></span>
  
  </h2>
  <div class="web_news">
    <div class="connews" style="padding:30px;">
	  <?php if(!empty($info)){?>
      <table width="860" border="0" cellspacing="0" cellpadding="0">
        <tr class="title2">
          <td><?=lang('pickup_userinfo')?></td>
          <td><?=lang('accommodation_info')?></td>
          <td><?=lang('accommodation_book_info')?></td>
          <td><?=lang('pickup_remark')?></td>
          <td><?=lang('accommodation_state')?></td>

        </tr>
  <!--循环开始订的房间-->
        <tr>
          <td><?=lang('pickup_name')?>: <?=!empty($userinfo['chname'])?$userinfo['chname']:''?><br />
            <?=lang('pickup_email')?>: <?=!empty($userinfo['email'])?$userinfo['email']:''?><br />
            <?=lang('pickup_sex')?>:<?php if(!empty($userinfo['sex'])){?> <?=lang('sex_'.$userinfo['sex'])?><?php }?><br />
            <?=lang('pickup_nationality')?>:<?php if(!empty($userinfo['nationality'])){?> <?=$nationality[$userinfo['nationality']]?><?php }?></td>
          <td><?=lang('myacc_camp')?>: <?=!empty($campname)?$campname:''?><br />
            <?=lang('myacc_building')?>: <?=!empty($buildname)?$buildname:''?><br />
            <?=lang('acc_ceng')?> :<?=!empty($builds[0]['floor'])?$builds[0]['floor']:''?> <br /><?=lang('acc_rname')?>:<?=!empty($builds)&&$puri=='cn'?$builds[0]['name']:$builds[0]['enname']?><br />
            <?=lang('myacc_type')?>:  <?=$data?><br />
           </td>
          <td><?=lang('myacc_sc')?>: <?=!empty($info['accendtime'])?$info['accendtime']:''?><br />
            <?=lang('myacc_time')?>: <?=!empty($info['accstarttime'])?date('Y-m-d',$info['accstarttime']):''?><br />
            <?=lang('myacc_tj')?>: <?php if(!empty($info['tj']) && $info['tj'] == 1){?><?=lang('myacc_yes')?><?php }else{?><?=lang('myacc_no')?><?php }?><br />
           <?php if($info['acc_state']==6){?>
                <?=!empty($info['residual_amount'])?lang('residual_amount').':'.$info['residual_amount']:''?>
           <?php }?>
           </td>
		   <td>
		   <?=!empty($info['remark'])?$info['remark']:''?>
		   </td>
          <td>
			<?php
				if($flag_isshoufei == 1){
					if(in_array($info['paystate'],array(0,2))){
			?>
                  <?php if($info['acc_state']<4){?>
		            	<a href="/<?=$puri?>/pay_pa/index?applyid=<?=!empty($info['id'])?cucas_base64_encode($info['id'].'-4'):''?>" target="_blank" onclick="pay()"><?=lang('apply_4')?></a><br />
                            <a href="javascript:;" style="color:red" onclick="delete_acc(<?=$info['id']?>)"><?=lang('message_del')?></a><br />
                  <?php }?>

	               <?php if($is_cause!=0){?>
                 <!--凭据失败原因-->
                 <?=lang('pujushifuzhibai')?><br />
                 <a href="javascript:;" onclick="look_cause(<?=$is_cause?>)"><?=lang('xiangxi')?></a>
                 <?php }?>
      <?php if($info['acc_state']!=1){?>
    	 <?=lang('myacc_state_'.$info['acc_state'])?>
      <?php }?>
			<?php }else{?>
				<?=lang('paid'.$info['paystate'])?><br />
         <?php if($info['acc_state']!=1){?>
          <?=lang('myacc_state_'.$info['acc_state'])?><br />
          <?php }?>
			<?php }?>

			<?php }else{?>
      <?php if($info['acc_state']!=1){?>
			   <?=lang('myacc_state_'.$info['acc_state'])?>
      <?php }?>
			<?php }?>
		  </td>

        </tr>
  <!--循环结束订的房间-->
      </table>
	<?php }else{ ?>
		  <h2><?=lang('non_apply_accommodation')?></h2>
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
	</div>--><?=lang('pay_tips')?>
<script type="text/javascript">
function delete_acc(id){
    var d = dialog({
        content: '<?=lang("acc_sfsc")?>',
        ok: function () {
            var thats = this;
            setTimeout(function () {
                thats.close().remove();
            }, 8000);
            $.get('/<?=$puri?>/student/accommodation/delete_acc?id='+id,{},function(r){
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
function no_pickup(){
	var d = dialog({
					content: '<?=lang('no_accommodeation')?>'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 3000);
}


function pay(){
    var id=<?=!empty($info['id'])?$info['id']:0?>;
    //验证该房间有没有满
    $.ajax({
        url: '/<?=$puri?>/student/student/check_room?id='+id,
        type: 'GET',
        dataType: 'json',
        data: {}
    })
        .done(function(r) {
           if(r.state==1){
               var d = dialog({
                   id:'cucasdialog',
                   content:$('#ispayok').html()
               });
               d.showModal();
           }
            if(r.state==2){
                //弹出框该房间已满预定失败
                var d = dialog({
                    content: "<?=lang('acc_yiman')?>"
                });
                d.show();
                setTimeout(function () {
                    d.close().remove();
                    window.location.reload();
                }, 2000);
            }
            if(r.state==3){
                //弹出框该房间已满预定失败
                var d = dialog({
                    content: "<?=lang('acc_yigaun')?>"
                });
                d.show();
                setTimeout(function () {
                    d.close().remove();
                    window.location.reload();
                }, 2000);
            }
        })
        .fail(function() {

        })
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
		window.location.href="/<?=$puri?>/student/accommodation";
	}
  if(bi==3){
    window.location.href="/<?=$puri?>/student/accommodation?tiaoji=<?=!empty($info['id'])?$info['id']:0?>";
  }
}
function baoxiu(){
$.ajax({
    url: '/<?=$puri?>/student/accommodation/repairs',
    type: 'POST',
    dataType: 'json',
    data: {}
  })
  .done(function(r) {
    if(r.state==1){
       var d = dialog({
          content: ''+r.data+''
        });
        d.show();
    }
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
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