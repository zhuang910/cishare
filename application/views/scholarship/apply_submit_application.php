<?php $this->load->view('student/headermy');?>
<link href="<?=RES?>home/css/thumbnail.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/select2.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.sticky.js"></script>

<div class="width_925 clearfix">
	<?php $this->load->view('scholarship/apply_coursename')?>
</div>
<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<?php $this->load->view('scholarship/apply_left')?>
	
	</div>
		<div class="applyonline-4-main">
		<div class="applyonline-5-main">
		<h4><?=lang('apply_submit_1')?></h4>
          <div class="mt_10">
            <?=!empty($apply_info['isinformation']) && $apply_info['isinformation'] == 1?lang('form_complated'):'<font color=red class="setblock">'.lang('form_uncomplated').'</font>'?>
			<?php if($apply_info['isinformation'] != 1){?>
             <a  class="seta" href="/<?=$puri?>/scholarshipgrf/fillingoutforms/apply?applyid=<?=cucas_base64_encode($apply_info['id'])?>"> <?=lang('continue')?> </a>
			 <?php }?>
            <br />
            
             <?=!empty($apply_info['isatt']) && $apply_info['isatt'] == 1?lang('material_completed'):'<font color=red class="setblock">'.lang('material_uncompleted').'</font>'?>
          <?php if($apply_info['isatt'] != 1){?>
		  <a class="seta" href="/<?=$puri?>/scholarshipgrf/apply/upload_materials?applyid=<?=cucas_base64_encode($apply_info['id'])?>"> <?=lang('continue')?> </a>
		  <?php }?>
		  <br />
		  
           
          </div>
		
			<!--<p class="p1"><?=lang('apply_check')?></p>-->
		
			<div class="redbtn-middle-dazi">
				<?php if($apply_info['isinformation'] == 1 && $apply_info['isatt'] == 1){?>
					<a href="javascript:;" onclick="save_submit()"><?=lang('submit')?></a>
				<?php }else{?>
						<a href="javascript:;" onclick="save_un()"><?=lang('submit')?></a>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
/*上一步*/
 var applyid = '<?=!empty($apply_info['id'])?cucas_base64_encode($apply_info['id']):''?>';
/*提交信息*/
function save_submit(){
  $.ajax({
    type:'GET',
    url:'/<?=$puri?>/scholarshipgrf/apply/save_submit?applyid='+applyid,
    beforeSend:function(){
		
		var d = dialog({
					id:'cucasartdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
		d.show();
		
    },
    success:function(r){
		dialog({id:'cucasartdialog'}).close();
        if(r.state == 1){
            window.location.href='/<?=$puri?>/scholarshipgrf/index/myscholarship';
        }else{
           var d = dialog({
					content: ''+r.info+''
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

function save_un(){
  var d = dialog({
					id:'cucasartdialog',
					content: '<?=lang('issubmit_title')?>'
				});
		d.show();
		setTimeout(function () {
					d.close().remove();
				}, 2000);
}

</script>
<?php $this->load->view('student/footer.php')?>