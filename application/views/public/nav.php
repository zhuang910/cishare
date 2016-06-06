<!--nav start-->
<div class="nav min">
  <div class="width_1024 nav-no <?php if($puri == 'en'){?> engmodel<?php }?>">
    <ul>
	<?php 
		if(!empty($head_nav)){
			foreach($head_nav as $hk => $hv){
	?>
		<?php 
			if($hv['identify'] == '/'){
		?>
		 <li class="home"><a href="/<?=$puri?>/"><i></i></a></li>
		<?php }else{?>
			<!--特色课程调取的是专业 比较特殊-->
			<?php if($hv['programaid'] == 2){?>
				<li class="meiyoujiao"><a href="/<?=$puri?>/course?programaid=0" class="mod_show_on"><?=lang('nav_'.$hv['programaid'])?></a>
				<!--<dl class="bg-sanjiao">
						<?php foreach($degree as $dk => $dv){
								
						?>
						
						  <dd><a href="/<?=$puri?>/course/feature?programaid=<?=$dv['id']?>"><?=lang('degree_'.$dv['id'])?></a></dd>
						
						<?php }?>
					</dl>-->
				  </li>
				
					<!--住宿 比较特殊-->
			<?php }else if($hv['programaid'] == 67){?>
				<li><a href="/<?=$puri?>/accommodation" class="bg-sanjiao mod_show_on"><?=lang('nav_67')?></a>
					<dl>
					  <dd><a href="/<?=$puri?>/accommodation_introduce"><?=lang('nav_92')?></a></dd>
					  <dd><a href="/<?=$puri?>/accommodation_book"><?=lang('nav_94')?> </a></dd>
					</dl>
				  </li>
			<?php }else if($hv['programaid'] == 68){?>
				<li  class="meiyoujiao"><a href="/<?=$puri?>/scholarship" class="mod_show_on"><?=lang('nav_68')?></a>
					
				  </li>
			<?php }else{?>
			
				<?php if($hv['isshow'] == 1 && !empty($hv['childs'])){?>
				<li><a href="<?=!empty($hv['identify'])?'/'.$puri.'/'.$hv['identify'].'?programaid='.$hv['programaid']:''?>" class="bg-sanjiao mod_show_on"><?=lang('nav_'.$hv['programaid'])?></a>
				<dl>
						<?php foreach($hv['childs'] as $ck => $cv){
								if(!$cv['isshow']){
									continue;
								}
						?>
						
						  
						  <dd><a href="<?=!empty($cv['identify'])?'/'.$puri.'/'.$cv['identify'].'?programaid='.$cv['programaid']:''?>"><?=$cv['isshow'] == 1 ?lang('nav_'.$cv['programaid']):''?></a></dd>
						
						<?php }?>
					</dl>
				  </li>
			
			<?php }else if($hv['isshow'] == 1){?>
			  <li class="meiyoujiao"><a href="<?=!empty($hv['identify'])?'/'.$puri.'/'.$hv['identify']:''?>" class="mod_show_on"><?=lang('nav_'.$hv['programaid'])?></a></li>
			
			<?php }?>
			
			<?php }?>
		<?php }?>
	
	<?php }}?>
    </ul>
    <div class="f_l blue-line">&nbsp;</div>
	<?php if(!empty($function_on_off['apply']) && $function_on_off['apply'] == 'no'){?>
    <div class="f_r"> <a href="javascript:;" onclick="no_apply()" class="apply-online"><?=lang('online_apply')?></a> </div>
	<?php }else{?>
	<div class="f_r"> <a href="/<?=$puri?>/course?programaid=0" class="apply-online"><?=lang('online_apply')?></a> </div>
	<?php }?>
	
  </div>
</div>
<script type="text/javascript">
	function no_apply(){
		var d = dialog({
		
			content:'<?=lang('no_accommodeation')?>'
		});
		d.show();
		setTimeout(function(){
			d.close().remove();
		},3000);
	}
</script>
<!--nav end-->