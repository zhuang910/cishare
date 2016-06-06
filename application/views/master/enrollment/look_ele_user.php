<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
		<div class="modal-dialog" style="width:850px">
			<div class="modal-content" >
				<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">查看学生</h4></div>
                    <!---->
					<form class="form-horizontal" id="validation-form" method="post">
						<?php if(!empty($data)){?>
						<table class="table table-striped table-bordered">

							<tr>
								<th>中文名字</th>
								<th>英文名字</th>
								<th>邮箱</th>
								<th>护照号</th>
								<th>国籍</th>
								<th>手机</th>
								<th>电话</th>
								<th>电费金额</th>
							</tr>
							<?php foreach($data as $k=>$v){?>
								<tr>
								<td><?=$v['name']?></td>
								<td><?=$v['enname']?></td>
								<td><?=$v['email']?></td>
								<td><?=$v['passport']?></td>
								<td><?=$v['nationality']?></td>
								<td><?=$v['mobile']?></td>
								<td><?=$v['tel']?></td>
								<td><?=!empty($v['fee'])?$v['fee']:0?></td>
								
							</tr>
							<?php }?>
						</table>
						<?php }?>
					</form>
				<!---->
                   
		</div>
	</div>
</div>

<!--日期插件-->
