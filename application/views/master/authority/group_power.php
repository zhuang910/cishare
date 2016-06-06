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


		{ id:111,pId:11,name:'模板设置',zjj_v:'<?=md5('/master/cms/template')?>'<?=!empty($power) && in_array(md5('/master/cms/template'),$power) ? ',checked:true':''?>},
		{ id:112,pId:11,name:'功能开关',zjj_v:'<?=md5('/master/basic/function_on_off')?>'<?=!empty($power) && in_array(md5('/master/basic/function_on_off'),$power) ? ',checked:true':''?>},
		{ id:113,pId:11,name:'多语言设置',zjj_v:'<?=md5('/master/cms/configuration')?>'<?=!empty($power) && in_array(md5('/master/cms/configuration'),$power) ? ',checked:true':''?>},
		{ id:114,pId:11,name:'提醒线设置',zjj_v:'<?=md5('/master/basic/warning_line')?>'<?=!empty($power) && in_array(md5('/master/basic/warning_line'),$power) ? ',checked:true':''?>},

		{ id:121,pId:12,name:'邮件设置',zjj_v:'<?=md5('/master/inform/emailset')?>'<?=!empty($power) && in_array(md5('/master/inform/emailset'),$power) ? ',checked:true':''?>},
		{ id:122,pId:12,name:'通知设置',zjj_v:'<?=md5('/master/message/messagedot')?>'<?=!empty($power) && in_array(md5('/master/message/messagedot'),$power) ? ',checked:true':''?>},
		{ id:123,pId:12,name:'导出设置',zjj_v:'<?=md5('/master/excel/educe')?>'<?=!empty($power) && in_array(md5('/master/excel/educe'),$power) ? ',checked:true':''?>},
		{ id:124,pId:12,name:'打印设置',zjj_v:'<?=md5('/master/print/printsetting')?>'<?=!empty($power) && in_array(md5('/master/print/printsetting'),$power) ? ',checked:true':''?>},
		{ id:125,pId:12,name:'支付设置',zjj_v:'<?=md5('/master/basic/payconf')?>'<?=!empty($power) && in_array(md5('/master/basic/payconf'),$power) ? ',checked:true':''?>},

		{ id:1211,pId:121,name:'邮件发送点',zjj_v:'<?=md5('/master/inform/emaildot')?>'<?=!empty($power) && in_array(md5('/master/inform/emaildot'),$power) ? ',checked:true':''?>},
		{ id:1212,pId:121,name:'自定义发送',zjj_v:'<?=md5('/master/inform/customemail')?>'<?=!empty($power) && in_array(md5('/master/inform/customemail'),$power) ? ',checked:true':''?>},

		{ id:1221,pId:122,name:'自定义发送',zjj_v:'<?=md5('/master/message/customemessage')?>'<?=!empty($power) && in_array(md5('/master/message/customemessage'),$power) ? ',checked:true':''?>},

		{ id:131,pId:13,name:'学历设置',zjj_v:'<?=md5('/master/basic/degree')?>'<?=!empty($power) && in_array(md5('/master/basic/degree'),$power) ? ',checked:true':''?>},
		{ id:132,pId:13,name:'专业设置',zjj_v:'<?=md5('/master/major')?>'<?=!empty($power) && in_array(md5('/master/major'),$power) ? ',checked:true':''?>},
		{ id:133,pId:13,name:'申请表设置',zjj_v:'<?=md5('/master/enrollment/apply_form')?>'<?=!empty($power) && in_array(md5('/master/enrollment/apply_form'),$power) ? ',checked:true':''?>},
		{ id:134,pId:13,name:'附件设置',zjj_v:'<?=md5('/master/enrollment/attachment')?>'<?=!empty($power) && in_array(md5('/master/enrollment/attachment'),$power) ? ',checked:true':''?>},
		{ id:135,pId:13,name:'奖学金设置',zjj_v:'<?=md5('/master/basic/scholarship')?>'<?=!empty($power) && in_array(md5('/master/basic/scholarship'),$power) ? ',checked:true':''?>},
		{ id:135,pId:13,name:'住宿设置',zjj_v:'<?=md5('/master/enrollment/acc_camp')?>'<?=!empty($power) && in_array(md5('/master/enrollment/acc_camp'),$power) ? ',checked:true':''?>},

		{ id:1321,pId:132,name:'专业图片设置',zjj_v:'<?=md5('/master/major/majorimg')?>'<?=!empty($power) && in_array(md5('/master/major/majorimg'),$power) ? ',checked:true':''?>},
		{ id:1322,pId:132,name:'专业评论设置',zjj_v:'<?=md5('/master/major/majorpl')?>'<?=!empty($power) && in_array(md5('/master/major/majorpl'),$power) ? ',checked:true':''?>},
		{ id:1323,pId:132,name:'班级管理',zjj_v:'<?=md5('/master/major/squad')?>'<?=!empty($power) && in_array(md5('/master/major/squad'),$power) ? ',checked:true':''?>},

		{ id:1351,pId:135,name:'住宿楼设置',zjj_v:'<?=md5('/master/enrollment/building')?>'<?=!empty($power) && in_array(md5('/master/enrollment/building'),$power) ? ',checked:true':''?>},
		{ id:1352,pId:135,name:'住宿楼图片设置',zjj_v:'<?=md5('/master/enrollment/buildingimg')?>'<?=!empty($power) && in_array(md5('/master/enrollment/buildingimg'),$power) ? ',checked:true':''?>},
		{ id:1353,pId:135,name:'住宿楼价格设置',zjj_v:'<?=md5('/master/enrollment/buildingprice')?>'<?=!empty($power) && in_array(md5('/master/enrollment/buildingprice'),$power) ? ',checked:true':''?>},

		{ id:141,pId:14,name:'时间设置',zjj_v:'<?=md5('/master/basic/hour')?>'<?=!empty($power) && in_array(md5('/master/basic/hour'),$power) ? ',checked:true':''?>},
		{ id:142,pId:14,name:'院系设置',zjj_v:'<?=md5('/master/basic/faculty')?>'<?=!empty($power) && in_array(md5('/master/basic/faculty'),$power) ? ',checked:true':''?>},
		{ id:143,pId:14,name:'课程设置',zjj_v:'<?=md5('/master/basic/course')?>'<?=!empty($power) && in_array(md5('/master/basic/course'),$power) ? ',checked:true':''?>},
		{ id:144,pId:14,name:'老师设置',zjj_v:'<?=md5('/master/teacher')?>'<?=!empty($power) && in_array(md5('/master/teacher'),$power) ? ',checked:true':''?>},
		{ id:145,pId:14,name:'教室设置',zjj_v:'<?=md5('/master/basic/classroom')?>'<?=!empty($power) && in_array(md5('/master/basic/classroom'),$power) ? ',checked:true':''?>},
		{ id:146,pId:14,name:'考试设置',zjj_v:'<?=md5('/master/score/itemsetting')?>'<?=!empty($power) && in_array(md5('/master/score/itemsetting'),$power) ? ',checked:true':''?>},
		{ id:148,pId:14,name:'书籍设置',zjj_v:'<?=md5('/master/basic/books')?>'<?=!empty($power) && in_array(md5('/master/basic/books'),$power) ? ',checked:true':''?>},
		{ id:147,pId:14,name:'考勤通知设置',zjj_v:'<?=md5('/master/basic/attendance_notice_set')?>'<?=!empty($power) && in_array(md5('/master/basic/attendance_notice_set'),$power) ? ',checked:true':''?>},
		{ id:149,pId:14,name:'评教时间设置',zjj_v:'<?=md5('/master/evaluate/settime')?>'<?=!empty($power) && in_array(md5('/master/evaluate/settime'),$power) ? ',checked:true':''?>},

		{ id:1441,pId:144,name:'老师添加课程',zjj_v:'<?=md5('/master/teacher/teacher_course')?>'<?=!empty($power) && in_array(md5('/master/teacher/teacher_course'),$power) ? ',checked:true':''?>},

		{id:21,pId:2,name:'帐号管理',zjj_v:'<?=md5('col_username')?>'<?=!empty($power) && in_array(md5('col_username'),$power) ? ',checked:true':''?>},
		{id:22,pId:2,name:'权限分配',zjj_v:'<?=md5('/master/authority/group')?>'<?=!empty($power) && in_array(md5('/master/authority/group'),$power) ? ',checked:true':''?>},
		{id:23,pId:2,name:'日志管理',zjj_v:'<?=md5('/master/authority/log')?>'<?=!empty($power) && in_array(md5('/master/authority/log'),$power) ? ',checked:true':''?>},
		{id:211,pId:21,name:'管理员帐号管理',zjj_v:'<?=md5('/master/authority/admin')?>'<?=!empty($power) && in_array(md5('/master/authority/admin'),$power) ? ',checked:true':''?>},


		{ id:41,pId:4,name:'注册管理',zjj_v:'<?=md5('/master/enrollment/register')?>'<?=!empty($power) && in_array(md5('/master/enrollment/register'),$power) ? ',checked:true':''?>},
		{ id:42,pId:4,name:'申请处理',zjj_v:'<?=md5('/master/enrollment/apply')?>'<?=!empty($power) && in_array(md5('/master/enrollment/apply'),$power) ? ',checked:true':''?>},
		{ id:44,pId:4,name:'接机预定',zjj_v:'<?=md5('/master/enrollment/pickup')?>'<?=!empty($power) && in_array(md5('/master/enrollment/pickup'),$power) ? ',checked:true':''?>},


		{ id:4341,pId:434,name:'电费管理',zjj_v:'<?=md5('/master/enrollment/acc_dispose_electric')?>'<?=!empty($power) && in_array(md5('/master/enrollment/acc_dispose_electric'),$power) ? ',checked:true':''?>},
		{ id:4342,pId:434,name:'住宿费管理',zjj_v:'<?=md5('/master/enrollment/acc_dispose_quarterage')?>'<?=!empty($power) && in_array(md5('/master/enrollment/acc_dispose_quarterage'),$power) ? ',checked:true':''?>},

		{ id:51,pId:5,name:'分类管理',zjj_v:'<?=md5('/master/category')?>'<?=!empty($power) && in_array(md5('/master/category'),$power) ? ',checked:true':''?>},
		{ id:52,pId:5,name:'专题管理',zjj_v:'<?=md5('/master/special')?>'<?=!empty($power) && in_array(md5('/master/special'),$power) ? ',checked:true':''?>},
		
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


