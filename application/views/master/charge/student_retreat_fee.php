<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">退费</h4></div>
            
					<div><span style="color:red">注:奖学金支付不退费,每一笔费用,上过的学期不再退费,扣去以上过月数的平均费用,费用扣除税点3.36</span></div>
                    <!---->
					<form class="form-horizontal" id="validation-form" method="post">
					
						<table class="table table-striped table-bordered">

							<tr>
								<th></th>
								<th>学期</th>
								<th>是否奖学金支付</th>
								<th>应退费金额</th>
								<th>已退费金额</th>
							</tr>
							<?php if(!empty($tuition_info)){?>
							<?php foreach($tuition_info as $k=>$v){?>
							<tr>
								<td><input type="checkbox" name="id[]" value='<?=$v['id']?>' onclick="total_tuition()" id="check<?=$v['id']?>"></td>
								<input type="hidden" name="tui_info[<?=$v['id']?>][t_id]" value="<?=$v['id']?>">
								<input type="hidden" name="tui_info[<?=$v['id']?>][tuifei]" value="<?=$v['tuifei']?>" id="yingtui<?=$v['id']?>">
								<input type="hidden" name="tui_info[<?=$v['id']?>][refund_amounts]" value="<?=$v['refund_amounts']?>" id="yitui<?=$v['id']?>">
								<td>第<?=$v['nowterm']?>学期</td>
								<td><?=!empty($v['paytype'])&&$v['paytype']==6?'是':'否'?></td>
								<td><?=$v['tuifei']?></td>
								<td><?=$v['refund_amounts']?></td>
								<input type="hidden" id="total<?=$v['id']?>" value="0" name="total[<?=$v['id']?>][total]">
							</tr>
							<?php }?>
							<?php }?>
							<tr>
								
								<td>合计</td>
								<td>应退费:<input style="width:120px" type="text" name="should_returned" value="<?=!empty($total)?$total:0?>"></td>
								<td>实退费:<input style="width:120px"  type="text" name="true_returned" value="" id="true_returned"></td>
							</tr>
						</table>
							
							<input type="hidden" name="userid" value="<?=$userid?>">
							<input type="hidden" name="flag" value="1" id="flag">
						<div class="modal-footer center">
							<a onclick="sub_mit()" id="tijiao"  class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i>
								退费
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
function total_tuition(){
	var total = 0;
	 $("input[name='id[]']").each(function(){
			var id = $(this).val();
		 	 if(this.checked==true){
				 var yingtui = $('#yingtui'+id).val();
				 var yitui = $('#yitui'+id).val();
				 var total_part = yingtui - yitui;
				 $('#total'+id).val(total_part);
				  total = total + total_part;
		 	 }else{
				
				  $('#total'+id).val(0);
			 }
			 
			
		  });
		  if(total!= 0){
			  total = Math.round(total*Math.pow(10,2))/Math.pow(10,2); 
			  $('#true_returned').val(total);
				$('#true_returned').attr('disabled',true);
				$('#flag').val(2);
				
		  }else{
			  
			  $('#true_returned').val('');
			  $('#true_returned').attr('disabled',false);
			  $('#flag').val(1);
		  }
		  
		 
	
}
function sub_mit(){
	var data=$('#validation-form').serialize();
	$.ajax({
		url: '/master/charge/retreat_fee/retreat',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
		}
		if(r.state==3){
			pub_alert_error('请补缴余额');
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
