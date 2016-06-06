<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('my_collect_activity')?></span><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/add"><?=lang('add_activity')?></a></em></h1>
    <div class="tab pt30">
	 <?php $this->load->view('student/activity_nav.php');?>
     
      <div class="Switch_bg">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('activity_title')?></th>
            <th align="left" scope="col"><?=lang('activity_starttime')?></th>
            <th align="left" scope="col"><?=lang('activity_endtime')?></th>
            <th align="left" scope="col"><?=lang('activity_state')?></th>
            <th align="left" scope="col"></th>
          </tr>
		  <?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left"><a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?php if($puri == 'cn'){echo $v['ctitle'];}else{echo $v['etitle'];}?></a><br />
				
			</td>
            <td align="left"><?=!empty($v['starttime'])?date('Y-m-d H:i',$v['starttime']):''?><br /></td>
            <td align="left"><?=!empty($v['endtime'])?date('Y-m-d H:i',$v['endtime']):''?><br /></td>
            <td align="left">
				<?php 
					//判断活动是否结束
					$flag = 0;
					if($v['ishandend'] == 0){
						echo lang('activity_start_3');
					}else{
						if(time() > $v['endtime']){
							echo '<span style="color:#CFCFCF">'.lang('activity_start_3').'</span>';
						}else if( time() > $v['starttime'] && time() < $v['endtime']){
							echo '<span style="color:#FA8072">'.lang('activity_start_2').'</span>';
						
						}else if(time() < $v['starttime']){
							 echo '<span style="color:#FF3030">'.lang('activity_start_1').'</span>';
							 $flag = 1;
						}
					
					}
				
				?>
			
			</td>
            <td align="left">
				
				<a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?=lang('detail')?></a> / 
				 <a href="javascript:;" onclick="activity_collect_cancel(<?=$v['id']?>)"><?=lang('activity_collect_cancel')?></a>
				<?php 
					if(!empty($flag) && $flag == 1){
				?>
					/ <a href="javascript:;" onclick="activity_join(<?=$v['id']?>)"><?=lang('activity_join')?></a> 
				<?php }?>
				
			</td>
          </tr>
		  
		  <?php }}?>
		 
          
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    function activity_collect_cancel(id) {
	
		var d = dialog({
				content: '<?=lang('activity_collectcancel_confirm')?>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					 $.post('/<?=$puri?>/student/activity/activity_collect_cancel',{id:id},function(msg){
						  if(msg.state == 1){
							var d = dialog({
								content: ''+msg.info+''
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
								
							}, 5000);
							window.location.reload();
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
