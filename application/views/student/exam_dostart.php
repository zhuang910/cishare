<?php $this->load->view('student/headermy.php')?>
<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('exam')?></span><em class="title_btn fr"><a href="javascript:;" onclick="history.back()"><?=lang('activity_return')?></a></em></h1>
	<form name="myform" id="myform" method="post" action="/<?=$puri?>/student/exam/dosave">
    <div class="tab pt30">
      <div class="notes clearfix"><span class="time fl"><?=lang('activity_starttime')?> : <span id="nowtime"></span></span> <span class="note fr"><?=lang('exam_has_time')?> : <em id="cucas_1"></em><i onclick="end_showtime()"data = 1 id="end_showtime"> Pause </i></span></div>
      
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
          <dt><?=$key + 1?>.<?=lang('exam_single_'.$val['topic_type'])?> (<?=!empty($val['score'])?$val['score'].lang('exam_score_small'):''?>) <?=!empty($val['topic_answer'])?$val['topic_answer']:''?>:</dt>
          <dd>
		  <?php if($val['topic_type'] == 1){?>
			<?php for($i = 0;$i< $val['answer_num'];$i++){ ?>
				<span><input name="<?=$val['topic_type']?>_<?=$val['id']?>" type="radio" value="<?=chr(65+$i)?>" /><em><?=chr(65+$i)?></em></span>
			<?php }?>
		<?php }else{?>
			<?php for($i = 0;$i< $val['answer_num'];$i++){ ?>
				<span><input name="<?=$val['topic_type']?>_<?=$val['id']?>[]" type="checkbox" value="<?=chr(65+$i)?>" /><em><?=chr(65+$i)?></em></span>
			<?php }?>
		<?php }?>
		  </dd>
        </dl>
		
		<?php }}?>
        
       
      </div>

		<?php }}?>
	  
      
    </div>
	<div class="enter_btn clearfix">
        <div class="gary_btn fl"><a href="javascript:;" onclick="history.back();"><?=lang('activity_return')?></a></div>
        <div class="blue_btn fl"><a href="javascript:;" onclick="save()"><?=lang('exam_save')?></a></div>
		<input type="hidden" name="pageid" value="<?=$pageid?>">
		<input type="hidden" name="time" value="<?=$time?>">
		<input type="hidden" name="used_time" value="" id="used_time">
      </div>
	</form>
  </div>
</div>
<script type="text/javascript">
$(function(){
	var myDate = new Date();
	var myyear = myDate.getFullYear();
	var mymonth = myDate.getMonth();
	mymonth = mymonth - (-1);
	var myday = myDate.getDate();
	var myhour = myDate.getHours(); 
	var myminute = myDate.getMinutes();
	var myseconds = myDate.getSeconds();
	var nowtime = myyear+'-'+mymonth+'-'+myday+' '+myhour+':'+myminute+':'+myseconds;
	$('#nowtime').html('');
	$('#nowtime').html(nowtime);

});
var ExamTimer;
var RunTime = 0;

function SetClock(){
    ExamTimer = setInterval("RunClock()",1000);
}

function RunClock(){
    RunTime++;
    var Temp_time = RunTime;
    Temp_time=Temp_time % (60*60*24);
    H=Math.floor(Temp_time/(60*60))==0?"00":Math.floor(Temp_time/(60*60))+"" ;
    if(H.length<2){H="0"+H;}
    Temp_time=Temp_time % (60*60);
    M=Math.floor(Temp_time/(60))==0?"00":Math.floor(Temp_time/(60))+"";
    if(M.length<2){M="0"+M;}
    Temp_time=Temp_time % (60);
    S=Math.floor(Temp_time)+"";
    if(S.length<2){S="0"+S;}
    R=H+":"+M+":"+S;
    $("#cucas_1").html(R);
}

function end_showtime(){
    clearInterval(ExamTimer);
	var d = dialog({
					id:'cucasdialog',
					content:"您已暂停作答",
					width:400,
					okValue: 'Continue',
					ok: function () { SetClock(); }
				});
				d.showModal();
	
	
    
 }
 
 SetClock();
</script>
<script type="text/javascript">
function save(){
	var used_time = $('#cucas_1').html();
	
	$('#used_time').val(used_time);
	$('#myform').submit();
}
$(function(){
	$('#myform').ajaxForm({
		beforeSend:function(){
			var d = dialog({
					id:'cucasdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
		},
		success:function(msg){
				dialog({id:'cucasdialog'}).close();
			if(msg.state == 1){
				var d = dialog({
					content: '<span><?=lang('exam_score')?>:'+msg.data.score+'</span><br /><span><?=lang('exam_finish_state')?>:'+msg.data.finish_state+'</span><br />'
				});
				d.showModal();
				setTimeout(function () {
					d.close().remove();
					history.back();
				}, 6000);
				
			}else if(msg.state == 0){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.showModal();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			}
		},
		dataType:'json'
	
	});
	
	

});
</script>
<?php $this->load->view('student/footer.php')?>
