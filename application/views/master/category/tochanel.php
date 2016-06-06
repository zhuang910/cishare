<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">导入</h5>

				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>

				<div class="widget-toolbar no-border">
					<a class="btn btn-xs bigger btn-danger" href="<?=$zjjp?>student/student/tochaneltenplate">
						下载模板
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 230px">
					<span>导入学生信息</span>
						<form id="validation-form" method="post" action="<?=$zjjp?>student/student/upload_excel">

							<input type="file" name="file">

							<button type="submit" class="btn btn-sm btn-primary">上传</button>

							<div id="profile2" class="tab-pane">
								<div class="scrollable" data-position="left"></div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(function($) {
		$('.scrollable').each(function () {
			var $this = $(this);
			$(this).ace_scroll({
				size: 140
				//styleClass: 'scroll-left scroll-margin scroll-thin scroll-dark scroll-light no-track scroll-visible'
			});
		});

		var $form = $('#validation-forms');
		var file_input = $form.find('input[type=file]');
		var upload_in_progress = false;

		file_input.ace_file_input({
			no_file : '未选择文件',
			btn_choose : '浏览',
			btn_change: '重选',
			droppable:false,
			onchange:null,
			thumbnail:false,
			maxSize: 8192000,//bytes
			allowExt: ["xlsx", "xls"],

			before_remove: function() {
				if(upload_in_progress)
					return false;
				return true;
			}
		})
		file_input.on('file.error.ace', function(ev, info) {
			if(info.error_count['ext'] || info.error_count['mime']) pub_alert_error('选择文件格式不正确，请选择 xlsx,xls');
			if(info.error_count['size']) pub_alert_error('选择问价大小不能超过 8 MB！');
		});

		var ie_timeout = null;

		$form.on('submit', function(e) {
			e.preventDefault();

			var files = file_input.data('ace_input_files');
			if( !files || files.length == 0 ) return false;//no files selected

			var deferred ;
			if( "FormData" in window ) {
				formData_object = new FormData();

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
					dataType: 'text',
					data: formData_object
				})
			}

			////////////////////////////
			//deferred callbacks, triggered by both ajax and iframe solution
			deferred
				.done(function(result) {//success
					$("#profile3 .scrollables").html(result);
				})
				.fail(function(result) {//failure
					alert("文件上传失败");
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
	jQuery(function($) {
		$('.scrollable').each(function () {
			var $this = $(this);
			$(this).ace_scroll({
				size: 140
				//styleClass: 'scroll-left scroll-margin scroll-thin scroll-dark scroll-light no-track scroll-visible'
			});
		});

		var $form = $('#validation-form');
		var file_input = $form.find('input[type=file]');
		var upload_in_progress = false;

		file_input.ace_file_input({
			no_file : '未选择文件',
			btn_choose : '浏览',
			btn_change: '重选',
			droppable:false,
			onchange:null,
			thumbnail:false,
			maxSize: 8192000,//bytes
			allowExt: ["xlsx", "xls"],

			before_remove: function() {
				if(upload_in_progress)
					return false;
				return true;
			}
		})
		file_input.on('file.error.ace', function(ev, info) {
			if(info.error_count['ext'] || info.error_count['mime']) pub_alert_error('选择文件格式不正确，请选择 xlsx,xls');
			if(info.error_count['size']) pub_alert_error('选择问价大小不能超过 8 MB！');
		});

		var ie_timeout = null;

		$form.on('submit', function(e) {
			e.preventDefault();

			var files = file_input.data('ace_input_files');
			if( !files || files.length == 0 ) return false;//no files selected

			var deferred ;
			if( "FormData" in window ) {
				formData_object = new FormData();

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
					dataType: 'text',
					data: formData_object
				})
			}

			////////////////////////////
			//deferred callbacks, triggered by both ajax and iframe solution
			deferred
				.done(function(result) {//success
					$("#profile2 .scrollable").html(result);
				})
				.fail(function(result) {//failure
					alert("文件上传失败");
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