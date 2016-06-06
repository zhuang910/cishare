<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">权限管理</a>
	</li>
	<li>
		<a href="javascript:;">账号管理</a>
	</li>
	<li class="active">教师账号</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
         <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	  教师账号
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				教师列表
				<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加老师" type="button">
				<span class="glyphicon  glyphicon-plus"></span>
				添加教师
				</button>
				<button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>teacher/tochanel?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="导入" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					导入
				</button>	
				<a style="float:right;" href="<?=$zjjp?>teacher/export" class="btn btn-primary btn-sm btn-default btn-sm" title="导出" type="button">
						<span class="glyphicon  glyphicon-plus"></span>
						导出
				</a>		
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax admin_index">
					<thead>
						<tr>
							<th class="center">
								ID
							</th>
							<th>用户名</th>
							<th>姓名</th>
							<th>登录邮箱</th>
							<th>性别</th>
							<th>电话</th>
							<th>手机</th>
							<th>最后登录时间</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>

					<tbody role="alert" aria-live="polite" aria-relevant="all">
						
					</tbody>
				</table>
			</div>
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
<script src="<?=RES?>master/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.bootstrap.js"></script>
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<script type="text/javascript">
	
	function add(){
		window.location.href='/master/authority/teacher/add';
	}
</script>
<script type="text/javascript">
	if($('#sample-table-2').length > 0){
	$('#sample-table-2').each(function(){
		var opt = {
			"iDisplayLength" : 25,
			"sPaginationType": "full_numbers",
			"oLanguage":{
				"sSearch": "<span>搜索:</span> ",
				"sInfo": "<span>_START_</span> - <span>_END_</span> 共 <span>_TOTAL_</span>",
				"sLengthMenu": "_MENU_ <span>条每页</span>",
				"oPaginate": {
					"sFirst" : "首页",
					"sLast" : "尾页",
			   		"sPrevious": " 上一页 ",
			   		"sNext":     " 下一页 "
		   		},
				"sInfoEmpty" : "没有记录",
				"sInfoFiltered" : "",
				"sZeroRecords" : '没有找到想匹配记录'
			}
		};
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "/master/authority/teacher/index";
		}

		if($(this).hasClass("admin_index")){
			opt.bStateSave = false;
			opt.aoColumns = [

								{ "mData": "id" },
								{ "mData": "username" },
								{ "mData": "name" },
								{ "mData": "email" },
								{ "mData": "sex" },
								{ "mData": "tel" },
								{ "mData": "phone" },
								{ "mData": "lasttime" },
								{ "mData": "state" },
								{ "mData": "operation" }
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 9 ] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}

function del(id){
	pub_alert_confirm('/master/authority/teacher/del?id='+id);
}

function upstate(id,state){
	pub_alert_confirm('/master/authority/teacher/upstate?id='+id+'&state='+state);
	
}

function uppassword(id){
	pub_alert_confirm('/master/authority/teacher/uppassword?id='+id);
	
}


</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
