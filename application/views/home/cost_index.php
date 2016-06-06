<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<div class="width_1024 clearfix mg-t-50">
  <div class="three-left-nav">
    <ul>
      <li class="selected"> <a href="/<?=$puri?>/cost?programaid=69">
        <?=lang('nav_69')?>
        </a> </li>
    </ul>
  </div>
  <div class="pickup-main">
  <div class="crumbs-nav mb30"><a href="/<?=$puri?>/"><?=lang('nav_1')?></a><i> / </i><span><?=lang('nav_69')?></span></div>
    <div class="CUCAS-map-speak">
      <?=!empty($result['content'])?$result['content']:''?>
    </div>
  </div>
</div>
<?php $this->load->view('public/footer.php')?>
