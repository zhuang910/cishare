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

  <li><a href="/admin/print/printsetting">打印设置</a></li>
  <li><a href="javascript:history.back();">模板设置</a></li>
  <li>{$r}</li>
</ul>
EOD;
?>    
<?php $this->load->view('admin/public/header',array(
  'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>admin/js/jquery.validate.min.js"></script>
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
<!-- /section:settings.box -->
<div class="page-header">
  <h1>
   打印
  </h1>
</div><!-- /.page-header -->
 <link rel="stylesheet" href="<?=RES?>admin/js/lodop/PrintSample10.css" />
<object id="LODOP1" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
  <embed id="LODOP_EM1" TYPE="application/x-print-lodop" width=0 height=0 PLUGINSPAGE="install_lodop32.exe"></embed>
</object> 
<script src="<?=RES?>admin/js/lodop/LodopFuncs.js"></script>   

<div class="row">
  <div class="col-xs-12">
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
    </div>
  </div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/admin/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>admin/js/ace-extra.min.js"></script>
<script src="<?=RES?>/admin/js/ace-elements.min.js"></script>
<script src="<?=RES?>/admin/js/ace.min.js"></script>
<!-- script -->
<script language="javascript" type="text/javascript"> 
  var LODOP; //声明为全局变量 

  $(document).ready(function(){
    CreatePage();
    var width=<?=$info['width']?>;
    var height=<?=$info['height']?>;
    LODOP.PRINT_INITA("0mm","0mm","<?=$info['width']?>mm","<?=$info['height']?>mm","打印控件功能演示_Lodop功能_在线编辑获得程序代码");
    <?=$info["lodop_content"]?>
    // LODOP.SET_SHOW_MODE("HIDE_ABUTTIN_SETUP",1);//隐藏应用按钮
    // LODOP.SET_SHOW_MODE("HIDE_RBUTTIN_SETUP",1);//隐藏复原按钮
    // LODOP.SET_SHOW_MODE("HIDE_VBUTTIN_SETUP",1);//隐藏预览按钮
    // LODOP.SET_SHOW_MODE("HIDE_PBUTTIN_SETUP",1);//隐藏打印按钮  
    LODOP.SET_SHOW_MODE("DESIGN_IN_BROWSE",1);
    LODOP.SET_SHOW_MODE("SETUP_ENABLESS","11111111000000");//隐藏关闭(叉)按钮
    LODOP.SET_SHOW_MODE("HIDE_GROUND_LOCK",true);//隐藏纸钉按钮
    // LODOP.SET_SHOW_MODE("SETUP_ENABLESS","strCheckSS0");
    LODOP.SET_SHOW_MODE("SETUP_IN_BROWSE",1);
    // LODOP.PRINT_SETUP();//插入的文本框不能编辑  
    LODOP.PRINT_DESIGN(); 
    <?php foreach($return_data as $k=>$v){?>
        LODOP.SET_PRINT_STYLEA("<?=$k?>",'Content',"<?=$v?>");
    <?php }?>
  })

  function CreatePage(){
    LODOP=getLodop(document.getElementById('LODOP2'),document.getElementById('LODOP_EM2')); 
  };  

</script> 
<!-- end script -->
<?php $this->load->view('admin/public/footer');?>