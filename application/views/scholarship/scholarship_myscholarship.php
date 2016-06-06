<?php $this->load->view('student/headermy.php')?>
<?php 
	$uri4 = $this->uri->segment(4);
	
?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('apply_scholarship')?></span></h1>
    <div class="tab pt30">
      <div class="Switch_title">
        <ul class="clearfix">
          <li style="border-right:none;" <?=empty($uri4) || $uri4 == 'index'?'class="active"':''?>><a href="/<?=$puri?>/scholarshipgrf/index?applyid=<?=$applyid?>"><?=lang('apply_scholarship_1')?></a></li>
          <li <?=!empty($uri4) && $uri4 == 'myscholarship'?'class="active"':''?>><a href="/<?=$puri?>/scholarshipgrf/index/myscholarship?applyid=<?=$applyid?>"><?=lang('apply_scholarship_2')?></a></li>
        </ul>
      </div>
      <div class="Switch_bg">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('schlorship_title')?></th>
            <th align="left" scope="col"><?=lang('schlorship_status')?></th>
            
            <th align="left" scope="col"><?=lang('user_apply_operation')?></th>
          </tr>
		  <?php if(!empty($apply_info)){
			foreach($apply_info as $key => $val){
		  ?>
		   <tr>
            <td align="left"><a href="/<?=$puri?>/scholarshipgrf/index/scholarship_detail?id=<?=$val['scholarshipid']?>"><?=!empty($result[$val['scholarshipid']])?$result[$val['scholarshipid']]:''?></a></td>
			<td align="left"><?php if($val['type'] == 2){?><?=lang('schlorship_status_'.$val['state'])?><?php }else{ ?>
			<?=lang('schlorship_status_'.$val['state'])?>
			<?php }?><br /></td>
            <td align="left"><?php if($val['type'] == 2){?>
				<?php if(in_array($val['state'],array(0,2)) ){?>
            <?=!empty($val['isinformation']) && $val['isinformation'] == 1?lang('form_complated'):'<font color=red>'.lang('form_uncomplated').'</font>'?>
            <?php if($val['isinformation'] != 1){?>
            <a href="/<?=$puri?>/scholarshipgrf/fillingoutforms/apply?applyid=<?=cucas_base64_encode($val['id'])?>">
            <?=lang('continue')?>
            </a>
            <?php }?>
            <br />
            <?=!empty($val['isatt']) && $val['isatt'] == 1?lang('material_completed'):'<font color=red>'.lang('material_uncompleted').'</font>'?>
            <?php if($val['isatt'] != 1){?>
            <a href="/<?=$puri?>/scholarshipgrf/apply/upload_materials?applyid=<?=cucas_base64_encode($val['id'])?>">
            <?=lang('continue')?>
            </a>
            <?php }?>
            <br />
          
            <?php }else{?>
			--
			<?php }?>
			
			<?php }else{?>
			--
			<?php }?><br /></td>
            
          </tr>
		  
		  <?php }}?>
         
         
        </table>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('student/footer.php')?>
