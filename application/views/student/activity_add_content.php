<?php $this->load->view('student/headermy.php')?>
<script src="/resource/master/js/bootstrap.min.js"></script>
<script src="/resource/master/js/bootbox.min.js"></script>
<link rel="stylesheet" href="/resource/home/css/sdyinc.css" />
<?php $this->load->view('public/js_My97DatePicker')?>
<?php $this->load->view('public/js_css_kindeditor');?>
<?php $this->load->view('public/js_kindeditor_create')?>
<?php $this->load->view('public/js_upload')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl">
      <?=lang('activity_add_content')?>
      </span><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/launch">
      <?=lang('activity_return')?>
      </a></em><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/add">
      <?=lang('add_activity')?>
      </a></em> </h1>
    <div class="tab pt30">
      <form class="form-signin" role="form"  id="myform" name="myform" action="/<?=$puri?>/student/activity/savec" method="post">
        <ul class="">
          <li class="mg_b_20"> <span>
            <?=lang('activity_ctitle')?>
            :</span>
            <input class="tongyong width_322" type="text"  id="ctitle" name="ctitle" value="<?=!empty($result['ctitle'])?$result['ctitle']:''?>"  validate="required:true" >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_etitle')?>
            :</span>
            <input class="tongyong width_322" type="text" id="etitle" name="etitle"  value="<?=!empty($result['etitle'])?$result['etitle']:''?>" validate="required:true" >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_starttime')?>
            :</span>
            <input class="tongyong width_322" type="text"  id="starttime" name="starttime" value="<?=!empty($result['starttime'])?date('Y-m-d H:i',$result['starttime']):''?>" class="Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" validate="required:true"  >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_endtime')?>
            :</span>
            <input class="tongyong width_322" type="text" id="endtime" name="endtime" value="<?=!empty($result['endtime'])?date('Y-m-d H:i',$result['endtime']):''?>" class="Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:'#F{$dp.$D(\'starttime\')}'})" validate="required:true" >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_isapply')?>
            :</span>
            <input type="radio" class="radio2" id="isapply" name="isapply" value="1" <?=!empty($result['isapply']) && $result['isapply'] == 1?'checked':''?>>
            <?=lang('activity_isapply_yes')?>
            <input type="radio" class="radio2" id="isapply" name="isapply" value="0" <?=empty($result['isapply'])?'checked':''?>>
            <?=lang('activity_isapply_no')?>
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_linkname')?>
            :</span>
            <input class="tongyong width_322" type="text"  id="linkname" name="linkname" value="<?=!empty($content['linkname']) ? $content['linkname'] : ''?>" >
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_linktel')?>
            :</span>
            <input class="tongyong width_322" type="text"  id="linktel" name="linktel" value="<?=!empty($content['linktel']) ? $content['linktel'] : ''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_budgeting')?>
            :</span>
            <input class="tongyong width_322" type="text"  id="budgeting" name="budgeting" value="<?=!empty($content['budgeting']) ? $content['budgeting'] : ''?>">
            <div class="sign-up-right"></div>
          </li>
          <li class="mg_b_20"> <span>
            <?=lang('activity_address')?>
            :</span>
            <input class="tongyong width_322" type="text"  id="address" name="address" value="<?=!empty($content['address']) ? $content['address'] : ''?>" >
            <div class="sign-up-right"></div>
          </li>
        <!--  <li class="mg_b_20"> <span>
            <?=lang('activity_image')?>
            :</span> <a href="javascript:swfupload('image_pic','image','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo)"> <img id="image_pic" width="135" height="113" src="<?=!empty($content['image'])?$content['image']:'/resource/master/images/admin_upload_thumb.png'?>"> </a>
            <div class="sign-up-right"></div>
          </li>-->
          <!-- <li class="mg_b_20"> <span>
            <?//=lang('activity_info')?>
            :</span>
            <textarea name="info" id="info"  style="width: 366px; height: 157px;"><?//=!empty($content['info'])?$content['info']:''?>
</textarea>
            <div class="sign-up-right"></div>
          </li>-->
          <li class="mg_b_20"> <span>
            <?=lang('activity_content')?>
            :</span>
            <div class="clearfix">
              <div style="display:none;" id="content_aid_box"></div>
              <textarea name="content" class='content'  id="content"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($content['content']) ? $content['content'] : ''?>
</textarea>
            </div>
            <div class="sign-up-right"></div>
          </li>
          <li>
            <input type="hidden" name="id" value="<?=!empty($result['id'])?$result['id']:''?>"/>
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
				window.location.href='/<?=$puri?>/student/activity/launch';
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
