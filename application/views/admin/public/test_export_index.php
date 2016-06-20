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
    <?=$title_h3?>模板
  </h1>
</div><!-- /.page-header -->
 <link rel="stylesheet" href="<?=RES?>admin/js/lodop/PrintSample10.css" />

<script src="<?=RES?>admin/js/lodop/LodopFuncs.js"></script>   

<div class="row">
  <div class="col-xs-12">
    <div style="float:right;margin-right:20px;">

    </div>
   <a href="javascript:SaveAsFile();">另存为</a>Excel件。
   <p>二、直接<a href="javascript:OutToFileOneSheet();">导出数据</a>到文件:<input type="text" id="T1" size="30" value="C:\Test.xls"></p>
   <p>三、<a href="javascript:OutToFileMoreSheet();">导出数据</a>到Excel文件时每页保存到不同Sheet中,并设置页眉页脚等,<br>
同时返回所保存的目标文件名称为：<input type="text" id="T2" size="45" value="">
</p>
<p>四、弹出对话框<a href="javascript:SaveAsEmfFile();">把本页内容导出</a>为EMF图文件，图片大小与当前纸张大小一致。</p>
    <div class="col-xs-12" id="sssss">
      <table class="table table-striped table-bordered table-hover" id="sample-table-1">
        <thead>
          <tr>
            <th class="center">
              <label class="position-relative">
                <input type="checkbox" class="ace">
                <span class="lbl"></span>
              </label>
            </th>
            <th>Domain</th>
            <th>Price</th>
            <th class="hidden-480">Clicks</th>

            <th>
              <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
              Update
            </th>
            <th class="hidden-480">Status</th>

            <th></th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td class="center">
              <label class="position-relative">
                <input type="checkbox" class="ace">
                <span class="lbl"></span>
              </label>
            </td>

            <td>
              <a href="#">ace.com</a>
            </td>
            <td>$45</td>
            <td class="hidden-480">3,330</td>
            <td>Feb 12</td>

            <td class="hidden-480">
              <span class="label label-sm label-warning">Expiring</span>
            </td>

            <td>
              <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-success">
                  <i class="ace-icon fa fa-check bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-info">
                  <i class="ace-icon fa fa-pencil bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-danger">
                  <i class="ace-icon fa fa-trash-o bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-warning">
                  <i class="ace-icon fa fa-flag bigger-120"></i>
                </button>
              </div>

              <div class="hidden-md hidden-lg">
                <div class="inline position-relative">
                  <button data-position="auto" data-toggle="dropdown" class="btn btn-minier btn-primary dropdown-toggle">
                    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                  </button>

                  <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-info" href="#" data-original-title="View">
                        <span class="blue">
                          <i class="ace-icon fa fa-search-plus bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-success" href="#" data-original-title="Edit">
                        <span class="green">
                          <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-error" href="#" data-original-title="Delete">
                        <span class="red">
                          <i class="ace-icon fa fa-trash-o bigger-120"></i>
                        </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td class="center">
              <label class="position-relative">
                <input type="checkbox" class="ace">
                <span class="lbl"></span>
              </label>
            </td>

            <td>
              <a href="#">base.com</a>
            </td>
            <td>$35</td>
            <td class="hidden-480">2,595</td>
            <td>Feb 18</td>

            <td class="hidden-480">
              <span class="label label-sm label-success">Registered</span>
            </td>

            <td>
              <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-success">
                  <i class="ace-icon fa fa-check bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-info">
                  <i class="ace-icon fa fa-pencil bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-danger">
                  <i class="ace-icon fa fa-trash-o bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-warning">
                  <i class="ace-icon fa fa-flag bigger-120"></i>
                </button>
              </div>

              <div class="hidden-md hidden-lg">
                <div class="inline position-relative">
                  <button data-position="auto" data-toggle="dropdown" class="btn btn-minier btn-primary dropdown-toggle">
                    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                  </button>

                  <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-info" href="#" data-original-title="View">
                        <span class="blue">
                          <i class="ace-icon fa fa-search-plus bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-success" href="#" data-original-title="Edit">
                        <span class="green">
                          <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-error" href="#" data-original-title="Delete">
                        <span class="red">
                          <i class="ace-icon fa fa-trash-o bigger-120"></i>
                        </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td class="center">
              <label class="position-relative">
                <input type="checkbox" class="ace">
                <span class="lbl"></span>
              </label>
            </td>

            <td>
              <a href="#">max.com</a>
            </td>
            <td>$60</td>
            <td class="hidden-480">4,400</td>
            <td>Mar 11</td>

            <td class="hidden-480">
              <span class="label label-sm label-warning">Expiring</span>
            </td>

            <td>
              <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-success">
                  <i class="ace-icon fa fa-check bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-info">
                  <i class="ace-icon fa fa-pencil bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-danger">
                  <i class="ace-icon fa fa-trash-o bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-warning">
                  <i class="ace-icon fa fa-flag bigger-120"></i>
                </button>
              </div>

              <div class="hidden-md hidden-lg">
                <div class="inline position-relative">
                  <button data-position="auto" data-toggle="dropdown" class="btn btn-minier btn-primary dropdown-toggle">
                    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                  </button>

                  <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-info" href="#" data-original-title="View">
                        <span class="blue">
                          <i class="ace-icon fa fa-search-plus bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-success" href="#" data-original-title="Edit">
                        <span class="green">
                          <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-error" href="#" data-original-title="Delete">
                        <span class="red">
                          <i class="ace-icon fa fa-trash-o bigger-120"></i>
                        </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td class="center">
              <label class="position-relative">
                <input type="checkbox" class="ace">
                <span class="lbl"></span>
              </label>
            </td>

            <td>
              <a href="#">best.com</a>
            </td>
            <td>$75</td>
            <td class="hidden-480">6,500</td>
            <td>Apr 03</td>

            <td class="hidden-480">
              <span class="label label-sm label-inverse arrowed-in">Flagged</span>
            </td>

            <td>
              <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-success">
                  <i class="ace-icon fa fa-check bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-info">
                  <i class="ace-icon fa fa-pencil bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-danger">
                  <i class="ace-icon fa fa-trash-o bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-warning">
                  <i class="ace-icon fa fa-flag bigger-120"></i>
                </button>
              </div>

              <div class="hidden-md hidden-lg">
                <div class="inline position-relative">
                  <button data-position="auto" data-toggle="dropdown" class="btn btn-minier btn-primary dropdown-toggle">
                    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                  </button>

                  <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-info" href="#" data-original-title="View">
                        <span class="blue">
                          <i class="ace-icon fa fa-search-plus bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-success" href="#" data-original-title="Edit">
                        <span class="green">
                          <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-error" href="#" data-original-title="Delete">
                        <span class="red">
                          <i class="ace-icon fa fa-trash-o bigger-120"></i>
                        </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>

          <tr>
            <td class="center">
              <label class="position-relative">
                <input type="checkbox" class="ace">
                <span class="lbl"></span>
              </label>
            </td>

            <td>
              <a href="#">pro.com</a>
            </td>
            <td>$55</td>
            <td class="hidden-480">4,250</td>
            <td>Jan 21</td>

            <td class="hidden-480">
              <span class="label label-sm label-success">Registered</span>
            </td>

            <td>
              <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-success">
                  <i class="ace-icon fa fa-check bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-info">
                  <i class="ace-icon fa fa-pencil bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-danger">
                  <i class="ace-icon fa fa-trash-o bigger-120"></i>
                </button>

                <button class="btn btn-xs btn-warning">
                  <i class="ace-icon fa fa-flag bigger-120"></i>
                </button>
              </div>

              <div class="hidden-md hidden-lg">
                <div class="inline position-relative">
                  <button data-position="auto" data-toggle="dropdown" class="btn btn-minier btn-primary dropdown-toggle">
                    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                  </button>

                  <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-info" href="#" data-original-title="View">
                        <span class="blue">
                          <i class="ace-icon fa fa-search-plus bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-success" href="#" data-original-title="Edit">
                        <span class="green">
                          <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                        </span>
                      </a>
                    </li>

                    <li>
                      <a title="" data-rel="tooltip" class="tooltip-error" href="#" data-original-title="Delete">
                        <span class="red">
                          <i class="ace-icon fa fa-trash-o bigger-120"></i>
                        </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
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
  function SaveAsFile(){ 
    LODOP=getLodop();   
    LODOP.PRINT_INIT(""); 
    LODOP.ADD_PRINT_TABLE(100,20,500,80,document.documentElement.innerHTML); 
    LODOP.SET_SAVE_MODE("Orientation",2); //Excel文件的页面设置：横向打印   1-纵向,2-横向;
    LODOP.SET_SAVE_MODE("PaperSize",9);  //Excel文件的页面设置：纸张大小   9-对应A4
    LODOP.SET_SAVE_MODE("Zoom",90);       //Excel文件的页面设置：缩放比例
    LODOP.SET_SAVE_MODE("CenterHorizontally",true);//Excel文件的页面设置：页面水平居中
    LODOP.SET_SAVE_MODE("CenterVertically",true); //Excel文件的页面设置：页面垂直居中
//    LODOP.SET_SAVE_MODE("QUICK_SAVE",true);//快速生成（无表格样式,数据量较大时或许用到） 
    LODOP.SAVE_TO_FILE("新文件名.xls"); 
  };   
  function OutToFileOneSheet(){ 
  alert(document.getElementById("sssss").innerHTML);
    
    LODOP=getLodop();   
    LODOP.PRINT_INIT(""); 
    LODOP.ADD_PRINT_TABLE(100,20,500,60,document.getElementById("sssss").innerHTML); 
    LODOP.SET_SAVE_MODE("FILE_PROMPT",false); 
    if (LODOP.SAVE_TO_FILE(document.getElementById("T1").value)) alert("导出成功！");     
  }; 
  function OutToFileMoreSheet(){ 
    LODOP=getLodop();   
    LODOP.PRINT_INIT(""); 
    LODOP.ADD_PRINT_TABLE(100,20,500,60,document.documentElement.innerHTML); 
    LODOP.SET_SAVE_MODE("PAGE_TYPE",2); 
    LODOP.SET_SAVE_MODE("CenterHeader","页眉"); //Excel文件的页面设置
    LODOP.SET_SAVE_MODE("CenterFooter","第&P页"); //Excel文件的页面设置
    LODOP.SET_SAVE_MODE("Caption","我的标题栏");//Excel文件的页面设置          
    LODOP.SET_SAVE_MODE("RETURN_FILE_NAME",1); 
    document.getElementById("T2").value=LODOP.SAVE_TO_FILE("多个Sheet的文件.jpg");    
  };  
  function SaveAsEmfFile(){ 
    alert(document.getElementById("sssss").innerHTMLL);
    LODOP=getLodop();   
    LODOP.PRINT_INIT(""); 
    LODOP.ADD_PRINT_TABLE(100,20,500,60,document.getElementById("sssss").innerHTML);
    LODOP.ADD_PRINT_HTM(0,0,"100%","100%",document.getElementById("sssss").innerHTMLL); 
    LODOP.SET_SAVE_MODE("SAVEAS_IMGFILE_EXENAME",".jpg");
    LODOP.SAVE_TO_FILE("新的矢量图片文件.jpg"); 
  };  
</script>
<!-- end script -->
<?php $this->load->view('admin/public/footer');?>