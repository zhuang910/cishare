<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>
<div style="position:absolute; top:120px; right:283px;">
<span class="shenqingbtn"><a href="javascript:history.back();" ><?=lang('activity_return')?></a></span>
</div>
<div class="width783">

  <div class="wap_box2">
    <h3><span><?=!empty($result['title'])?$result['title']:''?></span><em><?=lang('date_release')?>: <?=date('Y-m-d',$result['createtime'])?></em></h3>
    <div class="font_box">
	<?=!empty($result['content'])?$result['content']:''?>
    </div>
  </div>
</div>
<?php $this->load->view('student/footer_no.php')?>
