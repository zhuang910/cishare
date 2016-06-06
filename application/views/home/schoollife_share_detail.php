<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<div class="width_1024 clearfix">
    <div class="crumbs-nav clearfix mg_t_b_5030"><a href="/<?=$puri?>"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/schoollife?programaid=30"><?=lang('nav_30')?></a><i> / </i><span> <?=lang('nav_35')?></span></div>
	<div class="f_l share-left">
		<img src="<?=!empty($result->photo)?$result->photo:''?>" alt="" width="155" height="188"/>
		<p class="pragraph-1"><?=!empty($result->name)?$result->name:''?></p>
		<p class="pragraph"><?=!empty($result->class)?$result->class:''?></p>
		<!--<div class="share-left-btn">
			<a href="#">随便看看</a>
		</div>-->
	</div>
	<div class="f_l share-right">
		<?php if(!empty($share)){?>
		<div class="sharenow mb30">
			<p class="sharenow-title"><?=lang('new_share')?></p>
			<div class="togglebox">
			
				<div class="fortogg">
                    <p class="plist"><font><?=!empty($share[0]['title'])?$share[0]['title']:''?></font><span class="btntogg"></span></p>
                    <div class="toggcont"><p><?=!empty($share[0]['info'])?$share[0]['info']:''?> </p></div>
                </div>
		
            	
            </div>
			
		</div>
		<div class="sharenow">
			<p class="sharenow-title"><?=lang('more_share')?></p>
			<div class="togglebox">
			<?php foreach($share as $k => $vv){?>
				<div class="fortogg">
                    <p class="plist"><font><?=!empty($vv['title'])?$vv['title']:''?></font><span class="btntogg"></span></p>
                    <div class="toggcont"><p><?=!empty($vv['info'])?$vv['info']:''?> </p></div>
                </div>
			<?php }?>
            	
            </div>
		</div>
	<?php }else{?>
	<p class="no_data"><?=lang('no_data')?></p>
	<?php }?>
	</div>
</div>
<script type="text/javascript">
$(function(){
	
	$(".plist").click(function(){
		$(".toggcont").slideUp();
		var that = $(this);
		var next = that.next('.toggcont:hidden');
		$(".plist").css({borderBottom:"1px dotted #b5b5b5"});
		$(".plist").find("span.btntogg").css('background','url(/resource/home/images/home/togg3.jpg)');
		next.slideDown();
		if($.trim(next.html()) !== ''){
			that.css({borderBottom:"0px dotted #b5b5b5"});
			that.find("span.btntogg").css('background','url(/resource/home/images/home/togg2.jpg)');
		}
	});
});
</script>
<?php $this->load->view('public/footer.php')?>
