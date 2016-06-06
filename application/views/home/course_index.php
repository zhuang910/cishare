<?php $this->load->view('student/headermy.php')?>
<link rel="stylesheet" href="<?=RES?>home/css/chosen.css">
<script src="<?=RES?>home/js/chosen.jquery.min.js"></script>
<div class="width1024">
  <div class="wap_box p30">
    <h1><?=lang('choose_course')?></h1>
	<form method="get" name="myform" id="myform" action="/<?=$puri?>/course">
    <div class="radio">
      <dl class="clearfix">
        <dt><?=lang('choose_degree')?>:</dt>
       <?php if(!empty($degree_info)):?>
       	<div class="f_r radio_degree">
          <dd>
		<?php foreach($degree_info as $k=>$v):?>
       
          <input name="degree" <?=!empty($v['id']) && $v['id'] == $_GET['degree']?'checked':''?> type="radio" value="<?=$v['id']?>" class="radio2" id="degree<?=$v['id']?>" onclick="search_forms()">
           <span><label for="degree<?=$v['id']?>"><?=$puri=='en'?$v['entitle']:$v['title']?></label></span>
		 
      	<?php endforeach;?>
		
		</dd>
      	</div>
      <?php endif;?>
      </dl>
    </div>
    <div class="search">
      <dl class="clearfix">
        <dt><?=lang('search_course')?>:</dt>
        <dd><span class="input_s fl">
         
		
		<select data-placeholder="Your Favorite Type of Bear" style="width:350px;" class="chosen-select" tabindex="7" name="searchname">
            <option value=""><?=lang('search_course')?></option>
				<?php if($course_name){?>
					<?php foreach($course_name as $k => $v){?>
						<option value="<?=$k?>" <?=!empty($_GET['searchname']) && $_GET['searchname'] == $k?'selected':''?>><?=$v?></option>
					<?php }?>
				
				<?php }?>
          </select>
		  
          </span><span class="btn_s fl" onclick="search_form()" style="height:25px;margin-top:8px;margin-left:0px;width:31px;"></span></dd>
      </dl>
    </div>
	</form>
    <div class="list">
      <dl>
        <dt class="clearfix"><span  style="width:300px; "  class="b1"><?=lang('name')?></span> <span style="width:70px; margin-left:10px;" class="b2"><?=lang('opening')?></span> <span style="width:70px" class="b3"><?=lang('course_schooling')?></span> <span style="width:70px;" class="b4"><?=lang('deadline')?></span> <span style="width:70px;" class="b5"><?=lang('language')?></span> <span class="b6"><?=lang('tuition')?></span></dt>
		<?php 
			$lang = array(
				1 => 'Chinese',
				2 => 'English',
			);
			if(!empty($course)){
				foreach($course as $k => $v){
		?>
		<dd style="bakground:none;height:auto; <?=$k != 0?'border-top:none;':''?>" class="clearfix">
         
          <span style="width:300px;" class="b1"><?php if($puri=='en'){?><?=!empty($v['englishname'])?$v['englishname']:'--'?><?php }else{?><?=!empty($v['name'])?$v['name']:'--'?><?php }?></span> <span style="width:70px;margin-left:10px;" class="b2"><?=!empty($v['opentime'])?date('Y-m-d',$v['opentime']):'--'?></span> <span style="width:70px" class="b3">
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
		?></span> <span style="width:70px;" class="b4"><?=!empty($v['endtime'])?date('Y-m-d',$v['endtime']):'--'?></span> <span style="width:70px;" class="b5"><?=!empty($v['language'])?$lang[$v['language']]:'--'?></span> <span class="b6"><?=!empty($v['tuition'])?'RMB '.$v['tuition']:'--'?></span>
		<a href="javascript:;" onclick="apply_now(<?=$v['id']?>)"><span>
		<?=lang('online_apply')?>
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
	function search_forms(){
		$('.chosen-select').remove();
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
		url:'/<?=$puri?>/course/is_course_login?courseid='+id,
		success:function(r){
			dialog({id:'cucasdialog'}).close();
			if (r.state == 1) {
				/*直接跳转到申请页面*/
				window.location.href=r.data;
			}else if(r.state == 2){
				/*登录了 但是过期了 弹出过期的页面
				art.dialog({
							content:r.data,
							drag: false,
							resize: false,
							lock: true,
							opacity: 0.2,
							fixed: true
						});
				*/	
					
				var d = dialog({
						
						content: '<?=lang('overdue')?>'
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 4000);
					//window.location.reload();
			}else{
				/*没有登录 弹出登录窗口
				var p = art.dialog.open('/student/login/ajax_login?courseid='+id,{
							id:'plogin',
							drag: false,
							resize: false,
							lock: true,
							opacity: 0.2,
							fixed: true
						});
				*/		
				var d = dialog({
						content:r.data,
						padding:0
				});
				d.showModal();
			}
		},
		dataType:'json'

	});
	}
</script>
<?php $this->load->view('student/footer.php')?>