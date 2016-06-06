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
          <li style="border-right:none;" <?=empty($uri4) || $uri4 == 'index'?'class="active"':''?>><a href="/<?=$puri?>/scholarshipgrf/stuscholarship"><?=lang('apply_scholarship_1')?></a></li>
          <li <?=!empty($uri4) && $uri4 == 'myscholarship'?'class="active"':''?>><a href="/<?=$puri?>/scholarshipgrf/stuscholarship/myscholarship"><?=lang('apply_scholarship_2')?></a></li>
        </ul>
      </div>
      <div class="Switch_bg">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('schlorship_title')?></th>
            
            <th align="left" scope="col"><?=lang('user_apply_operation')?></th>
          </tr>
		  <?php if(!empty($result)){
			foreach($result as $key => $val){
		  ?>
		   <tr>
            <td align="left"><a href="/<?=$puri?>/scholarshipgrf/stuscholarship/scholarship_detail?id=<?=$val['id']?>"><?php if($puri == 'en'){?><?=!empty($val['entitle'])?$val['entitle']:''?><?php }else{?><?=!empty($val['title'])?$val['title']:''?><?php }?></a></td>
            <td align="left"><a href="/<?=$puri?>/scholarshipgrf/stuscholarship/apply?id=<?=cucas_base64_encode($val['id'])?>"><?=lang('apply_1')?></a><br /></td>
            
          </tr>
		  
		  <?php }}?>
         
         
        </table>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('student/footer.php')?>
