<script src="/resource/home/js/jquery.min.js"></script><link rel="stylesheet" href="/resource/home/js/plugins/artdialog/css/ui-dialog.css">
<script src="/resource/home/js/plugins/artdialog/dialog-min.js"></script></script>
<script src="/resource/home/js/plugins/from/jquery.form.js"></script>
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
.ss{
	 background: url("/resource/home/images/user/red_btn.png") repeat-x scroll 0 0 transparent;
    border: 1px solid #b90000;
    border-radius: 5px;
    display: inline-block;
    font-size: 14px;
    font-weight: bold;
    height: 28px;
    line-height: 28px;
    margin-bottom: 7px;
    text-align: center;
    width: 54px;
}
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
<div style="overflow:scroll; height:300px; width: 400px;">
	<div class="pop-login-form">
	<div id="acc_info" style="display:block;">	
      <form class="form-signin" role="form"  id="myform" name="myform" action="" method="post">
      <input type="hidden" name="term" value="<?=$term?>">
        <!--<div class="login-main-tips" id="errormsg" style="display:none;"></div>-->
        <ul>
        <li>
        <?php if(!empty($data)){?>
			<table>
				<tr>
					<td width="150px"><?=lang('book_name')?></td>
					<td width="100px"><?=lang('book_money')?></td>
					<td width="80px">&nbsp;</td>
				</tr>
				<?php foreach($data as $k=>$v){?>
				<tr>
					<td><?=$v['name']?></td>
					<td><?=$v['price']?></td>
					<td><input type="checkbox" name="ids[]" value="<?=$v['id']?>" onclick="price_add(this,<?=$v['price']?>)"></td>
				</tr>
				<?php }?>
				<tr>
					<td><?=lang('book_total')?></td>
					<td><span id="money_pages">0</span></td>
					<td><a class="ss" href="javascript:;" onclick="submits()">Pay</a></td>
				</tr>
			</table>
			<?php }?>
			</li>
        </ul>
      </form>
      </div>
    </div>
</div>
<!--关闭按钮-->
<script type="text/javascript">
	function login_close(){
		window.location.reload();
	}
	function price_add(th,mo){
		 var is = $(th).is(':checked');
		  if(is==true){
		    var num=parseInt($("#money_pages").text());
		    $("#money_pages").text(num+mo);
		  }else{
		    var num=parseInt($("#money_pages").text());
		    $("#money_pages").text(num-mo);
		  }
	}
	function submits(){
	  var money=$('#money_pages').text();
	  if(money==0){
	        var c = dialog({
	        width:100,
	        id:'win_repairs',
	        cancel:true,
	        fixed:true,
	       content: '请选择书籍'
	    });
	    c.show();
	  }
	  var data=$('#myform').serialize();
	  $.ajax({
	    url: '/<?=$puri?>/student/student/save_books_fee',
	    type: 'POST',
	    dataType: 'json',
	    data: data,
	  })
	  .done(function(r) {
	      if(r.state==1){
	        pays(r.data);
	     	  window.parent.location.href="/cn/student/student/book_fee?term=2&re=1";
	       
	      }
	  })
	  .fail(function() {
	    console.log("error");
	  })
	  
	}

	function pays(id){
	  var url = '/<?=$puri?>/pay_pa/index?applyid='+id;
	   window.open(url);
	}
</script>



























