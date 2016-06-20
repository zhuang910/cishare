<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '编辑' : '添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">文章管理</a>
	</li>
	<li ><a href='index'>分类管理</a></li>
	<li class="active">{$title_h3}分类</li>
</ul>
EOD;
?>

<?php $this->load->view('admin/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>admin/js/jquery.validate.min.js"></script>
<?php $this->load->view('admin/public/js_css_ztree');?>

<?php
$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		分类管理
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->

			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<div class="table-header">
						<?=!empty($info)?'编辑':'添加'?>
						<a type="button" title="返回上一级" class="btn btn-primary btn-sm btn-default btn-sm" href="javascript:history.back();" style="float:right;">
							<span class="ace-icon fa fa-reply"></span>
							返回上一级
						</a>
					</div>
					<div class="space-16"></div>
					<form class="form-horizontal" id="validation-form" method="post" action="<?=$access_path?>category/category/update">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">标题:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text"  id="category_name" name="category_name" value="<?=!empty($info->category_name) ? $info->category_name : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">父类别:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text"  id="pid_name" value="<?=!empty($info->pid_name) ? $info->pid_name : ''?>" readonly class="col-xs-12 col-sm-3" />
									&nbsp;<label class="control-label"><a id="menuBtn" href="javascript:;" onclick="showMenu(); return false;">选择</a></label>
									<input type="hidden"  id="pid" name="pid" value="<?=!empty($info->pid) ? $info->pid : ''?>" class="col-xs-12 col-sm-3" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="col-md-offset-3 col-md-9">
							<button type="submit" class="btn btn-info">
								<input type="hidden"  id="id" name="id" value="<?=!empty($info->cat_id) ? $info->cat_id : ''?>"/>
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
						<ul id="treeDemo" class="ztree" style="margin-top: 10px;border: 1px solid #617775;background: #fff;margin-top:0; width:252px;"></ul>
					</div>

		</div>
	</div>
</div>
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/admin/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>admin/js/ace-extra.min.js"></script>
<script src="<?=RES?>/admin/js/ace-elements.min.js"></script>
<script src="<?=RES?>/admin/js/ace.min.js"></script>


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
		$("#menuContent").css({position: "absolute",'left':cityOffset.left - 210 + "px", 'top':cityOffset.top + cityObj.outerHeight() - 156 + "px"}).slideDown("fast");

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

	$('#validation-form').validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: false,
		rules: {

			category_name: {
				required: true
			},

		},

		messages: {
			category_name:{
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
						window.location.href="/admin/article/category";
					}else{
						pub_alert_error();
					}

				})
				.fail(function() {

					pub_alert_error();
				})


		}

	});


</script>

<!-- end script -->
<?php $this->load->view('admin/public/footer');?>