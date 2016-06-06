<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<link href="<?=RES?>home/css/link.2012.css" rel="stylesheet"
	type="text/css" />
<?php $this->load->view('public/nav.php')?>
<div class="width_1024">
	<div class="link_box">
		<div class="link_top">
			<div class="top_right"></div>
			<div class="top_left"></div>
		</div>
		<div class="link_content">
			<h3 class="link_tit"> <?=lang('nav_84')?></h3>
			<ul class="link_list">
			<?php if(!empty($result)){
				
			?>
				<?php foreach( $result as $k => $v){?>

					<li style="border-bottom: 0;"><a href="<?=$v['url']?>"
							target="_blank"><?=!empty($v['title'])?$v['title']:''?> </a></li>

				<?php }}?>
			</ul>
		</div>
		<div class="link_bottom">
			<div class="bottom_right"></div>
			<div class="bottom_left"></div>
		</div>
	</div>
</div>
<?php $this->load->view('public/footer.php')?>
