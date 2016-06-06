<?php $this->load->view('student/header.php')?>
<div class="find-password-main width_925 clearfix p50">
  <div class="success-main-container-1"> <span class="gongxi"><?=lang('gongxi')?>，<?=!empty($email)?$email:''?> <?=lang('yjhcg')?>!</span>
    <a class="success-btn" href="/<?=$puri?>/student/index" target="_blank"><?=lang('fhgrzx')?></a> </div>

</div>

<?php $this->load->view('student/footer_no.php')?>
