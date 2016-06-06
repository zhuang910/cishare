<script src="/resource/home/js/jquery.min.js"></script><link rel="stylesheet" href="/resource/home/js/plugins/artdialog/css/ui-dialog.css">
<script src="/resource/home/js/plugins/artdialog/dialog-min.js"></script></script>
<script src="/resource/home/js/plugins/from/jquery.form.js"></script>
<?php $this->load->view('public/js_My97DatePicker')?>
<style>
/* == 错误提示的样式 == */
span.error {background: url("/resource/home/images/public/unchecked.gif") no-repeat scroll 4px center transparent;
color: red;
overflow: hidden;
padding-left: 24px;
height: 25px;
line-height: 25px;
display: block;}
span.success { background: url("/resource/home/images/public/checked.gif") no-repeat scroll 4px center transparent; color: red; overflow: hidden; padding-left: 19px; }
</style><!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<link href="<?=RES?>home/css/global.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" media="screen">

<style>
.clear{ clear:both;}
.clearfix{zoom:1;}
.clearfix:after{clear:both; content:"";display:block;height:0;line-height:0;visibility:hidden;}
.po-login{ width:405px; border:5px solid #eaeaea; border-radius:5px; background-color:#fff; box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
.closed{ width:25px; height:25px; display:block; margin-bottom:15px; float:right; background-color:#eaeaea; box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; }
a.close-img{ width:100%; height:100%; display:block; cursor:pointer; background:url(<?=RES?>home/images/user/icon222.png) 0px -25px no-repeat;box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
a.close-img:hover{background:url(<?=RES?>home/images/user/icon222.png) no-repeat;}
.qiehuan{margin:40px 40px 30px 40px; height:37px; font-size:16px; font-family:"微软雅黑",simsun,Arial,"黑体",Helvetica,sans-serif; color:#000; line-height:37px; border-bottom:2px solid #d3d3d3;}
.qiehuan > ul{ float:left;}
.qiehuan > ul > li{ float:left; height:37px; padding:0px 25px; cursor:pointer;}
li.selected{ border-left:2px solid #d3d3d3; border-right:2px solid #d3d3d3; border-top:2px solid #d3d3d3; border-bottom:2px solid #fff;; border-top-left-radius:5px; border-top-right-radius:5px; background:url(<?=RES?>home/images/user/bg-pop-login.gif) repeat-x;}
.tongyong{height:34px; padding:0px 10px; border:1px solid #d8d8d8; line-height:34px; font-size:12px; background-color:#fff;}
.width_322{width:322px;}
ul,li{list-style:none; font-family: "微软雅黑";}
.mg_b_20 {margin-bottom: 20px;}
input.ft_12 {font-size: 12px;}
.pop-login-form{margin:0px 40px 40px 40px;}
</style>
<div class="po-login clearfix">

	<div class="qiehuan">
		<ul>
			<li class="selected" id="login"><?=lang('shibaiyuanyin')?></li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="pop-login-form">
        <ul>
			<li class="mg_b_20">
			<textarea id="remark" style="width:300px;height:200px;" ><?=$info?></textarea>
			<div class="sign-up-right"></div>
			</li>
        </ul>
    </div>
</div>
<!--关闭按钮-->
<script type="text/javascript">
	function login_close(){
        top.window.location.reload();
	}
</script>



























