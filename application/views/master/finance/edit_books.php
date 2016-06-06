<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">学生发书</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: <?=count($s_b_info)*30+200?>px;padding:30px">
						<!---->
						<form method="post" id="book">
							<table class="table table-bordered table-striped">
								<thead>
									<th>ID</th>
									<th>中文名</th>
									<th>英文名</th>
									<th>单价</th>
								</thead>
								<tbody id='tbody'>
									<?php foreach($s_b_info as $k=>$v):?>
										<tr>
											<td><?=$v['id']?></td>
											<td><?=$v['name']?></td>
											<td><?=$v['enname']?></td>
											<td><?=$v['price']?></td>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
							<hr >
<!-- 							<div class="modal-footer center">
							<a class="btn btn-sm btn-success" onclick="submit_book()" id="tijiao"><i class="ace-icon fa fa-check"></i>
								提交
							</a>
							<button data-dismiss="modal" class="btn btn-sm" type="button"><i class="ace-icon fa fa-times"></i>
								取消
							</button>
						</div> -->
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
		url: '/master/student/book_fee/edit_book',
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
			window.location.href="/master/student/book_fee";
		}
	})
	.fail(function() {
		console.log("error");
	})
	
	
}
</script>
