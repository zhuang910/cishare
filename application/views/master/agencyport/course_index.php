<?php $this->load->view('public/css_basic')?>
<?php $this->load->view('public/js_basic')?>
<?php $this->load->view('public/js_artdialog')?>
<?php $this->load->view('public/js_validate')?>
<link href="<?=RES?>home/css/applyonline.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=RES?>home/css/chosen.css">
<script src="<?=RES?>home/js/chosen.jquery.min.js"></script>
<div class="width1024">
  <div class="wap_box p30">
    <h1>选择专业</h1>
	<form method="get" name="myform" id="myform" action="/master/agencyport/student_apply/course_index?userid=<?=$userid?>&s=1">
    <div class="radio">
      <dl class="clearfix">
        <dt>选择学历:</dt>
        <dd>
          <input name="degree" <?=!empty($_GET['degree']) && $_GET['degree'] == 1?'checked':''?> type="radio" value="1" class="radio2" id="degree1" onclick="search_form()">
           <span><label for="degree1">非学历</label></span>
		 </dd>
        <dd>
          <input name="degree" <?=!empty($_GET['degree']) && $_GET['degree'] == 2?'checked':''?> type="radio" value="2" class="radio2" id="degree2" onclick="search_form()">
         <span><label for="degree2">专科</label></span>
		 </dd>
		 <dd>
          <input name="degree" <?=!empty($_GET['degree']) && $_GET['degree'] == 3?'checked':''?> type="radio" value="3" class="radio2" id="degree3" onclick="search_form()">
          <span><label for="degree3">本科</label></span>
		 </dd>
		 <dd>
          <input name="degree" <?=!empty($_GET['degree']) && $_GET['degree'] == 4?'checked':''?> type="radio" value="4" class="radio2" id="degree4" onclick="search_form()">
          <span><label for="degree4">硕士</label></span>
		 </dd>
		 <dd>
          <input name="degree" <?=!empty($_GET['degree']) && $_GET['degree'] == 5?'checked':''?> type="radio" value="5" class="radio2" id="degree5" onclick="search_form()">
         <span><label for="degree5">博士</label></span>
		 </dd>
      </dl>
    </div>
    <div class="search">
      <dl class="clearfix">
        <dt>搜索专业:</dt>
        <dd><span class="input_s fl">
         
		
		<select data-placeholder="Your Favorite Type of Bear" style="width:350px;" class="chosen-select" tabindex="7" name="searchname">
            <option value=""><?=lang('search_course')?></option>
				<?php if($course_name){?>
					<?php foreach($course_name as $k => $v){?>
						<option value="<?=$k?>" <?=!empty($_GET['searchname']) && $_GET['searchname'] == $k?'selected':''?>><?=$v?></option>
					<?php }?>
				
				<?php }?>
          </select>
		  
          </span><span class="btn_s fl" onclick="search_form()" style="height:25px;margin-top:0px;margin-left:0px;width:31px;"></span></dd>
      </dl>
    </div>
	</form>
    <div class="list">
      <dl>
        <dt class="clearfix"><span class="b1"><?=lang('name')?></span> <span class="b2"><?=lang('opening')?></span> <span class="b3"><?=lang('course_schooling')?></span> <span class="b4"><?=lang('deadline')?></span> <span class="b5"><?=lang('language')?></span> <span class="b6"><?=lang('tuition')?></span></dt>
		<?php 
			$lang = array(
				1 => 'Chinese',
				2 => 'English',
			);
			if(!empty($course)){
				foreach($course as $k => $v){
		?>
		<dd class="clearfix" <?=$k != 0?'style="border-top:none;"':''?>>
         
          <span class="b1"><?php if($puri=='en'){?><?=!empty($v['englishname'])?$v['englishname']:'--'?><?php }else{?><?=!empty($v['name'])?$v['name']:'--'?><?php }?></span> <span class="b2"><?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):'--'?></span> <span class="b3">
		  <?php 
			if(!empty($v['schooling']) && !empty($v['xzunit'])){
				if($v['schooling'] > 1 || strstr($v['schooling'] ,'-')){
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']].'s';
				}else{
					echo $v['schooling'].' '.$publics['program_unit'][$v['xzunit']];
				}
			
			}else{
				echo '--';
			}
		?></span> <span class="b4"><?=!empty($v['endtime'])?date('Y-m-d',$v['endtime']):'--'?></span> <span class="b5"><?=!empty($v['language'])?$lang[$v['language']]:'--'?></span> <span class="b6"><?=!empty($v['tuition'])?'RMB '.$v['tuition'].lang('turr'):'--'?></span>
		<a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><span>
		申请
		</span></a>
		</dd>
		<?php }}?>

      </dl>
    </div>
  
  </div>
</div>
<script type="text/javascript">
$('.chosen-select').chosen({allow_single_deselect:true}); 
				//resize the chosen on window resize
				$(window).on('resize.chosen', function() {
					var w = $('.chosen-select').parent().width();
					$('.chosen-select').next().css({'width':w});
				}).trigger('resize.chosen');
			
				$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#type').addClass('tag-input-style');
					 else $('#type').removeClass('tag-input-style');
				});
				
				$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#country').addClass('tag-input-style');
					 else $('#country').removeClass('tag-input-style');
				});
				
				$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#purpose').addClass('tag-input-style');
					 else $('#purpose').removeClass('tag-input-style');
				});
				
				$('#chosen-multiple-style').on('click', function(e){
					var target = $(e.target).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#participant').addClass('tag-input-style');
					 else $('#participant').removeClass('tag-input-style');
				});
</script>
<script type="text/javascript">
	function search_form(){
		$('#myform').submit();
	}
</script>

<script type="text/javascript">
	function apply_now(id){
		$.ajax({
			beforeSend:function(){
				var d = dialog({
						id:'cucasdialog',
						content: '<img src="<?=RES?>home/images/public/loading.gif">'
					});
					d.showModal();
					
			},
		type:'GET',
		url:'/master/agencyport/course/is_course_login?courseid='+id+'&userid='+<?=$userid?>,
		success:function(r){
			dialog({id:'cucasdialog'}).close();
			if (r.state == 1) {
				/*直接跳转到申请页面*/
				window.location.href=r.data;
			}
		},
		dataType:'json'

	});
	}
</script>
