<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('my_join_activity')?></span><em class="title_btn fr"><a href="/<?=$puri?>/student/activity/add"><?=lang('add_activity')?></a></em></h1>
    <div class="tab pt30">
	 <?php $this->load->view('student/activity_nav.php');?>
     
      <div class="Switch_bg">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th align="left" scope="col"><?=lang('pickup_name')?></th>
            <th align="left" scope="col"><?=lang('pickup_sex')?></th>
            <th align="left" scope="col"><?=lang('pickup_nationality')?></th>
            <th align="left" scope="col"><?=lang('major')?></th>
            <th align="left" scope="col"><?=lang('class')?></th>
          </tr>
		  <?php if(!empty($result)){
			foreach($result as $k => $v){
		  ?>
		   <tr>
            <td align="left"><?=!empty($v['name'])?$v['name']:''?>
			</td>
            <td align="left"><?=!empty($v['sex'])?lang('pickup_sex'.$v['sex']):''?>
			</td>
            <td align="left"><?=!empty($v['nationality'])?$nationality[$v['nationality']]:''?></td>
            <td align="left">
				<?=!empty($v['major'])?$v['major']:''?>
			</td>
            <td align="left">
				
				<?=!empty($v['classid'])?$v['classid']:''?>
				
			</td>
          </tr>
		  <?php }}?>
		
          
        </table>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('student/footer.php')?>
