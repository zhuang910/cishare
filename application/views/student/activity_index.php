<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('my_join_activity')?></span><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/add"><?=lang('add_activity')?></a></em></h1>
    <div class="tab pt30">
	 <?php $this->load->view('student/activity_nav.php');?>
     
      <div class="Switch_bg">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('activity_title')?></th>
            <th align="left" scope="col"><?=lang('activity_starttime')?></th>
            <th align="left" scope="col"><?=lang('activity_endtime')?></th>
            <th align="left" scope="col"><?=lang('activity_user_state')?></th>
            <th align="left" scope="col"></th>
          </tr>
		  <?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left"><a href="/<?=$puri?>/student/activity/detail?id=<?=$v['activityid']?>">
			<?php 
				if(!empty($myact) && !empty($myact[$v['activityid']])){
					if($puri == 'cn' && !empty($myact[$v['activityid']]['ctitle'])){echo $myact[$v['activityid']]['ctitle'];}else if($puri == 'en' && !empty($myact[$v['activityid']]['etitle'])){echo $myact[$v['activityid']]['etitle'];}
				}
			
			
			?>
			
			</a><br />
				
			</td>
            <td align="left"><?=!empty($myact) && !empty($myact[$v['activityid']]) && !empty($myact[$v['activityid']]['starttime'])?date('Y-m-d H:i',$myact[$v['activityid']]['starttime']):''?><br />
			<?php if($v['state'] != 0){?><span style="color:red;">
					<?=!empty($v['auditopinion'])?lang('activity_auditopinion').':'.$v['auditopinion'].'<br />':''?>
					<?=!empty($v['auditname'])?lang('activity_auditname').':'.$v['auditname'].'<br />':''?>
					<?=!empty($v['audittime'])?lang('activity_audittime').':'.date('Y-m-d H:i:s',$v['audittime']).'<br />':''?>
				</span><?php }?>
			</td>
            <td align="left"><?=!empty($myact) && !empty($myact[$v['activityid']]) && !empty($myact[$v['activityid']]['endtime'])?date('Y-m-d H:i',$myact[$v['activityid']]['endtime']):''?><br /></td>
            <td align="left">
				<?php 
					//判断审核状态 决定 可以是否取消 活动
					$flag = 0;
					if($v['state'] == 0){
						echo '<span style="color:#DA70D6">'.lang('activity_state_0').'</span>';
						
					}else if($v['state'] == 1){
						echo '<span style="color:#98F5FF">'.lang('activity_state_1').'</span>';
						$flag = 1;
					}else if($v['state'] == 2){
						echo '<span style="color:#CD3333">'.lang('activity_state_2').'</span>';
					}
				
				?>
				
				<br />
				<?=lang('activity_user_isjoin')?> : <?=!empty($v['isjoin']) && $v['isjoin'] == 1?lang('activity_isapply_yes'):lang('activity_isapply_no')?>
				<br />
				<?=lang('activity_isapply_score')?> : <?=!empty($v['score'])?lang('activity_isapply_score_'.$v['score']):''?>
			</td>
            <td align="left">
				
				<a href="/<?=$puri?>/student/activity/detail?id=<?=$v['activityid']?>"><?=lang('detail')?></a>
				<?php 
					if(!empty($flag) && $flag == 1){
				?>
					/ <a href="/<?=$puri?>/student/activity/ckusersame?id=<?=$v['activityid']?>" ><?=lang('activity_ck_user')?></a> 
				<?php }?>
				
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
    function activity_cancel_join(id) {
	
		var d = dialog({
				content: '<?=lang('activity_cancel_confirm')?>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					 $.post('/<?=$puri?>/student/activity/activity_cancel_join',{id:id},function(msg){
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
				url:'/<?=$puri?>/student/activity/get_activity_index?page='+page,
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
