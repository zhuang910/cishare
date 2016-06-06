<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>

<div class="width_925">
<h2 class="mt50 mb20"><span><?=lang('nav_achievement')?></span> 
  
  
  
  <?php if(!empty($term)):?>
      <div style="float:right" class="btn_centre">
      <select id="select" onchange="term()">
        <?php for($i=1;$i<=$term;$i++):?>
          <option <?=$nowterm==$i?'selected="selected"':''?> value="<?=$i?>"><?=lang('attendance_di')?><?=$i?><?=lang('attendance_term')?></option>
       <?php endfor;?>
      </select>
      <select id="selects" onchange="scoretype()">
       <?php foreach($scoretype as $k=>$v):?>
          <option <?=$scoretypes==$v['id']?'selected="selected"':''?> value="<?=$v['id']?>"><?php echo  $puri=='cn'?$v['name']:$v['enname']?></option>
       <?php endforeach;?>
      </select>
      <span style="display:<?php echo !empty($achievement)?'block':'none'?>;" class="shenqingbtn"><a href="javascript:;" onclick="dayin()"><?=lang('achievement_print')?></a></span>
      <span style="display:<?php echo !empty($achievement)?'block':'none'?>;" class="shenqingbtn"><a href="javascript:;" onclick="daochu('<?=$puri?>')"><?=lang('achievement_export')?></a></span>
      </div>
  <?php endif;?>
</h2>

    <div class="web_news">
        <div class="connews" id='dayin'>
            <div class="cjbox">
            <div style=" text-align:center;font-size:12px;height: 40px;line-height: 40px;display:<?php echo !empty($achievement)?'block':'none'?>;">
              <?php echo  $puri=='cn'?$majorname['name']:$majorname['enname']?> - <?=lang('attendance_di')?><?=$nowterm?><?=lang('attendance_term')?> - <?php echo  $puri=='cn'?$squadname['name']:$squadname['enname']?>
            </div>
               <!-- <h2>你当前不达标学时为：24个，还剩下6个学时您将被退学。</h2>-->
               <?php if(empty($achievement)){echo '<h2>'.lang('achievement_noscore').'</h2>';}?>
                  <div class="cjtit" style="display:<?php echo !empty($achievement)?'block':'none'?>;">
                    <p class="wid_210"><?=lang('achievement_course')?></p><p class="wid_210"><?=lang('achievement_score')?></p><p class="wid_210"><?=lang('ketangbiaoxian')?></p><p class="wid_210"><?=lang('achievement_type')?></p>
                  </div>
                  <div class="forlist">
                  <?php if(!empty($achievement)):?>
                    <?php foreach($achievement as $k=>$v):?>
                    <li><p class="wid_210" style="width:210px;"><?php echo  $puri=='cn'?$v['name']:$v['englishname']?></p><p class="wid_210" style="width:210px;"><?=$v['score']?></p><p class="wid_210" style="width:210px;"><?=!empty($v['show_score'])?$v['show_score']:''?></p><p class="wi" style="width:210px;"><?=lang('achievement_notice_type_'.$v['type'])?></p><p class="wid_210" style="width:210px;"></p></li>
                    <?php endforeach;?>
                  <?php endif;?>
                  </div>
            </div>
              <div style="display:<?php echo !empty($achievement)?'block':'none'?>;" class="cjpj"><p class="wid_206"><?=lang('achievement_avgscore')?></p><p class="wid_160"><?=$avgscore?></p></div>

        </div>
      </div>
</div>
<!--
<div class="width_925">
  <h3 class="mt50 mb20"><span>我的考勤</span></h3>
 <div class="web_news">
  <div class="connews">
  <div class="cjbox">
      <h2>你当前不达标学时为：24个，还剩下6个学时您将被退学。</h2>
    <div class="cjtit">
      <p class="wid_160">课程</p><p class="wid_160">分数</p><p class="wid_160">类型</p><p class="wid_275">说明</p>
    </div>
    <div class="forlist">
      <li><p class="wid_160">语文</p><p class="wid_160">65</p><p class="wid_160">合格</p><p class="wid_275"></p></li>
      <li><p class="wid_160">语文</p><p class="wid_160">65</p><p class="wid_160">合格</p><p class="wid_275"></p></li>
      <li><p class="wid_160">语文</p><p class="wid_160">65</p><p class="wid_160">合格</p><p class="wid_275"></p></li>
    </div>
    <div class="cjpj"><p class="wid_160">平均分</p><p class="wid_160">66.5</p></div>
  </div>
  </div>-->
  

<script type="text/javascript">
function term(){
  var term=$('#select').val();
   var scoretype=$('#selects').val();
  window.location.href='/<?=$puri?>/student/student/score?term='+term+'&scoretype='+scoretype;     
}
function scoretype(){
  var term=$('#select').val();
  var scoretype=$('#selects').val();
   window.location.href='/<?=$puri?>/student/student/score?term='+term+'&scoretype='+scoretype;
}
function daochu(type){
    var term=$('#select').val();
    var scoretype=$('#selects').val();
     window.location.href='/<?=$puri?>/student/student/score_export?term='+term+'&scoretype='+scoretype+'&type='+type;
   
}
function dayin(){

  document.body.innerHTML=document.getElementById('dayin').innerHTML;
  window.print();
  window.location.href='/<?=$puri?>/student/student/score';
}

</script>
<?php $this->load->view('student/footer.php')?>
