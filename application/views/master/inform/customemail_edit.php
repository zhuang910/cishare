<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">基本设置</a>
	</li>
	<li>
		<a href="javascript:;">邮件设置</a>
	</li>
	<li  class="avtive"><a href="javascript:;">自定义发送</a></li>
	
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>


<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		自定义发送
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green">自定义发送
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>
			<form class="form-horizontal" id="mail-form" method="post">
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="title" name="title" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发件人:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="reply_to" name="reply_to" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
						<label for="smtp_pass" class="col-sm-3 control-label no-padding-right"> 发件人配置 </label>
						<div class="col-sm-8">
							<select  id="addresserset" name="addresserset" aria-required="true" aria-invalid="false">
									<option value="0">—请选择—</option>
										<?php foreach($addresserset as $k=>$v):?>
										<option value="<?php echo $v['id']?>"><?php echo $v['smtp_user']?></option>
										<?php endforeach;?>
								</select>
						</div>
					</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-tags">收件人</label>

					<div class="col-sm-9">
						<!-- #section:plugins/input.tag-input -->
						<div class="inline">
							<input type="text" name="sentto" id="form-field-tags"  placeholder="Enter tags ..." />
						</div>

						<!-- /section:plugins/input.tag-input -->
					</div>
				</div>
				<div class="space-2"></div>

				
			
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">内容:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor1"></div>
					</div>
				</div>

				<div class="space-2"></div>
				<div class="col-md-offset-3 col-md-9">
					<a onclick="send_email()" class="btn btn-info" data-last="Finish">
					<i class="ace-icon fa fa-check bigger-110"></i>
					发送
					</a>
					<a class="btn" type="reset">
					<i class="ace-icon fa fa-undo bigger-110"></i>
					重置
					</a>
				</div>
			</form>

		</div>
	</div>
</div>
	
			
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>			

		<!-- page specific plugin scripts -->
		<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
		<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>

		<script src="<?=RES?>master/js/bootstrap-tag.min.js"></script>
		
<script type="text/javascript">
jQuery(function($){
	function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			//console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	}
		$('#editor1').ace_wysiwyg({
				toolbar:
				[
					'font',
					null,
					'fontSize',
					null,
					{name:'bold', className:'btn-info'},
					{name:'italic', className:'btn-info'},
					{name:'strikethrough', className:'btn-info'},
					{name:'underline', className:'btn-info'},
					null,
					{name:'insertunorderedlist', className:'btn-success'},
					{name:'insertorderedlist', className:'btn-success'},
					{name:'outdent', className:'btn-purple'},
					{name:'indent', className:'btn-purple'},
					null,
					{name:'justifyleft', className:'btn-primary'},
					{name:'justifycenter', className:'btn-primary'},
					{name:'justifyright', className:'btn-primary'},
					{name:'justifyfull', className:'btn-inverse'},
					null,
					{name:'createLink', className:'btn-pink'},
					{name:'unlink', className:'btn-pink'},
					null,
					{name:'insertImage', className:'btn-success'},
					null,
					'foreColor',
					null,
					{name:'undo', className:'btn-grey'},
					{name:'redo', className:'btn-grey'}
				],
				'wysiwyg': {
					fileUploadError: showErrorAlert
				}
			}).prev().addClass('wysiwyg-style2');
		var tag_input = $('#form-field-tags');
				try{
					tag_input.tag(
					  {
						placeholder:tag_input.attr('placeholder'),
						//enable typeahead by specifying the source array
						source: ace.vars['US_STATES'],//defined in ace.js >> ace.enable_search_ahead
						/**
						//or fetch data from database, fetch those that match "query"
						source: function(query, process) {
						  $.ajax({url: 'remote_source.php?q='+encodeURIComponent(query)})
						  .done(function(result_items){
							process(result_items);
						  });
						}
						*/
					  }
					);
			
					//programmatically add a new
					var $tag_obj = $('#form-field-tags').data('tag');
					
				}
				catch(e) {
					//display a textarea for old IE, because it doesn't support this plugin or another one I tried!
					tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();
					//$('#form-field-tags').autosize({append: "\n"});
				}
})
function send_email(){
	var data=$('#mail-form').serialize();
	var content=$('#editor1').html();
	$.ajax({
		url: '<?=$zjjp?>customemail/insert',
		type: 'POST',
		dataType: 'json',
		data: data+'&content='+content,
	})
	.done(function(r) {
		if(r.state == 1){
				pub_alert_success(r.info);
				setTimeout('window.location.reload()',800);
			}else{
				pub_alert_error(r.info);
			}
	})
	.fail(function() {
		console.log("error");
	})

}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>