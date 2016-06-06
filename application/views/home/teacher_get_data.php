<p class="sharenow-title"><?=lang('student_eval')?></p>
			<div class="width_720 student-assessment">
				<ul>
					<?php 
						if(!empty($evaluate)){
							foreach($evaluate as $key => $val){
					?>
						<li class="<?php if($key == 0){ echo 'mg_b_30-g';}?>">
						<div class="f_l nickname">
							<img src="<?=!empty($val['photo'])?$val['photo']:'/resource/home/images/user/ures_pic22.png'?>" >
							<div class="namecountry">
								<p><?=!empty($val['name'])?$val['name']:''?></p>
								<p class="color-7">From <?=!empty($val['nationality'])?$nationality[$val['nationality']]:''?></p>
							</div>
							<div class="clear"></div>
						</div>
						<div class="assessment">
							<p class="mb15"><?=!empty($val['title'])?$val['title']:''?></p>
							<div class="huanhang">
							<div class="f_l pragraph"><?=lang('scoreall')?>:</div>
								<div class="f_l starstar">
									<div id="scoreall<?=$key?>" style="float:left;"></div>
									<script type="text/javascript">
										$('#scoreall<?=$key?>').raty({
											  readOnly:  true,
											  score:     <?=!empty($val['scoreall'])?number_format($val['scoreall'],1):0?>
										});
									</script>
									<div style="float:left;"><?=!empty($val['scoreall'])?number_format($val['scoreall'],1):0?></div>
								</div>
							</div>
							<div class="contactus-word-title-describe"><?=lang('evaluate_evaluate')?> :  <?=!empty($val['evaluate'])?$val['evaluate']:''?></div>
							<div class="contactus-word-title-describe"><?=lang('evaluate_content')?> :  <?=!empty($val['content'])?$val['content']:''?></div>
							<div class="mt20 color-7 f12"><?=!empty($val['createtime'])?date('Y/m/d',$val['createtime']):''?></div>
						</div>
					</li>
					<?php }}?>
					
					
				</ul>
			</div>
			<div class="assessment-next mt20">
				<a href="javascript:;" onclick="next(<?=$teacherid?>,<?=$page?>)" class="assessment-next-previous assessment-next-previous-next"></a>
				<a href="javascript:;" onclick="prev(<?=$teacherid?>,<?=$page?>)" class="assessment-next-previous assessment-next-previous-pre"></a>
			</div>