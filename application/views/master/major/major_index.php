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
	<li class="active">专业设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
<link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		专业设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			专业列表
			<button style="float:right;" onclick="updateterm()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加专业" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			更新学期
			</button>
			<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加专业" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			添加专业
			</button>
			<button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>major/major/tochanel?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="导入" type="button">
					<span class="ace-icon fa fa-mail-reply"></span>
					导入
			</button>
			<button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>major/major/export_where?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="导出" type="button">
					<span class="ace-icon fa fa-mail-forward"></span>
					导出
			</button>
		</div>
		<!-- <div class="table-responsive"> -->
		<!-- <div class="dataTables_borderWrap"> -->
		<div>
			<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
				<thead>
					<tr>
						<th class="center">ID</th>
						<th  style="width:150px;">专业名称</th>
						<th>所属院系</th>
						<th style="width:150px;">英文名称</th>
						<th>学位类型</th>
						<th>学期届数</th>
						<th>学期跨度</th>
						<th>课程总数</th>
						<th>班级总数</th>
						<th>状态</th>
						<th width="150"></th>
					</tr>
				</thead>

				<tbody>

				</tbody>
			</table>
		</div>
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
//更新学期
	function updateterm(){
		pub_alert_confirm('/master/major/major/update_term');
	}
function add(){
		window.location.href="/master/major/major/add";
	}

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

		 opt.bAutoWidth=true; 
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "<?=$zjjp?>major/major";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
							
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "fname" },
								{ "mData": "englishname" },
								{ "mData": "degree" },
								{ "mData": "termnum" },
								{ "mData": "termdays" },
								{ "mData": "coursenum" },
								{ "mData": "squadnum" },
								{ "mData": "state" },
								{"mData":"operation"}
								
							
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 10 ] }];
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
			pub_alert_confirm('/master/major/major/del?id='+id);
}

</script>
<script>
function upstate(id,state){
			pub_alert_confirm('/master/major/major/upstate?id='+id+'&state='+state);
}	
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>