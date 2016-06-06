<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<!--nav end-->
<!--banner start奖学金-->
<div class="bannerpic4">
  <div class="width_1024">
    <!------标题从这里开始------->
      <span class="banner_font2">
        <span class="chinese_font">助学基金</span> <span class="english_font">Scholarship</span>
      </span>
       <!--------------------->
    </div>
</div>
<!--banner end-->
<!--main start-->
<div class="width_1024 clearfix">
  <h2 class="mt50 mb20"><span>
    <?=lang('nav_68')?>
    </span></h2>
  <div class="for-contact">
    <?=lang('scholarship_introduce')?>
  </div>
  <div class="blue-top manageandservice"> <a href="/<?=$puri?>/scholarship?programaid=101"> <img src="<?=RES?>/home/images/scholarship/nav_101.png"/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_101')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('scholarship_introduce_101')?>
      </p>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
  <div class="blue-top manageandservice"> <a href="/<?=$puri?>/scholarship?programaid=100"> <img src="<?=RES?>/home/images/scholarship/nav_100.png" alt=""/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_100')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('scholarship_introduce_100')?>
      </p>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
  <div class="blue-top manageandservice mg_r_10"> <a href="/<?=$puri?>/scholarship?programaid=99"> <img src="<?=RES?>/home/images/scholarship/nav_99.png"/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_99')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('scholarship_introduce_99')?>
      </p>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
  <div class="blue-top manageandservice"> <a href="/<?=$puri?>/scholarship?programaid=98"> <img src="<?=RES?>/home/images/scholarship/nav_98.png" alt=""/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_98')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('scholarship_introduce_98')?>
      </p>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
  <div class="blue-top manageandservice"> <a href="/<?=$puri?>/scholarship?programaid=97"> <img src="<?=RES?>/home/images/scholarship/nav_97.png" alt=""/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_97')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('scholarship_introduce_97')?>
      </p>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
 <!-- <div class="blue-top manageandservice mg_r_10"> <a href="/<?=$puri?>/scholarship?programaid=96"> <img src="<?=RES?>/home/images/scholarship/nav_96.png" alt=""/>
    <div class="manageandservice-describe">
      <h5 class="mg-b-15">
        <?=lang('nav_96')?>
      </h5>
      <p class="ft-12-5">
        <?=lang('scholarship_introduce_96')?>
      </p>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>-->
</div>
<?php $this->load->view('public/footer.php')?>
