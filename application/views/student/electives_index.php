<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>
 <?php 
  $uriUrl = $this->uri->segment(4);
?>
<div class="width1024">
  <div class="wap_box p30">
    <h1><?=lang('electives_list')?></h1>

    <div class="tab pt30">
    <div class="Switch_title">
        <ul class="clearfix">
          <li style="border-right:none;" <?=!empty($uriUrl) && $uriUrl == 'index'?'class="active"':''?>>
           <a href="/<?=$puri?>/student/electives/index"><?=lang('electives_list')?></a></li>
          <li  style="border-right:none;" <?=!empty($uriUrl) && $uriUrl == 'collect'?'class="active"':''?>>
          <a href="/<?=$puri?>/student/electives/upelectives"><?=lang('electives_yibaolist')?></a></li>
          <li <?=!empty($uriUrl) && $uriUrl == 'collect'?'class="active"':''?>>
          <a href="/<?=$puri?>/student/electives/myelectives"><?=lang('electives_mylist')?></a></li>
          <li <?=!empty($uriUrl) && $uriUrl == 'collect'?'class="active"':''?>>
          <a href="/<?=$puri?>/student/electives/failelectives"><?=lang('electives_fail')?></a></li>
        </ul>
      </div>
      <!-- <div class="notes">Notice:You have to submit application for admission has been accepted, you can book accommodation, airport, or view Offer.</div> -->
      <div class="tab pt30 Switch_bg">
       <?php if(!empty($course_info)){?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('electives_course')?></th>
            <th align="left" scope="col"><?=lang('electives_teacher')?></th>
            <th align="left" scope="col"><?=lang('electives_room')?></th>
            <th align="left" scope="col"><?=lang('electives_time')?></th>
            <th align="left" scope="col"></th>
          </tr>
          
            <?php foreach($course_info as $k=>$v){?>
                <tr>
                  <td align="left"><!-- <input type="checkbox" name="checkbox" id="checkbox" /> -->
                   <?=$puri=='cn'?(!empty($v['cname'])?$v['cname']:''):(!empty($v['cenname'])?$v['cenname']:'')?></td>
                  <td align="left" class="red_font"><strong> <?=$puri=='cn'?(!empty($v['tname'])?$v['tname']:''):(!empty($v['tenname'])?$v['tenname']:'')?></td></strong></td>
                  <td align="left"><a href="#"> <?=$puri=='cn'?(!empty($v['rname'])?$v['rname']:''):(!empty($v['renname'])?$v['renname']:'')?></td></a></td>
                  <td align="left"><a href="#"><?=lang('electives_week'.$v['week'])?> <?=$v['knob']?><?=lang('electives_knob')?></a></td>
                  <td align="left"><a href="javascript:;" onclick="baoming(<?=$v['id']?>)"><?=lang('electives_baoming')?></a></td>
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
 function baoming(id){
 //  $.ajax({
 //    url: '/<?=$puri?>/student/electives/apply_course?sid='+id,
 //    type: 'GET',
 //    dataType: 'json',
 //    data: {},
 //  })
 //  .done(function(r) {
 //    if(r.state==1){
 //      var d = dialog({
 //          content: '<?=lang("electives_success")?>'
 //        });
 //        d.show();
 //        setTimeout(function () {
 //          d.close().remove();
 //        }, 2000);
 //    }
 //    if(r.state==0){
 //      var d = dialog({
 //          content: '<?=lang("electives_error")?>'
 //        });
 //        d.show();
 //        setTimeout(function () {
 //          d.close().remove();
 //        }, 2000);
 //    }
 //    if(r.state==2){
 //      var d = dialog({
 //          content: '<?=lang("electives_quanxian")?>'
 //        });
 //        d.show();
 //        setTimeout(function () {
 //          d.close().remove();
 //        }, 2000);
 //    }
 //      if(r.state==3){
 //      var d = dialog({
 //          content: '<?=lang("electives_chong")?>'
 //        });
 //        d.show();
 //        setTimeout(function () {
 //          d.close().remove();
 //        }, 2000);
 //    }
 //  })
 //  .fail(function() {
 //    console.log("error");
 //  })
  
 // }
 var d = dialog({
        content: '<?=lang("electives_content")?>',
        ok: function () {
          var thats = this;
          setTimeout(function () {
            thats.close().remove();
          }, 8000);
           $.get('/<?=$puri?>/student/electives/apply_course?sid='+id,{},function(r){
               if(r.state==1){
                  var d = dialog({
                      content: '<?=lang("electives_success")?>'
                    });
                    d.show();
                    setTimeout(function () {
                      d.close().remove();
                    }, 2000);
                    window.location.reload();
                }
                if(r.state==0){
                  var d = dialog({
                      content: '<?=lang("electives_error")?>'
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
                if(r.state==3){
                  var d = dialog({
                      content: '<?=lang("electives_chong")?>'
                    });
                    d.show();
                    setTimeout(function () {
                      d.close().remove();
                    }, 2000);
                }
                if(r.state==4){
                  var d = dialog({
                      content: '<?=lang("electives_notime")?>'
                    });
                    d.show();
                    setTimeout(function () {
                      d.close().remove();
                    }, 2000);
                }
                if(r.state==5){
                  var d = dialog({
                      content: '<?=lang("electives_noxutime")?>'
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