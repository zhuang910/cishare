<?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left"><a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?php if($puri == 'cn'){echo $v['ctitle'];}else{echo $v['etitle'];}?></a><br />
				<?php if($v['state'] != 0){?><span style="color:red;">
					<?=!empty($v['auditopinion'])?lang('activity_auditopinion').':'.$v['auditopinion'].'<br />':''?>
					<?=!empty($v['auditname'])?lang('activity_auditname').':'.$v['auditname'].'<br />':''?>
					<?=!empty($v['audittime'])?lang('activity_audittime').':'.date('Y-m-d H:i:s',$v['audittime']).'<br />':''?>
				</span><?php }?>
			</td>
            <td align="left"><?=!empty($v['starttime'])?date('Y-m-d H:i',$v['starttime']):''?><br /></td>
            <td align="left"><?=!empty($v['endtime'])?date('Y-m-d H:i',$v['endtime']):''?><br /></td>
            <td align="left">
				<?=lang('activity_state_'.$v['state'])?>
			
			</td>
            <td align="left">
			<a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?=lang('detail')?></a> /
				<a href="javascript:;" onclick="activity_end(<?=$v['id']?>)"><?=lang('ishandend')?></a> / 
				<?php 
					if($v['state'] == 0){
				?>
					<a href="javascript:;" onclick="activity_cancel(<?=$v['id']?>)"><?=lang('activity_cancel')?></a> /
					<a href="/<?=$puri?>/student/activity/add_c?id=<?=$v['id']?>"><?=lang('activity_add_content')?></a>
				<?php }else if($v['state'] == 1){?>
				 <a href="/<?=$puri?>/student/activity/ckuser?id=<?=$v['id']?>"><?=lang('activity_ck_user')?>(<?=!empty($count) && !empty($count[$v['id']])?$count[$v['id']]:0?>)</a>
				<?php }else{?>
				--
				<?php }?>
			</td>
          </tr>
		  
		  <?php }}?>