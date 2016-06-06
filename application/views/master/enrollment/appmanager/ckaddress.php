
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="table-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								<span class="white">&times;</span>
							</button>
							地址确认信息：
						</div>
					</div>

					
					

	<div class="form-group">
			<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">姓名:</label>
		<div class="col-xs-12 col-sm-9">
			<?=!empty($result->name)?$result->name:''?>
		</div>
	</div><br />

	<div class="form-group">
			<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">电话:</label>
		<div class="col-xs-12 col-sm-9">
			<?=!empty($result->tel)?$result->tel:''?>
		</div>
	</div><br />
	<div class="form-group">
			<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">门牌号:</label>
		<div class="col-xs-12 col-sm-9">
			<?=!empty($result->building)?$result->building:''?>
		</div>
	</div><br />
	<div class="form-group">
			<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">城市:</label>
		<div class="col-xs-12 col-sm-9">
			<?=!empty($result->city)?$result->city:''?>
		</div>
	</div><br />
	<div class="form-group">
			<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">国家:</label>
		<div class="col-xs-12 col-sm-9">
			<?=!empty($result->country)?$nationality[$result->country]:''?>
		</div>
	</div><br />

	<div class="form-group">
			<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">邮编:</label>
		<div class="col-xs-12 col-sm-9">
			<?=!empty($result->postcode)?$result->postcode:''?>
		</div>
	</div><br />
	<div class="form-group">
			<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">地址:</label>
		<div class="col-xs-12 col-sm-9">
			<?=!empty($result->address)?$result->address:''?>
		</div>
	</div><br />
			
			<div class="modal-footer center">
				
			 </div>
				

			</div>
</div>
</div>
