<!DOCTYPE html>
<html>
<head>
<title>留学北信 - 北京信息职业技术学院</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?=RES?>home/css/global.css" rel="stylesheet" type="text/css" />
<script src="<?=RES?>home/js/jquery.min.js"></script>
<link rel="stylesheet" href="<?=RES?>home/js/plugins/artdialog/css/ui-dialog.css">
<script src="<?=RES?>home/js/plugins/artdialog/dialog-min.js"></script>
<script src="<?=RES?>home/js/plugins/artdialog/dialog-plus-min.js"></script>
<script src="<?=RES?>home/js/plugins/jquery.validate.js"></script>

<script src="<?=RES?>home/js/plugins/validate/localization/messages_zh.js"></script>

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
<link href="<?=RES?>home/css/applyonline.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
</head>
<body>

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
<link rel="stylesheet" href="<?=RES?>home/css/bootstrap.min.css">
<script type="text/javascript" src="<?=RES?>home/js/plugins/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?=RES?>home/js/plugins/poshytip/tip-twitter/tip-twitter.css">
<link rel="stylesheet" href="<?=RES?>home/js/plugins/jFormer/jformer.css">
<link rel="stylesheet" href="<?=RES?>home/css/apply.css">
<script type="text/javascript" src="<?=RES?>home/js/plugins/poshytip/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.sticky.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.scrollTo.min.js"></script>
<link media="screen" rel="stylesheet" href="<?=RES?>home/js/plugins/datepicker/datepicker.css">
<script type="text/javascript" src="<?=RES?>home/js/plugins/datepicker/bootstrap-datepicker.js"></script>
 
<div class="width_925 clearfix">
	
</div>
<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<ul class="c2">
			<li class="d2"><a href="/master/enrollment/fillingoutforms/apply?applyid=<?=$a_id?>&template=1"><span>2</span> <em>Application Form</em></a></li>
			<li class="d3"><a href="/master/enrollment/upload_attachment/upload_materials?applyid=<?=$a_id?>"><span>3</span> <em>Upload Materials</em></a></li>
		</ul>
	
	</div>
	<div class="applyonline-2-main">
		 <div class='float_nav'>
			<?=$html_left?>
		</div>
		<div class="f_r applyonline-left-nav-width625">
		<form method="post" id="myform" action="/master/enrollment/fillingoutforms/save/<?=$cid?>" onsubmit="return scroll_to_error();">
			<?=$html_form?>
			<div class="applyonline-2-btn">
				
				
				<input type="button" class="appBtnSN" onclick="do_save()" value="Save">
				
			</div>
		</form>
		</div>
	</div>
</div>

<script type="text/javascript">
$('.RFormQues').poshytip({
	className: 'tip-twitter',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	offsetY: 5,
	allowTipHover: false,
	fade: false,
	slide: false
});

function scroll_to_error(){
	setTimeout(function(){
		if($('input.error').length > 0){
			$.scrollTo( $('input.error').offset().top,600 );
		}
	},300);
}

function do_save(){
	var data = $("#myform").serialize();
	$.ajax({
		url:'/master/enrollment/fillingoutforms/save/<?=cucas_base64_encode($apply_info['id'])?>',
		type:'post',
		data:data,
		dataType:'json',
		beforeSend:function(){

		},
		success:function(r){
			if(r.state == 1){
				var d = dialog({
					content: ''+r.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				setTimeout('window.location.reload()',1000);
			}else{
				var d = dialog({
					content: ''+r.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				setTimeout('window.location.reload()',1000);
			}
		}
	});
	return false;
}



function group_order_by(obj){
	if(obj.find(".jFormComponentName").length > 0){
		var item = obj.find(".jFormComponentName");
		item.each(function(i,v){
			var input = $(v).find('input[type="text"]');
			input.each(function(){
				var field = $(this).attr('data-field');
				var name = $(this).attr('data-name');
				$(this).attr('name',field+'['+i+']['+name+']');
			});
		});
	}
}

if($(".jFormComponentAddInstanceButton").length > 0){
	$(".jFormComponentAddInstanceButton").click(function(){
		var li = $(this).parent('li')
		var p_html = li.find('.jFormComponent').eq(0).clone();
		var remove = $('<input type="button" value="　 Remove" class="jFormComponentRemoveInstanceButton">').click(function(){
			$(this).parent('div').slideUp(300,function(){ $(this).remove();group_order_by(li)});
		});
		p_html.append(remove);
		p_html.find('input[type="text"]').val('');
		p_html.find('.datepick').datepicker({format: 'yyyy-mm-dd'});
		$(this).before(p_html);
		group_order_by(li);
	});
}

var temp_level = 0;
var last_level = 0;
var level = 0;
function zjj_show(id,lastid){
	if(!id) return false;
	var t = $("#controlid_"+lastid);
	var that = $("#controlid_"+id);
	var t_level = t.attr('level');
	var that_level = that.attr('level');
	if(!t_level){
		level++
		t.attr('level',level);
		temp_level = level;
	}
	temp_level = t_level ? t_level : temp_level;
	if(level == temp_level){
		$("li[level='2']").attr('level','').hide();
		$("li[level='3']").attr('level','').hide();
		$("li[level='4']").attr('level','').hide();
		$("li[level='5']").attr('level','').hide();
		$("li[level='6']").attr('level','').hide();
	}else if(temp_level == 2){
		$("li[level='3']").attr('level','').hide();
		$("li[level='4']").attr('level','').hide();
		$("li[level='5']").attr('level','').hide();
		$("li[level='6']").attr('level','').hide();
	}else if(temp_level == 3){
		$("li[level='4']").attr('level','').hide();
		$("li[level='5']").attr('level','').hide();
		$("li[level='6']").attr('level','').hide();
	}else if(temp_level == 4){
		$("li[level='5']").attr('level','').hide();
		$("li[level='6']").attr('level','').hide();
	}else if(temp_level == 5){
		$("li[level='6']").attr('level','').hide();
	}

	$("#controlid_"+id).show();
	$("#controlid_"+id).attr('level',parseInt(temp_level)+1);

}
 $(window.document).scroll(function () {
	var scrolltop = $(document).scrollTop();
    var form_box = $(".appInfo");
   	var isq = [];
   	var last = '';
   	form_box.each(function(i,v){
   		var t = $(this).offset().top-120;
   		if(scrolltop > t){
   			$(".appNav a").removeClass('appNavAc');
   			$(".appNav a").eq(i).addClass('appNavAc');
   		}
   	});
});
$(".appNav").sticky({bottomSpacing:415});
$(".appNav a").each(function(i,v){
	$(this).click(function(){
		var form_box = $(".appInfo");
		$.scrollTo( $(form_box).eq(i).offset().top,600 );
		$(".appNav a").removeClass('appNavAc');
		$(this).addClass('appNavAc');
	});
});
$(function(){
if($('.datepick').length > 0){
	$('.datepick').datepicker({format: 'yyyy-mm-dd',autoclose: true});
}
});

</script>

<div class="user-bottom min">
  <p><em class="Arial"><?=lang('footer_ba')?></em></p>
  <p><em class="Arial"><?=lang('footer_copy')?></em></p>
</div>

<!---->
</body>
</html>
