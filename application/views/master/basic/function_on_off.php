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
	<li class="active">功能开关</li>
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
		功能开关
	</h1>
</div><!-- /.page-header -->


	<div class="row">
		<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->


			<form id="apply" method="post" action="<?=$zjjp?>function_on_off/function_on_offsave">
			<div class="col-xs-12 col-sm-9">
			<label class="line-height-1 blue">
				
			<h4> 在线申请(报名费)</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['apply']) || $function_on_off['apply'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="apply" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['apply']) && $function_on_off['apply'] == 'no'?'checked':''?>  value="no" name="apply" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
				
			<h4> 在线预订住宿</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['accommaction']) || $function_on_off['accommaction'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="accommaction" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['accommaction']) && $function_on_off['accommaction'] == 'no'?'checked':''?>  value="no" name="accommaction" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
				
			<h4> 在线预订接机</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['pickup']) || $function_on_off['pickup'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="pickup" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['pickup']) && $function_on_off['pickup'] == 'no'?'checked':''?>  value="no" name="pickup" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
				
			<h4> 全文搜索开关</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['search']) || $function_on_off['search'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="search" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['search']) && $function_on_off['search'] == 'no'?'checked':''?>  value="no" name="search" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
				
			<h4> 住宿模式</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['putup']) || $function_on_off['putup'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="putup" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['putup']) && $function_on_off['putup'] == 'no'?'checked':''?>  value="no" name="putup" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
				
			<h4> 活动打分开关</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['activity']) || $function_on_off['activity'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="activity" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['activity']) && $function_on_off['activity'] == 'no'?'checked':''?>  value="no" name="activity" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<h1>
				在线支付开关
			</h1>
			<label class="line-height-1 blue">
			<h4> 在线交申请费(报名费)</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['online_apply']) || $function_on_off['online_apply'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="online_apply" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['online_apply']) && $function_on_off['online_apply'] == 'no'?'checked':''?>  value="no" name="online_apply" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
			<h4> 在线交住宿押金</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['acc_pledge']) || $function_on_off['acc_pledge'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="acc_pledge" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['acc_pledge']) && $function_on_off['acc_pledge'] == 'no'?'checked':''?>  value="no" name="acc_pledge" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			
			<label class="line-height-1 blue">
			<h4> 在线交住宿费</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['online_acc']) || $function_on_off['online_acc'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="online_acc" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['online_acc']) && $function_on_off['online_acc'] == 'no'?'checked':''?>  value="no" name="online_acc" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
			<h4> 在线交学费</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['tuition']) || $function_on_off['tuition'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="tuition" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['tuition']) && $function_on_off['tuition'] == 'no'?'checked':''?>  value="no" name="tuition" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
			<h4> 在线交换证费</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['card']) || $function_on_off['card'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="card" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['card']) && $function_on_off['card'] == 'no'?'checked':''?>  value="no" name="card" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
			<h4> 在线交重修费</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['rebuild']) || $function_on_off['rebuild'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="rebuild" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['rebuild']) && $function_on_off['rebuild'] == 'no'?'checked':''?>  value="no" name="rebuild" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
			<h4> 在线交入学押金</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['enrol_rebuild']) || $function_on_off['enrol_rebuild'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="enrol_rebuild" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['enrol_rebuild']) && $function_on_off['enrol_rebuild'] == 'no'?'checked':''?>  value="no" name="enrol_rebuild" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
			<h4> 在线交电费押金</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['electric_charge']) || $function_on_off['electric_charge'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="electric_charge" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['electric_charge']) && $function_on_off['electric_charge'] == 'no'?'checked':''?>  value="no" name="electric_charge" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
				<label class="line-height-1 blue">
			<h4> 在线交书费</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['book']) || $function_on_off['book'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="book" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['book']) && $function_on_off['book'] == 'no'?'checked':''?>  value="no" name="book" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<label class="line-height-1 blue">
			<h4> 在线交接机费</h4>
			</label>
			<br />
			<label class="line-height-1">
				<input class="ace valid" <?=empty($function_on_off['online_pickup']) || $function_on_off['online_pickup'] == 'yes'?'checked':''?>  type="radio"  value="yes" name="online_pickup" aria-required="true" aria-invalid="false">
				<span class="lbl"> 开启</span>
			</label>
			<label class="line-height-1">
			<input class="ace valid"  type="radio" <?=!empty($function_on_off['online_pickup']) && $function_on_off['online_pickup'] == 'no'?'checked':''?>  value="no" name="online_pickup" aria-required="true" aria-invalid="false">
				<span class="lbl"> 关闭</span>
			</label>
			<br />
			<div class="space-2"></div>
				<button class="btn btn-success btn-next" data-last="Finish">
					提 交
				<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
				</button>	
			</div>
			</form>

			
	
		</div>
	</div>
</div>
	<br />	
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script type="text/javascript">
  $("#apply").validate(
		{
				rules:
				{
			
				
				},

				messages:
				{
				
				},
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
						pub_alert_error();
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>