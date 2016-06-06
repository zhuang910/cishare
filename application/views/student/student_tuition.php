<?php $this->load->view('student/headermy.php')?>
<?php  $uri4_z =  $this->uri->segment(4);
	
?>
<?php 

	$data['cn'] =array(
		'1' => '第一学期',
		'2' => '第二学期',
		'3' => '第三学期',
		'4' => '第四学期',
		'5' => '第五学期',
		'6' => '第六学期',
		'7' => '第七学期',
		'8' => '第八学期',
		'9' => '第九学期',
		'10' => '第十学期',
	);
	$data['en'] =array(
		'1' => '1st Semester',
		'2' => '2nd Semester',
		'3' => '3rd Semester',
		'4' => '4th Semester',
		'5' => '5th Semester',
		'6' => '6th Semester',
		'7' => '7th Semester',
		'8' => '8th Semester',
		'9' => '9th Semester',
		'10' => '10th Semester',
	);
?>
<div class="width_925">
<span class="shenqingbtn"><a href="/<?=$puri?>/student/student/fee"><?=lang('acc_return')?></a></span>
  <h2 class="mt50 mb20"><span>
    <?=lang('scholarship_cost_cover_1')?>
    </span></h2>

  <div class="web_news">
   
    <div class="connews">
      <div class="con_con2">
        <?php if(!empty($history)){ ?>
        <dl class="clearfix">
          <dt class="clearfix cleartit">
          <span class="a1"><?=lang('tuition_term')?></span>
          <span class="a2"><?=lang('tuition_money')?></span>
          <span class="a3"><?=lang('tuition_time')?></span>
          <span class="a4"><?=lang('tuition_state')?></span> 
          <span class="a5"><?=lang('user_apply_operation')?></span>
            </dt>
          <?php
			foreach($history as $k => $v){
		?>
          <dd class="clearfix clearfix_list"> 
          <span class="a1"><?=$data[$puri][$v['nowterm']]?></span> 
          <span class="a2"><?=!empty($v['tuition'])?$v['tuition'].' RMB':''?></span>
          <span class="a3"><?=!empty($v['paytime'])?date('Y-m-d H:i:s',$v['paytime']):'--'?> </span> 
          <span class="a4">
            <?php if(!empty($v['paystate']) && $v['paystate'] == 1){?>
				<?=lang('paid_completed')?>
			
			<?php }else if(!empty($v['paystate']) && $v['paystate'] == 3){?>
			<?=lang('paid3')?>
			
			<?php }else if(!empty($v['paystate']) && $v['paystate'] == 2){?>
			<?=lang('paid2')?>
			
			<?php }else{?>
			
			<?=lang('paid0')?>
			<?php }?>
            </span>
			<span class="">

			<?php if(!empty($v['paystate']) && $v['paystate'] == 1||$v['paystate']==3){?>
				--
			
			<?php }else{?>
			<a  href="javascript:;" onclick="pay('<?=!empty($v['id'])?cucas_base64_encode($v['id'].'-6'):''?>')"><?=lang('apply_4')?></a><br />
			<?php if($v['is_cause']!=0){?>
			<!--凭据失败原因-->
                 <?=lang('pujushifuzhibai')?><br />
                 <a href="javascript:;" onclick="look_cause(<?=$v['is_cause']?>)"><?=lang('xiangxi')?></a>
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
			
          <?=lang('non_apply_data');?>
         </h2>
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
function baoxiu(){
    var d = top.dialog({
        id:'win_repairs',
        cancel:true,
        fixed:true,
        url:'/<?=$puri?>/student/student/look_tuition_detail'
    });
    d.show();
}
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
