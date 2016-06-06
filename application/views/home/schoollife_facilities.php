<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
 <script type="text/javascript" src="/resource/home/js/plugins/zyjlist.js"></script>
  <style type="text/css">
img,fieldset{border:0;vertical-align:middle}
.clearfix:after{content:"\20";clear:both;height:0;display:block;overflow:hidden}.clearfix{*zoom:1}
.clear{font-size:0;line-height:0;height:0;overflow:hidden;clear:both;visibility:hidden}
.fl,.mark{float:left}.fr,.subMark{float:right}
.spanclass,.contentdiv{display:none}
.mb10{margin-bottom:10px;}
.f14{font-size:14px;}
.f16{font-size:16px;}
.cp{cursor:pointer;}
/* scrolltab */
.scrolltab{position:relative; margin:0 auto; width:773px; float:right;}
.scrolltab .ulBigPic{overflow:hidden; width:773px; height:450px; position:relative;}
.scrolltab .ulBigPic li{display:none;}
.scrolltab .ulBigPic .liSelected{display:block;}
.scrolltab .ulBigPic img{background-color:#ccc;}
.scrolltab .ulBigPic .sPic{}
.scrolltab .ulBigPic .sSideBox{width:290px;float:right;overflow:hidden;}
.scrolltab .sSideBox span{width:290px;display:block;overflow:hidden;}
.scrolltab .ulBigPic .sTitle{height:32px;line-height:32px;font-size:14px;font-weight:700;color:#333;}
.scrolltab .ulBigPic .sIntro{line-height:24px;color:#666;word-wrap:break-word;word-break:break-all;}
.scrolltab .ulBigPic .sMore{height:24px;line-height:24px;}
.scrolltab .sLeftBtnA,.scrolltab .sLeftBtnASel,.scrolltab .sRightBtnA,.scrolltab .sRightBtnASel,.scrolltab .sRightBtnABan{width:43px;height:96px;display:block;position:absolute;top:177px;background:url(/resource/home/images/public/bgArt.png) no-repeat;}
.scrolltab .sLeftBtnA,.scrolltab .sLeftBtnASel,.scrolltab .sRightBtnA,.scrolltab .sRightBtnASel{cursor:pointer;}
.scrolltab .sLeftBtnA,.scrolltab .sLeftBtnASel,.scrolltab .sLeftBtnABan{left:0px;}
.scrolltab .sLeftBtnA{background-position:-5px -2px;}
.scrolltab .sLeftBtnASel{background:#000 url(/resource/home/images/page/left_pic.png) no-repeat center;filter:alpha(Opacity=80);-moz-opacity:0.8;opacity: 0.8;}

.scrolltab .sRightBtnA,.scrolltab .sRightBtnASel,.scrolltab .sRightBtnABan{right:0px;}
.scrolltab .sRightBtnA{background-position:-36px 0;}
.scrolltab .sRightBtnASel{background:#000 url(/resource/home/images/page/pic_right.png) no-repeat center;filter:alpha(Opacity=80);-moz-opacity:0.8;opacity: 0.8;}
.scrolltab .sRightBtnABan{display: none}
.scrolltab .dSmallPicBox{height:95px;position:relative; width:773px;background: #064ca2;}
.scrolltab .dSmallPic{width: 656px;
height: 95px;
position: absolute;
right: 58px;
top: 0px;
overflow: hidden;
padding-top: 13px;}
.scrolltab .dSmallPic ul{position:absolute;}
.scrolltab .dSmallPic li{ float:left;display:inline;cursor:pointer;overflow:hidden; width:101px; height:68px; margin-right:10px; background-color:#fff; filter:alpha(Opacity=50);-moz-opacity:0.5;opacity: 0.5;z-index:100;}
.scrolltab .dSmallPic .sPic{display:block;}
.scrolltab .dSmallPic .sTitle{width:141px;height:30px;line-height:30px;display:block;text-align:center;color:#333;overflow:hidden;}
.scrolltab .dSmallPic .liSelected{ background-color:#fff; filter:alpha(Opacity=100);-moz-opacity:1;opacity:1;z-index:100;}/*---------------------*/
.scrolltab .dSmallPic .liSelected .sPic img{border-color:#fff;}
#sLeftBtnB{ background:url(/resource/home/images/page/left_right.png) no-repeat 0 0;width: 22px;height: 38px;display: block;float: left;margin-left: 20px;margin-top: 29px; cursor:pointer;}
#sLeftBtnB:hover{ background:url(/resource/home/images/page/left_right.png) no-repeat 0 -190px;}
#sRightBtnB{ background:url(/resource/home/images/page/left_right.png) no-repeat -171px 0; float:right; margin-right:20px; margin-top:29px;width: 22px;height: 38px; cursor:pointer;}
#sRightBtnB:hover{ background:url(/resource/home/images/page/left_right.png) no-repeat -171px -190px;}

.scrolltab .sLeftBtnB,.scrolltab .sLeftBtnBSel,.scrolltab .sRightBtnB,.scrolltab .sRightBtnBSel{cursor:pointer;}
.scrolltab .sLeftBtnB,.scrolltab .sLeftBtnBSel,.scrolltab .sLeftBtnBBan{left:20px;}
.scrolltab .sLeftBtnB{background-position:0 -87px;}
.scrolltab .sLeftBtnBSel{background-position:-36px -87px;}
.scrolltab .sLeftBtnBBan{background-position:-72px -87px;}
.scrolltab .sRightBtnB,.scrolltab .sRightBtnBSel,.scrolltab .sRightBtnBBan{right:20px;}
.scrolltab .sRightBtnB{background-position:-17px -87px;}
.scrolltab .sRightBtnBSel{background:#000 url(/resource/home/images/page/pic_right.png) no-repeat center;filter:alpha(Opacity=80);-moz-opacity:0.8;opacity: 0.8;}
.jieshao{ padding:30px; background:#eee; border-bottom:4px solid #064ca2;}
.jieshao dl dt{ font-size:18px; padding-bottom:10px;}
.jieshao dl dd{ font-size:12px; color:#555; line-height:20px;}

.xuaidui{ position:absolute; width:773px; height:450px; cursor:pointer;}

.jiantoufu:hover .sLeftBtnA{ display:block;}
.jiantoufu{ width:386px; height:450px; position:relative; float:left;}
.jiantoufu .sLeftBtnA{ position:absolute; left:0; top:177px; background:#000 url(/resource/home/images/page/left_pic.png) no-repeat center; filter:alpha(Opacity=50);-moz-opacity:0.5;opacity: 0.5;z-index:100; width:43px; height:96px; display:none;}
.jiantoufu .sLeftBtnA:hover{ background:#000 url(/resource/home/images/page/left_pic.png) no-repeat center;filter:alpha(Opacity=80);-moz-opacity:0.8;opacity: 0.8;}

.jiantoufu2:hover .sRightBtnA{ display:block;}
.jiantoufu2{ width:386px; height:450px; float:right; position:relative;}
.jiantoufu2 .sRightBtnA{ position:absolute; right:0; top:177px; background:#000 url(/resource/home/images/page/pic_right.png) no-repeat center;filter:alpha(Opacity=50);-moz-opacity:0.5;opacity: 0.5;z-index:100; width:43px; height:96px; display:none;}
.jiantoufu2 .sRightBtnA:hover{ background:#000 url(/resource/home/images/page/pic_right.png) no-repeat center;filter:alpha(Opacity=80);-moz-opacity:0.8;opacity: 0.8;}
</style>
<div class="width_1024 clearfix mg-t-50">
  <div class="three-left-nav">
    <ul>
      <?php 
		
			if(!empty($l_n)){
				foreach($l_n as $lk => $lv ){
		?>
      <li class="<?=!empty($programaid) && $programaid == $lv['programaid']?'selected':''?>"> <a href="/<?=$puri?>/schoollife/facilities?programaid=<?=$lv['programaid']?>">
        <?=lang('nav_'.$lv['programaid'])?>
        </a> </li>
      <?php }}?>
    </ul>
  </div>
<div class="pickup-main">
<div class="crumbs-nav mb30"><a href="/<?=$puri?>/"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/schoollife?programaid=30"><?=lang('nav_30')?></a><i> / </i><span><?=lang('nav_'.$programaid)?></span></div>
<?php if(!empty($data)){?>
<div class="facilitiesdetails">
	
		<div class="scrolltab">
			<ul class="ulBigPic">
				<div class="xuaidui">
				<div class="jiantoufu">
				  <div class="sLeftBtnA" id="sLeftBtnA"></div>
				</div>
				  <div class="jiantoufu2">
				  <div class="sRightBtnA" id="sRightBtnA"></div>
				  </div>
				</div>
				  <?php 
						if(!empty($data)){
							foreach($data as $k => $v){
					?>
				  <li <?php if($k == 0){?> class="liSelected"<?php }?>> <span class="sPic"> <i class="iBigPic"><a href="javascript:;" title="<?=!empty($v['title'])?$v['title']:''?>"><img alt="<?=!empty($v['title'])?$v['title']:''?>" src="<?=!empty($v['image'])?$v['image']:''?>" width="773" height="450" /></a></i> </span> <span class="sSideBox"> <span class="sTitle"><a href="javascript:;"  title="<?=!empty($v['title'])?$v['title']:''?>">
					<?=!empty($v['title'])?$v['title']:''?>
					</a></span> <span class="sIntro"> </span>
					</span>
				  </li>
				  <?php }}?>
			</ul>
				<!--ulBigPic end-->
			<div class="dSmallPicBox">
				
				  <div class="dSmallPic">
					<ul class="ulSmallPic" style="width:2646px;left:0px" rel="stop">
					  <?php 
								if(!empty($data)){
									foreach($data as $k => $v){
							?>
					  <li <?php if($k == 0){?> class="liSelected"<?php }?>> <span class="sPic"><img alt="<?=!empty($v['title'])?$v['title']:''?>" src="<?=!empty($v['image'])?$v['image']:''?>" width="101" height="68" /></span> <span class="sTitle">
						<?=!empty($v['title'])?$v['title']:''?>
						</span> </li>
					  <?php }}?>
					</ul>
				  </div>
				  <span id="sLeftBtnB" class="sLeftBtnBBan"></span> <span id="sRightBtnB" class="sRightBtnB"></span> 
			 </div>
			  <div class="jieshao">
				  <dl>
				  <?php
					if(!empty($data)){
					  foreach($data as $k => $v){
					?>
					  <dl <?=$k == 0 ? '' : 'style="display:none"'?> data-img="true">
						  <dt><?=!empty($v['title'])?$v['title']:''?></dt>
						  <dd><?=!empty($v['info'])?$v['info']:''?></dd>
					  </dl>
					<?php }}?>
				</div>
				<!--dSmallPicBox end-->
		</div>
	
</div>
<?php }else{?>
		<div class="f_l share-right">
			<p class="no_data"><?=lang('no_data')?></p>
		</div>
				 
		
	<?php }?>
</div>

</div>
<?php $this->load->view('public/footer.php')?>
