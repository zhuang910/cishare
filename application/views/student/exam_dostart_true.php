<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('exam')?> (<?=lang('exam_score')?>:<span style="color:red;"> <?=!empty($flag_true['score'])?$flag_true['score'].lang('exam_score_small'):''?></span>)</span><em class="title_btn fr"><a href="javascript:;" onclick="history.back()"><?=lang('activity_return')?></a></em></h1>
	
    <div class="tab pt30">

      
	  <?php if($question){
		foreach($question as $k => $v){
	  ?>

		<div class="test mt30">
        <div class="g_title"><?=$k + 1?>.<?=!empty($v['name'])?$v['name']:''?><?=!empty($v['all_score'])?'('.$v['all_score'].lang('exam_score_small').')':''?></div>
		<?php if(!empty($v['childs'])){
			$count = count($v['childs']);
			foreach($v['childs'] as $key => $val){
		?>
		<dl class="test_Topic" <?=$key + 1 == $count?'style="border-bottom:none;"':''?>>
          <dt><?=$key + 1?>. <?=lang('exam_single_'.$val['topic_type'])?> (<?=!empty($val['score'])?$val['score'].lang('exam_score_small'):''?>) <?=!empty($val['topic_answer'])?$val['topic_answer']:''?>:</dt>
          <dd>
		  <?php if($val['topic_type'] == 1){?>
			<?php for($i = 0;$i< $val['answer_num'];$i++){ ?>
				<span><input disabled name="<?=$val['topic_type']?>_<?=$val['id']?>" type="radio" value="<?=chr(65+$i)?>" <?=!empty($answer[$val['id']]) && $answer[$val['id']] == chr(65+$i)?'checked':''?>/><em><?=chr(65+$i)?></em></span>
			<?php }?>
			<span style="color:red;"><?=lang('exam_right_answer')?>:<?=!empty($val['one_correct_answer'])?'['.$val['one_correct_answer'].']':''?></span>
		<?php }else{?>
			<?php for($i = 0;$i< $val['answer_num'];$i++){ ?>
				<span><input disabled name="<?=$val['topic_type']?>_<?=$val['id']?>[]" type="checkbox" <?=!empty($answer[$val['id']]) && in_array(chr(65+$i),$answer[$val['id']])?'checked':''?> value="<?=chr(65+$i)?>" /><em><?=chr(65+$i)?></em></span>
			<?php }?>
			<span style="color:red;"><?=lang('exam_right_answer')?>:<?=!empty($val['more_correct_answer'])?$val['more_correct_answer']:''?></span>
		<?php }?>
		  </dd>
		  
        </dl>
		
		<?php }}?>
        
       
      </div>

		<?php }}?>
	  
      
    </div>
	
  </div>
</div>
<?php $this->load->view('student/footer.php')?>
