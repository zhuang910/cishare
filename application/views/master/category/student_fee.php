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
<?php
    $type=array( 
    	1 => '申请费',
    	2 => '押金',
    	3 => '接机费',
    	4 => '住宿费',
    	5 => '入学押金',
    	6 => '学费',
    	7 => '电费',
    	8 => '书费',
    	9 => '保险费',
    	10 => '住宿押金费',
    	11 => '换证费',
    	12 => '重修费',
    	13 => '床品费',
    	14 => '电费押金',
    	15 => '申请减免学费'
    );
    $config_state =array(
		0 => '未支付',
		1 =>  '成功' , 
		2 => '失败', 
		3 =>  '待审核'
		);
    //支付方式
	 $config_paytype = array(
		1 => 'paypal',
		2 => 'payease',
		3 => '汇款',
		4 => '现金',
		5 => '刷卡',
		6=> '奖学金'
		);
	 $config_school = array(
		0 => '<span class="label label-important">否</span>',
		1 => '<span class="label label-success">是</span>'
		);

?>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												费用记录
											</div>
										</div>

										
										<div class="form-group">
												<table border="1" class="gridtable" width="586">
                                                    <tr>
                                                    <th colspan="8">交费记录</th>
                                                    </tr>
												<tr>
													<th>类别</th>
													<th>学期</th>
													<th>应交费用</th>
													<th>实交费用</th>
                                                    <th>支付时间</th>
                                                    <th>支付状态</th>
                                                    <th>支付类型</th>
                                                    <th>是否奖学金</th>
												</tr>
													<?php if(!empty($info)){?>
													<?php foreach($info as $k=>$v){?>
                                                        <tr>
                                                            <td><?=!empty($v['type'])?$type[$v['type']]:''?></td>
                                                            <td><?=!empty($v['term'])?'第'.$v['term'].'学期':''?></td>
                                                            <td><?=!empty($v['payable'])?$v['payable']:''?></td>
                                                            <td><?=!empty($v['paid_in'])?$v['paid_in']:''?></td>
                                                            <td><?=!empty($v['paytime'])?date('Y-m-d',$v['paytime']):''?></td>
                                                            <td><?=!empty($v['paystate'])?$config_state[$v['paystate']]:''?></td>
                                                            <td><?=!empty($v['paytype'])?$config_paytype[$v['paytype']]:''?></td>
                                                            <td><?=!empty($v['is_scholarship'])?$config_school[$v['is_scholarship']]:''?></td>
                                                        </tr>
                                                        <?php }?>
                                                    <?php }?>
												</table>
										</div>
                                            <?php if(!empty($info_tui)){?>
										
                                        <div class="form-group">
                                            <table border="1" class="gridtable" width="586">
                                                <tr>
                                                    <th colspan="5">退费记录</th>
                                                </tr>
                                                <tr>
                                                    <th>类别</th>
                                                    <th>学期</th>
                                                    <th>应退费用</th>
                                                    <th>实退费用</th>
                                                    <th>退费时间</th>
                                                </tr>
                                            
                                                    <?php foreach($info_tui as $k=>$v){?>
                                                        <tr>
                                                            <td><?=!empty($v['type'])?$type[$v['type']]:''?></td>
                                                            <td><?=!empty($v['term'])?'第'.$v['term'].'学期':''?></td>
                                                            <td><?=!empty($v['should_returned'])?$v['should_returned']:''?></td>
                                                            <td><?=!empty($v['true_returned'])?$v['true_returned']:''?></td>
                                                            <td><?=!empty($v['returned_time'])?date('Y-m-d',$v['returned_time']):''?></td>
                                                        </tr>
                                                    <?php }?>
                                            </table>
                                        </div>
                                                <?php }?>
									
								</div>
							</div>
</div>
