 <?php 
	$uriUrl = $this->uri->segment(4);
?>
 <div class="Switch_title">
        <ul class="clearfix">
          
          <li <?=!empty($uriUrl) && in_array($uriUrl,array('ckuser','launch'))?'class="active"':''?>><a href="/<?=$puri?>/society/activity/launch"><?=lang('my_add_activity')?></a></li>
         
        </ul>
      </div>