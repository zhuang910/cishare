<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<!--nav end-->
<!--banner start住宿-->
<div class="bannerpic3">
  <div class="width_1024">
    <!------标题从这里开始------->
      <span class="banner_font2">
        <span class="chinese_font">校内住宿</span> <span class="english_font">Accommodation</span>
      </span>
       <!--------------------->
    </div>
</div>
<!--banner end-->
<!--main start-->
<div class="width_1024 clearfix">
  <h2 class="mt50 mb30"><span>
    <?=lang('nav_67')?>
    </span></h2>
  <div class="about-school-main-news blue-top"> <a href="/<?=$puri?>/accommodation_introduce"> <img src="<?=RES?>/home/images/accommodation/nav_95.png" alt=""/>
    <div class="about-school-main-describe">
      <h5>
        <?=lang('nav_92')?>
      </h5>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
  <div class="about-school-main-news blue-top"> <a href="/<?=$puri?>/accommodation_book"> <img src="<?=RES?>/home/images/accommodation/nav_94.png" alt=""/>
    <div class="about-school-main-describe">
      <h5>
        <?=lang('nav_94')?>
      </h5>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
 <!-- <div class="about-school-main-news"> <a href="/<?=$puri?>/accommodation?programaid=93"> <img src="<?=RES?>/home/images/accommodation/nav_93.png" alt=""/>
    <div class="about-school-main-describe">
      <h5>
        <?=lang('nav_93')?>
      </h5>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>
  <div class="about-school-main-news mg_r_10"> <a href="/<?=$puri?>/accommodation?programaid=92" target="_blank"> <img src="<?=RES?>/home/images/accommodation/nav_92.png" alt=""/>
    <div class="about-school-main-describe">
      <h5>
        <?=lang('nav_92')?>
      </h5>
      <span class="about-school-main-news-see">
      <?=lang('look_detail')?>
      </span> </div>
    </a> </div>-->
  <div class="clear"></div>
</div>
<?php $this->load->view('public/footer.php')?>
