<?php 
/*
	$title 邮件标题
	$email  用户名
	$url  链接地址
	$flag  是否显示在关键区域 $flag = 1 显示
	$coursename  课程名称
	$schoolname  学校名称
	$cacahe_routes 路由
*/
?>
<?php  $this->load->view('webemail/webemail_header.php')?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #e4e4e4;font-size:13px;color:#666;">
  <tr>
    <td><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="120" colspan="2">
          <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="font-size:18px;height:50px;"><strong><?=$title?></strong></td>
              </tr>
              <tr>
                <td height="40" style="line-height:25px; font-size:14px; color:#444;">Hi,  <?=$email?></td>
              </tr>
              <tr>
                <td style="line-height:25px; font-size:14px; color:#444;">Your new password has been set.</td>
              </tr>
            </table>
           </td>
        </tr>
	

        <tr>
          <td height="70" colspan="2" valign="bottom" style="line-height:20px; font-size:13px; color:#444;"><strong><em>Should you have any question, please feel free to contact us. </em></strong></td>
        </tr>
        
        <tr>
          <td height="15" colspan="2" valign="bottom"></td>
        </tr>
       
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<?php  $this->load->view('webemail/webemail_footer.php')?>