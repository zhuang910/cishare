<?php $this->load->view('student/headermy.php')?>
<?php $this->load->view('public/js_My97DatePicker')?>

<div class="width_925">
  <h2 class="mt50 mb20"><span>
    <?=lang('personal_info')?>
    </span></h2>
  <div class="center_geren">
    <div class="xiugaimima">
      <form class="form-signin" role="form"  id="myform" name="myform" action="/<?=$puri?>/student/student/do_editinfo" method="post">
        <ul>
          <li class="font22">
            <?=lang('personal_info')?>
          </li>
		   <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['email'])?$userinfo['email']:lang('email')?>"  onfocus="if(this.value=='<?=lang('email')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('email')?>'){this.value='<?=lang('email')?>';}" id="email" name="email" value="<?=!empty($userinfo['email'])?$userinfo['email']:''?>"  validate="required:true,email:true,remote:'/<?=$puri?>/student/student/checkemail'">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['chfirstname'])?$userinfo['chfirstname']:lang('user_chfirstname')?>"  onfocus="if(this.value=='<?=lang('user_chfirstname')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_chfirstname')?>'){this.value='<?=lang('user_chfirstname')?>';}" id="chfirstname" name="chfirstname" value="<?=!empty($userinfo['chfirstname'])?$userinfo['chfirstname']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['chlastname'])?$userinfo['chlastname']:lang('user_chlastname')?>" onfocus="if(this.value=='<?=lang('user_chlastname')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_chlastname')?>'){this.value='<?=lang('user_chlastname')?>';}"   id="chlastname" name="chlastname" value="<?=!empty($userinfo['chlastname'])?$userinfo['chlastname']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['firstname'])?$userinfo['firstname']:lang('user_firstname')?>"  onfocus="if(this.value=='<?=lang('user_firstname')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_firstname')?>'){this.value='<?=lang('user_firstname')?>';}"  id="firstname" name="firstname" value="<?=!empty($userinfo['firstname'])?$userinfo['firstname']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['lastname'])?$userinfo['lastname']:lang('user_lastname')?>" onfocus="if(this.value=='<?=lang('user_lastname')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_lastname')?>'){this.value='<?=lang('user_lastname')?>';}"   id="lastname" name="lastname" value="<?=!empty($userinfo['lastname'])?$userinfo['lastname']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['mobile'])?$userinfo['mobile']:lang('user_mobile')?>"  onfocus="if(this.value=='<?=lang('user_mobile')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_mobile')?>'){this.value='<?=lang('user_mobile')?>';}"  id="mobile" name="mobile" value="<?=!empty($userinfo['mobile'])?$userinfo['mobile']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['tel'])?$userinfo['tel']:lang('user_tel')?>"  onfocus="if(this.value=='<?=lang('user_tel')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_tel')?>'){this.value='<?=lang('user_tel')?>';}"  id="tel" name="tel" value="<?=!empty($userinfo['tel'])?$userinfo['tel']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['passport'])?$userinfo['passport']:lang('user_passport')?>" onfocus="if(this.value=='<?=lang('user_passport')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_passport')?>'){this.value='<?=lang('user_passport')?>';}"   id="passport" name="passport" value="<?=!empty($userinfo['passport'])?$userinfo['passport']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['age'])?$userinfo['age']:lang('user_age')?>"  onfocus="if(this.value=='<?=lang('user_age')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_age')?>'){this.value='<?=lang('user_age')?>';}"  id="age" name="age" value="<?=!empty($userinfo['age'])?$userinfo['age']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input type="radio" class="radio2"     id="sex" name="sex" value="1" <?=!empty($userinfo['sex']) && $userinfo['sex'] == 1?'checked':''?>>
            <?=lang('pickup_sex1')?>
            <input type="radio" class="radio2"   id="sex" name="sex" value="2" <?=!empty($userinfo['sex']) && $userinfo['sex'] == 2?'checked':''?>>
            <?=lang('pickup_sex2')?>
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <select name="nationality" id="nationality" >
              <?php if($nationality){
			foreach($nationality as $k => $v){
			?>
              <option value="<?=$k?>" <?=!empty($userinfo['nationality']) && $userinfo['nationality'] == $k?'selected':''?>>
              <?=$v?>
              </option>
              <?php }}?>
            </select>
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['birthplace'])?$userinfo['birthplace']:lang('user_birthplace')?>"  onfocus="if(this.value=='<?=lang('user_birthplace')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_birthplace')?>'){this.value='<?=lang('user_birthplace')?>';}"  id="birthplace" name="birthplace" value="<?=!empty($userinfo['birthplace'])?$userinfo['birthplace']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['birthday'])?date('Y-m-d',$userinfo['birthday']):lang('user_birthday')?>" onfocus="if(this.value=='<?=lang('user_birthday')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_birthday')?>'){this.value='<?=lang('user_birthday')?>';}"  id="birthday" name="birthday" value="<?=!empty($userinfo['birthday'])?date('Y-m-d',$userinfo['birthday']):''?>" class="Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=!empty($userinfo['religion'])?$userinfo['religion']:lang('user_religion')?>" onfocus="if(this.value=='<?=lang('user_religion')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('user_religion')?>'){this.value='<?=lang('user_religion')?>';}"   id="religion" name="religion" value="<?=!empty($userinfo['religion'])?$userinfo['religion']:''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <input type="radio" class="radio2"     id="marital" name="marital" value="1" <?=!empty($userinfo['marital']) && $userinfo['marital'] == 1?'checked':''?>>
            <?=lang('marital_1')?>
            <input type="radio" class="radio2"   id="marital" name="marital" value="2" <?=!empty($userinfo['marital']) && $userinfo['marital'] == 2?'checked':''?>>
            <?=lang('marital_2')?>
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20">
            <textarea style="font-size:14px;"  onfocus="if(this.value=='<?=lang('user_chfirstname')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('personal_speciality')?>'){this.value='<?=lang('personal_speciality')?>';}" name="speciality"><?=!empty($userinfo['speciality'])?$userinfo['speciality']:''?></textarea>
            <div class="sign-up-right"></div>
          </li>
          <li>
            <input class="login-btn" type="submit" name="" value=" <?=lang('submit')?> "/>
          </li>
        </ul>
      </form>
    </div>
  </div>
</div>
<script>

$(function(){
	$('#myform').ajaxForm({
		beforeSend:function(){
			var d = dialog({
					id:'cucasdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
		},
		success:function(msg){
				dialog({id:'cucasdialog'}).close();
			if(msg.state == 1){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				window.location.reload();
			}else if(msg.state == 0){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			}
		},
		dataType:'json'
	
	});
	
	

});
</script>
<?php $this->load->view('student/footer.php')?>