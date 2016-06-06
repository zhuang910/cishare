<?php $this->load->view('master/enrollment/mail/mail_header')?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #e4e4e4;font-size:13px;color:#666;">
  <tr>
    <td><table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="120" colspan="2">
          <table width="550" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-size:18px;height:50px;"><strong>Offer Delivered</strong></td>
              </tr>
              <tr>
                <td height="40" style="line-height:25px; font-size:14px; color:#444;">Hi, <?=$val_arr['student_name']?></td>
              </tr>
              <tr>
                <td style="line-height:25px; font-size:14px; color:#444;">We are happy to inform you that your admission  package for <span style='color:red;'><?=$val_arr['program_name']?></span>
at has been delivered to you. Delivery information is indicated as below,</td>
              </tr>
            </table>
           </td>
        </tr>
        <tr>
          <td colspan="2" style="background:#e8f1fa; padding:20px 0;">
          <table width="510" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="510" style="padding:20px 0px;line-height:25px; font-size:14px; color:#444;">
                <ul>
                  <?php if(!empty($val_arr['delivery_method'])){?>
                  <li><strong>Type of Shipment</strong>:
                    <?php if($val_arr['delivery_method']==1){?>
                    	<span style="color:red;">by Express delivery (DHL)</span>
                    <?php }?>
                    <?php if($val_arr['delivery_method']==2){?>
                    	<span style="color:red;">by Airmail(it will take about 3-4 weeks)</span>
                    <?php }?>
                    <?php if($val_arr['delivery_method']==3){?>
                    	<span style="color:red;">Pick up my admission package by myself</span>
                        <ul>
                        	<li>Address: Room 611, Wenjin International Apartment, No.1 Yard, Zhongguancun East Road, Haidian District, Beijing, 100084, P.R. China</li>
                            <li>Postcode: 100084</li>
                            <li>Tel: 86-10-82865135</li>
                        </ul>
                    <?php }?>
                    <?php if($val_arr['delivery_method']==4){?>
                    	<span style="color:red;">Other delivery method</span>
                    <?php }?>
                  </li>
                  <?php }?>
                  <?php if(!empty($val_arr['delivery_time'])){?>
                  <li><strong>Date</strong>:
                    <?=empty($val_arr['delivery_time'])?'':$val_arr['delivery_time']?>
                  </li>
                  <?php }?>
                  <?php if(!empty($val_arr['delivery_proof']) && $val_arr['delivery_method']==1){?>
                  <li><strong>Tracking Number</strong>:
                    <?=empty($val_arr['delivery_proof'])?'':$val_arr['delivery_proof']?>
                    <?php if($val_arr['delivery_method']==1){?>
                    	(<a href="http://www.cn.dhl.com/en.html">Tracking</a>)
                    <?php }?>
                  </li>
                  <?php }?>
                  <?php if(!empty($val_arr['tip_content'])){?>
                  <li><strong>Rmark:</strong><br />
                    <?=empty($val_arr['tip_content'])?'':$val_arr['tip_content']?>
                  </li>
                  <?php }?>
                </ul>
                </td>
              </tr>

              <tr>
                <td width="510" style="line-height:25px; font-size:14px; color:#444;">When you receive it, please  take it to apply the student visa from your local Chinese embassy. </td>
              </tr>
            </table>
         </td>
        </tr>
       
      </table></td>
  </tr>
</table>
<?php $this->load->view('master/enrollment/mail/mail_footer')?>