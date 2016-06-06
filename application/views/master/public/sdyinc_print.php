<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?=$str?>
<?php if($ishow==false):?>
<div>
<button onclick="dayin()" class="btn btn-info" data-last="Finish">
<i class="ace-icon fa fa-check bigger-110"></i>
打印
</button>
</div>
<?php endif;?>
<?php if($ishow==false):?>
  <script src='<?=RES?>master/js/jquery.min.js'></script>
<script type="text/javascript">
function dayin(){
  $(".tiao").each(function(){
   var top =$(this).offset().top;
   var left =$(this).offset().left;
   $(this).css({"top":+top+"px","left":+left+"px"});
   // $(this).style.top=new_top+'px';
   // $(this).style.left=new_left+'px';
  });
	document.body.innerHTML=document.getElementById('print_bg').innerHTML;
	window.print();
}
// function dayin(){
// 	var str= document.body.innerHTML=document.getElementById('print_bg').innerHTML;
// 	printForm(str);
// }
function selects(id){
	var that=document.getElementById('s'+id);
	that.style.background="red";
}
function unselects(id){
	var that=document.getElementById('s'+id);
	var text=that.value;
	that.innerHTML=text;
	that.style.background="transparent";
}
function printForm(receipts){
  
  jsPrintSetup.setOption('orientation', jsPrintSetup.kPortraitOrientation ); //横向
  
  jsPrintSetup.setOption('printBGColors',0); // 打印背景颜色
  jsPrintSetup.setOption('printBGImages',0); // 打印背景图片
  // 页面外围设置
  jsPrintSetup.setOption('marginTop', 0);
  jsPrintSetup.setOption('marginBottom', 0);
  jsPrintSetup.setOption('marginLeft', 0);
  // 头部 左中右 内容
  if(receipts != undefined)
   jsPrintSetup.setOption('headerStrLeft', '');
  jsPrintSetup.setOption('headerStrCenter', receipts);
  jsPrintSetup.setOption('headerStrRight', '第&P页');
  // 底部 左中右 内容
  jsPrintSetup.setOption('footerStrLeft', '');
  jsPrintSetup.setOption('footerStrCenter', '');
  jsPrintSetup.setOption('footerStrRight', '');
  jsPrintSetup.setOption('printSilent', 1);
  jsPrintSetup.print();
  return receipts;
 }
</script>
<?php endif;?>
</body>
</html>
