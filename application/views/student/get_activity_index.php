<?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left"><a href="/<?=$puri?>/student/activity/detail?id=<?=$v['activityid']?>">
			<?php 
				if(!empty($myact) && !empty($myact[$v['activityid']])){
					if($puri == 'cn' && !empty($myact[$v['activityid']]['ctitle'])){echo $myact[$v['activityid']]['ctitle'];}else if($puri == 'en' && !empty($myact[$v['activityid']]['etitle'])){echo $myact[$v['activityid']]['etitle'];}
				}
			
			
			?>
			
			</a><br />
				
			</td>
            <td align="left"><?=!empty($myact) && !empty($myact[$v['activityid']]) && !empty($myact[$v['activityid']]['starttime'])?date('Y-m-d H:i',$myact[$v['activityid']]['starttime']):''?><br />
			<?php if($v['state'] != 0){?><span style="color:red;">
					<?=!empty($v['auditopinion'])?lang('activity_auditopinion').':'.$v['auditopinion'].'<br />':''?>
					<?=!empty($v['auditname'])?lang('activity_auditname').':'.$v['auditname'].'<br />':''?>
					<?=!empty($v['audittime'])?lang('activity_audittime').':'.date('Y-m-d H:i:s',$v['audittime']).'<br />':''?>
				</span><?php }?>
			</td>
            <td align="left"><?=!empty($myact) && !empty($myact[$v['activityid']]) && !empty($myact[$v['activityid']]['endtime'])?date('Y-m-d H:i',$myact[$v['activityid']]['endtime']):''?><br /></td>
            <td align="left">
				<?php 
					//判断审核状态 决定 可以是否取消 活动
					$flag = 0;
					if($v['state'] == 0){
						echo '<span style="color:#DA70D6">'.lang('activity_state_0').'</span>';
						$flag = 1;
					}else if($v['state'] == 1){
						echo '<span style="color:#98F5FF">'.lang('activity_state_1').'</span>';
						
					}else if($v['state'] == 2){
						echo '<span style="color:#CD3333">'.lang('activity_state_2').'</span>';
					}
				
				?>
			
			</td>
            <td align="left">
				
				<a href="/<?=$puri?>/student/activity/detail?id=<?=$v['activityid']?>"><?=lang('detail')?></a>
				<?php 
					if(!empty($flag) && $flag == 1){
				?>
					/ <a href="javascript:;" onclick="activity_cancel_join(<?=$v['activityid']?>)"><?=lang('activity_cancel_join')?></a> 
				<?php }?>
				
			</td>
          </tr>
		  
		  <?php }}?>