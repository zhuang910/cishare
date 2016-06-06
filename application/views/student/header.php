<!DOCTYPE html>
<html>
<head>
<title><?php if(!empty($puri) && $puri == 'en'){?>Study in ZUST - Apply China's Universities Online for Free<?php }else{?>来华留学网-中国大学在线申请和招生系统<?php }?></title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php $this->load->view('public/css_basic')?>
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />

<?php $this->load->view('public/js_basic')?>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
</head>
<body>
<div class="user-logo min">
  <div class="width_925 clearfix"> <a href="/<?=$puri?>/" class="mg_t_16"><img src="<?=RES?>home/images/public/login_logo.png" alt="logo"/></a>
    <div class="f_r">
	<?php $login_reg = $this->uri->segment(3);?>
	<?php if(empty($_SESSION['student']['userinfo'])){?>
      <div class="login f_l"><a <?=!empty($login_reg) && $login_reg == 'login'?'class="hover"':''?> href="/<?=$puri?>/student/login"><?=lang('login')?></a></div>
      <div class="login f_l"><a <?=!empty($login_reg) && $login_reg == 'reg'?'class="hover"':''?> href="/<?=$puri?>/student/reg"><?=lang('register')?></a></div>
	  <?php }?>
      <div class="login f_l width-2"></div>
    </div>
  </div>
</div>