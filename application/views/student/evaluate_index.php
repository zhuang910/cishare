<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>


<div class="width1024">
  <div class="wap_box p30">
      <h1><?=lang('evaluate_in')?><?=date('Y',time())?><?=lang('evaluate_tea_eav')?></h1>
      <div class="tab pt30">
        <div class="nub_01">
          <dl class="clearfix">
            <dt class="one_btn fl"><a href="javascript:;"><?=lang('evaluate_tip')?></a></dt>
           <!--  <?php if(!empty($evaluate_info)):?>
             <?php foreach($evaluate_info as $k=>$v):?>
              <dd class="fl"><a style="display:block;" onclick="course_teacher(<?=$v['courseid']?>,<?=$v['teacherid']?>)" href="javascript:;"><?=$k+1?></a><span><?=$v['cname']?></span></dd>
             <?php endforeach;?>
            <?php endif;?> -->
          </dl>
        </div>
        <div class="gray_bg mt30">

         <?php 
         if(!empty($evaluate_time['matters'])){
            echo $evaluate_time['matters'];
          }
          ?>
          <div class="btn clearfix"><a href="javascript:;" onclick="go_on()"><?=lang('evaluate_next')?></a></div>
        </div>
      </div>
    </div>
  </div>
  

<script type="text/javascript">
function go_on(){
  window.location='/<?=$puri?>/student/index/evaluate_page';
}
</script>
<?php $this->load->view('student/footer.php')?>
