<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					为专业添加课程
				</div>
			</div>
			
			<div>
				<!--start-->
				

					<?php foreach($courseinfo as $k=>$v):?>
					<form class="form-horizontal" id="setcourse<?=$v['id']?>" method="post" action="" >
							
								<label class="control-label" for="email"><?=$v['name']?>:</label>
									<input type="hidden" value='<?=$majorid?>' name="majorid" />
									<?php
										$checked='';
										$id=null;
										foreach ($mcinfo as $kk => $vv) {
											if($v['id']==$vv['courseid']){
												$checked='checked="checked"';
												$id=$vv['id'];
											}
										}

									?>
									<input id="setcourseid<?=$v['id']?>" type="hidden" value='<?=$id?>' name="id" />
									<input <?=$checked?> onchange="setcourse(<?=$v['id']?>)" class="ace ace-switch ace-switch-6" value="<?=$v['id']?>" type="checkbox" name="courseid">
									<span class="lbl"></span>
					</form>
					<?php endforeach;?>
								
					


				<!--end-->
			</div>
							
	</div>
</div>
<script type="text/javascript">
function setcourse(id){
	var data=$("#setcourse"+id).serialize();

	$.ajax({
		url: "<?=$zjjp?>major/major/set_m_c",
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			$("#setcourseid"+id).attr({
				value: r.data,
			});
			pub_alert_success(r.info);
			
		}else{
			
			pub_alert_error(r.info);
		}
	})
	.fail(function() {
		console.log("error");
	})
	
	return false;
}
</script>