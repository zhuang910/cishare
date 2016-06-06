<!DOCTYPE html>
<html>
<head>
<title><?php if(!empty($puri) && $puri == 'en'){?>Study in CUCAS - Apply China's Universities Online for Free<?php }else{?>来华留学网-中国大学在线申请和招生系统<?php }?></title>
<meta name="keywords" content="<?=!empty($seo['keywords'])?$seo['keywords']:'';?>" />
<meta name="description" content="<?=!empty($seo['description'])?$seo['description']:'';?>" /> 
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php $this->load->view('public/css_basic')?>
<link href="<?=RES?>home/css/page.css" rel="stylesheet" type="text/css" media="screen">
<?php $this->load->view('public/js_basic')?>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
</head>
<body>
<!--top start-->
<div class="top min">
  <div class="width_1024">
    <div class="f_l top-signup-out">
	    <div class="top-signup-in">
	    <?php if(!empty($_SESSION['student']['userinfo'])){?>
		    <a href="/<?=$puri?>/student/index" class="blue top-login login_true"><?=lang('hi')?><?=$puri=='en'?'! ':'！'?><?=!empty($_SESSION['student']['userinfo']['firstname']) && !empty($_SESSION['student']['userinfo']['lastname']) ? $_SESSION['student']['userinfo']['firstname'] .' '.$_SESSION['student']['userinfo']['lastname'] : $_SESSION['student']['userinfo']['email'] ?></a><span class="xian">&nbsp;</span><a href="/<?=$puri?>/student/login/out" class="blue top-login"><?=lang('login_out')?></a>
	    <?php }else{ ?>
		    <a href="/<?=$puri?>/student/login" class="blue top-login login_false"><?=lang('login')?></a><span class="xian">&nbsp;</span><a href="/<?=$puri?>/student/reg" class="blue top-login"><?=lang('register')?></a>
	    <?php }?>
	    </div>
		<div class="login_box">
			<dl class="germzii">
			
			  <dt>
			  <img id="student_pic" src="" width="81" height="81">
			  
			  </dt>
			  <dd class="name_login"><?=!empty($_SESSION['student']['userinfo']['email'])?$_SESSION['student']['userinfo']['email']:''?></dd>
			  <dd class="acotul"><?=!empty($_SESSION['student']['userinfo']['lastname'])?$_SESSION['student']['userinfo']['lastname']:''?>
				<?=!empty($_SESSION['student']['userinfo']['firstname'])?$_SESSION['student']['userinfo']['firstname']:''?>
			  </dd>	
			  <dd class="uplofin">
			  <a href="/<?=$puri?>/student/student/editphoto"><?=lang('editphoto')?></a>	
			    </dd>
				<dd class="uplofin">
			  		 <a href="/<?=$puri?>/student/index"><?=lang('my_CUCAS')?></a>
			  </dd>
			</dl>
			<ul class="list_vvv">
			  <li class="list1"><a href="/<?=$puri?>/student/student/editinfo"><?=lang('editinfo')?></a></li>
			  <li class="list2"><a href="/<?=$puri?>/student/student/editpassword"><?=lang('editpassword')?></a></li>
			  <li class="list3"><a href="/<?=$puri?>/student/student/stu_message"><?=lang('message_site')?></a><span id="xiaoxishu"></span></li>
			  <li class="list4"><a href="/<?=$puri?>/student/login/out"><?=lang('loginout')?></a></li>
			</ul>
		  </div> 
		<script type="text/javascript">
					

	$(".login_false").mouseover(function(){
		$('.login_box').hide();
	});
	$(".login_true").mouseover(function(){
		$('.login_box').show();
	});
	$(".top-signup-in a.top-login").eq(1).mouseover(function(){
		$('.login_box').hide();
	});
	$(".login_box").mouseover(function(){
		$('.login_box').show();
	});
	$(".login_box").mouseout(function(){
		$('.login_box').hide();
	});
		</script>
    </div>
    <div class="f_r">
      <div class="f_l top-nav-contact"><a href="/<?=$puri?>/pages?programaid=66"><?=lang('nav_66')?></a></div>
	  
      <span class="xian mg-t-b-11">&nbsp;</span>
      <div class="f_l top-nav"><a href="javascript:;"><?=lang('site_map')?></a>
        <ul class="map">
          <li><a href="/<?=$puri?>/"><?=lang('nav_1')?></a></li>
          <li><a href="/<?=$puri?>/course?programaid=0"><?=lang('nav_2')?></a></li>
          <li><a href="/<?=$puri?>/teacher"><?=lang('nav_21')?></a></li>
          <li><a href="/<?=$puri?>/schoollife?programaid=30"><?=lang('nav_30')?></a></li>
          <li><a href="/<?=$puri?>/accommodation_introduce"><?=lang('nav_67')?></a></li>
          <li><a href="/<?=$puri?>/scholarship?programaid=68"><?=lang('nav_68')?></a></li>
          <li><a href="/<?=$puri?>/pages?programaid=44"><?=lang('nav_44')?></a></li>
          <li><a href="/<?=$puri?>/about?programaid=73"><?=lang('nav_73')?></a></li>
          <li class="noborder"><a href="/<?=$puri?>/cost"><?=lang('nav_69')?></a></li>
        </ul>
      </div>
      <span class="xian mg-t-b-11">&nbsp;</span>
      
      <div class="f_l en <?=$puri=='en'?'Language':'Language2'?> top-nav Arial"><a href="#"><?=$site_language_open[$web_l[$puri]]?></a>
	  <?php 
		unset($site_language_open[$web_l[$puri]]);
		if(!empty($site_language_open)){
			$l_web = array_flip($web_l);
			
	  ?>
        <ul class="english" <?=$puri?> id="National_flag<?=$puri=='en'?'':'2'?>">
		<?php foreach($site_language_open as $kl => $vl){?>
          <li <?=$puri?>><a href="/<?=$l_web[$kl]?>"><?=$vl?></a></li>         
		  <?php }?>
        </ul>
		<?php }?>
      </div>
      <span class="xian mg-t-b-11">&nbsp;</span> </div>
  </div>
</div>
<!--top end-->
<!--logo start-->
<div class="width_1024 logo"> <a href="/<?=$puri?>/"><img src="<?=RES?>home/images/public/logo.png" alt="logo"/></a>
	<?php if(empty($function_on_off['search']) || (!empty($function_on_off['search']) && $function_on_off['search'] == 'yes')){?>
	  <div class="f_r">
		<form class="form" id="search_form" name="search_form" method="get" action="/<?=$puri?>/index/search">
		  <input type="text" id="search" name="search" placeholder="<?=lang('keywords')?>"/>
		  <a class="search-btn" href="javascript:;" onClick="search_submit()"></a>
		</form>
	  </div>
  <?php }?>
</div>
<!--logo end-->
<script type="text/javascript">
	$(document).ready(function(){
	　　$.ajax({
			url: '/<?=$puri?>/student/student/get_message_num',
			type: 'POST',
			dataType: 'json',
			data: {},
		})
		.done(function(r) {
			if(r.state==1){
				if(r.data==0){
					$('#xiaoxishu').css('display','none');
				}
				if(r.data>99){
					$('#xiaoxishu').text(r.data+'+');
				}else{
				$('#xiaoxishu').text(r.data);
				}
			}
		})
		.fail(function() {
			console.log("error");
		})

		　$.ajax({
			url: '/<?=$puri?>/student/student/get_stu_pic',
			type: 'POST',
			dataType: 'json',
			data: {},
		})
		.done(function(r) {
			if(r.state==1){
				$('#student_pic').attr('src',r.data);
				
			}
		})
		.fail(function() {
			console.log("error");
		})

	}); 
</script>