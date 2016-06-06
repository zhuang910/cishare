<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>


<div class="width1024">

  <div class="wap_box p30">
    <h1><?=lang('evaluate_in')?><?=date('Y',time())?><?=lang('evaluate_tea_eav')?>   <?=$coursename?> - <?=$teachername?></h1>
    <div class="tab pt30">
      <div class="nub_01">
        <dl class="clearfix">
          <dt class="one_btn fl"><a href="/<?=$puri?>/student/index"><?=lang('evaluate_tip')?></a></dt>
          <?php foreach($evaluate_info as $k=>$v):?>
            <dd class="fl <?=!empty($courseid)&&$courseid==$v['courseid']&&$teacherid==$v['teacherid']?'active':''?>"><a style="display:block;" onclick="course_teacher(<?=$v['courseid']?>,<?=$v['teacherid']?>)" href="javascript:;"><?=$k+1?></a></dd>
          <?php endforeach;?>
        
          <dt class="two_btn fl"><a href="javascript:;" onclick="accomplish_eva(<?=$_SESSION['student']['userinfo']['id']?>)"><?=lang('evaluate_wancheng')?></a></dt>
        </dl>
      </div>
     <!--  <div style="">
        <span style="font-size:20px;"><?=$coursename?> - <?=$teachername?></span>
      </div> -->
      <form id="eva_form" method="post">
      <?php if(!empty($class_info)):?>
        <?php foreach($class_info as $k=>$v):?>
          <div class="gray_bg mt30">
            <?php if($v['type']==1&&!empty($item_info[$v['id']])):?>
              <dl class="answer">
                  <dt><?=$puri=='cn'?$v['name']:$v['enname']?></dt>
              <?php foreach($item_info[$v['id']] as $kk=>$vv):?>
                

                  <dd> <span><?=$vv['title']?></span>
                     <em>
                       <?php
                          if(!empty($vv['answer_score'])){
                            $answer_score=json_decode($vv['answer_score'],true);
                       ?>
                        <?php foreach($answer_score as $kan=>$van):?>
                          <?php
                          if(!empty($stu_answer)){
                            if(!empty($stu_answer['item'.$vv['id']])&&$stu_answer['item'.$vv['id']]==$kan.'-grf-'.$van){
                              $checked='checked="checked"';
                            }else{
                              $checked='';
                            }
                          }else{
                            $checked='';
                          }
                            
                          ?>
                           <input style="margin-right:5px;" id="<?=$kan?>-grf-<?=$van?>-<?=$vv['id']?>" name="answer[item<?=$vv['id']?>]" <?=$checked?> type="radio" value="<?=$kan?>-grf-<?=$van?>" /><label style="margin-right:20px;" for="<?=$kan?>-grf-<?=$van?>-<?=$vv['id']?>"><?=lang('evaluate_answer_'.$kan.'')?></label>
                        <?php endforeach;?>
                        <?php }?>
                     </em>
                  </dd>
                
                
            <?php endforeach;?>
              </dl>
            <?php elseif($v['type']==2):?>
              <dl class="answer2" style="padding-top:0;">
                <dd class="heg_box"> <span><?=$puri=='cn'?$v['name']:$v['enname']?></span>
                 <em>
                    <textarea name="answer[class<?=$v['id']?>]" cols="" rows=""><?=!empty($stu_answer['class'.$v['id']])?$stu_answer['class'.$v['id']]:''?></textarea>
                 </em> 
                </dd>
              </dl>
            <?php endif;?>
          </div>
        <?php endforeach;?>
      <?php endif;?>
    
      <div class="enter_btn clearfix">
        <div class="gary_btn fl"><a href="javascript:;" onclick="save()"><?=lang('evaluate_save')?></a></div>
        <div class="blue_btn fl"><a onclick="accomplish_eva(<?=$_SESSION['student']['userinfo']['id']?>)" href="javascript:;"><?=lang('evaluate_goon')?></a></div>

      </div>
    </div>
  </div>
</div>
      <input type="hidden" name="userid" value="<?=$_SESSION['student']['userinfo']['id']?>">
      <input type="hidden" name="majorid" value="<?=!empty($majorid)?$majorid:''?>">
      <input type="hidden" name="squadid" value="<?=!empty($squadid)?$squadid:''?>">
      <input type="hidden" name="term" value="<?=!empty($term)?$term:''?>">
      <input type="hidden" name="courseid" value="<?=!empty($courseid)?$courseid:''?>">
      <input type="hidden" name="teacherid" value="<?=!empty($teacherid)?$teacherid:''?>">
 </form>

<script type="text/javascript">
function go_on(){
  window.location='/<?=$puri?>/student/index/evaluate_page';
}
function course_teacher(courseid,teacherid){
  window.location='/<?=$puri?>/student/index/evaluate_page?courseid='+courseid+'&teacherid='+teacherid;
}
function select_answer(th){
  $(th).children().attr('checked', 'checked');
}
function save(){
  var data=$('#eva_form').serialize();
  $.ajax({
    url: '/student/index/sava_eva_item',
    type: 'POST',
    dataType: 'json',
    data: data,
  })
  .done(function(r) {
    if(r.state==2){
      var d = dialog({
          content: ''+r.info+''
        });
        d.show();
        setTimeout(function () {
          d.close().remove();
        }, 2000);
    }
    if(r.state==1){
      var d = dialog({
          content: ''+r.info+''
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
function accomplish_eva(userid){
  $.ajax({
    url: '/student/index/accomplish_eva?userid='+userid,
    type: 'GET',
    dataType: 'json',
    data: {},
  })
  .done(function(r) {
    if(r.state==1){
      window.location.href="/<?=$puri?>/student/index";
    }
    if(r.state==2){
       window.location.href="/<?=$puri?>/student/index/evaluate_page?courseid="+r.data.courseid+"&teacherid="+r.data.teacherid;
    }
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
  
}
</script>
<?php $this->load->view('student/footer.php')?>
