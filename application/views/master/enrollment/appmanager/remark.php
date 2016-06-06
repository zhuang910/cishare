
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">&times;</span>
						</button>
						添加失败原因
					</div>
				</div>

				<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/change_app_status/submit_app_status" >
					
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">失败原因:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<textarea name="cause"></textarea>	
						</div>
					</div>
				</div>
				<input type="hidden" id="c_id" name="id" value="<?=$id?>">
				<div class="space-2"></div>	
				<div class="modal-footer center">
					<button type="button" class="btn btn-sm btn-success" onclick="save()" id="sub"><i class="ace-icon fa fa-check"></i> Submit</button>
					<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
				 </div>
					
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
function save(){
	var id = $('#c_id').val();
	var applyid=<?=$applyid?>;
	var orderid=<?=$orderid?>;
	var state=<?=$state?>;
	var userid=<?=$userid?>;
	var h = '<i class="ace-icon fa fa-check">Process...</i>';
	$('#sub').html(h);
	var data=$('#pub_form').serialize();
	$.ajax({
		
		url: '/master/finance/credentials_acc/save_remark',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state == 1){
			// pub_alert_confirm(');
			// pub_alert_success();
			// window.location.reload();
			$.ajax({
				url: '/master/enrollment/appmanager/goproof?id='+id+'&applyid='+applyid+'&orderid='+orderid+'&state='+state+'&userid='+userid,
				type: 'GET',
				dataType: 'json',
				data: {},
			})
			.done(function(r) {
				if(r.state==1){
					pub_alert_success();
					window.location.reload();
				}
			})
			.fail(function() {
				console.log("error");
			})
			
		}else{
			pub_alert_error();
		}
	})
	.fail(function() {
		pub_alert_error();
	})
}
</script>