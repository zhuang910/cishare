 <?php 
		if(!empty($events)){
			foreach($events as $k => $v){
			
	?>
	  <div class="blue-top teacher-china <?php if( ($k+1) %3 == 0){?>mg_r_10<?php }?>"> <a href="/<?=$puri?>/schoollife/share_detail?id=<?=$v['id']?>">
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
	
	

