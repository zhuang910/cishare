<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header no-padding">
											<div class="table-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													<span class="white">&times;</span>
												</button>
												邮件发送
											</div>
										</div>

										<form class="form-horizontal" id="validation-form" method="post" action="/master/student/student_tuition/do_tuition_notice" >
											
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">邮件内容:</label>
											<div class="col-xs-12 col-sm-9">
												<textarea id="content" name="content" cols="25" rows="5" style="width: 262px; height: 131px;"></textarea>
											</div>
																						
										</div>
														
										<div class="modal-footer no-margin-top">
												
											<div class="space-2"></div>
											<div class="modal-footer center">
											<button type="button" class="btn btn-sm btn-success" id="zyj"><i class="ace-icon fa fa-check"></i> Submit</button>
											<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
										 </div>
									
									
										</div>
									</form>
										<div class="box-content" style="display:none;" id="zyj_pro">
											<div class="row">
												<div class="col-xs-8">
													<!-- #section:elements.progressbar -->
													<div data-percent="0%" class="progress" id="zyj_log">
														<div style="width:0%;" class="progress-bar"></div>
													</div>
												</div>
											</div>
											<div class="alert alert-info">
												<div id="log"></div>
												<strong id="zyj_total" style="display:none;">共　计<span class="badge badge-important" id="total"></span></strong><br>
											</div>
											<div class="modal-footer no-margin-top" id="gb" style="display:none;">
												
												<div class="space-2"></div>
												<div class="modal-footer center">
												<button type="button" class="btn btn-sm btn-success" onclick="gb()"><i class="ace-icon fa fa-check"></i> 关闭</button>
												
												</div>
										
										
											</div>
											
										</div>
								</div>
					
					</div>
					
</div>

<script type="text/javascript">
function gb(){
	window.location.reload();
}

$(function(){
	var page = 1;
	var count = <?=$count?>;
	var countpage = <?=$countpage?>;
	var size = <?=$size?>;
	
$("#zyj").click(function(){
		page = 1;
		do_save();
		$('#validation-form').hide('slow');
		$('#zyj_pro').show('slow');
	});
	
	function do_save(){
		var content = $('#content').val();
		if(countpage >= page){
			$.ajax({
				type:'post',
				url:'/master/student/student_tuition/do_tuition_notice',
				data:{page:page,count:count,countpage:countpage,content:content},
				dataType:'json',
				success:function(r){
					if(r.state == 1){
						$("#log").html('发送完成<span class="badge badge-important">'+r.info+'％</span>');
						$('#zyj_log').attr('data-percent',r.info+'%');
						$(".progress-bar").css({width:r.info+'%'});
						page ++;
						do_save();
					}
				}
			});
		}else{
			$("#log").html('发送完成');
			$('#gb').show();
			
		}

	}
});

</script>