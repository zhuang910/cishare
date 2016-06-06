
    
	<?php 
		if(!empty($events)){
			foreach($events as $k => $v){
	?>
		<div class="blue-top campuslife <?=($k+1)%3 ==0?'mg_r_10':''?>">
			<a class="erjitongyong" href="/<?=$puri?>/schoollife/events_detail?id=<?=$v['id']?>">
				<img src="<?=!empty($v['image'])?$v['image']:''?>" width="328" height="128" alt=""/>
				<div class="campuslife_describe">
					<h5 class="mg-b-15"><?=!empty($v['title'])?$v['title']:''?></h5>
					<p class="ft-12-5"><?=!empty($v['description'])?$v['description']:''?></p>
					<span class="about-school-main-news-see"><?=lang('look_detail')?></span>
					<div class="campuslife_describe_time"><?=date('Y-m-d',$v['createtime'])?></div>
				</div>
			</a>
		</div>
	
	<?php }}?>
	
	

