<?php $this->load->view('student/headermy.php')?>
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
			if(!empty($result)){
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
				}
				?>
		  </td>
        </tr>
		<?php if(!empty($result)){?>
		<tr>
          <th align="center" scope="row" colspan="2">
		  <?php if(!empty($uid) && $uid != $result['userid']){?>
		   <a href="javascript:;" onclick="activity_collect(<?=$result['id']?>)"><?=lang('activity_collect')?></a>
		   <?php }?>
		  <?php 	
				if(!empty($uid) && $uid != $result['userid']){
					if(!empty($flag) && $flag == 1){
				?>
					/ <a href="javascript:;" onclick="activity_join(<?=$result['id']?>)"><?=lang('activity_join')?></a> 
				<?php }}?>
		  </th>
          
        </tr>
		<?php }?>
      </table>
    </div>
	<?=!empty($content['content'])?$content['content']:''?>
    </div>
  </div>
</div>

<script type="text/javascript">
    function activity_collect(id) {
	
		var d = dialog({
				content: '<?=lang('activity_collect_confirm')?>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					 $.post('/<?=$puri?>/student/activity/activity_collect',{id:id},function(msg){
						  if(msg.state == 1){
							var d = dialog({
								content: ''+msg.info+''
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
								
							}, 5000);
							
						  }else{
							 var d = dialog({
								content: ''+msg.info+''
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
							}, 5000);
						  }
					  },'json');
				},
				cancel: function () {
					
					return true;
				}
			});
			d.show();
			

  }

</script>

<script type="text/javascript">
    function activity_join(id) {
	
		var d = dialog({
				content: '<?=lang('join_confirm')?>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					 $.post('/<?=$puri?>/student/activity/apply_activity',{id:id},function(msg){
						  if(msg.state == 1){
							var d = dialog({
								content: ''+msg.info+''
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
								window.location.href='/<?=$puri?>/student/activity/index';
							}, 5000);
							
						  }else{
							 var d = dialog({
								content: ''+msg.info+''
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
							}, 5000);
						  }
					  },'json');
				},
				cancel: function () {
					
					return true;
				}
			});
			d.show();
			

  }

</script>
<?php $this->load->view('student/footer.php')?>
