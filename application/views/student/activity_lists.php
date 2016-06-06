<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('activity_list')?></span><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/add"><?=lang('add_activity')?></a></em></h1>
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
				
				<a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?=lang('detail')?></a>
				<?php if(!empty($uid) && $uid != $v['userid']){?>
				 / 
				 <a href="javascript:;" onclick="activity_collect(<?=$v['id']?>)"><?=lang('activity_collect')?></a>
				 <?php }?>
				<?php 
					if(!empty($uid) && $uid != $v['userid']){
					if(!empty($flag) && $flag == 1){
				?>
					/ <a href="javascript:;" onclick="activity_join(<?=$v['id']?>)"><?=lang('activity_join')?></a> 
				<?php }}?>
				
			</td>
          </tr>
		  
		  <?php }}?>
		   <tbody id = 'evaluate_page'></tbody>
		   
		  <?php if(!empty($pagecount) && $pagecount > 1){?>
			<tr>
			<td colspan="5"  id = 'page' align="center"><div class="locading"><a class="view-more-img" onclick="more(<?=$page?>)" href="javascript:;"><?=lang('evaluate_more')?></a><span id="load_more_img"><img  style="display:none;" id="loading" src="<?=RES?>home/images/public/loading.gif" width="9" height="5" /></span></div></td>
			
			</tr>
		  
		  <?php }?>
          
        </table>
      </div>
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
<script type="text/javascript">
	var pagecount = <?=$pagecount?>;
	function more(page){
		page ++;
		if(page < pagecount){
			var page_html = '<div class="locading mt20"><a class="view-more-img" onclick="more('+page+')" href="javascript:;">Locading</a><span id="load_more_img"><img  id="loading" src="<?=RES?>home/images/public/loading.gif" width="9" height="5" /></span></div>';
			$('#page').html();
			$('#page').html(page_html);
			
		}else{
		
			$('#page').hide('slow');
		}
		
		if(page <= pagecount){
			$.ajax({
				type:'GET',
				url:'/<?=$puri?>/student/activity/get_activity_lists?page='+page,
				success:function(r){
					if(r.state == 1){
						$('#loading').show();
						$('#evaluate_page').append(r.data);
						$('#loading').hide();
					}else{
						art.dialog.tips(''+r.info+'');
						
					}
				},
				dataType:'json'

			});
		}else{
			art.dialog.tips('No Data');
		}
	}

</script>
<?php $this->load->view('student/footer.php')?>
