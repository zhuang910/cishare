<?php $this->load->view('student/headermy');?>
<link href="<?=RES?>home/css/thumbnail.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/select2.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.sticky.js"></script>

<script src="<?=RES?>home/js/plugins/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?=RES?>home/js/plugins/uploadify/uploadify.css">
 
<div class="width_925 clearfix">
	<?php $this->load->view('student/apply_coursename')?>
</div>
<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<?php $this->load->view('student/apply_left')?>
	
	</div>
	<div class="applyonline-2-main">
		<div class="f_l applyonline-3-left">
			<dl>
				<dt><?=lang('notice')?></dt>
				<dd>1.<?=lang('notice_1')?></dd>
				<dd>2.<?=lang('notice_2')?></dd>
				<dd>3.<?=lang('notice_3')?> </dd>
				<dd style="background:none; padding-bottom:0px;">4.<?=lang('notice_4')?></dd>
			</dl>
		</div>
		<!--<form name="myform" id="myform" method="post"  enctype="multipart/form-data">
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
				<div class="applyonline-2-btn-1 f_l"><a href="javascript:;" onclick="Previous()"><?=lang('apply_prev')?></a></div>
				<div class="redbtn"><a href="javascript:;" onclick="Next()"><?=lang('apply_next')?></a></div>
				
			</div>
		</div>
		</form>-->
		
		<form name="atta_form" id="atta_form" method="post"  enctype="multipart/form-data">
		<div class="f_r applyonline-left-nav-width624">
			<div class="applyonline-3-right-title"><?=lang('uploadpay')?></div>
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
						<p class="applyonline-3-right-left-title-word"><?=!empty($value['des'])?$value['des']:''?> <?=!empty($value['FileDownload'])?'<a href="'.$value['FileDownload'].'" target="_blank">下载附件</a>':''?></p>
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
										  <a href="/download?path=<?=$v['url']?>&file=<?=$v['truename']?>" title="点击下载">
											<img src="<?=$v['thumbnailUrl'].'#'.time()?>" class="restaurant-logo"></a>
										</div>
										<div class="deliver-time-wrapper busy tooltip_on" title="<?=$v['truename']?>"><?=$v['truename']?></div>
									  </div>
									</div>
									<div class="delete-action" style="display: none;">
									  <a rel="<?=$v['id']?>" class="eleme-icon delete" href="javascript:;"></a>
									</div>
								  </div>
								   <input type="hidden" name="img-<?=$value['aTopic_id']?>" value= '<?=!empty($v['thumbnail_path'])?'flag':''?>' >
							 
						  <?php }}}?>
					 
						<div style="width: 125px; height: 125px; float: left;margin-left: 10px" data-attid="<?=$value['aTopic_id']?>">
						<a href="javascript:void(0);" class="FPUploadBtn" id="FpUpload_<?=$value['aTopic_id']?>" onclick="upfile(this,<?=$value['aTopic_id']?>,10);"><img alt="" src="<?=RES?>home/images/user/applyonline-3-right-plus.png"></a>
						
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
				<div class="applyonline-2-btn-1 f_l"><a href="javascript:;" onclick="Previous()"><?=lang('apply_prev')?></a></div>
				<div class="redbtn"><a href="javascript:;" onclick="do_submit_infomation()"><?=lang('apply_next')?></a></div>
			</div>
		</div>
		</form>
		
	</div>
</div>


<script>
  /*前一步*/
        function Previous(){
          window.location.href='/<?=$puri?>/student/fillingoutforms/apply?applyid='+applyid;

        }

    var upkey_lp = 0;
    var is_upload = false;
    var win = '';

function is_up_check(){
	var u_files = $(".applyonline-3-right-left-title-des");
	var is_go = true;
	u_files.each(function(){
		var is = $(this).find('span');
		var p = $(this).parents().eq(1);
		if(is.html() != undefined){
			var is_u = p.find(".delete-action").length;
			if(is_u == 0){
				is_go = false;
			}
		}
	});

	if(!is_go){
		zjj.box_alert_error('请上传 \' <font color="red">*</font> \' 标记项',null,2,true);
		return false;
	}
	return true;
}

function do_submit_infomation(){
	var windialog=[];

	var is = is_up_check();
	if(is === false)
		return false;

	 window.location.href='/<?=$puri?>/student/apply/make_paymeznt?applyid='+applyids;
}
</script>

<script>
    var courseid=<?=!empty($courseid)?$courseid:''?>;
    var applyid = '<?=!empty($apply_info['id'])?cucas_base64_encode($apply_info['id']):''?>';
    var applyids = '<?=!empty($apply_info['id'])?cucas_base64_encode($apply_info['id'].'-cucas'):''?>';
	<?php $timestamp = time();?>
	$('.FPUploadBtn').uploadify({
		//'buttonClass' : 'FPUploadBtn FPUploadBtnR',
		'buttonImage' : '<?=RES?>home/images/user/applyonline-3-right-plus.png',
		'buttonText' : '',
		'height'   : 78,
		'width'   : 78,
		'multi'    : false,
		'fileSizeLimit' : '8192KB',
		'fileObjName' : 'files',
		'fileTypeExts' : '*.rar; *.pdf; *.jpg; *.png; *.gif; *.doc; *.docx; *.xls; *.xlsx; *.ppt;',
		'formData'     : {
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
		},
		'queueSizeLimit' : 1,
		'swf'      : '<?=RES?>home/js/plugins/uploadify/uploadify.swf',
		'uploader' : '/uploadify/do_upload',
		'onUploadSuccess' : function(r,data,resource) {
			is_upload = false;
			file = [];
			try
			{
				file = $.parseJSON(data);
			}
			catch (e)
			{
				zjj.box_alert_error(data,null,2,true);
				return false;
			}

				var gethtml='';
				gethtml+='<div class="line-one"><div class="logo-wrapper"><div class="logo"><a href="/download?path='+file.url+'&file='+file.truename+'">';

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
				}else if(file.ext == '.xls' || file.ext == '.xlsx'){
					tp = '<?=RES?>home/images/dir_icon/xlxs.png';
					file.thumbnailUrl = '<?=RES?>home/images/dir_icon/xlxs.png';
				}else if(file.ext == '.xls' || file.ext == '.xlsx'){
					tp = '<?=RES?>home/images/dir_icon/xlxs.png';
					file.thumbnailUrl = '<?=RES?>home/images/dir_icon/xlxs.png';
				}

				gethtml+='<img src="'+(tp ? tp : file.url)+'#'+t+'" class="restaurant-logo">';
				gethtml+='</a></div><div class="deliver-time-wrapper busy tooltip_on">'+file.truename+'</div>';
				gethtml+='</div></div><div class="delete-action" style="display: none;">';
				gethtml+='<a rel="true" class="eleme-icon delete" href="javascript:;"></a>';
				gethtml+='</div>';


				var new_upload_img = $('<div class="restaurant-block lite"></div>');
				$("#"+r.id).parents().eq(1).before(new_upload_img.append(gethtml));
				var at_id = $("#"+r.id).parents().eq(1).attr('data-attid');
               	var attachmentid=at_id;
				var up_mongodb_url="/<?=$puri?>/student/apply/save_upload_atta";
				$.post(up_mongodb_url,{courseid:courseid,applyid:applyid,attachmentid:at_id,datas:file},function(data){
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
				});

				var comfrm_win;
				new_upload_img.find(".delete-action a").on('click',function(){
					var that = $(this);
					var rel = that.attr('rel');
					var delurl="/<?=$puri?>/student/apply/delFiles";
					zjj.box_alert_confirm('确定删除该文件吗?',function(){
						$.post(delurl,{id:rel},function(msg){
							if(msg){
								that.parent().parent().hide(300,function(){
									$(this).remove();
									comfrm_win.close().remove();
								});
							}else{
								alert('错误！');
							}
						});
					});
				});

		},
		'itemTemplate' : '<div id="${fileID}" class="uploadify-queue-item">\
					        	Uploading <span class="data"></span>\
								<div class="uploadify-progress">\
									<div class="uploadify-progress-bar"><!--Progress Bar--></div>\
	</div>\
	</div>'
	});

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
			var delurl="/<?=$puri?>/student/apply/delFiles";
			zjj.box_alert_confirm('确定删除该文件吗?',function(){
				var thats = this;
				this.title('提交中...');
				setTimeout(function () {
					thats.close().remove();
				}, 5000);
				$.post(delurl,{id:rel},function(msg){
					if(msg){
						that.parent().parent().hide(300,function(){
							$(this).remove();
						});
					}else{
						alert('错误！');
					}
				});
			});
		});
	});
</script>
<!--
<script>
        var applyid = '<?=!empty($apply_info['id'])?cucas_base64_encode($apply_info['id']):''?>';
        var upkey_lp = 0;
        var is_upload = false;
        /*前一步*/
        function Previous(){
          window.location.href='/<?=$puri?>/student/fillingoutforms/apply?applyid='+applyid;

        }

        /*下一步*/
        function Next(){
          //验证表单
           var  ajaxCallUrl= '/<?=$puri?>/student/apply/validatorsaveMateerial?applyid='+applyid;
        $.ajax({
               cache: true,
               type: "GET",
               url:ajaxCallUrl,
               data:$('#myform').serialize(),
               dataType:'json',
               async: false,
               error: function(request) {
					 var d = dialog({
						content: 'Error'
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 5000);
               },
               success: function(r) {
                  if(r.state == 1){
                    window.location.href='/<?=$puri?>/student/apply/make_paymeznt?applyid='+applyid;
                  }else{
						 var d = dialog({
							content: ''+r.info+''
						});
						d.show();
						setTimeout(function () {
							d.close().remove();
						}, 5000);
                  }
               }
           });
         //  window.location.href='/apply/apply/make_paymeznt?applyid='+applyid;
        }

                 $("#appBtnSN").on('click',function(msg){
                         window.location.href='/<?=$puri?>/fillingoutforms/apply/2';  
                     });

                 $("#appBtnCC").on('click',function(msg){

                   //验证表单
                     var  ajaxCallUrl= '/<?=$puri?>/studnet/index/validatorsaveMateerial';
                  $.ajax({
                         cache: true,
                         type: "POST",
                         url:ajaxCallUrl,
                         data:$('#myform').serialize(),
                         dataType:'json',
                         async: false,
                         error: function(request) {
							 var d = dialog({
								content: 'Error'
							});
							d.show();
							setTimeout(function () {
								d.close().remove();
							}, 5000);
                         },
                         success: function(r) {
                            if(r.state == 1){
                              window.location.href=r.data;
                            }else{
								  var d = dialog({
									content: ''+r.info+''
								});
								d.show();
								setTimeout(function () {
									d.close().remove();
								}, 5000);
                            }
                         }
                     });
                     return false;
                 });
          
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
                        url:'/<?=$puri?>/student/bigupload/getprogress',
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
                 var url = '/<?=$puri?>/student/apply/upload_files';
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
                  var up_mongodb_url="/<?=$puri?>/student/apply/save_upload_atta";
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
					  
					   $(".delete-action a").on('click',function(){
							  var that = $(this);
							  var rel = that.attr('rel');
							  var delurl="/<?=$puri?>/student/apply/delFiles";
							  var d = dialog({
										title: '<?=lang('welcome')?>',
										content: '<?=lang('del_confirm')?>',
										ok: function () {
											var thats = this;
											this.title('<?=lang('submiting')?>');
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
									setTimeout(function () {
										d.close().remove();
									}, 5000);
					  
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
                  var delurl="/<?=$puri?>/student/apply/delFiles";
				  var d = dialog({
							title: '<?=lang('welcome')?>',
							content: '<?=lang('del_confirm')?>',
							ok: function () {
								var thats = this;
								this.title('<?=lang('submiting')?>');
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



                </script>-->
<?php $this->load->view('student/footer_no.php')?>