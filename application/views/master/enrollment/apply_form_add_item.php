<div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单分项:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="itemTitle[]" id="itemTitle" value="<?=!empty($val['itemTitle'])?$val['itemTitle']:''?>" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">子项值:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="formValue[]" id="formValue" value="<?=!empty($val['formValue'])?$val['formValue']:''?>" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<input type="text" name="lines[]" id="lines" value="<?=!empty($val['line'])?$val['line']:'0'?>" class="col-xs-12 col-sm-5" />
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">onclick 控制项选择后自动生成（群组）:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<select name="ControlIDG[]" id="ControlIDG">
													<option value="">--Please Select--</option>
													<?php if(!empty($group)){
														foreach ($group as $keyg => $valueg) {									
														?>
															<option value="<?=$keyg?>" <?=!empty($val['ControlID']) && $val['ControlID'] == $keyg?'selected':''?>><?=$valueg?></option>

													<?php }}?>
													</select>
													</div>
												</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">onclick 控制项选择后自动生成（项）:</label>

												<div class="col-xs-12 col-sm-9">
													<div class="clearfix">
													<select name="ControlIDF[]" id="ControlIDF">
													<option value="">--Please Select--</option>
													<?php if(!empty($form)){
														foreach ($form as $keyf => $valuef) {									
														?>
															<option value="<?=$keyf?>"  <?=!empty($val['ControlID']) && $val['ControlID'] == $keyf?'selected':''?>><?=$valuef?></option>

													<?php }}?>
													</select>
													</div>
												</div>
										</div>
										<div class="actions"><a href="javascript:;" onclick="del(this)" class="red" title="删除"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>
									</div>