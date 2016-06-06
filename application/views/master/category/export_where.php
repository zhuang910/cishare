<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												导出
											</div>
										</div>

										<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>student/student/export" >
											
										
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">状态:</label>
											
										
													<select id="state" class="input-medium valid" name="state" aria-required="true" aria-invalid="false">
														<option value="0">-请选择-</option>
														<option value="1">在校</option>
														<option value="2">转学</option>
														<option value="3">正常离开</option>
														<option value="4">非正常离开</option>
														<option value="5">休学</option>
														<option value="6">申请</option>
														<option value="7">已报到</option>
														<option value="8">未报到</option>
													</select> 
												
											
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">国籍:</label>
											
										
													<select id="nationality" class="input-medium valid" name="nationality" aria-required="true" aria-invalid="false">
														<?php foreach($nationality['global_country_cn'] as $k=>$v):?>
																	<option value="<?=$k?>"><?=$v?></option>
																<?php endforeach;?>
													</select> 
												
											
										</div>
										<div class="modal-footer no-margin-top">
											<div class="space-2"></div>
													<div class="col-md-offset-3 col-md-9">
														<button class="btn btn-info" data-last="Finish">
															<i class="ace-icon fa fa-check bigger-110"></i>
																提交
														</button>
														<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
																重置
														</button>
													</div>
									
									
									</div>
									</form>
								</div>
							</div>
			
<script type="text/javascript">

</script>
