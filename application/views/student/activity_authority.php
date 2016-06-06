<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('activity_authority')?></span><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/launch"><?=lang('activity_return')?></a></em></h1>
    <div class="tab pt30">
		
    </div>
  </div>
</div>

<script type="text/javascript">
 setTimeout("window.location.href='/<?=$puri?>/student/activity/launch'", 5000);
</script>
<?php $this->load->view('student/footer.php')?>
