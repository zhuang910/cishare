<?php $this->load->view('public/header.php')?>
<?php $this->load->view('public/js_My97DatePicker')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<style>

#container {
	width:580px;
	padding:10px;
	margin:0 auto;
	position:relative;
	z-index:0;
}

#example {
	width:600px;
	height:275px;
	position:relative;
}

#ribbon {
	position:absolute;
	top:-3px;
	left:-15px;
	z-index:500;
}

#frame {
	position:absolute;
	z-index:0;
	width:739px;
	height:341px;
	top:-3px;
	left:-80px;
}

#slides {
	position:absolute;
	top:0px;
	left:4px;
	z-index:100;
}

#slides .next,#slides .prev {
	position:absolute;
	top:107px;
	left:-23px;
	width:24px;
	height:43px;
	display:block;
	z-index:101;
}

.slides_container {
	width:570px;
	height:270px;
	overflow:hidden;
	position:relative;
}

#slides .next {
	left:570px;
}

.pagination {
	margin:26px auto 0;
	width:100px;
}

.pagination li {
	float:left;
	margin:0 1px;
}

.pagination li a {
	display:block;
	width:12px;
	height:0;
	padding-top:12px;
	background-image:url(<?=RES?>/home/images/public/pagination.png);
	background-position:0 0;
	float:left;
	overflow:hidden;
}

.pagination li.current a {
	background-position:0 -12px;
}

.caption {
	position:absolute;
	bottom:-35px;
	height:45px;
	padding:5px 20px 0 20px;
	background:#000;
	background:rgba(0,0,0,.5);
	width:570px;
	font-size:1.3em;
	line-height:1.33;
	color:#fff;
	text-shadow:none;
}
.caption p{color:#fff;}


</style>
<script src="<?=RES?>home/js/plugins/slides.min.jquery.js"></script>
<?php 
	$uri2 = $this->uri->segment(2);
?>
<div class="width_1024 clearfix mg-t-50">

	<div class="three-left-nav">
		<ul>
		<li <?=!empty($uri2) && $uri2 == 'accommodation_introduce'?'class="selected"':''?>>
			<a href="/<?=$puri?>/accommodation_introduce"><?=lang('nav_92')?></a>
		</li>
		<li <?=!empty($uri2) && $uri2 == 'accommodation_book'?'class="selected"':''?>>
			<a href="/<?=$puri?>/accommodation_book"><?=lang('nav_94')?></a>
		</li>
		
		
		</ul>
	</div>
	<div class="pickup-main">
	<div class="crumbs-nav"><a href="/<?=$puri?>/"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/accommodation"><?=lang('nav_67')?></a><i> / </i><a href="/<?=$puri?>/accommodation_introduce"><?=lang('nav_92')?></a><i> / </i><span><?=$info->name?></span></div>
		<div class="borderbox">
			<div class="showboxd">
				<h2><?=!empty($info->name)?$info->name:''?></h2>
				<div>
					<div id="container">

						<div id="example">
							<div id="slides">
								<div class="slides_container">
									<?php if(!empty($buildimg)){
										foreach ($buildimg as $key => $value) {
											
									?>
										<div>
											<img src="<?=!empty($value['pictures'])?$value['pictures']:''?>" width="570" height="270" alt="Slide 1">
											<div class="caption" style="bottom:0">
												<p><?=!empty($value['info'])?$value['info']:''?></p>
											</div>
										</div>
									<?php }}?>
									

								</div>
								<a href="#" class="prev"><img src="<?=RES?>/home/images/public/arrow-prev.png" width="24" height="43" alt="上一张"></a>
								<a href="#" class="next"><img src="<?=RES?>/home/images/public/arrow-next.png" width="24" height="43" alt="下一张"></a>
							</div>
								
							</div>
						</div>
				</div>
				<div class="homejs">
				<h3><?=lang('accommodation_building_introduce')?> </h3>
				<p><?=!empty($info->info)?$info->info:''?></p>
				<h3><?=lang('room_price')?></h3>
				<table width="594" border="1" cellspacing="1" class="hometab">
					<tbody>
					<tr><th><?=lang('accommodation_building_type')?></th>
					<th><?=lang('accommodation_building_price')?></th>
					<th><?=lang('accommodation_building_info')?></th>
					<th><?=lang('accommodation_building_count')?></th>
					<th><?=lang('accommodation_building_action')?></th>
					</tr>
					<?php if(!empty($buildprice)){
						foreach($buildprice as $k => $v){
					?>
					<tr>
					
						<td><?//=$type[$v['campusid']]?><?=lang('root_type_'.$v['campusid'])?></td>
						<td>RMB <?=$v['prices']?><?=lang('room_danwei')?></td>
						<td><?=!empty($v['remark'])?$v['remark']:''?></td>
						<td>
						
							<?php 
								if(!empty($v['isroomset']) && $v['isroomset'] == 1){
							?>
							<?=$v['roomcount']?>
							
							<?php }else{?>
								--
							
							<?php }?>
						</td>
						
						
						
						
						<!--<td><div class="ydbtn"><a href="javascript:;" onclick="accommodation_book(<?=$v['columnid']?>,<?=$v['bulidingid']?>,<?=$v['id']?>)">住宿预订</a></div></td>-->
						<td>
						<?php if(!empty($function_on_off['accommaction']) && $function_on_off['accommaction'] == 'no'){?>
						
						<div class="ydbtn"><a href="javascript:;" onclick="no_accommodeation()"><?=lang('myacc_title')?></a></div>
						<?php }else{?>
						
							<?php 
								if(!empty($v['isroomset']) && $v['isroomset'] == 1){
							?>
								<?php if($v['roomcount'] > 0){?>
									<div class="ydbtn"><a href="javascript:;" onclick="login_accommodeation(<?=$v['id']?>)"><?=lang('myacc_title')?></a></div>
								
								<?php }else{?>
								
								--
								
								<?php }?>
							<?php }else{?>
							
							<div class="ydbtn"><a href="javascript:;" onclick="login_accommodeation(<?=$v['id']?>)"><?=lang('myacc_title')?></a></div>
							<?php }?>
						
						
							
						<?php }?>
						
						</td>
					</tr>
					<?php }}?>			
					</tbody></table>
			</div>
		</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function no_accommodeation(){
		var d = dialog({
					content: '<?=lang('no_accommodeation')?>'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 3000);
	}
</script>
<script type="text/javascript">
$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'img/loading.gif',
				play: 5000,
				pause: 2500,
				hoverPause: true,
				animationStart: function(){
					$('.caption').animate({
						bottom:-35
					},100);
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log(current);
					};
				}
			});
		});
</script>
<script type="text/javascript">
	function select_building(){
		var campid = $('#campid').val();
		if(campid != ''){
			$.ajax({
				type:'GET',
				url:'/accommodation_book/select_building?campid='+campid,
				success:function(r){
					if(r.state == 1){
						$('#buildingid').html(r.data);
					}
				},
				dataType:'json'
			
			
			});
		}else{
			return false;
		}
	}
	
	function select_room(){
		var buildingid = $('#buildingid').val();
		if(buildingid != ''){
			$.ajax({
				type:'GET',
				url:'/accommodation_book/select_room?buildingid='+buildingid,
				success:function(r){
					if(r.state == 1){
						$('#type').html(r.data);
					}
				},
				dataType:'json'
			
			
			});
		}else{
			return false;
		}
	}
</script>

<script type="text/javascript">
	function login_accommodeation(priceid){
		$.ajax({
			beforeSend:function(){
				var d = dialog({
						id:'cucasdialog',
						content: '<img src="<?=RES?>home/images/public/loading.gif">'
					});
					d.showModal();
					
			},
			type:'GET',
			url:'/<?=$puri?>/accommodation_book/login_accommodeation?priceid='+priceid,
			success:function(r){
				dialog({id:'cucasdialog'}).close();
				if (r.state == 1) {
					var d = dialog({
							content:r.data
					});
					d.showModal();
				}else{
					window.location.href=r.data;
				}
			},
			dataType:'json'

		});
	}
</script>
<script>



$(function(){
	$('#myform').ajaxForm({
		beforeSend:function(){
			var d = dialog({
					id:'cucasdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
		},
		success:function(msg){
				dialog({id:'cucasdialog'}).close();
			if(msg.state == 1){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				window.location.href='/<?=$puri?>/student/accommodation';
			}else if(msg.state == 0){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			}
		},
		dataType:'json'
	
	});
	
	

});
</script>
<?php $this->load->view('public/footer.php')?>
