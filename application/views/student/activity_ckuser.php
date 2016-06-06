<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('my_add_activity')?></span><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/add"><?=lang('add_activity')?></a></em></h1>
    <div class="tab pt30">
	 <?php $this->load->view('student/activity_nav.php');?>
     
      <div class="Switch_bg">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('activity_user_info')?></th>
            <th align="left" scope="col"><?=lang('activity_user_applytime')?></th>
            <th align="left" scope="col"><?=lang('activity_user_state')?></th>
            <th align="left" scope="col"><?=lang('activity_user_isjoin')?></th>
            <th align="left" scope="col"><?=lang('activity_isapply_score')?></th>
          </tr>
		  <?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left">
			<?=lang('pickup_name')?>:<?=!empty($v['name'])?$v['name']:''?><br />
			<?=lang('pickup_sex')?>:<?=!empty($v['sex'])?lang('pickup_sex'.$v['sex']):''?><br />
			<?=lang('pickup_nationality')?>:<?=!empty($v['nationality'])?$nationality[$v['nationality']]:''?><br />
			<?=lang('passport')?>:<?=!empty($v['passport'])?$v['passport']:''?><br />
			<?=lang('major')?>:<?=!empty($v['major'])?$v['major']:''?><br />
			<?=lang('class')?>:<?=!empty($v['classid'])?$v['classid']:''?><br />

			<a href="javascript:;" onclick="look_speciality(<?=$v['userid']?>)"><?=lang('activity_look_speciality')?></a>
			<br />
				
			</td>
            <td align="left"><?=!empty($v['applytime'])?date('Y-m-d H:i:s',$v['applytime']):''?><br />
			</td>
            <td align="left">
			<?php 
					//判断审核状态 决定 可以是否取消 活动
					
					if($v['state'] == 0){
						echo '<span style="color:#DA70D6">'.lang('activity_state_0').'</span>';
						
					}else if($v['state'] == 1){
						echo '<span style="color:#98F5FF">'.lang('activity_state_1').'</span>';
						
					}else if($v['state'] == 2){
						echo '<span style="color:#CD3333">'.lang('activity_state_2').'</span>';
					}
				
				?>
				
			<br />
			<a href="javascript:;" onclick="activity_isapply_check(<?=$v['activityid']?>,<?=$v['userid']?>)"><?=lang('activity_isapply_check')?></a>
			</td>
            <td align="left">
				<?php 
					if(!empty($v['isjoin']) && $v['isjoin'] == 1){
						echo lang('activity_isapply_yes');
					}else{
						echo lang('activity_isapply_no');
					}
				
				?><br />
				<a href="javascript:;" onclick="activity_isapply_set(<?=$v['activityid']?>,<?=$v['userid']?>)"><?=lang('activity_isapply_set')?></a>
				
			</td>
            <td align="left">
				<?=!empty($v['score'])?lang('activity_isapply_score_'.$v['score']):''?>
			<br />
				<?php if(!empty($on) && $on == 1){?>
				<a href="javascript:;" onclick="activity_isapply_score(<?=$v['activityid']?>,<?=$v['userid']?>)"><?=lang('activity_isapply_score')?></a> 
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
	function activity_isapply_score(activityid,userid){
		if(activityid != '' && userid != ''){
			var d = dialog({
				title:'<?=lang('activity_isapply_score')?>',
				content: '<select name="score" id="score"><option value=""><?=lang('activity_isapply_score')?></option><option value="1"><?=lang('activity_isapply_score_1')?></option><option value="2"><?=lang('activity_isapply_score_2')?></option><option value="3"><?=lang('activity_isapply_score_3')?></option><option value="4"><?=lang('activity_isapply_score_4')?></option><option value="5"><?=lang('activity_isapply_score_5')?></option></select>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					var score = $('#score').val();
					$.post('/<?=$puri?>/student/activity/activity_isapply_score',{userid:userid,activityid:activityid,score:score},function(msg){
						  if(msg.state == 1){
							var d = dialog({
								content: ''+msg.info+''
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
								
							}, 2000);
							
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
			d.showModal();
		}
	}
</script>
<script type="text/javascript">
	function activity_isapply_check(activityid,userid){
		if(activityid != '' && userid != ''){
			var d = dialog({
				title:'<?=lang('activity_isapply_check')?>',
				content: '<?=lang('activity_isapply_check')?>  :<select name="state" id="state"><option value=""><?=lang('activity_isapply_check')?></option><option value="1"><?=lang('activity_state_1')?></option><option value="2"><?=lang('activity_state_2')?></option></select><br /><?=lang('activity_isapply_auditopinion')?>:<textarea id="auditopinion" name="auditopinion"></textarea>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					var state = $('#state').val();
					var auditopinion = $('#auditopinion').val();
					$.post('/<?=$puri?>/student/activity/activity_isapply_check',{userid:userid,activityid:activityid,state:state,auditopinion:auditopinion},function(msg){
						  if(msg.state == 1){
							var d = dialog({
								content: ''+msg.info+''
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
								
							}, 2000);
							
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
			d.showModal();
		}
	}
</script>
<script type="text/javascript">
	function activity_isapply_set(activityid,userid){
		if(activityid != '' && userid != ''){
			var d = dialog({
				title:'<?=lang('activity_isapply_set')?>',
				content: '<input type="radio" name="isjoin" value=1><?=lang('activity_isapply_yes')?>&nbsp;<input type="radio" name="isjoin" value=-1><?=lang('activity_isapply_no')?>',
				ok: function () {
					var thats = this;
					this.title('<?=lang('submiting')?>');
					setTimeout(function () {
						thats.close().remove();
					}, 8000);
					var isjoin = $('input[name="isjoin"]:checked').val();
					$.post('/<?=$puri?>/student/activity/activity_join_set',{userid:userid,activityid:activityid,isjoin:isjoin},function(msg){
						  if(msg.state == 1){
							var d = dialog({
								content: ''+msg.info+''
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
								
							}, 2000);
							
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
			d.showModal();
		}
	}
</script>
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
//查看特长
function look_speciality(userid){
$.ajax({
    url: '/<?=$puri?>/student/activity/look_speciality?userid='+userid,
    type: 'POST',
    dataType: 'json',
    data: {},
  })
  .done(function(r) {
    if(r.state==1){
       var d = dialog({
       	title:"<?=lang('activity_look_speciality')?>",
       	width:300,
          content: ''+r.data+''
        });
        d.show();
    }
    if(r.state==2){
      var d = dialog({
		  	title:"<?=lang('activity_look_speciality')?>",
		   	width:300,
            content: "<?=lang('activity_no_speciality')?>"
        });
        d.show();
        setTimeout(function () {
          d.close().remove();
        }, 2000);
    }

  })
  .fail(function() {
    console.log("error");
  })

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
