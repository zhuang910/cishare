<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">导入导出</a>
	</li>
	<li class="active">导入</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		导入
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<!-- PAGE CONTENT BEGINS -->
							<div class="tabbable">
								<!-- #section:pages/faq -->
								<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
									<li id="major" class="active">
										<a data-toggle="tab" href="#faq-tab-1">
											
											专业导入
										</a>
									</li>

									<li id="couese">
										<a data-toggle="tab" href="#faq-tab-2">
										
											课程导入
										</a>
									</li>
									
									<li id="score">
										<a data-toggle="tab" href="#faq-tab-4">
										
											成绩导入
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-5">
										
											考勤导入
										</a>
									</li>
									

								
								</ul>

								<!-- /section:pages/faq -->
								<div class="tab-content no-border padding-24">
									<div id="faq-tab-1" class="tab-pane fade in active">
									<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											字段设置
									</h4>
									<form class="form-horizontal" method="post" action="<?=$zjjp?>tochanel/educe_template" >
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($mfields as $k =>$v):?>
																<input type="hidden" value="<?=$v?>" id="mailserver" name="<?=$k?>" class="col-xs-12 col-sm-5" />

																<label class="middle">
																<input id="id-disable-check" disabled="disabled" checked="checked" class="ace" type="checkbox">
																<span class="lbl"> <?=$v?></span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<button class="btn btn-info" data-last="Finish">
														导出模板
														</button>
													
									
										</form>
										<hr />
										<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											上传excel
										</h4>
										<form id="majorupload" method="post" action="<?=$zjjp?>tochanel/upload_excel" enctype="multipart/form-data" >
											<div class="widget-body">
												<div class="widget-main">
													<div class="form-group">
														<div class="col-xs-7">
															<!-- #section:custom/file-input -->
															<input type="file" id="id-input-file-1" name='file' />
														</div>
													</div>
												</div>
											</div>
											<div class="space-2"></div>
											<button class="btn btn-info" data-last="Finish">
														上传
											</button>
										</form>
										<!---->
									
										<!---->
									</div>
									<div id="faq-tab-2" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											课程
											</h4>
											<form class="form-horizontal" id="emailset_defult" method="post" action="<?=$zjjp?>tochanel/educe_course_template" >
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php foreach($cfields as $k =>$v):?>
																<input type="hidden" value="<?=$v?>" id="mailserver" name="<?=$k?>" class="col-xs-12 col-sm-5" />

																<label class="middle">
																<input id="id-disable-check" disabled="disabled" checked="checked" class="ace" type="checkbox">
																<span class="lbl"> <?=$k?>(<?=$v?>)</span>
																</label>
														<?php endforeach;?>		
														<?php foreach($course_content as $k =>$v):?>
																<input type="hidden" value="<?=$v?>" id="mailserver" name="<?=$k?>" class="col-xs-12 col-sm-5" />

																<label class="middle">
																<input id="id-disable-check" disabled="disabled" checked="checked" class="ace" type="checkbox">
																<span class="lbl"> <?=$k?>(<?=$v?>)</span>
																</label>
														<?php endforeach;?>	
														<?php foreach($course_images as $k =>$v):?>
																<input type="hidden" value="<?=$v?>" id="mailserver" name="<?=$k?>" class="col-xs-12 col-sm-5" />

																<label class="middle">
																<input id="id-disable-check" disabled="disabled" checked="checked" class="ace" type="checkbox">
																<span class="lbl"> <?=$k?>(<?=$v?>)</span>
																</label>
														<?php endforeach;?>	
															</div>
													</div>

												<div class="space-2"></div>
														<button class="btn btn-info" data-last="Finish">
														导出模板
														</button>
													
									
										</form>
										<hr />
										<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											上传excel
										</h4>
										<form id="majorupload" method="post" action="<?=$zjjp?>tochanel/upload_course_excel" enctype="multipart/form-data" >
											<div class="widget-body">
												<div class="widget-main">
													<div class="form-group">
														<div class="col-xs-7">
															<!-- #section:custom/file-input -->
															<input type="file" id="id-input-file-2" name='file' />
														</div>
													</div>
												</div>
											</div>
											<div class="space-2"></div>
											<button class="btn btn-info" data-last="Finish">
														上传
											</button>
										</form>
											</div>
										</div>
									</div>
									<!--4-->
									<div id="faq-tab-4" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											成绩
											</h4>
											<form class="form-horizontal" id="emailset_defult" method="post" action="<?=$zjjp?>tochanel/educe_score_template">
													<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">所属专业:</label>
															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<select  id="majorid" name="majorid" aria-required="true" aria-invalid="false" onchange="major()">
																		<option value="0">—请选择—</option>
																			<?php foreach($minfo as $k=>$v):?>
																			<option value="<?=$v['id']?>"><?=$v['name']?></option>
																			<?php endforeach;?>
																	</select>
																</div>
															</div>
													</div>
													<div class="space-2"></div>
													<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">学期:</label>
															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<select id="nowterm" name="nowterm" aria-required="true" aria-invalid="false">
																		<option value="0">—请选择—</option>							
																	</select>
																</div>
															</div>
													</div>
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php unset($sfields['majorid'])?>
														<?php foreach($sfields as $k =>$v):?>
																<input type="hidden" value="<?=$v?>" id="mailserver" name="<?=$k?>" class="col-xs-12 col-sm-5" />

																<label class="middle">
																<input id="id-disable-check" disabled="disabled" checked="checked" class="ace" type="checkbox">
																<span class="lbl"> <?=$k?>(<?=$v?>)</span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<button class="btn btn-info" data-last="Finish">
														导出模板
														</button>
													
									
										</form>
										<hr />
										<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											上传excel
										</h4>
										<form id="scoreupload" method="post" action="<?=$zjjp?>tochanel/upload_score_excel" enctype="multipart/form-data">
											<div class="widget-body">
												<div class="widget-main">
													<div class="form-group">
														<div class="col-xs-7">
															<!-- #section:custom/file-input -->
															<input type="file" id="id-input-file-4" name='file' />
														</div>
													</div>
												</div>
											</div>
											<div class="space-2"></div>
											<button class="btn btn-info" data-last="Finish">
														上传
											</button>
										</form>
											</div>
										</div>
									</div>
									<!--end4-->
									<!--5-->
									<div id="faq-tab-5" class="tab-pane fade">
									

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
											<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											考勤
											</h4>
											<form class="form-horizontal" id="emailset_defult" method="post" action="<?=$zjjp?>tochanel/educe_checking_template">
													<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">所属专业:</label>
															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<select  id="majorid1" name="majorid" aria-required="true" aria-invalid="false" onchange="major1()">
																		<option value="0">—请选择—</option>
																			<?php foreach($minfo as $k=>$v):?>
																			<option value="<?=$v['id']?>"><?=$v['name']?></option>
																			<?php endforeach;?>
																	</select>
																</div>
															</div>
													</div>
													<div class="space-2"></div>
													<div class="form-group">
															<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">学期:</label>
															<div class="col-xs-12 col-sm-9">
																<div class="clearfix">
																	<select id="nowterm1" name="nowterm" aria-required="true" aria-invalid="false">
																		<option value="0">—请选择—</option>							
																	</select>
																</div>
															</div>
													</div>
													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-4 col-sm-1 no-padding-right"></label>
														<div class="col-xs-12 col-sm-9">
														<?php unset($checkfields['majorid'])?>
														<?php foreach($checkfields as $k =>$v):?>
																<input type="hidden" value="<?=$v?>" id="mailserver" name="<?=$k?>" class="col-xs-12 col-sm-5" />

																<label class="middle">
																<input id="id-disable-check" disabled="disabled" checked="checked" class="ace" type="checkbox">
																<span class="lbl"> <?=$k?>(<?=$v?>)</span>
																</label>
														<?php endforeach;?>		

															</div>
													</div>

												<div class="space-2"></div>
														<button class="btn btn-info" data-last="Finish">
														导出模板
														</button>
													
									
										</form>
										<hr />
										<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											上传excel
										</h4>
										<form id="scoreupload" method="post" action="<?=$zjjp?>tochanel/upload_checking_excel" enctype="multipart/form-data">
											<div class="widget-body">
												<div class="widget-main">
													<div class="form-group">
														<div class="col-xs-7">
															<!-- #section:custom/file-input -->
															<input type="file" id="id-input-file-5" name='file' />
														</div>
													</div>
												</div>
											</div>
											<div class="space-2"></div>
											<button class="btn btn-info" data-last="Finish">
														上传
											</button>
										</form>
											</div>
										</div>
									</div>
									<!--end5-->
									
								</div>
							</div>
		</div>
	</div>
</div>	

<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>

<script type="text/javascript">
jQuery(function($) {
	$('#id-input-file-1').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
	$('#id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
		$('#id-input-file-4').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
			$('#id-input-file-5').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
	
			
});

function majorupload(){
	var that_form =  $("#majorupload");
			var data = that_form.serialize();
			alert(data);
			$.ajax({
				type:'post',
				url:that_form.attr('action'),
				data:data,
				dataType:'json',
				success:function(r){
					if(r.state == 1){
						pub_alert_success(r.info);
						setTimeout('window.location.reload()',800);
					}else{
						pub_alert_error(r.info);
					}
				}
			});
			return false;
}
function major(){

	var mid=$('#majorid').val();
		$.ajax({
			url: '<?=$zjjp?>tochanel/get_nowterm/'+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			$("#nowterm").empty();
			$("#nowterm").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm").append(opt); 
			  });
			 
		})
		.fail(function() {
 
			
		})

}
function major1(){

	var mid=$('#majorid1').val();
		$.ajax({
			url: '<?=$zjjp?>tochanel/get_nowterm/'+mid,
			type: 'POST',
			dataType: 'json',
			data:{},
		})
		.done(function(r) {
			$("#nowterm1").empty();
			$("#nowterm1").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm1").append(opt); 
			  });
			 
		})
		.fail(function() {
 
			
		})

}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>