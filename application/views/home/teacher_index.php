<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<div class="bannerpic1">
  <div class="width_1024">
    <!------标题从这里开始------->
      <span class="banner_font2">
        <span class="chinese_font">师资力量</span> <span class="english_font">Faculty Staff</span>
      </span>
       <!--------------------->
    </div>
</div>
<!--main start师资-->
<div class="width_1024 clearfix">
  <h2 class="mt50 mb30"><span>
    <?=lang('teacher_style')?>
    </span></h2>
	<?php if(!empty($teacher_hyms)){?>
  <div class="blue-btn"><a href="javascript:;">
    <?=lang('nav_103')?>
    </a></div>
<?php }?>
  <?php 
		if(!empty($teacher_hyms)){
			foreach($teacher_hyms as $k => $v){
			
	?>
  <div class="blue-top teacher-china <?php if( ($k+1) %3 == 0){?>mg_r_10<?php }?>"> <a href="/<?=$puri?>/teacher/detail?id=<?=$v['id']?>">
    <dl>
      <dt><img src="<?=!empty($v['photo'])?$v['photo']:''?>" width="117" height="154"></dt>
      <dd class="ft-18">
        <?php if($puri == 'cn'){?>
		<?=!empty($v['name'])?$v['name']:''?>
		<?php }else{?>
		<?=!empty($teacher_name[$v['teacherid']])?$teacher_name[$v['teacherid']]:''?>
		<?php }?>
      </dd>
      <dd class="teacher-history">
        <?=!empty($v['info'])?$v['info']:''?>
      </dd>
      <dd><span class="about-school-main-news-see">
        <?=lang('look_detail')?>
        </span></dd>
    </dl>
    </a> </div>
  <?php }}?>
  <div class="clear"></div>
  <?php if(!empty($teacher_zyms)){?>
  <div class="blue-btn"><a href="javascript:;" style="padding-left:30px;">
    <div class="f_l">
      <?=lang('nav_104')?>
    </div>
    </a></div>
<?php }?>
  <?php 
		if(!empty($teacher_zyms)){
			foreach($teacher_zyms as $k => $v){
			
	?>
  <div class="blue-top teacher-china <?php if( ($k+1) %3 == 0){?>mg_r_10<?php }?>"> <a href="/<?=$puri?>/teacher/detail?id=<?=$v['id']?>">
    <dl>
      <dt><img src="<?=!empty($v['photo'])?$v['photo']:''?>" width="117" height="154"></dt>
      <dd class="ft-18">
        <?=!empty($v['name'])?$v['name']:''?>
      </dd>
      <dd class="teacher-history">
        <?=!empty($v['info'])?$v['info']:''?>
      </dd>
      <dd><span class="about-school-main-news-see">
        <?=lang('look_detail')?>
        </span></dd>
    </dl>
    </a> </div>
  <?php }}?>
</div>
<?php $this->load->view('public/footer.php')?>
