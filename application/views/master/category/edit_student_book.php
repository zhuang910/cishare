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
					<div class="widget-main" style="height: <?=count($m_book)*30+450?>px;padding:30px">
						<!---->
						<form method="post" id="book">
							<table class="table table-bordered table-striped">
								<thead>
									<th>书籍中文名</th>
									<th>书籍英文名</th>
									<th>单价</th>
									<th>领取状态</th>
								</thead>
								<tbody id='tbody'>
									<?php
										$book_arr=explode('-grf-', $s_b_info['bookid']);

									?>
									<?php foreach($m_book as $k=>$v):?>
										<tr>
											<td><?=$v['name']?></td>
											<td><?=$v['enname']?></td>
											<td><?=$v['price']?></td>
											<?php 
												$checked='';
												foreach ($book_arr as $kk => $vv) {
													if($vv==$v['id']){
														$checked='checked';
													}
												}
											?>
											<td><input type="checkbox" name='bookid[]' <?=$checked?> value="<?=$v['id']?>"></td>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
							<hr >
							<input type="hidden" name="studentid" value="<?=$studentid?>">
							<input type="hidden" name="nowterm" value="<?=$nowterm?>">

							备注:
							<br />
							<textarea name="remark" style="resize: none;width:537px;height:180px;"><?=!empty($s_b_info)&&!empty($s_b_info['remark'])?$s_b_info['remark']:''?></textarea>
							<div class="modal-footer center">
							<a class="btn btn-sm btn-success" onclick="submit_book()" id="tijiao"><i class="ace-icon fa fa-check"></i>
								提交
							</a>
							<button data-dismiss="modal" class="btn btn-sm" type="button"><i class="ace-icon fa fa-times"></i>
								取消
							</button>
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
