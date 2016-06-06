<?php $this->load->view('student/headermy.php')?>
<div class="width783">
  <div class="wap_box2">
  <h1 class="clearfix" style="border-bottom:none;"><em class="title_btn fr"><a href="javascript:;" onclick="history.back()"><?=lang('activity_return')?></a></em></h1>
    <h3><span><?php if($puri == 'cn' && !empty($result['title'])){ echo $result['title'];}?>
	<?php if($puri == 'en' && !empty($result['entitle'])){ echo $result['entitle'];}?>
	</span></h3>
    <div class="font_box"> 
		<div class="CON p30">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <th width="300" align="left" scope="row"><?=lang('scholarship_cost_state')?></th>
          <td align="left">
		  <?php 
					if(!empty($result['cost_state'])){
				?>
				<?php if($result['cost_state'] == 1){?>
					<?=lang('scholarship_cost_state_'.$result['cost_state'])?>
				<?php if($result['cost_cover']){
				$cost_cover = json_decode($result['cost_cover'],true);?>
				(<?php foreach($cost_cover as $zk => $zv){ echo lang('scholarship_cost_cover_'.$zk).' &nbsp;'; } ?>)
				<?=!empty($result['trem_year'])?lang('scholarship_trem_year_'.$result['trem_year']):''?>
					
				<?php }?>
				<?php }else if($result['cost_state'] == 2){?>
				<?=lang('scholarship_cost_state_'.$result['cost_state'])?>
				<?php if(!empty($result['cost_ratio'])){
					echo $result['cost_ratio'].'%';
				?>
					
				<?php }?>
				<?php }else if($result['cost_state'] == 3){?>
				<?=lang('scholarship_cost_state_'.$result['cost_state'])?>
				<?php if(!empty($result['cost_money'])){
					echo $result['cost_money'];
				?>
					
				<?php }?>
				<?php }?>
				<?php }?>
		  
		  </td>
        </tr>
        <tr>
          <th align="left" scope="row"><?=lang('schlorship_info')?></th>
          <td align="left"><?php if($puri == 'en'){?><?=!empty($result['enintro'])?$result['enintro']:''?><?php }else{?><?=!empty($result['intro'])?$result['intro']:''?><?php }?></td>
        </tr>
        <tr>
          <th align="left" scope="row"><?=lang('schlorship_content')?></th>
          <td align="left"><?php if($puri == 'en'){?><?=!empty($result['encondition'])?$result['encondition']:''?><?php }else{?><?=!empty($result['condition'])?$result['condition']:''?><?php }?></td>
        </tr>
		<?php if(!empty($result['files'])){?>
		<tr>
          <th align="center" scope="row" colspan='2'><a href="<?=$result['files']?>"><?=lang('schlorship_files')?></a></th>
          
        </tr>
		<?php }?>
       <tr>
		<!--  <th scope="row" colspan="2" align="center"><a href="/<?=$puri?>/scholarshipgrf/stuscholarship/apply?id=<?=cucas_base64_encode($result['id'])?>"><span>
		<?=lang('apply_1')?>
		</span></a></th> -->
	   </tr>
      </table>
    </div>
	
    </div>
  </div>
</div>
<?php $this->load->view('student/footer.php')?>
