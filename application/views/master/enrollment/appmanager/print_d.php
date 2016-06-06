
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												打印邮寄单
											</div>
										</div>

										<form id="pub_form" class="form-horizontal" id="validation-form" method="get" action="/master/enrollment/change_offer_status/print_address" target="_blank">
										

								<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">请选择模版:</label>
									<div class="col-xs-12 col-sm-9">
										<select name="m" id="m">
										<option value="">--请选择--</option>
										<?php if(!empty($result)){?>
											<?php foreach($result as $k => $v){?>
											<option value="<?=$v['id']?>"><?=$v['name']?></option>
											<?php }?>
										
										<?php }?>
										</select>
									</div>
								</div>
								<input type="hidden" name="id" value="<?=!empty($id)?$id:''?>">
								<div class="modal-footer center">
									<button type="submit" class="btn btn-sm btn-success" id="sub"><i class="ace-icon fa fa-check"></i> Submit</button>
									<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
									
								 </div>
									
									</form>
								</div>
							</div>
</div>
<script type="text/javascript">

function save(){
var h = '<i class="ace-icon fa fa-check">Process...</i>';
	$('#sub').html(h);
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/enrollment/change_offer_status/print_ad',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state == 1){
			
			window.open('/master/enrollment/change_offer_status/print_address?id='+r.data.id+'&m='+r.data.m);
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