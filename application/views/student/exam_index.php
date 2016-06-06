<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
   <h1 class="clearfix"><span class="fl"><?=lang('exam')?></span></h1>
    <div class="tab pt30">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          
          <th align="left" scope="col"><?=lang('exam_title')?></th>
          <th align="left" scope="col"></th>
        </tr>
		<?php if($result1){
			foreach($result1 as $k => $v){
		?>
		<tr>
         
          <td align="left"><a href="/<?=$puri?>/student/exam/dostart?id=<?=$v['id']?>"><?php if($puri == 'cn' && !empty($v['name'])){ echo $v['name']; }else{ echo $v['enname'];}?></a></td>
          <td align="right"><div class="table_btn"><a href="/<?=$puri?>/student/exam/dostart?id=<?=$v['id']?>"><?=!empty($join_info) && in_array($v['id'],$join_info)?lang('exam_start_result'):lang('exam_start')?></a></div></td>
        </tr>
		
		<?php }}?>
        <?php if($result2){
			foreach($result2 as $key => $val){
		?>
		<tr>
         
          <td align="left"><a href="/<?=$puri?>/student/exam/dostart?id=<?=$val['id']?>"><?php if($puri == 'cn' && !empty($val['name'])){ echo $val['name']; }else{ echo $val['enname'];}?></a></td>
          <td align="right"><div class="table_btn"><a href="/<?=$puri?>/student/exam/dostart?id=<?=$val['id']?>"><?=!empty($join_info) && in_array($val['id'],$join_info)?lang('exam_start_result'):lang('exam_start')?></a></div></td>
        </tr>
		
		<?php }}?>
       
      </table>
    </div>
  </div>
</div>
<?php $this->load->view('student/footer.php')?>
