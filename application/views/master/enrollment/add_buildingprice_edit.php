<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑房间':'添加房间';
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
		<a href="javascript:;">申请设置</a>
	</li>
	
	<li class="active">住宿设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	住宿设置
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green"><?=!empty($info)?'编辑房间':'添加房间'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="/master/enrollment/buildingprice/<?=$form_action?>">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">楼层数:</label>

					<div class="col-xs-12 col-sm-4">
						<select name="floor" id="floor" onchange="floor_num(<?=$buildingid?>)">
							<option value="">请选择楼层数...</option>
							<?php if(!empty($floor_num_all)):?>
							<?php for($i=1;$i<=$floor_num_all;$i++){ ?>
							<option value="<?=$i?>" <?=!empty($info->floor) && $info->floor == $i ? 'selected' : ''?>>第<?=$i?>层</option>
							<?php }?>
							<?php endif;?>
						</select>
						<sqpn id="tip"></sqpn>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">房间类型:</label>

					<div class="col-xs-12 col-sm-4">
						<select name="campusid" id="campusid">
							<option value="">请选择房间类型...</option>
							<?php foreach($room as $value => $item){ ?>
							<option value="<?=$value?>" <?=!empty($info->campusid) && $info->campusid == $value ? 'selected' : ''?>><?=$item?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">房间中文名:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="name" name="name" value="<?=!empty($info->name) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">房间英文名:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="enname" name="enname" value="<?=!empty($info->enname) ? $info->enname : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">价格:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="prices" name="prices" value="<?=!empty($info->prices) ? $info->prices : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				
			<!-- 	<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">是否指定房间数量:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
						<input type="radio" name="isroomset" value="0" <?=empty($info->isroomset) || $info->isroomset == 0?'checked':''?>> 不指定
						<input type="radio" name="isroomset" value="1" <?=!empty($info->isroomset) && $info->isroomset == 1?'checked':''?>> 指定
						</div>
					</div>
				</div>
				<div class="space-2"></div> -->
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">开放预订:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
						<input type="radio" name="is_reserve" value="0" <?=empty($info->is_reserve) || $info->is_reserve == 0?'checked':''?>> 否
						<input type="radio" name="is_reserve" value="1" <?=!empty($info->is_reserve) && $info->is_reserve == 1?'checked':''?>> 是
						<input type="radio" name="is_reserve" value="2" <?=!empty($info->is_reserve) && $info->is_reserve == 2?'checked':''?>> 已满
						</div>
					</div>
				</div>
				<!--添加隐藏字段记录当前的入住的人数-->
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">容纳人数:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text" onchange="check_maxuser(<?=!empty($info->id)?$info->id:''?>)" id="maxuser" name="maxuser" value="<?=!empty($info->maxuser) ? $info->maxuser : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
<!-- 
				<div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">房间数量:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="roomcount" name="roomcount" value="<?=!empty($info->roomcount) ? $info->roomcount : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div> -->

				<!-- <div class="space-2"></div>
				
					<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">简介:</label>

					<div class="col-xs-12 col-sm-9">
							
							<div class="wysiwyg-editor" id="editor2"><?=!empty($info) ? $info->remark : ''?></div>
							<input type="hidden" name="remark" id="remark" value="">
					</div>
				</div>

				<div class="space-2"></div> -->

				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
				<input type="hidden" name="bulidingid" value="<?=!empty($info->bulidingid)?$info->bulidingid:$buildingid?>">
				<div class="space-2"></div>
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn btn-info">
						<i class="ace-icon fa fa-check bigger-110"></i>
							提交
					</button>
					<button class="btn" type="reset">
						<i class="ace-icon fa fa-undo bigger-110"></i>
							重置
					</button>
				</div>
			</form>

		</div>
	</div>
</div>
	<!-- ace scripts -->
		<script src="/resource/master/js/ace-extra.min.js"></script>
		<script src="/resource//master/js/ace-elements.min.js"></script>
		<script src="/resource//master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
function check_maxuser(id){
	var num=$('#maxuser').val();
	if(id!==undefined){
		$.ajax({
			url: '/master/enrollment/buildingprice/check_maxuser?num='+num+'&roomid='+id,
			type: 'POST',
			dataType: 'json',
			data: {},
		})
		.done(function(r) {
			if(r.data!==0){
				$('#maxuser').val(r.data);
				pub_alert_error('入住该房间的人数已经超过您设置的人数，请处理都再设置！');
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
}
function floor_num(buildingid){
	var floor=$('#floor').val();
	$('#tip').empty();
	$.ajax({
		url: '/master/enrollment/buildingprice/get_room_num?buildingid='+buildingid+'&floor='+floor,
		type: 'GET',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			var str="该楼层还剩余"+r.data+"个房间";
			$('#tip').html(str);
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						floor: {
							required: true,
							
						},
	
						campusid: {
							required: true
						},
						name: {
							required: true
						},
						enname: {
							required: true
						},
						prices: {
							required: true,
							
						},
						roomcount: {
							required: true
						},
						remark: {
							required: true
						},
						maxuser: {
							required: true
						},
						
					},
			
					messages: {
						floor: {
							required: "请选择楼层",
						},
						state: "请选择状态",
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
						$('#remark').val($('#editor2').html());
						var data=$(form).serialize();
						
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success(r.info);
								window.history.back();
							}
							if(r.state==0){
								pub_alert_error(r.info);
							}
							
						})
						.fail(function() {
							
							pub_alert_error();
						})
						
						
					}
			
				});

});
</script>

<script type="text/javascript">
jQuery(function($) {
		var cucaseditor = ['#editor1','#editor2','#editor3','#editor4'];
		$.each(cucaseditor,function(i,v){
			$(v).ace_wysiwyg({
					toolbar:
					[
						{
							name:'font',
							title:'Custom tooltip',
							values:['Some Font!','Arial','Verdana','Comic Sans MS','Custom Font!']
						},
						null,
						{
							name:'fontSize',
							title:'Custom tooltip',
							values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text'} 
						},
						null,
						{name:'bold', title:'Custom tooltip'},
						{name:'italic', title:'Custom tooltip'},
						{name:'strikethrough', title:'Custom tooltip'},
						{name:'underline', title:'Custom tooltip'},
						null,
						'insertunorderedlist',
						'insertorderedlist',
						'outdent',
						'indent',
						null,
						{name:'justifyleft'},
						{name:'justifycenter'},
						{name:'justifyright'},
						{name:'justifyfull'},
						null,
						{
							name:'createLink',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-purple',
							button_text:'Custom TEXT'
						},
						{name:'unlink'},
						null,
						{
							name:'insertImage',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-inverse',
							//choose_file:false,//hide choose file button
							button_text:'Set choose_file:false to hide this',
							button_insert_class:'btn-pink',
							button_insert:'Insert Image'
						},
						null,
						{
							name:'foreColor',
							title:'Custom Colors',
							values:['red','green','blue','navy','orange'],
							/**
								You change colors as well
							*/
						},
						/**null,
						{
							name:'backColor'
						},*/
						null,
						{name:'undo'},
						{name:'redo'},
						null,
						'viewSource'
					],
					//speech_button:false,//hide speech button on chrome
					
					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
					
				}).prev().addClass('wysiwyg-style2');
		});
				

				
				
	});
			

</script>


<?php $this->load->view('master/public/footer');?>