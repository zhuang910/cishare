<?php $this->load->view('society/headermy.php')?>
<div class="width783">
  <div class="wap_box2">
  <h1 class="clearfix" style="border-bottom:none;"><em class="title_btn fr"><a href="javascript:;" onclick="history.back()"><?=lang('activity_return')?></a></em></h1>
    <h3><span><?php if($puri == 'cn' && !empty($result['ctitle'])){ echo $result['ctitle'];}?>
	<?php if($puri == 'en' && !empty($result['etitle'])){ echo $result['etitle'];}?>
	</span></h3>
    <div class="font_box"> 
		<div class="CON p30">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <th width="300" align="left" scope="row"><?=lang('activity_starttime')?></th>
          <td align="left"><?=!empty($result['starttime'])?date('Y-m-d H:i',$result['starttime']):''?></td>
        </tr>
        <tr>
          <th align="left" scope="row"><?=lang('activity_endtime')?></th>
          <td align="left"><?=!empty($result['endtime'])?date('Y-m-d H:i',$result['endtime']):''?></td>
        </tr>
        <tr>
          <th align="left" scope="row"><?=lang('activity_isapply')?></th>
          <td align="left"><?php 
			if(!empty($result['isapply']) && $result['isapply'] == 1){
				echo lang('activity_isapply_yes');
			}else{
				echo lang('activity_isapply_no'); 
			}
		  ?></td>
        </tr>
        <tr>
          <th align="left" scope="row"><?=lang('activity_linkname')?></th>
          <td align="left"><?=!empty($content['linkname'])?$content['linkname']:''?></td>
        </tr>
		<tr>
          <th align="left" scope="row"><?=lang('activity_linktel')?></th>
          <td align="left"><?=!empty($content['linktel'])?$content['linktel']:''?></td>
        </tr>
		<tr>
          <th align="left" scope="row"><?=lang('activity_budgeting')?></th>
          <td align="left"><?=!empty($content['budgeting'])?$content['budgeting']:''?></td>
        </tr>
		<tr>
          <th align="left" scope="row"><?=lang('activity_address')?></th>
          <td align="left"><?=!empty($content['address'])?$content['address']:''?></td>
        </tr>
        <tr>
          <th align="left" scope="row"><?=lang('activity_state')?></th>
          <td align="left">
		  <?php 
					//判断活动是否结束
					$flag = 0;
					if($result['ishandend'] == 0){
						echo lang('activity_start_3');
					}else{
						if(time() > $result['endtime']){
							echo '<span style="color:#CFCFCF">'.lang('activity_start_3').'</span>';
						}else if( time() > $result['starttime'] && time() < $result['endtime']){
							echo '<span style="color:#FA8072">'.lang('activity_start_2').'</span>';
						
						}else if(time() < $result['starttime']){
							 echo '<span style="color:#FF3030">'.lang('activity_start_1').'</span>';
							 $flag = 1;
						}
					
					}
				
				?>
		  </td>
        </tr>
	
      </table>
    </div>
	<?=!empty($content['content'])?$content['content']:''?>
    </div>
  </div>
</div>

<?php $this->load->view('society/footer.php')?>
