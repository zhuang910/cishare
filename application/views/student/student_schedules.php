<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>

<div class="width_925">
  <h2 class="mt50 mb20"><span>
    <?=lang('nav_schedules')?>
    </span> 
    <span class="shenqingbtn">
        <a href="/<?=$puri?>/student/electives/index"><?=lang('electives_manage')?></a>
    </span>
    </h2>
  <div class="web_news">
    <div class="cjbox" >
      <!-- <h2>你当前不达标学时为：24个，还剩下6个学时您将被退学。</h2>-->
      <div class="connews">
        <?php if(empty($schedules)){echo '<h2>'.lang('schedules_noschedules').'</h2>';}?>
        <div class="con_con2" <?php if(empty($schedules)){echo 'style="display:none"';}?>>
          <dl class="clearfix">
            <dt class="clearfix cleartit"> <span class="a7"> </span> <span class="a6">
              <?=lang('schedules_Monday')?>
              </span> <span class="a6">
              <?=lang('schedules_Tuesday')?>
              </span> <span class="a6">
              <?=lang('schedules_Wednesday')?>
              </span> <span class="a6">
              <?=lang('schedules_Thursday')?>
              </span> <span class="a6">
              <?=lang('schedules_Friday')?>
              </span> <span class="a6">
              <?=lang('schedules_Saturday')?>
              </span> <span class="a6">
              <?=lang('schedules_Sunday')?>
              </span> </dt>
            <?php if(!empty($schedules)):?>
            <?php foreach($hour['hour'] as $k=>$v):?>
            <dd class="clearfix clearfix_list"> <span class="a7">
              <?php 
                               echo $v.lang('schedules_knob');
                               echo '<br />'.$time['hour'][$v];
                             ?>
              </span>
              <?php for($i=1;$i<8;$i++):?>
              <span class="a6">
              <?php
                                    foreach ($schedules as $key => $value) {
                                      $str='--';
                                      if($v==$value['knob']&&$i==$value['week']){
                                          if($puri=='cn'){
                                             $str=$value['cname'].'<br />'.$value['tname'].'<br />'.$value['rname'];break;
                                          }else{
                                              $str=$value['cenglishname'].'<br />'.$value['tenglishname'].'<br />'.$value['renglishname'];break;
                                          }
                                       
                                      }
                                       
                                    }
                                    if(!empty($ele_info)){
                                       foreach ($ele_info as $kk => $vv) {
                                        
                                            if($v==$vv['knob']&&$i==$vv['week']){
                                                if($puri=='cn'){
                                                   $str='<label style="color:red;">'.$vv['cname'].'<br />'.$vv['tname'].'<br />'.$vv['rname'].'</label>';break;
                                                }else{
                                                    $str='<label style="color:red;">'.$vv['cenname'].'<br />'.$vv['tenname'].'<br />'.$vv['renname'].'</label>';break;
                                                }
                                             
                                            }
                                         }
                                    }
                                   
                                     echo $str;
                                  ?>
              </span>
              <?php endfor;?>
            </dd>
            <?php endforeach;?>
            <?php endif;?>
          </dl>
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

</script>
<?php $this->load->view('student/footer.php')?>
