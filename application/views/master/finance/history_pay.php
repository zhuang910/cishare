<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												缴费历史记录
											</div>
										</div>

										
										<div class="form-group">
												<table border="1" class="gridtable" width="586">
												<tr>
													<th>姓名</th>
													<th>学期</th>
													<th>金额</th>
													<th>方式</th>
													<th>交费时间</th>
													<th>状态</th>
												</tr>
													<?php if(!empty($history)){
														foreach($history as $k => $v){
													?>
													<tr>
														<td><?=!empty($v['name'])?$v['name']:''?></td>
														<td><?php 
															if(!empty($v['nowterm'])){
																echo '第'.$v['nowterm'].'学期';
															}
															
														?></td>
														<td><?=!empty($v['tuition'])?$v['tuition']:''?></td>
														<td>
															<?php
																if($v['paytype'] == 1){
																	echo "Paypal";
																}else if($v['paytype'] == 2){
																	 echo "Payease";
																}else if($v['paytype'] == 3){
																	echo "凭据";
																}
																
															?>
														</td>
														<td><?=!empty($v['paytime'])?date('Y-m-d H:i:s',$v['paytime']):''?></td>
														<td><?php
																if($v['paystate'] == 1){
																	echo "已支付";
																}else if($v['paystate']  == 2){
																	 echo "支付失败";
																}else{
																	echo "未支付";
																}
																
															?></td>
													</tr>
													<?php }}?>
												</table>
										</div>
										
									
									
								</div>
							</div>
</div>
