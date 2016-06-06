
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Print</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="public">
<script src='<?=RES?>master/js/jquery.min.js'></script>
	<script src="<?=RES?>master/js/utils.js"></script>
    <script src="<?=RES?>master/js/common.js"></script>

	<script language="JavaScript">
		<!--
		// 这里把JS用到的所有语言都赋值到这里
		var process_request = "正在处理您的请求...";
		var todolist_caption = "记事本";
		var todolist_autosave = "自动保存";
		var todolist_save = "保存";
		var todolist_clear = "清除";
		var todolist_confirm_save = "是否将更改保存到记事本？";
		var todolist_confirm_clear = "是否清空内容？";
		var lang_removeconfirm = "您确定要卸载该配送方式吗？";
		var shipping_area = "设置区域";
		var upload_falid = "错误：文件类型不正确。请上传“%s”类型的文件！";
		var upload_del_falid = "错误：删除失败！";
		var upload_del_confirm = "提示：您确认删除打印单图片吗？";
		var no_select_upload = "错误：您还没有选择打印单图片。请使用“浏览...”按钮选择！";
		var no_select_lable = "操作终止！您未选择任何标签。";
		var no_add_repeat_lable = "操作失败！不允许添加重复标签。";
		var no_select_lable_del = "删除失败！您没有选中任何标签。";
		var recovery_default_suer = "您确认恢复默认吗？恢复默认后将显示安装时的内容。";
		//-->
	</script>
<style type="text/css">
body
{
  background-color: #ffffff;
  padding: 0px;
  margin: 0px;
}

body, td
{
  font-family: Arial, Verdana, sans-serif;
  font-size: 12px;
}

.table_box
{
  border:#77776F 1px solid;
  table-layout: fixed;
}

.table_line
{
  border:#77776F 1px solid;
}

.select_box
{
  margin:-2;
  width:150px;
  background:#FFFFFF;
  line-height:18px;
  border:0px;
  border-style: none;
  font-size:16px;
}

.display_no
{
  display:none;
}

.div_play_aray
{
  border-style:#77776F 1px solid;
  margin:0;
  height:100%;
  width:100%;
  overflow:auto;
}
</style>
</head>
<body>
  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table_box">
  <form action="<?=$zjjp?>printsetting/settemplate" enctype="multipart/form-data" method="post" name="theForm" id="theForm" onsubmit="return validate();">
  <input type="hidden" name="id" value="<?=$id?>">
  <input type="hidden" name="act" value="">
  <input type="hidden" name="shipping" value="">
  <input type="hidden" name="config_lable" value="">
  <input type="hidden" name="print_model" value="2">
  <input type="hidden" name="shipping_name" value="">
    <!--菜单栏 start-->
    <tr>
      <td style="overflow:hidden; background-color:#EFEFDE; padding-left:8px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" height="50">
          <tr>
            <td align="left">
              <select name="lable" id="lable" class="select_box" onchange="javascript:call_flash('lable_add', this);">
                <option value="" selected="selected"></option>
                <?php foreach($fields as $key=>$val):?>
                <option value="<?=$val['fieldsvalue']?>"><?=$val['name']?></option>
                <?php endforeach;?>
                
              </select>
              <input type="button" name="del" id="del" value="删除该标签" onclick="javascript:call_flash('lable_del', this);">
            </td>
            <td id="pic_control_upload"  <?php echo $info['img']?'class="display_no"':''?>>
              <input type="file" name="bg" id="bg"  >
              <input type="button" name="upload" id="upload" value="上传打印图片" onclick="javascript:bg_upload();"  >
              <iframe id="bg_upload_hidden" name="bg_upload_hidden" frameborder="0" scrolling="no" class="display_no"></iframe>
            </td>
            <td id="pic_control_del"  <?php echo $info['img']?'':'class="display_no"'?> >
              <input type="button" name="upload_del" id="upload_del" onclick="javascript:bg_del(<?=$id?>);" value="删除图片">
            </td>
            <td align="right"><input type="button" value="保存设置" onclick="javascript:save();">&nbsp;&nbsp;<input type="button" value="返回" onclick="javascript:history.back();"></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="overflow: hidden;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table_line">
          <tr style="display: none">
            <td colspan="3"></td>
          </tr>
        </table>
      </td>
    </tr>
    <!--菜单栏 end-->

    <div id='div1'>
    <!--编辑区 start-->
    <tr>
        <td id="xEditingArea" valign="top" height="2000" width="100%"><div class="div_play_aray"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1190" height="1648" id="test">
      <param name="movie" value="<?=RES?>master/js/print/pint.swf">
      <param name="quality" value="high">
      <param name="menu" value="false">
      <param name="wmode" value="transparent">
      <param name="FlashVars" value="">
      <param name="allowScriptAccess" value="sameDomain"/>
      <embed src="<?=RES?>master/js/print/pint.swf" wmode="transparent" FlashVars="bcastr_config_bg=<?=$info['img']?>&swf_config_lable=<?=$info['config_lable']?>" menu="false" quality="high" width="<?=$width*$info['multiple']?>" height="<?=$height*$info['multiple']?>" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="sameDomain" name="test" swLiveConnect="true"/>
      </object></div></td>
    </tr>
    <!--编辑区 end-->
  
  </div>
  </form>

  </table>
</body>
</html>

<script type="text/javascript">
function dayin(){
document.body.innerHTML=document.getElementById('div1').innerHTML;

  window.print();
}
<!--
var display_yes = (Browser.isIE) ? 'block' : 'table-row-group';

window.onload = function()
{

  //callFromFlash();


}

/**
 * 恢复默认
 */
function recovery_default()
{
  //获取表单对象
  var the_form = this_obj("theForm");
  if (typeof(the_form) == "undefined")
  {
    return false; //程序错误
  }

  if (!confirm(recovery_default_suer))
  {
    return false; //中止执行
  }

  the_form.target = '_parent';
  the_form.act.value = 'recovery_default_template';

  the_form.submit();

  return true;
}

/**
 * 保存
 */
function save()
{
  //获取表单对象
  var the_form = this_obj("theForm");
  if (typeof(the_form) == "undefined")
  {
    return false; //程序错误
  }

  the_form.config_lable.value = call_flash('lable_Location_info', '');

  the_form.target = '_parent';
  the_form.act.value = 'do_edit_print_template';

  the_form.submit();
  
  return true;
}

/**
 * 打印单背景图片删除
 */
function bg_del(id)
{
	//获取表单对象
	var the_form = this_obj("theForm");
	if (typeof(the_form) == "undefined")
	{
		return false; //程序错误
	}

	if (!confirm(upload_del_confirm))
	{
		return false; //中止执行
	}

	$.ajax({
		type:'get',
		url:'<?=$zjjp?>printsetting/delimg?id='+id,
		dataType:'json',
		success:function(r){
			call_flash('bg_delete', '');
		}
	});
}

function bg_del_call_back(result,id)
{
  //==0 成功
  if (result.error == 0)
  {
    window.location.href='<?=$zjjp?>printsetting?id='+id
  }

}

/**
 * 打印单背景图片上传
 */
function bg_uplowad()
{
  //获取表单对象
  var the_form = this_obj("theForm");
  if (typeof(the_form) == "undefined")
  {
    return false; //程序错误
  }

  //判断是否选取了上传文件
  if (the_form.bg.value == '')
  {
    alert('请选择图片');

    return false;
  }

  the_form.target = 'bg_upload_hidden';
  the_form.act.value = 'print_upload';

  the_form.submit();

  location.replace(location.href);

}
/**
 * 打印单背景图片上传
 */
function bg_upload()
{
  //获取表单对象
  var the_form = this_obj("theForm");
  if (typeof(the_form) == "undefined")
  {
    return false; //程序错误
  }

  //判断是否选取了上传文件
  if (the_form.bg.value == '')
  {
    alert('请选择图片');

    return false;
  }

  the_form.target = 'bg_upload_hidden';
  the_form.act.value = 'print_upload';
  
  the_form.submit();

  return true;

}
/**
 * 与模板Flash编辑器通信
 */
function call_flash(type, currt_obj)
{
  //获取flash对象
  var obj = this_obj("test");

  //执行操作
  switch (type)
  {
    case 'bg_delete': //删除打印单背景图片

      var result_del = obj.bg_delete();

      //执行成功 修改页面上传窗口为显示 生效
      if (result_del)
      {
        document.getElementById('pic_control_upload').style.display = display_yes;
        document.getElementById('pic_control_del').style.display = 'none';

        var the_form = this_obj("theForm");
        the_form.bg.disabled = "";
        the_form.bg.value = "";
        the_form.upload.disabled = "";
        the_form.upload_del.disabled = "disabled";
      }

    break;

    case 'bg_add': //添加打印单背景图片

      var result_add = obj.bg_add(currt_obj);

      //执行成功 修改页面上传窗口为隐藏 失效
      if (result_add)
      {
        document.getElementById('pic_control_upload').style.display = 'none';
        document.getElementById('pic_control_del').style.display = display_yes;

        var the_form = this_obj("theForm");
        the_form.bg.disabled = "disabled";
        the_form.upload.disabled = "disabled";
        the_form.upload_del.disabled = "";
      }

    break;

    case 'lable_add': //插入标签

      if (typeof(currt_obj) != 'object')
      {
        return false;
      }

      if (currt_obj.value == '')
      {
        alert(no_select_lable);

        return false;
      }

      var result = obj.lable_add('t_' + currt_obj.value, currt_obj.options[currt_obj.selectedIndex].text, 150, 50, 20, 100, 'b_' + currt_obj.value);
      if (!result)
      {
        alert(no_add_repeat_lable);

        return false;
      }

    break;

    case 'lable_del': //删除标签

      var result_del = obj.lable_del();

      if (result_del)
      {
        //alert("删除成功！");
      }
      else
      {
        alert(no_select_lable_del);
      }

    break;

    case 'lable_Location_info': //获取标签位置信息

      var result_info = obj.lable_Location_info();

      return result_info;

    break;
  }

  return true;

}

/**
 * 获取页面Flash编辑器对象
 */
function this_obj(flash_name)
{
  var _obj;

  if (Browser.isIE)
  {
      _obj = window[flash_name];
  }
  else
  {
      _obj = document[flash_name];
  }

  if (typeof(_obj) == "undefined")
  {
    _obj = document[flash_name];
  }
  
  return _obj;

}
//-->
</script>