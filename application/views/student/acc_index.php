<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>

<div class="width1024">
  <div class="wap_box p30">
    <span class="shenqingbtn"><a href="/<?=$puri?>/student/student/accommodation"><?=lang('acc_return')?></a></span>
  
    <h1 class="clearfix"><span class="fl"><?=lang('acc_accyuding')?></span>
    </h1>
    <div class="tab pt30">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <th colspan="3" align="right" scope="col">
          <span style="float:left">
          <label><?=lang('acc_xiaoqu')?></label>
          <select  id="campusid" name="campusid" aria-required="true" aria-invalid="false" onchange="campus()">
            <option value="0"><?=lang('acc_select')?></option>
            <?php foreach($campus_info as $k=>$v):?>
              <option <?=!empty($cid)&&$cid==$v['id']?'selected="selected"':''?> value="<?=$v['id']?>"><?=$puri=='cn'?$v['name']:$v['enname']?></option>
            <?php endforeach;?>
          </select>
          <label class="control-label" for="platform"><?=lang('acc_buli')?></label>
          <select  id="bulidingid" name="bulidingid" aria-required="true" aria-invalid="false" onchange="buliding()">
            <option value="0"><?=lang('acc_select')?></option>
            <?php if(!empty($bulid_info)):?>
              <?php foreach($bulid_info as $k=>$v):?>
                <option <?=!empty($bid)&&$bid==$v['id']?'selected="selected"':''?> value="<?=$v['id']?>"><?=$puri=='cn'?$v['name']:$v['enname']?></option>
              <?php endforeach;?>
            <?php endif;?>
          </select>
          </span>
          <span class="v1"><?=lang('acc_kongfang')?></span> <span class="v2"><?=lang('acc_keruzhu')?></span> <span class="v3"><?=lang('acc_manyuan')?></span>
          </th>
        </tr>
        <?php if(empty($bulid_info_one['floor_num'])):?>
        <tr><td colspan="3"><h3><?=lang('acc_select_b_c')?></h3></td></tr>
      <?php endif;?>
        <?php if(!empty($bulid_info_one['floor_num'])){?>
          <?php for($i=1;$i<=$bulid_info_one['floor_num'];$i++){?>
            <tr>
              <td width="80"><?=lang('acc_di')?> <?=$i?> <?=lang('acc_ceng')?></td>
              <td colspan="2" class="clearfix">
              <?php if(!empty($room_info_hao[$i])){?>
                <?php foreach($room_info_hao[$i] as $k=>$v){?>
              <?php
                  //满没和住宿情况
                 if($v['in_user_num']>=$v['maxuser']||$v['is_reserve']==2){?>
                  <em class="v3" title="<?=$v['name']?>"></em>
                  <?php }elseif($v['in_user_num']>0){?>
                  <em class="v2" onclick="book_room(<?=$v['id']?>)" title="<?=$v['name']?>"></em>
                  <?php }else{?>
                   <em class="v1" onclick="book_room(<?=$v['id']?>)" title="<?=$v['name']?>"></em>
                 <?php }?>
            
                  
              <?php }}?>
              </td>
            </tr>
       <?php }}?>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">
  function c(){

  $('#tables').attr({
        class: 'widget-box transparent collapsed'
      });
   $('#add').remove();
}
function campus(){

  var cid=$('#campusid').val();
    $.ajax({
      url: '/student/accommodation/get_buliding?cid='+cid,
      type: 'POST',
      dataType: 'json',
      data:{}
    })
    .done(function(r) {
      $("#bulidingid").empty();
      if(r.state==1){
          $("#bulidingid").append("<option value='0'>"+"<?=lang('acc_select')?>"+"</option>"); 
           $.each(r.data, function(i, k) { 
             var opt = $("<option/>").text(k.name).attr("value",k.id);
              $("#bulidingid").append(opt); 
            });
      }
    
    })
    .fail(function() {
 
      
    })

}
function buliding(){

  var bid=$('#bulidingid').val();
  var cid=$('#campusid').val();
  if(bid!=undefined&&cid!=undefined){
    window.location.href="/<?=$puri?>/student/accommodation?tiaoji=<?=$tiaoji?>&bid="+bid+"&cid="+cid;
  }

}
/**
 * [dingfang 订房]
 * @return {[type]} [description]
 */
function book_room(roomid){
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
        url:'/<?=$puri?>/student/accommodation/book_room?tiaoji=<?=$tiaoji?>&roomid='+roomid
    });
    d.show();

//  $.ajax({
//    url: '/<?//=$puri?>///student/accommodation/book_room?roomid='+roomid,
//    type: 'POST',
//    dataType: 'json',
//    data: {}
//  })
//  .done(function(r) {
//    if(r.state==1){
//       var d = dialog({
//          content: ''+r.data+''
//        });
//        d.show();
//    }
//  })
//  .fail(function() {
//    console.log("error");
//  })
//  .always(function() {
//    console.log("complete");
//  });
  
}
</script>
<?php $this->load->view('student/footer.php')?>