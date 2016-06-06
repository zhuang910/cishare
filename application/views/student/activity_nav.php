 <?php 
	$uriUrl = $this->uri->segment(4);
?>
 <div class="Switch_title">
        <ul class="clearfix">
          <li style="border-right:none;" <?=!empty($uriUrl) && in_array($uriUrl,array('ckusersame','index'))?'class="active"':''?>>
		  <a href="/<?=$puri?>/student/activity/index"><?=lang('my_join_activity')?></a></li>
          <li  style="border-right:none;" <?=!empty($uriUrl) && $uriUrl == 'collect'?'class="active"':''?>><a href="/<?=$puri?>/student/activity/collect"><?=lang('my_collect_activity')?></a></li>
          <li  style="border-right:none;" <?=!empty($uriUrl) && in_array($uriUrl,array('ckuser','launch'))?'class="active"':''?>><a href="/<?=$puri?>/student/activity/launch"><?=lang('my_add_activity')?></a></li>
          <li <?=!empty($uriUrl) && $uriUrl == 'lists'?'class="active"':''?>><a href="/<?=$puri?>/student/activity/lists"><?=lang('activity_list')?></a></li>
        </ul>
      </div>