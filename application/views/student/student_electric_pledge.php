<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>

<div class="width1024">
  <div class="wap_box p30">
    <span class="shenqingbtn"><a href="/<?=$puri?>/student/student/fee"><?=lang('acc_return')?></a></span>
  
    <h1 class="clearfix"><span class="fl"><?=lang('fee_election')?></span>
    </h1>
    <div class="tab pt30">
     
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><?=lang('fee_yj')?></td>
            <td><?=lang('fee_sj')?></td>
            <td><?=lang('fee_time')?></td>
            <td></td>
          </tr>
          <?php if(!empty($info)):?>
           <tr>
            <td><?=!empty($info['last_money'])?$info['last_money']:''?></td>
            <td><?=!empty($info['paid_in'])?$info['paid_in']:'--'?></td>
            <td><?=!empty($info['paytime'])?date('Y-m-d',$info['paytime']):'--'?></td>
            <td  class="jiji">
                <?php if($info['paystate']!=1||$info=null):?>
                  <a onclick="pay('<?=!empty($info['order_id'])?cucas_base64_encode($info['order_id'].'-14'):''?>')" href="javascript:;"><?=lang('apply_4')?></a>
                <?php else:?>
                  <a style="background: url('');border: 0px solid #b90000;" href="javascript:;"><?=lang('paid_completed')?></a>
                <?php endif;?>
            </td>
          </tr>
        <?php endif;?>
        </table>
    </div>
  </div>
</div>
<?=lang('pay_tips')?>
<script type="text/javascript">
  function c(){

  $('#tables').attr({
        class: 'widget-box transparent collapsed'
      });
   $('#add').remove();
}

function term(){

  var term=$('#term').val();
  if(term!=undefined){
    window.location.href="/<?=$puri?>/student/student/book_fee?term="+term;
  }

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
  function pay_ok(div){
    window.location.reload();
    }
      function pay_in(div){
    window.location.reload();
    }
</script>
<?php $this->load->view('student/footer.php')?>