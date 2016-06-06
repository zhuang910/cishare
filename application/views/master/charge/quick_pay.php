<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">快捷缴费</h4></div>
				<!---->
				<?php if(!empty($is_scholarship)&&$is_jiaoguo==0):?>
					<form class="form-horizontal" id="validation-form" method="post">
						<table class="table table-hover table-nomargin table-bordered">
							<thead>		
								<tr>
									<th>
										<table style='background-color:#fff;width:100%'>
											<tr>
												<td colspan='3' style='border-bottom:1px solid #ddd;'>缴费信息：</td>
											</tr>
											<tr>
												<td style='width:10%;font-weight:normal;'>学费：</td>
												<td style='width:10%;font-weight:normal;'><input type="checkbox" onclick="scholarship_on()" name='tuition' value="tuition"></td>
												<td style='width:60%;font-weight:normal;'><input type="text" value="<?=!empty($tuition_p)?$tuition_p:0?>" name="tuition_p"></td>
											</tr>
											<tr>
												<td style='width:10%;font-weight:normal;'>电费：</td>
												<td style='width:10%;font-weight:normal;'><input type="checkbox" onclick="scholarship_on()" name='electric' value="electric"></td>
												<td style='width:60%;font-weight:normal;'><input type="text" value="<?=!empty($electric_p)?$electric_p:0?>" name="electric_p"></td>
											</tr>
											<tr>
												<td style='width:10%;font-weight:normal;'>书费：</td>
												<td style='width:10%;font-weight:normal;'><input type="checkbox"onclick="scholarship_on()" name='book' value="book"></td>
												<td style='width:60%;font-weight:normal;'><input type="hidden" name="book_ids" value="<?=!empty($book_ids)?$book_ids:''?>"><input type="text" value="<?=!empty($book_p)?$book_p:0?>" name="book_p"></td>
											</tr>
											<tr>
												<td style='width:10%;font-weight:normal;'>住宿费：</td>
												<td style='width:10%;font-weight:normal;'><input type="checkbox" onclick="scholarship_on()" name='acc' value="acc"></td>
												<td style='width:60%;font-weight:normal;'><input type="text" value="<?=!empty($acc_p)?$acc_p:0?>" name="acc_p"></td>
											</tr>
											<tr>
												<td style='width:10%;font-weight:normal;'>保险费：</td>
												<td style='width:10%;font-weight:normal;'><input type="checkbox" onclick="scholarship_on()" name='insurance' value="insurance"></td>
												<td style='width:60%;font-weight:normal;'><input type="text" value="<?=!empty($insurance_p)?$insurance_p:0?>" name="insurance_p"></td>
											</tr>
											<tr>
												<td style='width:10%;font-weight:normal;'>住宿押金：</td>
												<td style='width:10%;font-weight:normal;'><input type="checkbox" onclick="scholarship_on()" name='acc_pledge' value="acc_pledge"></td>
												<td style='width:60%;font-weight:normal;'><input type="text" value="<?=!empty($acc_pledge_p)?$acc_pledge_p:0?>" name="acc_pledge_p"></td>
											</tr>
											<tr>
												<td colspan='2' style='border-bottom:1px solid #ddd;'>合计：</td><td><input type="text" value="<?=!empty($total)?$total:0?>" name="total"></td>
											</tr>
											<?php if(!empty($is_scholarship['scholorshipid'])&&!empty($scholarship_info)):?>
												<tr id="scholarship_p">
													<td style='border-bottom:1px solid #ddd;'>奖学金余额：</td>
													<td style='width:10%;font-weight:normal;'></td>
													<td><input type="text"  value="<?=!empty($scholarship_info['money'])?$scholarship_info['money']:0?>" name="scholarship_p"></td>
												</tr>
											<?php endif;?>
										</table>
									</th>
								</tr>

							</thead>
						</table>	
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
			<?php elseif(!empty($is_scholarship)&&$is_jiaoguo==1):?>
				该生已经用奖学金交过费用
			<?php elseif(empty($is_scholarship)&&$is_jiaoguo==0):?>
				该生不是奖学金生
			<?php endif;?>
		</div>
	</div>
</div>
<script type="text/javascript">
function sub_mit(){
	var data=$('#validation-form').serialize();
	$.ajax({
		url: '/master/charge/pay/sub_pay',
		type: 'POST',
		dataType: 'json',
		data: data,
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
function scholarship_on(){
	// if($("#scholarship").attr("checke") == "true"){
	// 		$('#arrearage').remove();
	// 	   $("#scholarship").attr("checke","flase")
	//   }else{
	//   
	$('#arrearage').remove();
	  		var data=$('#validation-form').serialize();
			$.ajax({
				url: '/master/charge/pay/count_scholarship',
				type: 'POST',
				dataType: 'json',
				data: data,
			})
			.done(function(r) {
				if(r.state==0){
					var str='<tr id="arrearage"><td colspan="2" style="border-bottom:1px solid #ddd;">请补缴余额：</td><td>';
					str+='<input type="text" id="arrearage_p" value="'+r.data+'" name="arrearage_p"></td></tr>';
					$('#scholarship_p').after(str);
					pub_alert_error('请补缴余额');
				}
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		   $("#scholarship").attr("checke","true");
	  // }
}
</script>
<!--日期插件-->
