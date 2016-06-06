<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单类型:</label>

	<div class="col-xs-12 col-sm-4">
		<div class="clearfix">
		<?=$formType[$result['formType']]?>
		</div>
	</div>
</div>

<div class="space-2"></div>

<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单名称:</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<input type="text" name="formTitle" id="formTitle" value="<?=!empty($result['formTitle'])?$result['formTitle']:''?>" class="col-xs-12 col-sm-5" disabled="true" />
		</div>
	</div>
</div>

<div class="space-2"></div>

<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单ID:</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<input type="text" name="formID" id="formID" value="<?=!empty($result['formID'])?$result['formID']:''?>" class="col-xs-12 col-sm-5" disabled="true"/>
		</div>
	</div>
</div>

<div class="space-2"></div>
<?php if(!empty($result['formType']) && $result['formType'] == 6){?>
<div class="form-group" >
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">PHP数组:</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<input type="text" name="phpArrar" id="phpArrar" value="<?=!empty($result['phpArrar'])?$result['phpArrar']:''?>"  class="col-xs-12 col-sm-5" disabled="true"/>
		</div>
	</div>
</div>

<div class="space-2"></div>
<?php }?>
<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<input type="text" name="line" id="line" value="<?=!empty($result['line'])?$result['line']:''?>" class="col-xs-12 col-sm-5" disabled="true"/>
		</div>
	</div>
</div>

<div class="space-2"></div>

<!--添加-->
<div class="form-group" id="additem" style="display:<?=!empty($result['formType']) && in_array($result['formType'], array(5,4,6))?'':'none'?>">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">添加值:</label>
	
	<div class="col-xs-12 col-sm-4">
		<div class="clearfix">
		<?php if(!empty($formitem)){
			foreach($formitem as $key => $val){
		?>
			<div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">表单分项:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
							<input type="text" name="itemTitle[]" id="itemTitle" value="<?=!empty($val['itemTitle'])?$val['itemTitle']:''?>" class="col-xs-12 col-sm-5" disabled="true"/>
							</div>
						</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">子项值:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
							<input type="text" name="formValue[]" id="formValue" value="<?=!empty($val['formValue'])?$val['formValue']:''?>" class="col-xs-12 col-sm-5" disabled="true"/>
							</div>
						</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
							<input type="text" name="lines[]" id="lines" value="<?=!empty($val['line'])?$val['line']:'0'?>" class="col-xs-12 col-sm-5" disabled="true"/>
							</div>
						</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">onclick 控制项选择后自动生成（群组）:</label>

						<div class="col-xs-12 col-sm-9">
							<div class="clearfix">
							<select name="ControlIDG[]" id="ControlIDG" disabled="true">
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
							<select name="ControlIDF[]" id="ControlIDF" disabled="true">
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
				
			</div>
		<?php }}?>
		</div>
	</div>
</div>

<div class="space-2"></div>

<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">描述:</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<textarea name="des" style="width: 342px; height: 150px;" disabled="true"><?=!empty($result['des'])?$result['des']:''?></textarea>
		</div>
	</div>
</div>

<div class="space-2"></div>	
<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">帮助:</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<textarea name="formHelp" style="width: 342px; height: 150px;" disabled="true"><?=!empty($result['formHelp'])?$result['formHelp']:''?></textarea>
		</div>
	</div>
</div>

<div class="space-2"></div>	

<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">属性:</label>
	
	<div class="col-xs-12 col-sm-4">
		<div class="checkbox">
			<label>
				<input type="checkbox" class="ace" name="isHidden" id="isHidden" value="Y" disabled="true"  <?=!empty($result['isHidden']) && $result['isHidden'] == 'Y'?'checked':''?>>
				<span class="lbl">默认隐藏</span>
			</label>
			<label>
				<input type="checkbox" class="ace" name="isInput" id="isInput" disabled="true" value="Y" <?=!empty($result['isInput']) && $result['isInput'] == 'Y'?'checked':''?> >
				<span class="lbl">是否必填</span>
			</label>
		</div>
	</div>
</div>

<div class="space-2"></div>
	<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">宽:</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<input type="text" name="cols" id="cols" disabled="true" value="<?=!empty($result['cols'])?$result['cols']:''?>"  class="col-xs-12 col-sm-5" />
		</div>
	</div>
</div>

<div class="space-2"></div>
	<div class="form-group">
	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">行:</label>

	<div class="col-xs-12 col-sm-9">
		<div class="clearfix">
			<input type="text" name="rows" id="rows" disabled="true" value="<?=!empty($result['rows'])?$result['rows']:''?>" class="col-xs-12 col-sm-5" />
		</div>
	</div>
</div>
<input type="hidden" name="zyjid" value="<?=!empty($result['topic_id'])?$result['topic_id']:''?>">

					
				