<?php $this->load->view('public/header.php')?>
<?php $this->load->view('public/nav.php')?>
<style>
.changeBox_a1 { 
    height: 231px;
    position: relative;
}
.changeDiv {
    display: none;
    position: absolute;
}

</style>
<div class="page_banner">
<div class="changeBox_a1">
<?php if(!empty($advance_course)){
	foreach($advance_course as $lk => $lv){
?>
	<div class="bannerpic changeDiv" style="width: 100%;" <?php if($lk != 0){?> style="display:none;"<?php }?>>
    <div class="width_1024">
      <div class="bantouming"></div>
      <div class="banner_news">
        <dl>
          <dt><?=!empty($lv['title'])?$lv['title']:''?></dt>
          <dd><?=!empty($lv['description'])?nl2br($lv['description']):''?></dd>
          <dd class="mt15"><a href="<?=!empty($lv['url'])?$lv['url']:''?>"><?=lang('look_detail')?></a></dd>
        </dl>
      </div>
    </div>
	<div style="background: url(<?=!empty($lv['image'])?$lv['image']:''?>) no-repeat;height: 232px;"></div>
  </div>
<?php }}?>
  </div>
  
  <div class="banner_qiehuan">
    <ul>
		<?php if(!empty($advance_course)){
			foreach($advance_course as $zyjk => $zyjv){
		?>
			 <li  <?php if($zyjk == 0){?> class="active" <?php }?>></li>
		<?php }}?>
     
    </ul>
  </div>
</div>
<!--内容-->
<div class="width_1024">
  <div class="page_con mt50">
    <div class="page_qiehuan">
      <ul>
        <li class="<?=empty($majorid)?'active':''?>" onclick="qh_course(this,0)"><?=lang('allcourse')?><span></span></li>
        <?php if(!empty($d) && in_array(1,$d)){?>

        	<li class="<?=!empty($majorid) && $majorid == 1?'active':''?>"  onclick="qh_course(this,1)"><?=lang('degree_1')?><span></span></li>
        <?php }?>
         <?php if(!empty($d) && in_array(2,$d)){?>
         <li class="<?=!empty($majorid) && $majorid == 2?'active':''?>"  onclick="qh_course(this,2)"><?=lang('degree_2')?><span></span></li>
         <?php }?>
         <?php if(!empty($d) && in_array(3,$d)){?>
        <li class="<?=!empty($majorid) && $majorid == 3?'active':''?>"  onclick="qh_course(this,3)"><?=lang('degree_3')?><span></span></li>
        <?php }?>
        <?php if(!empty($d) && in_array(4,$d)){?>
        <li class="<?=!empty($majorid) && $majorid == 4?'active':''?>"  onclick="qh_course(this,4)"><?=lang('degree_4')?><span></span></li>
        <?php }?>
        <?php if(!empty($d) && in_array(5,$d)){?>
        <li class="<?=!empty($majorid) && $majorid ==5?'active':''?>"  onclick="qh_course(this,5)"><?=lang('degree_5')?><span></span></li>
        <?php }?>
      </ul>
    </div>
	<div>
    <div class="coure clearfix" id="majorid0" style="display:<?=empty($majorid)?'':'none'?>">
	<?php if(!empty($course_all)){
		foreach($course_all as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
         <dt><img src='<?=!empty($v['img'])?$v['img']:''?>' width="196" height="135"></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }else{?><?=!empty($v['name'])?$v['name']:''?><?php }?></dd>
        <dd><?=lang('course_opentime')?>: <?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):''?></dd>
        <dd><?=lang('course_schooling')?>：
		<?php 
			if(!empty($v['schooling']) && !empty($v['xzunit'])){
				if($v['schooling'] > 1 || strstr($v['schooling'] ,'-')){
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']].'s';
				}else{
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']];
				}
			
			}
		?>
		</dd>
        <dd><?=lang('course_tuition')?>：<?=!empty($v['tuition'])?'RMB '.$v['tuition']:''?><?=lang('turr')?></dd>
        <dd class="bent">
			<?php if(!empty($function_on_off['apply']) && $function_on_off['apply'] == 'no'){?>
			<span class="btn"><a href="javascript:;" onclick="no_apply()"><?=lang('online_apply')?></a></span>
			<?php }else{?>
			<span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('online_apply')?></a></span>
			<?php }?>
			<span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('look_detail')?></a></span>
		</dd>
      </dl>	
	<?php }}?>

    </div>
	<div class="coure clearfix" id="majorid1" style="display:<?=!empty($majorid) && $majorid == 1?'':'none'?>">
	<?php if(!empty($course_fxl)){
		foreach($course_fxl as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
        <dt><img src='<?=!empty($v['img'])?$v['img']:''?>' width="196" height="135"></dt>
       <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }else{?><?=!empty($v['name'])?$v['name']:''?><?php }?></dd>
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
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'RMB '.$v['tuition']:''?><?=lang('turr')?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('online_apply')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('look_detail')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	<div class="coure clearfix" id="majorid2" style="display:<?=!empty($majorid) && $majorid == 2?'':'none'?>">
	<?php if(!empty($course_zk)){
		foreach($course_zk as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
       <dt><img src='<?=!empty($v['img'])?$v['img']:''?>' width="196" height="135"></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }else{?><?=!empty($v['name'])?$v['name']:''?><?php }?></dd>
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
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'RMB '.$v['tuition']:''?><?=lang('turr')?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('online_apply')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('look_detail')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	<div class="coure clearfix" id="majorid3" style="display:<?=!empty($majorid) && $majorid == 3?'':'none'?>">
	<?php if(!empty($course_bk)){
		foreach($course_bk as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
       <dt><img src='<?=!empty($v['img'])?$v['img']:''?>' width="196" height="135"></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }else{?><?=!empty($v['name'])?$v['name']:''?><?php }?></dd>
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
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'RMB '.$v['tuition']:''?><?=lang('turr')?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('online_apply')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('look_detail')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	<div class="coure clearfix" id="majorid4" style="display:<?=!empty($majorid) && $majorid == 4?'':'none'?>">
	<?php if(!empty($course_sh)){
		foreach($course_sh as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
       <dt><img src='<?=!empty($v['img'])?$v['img']:''?>' width="196" height="135"></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }else{?><?=!empty($v['name'])?$v['name']:''?><?php }?></dd>
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
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'RMB '.$v['tuition']:''?><?=lang('turr')?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('online_apply')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('look_detail')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	<div class="coure clearfix" id="majorid5" style="display:<?=!empty($majorid) && $majorid == 5?'':'none'?>">
	<?php if(!empty($course_bs)){
		foreach($course_bs as $k => $v){
	?>
		<dl <?=$k % 2 == 0 ? 'class="mgin_none"' : ''?>>
        <dt><img src='<?=!empty($v['img'])?$v['img']:''?>' width="196" height="135"></dt>
        <dd class="big_title f18b"><?php if($puri=='en'){?><?=!empty($v['englishname'])?$v['englishname']:''?><?php }else{?><?=!empty($v['name'])?$v['name']:''?><?php }?></dd>
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
        <dd><?=lang('course_tuition')?>:<?=!empty($v['tuition'])?'RMB '.$v['tuition']:''?><?=lang('turr')?></dd>
        <dd class="bent"><span class="btn"><a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><?=lang('online_apply')?></a></span><span class="btn2" style="margin-right:0"><a href="/<?=$puri?>/course/detail?id=<?=$v['id']?>&site_language=<?=$where_lang?>"><?=lang('look_detail')?></a></span></dd>
      </dl>	
	<?php }}?>

    </div>
	
	</div>
  </div>
</div>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.soChange.js"></script>
<script type="text/javascript">
$(function () {
	$('.changeBox_a1 .changeDiv').soChange({
		thumbObj:'.banner_qiehuan li', 
		thumbNowClass:'active'
	});
});
</script>

<script type="text/javascript">
	function apply_now(id){
		$.ajax({
			beforeSend:function(){
				var d = dialog({
						id:'cucasdialog',
						content: '<img src="<?=RES?>home/images/public/loading.gif">'
					});
					d.showModal();
					
			},
		type:'GET',
		url:'/<?=$puri?>/course/is_course_login?courseid='+id,
		success:function(r){
			dialog({id:'cucasdialog'}).close();
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
						
						content: '<?=lang('overdue')?>'
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 4000);
					//window.location.reload();
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
						content:r.data,
						padding:0
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
				
				$('#majorid'+id).hide();
				$('#majorid'+id).siblings().hide();
				$('#majorid'+id).show();
			}
</script>
<?php $this->load->view('public/footer.php')?>
