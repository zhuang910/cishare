<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<!--新闻-->
<div class="bannerpic7">
  <div class="width_1024">
    <!------标题从这里开始------->
      <span class="banner_font2">
        <span class="chinese_font">新闻中心</span> <span class="english_font">NEWS CENTER</span>
      </span>
       <!--------------------->
    </div>
</div>
<!--内容-->
<div class="width_1024 clearfix mg-t-50">
  <h2 class="mt50 mb30"><span>
    <?=lang('nav_'.$programaid)?>
    </span></h2>
  <div class="three-left-nav">
    <ul>
      <?php 
			if(!empty($l_n)){
					foreach($l_n as $lk => $lv ){
					if($lv['programaid'] != 105 && $lv['programaid'] != 87){
					
			?>
      <li class="<?=!empty($programaid) && $programaid == $lv['programaid']?'selected':''?>"> <a href="/<?=$puri?>/news?programaid=<?=$lv['programaid']?>">
        <?=lang('nav_'.$lv['programaid'])?>
        </a> </li>
      <?php }}}?>
	  
	  <li class="<?=!empty($programaid) && $programaid == 39?'selected':''?>"> <a href="/<?=$puri?>/news?programaid=39">
        <?=lang('nav_39')?>
        </a> </li>
    </ul>
  </div>
  <div class="newscenter-main">
    <?php if(!empty($news)){
	$count = count($news);
			foreach($news as $k => $v){
		?>

	 <dl class="news_list_new <?=$k == $count - 1?'botm_lin':''?> clearfix" >
    <a href="/<?=$puri?>/news/detail?id=<?=$v['id']?>">
    <dt><img src="<?=!empty($v['image'])?$v['image']:''?>"  alt="" <?=$programaid == '86' ? 'style="height:auto;"' : ''?> <?=$programaid == '39' ? 'style="width:auto;"' : ''?>/></dt>
    <dd>
    <div class="news_title_new"><span> <?=date('Y/m/d',$v['createtime'])?></span><em> <?=!empty($v['title'])?$v['title']:''?></em></div>
    <div class="news_txt_new"> <?=!empty($v['description'])?$v['description']:''?> </div>
    <div class="news_btn_new"><span> <?=lang('look_detail')?></span></div>
    </dd>
    </a>
  </dl>
    <?php }}?>
  </div>
</div>
<div class="width_1024 clearfix">
  <?=$pagestring?>
</div>

<?php $this->load->view('public/footer.php')?>
