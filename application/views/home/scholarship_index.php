<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<div class="width_1024 clearfix mg-t-50">
	<div class="three-left-nav">
		<ul>
		<?php 
		
			if(!empty($list)){
				foreach($list as $lk => $lv ){
					
						
		?>
		<li class="<?=!empty($programaid) && $programaid == $lv['id']?'selected':''?>">
		<a href="/<?=$puri?>/scholarship?programaid=<?=$lv['id']?>"><?=$lv['title']?></a>
		</li>
		<?php }}?>
			
			
		</ul>
	</div>
	<div class="pickup-main">
		<div class="crumbs-nav mb30"><a href="/<?=$puri?>"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/scholarship"><?=lang('nav_68')?></a><i> / </i><span><?=$result['title']?></span></div>
		
		<div class="f_l share-right">
		<div class="borderbox">
		<div class="sysj">
			<ul>
				<li class="setwt"><div class="f_l width-35"><?=lang('schlorship_title')?></div><div class="f_l width-65"><?=!empty($result['title'])?$result['title']:''?></div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_count')?></div><div class="f_l width-65"><span class="jjnum">
				<?php 
					if(empty($result['count'])){
				?>
				--
				<?php }else{
					$count = $result['count'] - $count_apply;
					if($count <= 0){
						echo 0;
					}else{
						echo $count;
					}
				
				}?>
					
				
				
				</span></div></li>
				<li class="setwt"><div class="f_l width-35"><?=lang('schlorship_money')?></div><div class="f_l width-65"><?=!empty($result['money'])?$result['money']:'--'?></div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_endtime')?></div><div class="f_l width-65"><strong>
				<?=!empty($result['applyendtime'])?date('Y-m-d',$result['applyendtime']):'None'?></strong></div></li>
				<li class="setwt"><div class="f_l width-35"><?=lang('schlorship_info')?>:</div><div class="f_l width-65"><?=!empty($result['intro'])?$result['intro']:''?></div></li>
				<li><div class="f_l width-35"><?=lang('schlorship_content')?></div><div class="f_l width-65"><?=!empty($result['condition'])?$result['condition']:''?></div></li>

			</ul>
		</div>
		</div>
		</div>
	</div>
</div>
<?php $this->load->view('public/footer.php')?>
