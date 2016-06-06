<?php $this->load->view('master/enrollment/mail/mail_header')?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #e4e4e4;font-size:13px;color:#666;">
  <tr>
    <td><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="120" colspan="2">
          <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-size:18px;height:50px;"><strong>Pending  Application</strong></td>
              </tr>
              <tr>
                <td height="40" style="line-height:25px; font-size:14px; color:#444;">Hi, <?=$val_arr['student_name']?></td>
              </tr>
              <tr>
                <td style="line-height:25px; font-size:14px; color:#444;">After the detailed verification by CUCAS admission committee,  your application to <span style='color:red;'><?=$val_arr['program_name']?></span>
at
<span style='color:red;'><?=$val_arr['school_name']?></span> is PENDING  now because:</td>
              </tr>
            </table>
           </td>
        </tr>
        <?php if(!empty($val_arr['tip_content'])){?>
        <tr>
          <td colspan="2" style="background:#e8f1fa; padding:20px 0;">
          <table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
              
              <tr>
                <td width="510" style="padding:20px 0px;line-height:25px; font-size:14px; color:#444;"><?=empty($val_arr['tip_content'])?'':nl2br($val_arr['tip_content'])?></td>
              </tr>
              
            </table>
         </td>
        </tr>
        <?php }?>
        
        <tr>
          <td height="15" colspan="2" valign="bottom"></td>
        </tr>
      
      </table></td>
  </tr>
</table>
<?php $this->load->view('master/enrollment/mail/mail_footer')?>

