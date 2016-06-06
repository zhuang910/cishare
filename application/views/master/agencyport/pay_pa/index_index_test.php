<?php $this->load->view('pay/public/header')?>
<link rel="stylesheet" type="text/css" href="<?=RES?>home/pay/upload.css" media="screen" />
<style type="text/css">
.clear{ clear:both;}
.clearfix{zoom:1;}
.clearfix:after{clear:both; content:"";display:block;height:0;line-height:0;visibility:hidden;}
.float_l{ float:left;}
.float_r{ float:right;}
.width_1024{ width:1024px; margin:0 auto;}
.color_7{ color:#777;}
.bottom{ margin-top:50px; height:90px; background:url(<?=RES?>home/pay/global/bottom_bg.png) repeat-x; clear:both;}
.bot_left{ width:450px; margin-top:19px;}
.bottom .logo_pic{ padding-top:3px; padding-right:10px; border-right:1px solid #c8c8c8;}
.bottom .copyright{ font-size:12px; line-height:18px; padding-left:10px;}
.link ul{ margin-top:20px;}
.link ul li.f{ background:url(<?=RES?>home/pay/global/page_icon.png) no-repeat 0 -454px; padding-left:18px; padding-right:10px; font-size:12px;}
.link ul li.l{ background:url(<?=RES?>home/pay/global/page_icon.png) no-repeat 0 -507px; padding-left:18px; padding-right:10px; font-size:12px;}
</style>
<script type="text/javascript">
    $(function(){
    var $menu =$('.BuyMenu li');
    $menu.click(function(){
        $(this).addClass('BuySelect')         
               .siblings().removeClass('BuySelect');  
        var index =  $menu.index(this);  
        $('.BuyBox > div')      
                .eq(index).show()   
                .siblings().hide(); 
    })
})

    function do_pay(){
        var paytype = $("input[name='paytype']:checked").val();
        if(paytype){
            $("#"+paytype).submit();
        }
    }
</script>
<div class="BuyMain">
    <?php if(empty($order_info) || (!empty($order_info) && !in_array($order_info['paystate'], array(1,3)))){?>
    <div class="BuyPlease"><span>Select payment method</span></div>
    <div class="BuyMenu">
        <ul>
            <li class="none BuySelect"><span>Credit or Debit Card</span></li>
            <?php if(!empty($item_type)){?>
            <li><span>Western Union</span></li>
            <li><span>Bank Transfer Overseas</span></li>
            <li><span>Bank Transfer In China</span></li>
            <?php }?>
        </ul>
    </div>
    <div class="BuyBox">
        <div class="yrhide">
            <div class="BuyCost">You need to pay: <span>USD <?=!empty($m_usd)?$m_usd:''?> (RMB <?=!empty($m_rmb)?$m_rmb:''?>)</span></div>
            <div class="BuyBankBox">
                <div class="BuyBank">
                    <label class="checkText" for='paytype_1'>
                        <input type="radio" name="paytype" id='paytype_1' value="paypal" class="FormCheck" />
                        <img src="<?=RES?>home/pay/apply/new_14.jpg" class="BuyBankImg" />
                        <div class='intro'>
                            <div class='img'>
                                <img src='<?=RES?>home/pay/apply/new_24.jpg'>
                                <img src='<?=RES?>home/pay/apply/new_22.jpg'>
                                <img src='<?=RES?>home/pay/apply/paypal_icon.png'>
                            </div>
                            <div class='info'>
                                Payments made through Paypal are safe, easy and convenient
                                <span>
                                If you pay by e-check, it needs 1 or 2 weeks to confirm that the payment has been remitted to the account. We recommend you to pay by credit card.
                                </span>
                            </div>
                        </div>
                    </label>
                </div>
                <?php if(!empty($item_name) && $item_name != 'Transfer'){?>
                <div class="BuyBank">
                    <label class="checkText" for='paytype_2'>
                    <input type="radio" name="paytype" id='paytype_2' value="payease_overseas" class="FormCheck" />
                    <img src="<?=RES?>home/pay/apply/new_20.jpg" class="BuyBankImg" /> 
                    <div class='intro'>
                        <div class='img'>
                            <img src='<?=RES?>home/pay/apply/new_24.jpg'>
                            <img src='<?=RES?>home/pay/apply/new_22.jpg'>
                        </div>
                        <div class='info'>
                            Credit Card - Only Support Visa Or MasterCard [<a href='#'>Help with PayEase</a>]
                        </div>
                    </div>
                    </label>
                </div>
                <div class="BuyBank">
                    <label class="checkText" for='paytype_3'>
                    <input type="radio" name="paytype" id='paytype_3' value="payease_china" class="FormCheck" />
                    <img src="<?=RES?>home/pay/apply/new_20.jpg" class="BuyBankImg" />
                    <div class='intro'>
                        <div class='img'>
                            <img src='<?=RES?>home/pay/apply/new_30.jpg'>
                        </div>
                        <div class='info'>
                            Chinese UnionPay Card Or Chinse Credit Card [<a href='#'>Help with PayEase</a>]
                        </div>
                    </div>
                    </label>
                </div>
                <?php }?>
            </div>
            <a href="javascript:;" class="BuyBtn BuyPayBtn" onclick="do_pay();">Pay Now</a>
            <div class="clear"></div>
        </div>
        <div class="BuyWU ythide">
            <form method="post" action="/master/agencyport/pay/remittance/westernunion" id="form-westernunion" enctype="multipart/form-data">
            <div class="BuyCost">You need to pay: <span>USD <?=!empty($m_usd)?$m_usd:''?> (RMB <?=!empty($m_rmb)?$m_rmb:''?>)</span></div>
            <div class="BuyAcc">
                <h2>Recipient's information:</h2>
                <table class="BuyAccTab">
                    <tr>
                        <th>First name/Given name:</th>
                        <td>HUIJUAN</td>
                    </tr>
                    <tr>
                       <th>Last name/Family name: </th>
                       <td>REN</td>
                    </tr>
                </table>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail">
                            <img src="<?=RES?>home/pay/global/200_200_no_image.gif" /></div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 300px; max-height: 200px; line-height: 20px;"></div>
                        <div>
                            <span class="btn btn-file">
                                <span class="fileupload-new">Upload Receipt</span>
                                <span class="fileupload-exists">Reset</span>
                                <input type="file" id="imgfile" name="imgfile"  />            
                            </span>
                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="BuyXing" style="padding-top:20px;">
                <dl>
                <dt>CUCAS recommends applicants who are inconvient with T/T or PayPal payments use Western Union (especially African applicants).</dt>
                    <dd>Go to your nearest Western Union service provider. Remember to take INVOICE with you.</dd>
                    <dd>Fill in the form provided and show your government-issued identification card or other supporting documents to the service provider.</dd>
                    <dd>The service provider will wire the money for you. </dd>
                    <dd>You will be required to sign a receipt after you have verified all the details printed on the receipt are correct. One of the details printed on the receipt is your Money Transfer Control Number (MTCN).</dd>
                    <dd>Please upload the photocopy of your receipt with MTCN in time to make sure CUCAS confirm your payment.</dd>
                </dl>
            </div>
            <input type="hidden" name="userid" value="<?=!empty($userid)?$userid:0?>">
            <input type="hidden" name="key" value="<?=!empty($order_info['ordernumber'])?$order_info['ordernumber']:''?>">
             <input type="hidden" name="orderid" value="<?=!empty($order_info['id'])?$order_info['id']:''?>">
            <button type="submit" class="btn btn-primary submitbut" style="margin-top:20px;">Submit</button>
            <div class="clear"></div>
            </form>
        </div>
        <div class="BuyWU ythide">
        <form method="post" action="/pay/remittance/abroad" id="form-abroad" enctype="multipart/form-data">
            <div class="BuyCost">You need to pay: <span>USD <?=!empty($m_usd)?$m_usd:''?> (RMB <?=!empty($m_rmb)?$m_rmb:''?>)</span></div>
            <div class="BuyAcc">
                <h2>Account Info</h2>
                <table class="BuyAccTab">
                    <tr>
                        <th>Account Name: </th>
                        <td>Beijing Chiwest Co., Ltd.</td>
                    </tr>
                    <tr>
                        <th>Account No.:</th>
                        <td>11001079900053005134</td>
                    </tr>
                    <tr>
                        <th>Bank Info:</th>
                        <td>China Construction Bank, Beijing Br. Qinghuayuan Subbr.</td>
                    </tr>
                    <tr>
                        <th>Swift Code:</th>
                        <td>PCBCCNBJBJX</td>
                    </tr>
                    <tr>
                        <th>Intermediate Bank Info:</th>
                        <td>Bank of America, New York</td>
                    </tr>
                    <tr>
                        <th>Swift Code:</th>
                        <td>BOFAUS3N</td>
                    </tr>
                    <tr>
                        <th>Fedwire:</th>
                        <td>FW 026009593</td>
                    </tr>
                </table>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail">
                            <img src="<?=RES?>home/pay/global/200_200_no_image.gif" /></div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 300px; max-height: 200px; line-height: 20px;"></div>
                        <div>
                            <span class="btn btn-file">
                                <span class="fileupload-new">Upload Receipt</span>
                                <span class="fileupload-exists">Reset</span>
                                <input type="file" id="imgfile" name="imgfile"  />            
                            </span>
                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">remove</a>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="BuyXing" style="padding-top:20px;">
                <dl>
                <dt>Applicants overseas can use bank transfer to pay the application fee in US Dollars.</dt>
                    <dd>Please take your invoice when you go to the bank. Don't forget to leave your Application ID when you wire the money.</dd>
                    <dd>Please upload the photocopy of your bank receipt to make sure CUCAS could confirm your payment. It usually takes 2-3 weeks for the banking system to confirm an overseas payment. Please wait patiently.</dd>
                </dl>
            </div>
            <input type="hidden" name="key" value="<?=!empty($order_info['ordernumber'])?$order_info['ordernumber']:''?>">
             <input type="hidden" name="orderid" value="<?=!empty($order_info['id'])?$order_info['id']:''?>">
            <button type="submit" class="btn btn-primary submitbut" style="margin-top:20px;">Submit</button>
            <div class="clear"></div>
        </form>
        </div>
        <div class="BuyWU ythide">
        <form method="post" action="/pay/remittance/internal" id="form-internal" enctype="multipart/form-data">
            <div class="BuyCost">You need to pay: <span>USD <?=!empty($m_usd)?$m_usd:''?> (RMB <?=!empty($m_rmb)?$m_rmb:''?>)</span></div>
            <div class="BuyAcc">
                <h2>银行信息(BANK DETAILS)</h2>
                <table class="BuyAccTab">
                    <tr>
                        <th>账户名(Account Name)：</th>
                        <td>北京世华易网教育科技有限公司</td>
                    </tr>
                    <tr>
                        <th>账号(Accout Number)：</th>
                        <td>11001079900053005134</td>
                    </tr>
                    <tr>
                        <th>开户行(Bank)：</th>
                        <td>中国建设银行北京清华园支行</td>
                    </tr>
                </table>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail">
                            <img src="<?=RES?>home/pay/global/200_200_no_image.gif" /></div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 300px; max-height: 200px; line-height: 20px;"></div>
                        <div>
                            <span class="btn btn-file">
                                <span class="fileupload-new">Upload Receipt</span>
                                <span class="fileupload-exists">Reset</span>
                                <input type="file" id="imgfile" name="imgfile"  />            
                            </span>
                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="BuyXing" style="padding-top:20px;">
                <dl>
                <dt>Applicants in China can use a direct bank transfer to pay the application fee in RMB.</dt>
                    <dd>If you are in China or you have a friend in China, you could pay your application fee in RMB through China's bank. Please take your invoice below when you or your friend goes to the bank. </dd>
                    <dd>Please upload the photocopy of your bank receipt to make sure CUCAS  could confirm your payment. It usually takes 2-3 working days for the banking system to confirm the payment. </dd>
                </dl>
            </div>
            <input type="hidden" name="key" value="<?=!empty($order_info['ordernumber'])?$order_info['ordernumber']:''?>">
            <input type="hidden" name="orderid" value="<?=!empty($order_info['id'])?$order_info['id']:''?>">
            <button type="submit" class="btn btn-primary submitbut" style="margin-top:20px;">Submit</button>
            <div class="clear"></div>
        </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="alert alert-success">
        <strong>Payment Confirmed</strong>
        <ul>
          <li><strong>Amount:</strong> USD <?=!empty($m_usd)?$m_usd:''?> (RMB <?=!empty($m_rmb)?$m_rmb:''?>)</li>
          <li><strong>Payment method:</strong> <?php if(!empty($credentials)){?><?=isset($credentials) && isset($way[$credentials['way']]) ? $way[$credentials['way']] : 'Online Payment'?><?php }?></li>
          <li><strong>Payment Status:</strong> <font style='color:red;'><?=$flag[$order_info['paystate']]?></font>
          <span>The next step is to submit your application online when verification succeeded.
          Please wait for 1-2 working days for CUCAS to verify your payment receipt. (An e-mail contains verification result will be sent to you.) Then you can access your application and click on the 'Submit' button to send your application to the university online.
          </span></li>
        </ul>
        <script type="text/javascript">
			var url = '/<?=$puri?>/student/student/tuition';
                $(function(){
                    setTimeout("location.href='/<?=$puri?>/student/student/tuition'",10000)
                });
        </script>
    </div>
    <?php }?>
</div>

<!-- paypal -->
<!-- <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" id="paypal">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="34UYQBQ6D2E6J">
    <input type="hidden" name="lc" value="C2">

    <input type="hidden" name="cn" value="Add special instructions to the seller">
    <input type="hidden" name="no_shipping" value="2">
    <input type="hidden" name="item_name" value="Application fee for {$pageApp.programName}">
    <input type="hidden" name="item_number" value="{$pageApp.appNumber}-1-{$pageApp.app_id}">
    <input type="hidden" name="amount" value="{$TotalFee}">
    <input type="hidden" name="currency_code" value="USD">
    <INPUT TYPE="hidden" name="charset" value="utf-8">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
    <input type="image" src="images/btn_29.jpg" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">

    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form> -->

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="paypal">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="130506866-bw-school@qq.com">
    <input type="hidden" name="lc" value="C2">

    <input type="hidden" name="cn" value="Add special instructions to the seller">
    <input type="hidden" name="no_shipping" value="2">
    <input type="hidden" name="item_name" value="<?=$item_name?>">
    <input type="hidden" name="item_number" value="<?=$order_info['ordernumber']?>">
    <input type="hidden" name="amount" value="1<?//=!empty($m_usd)?$m_usd:''?>">
    <input type="hidden" name="currency_code" value="USD">
    <INPUT TYPE="hidden" name="charset" value="utf-8">
    <input type="hidden" name="bn" value="/<?=$puri?>/student/apply/make_paymeznt?applyid=<?=cucas_base64_encode($order_info['applyid'])?>">
</form>
<!--
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="34UYQBQ6D2E6J">
    <input type="hidden" name="lc" value="C2">

    <input type="hidden" name="cn" value="Add special instructions to the seller">
    <input type="hidden" name="no_shipping" value="2">
    <input type="hidden" name="item_name" value="<?=$item_name?>">
    <input type="hidden" name="item_number" value="<?=$order_info['ordernumber']?>">
    <input type="hidden" name="amount" value="<?=!empty($m_usd)?$m_usd:''?>">
    <input type="hidden" name="currency_code" value="USD">
    <INPUT TYPE="hidden" name="charset" value="utf-8">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
    <input type="hidden" name="return" value="http://<?=$nowurl?>/student/apply/make_paymeznt?applyid=<?=cucas_base64_encode($order_info['applyid'])?>" />
</form>
-->
<!-- payease -->
<form name=form method=post action="http://pay.beijing.com.cn/prs/e_user_payment.checkit" id="payease_overseas">
<input type="hidden"  name="v_mid"      value="5181">              
<input type="hidden"  name="v_oid"      value="<?=$v_oid?>">               
<input type="hidden"  name="v_rcvname"  value="<?=$v_rcvname;?>">      
<input type="hidden"  name="v_rcvaddr"  value="<?=$v_rcvaddr;?>">       
<input type="hidden"  name="v_rcvtel"   value="<?=$v_rcvtel;?>">        
<input type="hidden"  name="v_rcvpost"  value="<?=$v_rcvpost;?>">   
<input type="hidden"  name="v_amount"   value="<?=!empty($m_usd)?$m_usd:''?>">       
<input type="hidden"  name="v_ymd"      value="<?=$v_ymd;?>">        
<input type="hidden"  name="v_orderstatus" value="<?=$v_orderstatus;?>"> 
<input type="hidden"  name="v_ordername" value="<?=$v_ordername;?>">
<input type="hidden"  name="v_moneytype" value="<?=$v_moneytype;?>">     
<input type="hidden"  name="v_url" value="<?=$v_url;?>">         
<input type="hidden"  name="v_md5info" value="<?=$v_md5info;?>">
</form>

<!-- payease RMB -->
<form name=form method=post action="http://pay.beijing.com.cn/prs/user_payment.checkit" id="payease_china">
    <input type="hidden"  name="v_mid"      value="6970">              
    <input type="hidden"  name="v_oid"      value="<?=$v_oid_1;?>">               
    <input type="hidden"  name="v_rcvname"  value="<?=$v_rcvname_1;?>">      
    <input type="hidden"  name="v_rcvaddr"  value="<?=$v_rcvaddr_1;?>">       
    <input type="hidden"  name="v_rcvtel"   value="<?=$v_rcvtel_1;?>">        
    <input type="hidden"  name="v_rcvpost"  value="<?=$v_rcvpost_1;?>">   
    <input type="hidden"  name="v_amount"   value="<?=!empty($m_rmb)?$m_rmb:''?>">       
    <input type="hidden"  name="v_ymd"      value="<?=$v_ymd_1;?>">        
    <input type="hidden"  name="v_orderstatus" value="<?=$v_orderstatus_1;?>"> 
    <input type="hidden"  name="v_ordername" value="<?=$v_ordername_1;?>">
    <input type="hidden"  name="v_moneytype" value="<?=$v_moneytype_1;?>">     
    <input type="hidden"  name="v_url" value="<?=$v_url_1;?>">         
    <input type="hidden"  name="v_md5info" value="<?=$v_md5info_1;?>">
</form>
<script src="<?=RES?>home/pay/fileupload/bootstrap-fileupload.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#form-westernunion').ajaxForm({
        beforeSend:function(){
		 d = dialog({
			 		id:'cucasartdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
        },
        dataType:'json',
        success:function(re){
			dialog({id:'cucasartdialog'}).close();
            if(re.state == 1){
                window.location.href=re.data;
            }else{
                
				var d = dialog({
					content: ''+re.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
            }
        }
    });
    $('#form-abroad').ajaxForm({
        beforeSend:function(){
             d = dialog({
			 		id:'cucasartdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
        },
        dataType:'json',
        success:function(re){
            if(re.state == 1){
			dialog({id:'cucasartdialog'}).close();
                window.location.href=re.data;
            }else{
               var d = dialog({
					content: ''+re.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
            }
        }
    });
    $('#form-internal').ajaxForm({
        beforeSend:function(){
              d = dialog({
			 		id:'cucasartdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
        },
        dataType:'json',
        success:function(re){
		dialog({id:'cucasartdialog'}).close();
            if(re.state == 1){
                window.location.href=re.data;
            }else{
                var d = dialog({
					content: ''+re.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
            }
        }
    });
});

</script>
<?//php $this->load->view('public/footer.php')?>
