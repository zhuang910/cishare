<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	
	<li>
		<a href="javascript:;">在学管理</a>
	</li>
	<li><a href="index">学生管理</a></li>
	<li>添加重修费用</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>

<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script src="<?=RES?>master/js/upload.js"></script>	
<?php $this->load->view('master/public/js_css_kindeditor');?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		学生管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green">添加重修费用
						<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
							<i class="ace-icon fa fa-reply light-green bigger-130"></i>
						</a>
					</h3>
					<!---->
					<form class="form-horizontal" id="validation-form" method="post">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="platform">支付学期:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<select id="term" class="input-medium valid" name="term" aria-required="true" aria-invalid="false">
									
										<?php for($i=1;$i<=$mdata['termnum'];$i++):?>
										<option value="<?php echo $i?>" ><?php echo '第'.$i.'学期'?></option>
										<?php endfor?>
									</select>
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">金额:</label>
			
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
										<input type="text"  name="money" id="money"  class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
										<div style="display:none;" id="content_aid_box"></div>
										<textarea name="remark" class='content'  id="remarks"  boxid="content" style="width:100%;height:350px;resize: none;"></textarea>
										
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<input type="hidden" name="userid" value="<?=!empty($userid)?$userid:0?>">
						<input type="hidden" name="squadid" value="<?=!empty($info->squadid)?$info->squadid:0?>">
						<input type="hidden" name="passport" value="<?=!empty($info->passport)?$info->passport:''?>">
						<input type="hidden" name="email" value="<?=!empty($info->email)?$info->email:''?>">
						<input type="hidden" name="name" value="<?=!empty($info->name)?$info->name:''?>">
						<input type="hidden" name="majorid" value="<?=!empty($info->major)?$info->major:''?>">
						<div class="col-md-offset-3 col-md-9">
							<a class="btn btn-info" onclick="sub_mit()" id="tijiao">
								<i class="ace-icon fa fa-check bigger-110"></i>
									提交
							</a>
						</div>
					</form>
					<!---->
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
<?php $this->load->view('master/public/js_kindeditor_create')?>
<!-- script -->
<script type="text/javascript">

function sub_mit(){
	var data=$('#validation-form').serialize();
	$.ajax({
		url: '/master/student/student/insert_rebuild',
		type: 'POST',
		dataType: 'json',
		data: data,
	})
	.done(function(r) {
		if(r.state==1){
			window.location.href="/master/student/student";
			pub_alert_success();
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>