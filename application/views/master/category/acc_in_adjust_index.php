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
		<a href="javascript:;">学生管理</a>
	</li>
	<li class="active">分配房间</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script src="<?=RES?>master/js/jqGrid/jquery.jqGrid.min.js"></script>
<script src="<?=RES?>master/js/jqGrid/i18n/grid.locale-cn.js"></script>
<?php
$k=date('w',time());
if($k==0){
	$k=6;
}else{
	$k=$k-1;
}
$stime=time()-$k*3600*24;
$etime=$stime+6*24*3600;
?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		入住调剂
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<div class="col-sm">
				<div class="widget-box transparent">
					<!--tabbable-->
					<div class="tabbable">
								
						<!--tab-content no-border padding-24-->
						<div class="tab-content no-border padding-24">
							<!--1-->
							<div id="faq-tab-1" >
								<div class="widget-box transparent">
									<div class="widget-box">
										<div class="widget-header">
											<h4 class="widget-title">按条件筛选</h4>
										</div>
										<div class="widget-body">
											<div class="widget-main">
												<form class="form-inline" id="condition" method="post">
													<label class="control-label" for="platform">校区:</label>
													<select  id="campusid" name="campusid" aria-required="true" aria-invalid="false" onchange="campus()">
														<option value="0">—请选择—</option>
														<?php foreach($campus_info as $k=>$v):?>
															<option value="<?=$v['id']?>"><?=$v['name']?></option>
														<?php endforeach;?>
													</select>
													<label class="control-label" for="platform">宿舍楼:</label>
													<select  id="bulidingid" name="bulidingid" aria-required="true" aria-invalid="false" onchange="buliding()">
														<option value="0">—请选择—</option>
														
													</select>
													<label class="control-label" for="platform">楼层:</label>
													<select onchange="c()" id="floor" name="floor" aria-required="true" aria-invalid="false">
														<option value="0">—请选择—</option>
														
													</select>
													<input type="hidden" name="id" value="0">
													<input type="hidden" name="userid" value="<?=$userid?>">
													<a class="btn btn-primary btn-sm" type="button" onclick="sure()">
														确认条件
													</a>
												</form>
											</div>
										</div>
									</div>
								</div>
							
								<div>
									<div class="col-sm-12">
										<div id="tables" class="widget-box transparent collapsed">
											<div class="widget-body">
											<div><span class="btn btn-primary"></span>&nbsp;&nbsp;<b>可用</b>&nbsp;&nbsp;<span class="btn btn-warning"></span>&nbsp;&nbsp;<b>未开放</b>&nbsp;&nbsp;<span class="btn btn-danger"></span>&nbsp;&nbsp;<b>已满</b></div>
												<div class="widget-main" id="insert">
													
												</div><!-- /.widget-main -->
											</div><!-- /.widget-body -->
										</div><!-- /.widget-box -->
									</div><!-- /.col -->
								</div>
							</div>
							<!--1-->
						
						</div>
						<!--tab-content no-border padding-24-->
					</div>
				<!--tabbable-->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->

<script src="<?=RES?>master/js/fuelux/fuelux.wizard.min.js"></script>

<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>master/js/ace-elements.min.js"></script>
<script src="<?=RES?>master/js/ace.min.js"></script>
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>	
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<!-- delete -->
<script type="text/javascript">

function c(){

	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#add').remove();
}
function campus(){

	var cid=$('#campusid').val();
		$.ajax({
			url: '/master/enrollment/acc_apply/get_buliding?cid='+cid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#bulidingid").empty();
			$("#bulidingid").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data, function(i, k) { 
			 	 var opt = $("<option/>").text(k.name).attr("value",k.id);
			 	  $("#bulidingid").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
		})
		.fail(function() {
 
			
		})

}
function buliding(){

	var bid=$('#bulidingid').val();
		$.ajax({
			url: '/master/enrollment/acc_apply/get_buliding_floor?bid='+bid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#floor").empty();
			$("#floor").append("<option value='0'>—请选择—</option>"); 
			 for(i=1;i<=r.data;i++){
			 	 var opt = $("<option/>").text("第"+i+"层").attr("value",i);
			 	  $("#floor").append(opt); 
			  }
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
		})
		.fail(function() {
 
			
		})

}
function sure(){
	var data=$('#condition').serialize();
	$.ajax({
		url: '/master/enrollment/acc_apply/adjust_sure',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			$('#add').remove();
			var str='<div id="add" class="col-sm-12 infobox-container">';
											

			$.each(r.data.room, function(k, v) {
				str+='<div class="infobox infobox-green" style="height:100px;width:150px; float:left;border:none;">';
				if(v.is_reserve==0){
					str+='<a class="btn btn-app btn-primary btn-warning btn-sm" href="javascript:;"><i class="ace-icon glyphicon glyphicon-home"></i>'+v.name+'</a></div>';
				}else if(v.is_reserve==1){
					str+='<a class="btn btn-app btn-primary btn-sm" href="javascript:;" onclick="pub_alert_html(\'<?=$zjjp?>student/student/fenpei?floor='+r.data.floor+'&bulidingid='+r.data.bulidingid+'&campusid='+r.data.campusid+'&userid='+r.data.userid+'&roomid='+v.id+'&id='+r.data.id+'&s=1\')"><i class="ace-icon glyphicon glyphicon-home"></i>'+v.name+'</a></div>';
				}else if(v.is_reserve==2){
					str+='<a class="btn btn-app btn-primary btn-danger btn-sm" href="javascript:;"><i class="ace-icon glyphicon glyphicon-home"></i>'+v.name+'</a></div>';
				}
				
			}); 
			str+='</div>';
			$('#insert').after(str);
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
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>