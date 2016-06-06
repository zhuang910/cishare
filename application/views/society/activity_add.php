<?php $this->load->view('society/headermy.php')?>
<?php $this->load->view('public/js_My97DatePicker')?>

<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl">
      <?=lang('activity_base')?>
      </span><em class="title_btn fr"><a href="/<?=$puri?>/society/activity/launch">
      <?=lang('activity_return')?>
      </a></em> <em class="title_btn fr"><a href="/<?=$puri?>/society/activity/add">
      <?=lang('add_activity')?>
      </a></em></h1>
    <div class="tab pt30">
      <form class="form-signin" role="form"  id="myform" name="myform" action="/<?=$puri?>/society/activity/save" method="post">
        <ul class="xinxi">
          <li class="mg_b_20"> <span>
            <?=lang('activity_ctitle')?>
            :</span>
            <input class="tongyong width_322" type="text"  id="ctitle" name="ctitle" value=""  validate="required:true" >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_etitle')?>
            :</span>
            <input class="tongyong width_322" type="text" id="etitle" name="etitle"  value="" validate="required:true" >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_starttime')?>
            :</span>
            <input class="tongyong width_322" type="text"  id="starttime" name="starttime" value="" class="Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" validate="required:true"  >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_endtime')?>
            :</span>
            <input class="tongyong width_322" type="text" id="endtime" name="endtime" value="" class="Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:'#F{$dp.$D(\'starttime\')}'})" validate="required:true" >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_isapply')?>
            :</span>
            <input type="radio" class="radio2" id="isapply" name="isapply" value="1" checked>
            <?=lang('activity_isapply_yes')?>
            <input type="radio" class="radio2" id="isapply" name="isapply" value="0" >
            <?=lang('activity_isapply_no')?>
            <div class="sign-up-right"></div>
          </li>
          <li class="btn_margin clearfix">
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
				window.location.href='/<?=$puri?>/society/activity/launch';
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
<?php $this->load->view('society/footer.php')?>
