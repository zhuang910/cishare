
<div class="modal fade">
  <div class="modal-dialog">
   <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="blue">添加权限</h4>
	</div>
	
	<form action="/admin/cms/column/save" method="post" id="myform"  role="form" class="form-horizontal">
	
		 <div class="control-group">

			<label class="control-label bolder blue" onclick="select_all(this)">
			控制台
			</label>
			
			<div class="checkbox">
				<label>
					<input type="checkbox" class="ace" name="form-field-checkbox" value="/admin/cms/column/index">
					<span class="lbl"> 管理员帐号管理</span>
				</label>
				<label>
					<input type="checkbox" class="ace" name="form-field-checkbox" value="/admin/cms/column/index">
					<span class="lbl"> 管理员帐号管理</span>
				</label>
			</div>
		 </div> 
		  <div class="control-group">
		 	
			<label class="control-label bolder blue">
			控制台
			</label>
			<div class="checkbox">
				<label>
					<input type="checkbox" class="ace" name="form-field-checkbox" value="/admin/cms/column/index">
					<span class="lbl"> 管理员帐号管理</span>
				</label>
				<label>
					<input type="checkbox" class="ace" name="form-field-checkbox" value="/admin/cms/column/index">
					<span class="lbl"> 管理员帐号管理</span>
				</label>
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

