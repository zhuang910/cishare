<?php $this->load->view('student/headermy.php')?>
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
  <h2 class="mt50 mb20"><span>
    <?=lang('fee_history')?>
    </span>
	  <span class="shenqingbtn"><a href="javascript:;" onclick="window.location.href='/<?=$puri?>/student/student/tuition'"><?=lang('fee_xf')?></a></span>
	  <span class="shenqingbtn"><a href="javascript:;" onclick="window.location.href='/<?=$puri?>/student/student/book_fee'"><?=lang('fee_books')?></a></span>
	  <span class="shenqingbtn"><a href="javascript:;" onclick="window.location.href='/<?=$puri?>/student/student/electric_pledge'"><?=lang('fee_election')?></a></span>
    </h2>
  <div class="web_news">
   
    <div class="connews">
      <div class="con_con2">
        <?php if(!empty($info)){ ?>
        <dl class="clearfix">
          <dt class="clearfix cleartit">
          <span class="a1"><?=lang('fee_sztype')?></span>
          <span class="a1"><?=lang('fee_yyfy')?></span>
          <span class="a1"><?=lang('fee_sjfy')?></span>
          <span class="a1"><?=lang('fee_paystate')?></span>
          <span class="a1"><?=lang('fee_paytype')?></span> 
          <span class="a1"><?=lang('fee_paytime')?></span>
          <span class="a1"><?=lang('fee_paytemr')?></span>
          <span class="a1"><?=lang('fee_oper')?></span>
			
            </dt>
          <?php foreach($info as$k=>$v){?>
          <dd class="clearfix clearfix_list"> 

           <span class="a1"><?=!empty($v['type'])?lang('fee_type_'.$v['type']):'--'?></span>
           <span class="a1"><?=!empty($v['payable'])?$v['payable']:''?></span>
           <span class="a1"><?=!empty($v['paid_in'])?$v['paid_in']:''?></span>
           <span class="a1"><?=!empty($v['paystate'])?lang('fee_paystate_'.$v['paystate']):lang('fee_paystate_0')?></span>
           <span class="a1"><?=!empty($v['paytype'])?lang('fee_paytype_'.$v['paytype']):''?></span> 
           <span class="a1"><?=!empty($v['paytime'])?date('Y-m-d',$v['paytime']):''?></span>
           <span class="a1"><?=!empty($v['term'])?$data[$puri][$v['term']]:''?></span>
           <span class="a1"><a href="javascript:;" onclick="look_remark('<?=$v['remark']?>')"><?=lang('fee_ckremark')?></a></span>
          </dd>
        
         <?php }?>
        </dl>
       
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
function look_remark(content){
	 var d = top.dialog({
			  id:'win_repairss',
	        cancel:true,
	        fixed:true,
	        modal: true, //蒙层
			content:content
	   });
	   d.show();
}
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
</script>
<?php $this->load->view('student/footer.php')?>
