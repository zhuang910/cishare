<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<!--内容-->
<div class="width_1024 clearfix">
    <h3 class="search_tit" style="margin-top:40px;">
	<?php if(!empty($news)){?>
	
	<?=lang('search_1')?>：<font size="12" color="red"><?=!empty($search)?$search:''?></font>
		<?=lang('search_2')?>：
	<?php }else{?>
	<?=lang('search_3')?>：<font size="12" color="red"><?=!empty($search)?$search:''?></font>
		<?=lang('search_4')?>
	<?php }?>
	</h3>

	
	  <div class="newscenter-main" style="width:1024px;">
    <?php if(!empty($news)){
	$count = count($news);
			foreach($news as $k => $v){
		?>

	 <dl class="news_list_new <?=$k == $count - 1?'botm_lin':''?> clearfix" id="hxk_list" style="width:1024px;">
    <a href="/<?=$puri?>/news/detail?id=<?=$v['id']?>">
    <dt><img src="<?=!empty($v['image'])?$v['image']:''?>"  alt="" style="height:auto;"/></dt>
    <dd style="width:795px;">
    <div class="news_title_new"><span> <?=date('Y/m/d',$v['createtime'])?></span><em> <?=!empty($v['title'])?$v['title']:''?></em></div>
    <div class="news_txt_new"> <?=!empty($v['description'])?$v['description']:''?> </div>
    <div class="news_btn_new"><span> <?=lang('look_detail')?></span></div>
    </dd>
    </a>
  </dl>
    <?php }}?>
  </div>
</div>

<?php $this->load->view('public/footer.php')?>
