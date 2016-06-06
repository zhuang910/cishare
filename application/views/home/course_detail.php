<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery-scrolltofixed-min.js"></script>

<!--内容-->
<div class="width_1024 clearfix">
	<?=$bread_line?>
	<div class="course-details-main-left">
		<div class="course-highlights mb30">
		<?php 
					if(!empty($course_basic['video'])){
					//弹视频
						$href = 'onclick="video_play(\''.$course_basic['video'].'\')"';
						$class="class='f_l course-highlights-img video_cour'";
					}else{
						//弹图集
						$href = 'onclick="piclb('.$course_basic['id'].')"';
						$class="class='f_l course-highlights-img img_cour'";
					}
				?>
			<div  <?=!empty($class)?$class:''?>>
				
				<a href="javascript:;" <?=$href?>>
					<img src="<?=!empty($img[0]['image'])?$img[0]['image']:''?>" width="196" height="135">
					<?php if(!empty($course_basic['video'])){?>
					<div class="course-highlights-play"></div>
					<?php }?>
					<span></span>
				</a>
				</div>
			<div class="f_l course-highlights-word">
				<p class="loadkind"><?=lang('course_bright_spot')?></p>
				<p class="pragraph mt10"><?=!empty($course_content->feature)?$course_content->feature:''?></p>
			</div>
		</div>
		
		<div class="coures_qiehuan">
		  <ul class="clearfix">
		  <?php $i = 0;
			foreach($c_s as $ck => $cv){
			
		  ?>
		  <li class="<?=$course_css[$ck]?> <?=$i == 0?'hover':''?>"><a href="#content<?=$ck?>"><span><?=lang('course_content'.$ck)?></span></a></li>
		  
		  <?php $i++;}?>
			
		  </ul>
		</div>
		<div class="qh_cont">
				<?php if(!empty($c_s)){
					
					foreach($c_s as $zk => $zv){
					if(!empty($course_content->$zv) && $course_content->$zv != '<br>'){
				?>
			
				<div class="course-details-list-main" id="content<?=$zk?>">
					<div class="cucas_CUCAS"><?=lang('course_content'.$zk)?></div>
					<?=$course_content->$zv?>
				</div>
				
				<?php }}}?>
				
		</div>
	</div>
	<div class="course-details-main-right f_l">
		<div class="apply-course-detail">
			<ul>
				<li>
					<div class="f_l width-50"><?=lang('degree')?>:</div>
				
					<div class="f_l width-50"><?=!empty($course_basic['degree'])?lang('degree_'.$course_basic['degree']):''?></div>
				</li>
				<?php if(!empty($scholorship)){?>
				<li>
					<div class="f_l width-50"><font color="red"><?=lang('schlorship_name')?>:</font></div>
						
					<div class="f_l width-50">
						<?php if(!empty($schlorship_flag) && $schlorship_flag == 1){?>
						<?=lang('schlorship_available')?>
						<?php }else{?>
						<?=lang('schlorship_unavailable')?>
						<?php }?>
					</div>
				</li>
				<?php }?>
				<li>
					<div class="f_l width-50"><?=lang('program_length')?>:</div>
					<div class="f_l width-50"><?php 
											if(!empty($course_basic['schooling']) && !empty($course_basic['xzunit'])){
												if($course_basic['schooling'] > 1 || strstr($course_basic['schooling'],'-')){
													echo $course_basic['schooling'].' '.$publics['program_unit'][$course_basic['xzunit']].'s';
												}else{
													echo $course_basic['schooling'].' '.$publics['program_unit'][$course_basic['xzunit']];
												}
											
											}
										?></div>
				</li>
				<li>
					<div class="f_l width-50"><?=lang('opening')?>:</div>
					<div class="f_l width-50"><?=date('m',$course_basic['opentime'])?>.<?=date('d',$course_basic['opentime'])?>&nbsp;<?=date('Y',$course_basic['opentime'])?></div>
				</li>
				<li>
					<div class="f_l width-50"><?=lang('deadline')?>:</div>
					<div class="f_l width-50"><?=date('m',$course_basic['endtime'])?>.<?=date('d',$course_basic['endtime'])?>&nbsp;<?=date('Y',$course_basic['endtime'])?></div>
				</li>
				<li>
					<div class="f_l width-50"><?=lang('tuition')?>:</div>
					<div class="f_l width-50">RMB <?=$course_basic['tuition']?><?=lang('turr')?></div>
				</li>
				<li>
					<div class="f_l width-50"><?=lang('registration_fee')?>:</div>
					<div class="f_l width-50"><?php if(!empty($course_basic['danwei']) && $course_basic['danwei'] == 2){echo 'RMB';}else{echo 'USD';}?> <?=!empty($course_basic['applytuition'])?$course_basic['applytuition']:''?>  (<?=lang('no_return')?>)</div>
				</li>
				<li>
					<div class="f_l width-50"><?=lang('language')?>:</div>
					<div class="f_l width-50"><?=!empty($course_basic['language'])?$lang[$course_basic['language']]:''?></div>
				</li>
				<li>
					<div class="f_l width-50"><?=lang('education')?>:</div>
					<div class="f_l width-50"><?=!empty($education[$course_basic['minieducation']])?$education[$course_basic['minieducation']]:lang('education_none')?></div>
				</li>
				
			</ul>
			<div class="redbtn-big">
				<?php if(!empty($function_on_off['apply']) && $function_on_off['apply'] == 'no'){?>
				<a href="javascript:;" onclick="no_apply()"><?=lang('online_apply')?></a>
				<?php }else{?>
				<a href="javascript:;" onclick="apply_now(<?=$course_basic['id']?>)"><?=lang('online_apply')?></a>
				<?php }?>
				
			</div>
		</div>
		<?php if(!empty($pl[0])){?>
		<div class="introduce mt30">
			<a href="javascript:;" class="bb">
				<div class="f_l">
				<img src="<?=!empty($pl[0]['image'])?$pl[0]['image']:''?>" alt="" width="92" height="92"/></div>
				<div class="f_l width_205">
					<div style="display:none;" id="zyj0">
						<p class="pragraph" ><?=!empty($pl[0]['info'])?strip_tags($pl[0]['info']):''?></p>
						<span class="about-school-main-news-see"  onclick="hide_show(0)"><?=lang('look_less')?></span>
					</div>
					<div id="yjz0">
						
						<?php
						
						if(strlen(strip_tags($pl[0]['info'])) > 80){?>
						<p class="pragraph"><?=!empty($pl[0]['info'])?cut_str(strip_tags($pl[0]['info']),80):''?></p>
						<span class="about-school-main-news-see"  onclick="show_hide(0)"><?=lang('look_detail')?></span>
						<?php }else{?>
						<p class="pragraph"><?=!empty($pl[0]['info'])?strip_tags($pl[0]['info']):''?></p>
						<?php }?>
					</div>
				</div>
				<div class="clear"></div>
			</a>
		</div>
		<?php }?>
		<?php if(!empty($pl[1])){?>
		<div class="introduce">
			<a href="javascript:;">
				<div class="f_l">
				<img src="<?=!empty($pl[1]['image'])?$pl[1]['image']:''?>" alt="" width="92" height="92"/></div>
				<div class="f_l width_205">
					<div style="display:none;" id="zyj1">
						<p class="pragraph" ><?=!empty($pl[1]['info'])?strip_tags($pl[1]['info']):''?></p>
						<span class="about-school-main-news-see"  onclick="hide_show(1)"><?=lang('look_less')?></span>
					</div>
					<div id="yjz1">
						
						<?php
						
						if(strlen(strip_tags($pl[1]['info'])) > 80){?>
						<p class="pragraph"><?=!empty($pl[1]['info'])?cut_str(strip_tags($pl[1]['info']),80):''?></p>
						<span class="about-school-main-news-see"  onclick="show_hide(1)"><?=lang('look_detail')?></span>
						<?php }else{?>
						<p class="pragraph"><?=!empty($pl[1]['info'])?strip_tags($pl[1]['info']):''?></p>
						<?php }?>
					</div>
				</div>
				<div class="clear"></div>
			</a>
		</div>
		<?php }?>
	</div>
	
	
</div>

<script type="text/javascript">
	/*图片播放*/
function piclb(majorid){
		if(majorid != null){
			$.ajax({
			beforeSend:function(){
				var d = dialog({
						id:'cucasdialog',
						content: '<img src="<?=RES?>home/images/public/loading.gif">'
					});
					d.showModal();
					
			},
		type:'GET',
		url:'/<?=$puri?>/course/get_images?majorid='+majorid,
		success:function(r){
				dialog({id:'cucasdialog'}).close();
			if (r.state == 1) {
				dialog({id:'cucasdialog'}).close();
				var d = dialog({
						content:r.data,
						padding:0,
						cancel:true
				});
				d.showModal();
			}else{
				var d = dialog({
					content: '<?=lang('no_img')?>'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			}
		},
		dataType:'json'

	});
		}
	}
	
	/*视频播放*/
	/*播放视频*/
function video_play(url){

	dialog({
				id: 'test-dialog',
				cancel:true,
				url:'/course/video?url='+encodeURIComponent(url),
				padding:0
			})
			.showModal();
			return false;
	}
</script>
<script type="text/javascript">

	$(document).ready(function(){
		$(".course-highlights-img").hover(function(){
			$(this).fadeTo("slow",0.7);
			
		},function(){
		$(this).fadeTo("slow",1);
		})
	})
</script>

<script type="text/javascript">
	function show_hide(id){
		var objzyj = $('#zyj'+id);
		objzyj.siblings().hide();
		objzyj.show();
	}
	
	function hide_show(ids){
		
		var objzyjs = $('#yjz'+ids);
		objzyjs.show();
		objzyjs.siblings().hide();
		
	}
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
	function course_content(t,id){
				$(t).removeClass('title-selected');
				$(t).siblings().removeClass('title-selected');
				$(t).addClass('title-selected');
				
				$('#content'+id).hide();
				$('#content'+id).siblings().hide();
				$('#content'+id).show();
			}
</script>
<script type="text/javascript">
	$(document).ready(function(){
						$(".clearfix >li").click(function(){
					$(".clearfix >li").removeClass("hover");
					$(this).addClass("hover");
					var $index=$(this).index();
					$(".qh_cont div.course-details-list-main").hide();
					$(".qh_cont div.course-details-list-main").eq($index).show();
				});
		})
</script>
<script type="text/javascript">
$(function () {
  $(".coures_qiehuan").scrollToFixed();
   $(window.document).scroll(function () {
  var scrolltop = $(document).scrollTop();
    var form_box = $('div[data-setp="true"]');
    var isq = [];
    var last = '';
    form_box.each(function(i,v){
      var t = $(this).offset().top-120;
      if(scrolltop > t){
        $(".coures_qiehuan ul li span").hide();
        $(".coures_qiehuan ul li span").eq(i).show();
        $(".coures_qiehuan ul li").attr("class", " ");
        $(".coures_qiehuan ul li").eq(i).attr("class", " hover");
      }
    });
  });
});

</script>
<?php $this->load->view('public/footer.php')?>
