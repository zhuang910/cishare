<script src="<?=RES?>home/js/plugins/slides.min.jquery.js"></script>
<style>
/* 
	Resets defualt browser settings
	reset.css
*/
#container {
	margin:0 auto;
	position:relative;
	z-index:0;
	background:#ccc;
}

#example {
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
	position: relative;
	z-index:100;
	width:100%;
}

#slides .prev{ 
	position:absolute;
	top:115px;
	width:24px;
	height:43px;
	display:block;
	z-index:101;
	right:0;
}
#slides .next{
	position:absolute;
	top:115px;
	right:-39px;
	width:24px;
	height:43px;
	display:block;
	z-index:101;
	left:0;
}

.slides_container {
	height:281px;
	overflow:hidden;
	position:relative;
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
	height:30px;
	padding:5px 20px 0 20px;
	background:#000;
	background:rgba(0,0,0,.5);
	width:540px;
	font-size:1.3em;
	line-height:1.33;
	color:#fff;
	border-top:1px solid #000;
	text-shadow:none;
}
</style>
<script>
		$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: '<?=RES?>home/images/public/loading.gif">',
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
<div id="container">
		<div id="example">
			
			<div id="slides">
				<div class="slides_container">
					 <?php if(!empty($data)){

						foreach ($data as $key => $value) {
							
					?>
					
						<div>
						<a href="javascript:;" title="<?=!empty($value['title'])?$value['title']:''?>" ><img src="<?=!empty($value['image'])?$value['image']:''?>" alt="<?=!empty($value['title'])?$value['title']:''?>"></a>
						<!-----<div class="caption" style="bottom:0">
							<p><?=!empty($value['title'])?$value['title']:''?></p>
						</div>----->
						</div>
					<?php }}?>
				
					
				</div>
				<a href="#" class="prev"><img src="<?=RES?>/home/images/public/arrow-prev.png" width="24" height="43" alt="上一张"></a>
				<a href="#" class="next"><img src="<?=RES?>/home/images/public/arrow-next.png" width="24" height="43" alt="下一张"></a>
			</div>
			
		</div>
       
	</div>
	
<div id="controller">
<div class="width_1024dd">
	<?php if(!empty($conumn_title)){
		foreach ($conumn_title as $ckey => $cvalue) {
	?>
			<span class="flowctrl"><a href="javascript:sel_fac(<?=$ckey?>);"><?=$cvalue?></a></span> 
	<?php }}?>
	
</div>	
</div><!--controller end-->