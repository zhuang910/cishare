<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑广告':'添加广告';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">信息管理</a>
	</li>
	<li>
		<a href="javascript:;">模块管理</a>
	</li>
	<li><a href="index">广告管理</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>	
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	广告管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green"><?=!empty($info)?'编辑广告':'添加广告'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="/master/cms/advance/save">
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">栏目名称:</label>

					<div class="col-xs-12 col-sm-4">
						<select name="columnid" id="columnid" onchange="msjt()">
							<option value="">请选择栏目...</option>
							<?php foreach($programaids as $value => $item){ ?>
							<option value="<?=$value?>" <?=!empty($info->columnid) && $info->columnid == $value ? 'selected' : ''?>><?=$item?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="title" name="title" value="<?=!empty($info->title) ? $info->title : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">外链地址:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="url" name="url" value="<?=!empty($info->url) ? $info->url : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">描述:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<textarea placeholder="请输入简介" rows="4" class="span5 ui-wizard-content" id="description" name="description"   style="width: 342px; height: 95px;"><?=!empty($info) ? $info->description : ''?></textarea>
						</div>
					</div>
				</div>

				<div class="space-2"></div>
			
			
				
			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">图片:</label>

				<div class="col-xs-6 col-sm-2">
					<div class="clearfix">
						<div id="load_img_zjj">
							<?php if(!empty($info->image)){?>
								<label class="ace-file-input ace-file-multiple">
									<span class="ace-file-container hide-placeholder selected">
										<span class="ace-file-name">
											<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==" class="middle" style="background-image: url(<?=$info->image?>); width: 145px; height: 150px;">
											<i class=" ace-icon fa fa-picture-o file-image"></i>
										</span>
									</span>
									<a class="remove" href="javascript:;" onclick="$('#upload_img_zjj').show();$('#load_img_zjj').hide()"><i class=" ace-icon fa fa-times"></i></a></label>
							<?php }?>
						</div>
						<div <?=!empty($info->image)?'style="display: none"':''?> id="upload_img_zjj">
							<input  type="file" name="imagefile" id="zyj_f" value=""/>
						</div>
					</div>
				</div>
			</div>

			
				
				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="orderby" name="orderby" value="<?=!empty($info->orderby) ? $info->orderby : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="state" class="form-control" name="state">
							<option value="1" <?=!empty($info) && $info->state == 1?'selected':''?>>启用</option>
							<option value="0"  <?=!empty($info) && $info->state == 0?'selected':''?>>禁用</option>
							
						</select>
					</div>
				</div>
			
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发布时间:</label>

					<div class="col-xs-12 col-sm-4">
						<div class="input-group">
							<input type="text" data-date-format="yyyy-mm-dd" id="createtime" class="form-control date-picker" name="createtime" value="<?=!empty($info->createtime)?date('Y-m-d',$info->createtime):date('Y-m-d',time())?>">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				
				<div class="space-2"></div>
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn btn-info">
						<i class="ace-icon fa fa-check bigger-110"></i>
							提交
					</button>
					<button class="btn" type="reset">
						<i class="ace-icon fa fa-undo bigger-110"></i>
							重置
					</button>
				</div>
			</form>

		</div>
	</div>
</div>
<!-- ace scripts -->
<script src="/resource/master/js/ace-extra.min.js"></script>
<script src="/resource//master/js/ace-elements.min.js"></script>
<script src="/resource//master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
			jQuery(function($) {
				var $form = $('#validation-form');
				//you can have multiple files, or a file input with "multiple" attribute
				var file_input = $form.find('input[type=file]');
				var upload_in_progress = false;

				file_input.ace_file_input({
					style : 'well',
					btn_choose : 'Select or drop files here',
					btn_change: null,
					droppable: true,
					thumbnail: 'large',
					
					maxSize: 2048000,//bytes
					allowExt: ["jpeg", "jpg", "png", "gif"],
					allowMime: ["image/jpg", "image/jpeg", "image/png", "image/gif"],

					before_remove: function() {
						if(upload_in_progress)
							return false;//if we are in the middle of uploading a file, don't allow resetting file input
						return true;
					},

					preview_error: function(filename , code) {
						//code = 1 means file load error
						//code = 2 image load error (possibly file is not an image)
						//code = 3 preview failed
					}
				})
				file_input.on('file.error.ace', function(ev, info) {
					if(info.error_count['ext'] || info.error_count['mime']) alert('未知格式，请选择：jpeg、jpg、png、gif 格式的文件');
					if(info.error_count['size']) alert('图片大小不能超过 2MB');
					
					//you can reset previous selection on error
					//ev.preventDefault();
					//file_input.ace_file_input('reset_input');
				});
				
				
				var ie_timeout = null;//a time for old browsers uploading via iframe
				
				$form.on('submit', function(e) {
					e.preventDefault();
					var files = file_input.data('ace_input_files');
					//if( !files || files.length == 0 ) return false;//no files selected
					
									
					var deferred ;
					if( "FormData" in window ) {
				
						//for modern browsers that support FormData and uploading files via ajax
						//we can do >>> var formData_object = new FormData($form[0]);
						//but IE10 has a problem with that and throws an exception
						//and also browser adds and uploads all selected files, not the filtered ones.
						//and drag&dropped files won't be uploaded as well
						
						//so we change it to the following to upload only our filtered files
						//and to bypass IE10's error
						//and to include drag&dropped files as well
						formData_object = new FormData();//create empty FormData object
						
						//serialize our form (which excludes file inputs)
						$.each($form.serializeArray(), function(i, item) {
							//add them one by one to our FormData 
							formData_object.append(item.name, item.value);							
						});
						//and then add files
						$form.find('input[type=file]').each(function(){
							var field_name = $(this).attr('name');
							//for fields with "multiple" file support, field name should be something like `myfile[]`

							var files = $(this).data('ace_input_files');
							if(files && files.length > 0) {
								for(var f = 0; f < files.length; f++) {
									formData_object.append(field_name, files[f]);
								}
							}
						});
	

						upload_in_progress = true;
						file_input.ace_file_input('loading', true);
						
						deferred = $.ajax({
							        url: $form.attr('action'),
							       type: $form.attr('method'),
							processData: false,//important
							contentType: false,//important
							   dataType: 'json',
							       data: formData_object
							/**
							,
							xhr: function() {
								var req = $.ajaxSettings.xhr();
								if (req && req.upload) {
									req.upload.addEventListener('progress', function(e) {
										if(e.lengthComputable) {	
											var done = e.loaded || e.position, total = e.total || e.totalSize;
											var percent = parseInt((done/total)*100) + '%';
											//percentage of uploaded file
										}
									}, false);
								}
								return req;
							},
							beforeSend : function() {
							},
							success : function() {
							}*/
						})

					}else {
						//for older browsers that don't support FormData and uploading files via ajax
						//we use an iframe to upload the form(file) without leaving the page

						deferred = new $.Deferred //create a custom deferred object
						
						var temporary_iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000));
						var temp_iframe = 
								$('<iframe id="'+temporary_iframe_id+'" name="'+temporary_iframe_id+'" \
								frameborder="0" width="0" height="0" src="about:blank"\
								style="position:absolute; z-index:-1; visibility: hidden;"></iframe>')
								.insertAfter($form)

						$form.append('<input type="hidden" name="temporary-iframe-id" value="'+temporary_iframe_id+'" />');
						
						temp_iframe.data('deferrer' , deferred);
						//we save the deferred object to the iframe and in our server side response
						//we use "temporary-iframe-id" to access iframe and its deferred object
						
						$form.attr({
									  method: 'POST',
									 enctype: 'multipart/form-data',
									  target: temporary_iframe_id //important
									});

						upload_in_progress = true;
						file_input.ace_file_input('loading', true);//display an overlay with loading icon
						$form.get(0).submit();
						
						
						//if we don't receive a response after 30 seconds, let's declare it as failed!
						ie_timeout = setTimeout(function(){
							ie_timeout = null;
							temp_iframe.attr('src', 'about:blank').remove();
							deferred.reject({'status':'fail', 'message':'Timeout!'});
						} , 30000);
					}


					////////////////////////////
					//deferred callbacks, triggered by both ajax and iframe solution
					deferred
					.done(function(result) {//success
						//format of `result` is optional and sent by server
						//in this example, it's an array of multiple results for multiple uploaded files
						var message = '';
						for(var i = 0; i < result.length; i++) {
							if(result[i].status == 'OK') {
								message += "File successfully saved. Thumbnail is: " + result[i].url
							}
							else {
								message += "File not saved. " + result.message;
							}
							message += "\n";
						}
						if(result.state == 1){
								pub_alert_success();
								window.location.href="<?=$zjjp?>advance";
						}else{
							pub_alert_error();
						}
					})
					.fail(function(result) {//failure
						alert("There was an error");
					})
					.always(function() {//called on both success and failure
						if(ie_timeout) clearTimeout(ie_timeout)
						ie_timeout = null;
						upload_in_progress = false;
						file_input.ace_file_input('loading', false);
					});

					deferred.promise();
				});


				//when "reset" button of form is hit, file field will be reset, but the custom UI won't
				//so you should reset the ui on your own
				$form.on('reset', function() {
					$(this).find('input[type=file]').ace_file_input('reset_input_ui');
				});


				if(location.protocol == 'file:') alert("For uploading to server, you should access this page using 'http' protocal, i.e. via a webserver.");

			});
		</script>

<!-- script -->

<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>

<script type="text/javascript">
	function msjt(){
		var columnid = $('#columnid').val();
		if(columnid == 105){
			$('#msjt').show();
		}else{
			$('#msjt').hide();
		}
	}
</script>
<?//php $this->load->view('master/public/footer');?>