 <?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left"><a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?php if($puri == 'cn'){echo $v['ctitle'];}else{echo $v['etitle'];}?></a><br />
				
			</td>
            <td align="left"><?=!empty($v['starttime'])?date('Y-m-d H:i',$v['starttime']):''?><br /></td>
            <td align="left"><?=!empty($v['endtime'])?date('Y-m-d H:i',$v['endtime']):''?><br /></td>
            <td align="left">
				<?php 
					//判断活动是否结束
					$flag = 0;
					if($v['ishandend'] == 0){
						echo lang('activity_start_3');
					}else{
						if(time() > $v['endtime']){
							echo '<span style="color:#CFCFCF">'.lang('activity_start_3').'</span>';
						}else if( time() > $v['starttime'] && time() < $v['endtime']){
							echo '<span style="color:#FA8072">'.lang('activity_start_2').'</span>';
						
						}else if(time() < $v['starttime']){
							 echo '<span style="color:#FF3030">'.lang('activity_start_1').'</span>';
							 $flag = 1;
						}
					
					}
				
				?>
			
			</td>
            <td align="left">
				
				<a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?=lang('detail')?></a>
				<?php if(!empty($uid) && $uid != $v['userid']){?>
				 / 
				 <a href="javascript:;" onclick="activity_collect(<?=$v['id']?>)"><?=lang('activity_collect')?></a>
				 <?php }?>
				<?php 
				if(!empty($uid) && $uid != $v['userid']){
					if(!empty($flag) && $flag == 1){
				?>
					/ <a href="javascript:;" onclick="activity_join(<?=$v['id']?>)"><?=lang('activity_join')?></a> 
				<?php }}?>
				
			</td>
          </tr>
		  
		  <?php }}?>