<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
    <link rel="stylesheet" href="/resource/master/css/datepicker.css" />
    <script src="/resource/master/js/date-time/bootstrap-datepicker.min.js"></script>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="widget-header">
								<h5 class="widget-title">分配房间</h5>

								<div class="widget-toolbar">
									<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
										<i class="1 ace-icon bigger-125 fa fa-remove"></i>
									</a>
								</div>
							</div>
							<form class="form-horizontal" id="validation-form" method="post" action="/master/student/student/sub_room">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">入住时间</label>
							<div class="col-xs-12 col-sm-4">
								<div class="clearfix">
                                    <input type="text" data-date-format="yyyy-mm-dd" id="accstarttime" class="form-control date-picker" name="accstarttime" value="">
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">截止时间:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="clearfix">
                                    <input type="text" data-date-format="yyyy-mm-dd" id="endtime" class="form-control date-picker" name="endtime" value="">
                                </div>

							</div>
						</div>
                                <input type="hidden" name="campid" value="<?=$campusid?>">
                                <input type="hidden" name="buildingid" value="<?=$bulidingid?>">
                                <input type="hidden" name="floor" value="<?=$floor?>">
                                <input type="hidden" name="roomid" value="<?=$roomid?>">
                                <input type="hidden" name="userid" value="<?=$userid?>">
						<div class="modal-footer center">
							<button id="tijiao" type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
								提交
							</button>
							<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
								取消
							</button>
						</div>
						</form>
					</div>
				</div>
			</div>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: false,
		rules: {
			name: {
				required: true,
				
			},

			enname: {
				required: true
			},
			price: {
				required: true
				
			},
			state: 'required'
		},


		highlight: function (e) {
			$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
		},

		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
			$(e).remove();
		},

		errorPlacement: function (error, element) {
			if(element.is(':checkbox') || element.is(':radio')) {
				var controls = element.closest('div[class*="col-"]');
				if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
				else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
			}
			else if(element.is('.select2')) {
				error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
			}
			else if(element.is('.chosen-select')) {
				error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
			}
			else error.insertAfter(element.parent());
		},

		submitHandler: function (form) {
			var data=$(form).serialize();

	
			$.ajax({
//				beforeSend:function (){
//					$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>正在提交');
//					$('#tijiao').attr({
//						disabled:'disabled',
//					});
//				},
				url: $(form).attr('action'),
				type: 'POST',
				dataType: 'json',
				data: data,
			})
			.done(function(r) {
				if(r.state==1){
					pub_alert_success();
					window.location.href="/master/student/student";
				}
                if(r.state==0){
                    pub_alert_error('请正确填写内容');
                }
			})
			.fail(function() {

				pub_alert_error();
			})
		}
	});
});
</script>
<!--日期插件-->
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
