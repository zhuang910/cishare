<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑教师课程':'添加教师课程';
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
		<a href="javascript:;">在学设置</a>
	</li>
	<li>
		<a href="/master/teacher/teacher">教师设置</a>
	</li>
	<li><a href="javascript:history.back();">关联课程</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!-- bootstrap & fontawesome -->
		
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 

$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';



?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		教师管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<div class="step-pane active" id="step1">
			<h3 class="lighter block green"><?=$title_h3?>教师课程
			<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
			</a>
			</h3>
			</div>

			<!-- #section:plugins/fuelux.wizard.container -->
					<?php $i=1?>
					<?php foreach($courseinfo as $k=>$v):?>
						
							<div class="col-xs-4">
								<form class="form-horizontal" id="setcourse<?=$v['id']?>" method="post" action="" >
									<input type="hidden" name="teacherid" value="<?=$teacherid?>">
									<input type="hidden" name="week" value="99">
									<input type="hidden" name="knob" value="99">
									<?php
										$checked='';
										$id=null;
										foreach ($tcinfo as $kk => $vv) {
											if($v['id']==$vv['courseid']){
												$checked='checked="checked"';
												$id=$vv['id'];
											}
										}

									?>	
									<span class="col-sm-12">
										<input id="setcourseid<?=$v['id']?>" type="hidden" value='<?=$id?>' name="id" />
										<small class="muted smaller-90"><?=$v['name']?>:</small>
										<input id="id-button-borders" <?=$checked?> onchange="setcourse(<?=$v['id']?>)" class="ace ace-switch ace-switch-4" value="<?=$v['id']?>" type="checkbox" name="courseid">
										<span class="lbl middle"></span>
									</span>
								<!-- 	<span class="col-sm-6">
										<label class="pull-right inline">
										<small class="muted smaller-90">Bosssssssssssrder:</small>
										<input id="id-button-borders" class="ace ace-switch ace-switch-5" type="checkbox" checked="checked">
										<span class="lbl middle"></span>
										</label>
									</span> -->
								</form>
							</div>
						<?php
							if($i%3==0){
								echo "<br /><br />";
							}
						?>
						<?php $i++?>
					<?php endforeach;?>
											
												
													
												

													
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
<script src="<?=RES?>master/js/markdown/markdown.min.js"></script>
<script src="<?=RES?>master/js/markdown/bootstrap-markdown.min.js"></script>
		

<script type="text/javascript">
function setcourse(id){
	var data=$("#setcourse"+id).serialize();

	$.ajax({
		url: "<?=$zjjp?>teacher_course/insert",
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
</script>


	
		
	
<!-- end script -->
<?php $this->load->view('master/public/footer');?>