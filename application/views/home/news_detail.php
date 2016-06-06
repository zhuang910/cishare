<?php $this->load->view('public/header.php')?>
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
	height:350px;
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
	top:15px;
	left:4px;
	z-index:100;
}

#slides .next,#slides .prev {
	position:absolute;
	top:107px;
	left:-39px;
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
	left:585px;
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
<!--内容-->
<div class="width_1024 clearfix mg-t-50">
	 <div class="three-left-nav">
    <ul>
      <?php 
			if(!empty($l_n)){
					foreach($l_n as $lk => $lv ){
					if($lv['programaid'] != 105 && $lv['programaid'] != 87){
					
			?>
      <li class="<?=!empty($programaid) && $programaid == $lv['programaid']?'selected':''?>"> <a href="/<?=$puri?>/news?programaid=<?=$lv['programaid']?>">
        <?=lang('nav_'.$lv['programaid'])?>
        </a> </li>
      <?php }}}?>
	  
	  <li class="<?=!empty($programaid) && $programaid == 39?'selected':''?>"> <a href="/<?=$puri?>/news?programaid=39">
        <?=lang('nav_39')?>
        </a> </li>
    </ul>
  </div>
	<div class="newscenter-main">
	<div class="crumbs-nav"><a href="/<?$puri?>"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/news?programaid=<?=$programaid?>"><?=lang('nav_'.$programaid)?></a><i> / </i><span><?=lang('news_detail')?></span></div>
		<div class="CUCAS-map-speak mt30">
		<h4><?=!empty($result['title'])?$result['title']:''?></h4>
		<h6><?=!empty($result['createtime'])? date('Y-m-d',$result['createtime']):''?></h6>
		<?=!empty($result['content'])?$result['content']:''?>
		<?php if(!empty($jpkc)){?>
		
			<?php if(!empty($jpkc->video)){?>
				<iframe width="640" align="center" height="480" id="win" name="win"  frameborder="0" scrolling="no" src="<?=$jpkc->video?>"></iframe>
			<?php }else{?>
				<div id="container">

						<div id="example">
							<div id="slides">
								<div class="slides_container">
									<?php if(!empty($imgs)){
									
	
										foreach ($imgs as $key => $value) {
											
									?>
										<div>
											<img src="<?=!empty($value['image'])?$value['image']:''?>" width="570" height="270" alt="Slide 1">
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
			
			<?php }?>
			
		
		<?php }?>
		 
		</div>
	</div>
</div>

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
<?php $this->load->view('public/footer.php')?>
