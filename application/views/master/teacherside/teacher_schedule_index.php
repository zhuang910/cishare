<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">在学管理</a>
	</li>
	<li>
		<a href="javascript:;">排课管理</a>
	</li>
	<li class="active">查看</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>

 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
 <script src="<?=RES?>master/js/jquery.validate.min.js"></script>


<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		课表查看
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
								</ul>

								<!-- /section:pages/faq -->
								<div class="tab-content no-border padding-24">
									<div id="faq-tab-2">
										<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											老师
										</h4>

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
												<div class="col-sm offset-1">
													<div class="widget-box transparent">
														<div class="col-sm-12">
																		<div id="t2" class="widget-box transparent">
																			<div class="widget-body">
																				<div class="widget-main" id="dayin2">
																					<table class="table table-bordered table-striped" id="table">
																						<thead class="thin-border-bottom">
																							<tr>
																								<th>课时 / 星期</th>
																								<th>星期一</th>
																								<th>星期二</th>
																								<th>星期三</th>
																								<th>星期四</th>
																								<th>星期五</th>
																								<th>星期六</th>
																								<th>星期日</th>
																							</tr>
																						</thead>

																						<tbody>
																								<?php foreach($hour['hour'] as $k=>$v):?>
																									<tr> 
																										<td><?php 
																										echo $v*2-1,",",$v*2,"节课";
																										?></td>
																									<?php for($i=1;$i<=7;$i++):?>

																										<?php
																										$str='--';
																										  foreach ($tdata as $key => $value) {
																										  	
																										  	if($v==$value['knob']&&$i==$value['week']){
																										  		$str=$value['cname'].'-'.$value['sname'].'-'.$value['rname'];break;
																										  	}
																										  }
																											echo '<td id="tdd'.$i.$v.'"><a role="button" class="blue" data-toggle="modal">'.$str.'</a></td>';
																										?>
																									<?php endfor;?>
																									
																									</tr>
																							<?php endforeach;?>
																						</tbody>
																					</table>
																				</div><!-- /.widget-main -->
																			</div><!-- /.widget-body -->
																		</div><!-- /.widget-box -->
																	</div><!-- /.col -->
											
											</div>

										

										
										</div>
									</div>

									

									
								</div>
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

function s(){
	//$('#table').find('a').eq(1).remove();
	$('#info').html('')
	$('#numdays').html('')
	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	$('#oo').remove();
}

function major(){
	var mid=$('#majorid').val();
		$.ajax({
			url: '<?=$zjjp?>arrangement/get_nowterm/'+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#nowterm").empty();
			$("#squad").empty();
			$("#course").empty();
			$("#nowterm").append("<option value='0'>——请选择——</option>"); 
			$("#squad").append("<option value='0'>——请选择——</option>"); 
			 $.each(r.data.nowterm, function(i, k) { 
			 	 var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
			 	  $("#nowterm").append(opt); 
			  });
			 $("#course").empty();
			 $("#course").append("<option value='0'>——请选择——</option>"); 
			 $.each(r.data.course, function(k, v) { 
			 	var opt = $("<option/>").text(v.cname).attr("value",v.id);
			 	  $("#course").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
			 $('#oo').remove();
		})
		.fail(function() {

			
		})
						
}
function term(){
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();

		$.ajax({
			url: '<?=$zjjp?>arrangement/get_squad?term='+term+'&mid='+mid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#squad").empty();
			$("#squad").append("<option value='0'>——请选择——</option>"); 
			 $.each(r.data, function(k, v) { 
			 	var opt = $("<option/>").text(v.name).attr("value",v.id);
			 	  $("#squad").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
			 $('#oo').remove();
		})
		
						
}
function paike(){
	var data=$('#condition').serialize();
	var term=$('#nowterm').val();
	var mid=$('#majorid').val();
	var sid=$('#squad').val();
	
	$.ajax({
		url: '<?=$zjjp?>export/get_condition',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
				$.each(r.data.hour, function(k, v) {
					for(var i=1;i<=7;i++){
						 $('#td'+i+v).find('a').text('---');
					}
				});

				if(r.data.scheduling){
					$.each(r.data.scheduling,function(kk,vv){
						var info='';
						info=vv.tname+'-';
						info+=vv.cname+'-';
						info+=vv.rname;
						$('#td'+vv.week+vv.knob).find('a').text(info);

					})
				}
				$('#tables').attr({
					class: 'widget-box transparent'
				});
			}else if(r.state==0){
				pub_alert_error(r.info);
			}
			if(r.state==2){
				$('#oo').remove();
				var str='<div id="oo">';
				$.each(r.data.scheduling, function(kk, vv) {
					//alert(r.data.sinfo[kk].name);
					str+='<div class="alert alert-info">'+r.data.sinfo[kk].name+'</div>';
					 str+='<div class="col-sm-12"><div id="table2" class="widget-box transparent"><div class="widget-body"><div class="widget-main"><table class="table table-bordered table-striped"><thead class="thin-border-bottom"><tr><th>课时 / 星期</th><th>星期一</th><th>星期二</th><th>星期三</th><th>星期四</th><th>星期五</th><th>星期六</th><th>星期日</th></tr></thead><tbody>';
					$.each(r.data.hour, function(k, v) {
						str+='<tr>';
						str+='<td>'+(v*2-1)+','+(v*2)+'节课</td>';
						for(var i=1;i<=7;i++){
								var strr;
								$.each(vv, function(kkk, vvv) {
									
									if(vvv.week==i&&vvv.knob==v){
										strr=vvv.tname+'-'+vvv.cname+'-'+vvv.rname;
										return false;
									}else{
										strr='---';
									}
								});
								str+='<td>'+strr+'</td>';
							}
						
						str+='</tr>';
					});
					str+='</tbody></table></div></div></div></div>';
					
				});
				str+='</div>';
				$('#pp').after(str);
			}
	})
	.fail(function() {
		
	})

}
function teacher(){
$('#oo').remove();
	var tid=$('#teacherid').val();
	var term=$('#sterm').val();
	$.ajax({
		url: '<?=$zjjp?>export/select_teacher?tid='+tid+'&term='+term,
		type: 'GET',
		dataType: 'json',
		data: {}
	})
	.done(function(r) {
		if(r.state==1){
				$.each(r.data.hour, function(k, v) {
					for(var i=1;i<=7;i++){
						 $('#tdd'+v+i).find('a').text('---');
					}
				});

				if(r.data.scheduling){
					$.each(r.data.scheduling,function(kk,vv){
						var info='';
						info=vv.mname+'-';
						info+=vv.sname+'-';
						info+=vv.cname+'-';
						info+=vv.rname;
						$('#tdd'+vv.week+vv.knob).find('a').text(info);

					})
				}

		 $('#t2').attr({
				class: 'widget-box transparent'
			});
		}else{
			pub_alert_error(r.info);
		}
	
	})
	.fail(function() {
		console.log("error");
	})
	
}
function t(){
	var tid=$('#teacherid').val();
	$.ajax({
			url: '<?=$zjjp?>export/get_s_nowterm?tid='+tid,
			type: 'GET',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#sterm").empty();
			$("#sterm").append("<option value='0'>——请选择——</option>"); 
			 $.each(r.data, function(k, v) { 
			 	 var opt = $("<option/>").text('第'+v.nowterm+'学期').attr("value",v.nowterm);
			 	  $("#sterm").append(opt); 
			  });
			
			 $('#t2').attr({
				class: 'widget-box transparent collapsed'
			});
			 $('#oo').remove();
		})
		.fail(function() {

			
		})
}
function dayin(){
  // var dayin=$('#dayin').attr('dayin');
 	// if(dayin=='no'){
 		 document.body.innerHTML=document.getElementById('dayin').innerHTML;
  		window.print();
 	// }else{
 	// 	pub_alert_error('还没有打印的内容');
 	// }
 
}
function dayin2(){

  document.body.innerHTML=document.getElementById('dayin2').innerHTML;
  window.print();
}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>