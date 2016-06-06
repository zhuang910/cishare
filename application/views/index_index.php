
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>浙江科技首页</title>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<!-- <link href="<?=RES?>home/css/global.css" rel="stylesheet" type="text/css" /> -->
<?php $this->load->view('public/js_basic')?>
<?php $this->load->view('public/js_artdialog')?>

</head>
<body>
<div class="top clearfix">
<?php if(!empty($_SESSION['student']['userinfo'])){?>
<div class="reg fl"><a href="/<?=$puri?>/student/index"><?=lang('hi')?><?=$puri=='en'?'! ':'！'?><?=!empty($_SESSION['student']['userinfo']['firstname']) && !empty($_SESSION['student']['userinfo']['lastname']) ? $_SESSION['student']['userinfo']['firstname'] .' '.$_SESSION['student']['userinfo']['lastname'] : $_SESSION['student']['userinfo']['email'] ?></a><span class="xian">&nbsp;</span></div>
<div class="reg fl"><a href="/<?=$puri?>/student/login/out" class="blue top-login"><?=lang('login_out')?></a></div>
 <?php }else{ ?>
  <div class="reg fl"><a href="/<?=$puri?>/student/login"><?=lang('login')?></a></div>
  <div class="reg fl"><a href="/<?=$puri?>/student/reg"><?=lang('register')?></a></div>
 <?php }?>
  <div class="eng fl">
    <a id="one" onclick="language(this,'cn')" class="cn"></a>
    <a id="two"  onclick="language(this,'en')" class="eng"></a>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  var la='<?=$puri?>';
  if(la=='cn'){
    $('#one').text('English');
    $('#two').text('Chinese');
     $('#one').attr({
       style:"background:#589c27 url(<?=RES?>home/images/home/zust/eng.png) no-repeat 15px;",
       href:'/en/'
     });
     $('#two').attr({
       style:"background: url(<?=RES?>home/images/home/zust/cn.png) no-repeat 5px;",
     });
  }
  if(la=='en'){
    $('#one').text('Chinese');
    $('#two').text('English');
    $('#one').attr({
       style:"background:#589c27 url(<?=RES?>home/images/home/zust/cn.png) no-repeat 15px;",
       href:'/cn/'
     });
     $('#two').attr({
       style:"background: url(<?=RES?>home/images/home/zust/eng.png) no-repeat 5px;",
     });
  }
  
})
  function language(th,lan){
    
  }
</script>
<div class="con">
  <div class="width960 clearfix">
    <div class="left_c fl">
      <div class="logo"></div>
      <a href="/<?=$puri?>/student/index"><div class="apply"> 
        <dl class="box_font clearfix">
          <dt class="pic_bg"></dt>
          <dd>
            <h1>在线申请</h1>
          </dd>
          <dd>
            <h3>online application</h3>
          </dd>
        </dl>
        </div></a> 
    </div>
    <div class="center_c fl">
      <a href="/<?=$puri?>/student/student/accommodation"><div class="application"> 
        <dl class="box_font clearfix">
          <dt class="pic_bg2"></dt>
          <dd>
            <h1>住宿预订</h1>
          </dd>
          <dd  style="padding-left:65px;">
            <h3>Accommodation Reservation</h3>
          </dd>
        </dl>
        </div></a> 
      <a href="/<?=$puri?>/student/student/pickuplist"><div class="online"> 
        <dl class="box_font clearfix">
          <dt class="pic_bg3"></dt>
          <dd>
            <h1>接机预订</h1>
          </dd>
          <dd>
            <h3>Airport Pickup</h3>
          </dd>
        </dl>
         </div></a>
    </div>
    <div class="right_c fr">
      <a href="/<?=$puri?>/student/index"><div class="apply1"> 
        <dl class="box_font clearfix">
          <dt class="pic_bg4"></dt>
          <dd>
            <h1>申请跟进</h1>
          </dd>
          <dd>
            <h3 style="padding-left:3px;">Tracking Progress</h3>
          </dd>
        </dl>
        </div></a> 
		<?php if(!empty($notice)){?>
      <div class="news" id="evaluate_page">
        <dl class="news_font">
          <dt><?=!empty($notice[0]['createtime'])?date('m',$notice[0]['createtime']).'-'.date('d',$notice[0]['createtime']):''?></dt>
          <dd>
            <h2><a href="<?php 
				if(!empty($notice[0]['isjump']) && $notice[0]['isjump'] == 1){
					echo $notice[0]['jumpurl'];
				}else{
					echo "/{$puri}/index/noticedetail?id=".$notice[0]['id'];
				}
			
			?>"><?=!empty($notice[0]['title'])?$notice[0]['title']:''?></a></h2>
          </dd>
          <dd class="font_gray"><?=!empty($notice[0]['desperation'])?$notice[0]['desperation']:''?></dd>
        </dl>
        <div class="qh clearfix">
          <div class="qh_l" onclick="prev(<?=$page?>)"></div>
          <div class="qh_r" onclick="next(<?=$page?>)"></div>
        </div>
      </div>
	  <?php }?>
    </div>
  </div>
</div>
<div class="bottom clearfix">
  <div class="left_nav fl">
    <ul class="clearfix">
      <li class="active"></li>
      <li></li>
      <li></li>
    </ul>
  </div>
  <div class="right_copy fr">
    <dl class="clearfix">
      <dt>© 2008 - 2014 ZUST, Inc. All rights reserved</dt>
      <dd class="a1"><a href="#"></a></dd>
      <dd class="a2"><a href="#"></a></dd>
      <dd class="a3"><a href="#"></a></dd>
    </dl>
  </div>
</div>
<script type="text/javascript">
		
	var pagecount = <?=!empty($pagecount)?$pagecount:0?>;
	function next(page){
		page ++;
		if(page <= pagecount){
			$.ajax({
				type:'GET',
				url:'/<?=$puri?>/index/get_data?flag=1&page='+page,
				success:function(r){
					if(r.state == 1){
						$('.news_font').hide('slow');
						$('.news_font').remove();
						
						$('#evaluate_page').html(r.data);
					}else{
						var d = dialog({
							content: ''+r.info+''
						});
						d.show();
						setTimeout(function () {
							d.close().remove();
						}, 2000);
					}
				},
				dataType:'json'

			});
		}else{
var d = dialog({
				content: '<?=lang('no_data')?>'
			});
			d.show();
			setTimeout(function () {
				d.close().remove();
			}, 2000);
			
		}
	
	}
	
	function prev(page){
	
		page --;
		if(page >= 1){
			$.ajax({
				type:'GET',
				url:'/<?=$puri?>/index/get_data?flag=2&page='+page,
				success:function(r){
					if(r.state == 1){
						$('#evaluate_page').html();
						$('#evaluate_page').html(r.data);
					}else{
						var d = dialog({
							content: ''+r.info+''
						});
						d.show();
						setTimeout(function () {
							d.close().remove();
						}, 2000);
					}
				},
				dataType:'json'

			});
		}else{
			var d = dialog({
				content: '<?=lang('no_data')?>'
			});
			d.show();
			setTimeout(function () {
				d.close().remove();
			}, 2000);
		}
		}
	

</script>
</body>
</html>
