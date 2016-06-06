
<div class="modal fade">
  <div class="modal-dialog">
   <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="blue">邮件发送  <?=!empty($info->company)?'-->'.$info->company:''?> <?=!empty($info->email)?'('.$info->email.')':''?></h4>
	</div>
	
	<form action="/master/authority/applyagency/dosendemail" method="post" id="myform"  role="form" class="form-horizontal">
	 <div class="modal-body">
		<div class="space-4"></div>
		<div style="width:75%;margin-left:12%;">
		
		
		<div class="form-group">
			<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 标题： </label>

			<div class="col-sm-9">
				<input type="text" class="input-xlarge" value="<?=!empty($info->title) ? $info->title : ''?>" name="title">
			</div>
		</div>
		<div class="form-group">
			<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> 内容： </label>

			<div class="col-sm-9">
				<textarea name="content" style="width: 270px; height: 188px;"></textarea>
			</div>
		</div>
		
		
		
		<input type="hidden" name="id" value="<?=!empty($id) ? $id : ''?>">
		<input type="hidden" name="email" value="<?=!empty($info->email) ? $info->email : ''?>">
		</div>
	 </div>
	
	 <div class="modal-footer center">
		<button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="ace-icon fa fa-check"></i> Submit</button>
		<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
	 </div>
	</form>
  </div>
 </div>
</div>