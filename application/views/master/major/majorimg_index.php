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
	<li><a href="/master/major/major">专业设置</a></li>
	<li class="active">图集管理</li>
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
	专业管理 图集管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				<div class="table-header">
				专业管理图集列表
				<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-defalut btn-sm" title="添加图集" type="button">
				<span class="glyphicon  glyphicon-plus"></span>
				添加图集
				</button>	
				<a style="float:right;" href="javascript:history.back();" class="btn btn-primary btn-sm btn-default btn-sm" title="添加教室" type="button">
					<span class="ace-icon fa fa-reply"></span>
					专业管理
				</a>
				</div>
			</div>
			<div>
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax cms_news dataTable-url" data-ajax-url='/master/major/majorimg/index?label_id=<?=$label_id?>&majorid=<?=$majorid?>'>
					<thead>
						<tr>
							<th class="center">
								ID
							</th>
							<th>标题</th>
							<th>排序</th>
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
<link rel="stylesheet" href="<?=RES?>master/css/ace.onpage-help.css" />
<script type="text/javascript">
	function add(){
		window.location.href='/master/major/majorimg/edit?label_id=<?=$label_id?>&majorid=<?=$majorid?>';
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
				opt.sAjaxSource = "/master/major/majorimg/index?label_id=<?=$label_id?>&majorid=<?=$majorid?>";
			}
		}

		if($(this).hasClass("cms_news")){
			opt.bStateSave = false;
			opt.aoColumns = [

								{ "mData": "id" },
								{ "mData": "title" },
								{ "mData": "orderby" },
								{ "mData": "operation" }
							];
			opt.aaSorting = [[3,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [3 ] }];
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
	pub_alert_confirm('/master/major/majorimg/del?id='+id);

}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>