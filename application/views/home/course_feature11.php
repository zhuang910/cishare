<?php $this->load->view('public/header.php')?>
<?php $this->load->view('public/nav.php')?>
<!--banner-->
<link href="<?=RES?>/home/css/zyjhtml5/slider.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]><script src="<?=RES?>/home/js/plugins/zyjhtml5/html5.js"></script><![endif]-->
<script type='text/javascript' src='<?=RES?>/home/js/plugins/zyjhtml5/common.js'></script>
<script type='text/javascript' src='<?=RES?>/home/js/plugins/zyjhtml5/slider.js'></script>
<div class="page_banner">
	<?php if(!empty($advance_course)){?>
	 <div class="header-content home">
		<div class="parallax-bg" id="slider-wrap">
			<div class="slider parallax-bg" id="slider">
				<div class="slider-sections sandbox">
					<?php 
						foreach($advance_course as $adk => $adv){
					?>
						<section <?php if($adk == 0){?>class="first"<?php }?>><img alt="<?=!empty($adv['title'])?$adv['title']:''?>" src="<?=!empty($adv['image'])?$adv['image']:''?>" width="378" height="245"/>
					<div class="text" <?php if($adk == 1){?>style="padding-top: 10px;"<?php }?>>
						<h3>
						<?=!empty($adv['title'])?$adv['title']:''?>
						</h3>
						<p class="copy">
							<?=!empty($adv['description'])?$adv['description']:''?>
						</p>
						<p class="button">
							<a href="<?=!empty($adv['url'])?$adv['url']:''?>"><?=lang('look_detail')?></a>
						</p>
					</div>
					</section>
					
					<?php }?>
					
				</div>
			</div>
			<a class="slider-prev" href="javascript: void(0)">?</a><a class="slider-next" href="javascript: void(0)">?</a>
	</div>
	</div>
	
	<?php }?>

</div>
<!--内容-->
<div class="width_1024">
  <div class="page_con mt50">
    <div class="page_qiehuan">
      <ul>
        <li class="<?=empty($majorid)?'active':''?>" onclick="qh_course(this,0)"><?=lang('allcourse')?><span></span></li>
        <li class="<?=!empty($majorid) && $majorid == 1?'active':''?>"  onclick="qh_course(this,1)"><?=lang('degree_1')?></li>
         <li class="<?=!empty($majorid) && $majorid == 2?'active':''?>"  onclick="qh_course(this,2)"><?=lang('degree_2')?></li>
        <li class="<?=!empty($majorid) && $majorid == 3?'active':''?>"  onclick="qh_course(this,3)"><?=lang('degree_3')?></li>
        <li class="<?=!empty($majorid) && $majorid == 4?'active':''?>"  onclick="qh_course(this,4)"><?=lang('degree_4')?></li>
        <li class="<?=!empty($majorid) && $majorid ==5?'active':''?>"  onclick="qh_course(this,5)"><?=lang('degree_5')?></li>
      </ul>
    </div>
	<div>
    <div class="coure clearfix" id="majorid0" style="display:<?=empty($majorid)?'':'none'?>">
	<?php if(!empty($course_all)){
		foreach($course_all as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
        <dt></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['name'])?$v['name']:''?><?php }else{?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }?></dd>
        <dd><?=lang('course_opentime')?>: <?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):''?></dd>
        <dd><?=lang('course_schooling')?>：
		<?php 
			if(!empty($v['schooling']) && !empty($v['xzunit'])){
				if($v['schooling'] > 1){
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']].'s';
				}else{
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']];
				}
			
			}
		?>
		</dd>
        <dd><?=lang('course_tuition')?>：<?=!empty($v['tuition'])?'$'.$v['tuition']:''?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('baoming')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('zixun')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	<div class="coure clearfix" id="majorid1" style="display:<?=!empty($majorid) && $majorid == 1?'':'none'?>">
	<?php if(!empty($course_fxl)){
		foreach($course_fxl as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
        <dt></dt>
        <dd class="big_title f18b">
		
		<?php if($puri=='en'){?><?=!empty($v['name'])?$v['name']:''?><?php }else{?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }?>
		</dd>
        <dd><?=lang('course_opentime')?>: <?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):''?></dd>
        <dd><?=lang('course_schooling')?>:
		<?php 
			if(!empty($v['schooling']) && !empty($v['xzunit'])){
				if($v['schooling'] > 1){
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']].'s';
				}else{
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']];
				}
			
			}
		?>
		</dd>
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'$'.$v['tuition']:''?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('baoming')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('zixun')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	<div class="coure clearfix" id="majorid2" style="display:<?=!empty($majorid) && $majorid == 2?'':'none'?>">
	<?php if(!empty($course_zk)){
		foreach($course_zk as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
        <dt></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['name'])?$v['name']:''?><?php }else{?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }?></dd>
        <dd><?=lang('course_opentime')?>: <?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):''?></dd>
        <dd><?=lang('course_schooling')?>:
		<?php 
			if(!empty($v['schooling']) && !empty($v['xzunit'])){
				if($v['schooling'] > 1){
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']].'s';
				}else{
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']];
				}
			
			}
		?>
		</dd>
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'$'.$v['tuition']:''?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('baoming')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('zixun')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	<div class="coure clearfix" id="majorid3" style="display:<?=!empty($majorid) && $majorid == 3?'':'none'?>">
	<?php if(!empty($course_bk)){
		foreach($course_bk as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
        <dt></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['name'])?$v['name']:''?><?php }else{?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }?></dd>
        <dd><?=lang('course_opentime')?>: <?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):''?></dd>
        <dd><?=lang('course_schooling')?>:
		<?php 
			if(!empty($v['schooling']) && !empty($v['xzunit'])){
				if($v['schooling'] > 1){
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']].'s';
				}else{
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']];
				}
			
			}
		?>
		</dd>
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'$'.$v['tuition']:''?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('baoming')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('zixun')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	<div class="coure clearfix" id="majorid4" style="display:<?=!empty($majorid) && $majorid == 4?'':'none'?>">
	<?php if(!empty($course_sh)){
		foreach($course_sh as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
        <dt></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['name'])?$v['name']:''?><?php }else{?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }?></dd>
        <dd><?=lang('course_opentime')?>: <?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):''?></dd>
        <dd><?=lang('course_schooling')?>:
		<?php 
			if(!empty($v['schooling']) && !empty($v['xzunit'])){
				if($v['schooling'] > 1){
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']].'s';
				}else{
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']];
				}
			
			}
		?>
		</dd>
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'$'.$v['tuition']:''?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('baoming')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('zixun')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	<div class="coure clearfix" id="majorid5" style="display:<?=!empty($majorid) && $majorid == 5?'':'none'?>">
	<?php if(!empty($course_bs)){
		foreach($course_bs as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
        <dt></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['name'])?$v['name']:''?><?php }else{?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }?></dd>
        <dd><?=lang('course_opentime')?>: <?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):''?></dd>
        <dd><?=lang('course_schooling')?>:
		<?php 
			if(!empty($v['schooling']) && !empty($v['xzunit'])){
				if($v['schooling'] > 1){
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']].'s';
				}else{
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']];
				}
			
			}
		?>
		</dd>
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'$'.$v['tuition']:''?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('baoming')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('zixun')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	</div>
  </div>
</div>
<script type="text/javascript">
	function apply_now(id){
		$.ajax({
			beforeSend:function(){
				var d = dialog({
						content: '<img src="<?=RES?>home/images/public/loading.gif">'
					});
					d.showModal();
					
			},
		type:'GET',
		url:'/course/is_course_login?courseid='+id,
		success:function(r){
			if (r.state == 1) {
				/*直接跳转到申请页面*/
				window.location.href=r.data;
			}else if(r.state == 2){
				/*登录了 但是过期了 弹出过期的页面
				art.dialog({
							content:r.data,
							drag: false,
							resize: false,
							lock: true,
							opacity: 0.2,
							fixed: true
						});
				*/		
				var d = dialog({
						
						content: '课程已过期'
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 2000);
					window.location.reload();
			}else{
				/*没有登录 弹出登录窗口
				var p = art.dialog.open('/student/login/ajax_login?courseid='+id,{
							id:'plogin',
							drag: false,
							resize: false,
							lock: true,
							opacity: 0.2,
							fixed: true
						});
				*/		
				var d = dialog({
						content:r.data
				});
				d.showModal();
			}
		},
		dataType:'json'

	});
	}
</script>

<script type="text/javascript">
	function qh_course(t,id){
				$(t).removeClass('active');
				$(t).siblings().removeClass('active');
				$(t).addClass('active');
				
				$('#majorid'+id).hide('slow');
				$('#majorid'+id).siblings().hide('slow');
				$('#majorid'+id).show('slow');
			}
</script>
<?php $this->load->view('public/footer.php')?>
