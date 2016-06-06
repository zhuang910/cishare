
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
				<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="bootbox-close-button close" type="button">×</button><h4 class="modal-title">查看二级学院备注</h4></div>
				</div>

				<form id="pub_form" class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/appmanager/faculty_change_state" >

				<table class="table table-hover table-nomargin table-bordered">
								<thead>	
									<tr>
										<td style="border-bottom:1px solid #ddd;" colspan="2">备注信息</td>
									</tr>	
									<tr>
										<th>
											<table style="background-color:#fff;width:100%">
												
												<tbody>
												<tr>
													<td><?=!empty($info['faculty_remark'])?$info['faculty_remark']:'无备注'?></td>
												</tr>
											</tbody>
											</table>
										</th>
									</tr>
									</thead>
								</table>
			</form>
		</div>
	</div>
</div>
