<?php   
$uri3 = $this->uri->segment(3);
$uri4 = $this->uri->segment(4);
?>
<div class="user-bottom min">
  <p><em class="Arial"><?=lang('footer_ba')?></em></p>
  <p><em class="Arial"><?=lang('footer_copy')?></em></p>
</div>·

<!--个人中心侧栏-->
<?php if(!empty($_SESSION ['student'] ['userinfo'])&&empty($is_floot)){ ?>

<div class="center_menu">
	<ul>
		<!--<li class="g7"><a href="#"></a><div class="tanchu"><?=lang('nav_apply')?></div></li>-->
		<li class="g1 <?php echo empty($uri4)?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/index'"><a href="javascript:;"></a><div class="tanchu"><?=lang('nav_apply')?></div></li>
        
            <li class="g2 <?php echo $uri4=='pickuplist'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/student/pickuplist'"><a href="javascript:;"></a><div class="tanchu"><?=lang('nav_pick_up')?></div></li>
            <li class="g3 <?php echo $uri4=='accommodation'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/student/accommodation'"><a href="javascript:;"></a><div class="tanchu"><?=lang('nav_accommodation')?></div></li>
        <?php if($is_user_student==1){?>
        <li class="g4 <?php echo $uri4=='checking'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/student/checking'"><a href="javascript:;"></a><div class="tanchu"><?=lang('nav_attendance')?></div></li>
		<li class="g5 <?php echo $uri4=='score'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/student/score'"><a href="javascript:;"></a><div class="tanchu"><?=lang('nav_achievement')?></div></li>
		<li class="g6 <?php echo $uri4=='schedules'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/student/schedules'"><a href="javascript:;"></a><div class="tanchu"><?=lang('nav_schedules')?></div></li>
		<li class="g8 <?php echo $uri3=='evaluate'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/evaluate/index'"><a href="javascript:;"></a><div class="tanchu"><?=lang('evaluate_my_eav')?></div></li>
		<li class="g9 <?php echo $uri3=='activity'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/activity/index'"><a href="javascript:;"></a><div class="tanchu"><?=lang('activity')?></div></li>
		<li class="g10 <?php echo $uri3=='exam'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/exam'"><a href="javascript:;"></a><div class="tanchu"><?=lang('exam')?></div></li>
		<li class="g11 <?php echo $uri3=='stuscholarship'?'active':''?>" onclick="window.location.href='/<?=$puri?>/scholarshipgrf/stuscholarship'"><a href="javascript:;"></a><div class="tanchu"><?=lang('apply_scholarship')?></div></li>
            <li class="g7 <?php echo $uri4=='tuition'?'active':''?>" onclick="window.location.href='/<?=$puri?>/student/student/fee'"><a href="javascript:;"></a><div class="tanchu"><?=lang('tuition_history')?></div></li>
        <?php }?>
    </ul>
    <div class="back_top"><a href="#"></a></div>
</div>
<?php } ?>

<!---->
</body>
</html>
