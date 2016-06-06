<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>
	<li>
		<a href="#">教务管理</a>
	</li>
	<li>
		<a href="#">排课管理</a>
	</li>
	<li class="active">排课</li>
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
	<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">

	<!-- /section:settings.box -->
	<div class="page-header">
		<h1>
			排课管理
		</h1>
	</div><!-- /.page-header -->


	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div>
				<div class="col-sm">
					<div class="widget-box transparent">
						<div class="widget-box">
							<div class="widget-header">
								<h4 class="widget-title">按条件筛选</h4>
							</div>

							<div class="widget-body">
								<div class="widget-main">
									<form class="form-inline" id="condition" method="post">
										<label class="control-label" for="platform">专业:</label>
										<select id="majorid" class="input-medium valid" name="majorid" aria-required="true" aria-invalid="false" onchange="major()">
											<option value="0">--请选择 --</option>>
												<?php foreach($mdata as $item){ ?>
												<optgroup label="<?=$item['degree_title']?>">
												<?php foreach($item['degree_major'] as $item_info){ ?>
													<option value="<?=$item_info->id?>"><?=$item_info->name?></option>
												<?php } ?>
												</optgroup>
												<?php } ?>
										</select>
										<label class="control-label" for="platform">学期:</label>
										<select id="nowterm" class="input-medium valid" name="nowterm" aria-required="true" aria-invalid="false" onchange="term()">
											<option value="0">——请选择——</option>
										</select>
										<label class="control-label" for="platform">班级:</label>
										<select id="squad" class="input-medium valid" name="squadid" aria-required="true" aria-invalid="false" onchange="s()">
											<option value="0">——请选择——</option>
										</select>
										<label class="control-label" for="platform">预期排课学期:</label>
										<select onchange="get_term_course();" id="expectterm" class="input-medium valid" name="expectterm" aria-required="true" aria-invalid="false" onchange="c()">
											<option value="0">——请选择——</option>
										</select>
										<label class="control-label" for="platform">课程:</label>
										<select id="course" class="input-medium valid" name="courseid" aria-required="true" aria-invalid="false" onchange="c()">
											<option value="0">——请选择——</option>
										</select>
										
										<button class="btn btn-info btn-sm" type="button" onclick="paike()">
											确认条件
										</button>
									</form>
								</div>
							</div>
						</div>
						<div>
							<div class="col-sm-12">
								<div id="tables" class="widget-box transparent collapsed">
									<div class="widget-body">
										<div class="widget-main">
											<div class="alert alert-warning">
												<button data-dismiss="alert" class="close" type="button">
													<i class="ace-icon fa fa-times"></i>
												</button>
												<strong>提示!</strong>
												<span id="info"></span>
												<span id="numdays"></span>
												<br>
												<strong id='strong'></strong>
												<span id="tip"></span>
												<a href="/master/teacher/teacher" id='a'></a>
												<span id="tips"></span>
											</div>
											<table class="table table-bordered table-striped">
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
														<td id="td<?=$v?>"><?php
															echo $v."节课 <br />";
															echo $time['hour'][$v];
															?></td>
														<?php for($i=1;$i<=7;$i++):?>
															<?php
															echo '<td id="td'.$i.$v.'"><a role="button" class="blue" data-toggle="modal">---</a></td>';
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

	<!-- script -->
	<!--[if lte IE 8]>
	<script src="<?=RES?>/master/js/excanvas.min.js"></script>
	<![endif]-->
	<!-- ace scripts -->
	<script src="<?=RES?>master/js/ace-extra.min.js"></script>
	<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
	<script src="<?=RES?>/master/js/ace.min.js"></script>
	<script src="<?=RES?>master/js/jquery.dataTables.min.js"></script>
	<script src="<?=RES?>master/js/jquery.dataTables.bootstrap.js"></script>
	<!-- delete -->
	<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
	<script type="text/javascript">
		function del(id){
			pub_alert_confirms('/master/syllabus/arrangement/del?id='+id);
		}
		function s(){
			$('#info').html('')
			$('#numdays').html('')
			$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
		}
		function c(){
			$('#info').html('')
			$('#numdays').html('')
			$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
		}
		function major(){

			$('#info').html('')
			$('#numdays').html('')
			var mid=$('#majorid').val();
			$.ajax({
				url: '<?=$zjjp?>arrangement/get_nowterm/'+mid,
				type: 'POST',
				dataType: 'json',
				data:{}
			})
				.done(function(r) {
					if(r.state==1){
						$("#nowterm").empty();
						$("#squad").empty();
						$("#expectterm").empty();
						$("#nowterm").append("<option value='0'>——请选择——</option>");
						$("#squad").append("<option value='0'>——请选择——</option>");
						$("#expectterm").append("<option value='0'>——请选择——</option>");
						$.each(r.data.nowterm, function(i, k) {
							var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
							$("#nowterm").append(opt);
							// $("#expectterm").append(opt);
						});
						$.each(r.data.nowterm, function(i, k) {
							var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
							$("#expectterm").append(opt);
						});
						
						$('#tables').attr({
							class: 'widget-box transparent collapsed'
						});
					}else if(r.state==2){
						$("#nowterm").empty();
						$("#squad").empty();
						$("#course").empty();
						$("#nowterm").append("<option value='0'>——请选择——</option>");
						$("#squad").append("<option value='0'>——请选择——</option>");
						$.each(r.data.nowterm, function(i, k) {
							var opt = $("<option/>").text('第'+r.data.nowterm[i]+'学期').attr("value",r.data.nowterm[i]);
							$("#nowterm").append(opt);
							// $("#expectterm").append(opt);
						});
						$("#course").empty();
						$("#course").append("<option value='0'>——请选择——</option>");
						pub_alert_error(r.info);
					}
				})
				.fail(function() {


				})

		}
		function term(){
			$('#info').html('')
			$('#numdays').html('')
			$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
			var term=$('#nowterm').val();
			var mid=$('#majorid').val();
			$("#squad").empty();
			$.ajax({
				url: '<?=$zjjp?>arrangement/get_squad?term='+term+'&mid='+mid,
				type: 'POST',
				dataType: 'json',
				data:{}
			})
				.done(function(r) {
					if(r.state==1){
						$("#squad").empty();
						$("#squad").append("<option value='0'>——请选择——</option>");
						$.each(r.data, function(k, v) {
							var opt = $("<option/>").text(v.name).attr("value",v.id);
							$("#squad").append(opt);
						});
					}else if(r.state==2){
						$("#squad").empty();
						$("#squad").append("<option value='0'>——请选择——</option>");
						pub_alert_error(r.info);

					}
				})


		}
		function paike(){
			var data=$('#condition').serialize();
			var term=$('#expectterm').val();
			var mid=$('#majorid').val();
			var sid=$('#squad').val();
			var cid=$('#course').val();
			$.ajax({
				url: '<?=$zjjp?>arrangement/get_condition',
				type: 'POST',
				dataType: 'json',
				data: data
			})
				.done(function(r) {
					if(r.state==1){
						$('#info').text('每周最少上'+r.data.hourinfo.hour+'节课');
						$('#numdays').text('每周老师的可用课时'+r.data.hourinfo.numdays.thour);

						$.each(r.data.hour, function(k, v) {
							for(var i=1;i<=7;i++){

								$('#at'+i+v).remove();

								$('#td'+i+v).find('a').removeAttr('title');
								$('#td'+i+v).find('a').removeAttr('onclick');
								$('#td'+i+v).find('a').removeAttr('class');
								$('#td'+i+v).find('a').text('---');
							}


						});

						if(r.data.usablehour){
							$.each(r.data.usablehour, function(k, v) {

								$('#td'+v.week+v.knob).find('a').eq(0).html('');
								$('#td'+v.week+v.knob).find('a').eq(1).remove();
								var t='';
								var info='可用';
								var id=null;
								$.each(r.data.teacher, function(kkk,vvv) {

									if(v.week==vvv.week&&v.knob==vvv.knob){
										t+=vvv.name;
									}
								});
								var str='pub_alert_html('+'\'<?=$zjjp?>arrangement/popup?id='+id+'&knob='+v.knob+'&week='+v.week+'&courseid='+cid+'&termnum='+term+'&squadid='+sid+'&majorid='+mid+'\''+')';
								$('#td'+v.week+v.knob).find('a').attr({
									onclick: str,
									title:t
								});
								$('#td'+v.week+v.knob).find('a').text(info).attr('class','btn btn-minier btn-primary');
							});
						}
						if(r.data.scheduling){
							var canshu ='';
							$.each(r.data.scheduling,function(kk,vv){
								//  $('#td'+vv.week+vv.knob).find('a').removeAttr('title');
								//  $('#td'+vv.week+vv.knob).find('a').removeAttr('onclick');
								var info='';
								info=vv.tname+'-';
								info+=vv.cname+'-';
								info+=vv.rname+' ';
								// alert(1);
								var del= vv.id ? '<a href="javascript:;" id="at'+vv.week+vv.knob+'" onclick="del('+vv.id+')" class="red"> <i class="ace-icon fa fa-remove bigger-130"></i></a>' :'';

								var strs='pub_alert_html('+'\'<?=$zjjp?>arrangement/popup?id='+vv.id+'&knob='+vv.knob+'&week='+vv.week+'&courseid='+cid+'&termnum='+vv.nowterm+'&squadid='+vv.squadid+'&majorid='+vv.majorid+'\''+')';
								// $('#td'+vv.week+vv.knob).find('a').attr({
								// 	onclick: strs
								// })
								if(vv.merge!=0){
									canshu+=vv.week+'-'+vv.knob+'-'+vv.id+'-'+vv.merge+'-grf-';
								}
								

								$('#td'+vv.week+vv.knob).find('a').text(info);
								$('#td'+vv.week+vv.knob).find('a').after(del);
								
							})
						}
						var reg=/-grf-$/gi;
						canshu=canshu.replace(reg,"");
						merge(canshu);
						if(r.data.tip!=undefined){
							$('#strong').text('提示!');
							$('#tip').text(r.data.tip);
							$('#a').text(r.data.a);
							$('#tips').text(r.data.tips);
						}
						$('#tables').attr({
							class: 'widget-box transparent'
						});
					}else if(r.state==0){
						pub_alert_error(r.info);
					}
				})
				.fail(function() {

				})

		}
		function merge(str){

			if(str!=''){
				var arr= new Array(); //定义一数组 
				arr=str.split('-grf-');
				for (i=0;i<arr.length ;i++ )
				{
					var sub_arr= new Array(); //定义一数组 
					sub_arr=arr[i].split('-');
					for(j=1;j<sub_arr[3];j++){s
						$('#td'+sub_arr[0]+(parseInt(sub_arr[1])+j)).remove();
					}
					$('#td'+sub_arr[0]+sub_arr[1]).attr({
						rowspan: sub_arr[3]
					});	
				} 
			}
		}
		/**
		 * [get_term_course 获取该学期下排的课程]
		 * @return {[type]} [description]
		 */
		function get_term_course(){
			var data=$('#condition').serialize();
			$.ajax({
				url: '<?=$zjjp?>arrangement/get_term_course',
				type: 'POST',
				dataType: 'json',
				data: data,
			})
			.done(function(r) {
				if(r.state==1){
					$("#course").empty();
					$("#course").append("<option value='0'>——请选择——</option>");
					$.each(r.data, function(k, v) {
						var opt = $("<option/>").text(v.cname).attr("value",v.id);
						$("#course").append(opt);
					});
				}
				if(r.state==0){
					pub_alert_error(r.info);
				}
			})
			.fail(function() {
				console.log("error");
			})
		}
	</script>

	<!-- end script -->
<?php $this->load->view('master/public/footer');?>