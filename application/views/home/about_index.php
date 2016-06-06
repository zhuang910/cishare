<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<div class="width_1024 clearfix mg-t-50">
	<div class="three-left-nav">
		<ul>
		<?php 
		
			if(!empty($l_n)){
				foreach($l_n as $lk => $lv ){
		?>
		<li class="<?=!empty($programaid) && $programaid == $lv['programaid']?'selected':''?>">
		<a href="/<?=$puri?>/about?programaid=<?=$lv['programaid']?>"><?=lang('nav_'.$lv['programaid'])?></a>
		</li>
		<?php }}?>
			
			
		</ul>
	</div>
	<div class="pickup-main">
		<?php if(!empty($result['content'])){?>
			<div class="crumbs-nav mb30"><a href="/<?=$puri?>"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/about?programaid=73"><?=lang('nav_73')?></a><i> / </i><span><?=lang('nav_'.$programaid)?></span></div>
				<div class="CUCAS-map-speak">
				<?=!empty($result['content'])?$result['content']:''?>
			</div>
	
	<?php }else{?>
	
	<?=lang('no_data')?>
	<?php }?>
		
	</div>
</div>
<?php $this->load->view('public/footer.php')?>
