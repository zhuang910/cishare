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
		<a href="javascript:;">申请设置</a>
	</li>
	
	<li class="active">住宿设置</li>
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
		住宿设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				<div class="table-header">
					宿舍楼管理 房间管理
					<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加房间" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					添加房间
					</button>
					<a style="float:right;" href="javascript:history.back();" class="btn btn-primary btn-sm btn-default btn-sm" title="添加教室" type="button">
					<span class="ace-icon fa fa-reply"></span>
					返回宿舍楼
					</a>
				</div>
			</div>
			<!--<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				<?php if(!empty($site_language_admin)){ 
					foreach($site_language_admin as $ak => $av){
				?>
					<li <?php if(!empty($label_id) && $label_id ==$ak):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/buildingprice/index?label_id=<?=$ak?>&buildingid=<?=$buildingid?>"><h5><?=$av?></h5></a>
                    </li>
				<?php }}?>
			</ul>-->

			<div>
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax cms_news dataTable-url" data-ajax-url='/master/enrollment/buildingprice/index?buildingid=<?=$buildingid?>'>
					<thead>
						<tr>
						<th class="center">
								ID
							</th>
							<th>房间中文名称</th>
							<th>房间英文名称</th>
							<th>房间类型</th>
							<th>所在楼层</th>
							<th>价格</th>
							<th>是否开放预订</th>
							<th>简介</th>
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

		<div id="dialog-confirm" class="hide">
			<div class="alert alert-info bigger-110">
				您确定要删除吗?删除后将不能恢复.
			</div>

			<div class="space-6"></div>

			<p class="bigger-110 bolder center grey">
				<i class="ace-icon fa fa-hand-o-right blue bigger-120"></i>
				Are you sure?
			</p>
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
<link rel="stylesheet" href="<?=RES?>master/css/ace.onpage-help.css" />
<script type="text/javascript">
	function add(){
		window.location.href='/master/enrollment/buildingprice/add_buildingprice?buildingid=<?=$buildingid?>';
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
			if($(this).hasClass("dataTable-url")){
				opt.sAjaxSource = $(this).attr('data-ajax-url');
			}else{
				opt.sAjaxSource = "/master/enrollment/buildingprice/index?buildingid=<?=$buildingid?>";
			}
		}

		if($(this).hasClass("cms_news")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "enname" },
								{ "mData": "campusid" },
								{ "mData": "floor" },
								{ "mData": "prices" },
								{ "mData": "is_reserve" },
								{ "mData": "remark" },
								{ "mData": "operation" }
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [8 ] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}
function del(id,label_id){
	pub_alert_confirm('/master/enrollment/buildingprice/delete?id='+id+'&label_id='+label_id);
}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>