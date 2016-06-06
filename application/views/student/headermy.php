<!DOCTYPE html>
<html>
<head>
<title><?php if(!empty($puri) && $puri == 'en'){?>Study in ZUST<?php }else{?>浙江科技学院<?php }?></title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php $this->load->view('public/css_basic')?>
<?php $this->load->view('public/js_basic')?>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<link href="<?=RES?>home/css/applyonline.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
</head>
<body>
<div class="applyonline-head">
	<div class="width_925 clearfix">
		<div class="f_l logo-personal">
			<div class="bg-logo-personal">
				<a href="/<?=$puri?>/" class="logo-personal-img"><img src="<?=RES?>home/images/user/center_logo.png"/></a>
			</div>
		</div>
		<div class="right_user f_r" style="border:none">
		<div class="f_r applyonline-head-nav clear">
            <?php if(!empty($_SESSION ['student'] ['userinfo']['id'])){?>
                <ul>

                    <li><a href="#" class="applyonline-head-nav-selected"><span class="login_img"> <img id="student_pics" src="" width="61" height="61"></span></a></li>
                </ul>
            <?php }?>
			<div class="login_box login_box2">
			<dl class="germzii">
			  <dt>
			  <img id="student_pic" src="" width="81" height="81">
			  
			  </dt>
			  <dd class="name_login"><?=!empty($_SESSION['student']['userinfo']['email'])?$_SESSION['student']['userinfo']['email']:''?></dd>
			  <dd class="acotul"><?=!empty($_SESSION['student']['userinfo']['lastname'])?$_SESSION['student']['userinfo']['lastname']:''?>
<?=!empty($_SESSION['student']['userinfo']['firstname'])?$_SESSION['student']['userinfo']['firstname']:''?>
			  </dd>	
			  <dd class="uplofin"><a href="/<?=$puri?>/student/student/editphoto"><?=lang('editphoto')?></a></dd>
			</dl>
			<ul class="list_vvv">
			  <li class="list1"><a href="/<?=$puri?>/student/student/editinfo"><?=lang('editinfo')?></a></li>
			  <li class="list2"><a href="/<?=$puri?>/student/student/editpassword"><?=lang('editpassword')?></a></li>
			  <li class="list3"><a href="/<?=$puri?>/student/student/stu_message"><?=lang('message_site')?></a><span id="xiaoxishu"></span></li>
			  <li class="list4"><a href="/<?=$puri?>/student/login/out"><?=lang('loginout')?></a></li>
			</ul>
		  </div>
		  <?php if(!empty($_SESSION['student']['userinfo'])){?>
			<div class="logo-personal-line"></div>
			<?php }?>
		</div>
        <div class="f_l en chinese <?=$puri=='en'?'Language_my':'Language_my_2'?> top-nav Arial"><span></span><em style="float:left;"><a href="#"><?=$puri=='en'?'English':'Chinese'?></a></em>
			<ul class="language_my_dropdown" <?=$puri?> id="National_flag<?=$puri=='en'?'':'2'?>" >
			  <?php if($puri=='en'):?>
			 	 <li <?=$puri?>><a href="/cn/student/index">Chinese</a></li>  
			  <?php else:?>
		 		  <li <?=$puri?>><a href="/en/student/index">English</a></li>  
			  <?php endif;?>       
			</ul>
		  </div>
        </div>
		
		
	</div>
</div>
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
			type: 'GET',
			dataType: 'json',
			data: {},
		})
		.done(function(r) {
			if(r.state==1){
				$('#student_pic').attr('src',r.data);
				$('#student_pics').attr('src',r.data);
			}
		})
		.fail(function() {
			console.log("error");
		})

	}); 	
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".applyonline-head-nav ul li").mouseenter(function(){
			$(".login_box").show();
		});
		$(".login_box").mouseover(function(){
			$(".login_box").show();
		});
	$(".login_box").mouseout(function(){
			$(".login_box").hide();
		});
	});
	
	 $(document).ready(function(){
       $(".top-nav").mouseover(function(){
	      $(this).next("span.xian").hide();
		  $(this).prev("span.xian").hide();
	      //$(this).css({"background-color":"#fff","border-left":"1px solid #b8b8b8","border-right":"1px solid #b8b8b8","border-bottom":"2px solid #fff"});
          $(this).find("ul").show();
		  $(this).find(".mapxian-1").show();
        });
        $(".top-nav").mouseout(function(){
		  $(this).next("span.xian").show();
		  $(this).prev("span.xian").show();
	     // $(this).css({"background-color":"transparent","border":"none"});
          $(this).find("ul").hide();
		  $(this).find(".mapxian-1").hide();
        });
    });
</script>