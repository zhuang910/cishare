<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					添加项
				</div>
			</div>

			<form  method="post" id="myform"  role="form" class="form-horizontal" action="/master/enrollment/attachment/attachment_mb_savembitem">
				<div class="control-group">
					<?php if(!empty($global)){
						foreach ($global as $key => $value) {
					?>
					<div class="checkbox">
						<label>
							<input type="checkbox" class="ace" name="aTopic_id[]" value="<?=$value['aTopic_id']?>">
							<span class="lbl"> <?=str_replace('\\','',$value['TopicName'])?></span>
						</label>
					</div>
					
					<?php }}?>
				</div>
			<div class="modal-footer center">
				<input type="hidden" name="atta_id" id="atta_id" value="<?=$atta_id?>">
			<button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="ace-icon fa fa-check"></i> Submit</button>
			<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
			</div>
		</form>
	</div>
</div>



