<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header no-padding">
			<div class="table-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<span class="white">&times;</span>
				</button>
				查看信息
			</div>
		</div>
		<div style="text-align:center;">
		<table style="width:580px;height:auto;margin:auto;">
			<tr>
				<td style="border:1px solid ;padding:5px;text-align:left;">学生姓名：<?=$look_data['student_name']?></td><td style="border:1px solid ;padding:5px;text-align:left;">汇款人姓名：<?=$look_data['remit_name']?></td>
			</tr>
			<tr>
				<td style="border:1px solid ;padding:5px;text-align:left;">汇款人国家：<?=$look_data['remit_nationality']?></td><td style="border:1px solid ;padding:5px;text-align:left;">汇款金额：<?=$look_data['remit_money']?></td>
			</tr>
			<tr>
				<td style="border:1px solid ;padding:5px;text-align:left;">汇款备注：<?=$look_data['remit_remark']?></td>
			</tr>
		</table>
		</div>
		</div>
	</div>
</div>
