<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">权限管理</a>
	</li>
	
	<li class="active">权限管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php $this->load->view('master/public/js_css_ztree');?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		权限管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
	<ul id="treeDemo" class="ztree"></ul>
	<button data-last="Finish" class="btn btn-sm btn-info" onclick="do_authority_save()">
		<i class="ace-icon fa fa-check bigger-110"></i>
		确认
	</button>
	</div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>

<SCRIPT type="text/javascript">
	function do_authority_save(){
		var checked_node = $.fn.zTree.getZTreeObj("treeDemo").getCheckedNodes(true);
		var save_node = [];
		var id = <?=$_GET['id']?>;
		$(checked_node).each(function(i,v){
			save_node.push(v.zjj_v);
		});

		$.ajax({
			type:'post',
			url:'/master/authority/group/savepower',
			data:{power:save_node,id:id},
			dataType:'json',
			success:function(r){
				if(r.state == 1){
					pub_alert_success('授权成功');
				}else{
					pub_alert_error('授权失败');
				}
			}
		});
	}
		<!--
	var setting = {
		check: {
			enable: true,
			chkDisabledInherit: true
		},
		view: {
			showIcon: false
		},
		data: {
			simpleData: {
				enable: true
			}
		}
	};

	var zNodes =[
		{ id:1,pId:0,name:'基础设置',zjj_v:'<?=md5('set_basic')?>',open:true<?=!empty($power) && in_array(md5('set_basic'),$power) ? ',checked:true':''?>},

		{ id:2,pId:0,name:'权限管理',zjj_v:'<?=md5('set_authority')?>',open:true<?=!empty($power) && in_array(md5('set_authority'),$power) ? ',checked:true':''?>},
		{ id:5,pId:0,name:'文章管理',zjj_v:'<?=md5('set_in_article')?>',open:true<?=!empty($power) && in_array(md5('set_in_article'),$power) ? ',checked:true':''?>},

		{ id:11,pId:1,name:'网站设置',zjj_v:'<?=md5('set_web')?>'<?=!empty($power) && in_array(md5('set_web'),$power) ? ',checked:true':''?>},
		{ id:12,pId:1,name:'基本设置',zjj_v:'<?=md5('set_basic_2')?>'<?=!empty($power) && in_array(md5('set_basic_2'),$power) ? ',checked:true':''?>},
		{ id:13,pId:1,name:'申请设置',zjj_v:'<?=md5('set_apply')?>'<?=!empty($power) && in_array(md5('set_apply'),$power) ? ',checked:true':''?>},
		{ id:14,pId:1,name:'在学设置',zjj_v:'<?=md5('set_in_school_2')?>'<?=!empty($power) && in_array(md5('set_in_school_2'),$power) ? ',checked:true':''?>},

		{id:21,pId:2,name:'帐号管理',zjj_v:'<?=md5('col_username')?>'<?=!empty($power) && in_array(md5('col_username'),$power) ? ',checked:true':''?>},
		{id:22,pId:2,name:'权限分配',zjj_v:'<?=md5('/master/authority/group')?>'<?=!empty($power) && in_array(md5('/master/authority/group'),$power) ? ',checked:true':''?>},
		{id:23,pId:2,name:'日志管理',zjj_v:'<?=md5('/master/authority/log')?>'<?=!empty($power) && in_array(md5('/master/authority/log'),$power) ? ',checked:true':''?>},
		{id:211,pId:21,name:'管理员帐号管理',zjj_v:'<?=md5('/master/authority/admin')?>'<?=!empty($power) && in_array(md5('/master/authority/admin'),$power) ? ',checked:true':''?>},

		{ id:51,pId:5,name:'文章管理',zjj_v:'<?=md5('/master/article')?>'<?=!empty($power) && in_array(md5('/master/article'),$power) ? ',checked:true':''?>},
		{ id:51,pId:5,name:'分类管理',zjj_v:'<?=md5('/master/article/category')?>'<?=!empty($power) && in_array(md5('/master/article/category'),$power) ? ',checked:true':''?>},
		{ id:52,pId:5,name:'专题管理',zjj_v:'<?=md5('/master/article/special')?>'<?=!empty($power) && in_array(md5('/master/article/special'),$power) ? ',checked:true':''?>},
		{ id:51,pId:5,name:'评论管理',zjj_v:'<?=md5('/master/article/reply')?>'<?=!empty($power) && in_array(md5('/master/article/reply'),$power) ? ',checked:true':''?>},
		
	];	

	function disabledNode(e) {
		var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
			disabled = e.data.disabled,
			nodes = zTree.getSelectedNodes(),
			inheritParent = false, inheritChildren = false;
		if (nodes.length == 0) {
			alert("请先选择一个节点");
		}
		if (disabled) {
			inheritParent = $("#py").attr("checked");
			inheritChildren = $("#sy").attr("checked");
		} else {
			inheritParent = $("#pn").attr("checked");
			inheritChildren = $("#sn").attr("checked");
		}

		for (var i=0, l=nodes.length; i<l; i++) {
			zTree.setChkDisabled(nodes[i], disabled, inheritParent, inheritChildren);
		}
	}

	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"), setting, zNodes);
		$("#disabledTrue").bind("click", {disabled: true}, disabledNode);
		$("#disabledFalse").bind("click", {disabled: false}, disabledNode);
		$("#disabledTrue").removeAttr('href');
		$("#disabledFalse").removeAttr('href');

	});
	//-->
</SCRIPT>
<?php $this->load->view('master/public/footer');?>


