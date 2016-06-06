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
	<li class="active">社团账号</li>
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
	  社团账号
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				社团列表
				<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加社团" type="button">
				<span class="glyphicon  glyphicon-plus"></span>
				添加社团
				</button>	
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
							<th>中文名</th>
							<th>英文名</th>
							<th>登录邮箱</th>
							<th>电话</th>
							<th>手机</th>
							<th>最后登录时间</th>
							<th>状态</th>
							<th width="160">操作</th>
						</tr>
					</thead>

					<tbody role="alert" aria-live="polite" aria-relevant="all">
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

		<div id="dialog-confirm" class="hide">
			<!--<div class="alert alert-info bigger-110" id="confirm">
				您确定要删除吗?删除后将不能恢复.
			</div>

			<div class="space-6"></div>-->

			<p class="bigger-110 bolder center grey">
				<i class="ace-icon fa fa-hand-o-right blue bigger-120"></i>
				Are you sure?
			</p>
		</div>
		<div id="dialog-message" class="hide">
			<!--<p>
				This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.
			</p>

			<div class="hr hr-12 hr-double"></div>-->
			
			<p>
				Are you sure?
			</p>
		</div><!-- #dialog-message -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!-- script -->
<script src="<?=RES?>master/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.bootstrap.js"></script>
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<script type="text/javascript">
	
	function add(){
		window.location.href='<?=$zjjp?>society/add';
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
			opt.sAjaxSource = "/master/authority/society/index";
		}

		if($(this).hasClass("admin_index")){
			opt.bStateSave = false;
			opt.aoColumns = [

								{ "mData": "id" },
								{ "mData": "cnname" },
								{ "mData": "enname" },
								{ "mData": "email" },
								{ "mData": "tel" },
								{ "mData": "mobile" },
								{ "mData": "lasttime" },
								{ "mData": "state" },
								{ "mData": "operation" }
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 8 ] }];
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
	pub_alert_confirm('/master/authority/society/del?id='+id);
}

function upstate(id,state){
	pub_alert_confirm('/master/authority/society/upstate?id='+id+'&state='+state);
	
}

function uppassword(id){
	pub_alert_confirm('/master/authority/society/uppassword?id='+id);
	
}


</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
