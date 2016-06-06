<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												发送e-offer
											</div>
										</div>

										<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/change_offer_status/send_e_offer" >
											<div class="widget-main">
											<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">请选择模版:</label>
									<div class="col-xs-12 col-sm-6">
										<select name="m" id="m" class="col-sm-9">
										<option value="">--请选择--</option>
										<?php if(!empty($result)){?>
											<?php foreach($result as $k => $v){?>
											<option value="<?=$v['id']?>"><?=$v['name']?></option>
											<?php }?>

										<?php }?>
										</select>
									</div>
								</div>

										</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开学时间:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="opening_date" class="form-control date-picker" name="opening_date" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">报到截止时间:</label>

									<div class="col-xs-12 col-sm-4">
										<div class="input-group">
											<input type="text" data-date-format="yyyy-mm-dd" id="report_end_time" class="form-control date-picker" name="report_end_time" value="">
											<span class="input-group-addon">
												<i class="fa fa-calendar bigger-110"></i>
											</span>
										</div>
									</div>
								</div>

								<div class="center">
									<button class="btn btn-sm btn-success" type="button" onclick="preview(<?=!empty($id)?$id:''?>)">
										<i class="ace-icon fa fa-eye icon-on-right bigger-110"></i>
										预览
									</button>
								</div>
								<input type="hidden" name="id" value="<?=!empty($id)?$id:''?>">
								<div class="modal-footer center">
									<button type="button" class="btn btn-sm btn-success" onclick="save()" id="sub"><i class="ace-icon fa fa-check"></i> Submit</button>
									<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>

								 </div>
									
									
									</form>
								</div>
							</div>
</div>
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
function preview(id){
	var m = $('#m').val();
	if(m == ''){
		alert('请选择模版');
		return false;
	}
	var opening_date=$.trim($('#opening_date').val());
	var report_end_time=$.trim($('#report_end_time').val());
	if(opening_date!=''&&report_end_time!=''){
		window.location.href='/master/enrollment/change_offer_status/send_e_offer_preview?id='+id+'&m='+m+'&opening_date='+opening_date+'&report_end_time='+report_end_time;
	}else{
		pub_alert_error('时间不能为空');
	}
	

}



function save(){
var h = '<i class="ace-icon fa fa-check">Process...</i>';
	$('#sub').html(h);
	var m = $('#m').val();
	if(m == ''){
		alert('请选择模版');
		return false;
	}
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/enrollment/change_offer_status/send_e_offer',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state == 1){
			 pub_alert_success(r.info);
			//window.open('/master/enrollment/change_offer_status/print_offer?id='+r.data.id+'&m='+r.data.m);
			window.location.reload();
		}else{
			pub_alert_error();
		}
	})
	.fail(function() {
		pub_alert_error();
	})
}
</script>
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

