<?php 
  $uri4 = $this->uri->segment(4);
?>
  <div class="wlko">
      <div class="titlenews">
        <ul>
          <a href="/<?=$puri?>/student/index" class="White"><li class="<?=empty($uri4) || $uri4 == 'index'?'active':''?>">  <?=lang('my_all')?></li></a>
          <a href="/<?=$puri?>/student/index/apply_all" class="White"><li class="<?=!empty($uri4) && $uri4 == 'apply_all'?'active':''?>"><?=lang('my_incomplete')?> </li></a>
         <!-- <a href="/<?=$puri?>/student/index/accepted" class="White"> <li class="<?=!empty($uri4) && $uri4 == 'accepted'?'active':''?>"><?=lang('my_accepted')?> </li></a>
          <a href="/<?=$puri?>/student/index/admission" class="White"><li class="<?=!empty($uri4) && $uri4 == 'admission'?'active':''?>"><?=lang('my_admission')?> </li></a>
          <a href="/<?=$puri?>/student/index/callback" class="White"><li class="<?=!empty($uri4) && $uri4 == 'callback'?'active':''?>"> <?=lang('my_pending')?> </li></a>-->
        </ul>
      </div>
    </div>

