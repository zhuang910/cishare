<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>

<div class="width1024">
  <div class="wap_box p30">
    <span class="shenqingbtn"><a href="/<?=$puri?>/student/student/fee"><?=lang('acc_return')?></a></span>
  
    <h1 class="clearfix"><span class="fl"><?=lang('fee_books')?></span>
    </h1>
    <div class="tab pt30">
        <form id="book_fee">

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <th colspan="3" align="right" scope="col">
          <span style="float:left">
          <label><?=lang('attendance_term')?></label>
          <select  id="term" name="term" aria-required="true" aria-invalid="false" onchange="terms()">
            <option value="0"><?=lang('acc_select')?></option>
            <?php if(!empty($major_info['termnum'])):?>
              <?php for($i=1;$i<=$major_info['termnum'];$i++):?>
                <option <?=!empty($term)&&$term==$i?'selected="selected"':''?> value="<?=$i?>"><?=(lang('attendance_di').$i.lang('attendance_term'))?></option>
              <?php endfor;?>
            <?php endif;?>
          </select>
          </span>
          </th>
        </tr>
      </table>
      <?php if(!empty($book)):?>
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><?=lang('book_name')?></td>
            <td><?=lang('book_money')?></td>
            <td></td>
          </tr>
          <?php foreach($book as$k=>$v){?>
            <tr>
              <td><?=$v['name']?></td>
              <td><?=$v['price']?></td>
              <td><input type="checkbox" <?=!empty($is_jiao)?'disabled="disabled"':''?> <?=!empty($select_id)&&in_array($v['id'],$select_id)?'checked="checked"':''?> onchange="change_book(this,<?=$v['price']?>)" name="ids[]" value="<?=$v['id']?>"></td>
            </tr>
          <?php }?>
          <tr>
          <td><?=lang('book_total')?></td>
          <td><span id="money_page"><?=!empty($book_money)?$book_money:0?></span></td>
          <td class="jiji" style="width:300px;">
              <?php if(empty($is_jiao)):?>
                <a href="javascript:;" onclick="submit()"><?=lang('apply_4')?></a>
                <!-- <a onclick="pay('<?=!empty($term)?cucas_base64_encode($term.'-8'):''?>')" href="javascript:;">支付</a> -->
              <?php else:?>
                <span><?=lang('paid_completed')?></span>
                <?php if(!empty($wei_ids)){?>
                <a href="javascript:;" onclick="resubmissions(<?=$wei_ids?>)"><?=lang('bjsff')?></a>
                <?php }?>
              <?php endif;?>
            </td>
          </tr>
       </table>
       </form>
     <?php endif;?>
    </div>
  </div>
</div>
  <?=lang('pay_tips')?>
<script type="text/javascript">
$(function(){
  var re =<?=$re?>;
  if(re==1){
    var d = dialog({
      id:'cucasdialog',
      content:$('#ispayok').html()
     });
     d.showModal();
  }
})
  function c(){

  $('#tables').attr({
        class: 'widget-box transparent collapsed'
      });
   $('#add').remove();
}

function terms(){

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
    var term=$('#term').val();
    window.location.href="/<?=$puri?>/student/student/book_fee?term="+term;
    }
      function pay_in(div){
    window.location.reload();
    }
/**
 * [change_barter_card 计入换证费]
 * @return {[type]} [description]
 */
function change_book(th,mo){
  
  var is = $(th).is(':checked');
  if(is==true){
    var num=parseInt($("#money_page").text());
    $("#money_page").text(num+mo);
  }else{
    var num=parseInt($("#money_page").text());
    $("#money_page").text(num-mo);
  }
}
function submit(){
  var money=$('#money_page').text();
  if(money==0){
        var d = top.dialog({
        width:100,
        id:'win_repairs',
        modal: true, //蒙层
        cancel:true,
        fixed:true,
       content: '请选择书籍'
    });
    d.show();
  }
  var data=$('#book_fee').serialize();
  $.ajax({
    url: '/<?=$puri?>/student/student/save_books_fee',
    type: 'POST',
    dataType: 'json',
    data: data,
  })
  .done(function(r) {
      if(r.state==1){
        pay(r.data);
      }
  })
  .fail(function() {
    console.log("error");
  })
  
}
/**
 * [resubmissions 补交书费]
 * @return {[type]} [description]
 */
function resubmissions(y){
  var term=$('#term').val();
  var d = top.dialog({
//        title:'提示',
        id:'win_repairs',
        cancel:true,
        fixed:true,
        autoOpen: false,
        modal: true, //蒙层
        height:300,
        width:400,
        url:'/<?=$puri?>/student/student/resubmissions_book_fee?term='+term+'&ids='+y
    });
    d.show();
  
}
function shuaxin(){
  alert(123);
}
</script>
<?php $this->load->view('student/footer.php')?>