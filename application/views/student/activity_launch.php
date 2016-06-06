<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('my_add_activity')?></span><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/add"><?=lang('add_activity')?></a></em></h1>
    <div class="tab pt30">
	 <?php $this->load->view('student/activity_nav.php');?>
     
      <div class="Switch_bg">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('activity_title')?></th>
            <th align="left" scope="col"><?=lang('activity_starttime')?></th>
            <th align="left" scope="col"><?=lang('activity_endtime')?></th>
            <th align="left" scope="col"><?=lang('activity_admincheck')?></th>
            <th align="left" scope="col"></th>
          </tr>
		  <?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left"><a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?php if($puri == 'cn'){echo $v['ctitle'];}else{echo $v['etitle'];}?></a><br />
				<?php if($v['state'] != 0){?><span style="color:red;">
					<?=!empty($v['auditopinion'])?lang('activity_auditopinion').':'.$v['auditopinion'].'<br />':''?>
					<?=!empty($v['auditname'])?lang('activity_auditname').':'.$v['auditname'].'<br />':''?>
					<?=!empty($v['audittime'])?lang('activity_audittime').':'.date('Y-m-d H:i:s',$v['audittime']).'<br />':''?>
				</span><?php }?>
			</td>
            <td align="left"><?=!empty($v['starttime'])?date('Y-m-d H:i',$v['starttime']):''?><br /></td>
            <td align="left"><?=!empty($v['endtime'])?date('Y-m-d H:i',$v['endtime']):''?><br /></td>
            <td align="left">
				<?=lang('activity_state_'.$v['state'])?>
			
			</td>
            <td align="left">
				<a href="/<?=$puri?>/student/activity/detail?id=<?=$v['id']?>"><?=lang('detail')?></a> /
				<a href="javascript:;" onclick="activity_end(<?=$v['id']?>)"><?=lang('ishandend')?></a> / 
				<?php 
					if($v['state'] == 0){
				?>
					<a href="javascript:;" onclick="activity_cancel(<?=$v['id']?>)"><?=lang('activity_cancel')?></a> /
					<a href="/<?=$puri?>/student/activity/add_c?id=<?=$v['id']?>"><?=lang('activity_add_content')?></a>/
				<?php }else if($v['state'] == 1){?>
				 <a href="/<?=$puri?>/student/activity/ckuser?id=<?=$v['id']?>"><?=lang('activity_ck_user')?>(<?=!empty($count) && !empty($count[$v['id']])?$count[$v['id']]:0?>)</a>
				<?php }else{?>
				--
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
    function activity_end (id) {
	
		var d = dialog({
				title: '<?=lang('welcome')?>',
				content: '<?=lang('del_confirm')?>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					 $.post('/<?=$puri?>/student/activity/js_activity',{id:id},function(msg){
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
			setTimeout(function () {
				d.close().remove();
			}, 5000);

  }

</script>
<script type="text/javascript">
    function activity_cancel (id) {
	
		var d = dialog({
				title: '<?=lang('welcome')?>',
				content: '<?=lang('del_confirm')?>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					 $.post('/<?=$puri?>/student/activity/del_activity',{id:id},function(msg){
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
			setTimeout(function () {
				d.close().remove();
			}, 5000);

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
				url:'/<?=$puri?>/student/activity/get_activity_launch?page='+page,
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
