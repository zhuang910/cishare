<!DOCTYPE html>
<html>
<head>
<title>留学北信 - 北京信息职业技术学院</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?=RES?>home/css/global.css" rel="stylesheet" type="text/css" />
<script src="<?=RES?>home/js/jquery.min.js"></script>
<link rel="stylesheet" href="<?=RES?>home/js/plugins/artdialog/css/ui-dialog.css">
<script src="<?=RES?>home/js/plugins/artdialog/dialog-min.js"></script>
<script src="<?=RES?>home/js/plugins/artdialog/dialog-plus-min.js"></script>
<script src="<?=RES?>home/js/plugins/jquery.validate.js"></script>

<link href="<?=RES?>home/css/applyonline.css" rel="stylesheet" type="text/css" media="screen">
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
</head>
<body>

<script type="text/javascript">
	$(document).ready(function(){
		$(".applyonline-head-nav ul li").mouseenter(function(){
			$(".login_box").show();
		});
		$(".login_box").mouseover(function(){
			$(".login_box").show();
		});
	$(".login_box").mouseout(function(){
			$(".login_box").hide();
		});
	});
	
	 $(document).ready(function(){
       $(".top-nav").mouseover(function(){
	      $(this).next("span.xian").hide();
		  $(this).prev("span.xian").hide();
	      //$(this).css({"background-color":"#fff","border-left":"1px solid #b8b8b8","border-right":"1px solid #b8b8b8","border-bottom":"2px solid #fff"});
          $(this).find("ul").show();
		  $(this).find(".mapxian-1").show();
        });
        $(".top-nav").mouseout(function(){
		  $(this).next("span.xian").show();
		  $(this).prev("span.xian").show();
	     // $(this).css({"background-color":"transparent","border":"none"});
          $(this).find("ul").hide();
		  $(this).find(".mapxian-1").hide();
        });
    });
</script>
<link href="<?=RES?>home/css/thumbnail.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/select2.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.sticky.js"></script>

<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<ul class="c2">
			<li class="d2"><a href="/master/enrollment/fillingoutforms/apply?applyid=<?=$a_id?>&template=1"><span>2</span> <em>Application Form</em></a></li>
			<li class="d3"><a href="/master/enrollment/upload_attachment/upload_materials?applyid=<?=$a_id?>"><span>3</span> <em>Upload Materials</em></a></li>
		</ul>
	
	</div>
	<div class="applyonline-2-main">
		<div class="f_l applyonline-3-left">
			<dl>
				<dt><?=lang('notice')?></dt>
				<dd>1.To avoid delays in admission, please upload each document according to the description in the left column.</dd>
				<dd>2.Please make sure the scanned or photographied documents are clearly readable and can be printed.</dd>
				<dd>3.The system only accepts the following format RAR, PDF, JPG, PNG, GIF,DOC,DOCX. </dd>
				<dd style="background:none; padding-bottom:0px;">4.Single upload file size can not exceed 5M.</dd>
			</dl>
		</div>
		<form name="myform" id="myform" method="post"  enctype="multipart/form-data">
		<div class="f_r applyonline-left-nav-width624">
			<div class="applyonline-3-right-title"><?=lang('apply_3')?></div>
			<ul>
			<?php if(!empty($attachment_content)){
				foreach($attachment_content as $key => $value){
			?>
				<li>
					<div class="f_l applyonline-3-right-left-title">
						<p class="applyonline-3-right-left-title-des"> 
						<?php if(!empty($value['isrequired']) && $value['isrequired'] == 1){?>
						<span style="color:red;">*</span>
						<?php }?>
						<?=$key + 1?>.<?=!empty($value['TopicName'])?$value['TopicName']:''?></p>
						<p class="applyonline-3-right-left-title-word">,<?=!empty($value['des'])?$value['des']:''?></p>.
					</div>
					 	<div class="applyonline-3-right-right" style="max-width:260px;">
						<?php if(!empty($attachment_info)){
							  foreach ($attachment_info as $k => $v) {
								if($v['attachmentid'] == $value['aTopic_id']){
							?>
							
								  <div class="restaurant-block lite  ">
									<div class="line-one">
									  <div class="logo-wrapper">
										<div class="logo">
										  <a href="javascript:void();">
											<img src="<?=$v['thumbnailUrl'].'#'.time()?>" class="restaurant-logo"></a>
										</div>
										<div class="deliver-time-wrapper busy tooltip_on" title="<?=$v['truename']?>"><?=$v['truename']?></div>
									  </div>
									</div>
									<div class="delete-action" style="display: none;">
									  <a rel="<?=$v['id']?>" class="eleme-icon delete" href="javascript:;"></a>
									</div>
								  </div>
								   <input type="hidden" name="img-<?=$value['aTopic_id']?>" value= '<?=!empty($v['thumbnailUrl'])?'flag':''?>' >
							 
						  <?php }}}?>
					 
						<div style="width: 125px; height: 125px; float: left;margin-left: 10px"> 
						<a href="#" id="FpUpload_<?=$value['aTopic_id']?>" onclick="upfile(this,<?=$value['aTopic_id']?>,10);"><img alt="" src="<?=RES?>home/images/user/applyonline-3-right-plus.png"></a>
						
						 <input type="file" style="display: none;" name="files" class="hidden" value="" id="fileupload_<?=$value['aTopic_id']?>" />
						 <?php if(!empty($value['isrequired']) && $value['isrequired'] == 1){?>
						 <input type="hidden" name="<?=$value['aTopic_id']?>" value= '' id='img_<?=$value['aTopic_id']?>'>

						 <?php }?>
						 </div>
					</div>
					 
				</li>
			
			<?php }}?>
				
			</ul>

			<div class="applyonline-2-btn">
				
				
				
			</div>
		</div>
		</form>
	</div>
</div>

<script>
        var applyid = '<?=!empty($apply_info['id'])?cucas_base64_encode($apply_info['id']):''?>';
        var upkey_lp = 0;
        var is_upload = false;

          
                $('.FPUploadBtn').bind('click', function(e) {
                    e.preventDefault();
                   
                    
                });
                function upfile(divthis,at_id,count){
                  var upkey = $("#upkey");
                  upkey.val(upkey.val()+upkey_lp);
                  upkey_lp ++;
                  $(divthis).parent().find('input[type=file]').trigger('click');
                  filehander(divthis,at_id,count);
                }

                function getPr(w){
                  if(is_upload === true){
                    var upkey = $("#upkey").val();
                    setTimeout(function(){
                      $.ajax({
                        type:'get',
                        url:'/student/bigupload/getprogress',
                        data:{progress_key:upkey},
                        success:function(r){
                          if(r>=90){
                            is_upload = false;
                          }
                          w.content('<img src="<?=RES?>home/images/public/loading.gif"><br> Uploading：'+r+' %');
                          getPr(w);
                        }
                      });
                    },600);
                  }
                }

          function filehander(divthis,at_id,count){
                 var url = '/master/enrollment/upload_attachment/upload_files';
                 var courseid=<?=!empty($courseid)?$courseid:''?>;
                 var attachmentid=at_id;
                 var win = '';
                 'use strict';
            $('#fileupload_'+at_id).fileupload({
                    url: url,
                    dataType: 'json',
                    imitMultiFileUploads:count,
                    beforeSend:function(){
                      win = dialog({
								id:'cucasartdialog',
								content: '<img src="<?=RES?>home/images/public/loading.gif">'
							});
							win.showModal();
                  is_upload = true;
                  getPr(win);
                },
                done: function (e, r) {
                  is_upload = false;
                  if(r.result.state == 1){
                    var file = r.result.data;
                    var gethtml='';
                    gethtml+='<div class="line-one"><div class="logo-wrapper"><div class="logo"><a href="javascript:void();">';
                      
                      var t = new Date().getTime();
                      var tp = null;
                      file.thumbnailUrl = file.url;
                      if(file.ext == '.doc' || file.ext == '.docx'){
                        tp = '<?=RES?>home/images/dir_icon/doc.png';
                        file.thumbnailUrl = '<?=RES?>home/images/dir_icon/doc.png';
                      }else if(file.ext == '.pdf'){
                        tp = '<?=RES?>home/images/dir_icon/pdf.png';
                        file.thumbnailUrl = '<?=RES?>home/images/dir_icon/pdf.png';
                      }else if(file.ext == '.zip'){
                        tp = '<?=RES?>home/images/dir_icon/zip.png';
                        file.thumbnailUrl = '<?=RES?>home/images/dir_icon/zip.png';
                      }else if(file.ext == '.rar'){
                        tp = '<?=RES?>home/images/dir_icon/rar.png';
                        file.thumbnailUrl = '<?=RES?>home/images/dir_icon/rar.png';
                      }

                  gethtml+='<img src="'+(tp ? tp : file.url)+'#'+t+'" class="restaurant-logo">';
                  gethtml+='</a></div><div class="deliver-time-wrapper busy tooltip_on">'+file.truename+'</div>';
                  gethtml+='</div></div><div class="delete-action" style="display: none;">';
                  gethtml+='<a rel="true" class="eleme-icon delete" href="javascript:;"></a>';
                  gethtml+='</div>';
                    
                 // gethtml+="</div></div></div>";
					
					var new_upload_img = $('<div class="restaurant-block lite"></div>');
					
                   
                  $(divthis).parent().before(new_upload_img.append(gethtml));
                  win.close();
                  var up_mongodb_url="/master/enrollment/upload_attachment/save_upload_atta";
                  $.post(up_mongodb_url,{courseid:courseid,attachmentid:attachmentid,applyid:applyid,datas:r.result.data},function(data){
				 
                                  if(!data){
                                       alert('false');
                                    }else{
                                      $('#img_'+attachmentid).val('flag');
                                      $("a[rel='true']").attr('rel',data);

                                   }
                   });
				   new_upload_img.on('mouseover mouseout',function(event) {
					
					  if (event.type == 'mouseover') {
						$(this).find('.delete-action').show();
					  } else {
						$(this).find('.delete-action').hide();
					  }
					  var comfrm_win;
					   $(".delete-action a").on('click',function(){
							  var that = $(this);
							  var rel = that.attr('rel');
							  var delurl="/master/enrollment/upload_attachment/delFiles";
							  comfrm_win = dialog({
										title: 'Welcome',
										content: 'Are you sure to delete it?',
										ok: function () {
											 $.post(delurl,{id:rel},function(msg){
												  if(msg){
												 
													that.parent().parent().hide(300,function(){
													  $(this).remove();
													  comfrm_win.close().remove();
													});
												  }else{
													  alert('ERROR！');
												  }
											  });
										},
										cancel: function () {
											
											return true;
										}
									});
									comfrm_win.show();
					  
                });
				  });
                  }else{
                     d = dialog({
									content: ''+r.result.info+''
								});
								d.show();
								setTimeout(function () {
									d.close().remove();
								}, 5000);
                    return false;
                  }
                }
            }); 
                }

            $(function(){
                  $(".restaurant-block").on('mouseover mouseout',function(event) {
					
                  if (event.type == 'mouseover') {
                    $(this).find('.delete-action').show();
                  } else {
                    $(this).find('.delete-action').hide();
                  }
                });

                $(".delete-action a").on('click',function(){
                  var that = $(this);
                  var rel = that.attr('rel');
                  var delurl="/master/enrollment/upload_attachment/delFiles";
				  var d = dialog({
							title: 'Welcome',
							content: 'Are you sure to delete it?',
							ok: function () {
								var thats = this;
								this.title('Submitting...');
								setTimeout(function () {
									thats.close().remove();
								}, 5000);
								 $.post(delurl,{id:rel},function(msg){
									  if(msg){
									 
										that.parent().parent().hide(300,function(){
										  $(this).remove();
										});
									  }else{
										  alert('ERROR！');
									  }
								  });
							},
							cancel: function () {
								
								return true;
							}
						});
						d.show();
					  
                });
            });



                </script>

<div class="user-bottom min">
  <p><em class="Arial"><?=lang('footer_ba')?></em></p>
  <p><em class="Arial"><?=lang('footer_copy')?></em></p>
</div>

<!---->
</body>
</html>