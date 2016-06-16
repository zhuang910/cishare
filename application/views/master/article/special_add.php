<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑':'添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>
	<li class="active">专题管理</li>
	<li>{$r}</li>
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>	

<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php $this->load->view('master/public/js_css_kindeditor');?>
<?php $this->load->view('master/public/js_css_ztree');?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	专题管理
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green"><?=!empty($info)?'编辑':'添加'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
				</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>special/special/save">
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">名称:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
								<input type="text"  id="name" name="name" value="<?=!empty($info->name) ? $info->name : ''?>" class="col-xs-12 col-sm-5" />
						</div>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">上传主图:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<a href="javascript:swfupload('image_pic','face_pic','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo)">
								<img id="image_pic"  src="<?=!empty($info->image)?$info->image:'/resource/master/images/admin_upload_thumb.png'?>" width="135" height="113">
							</a>
						</div>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">父专题:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text"  id="pid_name" value="<?=!empty($info->pid_name) ? $info->pid_name : ''?>" readonly class="col-xs-12 col-sm-3" />
							&nbsp;<label class="control-label"><a id="menuBtn" href="javascript:;" onclick="showMenu(); return false;">选择</a></label>
							<input type="hidden"  id="pid" name="pid" value="<?=!empty($info->pid) ? $info->pid : ''?>" class="col-xs-12 col-sm-3" />
						</div>
					</div>
				</div>

				<div class="space-2"></div>
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
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

			<div id="menuContent" class="menuContent" style="display:none; position: absolute;">
				<ul id="treeDemo" class="ztree" style="margin-top: 10px;border: 1px solid #617775;background: #fff;margin-top:0; width:192px;"></ul>
			</div>

		</div>
	</div>
</div>
<script src="<?=RES?>master/js/upload.js"></script>
<!-- ace scripts -->
<script src="/resource/master/js/ace-extra.min.js"></script>
<script src="/resource/master/js/ace-elements.min.js"></script>
<script src="/resource/master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<?php $this->load->view('master/public/js_kindeditor_create')?>

<script type="text/javascript">
	<!--
	var setting = {
		view: {
			dblClickExpand: false,
			showIcon: showIconForTree
		},
		data: {
			simpleData: {
				enable: true
			}
		},
		callback: {
			//beforeClick: beforeClick,
			onClick: onClick
		}
	};

	function showIconForTree(treeId, treeNode) {
		return !treeNode.isParent;
	};

	var zNodes = <?=$list?>;

	function beforeClick(treeId, treeNode) {
		var check = (treeNode && !treeNode.isParent);
		if (!check) alert("只能选择城市...");
		//return check;
	}

	function onClick(e, treeId, treeNode) {
		var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
			nodes = zTree.getSelectedNodes(),
			v = v_id = "";
		nodes.sort(function compare(a,b){return a.id-b.id;});
		for (var i=0, l=nodes.length; i<l; i++) {
			v = nodes[i].name;
			v_id = nodes[i].id;
		}
		//if (v.length > 0 ) v = v.substring(0, v.length-1);
		var cityObj = $("#pid_name");
		var pidObj = $("#pid");
		cityObj.attr("value", v);
		pidObj.attr("value", v_id);
		hideMenu();
	}

	function showMenu() {
		var cityObj = $("#pid_name");
		var cityOffset = $("#pid_name").offset();
		$("#menuContent").css({position: "absolute",'left':cityOffset.left - 198 + "px", 'top':cityOffset.top + cityObj.outerHeight() - 156 + "px"}).slideDown("fast");

		$("body").bind("mousedown", onBodyDown);
	}
	function hideMenu() {
		$("#menuContent").fadeOut("fast");
		$("body").unbind("mousedown", onBodyDown);
	}
	function onBodyDown(event) {
		if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
			hideMenu();
		}
	}

	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	});
	//-->


	$(document).ready(function(){
		$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						name: {
							required: true
						},
						
					},
			
					messages: {
						name:{
							required:"请输入名称",
						},
						
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
						
						var data=$(form).serialize();
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="/master/special/special/index";
							}else{
								pub_alert_error();
							}
							
						})
						.fail(function() {
							
							pub_alert_error();
						})
						
						
					}
			
		});

	});
</script>

<!-- script -->
<?php $this->load->view('master/public/footer');?>