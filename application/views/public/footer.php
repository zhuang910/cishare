<!--bottom start-->
<div class="bottom-container min">
  <div class="width_1024">
    <?php $this->load->view('public/site_map')?>
        </div>
</div>
<div class="bottom-copyright min">
  <div class="width_1024">
    <div class="f_l">
      <div class="bottom-logo"><img src="<?=RES?>home/images/public/bottom-logo.png" alt="logo"/></div>	
      </div>
		<div class="bottom-word f_l">
			<p><em class="Arial"><?=lang('footer_ba')?></em> </p>
			<p><em class="Arial"><span>24小时技术故障服务电话:15210801350　邮箱:<a href=mailto:zhangjunjie@chiwest.cn>zhangjunjie@chiwest.cn</a>　联系人:张俊杰</span></em> </p>
      </div>
    <div class="f_r friend-link"> <span><a href="/<?=$puri?>/link" target="_blank"><?=lang('nav_84')?></a></span> <span class="friend-link-xian"></span> <span><a href="http://www.cucas.edu.cn" target="_blank"><?=lang('power_by')?></a></span> </div>
    <div class="clear"></div>
  </div>
</div>
<style type="text/css">
#goTopBtn {
    background: url("<?=RES?>home/images/public/top21.png") no-repeat scroll center center #5c5c5c;
    border-radius: 5px;
    bottom: 30px;
    cursor: pointer;
    height: 54px;
    position: fixed;
    right: 10px;
    width: 54px;
    z-index: 999;
}
#goTopBtn:hover {
    background: url("<?=RES?>home/images/public/top21.png") no-repeat scroll center center #4c4c4c;
}
</style>
<!--返回顶部  吖頭-->
<div style="display: none" id="goTopBtn"></div>
<!--bottom end-->
<script type=text/javascript>
/*回到顶部  2014-03-28 吖頭*/
function goTopEx(){
        var obj=document.getElementById("goTopBtn");
        function getScrollTop(){
                return $(window).scrollTop();
            }
        function setScrollTop(value){
                $(window).scrollTop(value);
            }    
        window.onscroll=function(){getScrollTop()>0?obj.style.display="":obj.style.display="none";}
        obj.onclick=function(){
            var goTop=setInterval(scrollMove,10);
            function scrollMove(){
                    setScrollTop(getScrollTop()/1.1);
                    if(getScrollTop()<1)clearInterval(goTop);
                }
        }
    }
goTopEx();
</script>
<script type="text/javascript">
    $(document).ready(function(){
       $(".top-nav").mouseover(function(){
	      $(this).next("span.xian").hide();
		  $(this).prev("span.xian").hide();
	      $(this).css({"background-color":"#fff","border-left":"1px solid #b8b8b8","border-right":"1px solid #b8b8b8","border-bottom":"2px solid #fff"});
          $(this).find("ul").show();
		  $(this).find(".mapxian-1").show();
        });
        $(".top-nav").mouseout(function(){
		  $(this).next("span.xian").show();
		  $(this).prev("span.xian").show();
	      $(this).css({"background-color":"transparent","border":"none"});
          $(this).find("ul").hide();
		  $(this).find(".mapxian-1").hide();
        });
    });
	
	$(document).ready(function(){
       $(".bg-banner-des-word").mouseover(function(){
	      $(this).find(".banner-des-word-link").show();
		  $(this).css({"height":"173px"});
		  $(this).siblings(".bg-banner-des").css({"height":"173px"});
        });
        $(".bg-banner-des-word").mouseout(function(){
		  $(this).find(".banner-des-word-link").hide();
		  $(this).css({"height":"143px"});
		  $(this).siblings(".bg-banner-des").css({"height":"143px"});
        });
    });
	
	$(document).ready(function(){
       $(".student li").mouseover(function(){
	      $(this).find(".student-black").hide();
		  $(this).find(".student-blue").show();
		  $(this).find("div.student-black-describe").css({"bottom":"55px"});
        });
        $(".student li").mouseout(function(){
		  $(this).find(".student-black").show();
		  $(this).find(".student-blue").hide();
		  $(this).find("div.student-black-describe").css({"bottom":"0px"});
        });
    });
	

  <!--搜索-->
  function search_submit(){
    var search = $('#search').val();
   
    if(search == ''){
      var d = dialog({
          id:'cucasdialog',
          content: '<?=lang('search_content')?>'
        });
        d.show(); 

        setTimeout(function(){
          d.close().remove();
        },2000);
        return false;
    }
    
        $('#search_form').submit();
    
    
  }
</script>
</body>
</html>