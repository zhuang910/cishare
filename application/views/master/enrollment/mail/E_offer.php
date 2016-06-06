<?php $this->load->view('master/enrollment/mail/mail_header')?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #e4e4e4;font-size:13px;color:#666;">
  <tr>
    <td><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="120" colspan="2">
          <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-size:18px;height:50px;"><span style="font-size:18px;"><strong>E-offer</strong></span><strong> Delivered</strong></td>
              </tr>
              <tr>
                <td height="40" style="line-height:25px; font-size:14px; color:#444;">Hi, <?=$val_arr['student_name']?></td>
              </tr>
              <tr>
                <td style="line-height:25px; font-size:14px; color:#444;">Please find the attached e-offer for your  application to <span style='color:red;'><?=$val_arr['program_name']?></span>
at
<span style='color:red;'><?=$val_arr['school_name']?></span> .</td>
              </tr>
            </table>
           </td>
        </tr>
       
       
      </table></td>
  </tr>
</table>
<?php $this->load->view('master/enrollment/mail/mail_footer')?>
