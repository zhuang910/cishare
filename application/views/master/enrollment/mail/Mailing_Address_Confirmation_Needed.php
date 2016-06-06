<?php $this->load->view('master/enrollment/mail/mail_header')?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #e4e4e4;font-size:13px;color:#666;">
  <tr>
    <td><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="120" colspan="2">
          <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-size:18px;height:50px;"><strong>Mailing  Address Confirmation Needed</strong></td>
              </tr>
              <tr>
                <td height="40" style="line-height:25px; font-size:14px; color:#444;">Hi, <?=$val_arr['student_name']?></td>
              </tr>
              <tr>
                <td style="line-height:25px; font-size:14px; color:#444;">Until now, you have not confirmed the mailing  address for the admission package of <span style='color:red;'><?=$val_arr['program_name']?></span>
at
<span style='color:red;'><?=$val_arr['school_name']?></span> .</td>
              </tr>
            </table>
           </td>
        </tr>
        <tr>
          <td colspan="2" style="background:#e8f1fa; padding:20px 0;">
          <table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
             <tr>
                <td width="510" style="line-height:25px; font-size:14px; color:#444;"> <a href="http://<?=$_SERVER['HTTP_HOST']?>/en/student/index/confirm_address?key=<?=$val_arr['link']?>">Please confirm your mailing address here</a></td>
              </tr>
               <tr>
                <td width="510" style="padding:10px 0px;line-height:25px; font-size:14px; color:#444;"><em>If  you still won't confirm your mailing address, CUCAS will deliver the admission  package to the address you registered in CUCAS as below on</em> <?=(!empty($val_arr['time']))?$val_arr['time']:''?></td>
              </tr>
              <tr>
                <td width="510" style="padding:10px 0px;line-height:25px; font-size:14px; color:#444;"><ol>
                  <?php if(!empty($val_arr['recive_name'])){?>
                  <li>Name:
                    <?=(!empty($val_arr['recive_name']))?$val_arr['recive_name']:''?>
                  </li>
                  <?php }?>
                  <?php if(!empty($val_arr['recive_building'])){?>
                  <li>Building:
                  	<?=(!empty($val_arr['recive_building']))?$val_arr['recive_building']:''?>
                  </li>
                  <?php }?>
                  <?php if(!empty($val_arr['recive_street'])){?>
                  <li>Street:
                  	<?=(!empty($val_arr['recive_street']))?$val_arr['recive_street']:''?>
                  </li>
                  <?php }?>
                  <?php if(!empty($val_arr['recive_city'])){?>
                  <li>City:
                  	<?=(!empty($val_arr['recive_city']))?$val_arr['recive_city']:''?>
                  </li>
                  <?php }?>
                  <?php if(!empty($val_arr['recive_country'])){?>
                  <li>Country:
                  	<?=(!empty($val_arr['recive_country']))?$val_arr['recive_country']:''?>
                  </li>
                  <?php }?>
                </ol></td>
              </tr>
              
            </table>
         </td>
        </tr>

      
      </table></td>
  </tr>
</table>
<?php $this->load->view('master/enrollment/mail/mail_footer')?>

