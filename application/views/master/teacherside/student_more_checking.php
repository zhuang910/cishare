
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												<?=$sname['name']?>--查看考勤
											</div>
										</div>
										<div>
											<table class="table table-striped table-bordered">
												<thead class="thin-border-bottom" id="stype">
													<tr>
													<th>日期</th>
													<th>专业</th>
													<th>班级</th>
													<th>课程</th>
													<th>代课老师</th>
													<th>考勤类型</th>
													<th>说明</th>
													</tr>
												</thead>
												<?php foreach($checking_info as $k=>$v):?>
													<tr><td><?=date('Y-m-d',$v['date'])?></td><td><?=$v['mname']?></td><td><?=$v['sname']?></td><td><?=$v['cname']?></td><td><?=$v['tname']?></td><td><?=$v['type']?></td><td><?=$v['knob']?></td></tr>
												<?php endforeach;?>
												<tbody>
													
													
													
												</tbody>		
											</table>				

										</div>
								</div>
							</div>
			
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>	

<script type="text/javascript">

</script>
