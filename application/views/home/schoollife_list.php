<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<!--nav end-->
<!--banner start校园生活-->
<div class="bannerpic2">
  <div class="width_1024">
    <!------标题从这里开始------->
      <span class="banner_font2">
        <span class="chinese_font">校园生活</span> <span class="english_font">Campus Life</span>
      </span>
       <!--------------------->
    </div>
</div>
<!--banner end-->
<!--main start-->
<div class="width_1024 clearfix">
  <h2 class="mt50 mb20"><span>
    <?=lang('nav_30')?>
    </span></h2>
  <div class="for-contact">
    <?=lang('schoollife_introduce')?>
  </div>
  <div class="blue-top manageandservice"> <a href="/<?=$puri?>/schoollife/facilities?programaid=32"> <img src="<?=RES?>/home/images/schoollife/nav_32.png"/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_32')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('schoollife_introduce_32')?>
      </p>
      <span class="schoollife-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
  <div class="blue-top manageandservice"> <a href="/<?=$puri?>/schoollife/share?programaid=35"> <img src="<?=RES?>/home/images/schoollife/nav_35.png" alt=""/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_35')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('schoollife_introduce_35')?>
      </p>
      <span class="schoollife-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
  <div class="blue-top manageandservice mg_r_10"> <a href="/<?=$puri?>/news?programaid=39"> <img src="<?=RES?>/home/images/schoollife/nav_39.png"/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_39')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('schoollife_introduce_39')?>
      </p>
      <span class="schoollife-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
 
</div>
<?php $this->load->view('public/footer.php')?>
