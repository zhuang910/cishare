<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.min.js"></script>
<script src="<?=RES?>home/js/plugins/veCarousel.js" type="text/javascript"></script>
<link href="<?=RES?>home/css/veCarousel.css" rel="stylesheet" type="text/css" />
<style>
/* == banner ==*/
.banner{ width:960px; height:auto; margin:0 auto; overflow:hidden; clear:both;}

.banimg{ position:relative; width:700px; float:left;}
.bantext{ position:absolute; bottom:0px; left:0px; width:698px; height:86px; background:url(/resource/home/images/public/bg1.png) 0 0; color:#FFF;}
.bantext h2{ font-size:20px; line-height:40px; padding:10px;background:none;font-family: Arial, "微软雅黑";font-weight:normal;}
.bantext h2 a{ color:#fcfcfc}
.bantext h3{ padding:0 0 0 10px;}
.bantext h3 a{ color:#CCC;}

.ban_btn{ z-index:10; position:absolute; bottom:30px; right:30px;}
.ban_btn a{ display:inline-block; margin-right:2px; width:24px; height:24px; float:left;}
.ban_btn_left{ background:url(../images/btn_lbg4.jpg) no-repeat 0 0;}
.ban_btn_left:hover{ background:url(../images/btn_lbg3.jpg) no-repeat 0 0;}
.ban_btn_right{ background:url(../images/btn_rbg4.jpg) no-repeat 0 0;}
.ban_btn_right:hover{ background:url(../images/btn_rbg3.jpg) no-repeat 0 0;}
.ban_stop{ background:url(../images/stop.jpg) no-repeat 0 0;}

.banlink{ float:left; width:260px;}

</style><?php if(!empty($images)){?>
<div class="banner" style='width:520px;' >
<div class="carousel" id="hp_carousel">
		<?php foreach($images as $k => $info){?>
			<div class="wrap carousel-item">
			<img src="<?=$info['image']?>" width="520" height="419">
			<div class="bantext" style='width:520px;height:auto;'>
				<h2>
						<?=!empty($info['title'])?cut_str($info['title'],100):''?>
					
				</h2>
				
			</div>
		</div>
		<?php }?>
    </div>
</div>
<?php }else{?>
<?=lang('no_img')?>
<?php }?>