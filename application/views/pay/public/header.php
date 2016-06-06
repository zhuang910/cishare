<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Payment</title>
<link rel="stylesheet" type="text/css" href="<?=RES?>home/pay/bootstrap.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?=RES?>home/pay/main.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?=RES?>home/pay/apply.css" media="screen" />
<script src="<?=RES?>home/pay/jquery.min.js"></script>
<?php $this->load->view('public/js_artdialog')?>
<script src="<?=RES?>home/js/plugins/jquery.validate.js"></script>
<script src="<?=RES?>home/js/plugins/from/jquery.form.js"></script>
<style>
/* == 错误提示的样式 == */
span.error {background: url("/resource/home/images/public/unchecked.gif") no-repeat scroll 4px center transparent;
color: red;
overflow: hidden;
padding-left: 24px;
height: 25px;
line-height: 25px;
display: block;}
span.success { background: url("<?=RES?>home/images/public/checked.gif") no-repeat scroll 4px center transparent; color: red; overflow: hidden; padding-left: 19px; }
</style>
</head>

<body>


<div class="Head">
    <img src="/resource/home/images/public/logo.png" class="ImgLogo" /><span class="HeadText HeadText777"><span>·</span>Payment</span>
    <div class="BuyStep" style="height:40px; line-height:40px;">
        <div style="background-color:#f1f1f1; float:right; padding-right:10px;">Email: 1247300855@qq.com</div>
        <div style="float:right;background-image:url('<?=RES?>home/pay/apply/regStep1.png')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div style="background-color:#0277c4;color:#fff; float:right; padding-left:10px;">Tel: +86-010-64369356 </div>
    </div>
</div>