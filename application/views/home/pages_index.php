<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<div class="width_1024 clearfix mg-t-50">
	<div class="three-left-nav">
		<ul>
		<?php 
	
			if(!empty($l_n)){
				foreach($l_n as $lk => $lv ){
				if($lv['programaid'] != 45){
		?>
		<li class="<?=!empty($programaid) && $programaid == $lv['programaid']?'selected':''?>">
		<a href="/<?=$puri?>/<?=$lv['identify']?>?programaid=<?=$lv['programaid']?>"><?=lang('nav_'.$lv['programaid'])?></a>
		</li>
		<?php }}}?>
			
			
		</ul>
	</div>
	<div class="pickup-main">
		<div class="crumbs-nav mb30"><a href="/<?=$puri?>"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/pages?programaid=44"><?=lang('nav_44')?></a><i> / </i><span><?=lang('nav_'.$programaid)?></span></div>
		<div class="CUCAS-map-speak">
			<?=!empty($result['content'])?$result['content']:''?>
		</div>
	</div>
</div>
<?php $this->load->view('public/footer.php')?>
