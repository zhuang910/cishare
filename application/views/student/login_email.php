<style type="text/css">
#idwrap{width:730px;height:240px;border:1px solid #dddddd; border-radius:5px; background-color:#f6f6f6;margin:50px;}

</style>
<div id="idwrap">
	<div class="find-password-title"><?=lang('editemail')?></div>
	<div class="find-password-container">
    <div class="find-password-form setcent">
        <form class="form-signin" role="form" id="myforms" name="myforms" action="/<?=$puri?>/student/login/do_login" method="post">
        <ul>
          <li class="mg_b_20">
            <input class="tongyong width_322" type="text" value="<?=lang('qsryx')?>" onfocus="if(this.value=='<?=lang('qsryx')?>'){this.value=''};" onblur="if(this.value==''||this.value=='<?=lang('qsryx')?>'){this.value='<?=lang('qsryx')?>';}"   id="emails" name="emails" >
          </li>
         <input type="hidden" name="password" value="<?=$password?>">
         <input type="hidden" name="passport" value="<?=$passport?>">
             
          <li class="mg_20">
           <input class="login-btn" type="button" name="findpassword" value="<?=lang('submit')?>" onclick="tj()"/> 
		   </li>
       
        </ul>
      </form>
    </div>
  </div>
</div>
<script>
function tj(){
	var email = $('#emails').val();
		
	if(email == '' || email == '<?=lang('qsryx')?>'){
		var d = dialog({
					content: '<?=lang('email_empty')?>'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 4000);
		return false;
	}else{
		data = $('#myforms').serialize();
		$.ajax({
			type:'POST',
			data:data,
			url:'/<?=$puri?>/student/login/edit_email_login',
			success:function(msg){
				if(msg.state == 1){
				var d = dialog({
						content: ''+msg.info+''
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 2000);
					if(msg.data != ''){
						window.location.href=msg.data;
					}else{
					  window.location.href='/<?=$puri?>/student/index';
					}
				
				
				}else {
					
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
	}
	
	
	
	
}
</script>
