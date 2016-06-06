<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text"  name="line" id="line" value="<?=!empty($result['line'])?$result['line']:''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">描述:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<textarea name="des" style="width: 342px; height: 150px;"><?=!empty($result['des'])?$result['des']:''?></textarea>
								</div>
							</div>
						</div>

						<div class="space-2"></div>	
					<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">属性:</label>
							
							<div class="col-xs-12 col-sm-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" class="ace" name="classKind" id="classKind" <?=!empty($result['classKind']) && $result['classKind'] == 'Y'?'checked':''?>  value="Y" >
										<span class="lbl">默认隐藏</span>
									</label>
								</div>
							</div>
						</div>

						<div class="space-2"></div>