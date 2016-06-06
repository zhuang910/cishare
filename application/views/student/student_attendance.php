<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>

<div class="width_925">
<h2 class="mt50 mb20"><span><?=lang('nav_attendance')?></span> 
  
  
 
  <?php if(!empty($term)):?>
   
   <span style="display:<?php echo !empty($attendance)?'block':'none'?>;" class="shenqingbtn"><a href="javascript:;" onclick="dayin()"><?=lang('attendance_print')?></a></span>
  <span style="display:<?php echo !empty($attendance)?'block':'none'?>;"class="shenqingbtn"><a href="javascript:;" onclick="daochu('<?=$puri?>')"><?=lang('attendance_export')?></a></span>
  <div class="setdelect">
    <select id="select" onchange="term()">
    <?php for($i=1;$i<=$term;$i++):?>
       <option <?=$nowterm==$i?'selected="selected"':''?> value="<?=$i?>"><?=lang('attendance_di')?><?=$i?><?=lang('attendance_term')?></option>
    <?php endfor;?>
    </select>

  </div>
 <?php endif;?> 
  </h2>

    <div class="web_news">
        <div class="connews" id='dayin'>
            <div class="cjbox">
               <!-- <h2>你当前不达标学时为：24个，还剩下6个学时您将被退学。</h2>-->
               <?php if(empty($attendance)){echo '<h2>'.lang('attendance_full').'</h2>';}?>
                  <div class="cjtit" style="display:<?php echo !empty($attendance)?'block':'none'?>;">
                    <p class="wid_160"><?=lang('attendance_date')?></p><p class="wid_210"><?=lang('attendance_major-course')?></p><p class="wid_160"><?=lang('attendance_type')?></p><p class="wid_275"><?=lang('attendance_explain')?></p>
                  </div>
                  <div class="forlist">
                  <?php if(!empty($attendance)):?>
                    <?php foreach($attendance as $k=>$v):?>
                    <li><p class="wid_160"><?=$v['date']?></p><p class="wid_210"><?php echo  $puri=='cn'?$v['mname']:$v['menglishname']?>-<?php echo  $puri=='cn'?$v['name']:$v['englishname']?></p><p class="wid_160"><?=lang('attendance_type_'.$v['type'])?></p><p class="wid_160"><?=$v['knob']?><?=lang('schedules_knob')?></p><p class="wid_275"></p></li>
                    <?php endforeach;?>
                  <?php endif;?>
                  </div>
            </div>
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
  window.location.href='/<?=$puri?>/student/student/checking?term='+term;     
}
function daochu(type){
    var term=$('#select').val();
     window.location.href='/<?=$puri?>/student/student/checking_export?term='+term+'&type='+type;
   
}
function dayin(){

  document.body.innerHTML=document.getElementById('dayin').innerHTML;
  window.print();
  window.location.href='/<?=$puri?>/student/student/checking';
}
function more(){
    $.ajax({
              url: '/student/student/checking_more',
              type: 'GET',
              dataType: 'json'
            })
            .done(function(r) {
              if(r.state==1){
                var last_li=$('#dayin li:last')
                var str='';
                $.each(r.data,function(k,v){
                     var type='';
                  
                    switch(v.type){
                      case 0:
                      type=<?=lang('attendance_type_0')?>;
                      break;
                      case 1:
                      type=<?=lang('attendance_type_1')?>;
                      break;
                      case 2:
                      type=<?=lang('attendance_type_2')?>;
                      break;
                      case 3:
                      type=<?=lang('attendance_type_3')?>;
                      break;
                    }
                   str+='<li><p class="wid_160">'+v.date+'</p><p class="wid_210">'+v.mname+'-'+v.name+'</p><p class="wid_160">'+type+'</p><p class="wid_160">'+v.knob+'</p><p class="wid_275"></p></li>';
                })
                last_li.after(str);
                $('.cjpj').find('a').attr('onclick','packup()');
                $('.cjpj').find('a').text('<?=lang('attendance_packup')?>');
              }
            })
            .fail(function() {
              console.log("error");
            })
}
function packup(){
  window.location='/<?=$puri?>/student/student/checking';
}
</script>
<?php $this->load->view('student/footer.php')?>
