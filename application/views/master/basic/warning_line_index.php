<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">网站设置</a>
	</li>
	<li class="active">提醒线设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>


<script src="<?=RES?>master/js/jquery.validate.min.js"></script>



<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		提醒线设置
	</h1>
</div><!-- /.page-header -->


	<div class="row">
		<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<!---->
		<form id="putup" method="post" action="<?=$zjjp?>warning_line/putup_save">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 住宿费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					
					提醒线:<input class="span3" type="text" name="putup_day" value="<?=!empty($info['putup_day'])?$info['putup_day']:'';?>" id="putup_day"  class="input required" placeholder="指定天数">(天数)&nbsp;&nbsp;&nbsp;&nbsp;
					
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form method="post" id="charge" action="<?=$zjjp?>warning_line/charge_save">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 电费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					提醒线:<input class="span3" value="<?=!empty($info['charge'])?$info['charge']:'';?>" type="text" id="charge" name="charge" placeholder="指定金额">(金额数)
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
	
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
	$(document).ready(function(){
		//床上费用收取
       $("#putup").validate(
		{
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
        $("#charge").validate(
		{
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
});

</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>