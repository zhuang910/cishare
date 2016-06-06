<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">考勤数据导入</h5>

				<div class="widget-toolbar">
					<a data-dismiss="modal" aria-hidden="true" data-action="collapse" href="#">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-main">
					<!-- #section:plugins/fuelux.wizard -->
					<div id="fuelux-wizard" data-target="#step-container">
						<!-- #section:plugins/fuelux.wizard.steps -->
						<ul class="wizard-steps">
							<li data-target="#step1" class="active">
								<span class="step">1</span>
								<span class="title">导出模板编辑数据</span>
							</li>

							<li data-target="#step2">
								<span class="step">2</span>
								<span class="title">导入模板验证数据</span>
							</li>
						</ul>

						<!-- /section:plugins/fuelux.wizard.steps -->
					</div>

					<hr />

					<!-- #section:plugins/fuelux.wizard.container -->
					<div class="step-content pos-rel" id="step-container">
						<div class="step-pane active" id="step1">
							<form class="form-horizontal" id="conditions" method="post" action="<?=$zjjp?>checking/checking/tochaneltenplate">
								<!-- #section:elements.form.input-state -->
								<div class="form-group">
									<label for="majorid" class="col-xs-12 col-sm-3 control-label no-padding-right">专业</label>
									<div class="col-xs-12 col-sm-5">
										<select  id="majorid" name="majorid" aria-required="true" aria-invalid="false" onchange="major()">
											<option value="0">—请选择—</option>
											<?php foreach($mdata as $k=>$v):?>
												<option value="<?php echo $v->id?>" <?=!empty($info)&&$v->id==$info->majorid ? 'selected' :''?>><?php echo $v->name?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="nowterm" class="col-xs-12 col-sm-3 control-label no-padding-right">学期</label>
									<div class="col-xs-12 col-sm-5">
										<select id="nowterm" name="nowterm" aria-required="true" aria-invalid="false" onchange="term()">
											<option value="0">—请选择—</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="squad" class="col-xs-12 col-sm-3 control-label no-padding-right">班级</label>
									<div class="col-xs-12 col-sm-5">
										<select id="squad" name="squadid" aria-required="true" aria-invalid="false" onchange="c()">
											<option value="0">—请选择—</option>
										</select>
									</div>
								</div>
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-xs btn-danger">
										<i class="ace-icon fa fa-download bigger-110"></i>
										导出模板
									</button>
								</div>
							</form>
						</div>

						<div class="step-pane" id="step2">
							<div class="widget-body-inner" style="display: block;">
								<div class="widget-main" style="height: 230px">
									<form id="validation-form" method="post" action="<?=$zjjp?>checking/checking/upload_excel">
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

					<!-- /section:plugins/fuelux.wizard.container -->
					<hr />
					<div class="wizard-actions">
						<!-- #section:plugins/fuelux.wizard.buttons -->
						<button class="btn btn-prev">
							<i class="ace-icon fa fa-arrow-left"></i>
							上一步
						</button>

						<button class="btn btn-success btn-next" data-last="完成">
							下一步
							<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
						</button>

						<!-- /section:plugins/fuelux.wizard.buttons -->
					</div>

					<!-- /section:plugins/fuelux.wizard -->
				</div><!-- /.widget-main -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(function($) {
	var $validation = false;
	$('#fuelux-wizard')
		.ace_wizard({
			//step: 2 //optional argument. wizard will jump to step "2" at first
		})
		.on('change' , function(e, info){
			if(info.step == 1 && $validation) {
				if(!$('#validation-form').valid()) return false;
			}
		})
		.on('finished', function(e) {
			bootbox.dialog({
				message: "Thank you! Your information was successfully saved!",
				buttons: {
					"success" : {
						"label" : "OK",
						"className" : "btn-sm btn-primary"
					}
				}
			});
		}).on('stepclick', function(e){
			//e.preventDefault();//this will prevent clicking and selecting steps
		});

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
<!--日期插件-->
