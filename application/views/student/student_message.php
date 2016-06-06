<?php $this->load->view('student/headermy.php')?>
<?php
  $uri4 = $this->uri->segment(5);
?>
<div class="width_925">
<h2 class="mt50 mb20"><?=lang('message_messagerie')?></span></h2> 
  <div class="web_news" >
  <div class="wlko" >
    <div class="titlenews" >
      <ul>
        <a href="/<?=$puri?>/student/student/stu_message/all" class="White"><li class="<?=empty($uri4) || $uri4 == 'all'?'active':''?>"><?=lang('message_hint')?></li></a>
        <a href="/<?=$puri?>/student/student/stu_message/read" class="White"><li class="<?=!empty($uri4) && $uri4 == 'read'?'active':''?>"><?=lang('message_read')?></li></a>
        <a href="/<?=$puri?>/student/student/stu_message/unread" class="White"><li class="<?=!empty($uri4) && $uri4 == 'unread'?'active':''?>"><?=lang('message_unread')?></li></a>
      </ul>
    </div>
    </div>
    <div class="connews" >
     <?php if(empty($data)){echo '<h2>'.lang('message_nomessage').'</h2>';}?>
      <div id="ss" class="con_con" style="display:<?php echo !empty($data)?'block':'none'?>;">

        <dl id="sss">
          <dt><span class="a1"><?=lang('message_title')?></span><span class="a2"><?=lang('message_content')?></span><span class="a3"><?=lang('message_sendtime')?></span></dt>
          <?php $i=1?>
          <?php foreach($data as $k=>$v):?>
          <?php if($i>6){break;}?>
          <dd id='m<?=$v['uid']?>' <?php if($v['readstate']==2)echo 'style="background-color: green"'?>><span class="a1">
            <label>
            </label>
           <a href="javascript:;" onclick="showcontent(<?=$v['uid']?>)"> <?=$v['title']?></a></span><span class="a2"><?=$v['content']?></span><span class="a3"><?=date('Y-m-d h:i:s',$v['sendtime'])?></span><span class="a4"><a href="javascript:;" onclick="del(<?=$v['uid']?>)"><?=lang('message_del')?></a></span></dd>
          <dd id='c<?=$v['uid']?>' class="close" style="border-bottom-right-radius:5px 5px; border-bottom-left-radius:5px 5px;display:none"><?=$v['content']?><i onclick="closecontent(<?=$v['uid']?>)"></i></dd>
        <?php $i++;?>
        <?php endforeach;?>

         <div style="float:right;display:<?php echo $count>6?'block':'none'?>;" class="cjpj"><p class="wid_206"><a href="javascript:;" onclick="more(<?=$uri4?>)"><?=lang('attendance_more')?></a></p></div>
        </dl>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function showcontent(id){
    $('.close').css('display','none');
    var con=$('#c'+id);
    con.css('display','block');
    $('#m'+id).removeAttr('style');
    $.ajax({
              url: '/<?=$puri?>/student/student/read_message?id='+id,
              type: 'GET',
              dataType: 'json'
            })
            .done(function(r) {

            })
            .fail(function() {
              console.log("error");
            })
  }
  function closecontent(id){
    var con=$('#c'+id);
    con.css('display','none'); 
  }
  function del(id){
     var d = dialog({
                    title: '<?=lang('welcome')?>',
                    content: '<?=lang('del_confirm')?>',
                    ok: function () {
                      $.ajax({
                          url: '/<?=$puri?>/student/student/del_stu_message?id='+id,
                          type: 'GET',
                          dataType: 'json'
                        })
                        .done(function(r) {
                          if(r.state==1){
                              var a = dialog({
                                  title: '<?=lang('welcome')?>',
                                  content: r.info,
                                });
                                a.show();

                            setTimeout('location.reload()',500);
                          }
                        })
                        .fail(function() {
                          console.log("error");
                        })
                    },
                    cancel: function () {
                      
                      return true;
                    }
                  });
                  d.show();
  }
  function moredel(){
     var d = dialog({
                    title: '<?=lang('welcome')?>',
                    content: '<?=lang('del_confirm')?>',
                    ok: function () {
                     var data= $('#form').serialize();
                      $.ajax({
                          url: '/<?=$puri?>/student/student/del_more_stu_message',
                          type: 'POST',
                          dataType: 'json',
                          data:data,
                        })
                        .done(function(r) {
                          if(r.state==1){
                              var a = dialog({
                                  title: '<?=lang('welcome')?>',
                                  content: r.info,
                                });
                                a.show();
                            setTimeout('location.reload()',500);
                          }
                        })
                        .fail(function() {
                          console.log("error");
                        })
                    },
                    cancel: function () {
                      
                      return true;
                    }
                  });
                  d.show();
  }
  function checkboxall(t){
       $(":checkbox").attr("checked", true);  
  }
  function checkboxdel(){
      $(":checkbox").removeAttr("checked");
  }
  function more(){
    $.ajax({
              url: '/<?=$puri?>/student/student/message_more',
              type: 'GET',
              dataType: 'json'
            })
            .done(function(r) {
              if(r.state==1){
                var last_li=$('#ss dd:last');
                var str='';

                $.each(r.data,function(k,v){
                  if(v.readstate==2){
                    css="style='background-color: green'";
                  }else{
                    css='';
                  }
                  str+="<dd id='"+v.uid+"' "+css+"><span class='a1'> <label> </label><a href='javascript:;' onclick='showcontent("+v.uid+")'> "+v.title+"</a></span><span class='a2'>"+v.content+"</span><span class='a3'>"+v.sendtime+"</span><span class='a4'><a href='javascript:;' onclick='del_more("+v.uid+")'><?=lang('message_del')?></a></span></dd><dd id='c"+v.uid+"' class='close' style='border-bottom-right-radius:5px 5px; border-bottom-left-radius:5px 5px;display:none'>"+v.content+"<i onclick='closecontent("+v.uid+")'></i></dd>";
                })
                last_li.after(str);
              $('.cjpj').find('a').attr('onclick','packup()');
                $('.cjpj').find('a').text('<?=lang('attendance_packup')?>');
              }
            })
            .fail(function() {
              console.log("error");
            })
}
function del_more(id){
   var d = dialog({
                    title: '<?=lang('welcome')?>',
                    content: '<?=lang('del_confirm')?>',
                    ok: function () {
                      $.ajax({
                          url: '/<?=$puri?>/student/student/del_stu_message?id='+id,
                          type: 'GET',
                          dataType: 'json'
                        })
                        .done(function(r) {
                          if(r.state==1){
                            $('#'+id).remove();
                           // var num= $("#sss dd").length;
                           // alert(num);
                          }
                        })
                        .fail(function() {
                          console.log("error");
                        })
                    },
                    cancel: function () {
                      
                      return true;
                    }
                  });
                  d.show();
}
function packup(){
  window.location='/<?=$puri?>/student/student/stu_message';
}
</script>
<?php $this->load->view('student/footer.php')?>
