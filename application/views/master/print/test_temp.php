<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑模板':'添加模板';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">基本设置</a>
	</li>

	<li><a href="/master/print/printsetting">打印设置</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		<?=$title_h3?>模板
	</h1>
</div><!-- /.page-header -->
 <link rel="stylesheet" href="/resource/public/lodop/PrintSample10.css" />
<object id="LODOP1" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
	<embed id="LODOP_EM1" TYPE="application/x-print-lodop" width=0 height=0 PLUGINSPAGE="install_lodop32.exe"></embed>
</object> 
<script src="/resource/public/lodop/LodopFuncs.js"></script>		

<div class="row">
	<div class="col-xs-12">
		<div style="float:right;margin-right:20px;">

				<a href="javascript:;" onclick="save(<?=$id?>)" class="btn btn-white btn-info btn-bold" >
				<i class="ace-icon fa fa-floppy-o bigger-120 blue"></i>
				保存
				</a>
				<a href="javascript:history.back();" class="btn btn-white btn-info btn-bold" >
				<i class="ace-icon fa fa-reply icon-only"></i>
				返回
				</a>
			</div>
		<!-- PAGE CONTENT BEGINS -->
		<div style="clear:both; float:none;">
			<!-- #section:plugins/fuelux.wizard.container -->
			
			<!-- <img src="/uploads/admin/201502/01/1422772835940646.jpg" class="nav-user-photo">
			<img src="E:/www/zust/uploads/ptint_template/201410/29/901414564822_temp.jpg"> -->
			<object id="LODOP2" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=1100 height=800> 
			  <param name="Caption" value="内嵌显示区域">
			  <param name="Border" value="1">
			  <param name="Color" value="#C0C0C0">
			  <embed id="LODOP_EM2" TYPE="application/x-print-lodop" width=1100 height=800 PLUGINSPAGE="install_lodop.exe">
			</object> 	
			<form id="fields_form">
			<?php if(!empty($fields_info)){?>
				<table class="table table-striped table-bordered" style="width:1100px;">
					<tr>
					<?php foreach($fields_info as $k=>$v){?>
						<?php
							$che='';
							if(!empty($info['fields'])){
								$id_arr=explode(',', $info['fields']);
								if(in_array($v['fieldsvalue'], $id_arr)){
									$che='checked="checked"';
								}
							}
						?>
						<?php if(($k+1)%5==0){?>
							<td><input type="checkbox" <?=$che?> title="<?=$v['name']?>" onchange="cheange_fields(this)" name="<?=$v['fieldsvalue']?>" value="<?=$v['id']?>">:<?=$v['name']?></td>
						</tr><tr>
						<?php }else{?>
							<td><input type="checkbox" <?=$che?> title="<?=$v['name']?>" onchange="cheange_fields(this)" name="<?=$v['fieldsvalue']?>" value="<?=$v['id']?>">:<?=$v['name']?></td>
						<?php }?>
					<?php }?>
					</tr>
				</table>
			<?php }?>	
			</form>
<!-- 			<br>
			<a href="javascript:;" onclick="javascript:LODOP.ADD_PRINT_TEXTA('a',9,262,175,30,'后加的Text内容，类名为a');">ADD_PRINT_TEXTA</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.ADD_PRINT_LINE(120,33,60,133,0,1);">ADD_PRINT_LINE</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.ADD_PRINT_RECT(141,44,100,60,0,1);">ADD_PRINT_RECT</a>或   
			<a href="javascript:;" onclick="javascript:LODOP.ADD_PRINT_ELLIPSE(235,45,100,60,0,1);">ADD_PRINT_ELLIPSE</a><br>或  
			<a href="javascript:;" onclick="javascript:LODOP.ADD_PRINT_BARCODE(87,258,100,60,'Code39','123456789012');">ADD_PRINT_BARCODE</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.ADD_PRINT_CHART(128,242,497,198,'5','');">ADD_PRINT_CHART</a>或  
			<a href="javascript:;" onclick="javascript:Addhtm();">ADD_PRINT_HTM</a><br>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA(2,'FontSize',15);">SET第2项的字体为15</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA(1,'Top','30mm');">SET第1项的Top为30mm</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA(1,'Left','++5mm');">SET第1项Left偏移++5mm</a>(可连续点击)<br>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA('PRINT_INIT','Top','10mm');">SET整体Top为10mm</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA('PRINT_INIT','Left','++5mm');">SET整体Left偏移++5mm</a><br>或   

			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA(2,'Deleted',true);">删除第2项</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA('Selected','Deleted',true);">删除所选内容项</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA('unSelected','Deleted',true);">删除未选内容项</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA('All','Deleted',true);">删除全部内容项</a><br>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA('First','Deleted',true);">删除第一内容项</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA('Last','Deleted',true);">删除末尾内容项</a>或  
			<a href="javascript:;" onclick="javascript:LODOP.SET_PRINT_STYLEA('a','Deleted',true);">删除类名为“a”的内容项</a><br>或  
			<a href="javascript:;" onclick="javascript:SetBKIMG()">设置背景图</a><br>
			或选项：<input type="checkbox" name="SendMan" value="发货人" onclick="Moditify(this)">发货人

			<br><br>
			<a href="javascript:;" onclick="javascript:getProgram();">1：获得全部程序代码：</a><br>  
			<textarea rows="6" id="S1" cols="107" >返回的结果值</textarea><br>
			<input type="button" value="用这些代码执行打印预览" id="button01" style="display:none"  onclick="prn_Preview()"> 

			<p>2：获得所有打印项的  
			<input type="button" value="有效个数:" onclick="document.getElementById('T1').value=LODOP.GET_VALUE('ItemCount',0)">
			  <input type="text" id="T1" size="20"> 
			<input type="button" value="历史个数:" onclick="document.getElementById('T1HS').value=LODOP.GET_VALUE('ItemCountHS',0)">     
			  <input type="text" id="T1HS" size="20">         
			</p>

			<p>3：获得第<input type="text" id="T2" size="2" value="1">个打印项的  
			<select size="1" id="Select01">
			  <option value="ItemTop">上边距</option>
			  <option value="ItemLeft">左边距</option>
			  <option value="ItemWidth">宽度</option>
			  <option value="ItemHeight">高度</option>
			  <option value="ItemContent">内容</option>
			  <option value="ItemClass">对象类别</option>
			  <option value="ItemClassName">对象类别名</option>
			  <option value="ItemPageType">对象类型</option>
			  <option value="ItemName">对象类名</option>
			  <option value="ItemNameID">对象识别序号</option>
			  <option value="ItemIndex">对象物理序号</option>
			  <option value="ItemFontName">字体名称</option>
			  <option value="ItemFontSize">字体大小</option>
			  <option value="ItemColor">字体颜色</option>
			  <option value="ItemAlign">靠齐方式</option>
			  <option value="Itembold">是否粗体</option>
			  <option value="ItemItalic">是否斜体</option>
			  <option value="ItemUnderline">是否下划线</option>  
			  <option value="ItemSelected">是否被选择</option>  
			  <option value="ItemPenWidth">线条宽度</option>
			  <option value="ItemPenStyle">线条类型</option>
			  <option value="ItemHorient">左右位置</option>
			  <option value="ItemVorient">上下位置</option>
			  <option value="ItemAngle">旋转角度</option>
			  <option value="ItemStretch">图片缩放模式</option>
			  <option value="ItemReadOnly">打印维护内容只读</option>
			  <option value="ItemPreviewOnly">是否仅预览</option>
			  <option value="ItemPageIndex">目标输出页</option>
			  <option value="ItemNumberStartPage">页号起始页</option>
			  <option value="ItemStartNumberValue">页号起始值</option>
			  <option value="ItemLineSpacing">行间距</option>
			  <option value="ItemLetterSpacing">字间距</option>
			  <option value="ItemGroundColor">背景色</option>
			  <option value="ItemShowBarText">显示条码文字</option>
			  <option value="ItemQRCodeVersion">QRCode版本号</option>
			  <option value="ItemTextFrame">边框类型</option>
			  <option value="ItemSpacePatch">文本尾是否补空格</option>
			  <option value="ItemAlignJustify">文本两端是否靠齐</option>
			  <option value="ItemTranscolor">图片透明背景色</option>
			  <option value="ItemTop2Offset">次页上边距偏移</option>
			  <option value="ItemLeft2Offset">次页左边距偏移</option>
			  <option value="ItemTableHeightScope">表格高是否含头脚</option>
			  <option value="ItemLinkedItem">关联对象(类名或识别序号)</option>
			  <option value="ItemHtmWaitMilSecs">HTM下载附加延迟毫秒数</option>
			</select>
			<input type="button" value="值:" onclick="document.getElementById('S2').value=  
			LODOP.GET_VALUE(document.getElementById('Select01').value,document.getElementById('T2').value)"> 


			或当前“已选”对象的这些<input type="button" value="值:" onclick="document.getElementById('S2').value=  
			LODOP.GET_VALUE(document.getElementById('Select01').value,'selected')"> 
			<br><textarea rows="4" id="S2" cols="107" ></textarea>    
			    
			</p>

			<p>4：把第<input type="text" id="T3" size="2" value="1">个打印项的内容  
			<input type="button" value="设置" onclick="LODOP.SET_PRINT_STYLEA(document.getElementById('T3').value,'Content',document.getElementById('S3').value)"> 
			为如下：  
			<br><textarea rows="3" id="S3" cols="107" >重新设置的内容</textarea> 
			</p>
			<p>5：获得所设背景图地址的  
			<input type="button" value="内容:" onclick="document.getElementById('T5').value=LODOP.GET_VALUE('BKIMG_Content',0)">     
			  <input type="text" id="T5" size="73">         
			</p>
			<p>6：获得打印初始化设置的  
			<input type="button" value="整体上边距:" onclick="document.getElementById('T6').value=LODOP.GET_VALUE('printInitTop',0)">     
			  <input type="text" id="T6" size="65">         
			</p>
			<p>7：获得打印初始化设置的  
			<input type="button" value="整体左边距:" onclick="document.getElementById('T7').value=LODOP.GET_VALUE('PrintInitLeft',0)">     
			  <input type="text" id="T7" size="65">         
			</p>
			<p>8：获得打印初始化设置的  
			<input type="button" value="编辑区宽度:" onclick="document.getElementById('T8').value=LODOP.GET_VALUE('PrintInitWidth',0)">     
			  <input type="text" id="T8" size="65">         
			</p>
			<p>9：获得打印初始化设置的  
			<input type="button" value="编辑区高度:" onclick="document.getElementById('T9').value=LODOP.GET_VALUE('PrintInitHeight',0)">     
			  <input type="text" id="T9" size="65">         
			</p>
			<p>10：获得打印初始化设置的 
			<input type="button" value="打印任务名:" onclick="document.getElementById('T10').value=LODOP.GET_VALUE('PrintTaskName',0)">     
			  <input type="text" id="T10" size="64">        
			</p>
			<p>11：获得当前内容被 
			<input type="button" value="打印的次数:" onclick="document.getElementById('T11').value=LODOP.GET_VALUE('PrintedTimes',0)">     
			  <input type="text" id="T11" size="71">        
			</p>
			<p>12：获得所设背景图的 
			<input type="button" value="X坐标(px):" onclick="document.getElementById('T12').value=LODOP.GET_VALUE('BKIMG_LEFT',0)">     
			  <input type="text" id="T12" size="69">        
			</p>
			<p>13：获得所设背景图的 
			<input type="button" value="Y坐标(px):" onclick="document.getElementById('T13').value=LODOP.GET_VALUE('BKIMG_TOP',0)">     
			  <input type="text" id="T13" size="69">        
			</p>
			<p>14：获得所设背景图的 
			<input type="button" value="宽度(px):" onclick="document.getElementById('T14').value=LODOP.GET_VALUE('BKIMG_WIDTH',0)">     
			  <input type="text" id="T14" size="71">        
			</p>
			<p>15：获得所设背景图的 
			<input type="button" value="高度(px):" onclick="document.getElementById('T15').value=LODOP.GET_VALUE('BKIMG_HEIGHT',0)">     
			  <input type="text" id="T15" size="71">        
			</p>
			<p>16：获得编辑界面原点坐标(相对于object)
			<input type="button" value="X坐标(px):" onclick="document.getElementById('T16').value=LODOP.GET_VALUE('DesignInterfaceBaseX',0)">     
			  <input type="text" id="T16" size="41">        
			</p>
			<p>17：获得编辑界面原点坐标(相对于object) 
			<input type="button" value="Y坐标(px):" onclick="document.getElementById('T17').value=LODOP.GET_VALUE('DesignInterfaceBaseY',0)">     
			  <input type="text" id="T17" size="41"><a href="http://www.lodop.net/uploads/file/sample/extsamples/drop_lodop/PrintSample36-01.html" target="_blank">&gt;&gt;原点坐标的实战例子</a>        
			</p> -->
			<!-- /section:plugins/fuelux.wizard.container -->
		</div>
	</div>
</div>
<div id="qwe">
<img  border="0" src="http://static15.photo.sina.com.cn/middle/4fe4ba17t993d651b55ce&690"  
style="z-index: -1; position: absolute; left:100px; top:230px;" />   
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!-- script -->
<script language="javascript" type="text/javascript"> 

	function cheange_fields(th){
		 var is = $(th).is(':checked');
			var value=$(th).attr('name');
			var name = $(th).attr('title');
 			 if(is==true){
 			 	//添加
 			 	LODOP.ADD_PRINT_TEXTA(value,10,10,175,30,name);
 			 }else{
 			 	//删除
 			 	LODOP.SET_PRINT_STYLEA(value,'Deleted',true);
 			 }
	}
	function save(id){
		var c=LODOP.GET_VALUE("ProgramCodes",0);
	   var fields_data=$('#fields_form').serialize();
	   var	data = {'content':c};

		$.ajax({
			url: '/master/print/printsetting/save_template?id='+id,
			type: 'POST',
			dataType: 'json',
			data:data,
		})
		.done(function(r) {
			if(r.state==1){
				if(r.data!=0){
					uploadImage(r.data);
				}
				pub_alert_success('保存成功')
				// window.location.href="/master/print/printsetting/template_manage?id=3";
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}


	var LODOP; //声明为全局变量 

	$(document).ready(function(){
		CreatePage();
		var width=<?=$info['width']?>;
		var height=<?=$info['height']?>;
		var strResult=LODOP. FORMAT("UpperMoney","1567.48");
		var datestrResult=LODOP.FORMAT("TIME:yyyy/mm/dd",new Date());
		LODOP.PRINT_INITA("0mm","0mm","<?=$info['width']?>mm","<?=$info['height']?>mm","打印控件功能演示_Lodop功能_在线编辑获得程序代码");
		<?=$info["lodop_content"]?>
	
		
		LODOP.SET_SHOW_MODE("HIDE_ABUTTIN_SETUP",1);//隐藏应用按钮
		LODOP.SET_SHOW_MODE("HIDE_RBUTTIN_SETUP",1);//隐藏复原按钮
		LODOP.SET_SHOW_MODE("HIDE_VBUTTIN_SETUP",1);//隐藏预览按钮
		LODOP.SET_SHOW_MODE("HIDE_PBUTTIN_SETUP",1);//隐藏打印按钮	
		LODOP.SET_SHOW_MODE("DESIGN_IN_BROWSE",1);
		LODOP.SET_SHOW_MODE("SETUP_ENABLESS","11111111000000");//隐藏关闭(叉)按钮
		LODOP.SET_SHOW_MODE("HIDE_GROUND_LOCK",true);//隐藏纸钉按钮

		LODOP.SET_SHOW_MODE("SETUP_IN_BROWSE",1);
		LODOP.PRINT_SETUP();//插入的文本框不能编辑	
		LODOP.PRINT_DESIGN();	
	})

	function CreatePage(){
		LODOP=getLodop(document.getElementById('LODOP2'),document.getElementById('LODOP_EM2')); 
		LODOP.PRINT_INITA(0,0,760,321,"打印控件功能演示_Lodop功能_在线编辑获得程序代码");
		LODOP.ADD_PRINT_TEXT(10,50,175,30,"先加的内容");
	};	

    function uploadImage(path){

          var filePath=path;
          var imageBuffer=LODOP.FORMAT("FILE:EncodeBase64",filePath);
             $.ajax({
                   url:'/master/print/printsetting/shangchuan',
                    data:'imageBuffer='+encodeURIComponent(imageBuffer)+'&id=<?=$id?>',
                   dataType:'text',
                   type:'post',
                  success:function(data){
                  	

                   }
          }); 
      }
	// function Addhtm() {	
	// 	LODOP.ADD_PRINT_HTM(45,494,288,88,"<table border='1'>\n<tr>\n<td>表格11</td>\n<td>表格12</td>\n<td>表格13</td>\n</tr>\n<tr>\n<td>表格21</td>\n<td>表格22</td>\n<td>表格23</td>\n</tr>\n</table>");
	// };
	// function SetBKIMG() {
	// 	LODOP=getLodop(document.getElementById('LODOP2'),document.getElementById('LODOP_EM2')); 
 //                LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='/uploads/cms/201502/02/F202778885327988.jpg'>");	

	// };
	// function getProgram() {	
	// 	LODOP=getLodop(document.getElementById('LODOP2'),document.getElementById('LODOP_EM2'));
	// 	alert(LODOP.GET_VALUE("ProgramCodes",0)); 	
	// 	document.getElementById('S1').value=LODOP.GET_VALUE("ProgramCodes",0);
	// 	document.getElementById('button01').style.display=""; 	
	// };	
	// function prn_Preview() {		
	// 	LODOP=getLodop(document.getElementById('LODOP1'),document.getElementById('LODOP_EM1')); 
	// 	eval(document.getElementById('S1').value); 
	// 	LODOP.PREVIEW();
	// 	LODOP=getLodop(document.getElementById('LODOP2'),document.getElementById('LODOP_EM2')); 
	// };
	

</script> 
<!-- end script -->
<?php $this->load->view('master/public/footer');?>