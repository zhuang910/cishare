<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>
 <?php 
  $uriUrl = $this->uri->segment(4);
?>
<div class="width1024">
  <div class="wap_box p30">
    <h1><?=lang('acc_myreacc')?>
 <span class="shenqingbtn"><a href="javascript:;" onclick="baoxiu()"><?=lang('acc_reacc')?></a></span>
    </h1>

    <div class="tab pt30">
    <div class="Switch_title">
        <ul class="clearfix">
          <li style="border-right:none;" <?=!empty($uriUrl) && $uriUrl == 'repairs_page'?'class="active"':''?>>
           <a href="/<?=$puri?>/student/accommodation/repairs_page"><?=lang('acc_wei')?></a></li>
          <li  style="border-right:none;" <?=!empty($uriUrl) && $uriUrl == 'repairs_page_in'?'class="active"':''?>>
          <a href="/<?=$puri?>/student/accommodation/repairs_page_in"><?=lang('acc_chu')?></a></li>
          <li <?=!empty($uriUrl) && $uriUrl == 'repairs_page_out'?'class="active"':''?>>
          <a href="/<?=$puri?>/student/accommodation/repairs_page_out"><?=lang('acc_li')?></a></li>
        </ul>
      </div>
      <!-- <div class="notes">Notice:You have to submit application for admission has been accepted, you can book accommodation, airport, or view Offer.</div> -->
      <div class="tab pt30 Switch_bg">
       <?php if(!empty($r_info)){?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('acc_cname')?></th>
            <th align="left" scope="col"><?=lang('acc_bname')?></th>
            <th align="left" scope="col"><?=lang('acc_floor')?></th>
            <th align="left" scope="col"><?=lang('acc_rname')?></th>
            <th align="left" scope="col"><?=lang('acc_time')?></th>
            <th align="left" scope="col"></th>
          </tr>
            <?php foreach($r_info as $k=>$v){?>
                <tr>
                  <td align="left"><?=$puri=='cn'?(!empty($v['cname'])?$v['cname']:''):(!empty($v['cenname'])?$v['cenname']:'')?></td>
                  <td align="left" class="red_font"><?=$puri=='cn'?(!empty($v['bname'])?$v['bname']:''):(!empty($v['benname'])?$v['benname']:'')?></td>
                  <td align="left"><?=lang('acc_di')?><?=!empty($v['floor'])?$v['floor']:''?><?=lang('acc_ceng')?></td>
                  <td align="left"><?=$puri=='cn'?(!empty($v['rname'])?$v['rname']:''):(!empty($v['renname'])?$v['renname']:'')?></td>
                  <td align="left"><?=!empty($v['createtime'])?date('Y-m-d H:i:s',$v['createtime']):''?></td>
                  <?php if($v['state']==0){?>
                      <td align="left"><label><a href="javascript:;" onclick="look_baoxiu(<?=$v['id']?>)"><?=lang('acc_ckbz')?></a></label>&nbsp;&nbsp;&nbsp;<label><a href="javascript:;" onclick="delete_baoxiu(<?=$v['id']?>)"><?=lang('electives_chexiao')?></a></label></td>
                  <?php }else{?>
                       <td align="left"><label><a href="javascript:;" onclick="look_baoxiu(<?=$v['id']?>)"><?=lang('acc_ckbz')?></a></td>
                  <?php }?>
                </tr>
          <?php }?>
        </table>
        <?php }else{?>
        <h3><?=lang('electives_nodata')?></h3>
        <?php }?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
 
function baoxiu(){
    var d = top.dialog({
//        title:'提示',
        id:'win_repairs',
        cancel:true,
        fixed:true,
        url:'/<?=$puri?>/student/accommodation/repairs'
    });
    d.show();

//$.ajax({
//    url: '/<?//=$puri?>///student/accommodation/repairs',
//    type: 'POST',
//    dataType: 'json',
//    data: {},
//  })
//  .done(function(r) {
//    if(r.state==1){
//       var d =top.dialog({
//           id:'win_repairs',
//          // url:'www.baidu.com'
//            content: r.data
//          //content: ''+r.data+''
//        });
//        d.show();
//    }
//      })
//  .fail(function() {
//    console.log("error");
//  })
//  .always(function() {
//    console.log("complete");
//  });
}
//查看保修
function look_baoxiu(id){
  $.ajax({
    url: '/<?=$puri?>/student/accommodation/look_repairs?id='+id,
    type: 'POST',
    dataType: 'json',
    data: {},
  })
  .done(function(r) {
    if(r.state==1){
       var d = dialog({
          content: ''+r.data+''
        });
        d.show();
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

  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
}
function delete_baoxiu(id){
 var d = dialog({
        content: '<?=lang("acc_sfsc")?>',
        ok: function () {
          var thats = this;
          setTimeout(function () {
            thats.close().remove();
          }, 8000);
           $.get('/<?=$puri?>/student/accommodation/delete_baoxiu?id='+id,{},function(r){
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