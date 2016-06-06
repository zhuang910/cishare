<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">类别缴费</h4></div>
				<!---->
					<form class="form-horizontal" id="validation-form" method="post">
							
						<div class="form-group" id="insert">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">费用类型:</label>
							
							<div class="col-xs-12 col-sm-4">
								<select onchange="chaange_select(<?=$userid?>)" id="type" class="form-control" name="type">
									<option value="0" >-请选择-</option>
									<option value="tuition" >学费</option>
									<option value="electric" >电费</option>
									<option value="book" >书费</option>
									<option value="acc" >住宿费</option>
									<option value="insurance" >保险费</option>
									<option value="acc_pledge" >住宿押金</option>
								</select>
							</div>
						</div>
						<div class="space-2"></div>
						<input type="hidden" name="userid" value="<?=$userid?>">
						<div class="modal-footer center">
							<a onclick="sub_mit()" id="tijiao"  class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
								缴费
							</a>
							<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
								取消
							</button>
						</div>
					</form>
				<!---->
		</div>
	</div>
</div>
<script type="text/javascript">
function major_term (majorid,userid){
	var term=$('#term').val();
	$.ajax({
		url: '/master/charge/pay/get_major_term_tuition?majorid='+majorid+'&term='+term+'&userid='+userid,
		type: 'GET',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			$('#is_term_tuition').remove();
			var strs='';
			if(r.data.is==1){
				strs+='<span>已经交过<span>';
			}else{
				strs+='<span>为交过<span>';
			}
			var str='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">当前学期学费:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="text" name="term_tuition" value="'+r.data.term_tuition.tuition+'">'+strs+'</div></div><div class="space-2"></div>';
			
			$('#insert_term').after(str);
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function chaange_select(userid){
	var type=$('#type').val();
	if(type=='tuition'){
		$.ajax({
			url: '/master/charge/pay/type_tuition?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: type,
		})
		.done(function(r) {
			if(r.state==1){
				$('#box').remove();
				var str='<div id="box"><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业名字:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="hidden" value="'+r.data.id+'" name="majorid">';
				str+='<input type="text" name="majorname" value="'+r.data.name+'"></div></div><div class="space-2"></div>';
				str+='<div class="form-group" id="insert_term"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学期:</label><div class="col-xs-12 col-sm-4">';
				str+='<select id="term" onchange="major_term('+r.data.id+','+userid+')" name="term">';
				str+='<option value="0">-请选择-</option>';
				for(i=1;i<r.data.termnum;i++){
					str+='<option value="'+i+'">第'+i+'学期</option>';
				}
				str+='</div></div><div class="space-2"></div></div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}
	if(type=='electric'){
		$('#box').remove();
		var str='<div id="box"><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">电费费用:</label><div class="col-xs-12 col-sm-4">';
		str+='<input type="text" name="electric" value=""></div></div><div class="space-2"></div></div>';
		$('#insert').after(str);
	}
	if(type=='acc'){
		$.ajax({
			url: '/master/charge/pay/type_acc?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: type,
		})
		.done(function(r) {
			if(r.state==1){
				$('#box').remove();
				var str='<div id="box">';
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">住宿费用:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="text" name="acc" value=""></div></div><div class="space-2"></div></div>';
				str+='<div id="insert" class="form-group"><label for="name" class="control-label col-xs-12 col-sm-2 no-padding-right"></label><div class="col-xs-12 col-sm-9">'+r.data.cname+'的'+r.data.bname+'第'+r.data.floor+'层的'+r.data.rname+'每天'+r.data.dayprices+'RMB</div></div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}
	if(type=='acc_pledge'){
		$('#box').remove();
		var str='<div id="box"><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">住宿押金:</label><div class="col-xs-12 col-sm-4">';
		str+='<input type="text" name="acc_pledge" value=""></div></div><div class="space-2"></div></div>';
		$('#insert').after(str);
	}
}
function sub_mit(){
	var data=$('#validation-form').serialize();
	$.ajax({
		url: '/master/charge/pay/chaange_type_select_submit',
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
</script>
<!--日期插件-->
