<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">住宿管理</a>
	</li>
	<li class="active">历史查询</li>
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
	  住宿管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
			<input type="hidden" name="is_userid" value="yes">
			<div>
				<!--标签-->
					<!-- <ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
						<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_history/index?&label_id=1"><h5>正在入住</h5></a>
						</li>
						<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_history/index?&label_id=2"><h5>已经搬走</h5></a>
						</li>
					</ul>   -->
				<!--标签-->
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax admin_index">
					<thead>
						<tr>
							<th class="center">
								ID
							</th>
							<th>英文名</th>
							<th>校区</th>
							<th>楼区</th>
							<th>层数</th>
							<th>房间名</th>
							<th>操作时间</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>

					<tbody role="alert" aria-live="polite" aria-relevant="all">
						
					</tbody>
				</table>
			</div>
			</form>
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
			opt.sAjaxSource = "/master/enrollment/acc_history/index";
		}

		if($(this).hasClass("admin_index")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "userid" },
								{ "mData": "campusid" },
								{ "mData": "buildingid" },
								{ "mData": "floor" },
								{ "mData": "roomid" },
								{ "mData": "time" },
								{ "mData": "state" },
								{ "mData": "operation" }

							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [8] }];
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
pub_alert_confirm( '/master/enrollment/acc_apply/del?id='+id);
}

function upstate(id,state){

	pub_alert_confirm('/master/enrollment/acc_apply/upstate?id='+id+'&state='+state);
	
}
function labour_affirm(id,acc_state){

	pub_alert_confirm('/master/enrollment/acc_apply/labour_affirm_upstate?id='+id+'&acc_state='+acc_state,'','确认交押金？');
	
}
function emphasis_affirm(id,acc_state){
	pub_alert_confirm('/master/enrollment/acc_apply/emphasis_student?id='+id+'&acc_state='+acc_state);
}
</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
