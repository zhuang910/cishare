<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>


<div class="width1024">
  <div class="wap_box p30">
    <h1 class="clearfix"><span class="fl"><?=lang('evaluate_my_eav')?></span><em class="list_box2 fr"><?=$year?>
      <ul>
        <li><a href="/<?=$puri?>/student/evaluate/index/2013">2013</a></li>
        <li><a href="/<?=$puri?>/student/evaluate/index/2014">2014</a></li>
        <li><a href="/<?=$puri?>/student/evaluate/index/2015">2015</a></li>
        <li><a href="/<?=$puri?>/student/evaluate/index/2016">2016</a></li>
      </ul>
      </em></h1>
    <div class="tab pt30">
    <?php if(empty($eav_info)){echo '<h3>'.lang('evaluate_no_data').'</h3>';}?>
    <?php if(!empty($eav_info)){?>
      <div class="a_title clearfix"><span class="bt1"><?=lang('evaluate_c_name')?></span><span class="bt2"><?=lang('evaluate_t_name')?></span><span class="bt3"><?=lang('evaluate_score')?></span></div>
      
      <?php foreach($eav_info as $k=>$v){?>
      <dl class="ak mt20">
        <dt id="dt<?=$v['id']?>" class="clearfix active"><span class="bt1 fl"><?=!empty($v['cname'])?$v['cname']:lang('evaluate_wei')?></span> <span class="bt2 fl"><?=!empty($v['tname'])?$v['tname']:lang('evaluate_wei')?></span> <span class="bt3 fl"><?=!empty($v['grade'])?lang('evaluate_answer_'.$v['grade'].''):''?></span> <span class="fl"><a att="gengduo" href="javascript:;" onclick="show_dd(this,<?=$v['id']?>)"><?=lang('evaluate_more')?></a></span></dt>
        <dd id="dd<?=$v['id']?>" style="display:none">
        <?php if(!empty($v['item_info'])){?>
            <?php foreach($v['item_info'] as $kkk=>$vvv){?>
                <b><?=$vvv['title']?></b>
                <p><?=lang('evaluate_answer_'.$vvv['answer'].'')?></p>
        <?php }}?>
        <?php if(!empty($v['text'])){?>
        <?php foreach($v['text'] as $kk=>$vv){?>
          <b><?=$puri=='cn'?$vv['name']:$vv['enname']?></b>
          <p><?=$vv['answer']?></p>
        <?php }}?>
        </dd>
      </dl>
      <?php }}?>
      <!-- <dl class="ak mt20">
        <dt class="clearfix"><span class="bt1 fl">Listening</span> <span class="bt2 fl">Song Meiyin teacher</span> <span class="bt3 fl red_font">Good</span> <span class="fl"><a href="#">More</a></span></dt>
      </dl>
      <dl class="ak mt20">
        <dt class="clearfix"><span class="bt1 fl">Listening</span> <span class="bt2 fl">Song Meiyin teacher</span> <span class="bt3 fl red_font">Good</span> <span class="fl"><a href="#">More</a></span></dt>
      </dl>
      <dl class="ak mt20">
        <dt class="clearfix"><span class="bt1 fl">Listening</span> <span class="bt2 fl">Song Meiyin teacher</span> <span class="bt3 fl red_font">Good</span> <span class="fl"><a href="#">More</a></span></dt>
      </dl>
      <dl class="ak mt20">
        <dt class="clearfix"><span class="bt1 fl">Listening</span> <span class="bt2 fl">Song Meiyin teacher</span> <span class="bt3 fl red_font">Good</span> <span class="fl"><a href="#">More</a></span></dt>
      </dl> -->
    </div>
  </div>
</div>

<script type="text/javascript">
function go_on(){
  window.location='/<?=$puri?>/student/index/evaluate_page';
}
function show_dd(th,id){
  var text= $(th).attr('att');

  if(text=='gengduo'){
    $(th).attr('att', 'shouqi');
    $(th).text('<?=lang('evaluate_packup')?>');
    $('#dt'+id).attr({
      class: 'clearfix'
    });
    $('#dd'+id).css({
      display: 'block'
    });
  }else if(text=='shouqi'){
    $(th).attr('att', 'gengduo');
    $(th).text('<?=lang('evaluate_more')?>');
     $('#dt'+id).attr({
      class: 'clearfix active'
    });
    $('#dd'+id).css({
      display: 'none'
    });
  }
  
}
</script>
<?php $this->load->view('student/footer.php')?>
