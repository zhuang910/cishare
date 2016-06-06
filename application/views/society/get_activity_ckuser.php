<?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left">
			<?=lang('pickup_name')?>:<?=!empty($v['name'])?$v['name']:''?><br />
			<?=lang('pickup_sex')?>:<?=!empty($v['sex'])?lang('pickup_sex'.$v['sex']):''?><br />
			<?=lang('pickup_nationality')?>:<?=!empty($v['nationality'])?$nationality[$v['nationality']]:''?><br />
			<?=lang('passport')?>:<?=!empty($v['passport'])?$v['passport']:''?><br />
			<?=lang('major')?>:<?=!empty($v['major'])?$v['major']:''?><br />
			<?=lang('class')?>:<?=!empty($v['classid'])?$v['classid']:''?><br />
			
			<br />
				
			</td>
            <td align="left"><?=!empty($v['applytime'])?date('Y-m-d H:i:s',$v['applytime']):''?><br />
			</td>
            <td align="left">
			<?php 
					//判断审核状态 决定 可以是否取消 活动
					
					if($v['state'] == 0){
						echo '<span style="color:#DA70D6">'.lang('activity_state_0').'</span>';
						
					}else if($v['state'] == 1){
						echo '<span style="color:#98F5FF">'.lang('activity_state_1').'</span>';
						
					}else if($v['state'] == 2){
						echo '<span style="color:#CD3333">'.lang('activity_state_2').'</span>';
					}
				
				?>
				
			<br />
			<a href="javascript:;" onclick="activity_isapply_check(<?=$v['activityid']?>,<?=$v['userid']?>)"><?=lang('activity_isapply_check')?></a>
			</td>
            <td align="left">
				<?php 
					if(!empty($v['isjoin']) && $v['isjoin'] == 1){
						echo lang('activity_isapply_yes');
					}else{
						echo lang('activity_isapply_no');
					}
				
				?><br />
				<a href="javascript:;" onclick="activity_isapply_set(<?=$v['activityid']?>,<?=$v['userid']?>)"><?=lang('activity_isapply_set')?></a>
				
			</td>
            <td align="left">
				<?=!empty($v['score'])?lang('activity_isapply_score_'.$v['score']):''?>
			<br />
				<?php if(!empty($on) && $on == 1){?>
				<a href="javascript:;" onclick="activity_isapply_score(<?=$v['activityid']?>,<?=$v['userid']?>)"><?=lang('activity_isapply_score')?></a> 
				<?php }?>
				
				
			</td>
          </tr>
		  
		  <?php }}?>