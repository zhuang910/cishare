<script src="/resource/home/js/jquery.min.js"></script><link rel="stylesheet" href="/resource/home/js/plugins/artdialog/css/ui-dialog.css">
<script src="/resource/home/js/plugins/artdialog/dialog-min.js"></script></script>
<script src="/resource/home/js/plugins/from/jquery.form.js"></script>
<?php $this->load->view('public/js_My97DatePicker')?>
<style>
/* == 错误提示的样式 == */
span.error {background: url("/resource/home/images/public/unchecked.gif") no-repeat scroll 4px center transparent;
color: red;
overflow: hidden;
padding-left: 24px;
height: 25px;
line-height: 25px;
display: block;}
span.success { background: url("/resource/home/images/public/checked.gif") no-repeat scroll 4px center transparent; color: red; overflow: hidden; padding-left: 19px; }
</style><!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<link href="<?=RES?>home/css/global.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" media="screen">

<style>
.clear{ clear:both;}
.clearfix{zoom:1;}
.clearfix:after{clear:both; content:"";display:block;height:0;line-height:0;visibility:hidden;}
.po-login{ width:405px; border:5px solid #eaeaea; border-radius:5px; background-color:#fff; box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
.closed{ width:25px; height:25px; display:block; margin-bottom:15px; float:right; background-color:#eaeaea; box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; }
a.close-img{ width:100%; height:100%; display:block; cursor:pointer; background:url(<?=RES?>home/images/user/icon222.png) 0px -25px no-repeat;box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
a.close-img:hover{background:url(<?=RES?>home/images/user/icon222.png) no-repeat;}
.qiehuan{margin:40px 40px 30px 40px; height:37px; font-size:16px; font-family:"微软雅黑",simsun,Arial,"黑体",Helvetica,sans-serif; color:#000; line-height:37px; border-bottom:2px solid #d3d3d3;}
.qiehuan > ul{ float:left;}
.qiehuan > ul > li{ float:left; height:37px; padding:0px 25px; cursor:pointer;}
li.selected{ border-left:2px solid #d3d3d3; border-right:2px solid #d3d3d3; border-top:2px solid #d3d3d3; border-bottom:2px solid #fff;; border-top-left-radius:5px; border-top-right-radius:5px; background:url(<?=RES?>home/images/user/bg-pop-login.gif) repeat-x;}
.tongyong{height:34px; padding:0px 10px; border:1px solid #d8d8d8; line-height:34px; font-size:12px; background-color:#fff;}
.width_322{width:322px;}
ul,li{list-style:none; font-family: "微软雅黑";}
.mg_b_20 {margin-bottom: 20px;}
input.ft_12 {font-size: 12px;}
.pop-login-form{margin:0px 40px 40px 40px;}
</style>

<div class="">
	<?php
		if(!empty($tiaoji)){
			$url="/$puri/student/accommodation/sub_acc_book_tiaoji";
		}else{
			$url="/$puri/student/accommodation/sub_acc_book";
		}
	?>
	<div class="pop-login-form">
	<div id="acc_info" style="display:block;">	
      <form class="form-signin" role="form"  id="myform" name="myform" action="<?=$url?>" method="post">
        <!--<div class="login-main-tips" id="errormsg" style="display:none;"></div>-->
        <ul>
        <?php if(!empty($student_info)):?>
      	  <li><a href="javascript:;" onclick="show_student(1)"><?=lang('acc_yiding')?></a></li>
    	<?php endif;?>
        <li>
			<table>
				<tr>
					<td width="130px"><?=lang('acc_fangjian')?></td>
					<td width="210px"><a href="/<?=$puri?>/student/accommodation/deatil?builiding=<?=$room_info['id']?>" target="_blank"><?=$puri=='cn'?$room_info['name']:$room_info['enname']?></a>&nbsp;<a href="/<?=$puri?>/student/accommodation/deatil?builiding=<?=$room_info['id']?>" target="_blank"><?=lang('look_detail')?></a></td>
				</tr>
				<tr>
					<td><?=lang('acc_leixing')?></td>
					<td><?=lang('root_type_'.$room_info['campusid'])?></td>
				</tr>
				<tr>
					<td><?=lang('acc_jiage')?></td>
					<td><?=$room_info['prices'].'RMB '.lang('room_danwei')?></td>
				</tr>

			</table>
			</li>
			<?php if(empty($tiaoji)){?>
			<li class="mg_b_20">
			<input id="accstarttime" class="tongyong width_322 valid" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd'})" validate="required:true" name="accstarttime" onblur="if(this.value==''||this.value=='<?=lang('acc_looktime')?>'){this.value='<?=lang('acc_looktime')?>';this.style.color='#333';}" style="color: rgb(51, 51, 51);" onfocus="if(this.value=='<?=lang('acc_looktime')?>'){this.value=''};this.style.color='#333';" value="<?=lang('acc_looktime')?>">
			<div class="sign-up-right"></div>
			</li>
			<li class="mg_b_20">
			<input onchange="jiesuans(this,<?=$room_info['prices']?>)" id="accendtime" class="tongyong width_322 valid" type="text" validate="required:true" name="accendtime" onblur="if(this.value==''||this.value=='<?=lang('acc_rztime')?>'){this.value='<?=lang('acc_rztime')?>';this.style.color='#333';}" style="color: rgb(51, 51, 51);" onfocus="if(this.value=='<?=lang('acc_rztime')?>'){this.value=''};this.style.color='#333';" value="<?=lang('acc_rztime')?>">
			<div class="sign-up-right"></div>
			</li>
			<li class="mg_b_20">
			<input id="jiesuan" onchange="jiesuans(this,<?=$room_info['prices']?>)" class="tongyong width_322 valid" type="text" onblur="if(this.value==''||this.value=='<?=lang('acc_jiesuan')?>'){this.value='<?=lang('acc_jiesuan')?>';this.style.color='#333';}" style="color: rgb(51, 51, 51);" onfocus="if(this.value=='<?=lang('acc_jiesuan')?>'){this.value=''};this.style.color='#333';" value="<?=lang('acc_jiesuan')?>">
			<div class="sign-up-right"></div>
			</li>
			<li class="mg_b_20">
			<font class="setmaer"><?=lang('acc_tj')?>:</font>
			<input id="tj" class="radio2" type="radio" value="1" name="tj" validate="required:true">
			<?=lang('activity_isapply_yes')?>
			<input id="tj" class="radio2" type="radio" value="2" name="tj">
			<?=lang('activity_isapply_no')?>
			<div class="sign-up-right"></div>
			</li>
			<li class="mg_b_20">
			<font class="setmaer"><?=lang('acc_isnewstudent')?>:</font>
			<input id="is_renascence" class="radio2" type="radio" validate="required:true" name="is_renascence" value="1">
			<?=lang('activity_isapply_yes')?>
			<input id="is_renascence" class="radio2" type="radio" name="is_renascence" value="2">
			<?=lang('activity_isapply_no')?>
			<div class="sign-up-right"></div>
			</li>
			<li class="mg_b_20">
			<textarea id="remark" onblur="if(this.value==''||this.value=='<?=lang('acc_remark')?>'){this.value='<?=lang('acc_remark')?>';this.style.color='#333';}" onfocus="if(this.value=='<?=lang('acc_remark')?>'){this.value=''};this.style.color='#333';" style="color: rgb(51, 51, 51); padding: 7px; font-size: 12px; width: 320px;height: 80px;max-width: 320px;max-height: 130px;" name="remark"><?=lang('acc_bz')?></textarea>
			<div class="sign-up-right"></div>
			</li>
			<?php }else{?>
				<input type="hidden" name="acc_id" value="<?=!empty($tiaoji)?$tiaoji:''?>">
			<?php }?>
          <li>
          	<input type="hidden" name="userid" value="<?=!empty($user_info['id'])?$user_info['id']:''?>">
          	<input type="hidden" name="campid" value="<?=!empty($room_info['columnid'])?$room_info['columnid']:''?>">
          	<input type="hidden" name="buildingid" value="<?=!empty($room_info['bulidingid'])?$room_info['bulidingid']:''?>">
          	<input type="hidden" name="floor" value="<?=!empty($room_info['floor'])?$room_info['floor']:''?>">
          	<input type="hidden" name="roomid" value="<?=!empty($room_info['id'])?$room_info['id']:''?>">
          	<input type="hidden" name="registeration_fee" value="<?=!empty($room_info['prices'])?$room_info['prices']:''?>">
            <input class="login-btn" type="submit" id="sub" name="" value=" <?=lang('submit')?> "/ style="border:none;">
          </li>
        </ul>
      </form>
      </div>
      <div id="student_info" style="display:none;">
     	<a href="javascript:;" onclick="show_student(2)"><?=lang('activity_return')?></a>
	      <div style="width:330px; height:400px; overflow:auto; border:0px solid #000000;">
	      	  <ul>
				<?php if(!empty($student_info)):?>
					<?php foreach ($student_info as $k => $v) {?>
				      	  <li>
							<table>
								<tr>
									<td width="130px"><?=lang('lastname')?></td>
									<td width="210px"><?=$v['enname']?></td>
								</tr>
								<tr>
									<td><?=lang('lname')?></td>
									<td><?=$v['chname']?></td>
								</tr>
								<tr>
									<td><?=lang('nationality')?></td>
									<td><?=!empty($v['nationality'])?$nationality[$v['nationality']]:''?></td>
								</tr>
								<tr>
									<td><?=lang('sex')?></td>
									<td><?=!empty($v['sex'])?($v['sex']==1?' Male ':' Female '):''?></td>
								</tr>
								<tr>
									<td><?=lang('religion')?></td>
									<td><?=!empty($v['religion'])?$v['religion']:($puri=='cn'?'无':'Nothing')?></td>
								</tr>
								<tr>
									<td><?=lang('acc_bz')?></td>
									<td><?=!empty($v['remark'])?$v['remark']:''?></td>
								</tr>
							</table>
							<hr />
						  </li>
				  <?php }?>
				<?php else:?>
					<li><?=lang('acc_no_r')?></li>
				<?php endif;?>
		        </ul>
	      </div>
      
     
      </div>
    </div>
</div>
<!--关闭按钮-->
<script type="text/javascript">
	function login_close(){
		window.location.reload();
	}
</script>
<script type="text/javascript">
function jiesuans(th,p){
	if($('#accendtime').val()!=undefined){
		$('#jiesuan').val($('#accendtime').val()*p);
	}else{
		$('#jiesuan').val("<?=lang('acc_jiesuan')?>");
	}
}
</script>

<!--提交表单-->
<script type="text/javascript">

var d = '';
$(function(){
	$('#myform').ajaxForm({
		beforeSend:function(){
            $('#sub').attr({
                disabled:'disabled'
            });
			var d = dialog({
					id:'cucasdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();				
		},
		success:function(msg){
			dialog({id:'cucasdialog'}).close();
			if(msg.state == 1){
				/*var d = dialog({
					content: '注册成功！'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);*/
				 top.location.href="/<?=$puri?>/student/student/accommodation";
			}else if(msg.state == 0){
					 var d = dialog({
						content: msg.info
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 2000);
					field = msg.data.field;

					var input = $('.mg_b_20').find("input[name='"+field+"']");

					input.css({border:"1px solid #FF0000"}).focus().blur(function(){

					  $(this).css({border:""});

					});
			}
		},
		dataType:'json'
	
	});
	
});
function show_student(r){
	if(r==1){
		$('#acc_info').css({
			display: 'none'
		});
		$('#student_info').css({
			display: 'block'
		});
	}else if(r==2){
		$('#acc_info').css({
			display: 'block'
		});
		$('#student_info').css({
			display: 'none'
		});
	}

}
</script>



























