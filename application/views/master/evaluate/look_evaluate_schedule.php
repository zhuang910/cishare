<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">评教进度查看</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height:350px;padding:30px">
						<!---->
						<form method="post" id="book">
							<table class="table table-bordered table-striped">
								<thead>
									<th>课程名称</th>
									<th>代课老师</th>
									<th>状态</th>
								</thead>
								<tbody id='tbody'>
									<?php if(!empty($course_teacher)):?>
										<?php foreach($course_teacher as $k=>$v):?>
											<tr>
												<td><?=$v['cname']?></td>
												<td><?=$v['tname']?></td>
												<td><?=$v['e_state']?></td>
												<!-- <td>
													<?php if($v['e_state']=='已完成'):?>
														<a class="normal-icon ace-icon fa fa-eye green bigger-130" <i="" onclick="show(this,1,89,89)" href="javascript:;" style="cursor:pointer;"></a>
													<?php endif;?>
												</td> -->
											</tr>
										<?php endforeach;?>
									<?php endif;?>
								</tbody>
							</table>
							<div class="modal-footer center">
							</div>
						</form>
						<!---->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function submit_book(){
	var data=$('#book').serialize();
	$.ajax({
		url: '/master/student/send_book/save_student_book',
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
			window.location.href="/master/student/send_book";
		}
	})
	.fail(function() {
		console.log("error");
	})
	
	
}
</script>
