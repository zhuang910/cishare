<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					<button type="button" onclick="refresh()" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					为专业添加课程
				</div>
			</div>
			
			<div>
				<!--start-->
					<div class="widget-body">
						<div class="widget-main no-padding">
						<form  id="search" method="post" action="" >
						<div class="input-group">
							<input type="text" name='search' placeholder="Type your query" class="form-control search-query">
							<span class="input-group-btn">
								<a class="btn btn-purple btn-sm" href="javascript:;" onclick="search_course()" type="button">
									Search
									<i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
								</a>
							</span>
						</div>
						</form>
						<table class="table table-bordered table-striped">
						<?php if(!empty($courseinfo)):?>
							<tbody id='tbody'>
								<?php for($i=0;$i<=count($courseinfo);$i=$i+2):?>
									<?php 
										$checked1='';
										$checked2='';
										$id1=null;
										$id2=null;
										$cid1=!empty($courseinfo[$i]['id'])?$courseinfo[$i]['id']:0;
										$cid2=!empty($courseinfo[$i+1]['id'])?$courseinfo[$i+1]['id']:0;
										foreach ($mcinfo as $k => $v) {
											if($v['courseid']==$cid1){
												$checked1='checked="checked"';
												$id1=$v['id'];
											}elseif($v['courseid']==$cid2){
												$checked2='checked="checked"';
												$id2=$v['id'];
											}
										}
										if($cid1==0&&$cid2==0){
											continue;
										}
									?>
									<tr>
										<td width='298'>
											<?php if($cid1!=0):?>
												<form class="form-horizontal" id="setcourse<?=!empty($courseinfo[$i]['id'])?$courseinfo[$i]['id']:''?>" method="post" action="" >
													<input type="hidden" value='<?=$majorid?>' name="majorid" />
													<input id="setcourseid<?=!empty($courseinfo[$i]['id'])?$courseinfo[$i]['id']:''?>" type="hidden" value='<?=$id1?>' name="id" />
													<label class="inline">
													<input class="ace" <?=$checked1?> onchange="setcourse(<?=!empty($courseinfo[$i]['id'])?$courseinfo[$i]['id']:''?>)" value="<?=!empty($courseinfo[$i]['id'])?$courseinfo[$i]['id']:''?>" type="checkbox" name="courseid">
														<!-- <input class="ace" <?=$checked1?> name='<?=$cid1?>' type="checkbox"> -->
														<span class="lbl"> <?=!empty($courseinfo[$i]['name'])?$courseinfo[$i]['name']:''?></span>
													</label>
												</form>
											<?php endif;?>
										</td>

										<td width='298'>
											<?php if($cid2!=0):?>
												<form class="form-horizontal" id="setcourse<?=!empty($courseinfo[$i+1]['id'])?$courseinfo[$i+1]['id']:''?>" method="post" action="" >
													<input type="hidden" value='<?=$majorid?>' name="majorid" />
													<input id="setcourseid<?=!empty($courseinfo[$i+1]['id'])?$courseinfo[$i+1]['id']:''?>" type="hidden" value='<?=$id2?>' name="id" />
													<label class="inline">
														<input class="ace" <?=$checked2?> onchange="setcourse(<?=!empty($courseinfo[$i+1]['id'])?$courseinfo[$i+1]['id']:''?>)" value="<?=!empty($courseinfo[$i+1]['id'])?$courseinfo[$i+1]['id']:''?>" type="checkbox" name="courseid">
														<!-- <input class="ace" <?=$checked2?> name='<?=$cid2?>' type="checkbox"> -->
														<span class="lbl"> <?=!empty($courseinfo[$i+1]['name'])?$courseinfo[$i+1]['name']:''?></span>
													</label>
												</form>
											<?php endif;?>
										</td>
									</tr>
								<?php endfor;?>
							</tbody>
						<?php endif;?>
						</table>
						<div class="form-actions center">
							<a id='up' href="javascript:;" onclick="up(<?=$up?>)" class="btn btn-sm btn-success" type="button">
								<i class="ace-icon fa fa-arrow-left icon-on-left bigger-110"></i>
								上一页
							</a>
							<a id='next' href="javascript:;" onclick="next(<?=$next?>)" class="btn btn-sm btn-success" type="button">
								下一页
								<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
							</a>
						</div>
						</div><!-- /.widget-main -->
					</div>
				<!--end-->
			</div>
							
	</div>
</div>
<script type="text/javascript">
function up(page){
	if(page==0){
		pub_alert_error('已经是最前页了');
	}else{
		var data=$('#search').serialize();
		$.ajax({
			url: "<?=$zjjp?>major/major/up_course?page="+page+"&majorid="+<?=$majorid?>,
			type: 'POST',
			dataType: 'json',
			data: data,
		})
		.done(function(r) {
			if(r.state==1){
				$('#tbody').empty();
				var str='<tr>';
				$.each(r.data.c, function(i, v) {
					var checked='';
					var id1='';
					var id2='';
					if((i)%2==0){
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.courseid==v.id){
							 	checked='checked="checked"';
							 	id1=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$majorid?>" name="majorid" />';
						str+='<input id="setcourseid'+v.id+'" type="hidden" value="'+id1+'" name="id" />';
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="courseid"><span class="lbl">'+v.name+'</span></label></form></td>';
					}else{
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.courseid==v.id){
							 	checked='checked="checked"';
							 	id2=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$majorid?>" name="majorid" />';
						str+='<input id="setcourseid'+v.id+'" type="hidden" value="'+id2+'" name="id" />';
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="courseid"><span class="lbl">'+v.name+'</span></label></form></td></tr><tr>';
					}
				});
				$('#tbody').append(str);
				$('#up').attr({
					onclick: 'up('+r.data.num+')',
				});
				$('#next').attr({
					onclick: 'next('+(r.data.num+1)+')',
				});
				
			}else{
				
				pub_alert_error(r.info);
			}
		})
		.fail(function() {
			console.log("error");
		})
	}
		return false;
}
function next(page){
	
		var data=$('#search').serialize();
		$.ajax({
			url: "<?=$zjjp?>major/major/next_course?page="+page+"&majorid="+<?=$majorid?>,
			type: 'POST',
			dataType: 'json',
			data: data,
		})
		.done(function(r) {
			if(r.state==1){
				$('#tbody').empty();
				var str='<tr>';
				$.each(r.data.c, function(i, v) {
					var checked='';
					var id1='';
					var id2='';
					if((i)%2==0){
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.courseid==v.id){
							 	checked='checked="checked"';
							 	id1=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$majorid?>" name="majorid" />';
						str+='<input id="setcourseid'+v.id+'" type="hidden" value="'+id1+'" name="id" />';
						
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="courseid"><span class="lbl">'+v.name+'</span></label></form></td>';
					}else{
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.courseid==v.id){
							 	checked='checked="checked"';
							 	id2=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$majorid?>" name="majorid" />';
						str+='<input id="setcourseid'+v.id+'" type="hidden" value="'+id2+'" name="id" />';
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="courseid"><span class="lbl">'+v.name+'</span></label></form></td></tr><tr>';
					}
				});
				$('#tbody').append(str);
				$('#next').attr({
					onclick: 'next('+r.data.num+')',
				});
				$('#up').attr({
					onclick: 'up('+(r.data.num-1)+')',
				});
			}else{
				
				pub_alert_error(r.info);
			}
		})
		.fail(function() {
			console.log("error");
		})
	
		return false;
}
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
function search_course(){
	var data=$('#search').serialize();
	$.ajax({
		url: "<?=$zjjp?>major/major/get_search_course?majorid="+<?=$majorid?>,
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			$('#tbody').empty();
				var str='<tr>';
				$.each(r.data.c, function(i, v) {
					var checked='';
					var id1='';
					var id2='';
					if((i)%2==0){
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.courseid==v.id){
							 	checked='checked="checked"';
							 	id1=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$majorid?>" name="majorid" />';
						str+='<input id="setcourseid'+v.id+'" type="hidden" value="'+id1+'" name="id" />';
						
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="courseid"><span class="lbl">'+v.name+'</span></label></form></td>';
					}else{
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.courseid==v.id){
							 	checked='checked="checked"';
							 	id2=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$majorid?>" name="majorid" />';
						str+='<input id="setcourseid'+v.id+'" type="hidden" value="'+id2+'" name="id" />';
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="courseid"><span class="lbl">'+v.name+'</span></label></form></td></tr><tr>';
					}
				});
				$('#tbody').append(str);
				$('#next').attr({
					onclick: 'next('+(r.data.num+1)+')',
				});
				$('#up').attr({
					onclick: 'up('+(r.data.num)+')',
				});
		}else{
			
			pub_alert_error(r.info);
		}
	})
	.fail(function() {
		console.log("error");
	})
	
	return false;
}
function refresh(){
	window.location.href="/master/major/major";
}
</script>