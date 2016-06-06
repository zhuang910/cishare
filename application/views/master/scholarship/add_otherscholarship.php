<?php
$uri = $this->uri->segment(3);
if($type == 2){
	$uri1 = '其他新生奖学金';
}else{
	$uri1 = '在学奖学金';
}
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	
	<li>
		<a href="javascript:;">奖学金管理</a>
	</li>
	<li>
		<a href="javascript:;">{$uri1} </a>
	</li>
	<li>
		人工授予奖学金
	</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		<?=$uri1?>
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
				<div class="step-content pos-rel" id="step-container">
					<div class="step-pane active" id="step1">

						<div class="widget-box">
							<div class="widget-header">
								<h4 class="widget-title">按条件筛选</h4>
							</div>
							<div class="widget-body">
								<div class="widget-main">
									<form class="form-inline" id="condition">
										<label class="control-label" for="platform">关键词:</label>
										<select id="key" name="key" aria-required="true" aria-invalid="false">
											<option value="0">—请选择—</option>
											<option value="chname">汉语名</option>
											<option value="email">邮箱</option>
											<option value="passport">护照号</option>
											<option value="studentid">学号</option>
										</select>

										<input id="value" type="text" value="" name="value"/>
										<a class="btn btn-info btn-sm" type="button" onclick="student_quick()">
											确认条件
										</a>
									</form>
								</div>
							</div>
						</div  transparent collapsed>
						<div id="tables-3" class="widget-box">
							<div class="widget-body" id="insert">
									
							</div>
						</div>
					</div>
				</div>
			<!-- /section:plugins/fuelux.wizard.container -->
		</div>
	</div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>	
<!-- script -->
<script type="text/javascript">
function student_quick(){
	var key=$('#key').val();
	var value=$('#value').val()
	$.ajax({
		url: '/master/charge/pay/get_student_quick?key='+key+'&value='+value,
		type: 'POST',
		dataType: 'json',
		data:{}
	})
	.done(function(r) {
		$('#insert').empty();
		if(r.state==1){
			var type=<?=$type?>;
			var str='';
			$.each(r.data.stu, function(k, v) {
				str+='<table class="table table-hover table-nomargin table-bordered"><thead><tr><th><table style="background-color:#fff;width:100%"><tr><td colspan="2" style="border-bottom:1px solid #ddd;">用户信息：';
				str+='<span style="float:right;"><a href="javascript:;" onclick="pub_alert_html(\'<?=$zjjp?>otherscholarship/shouyu?type='+type+'&userid='+v.id+'&s=1\')" class="btn btn-xs"><i class="ace-icon fa fa-bolt bigger-110"></i>授予奖学金</a></span></td></tr>';
				str+='<tr><td style="width:30%;font-weight:normal;">中文名：</td><td style="width:70%;font-weight:normal;">'+v.chname+'</td></tr>';
				str+='<tr><td style="width:30%;font-weight:normal;">英文名：</td><td style="width:70%;font-weight:normal;">'+v.enname+'</td></tr>';
				str+='<tr><td style="width:30%;font-weight:normal;">邮箱：</td><td style="width:70%;font-weight:normal;">'+v.email+'</td></tr>';
				str+='<tr><td style="width:30%;font-weight:normal;">电话：</td><td style="width:70%;font-weight:normal;">'+v.tel+'</td></tr>';
				str+='<tr><td style="width:30%;font-weight:normal;">护照号：</td><td style="width:70%;font-weight:normal;">'+v.passport+'</td></tr>';
				str+='<tr><td style="width:30%;font-weight:normal;">学号：</td><td style="width:70%;font-weight:normal;">'+v.studentid+'</td></tr>';
				str+='</table></th></tr></thead></table>';
			});
			$('#insert').append(str);
			$('#tables-3').attr({
				class: 'widget-box transparent'
			});

		}else if(r.state==2){
			 pub_alert_error(r.info);
		}
				

	})
	.fail(function() {
		
	})

}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>