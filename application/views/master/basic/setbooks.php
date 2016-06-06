<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					<button type="button" onclick="refresh()" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					为课程关联课本
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
						<?php if(!empty($bookinfo)):?>
							<tbody id='tbody'>
								<?php for($i=0;$i<=count($bookinfo);$i=$i+2):?>
									<?php 
										$checked1='';
										$checked2='';
										$id1=null;
										$id2=null;
										$cid1=!empty($bookinfo[$i]['id'])?$bookinfo[$i]['id']:0;
										$cid2=!empty($bookinfo[$i+1]['id'])?$bookinfo[$i+1]['id']:0;
										foreach ($cbinfo as $k => $v) {
											if($v['booksid']==$cid1){
												$checked1='checked="checked"';
												$id1=$v['id'];
											}elseif($v['booksid']==$cid2){
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
												<form class="form-horizontal" id="setcourse<?=!empty($bookinfo[$i]['id'])?$bookinfo[$i]['id']:''?>" method="post" action="" >
													<input type="hidden" value='<?=$courseid?>' name="courseid" />
													<input id="setbooksid<?=!empty($bookinfo[$i]['id'])?$bookinfo[$i]['id']:''?>" type="hidden" value='<?=$id1?>' name="id" />
													<label class="inline">
													<input class="ace" <?=$checked1?> onchange="setcourse(<?=!empty($bookinfo[$i]['id'])?$bookinfo[$i]['id']:''?>)" value="<?=!empty($bookinfo[$i]['id'])?$bookinfo[$i]['id']:''?>" type="checkbox" name="booksid">
														<!-- <input class="ace" <?=$checked1?> name='<?=$cid1?>' type="checkbox"> -->
														<span class="lbl"> <?=!empty($bookinfo[$i]['name'])?$bookinfo[$i]['name']:''?></span>
													</label>
												</form>
											<?php endif;?>
										</td>

										<td width='298'>
											<?php if($cid2!=0):?>
												<form class="form-horizontal" id="setcourse<?=!empty($bookinfo[$i+1]['id'])?$bookinfo[$i+1]['id']:''?>" method="post" action="" >
													<input type="hidden" value='<?=$courseid?>' name="courseid" />
													<input id="setbooksid<?=!empty($bookinfo[$i+1]['id'])?$bookinfo[$i+1]['id']:''?>" type="hidden" value='<?=$id2?>' name="id" />
													<label class="inline">
														<input class="ace" <?=$checked2?> onchange="setcourse(<?=!empty($bookinfo[$i+1]['id'])?$bookinfo[$i+1]['id']:''?>)" value="<?=!empty($bookinfo[$i+1]['id'])?$bookinfo[$i+1]['id']:''?>" type="checkbox" name="booksid">
														<!-- <input class="ace" <?=$checked2?> name='<?=$cid2?>' type="checkbox"> -->
														<span class="lbl"> <?=!empty($bookinfo[$i+1]['name'])?$bookinfo[$i+1]['name']:''?></span>
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
			url: "<?=$zjjp?>course/up_books?page="+page+"&courseid="+<?=$courseid?>,
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
							 if(vv.booksid==v.id){
							 	checked='checked="checked"';
							 	id1=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$courseid?>" name="courseid" />';
						str+='<input id="setbooksid'+v.id+'" type="hidden" value="'+id1+'" name="id" />';
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="booksid"><span class="lbl">'+v.name+'</span></label></form></td>';
					}else{
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.booksid==v.id){
							 	checked='checked="checked"';
							 	id2=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$courseid?>" name="courseid" />';
						str+='<input id="setbooksid'+v.id+'" type="hidden" value="'+id2+'" name="id" />';
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="booksid"><span class="lbl">'+v.name+'</span></label></form></td></tr><tr>';
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
			url: "<?=$zjjp?>course/next_books?page="+page+"&courseid="+<?=$courseid?>,
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
							 if(vv.booksid==v.id){
							 	checked='checked="checked"';
							 	id1=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$courseid?>" name="courseid" />';
						str+='<input id="setbooksid'+v.id+'" type="hidden" value="'+id1+'" name="id" />';
						
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="booksid"><span class="lbl">'+v.name+'</span></label></form></td>';
					}else{
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.booksid==v.id){
							 	checked='checked="checked"';
							 	id2=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$courseid?>" name="courseid" />';
						str+='<input id="setbooksid'+v.id+'" type="hidden" value="'+id2+'" name="id" />';
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="booksid"><span class="lbl">'+v.name+'</span></label></form></td></tr><tr>';
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
		beforeSend:function (){
			$(this).attr({
				disabled:'disabled',
			});
		},
		url: "<?=$zjjp?>course/set_c_b",
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			$("#setbooksid"+id).attr({
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
		url: "<?=$zjjp?>course/get_search_books?courseid="+<?=$courseid?>,
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
							 if(vv.booksid==v.id){
							 	checked='checked="checked"';
							 	id1=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$courseid?>" name="courseid" />';
						str+='<input id="setbooksid'+v.id+'" type="hidden" value="'+id1+'" name="id" />';
						
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="booksid"><span class="lbl">'+v.name+'</span></label></form></td>';
					}else{
						$.each(r.data.mc, function(ii, vv) {
							 if(vv.booksid==v.id){
							 	checked='checked="checked"';
							 	id2=vv.id;
							 }
						});
						str+='<td width="298"><form class="form-horizontal" id="setcourse'+v.id+'" method="post" action="" >';
						str+='<input type="hidden" value="<?=$courseid?>" name="courseid" />';
						str+='<input id="setbooksid'+v.id+'" type="hidden" value="'+id2+'" name="id" />';
						str+='<label class="inline"><input class="ace" '+checked+' onchange="setcourse('+v.id+')" value="'+v.id+'" type="checkbox" name="booksid"><span class="lbl">'+v.name+'</span></label></form></td></tr><tr>';
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
	window.location.href="/master/basic/course";
}
</script>