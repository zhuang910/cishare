<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">测试管理</a>
	</li>
	<li class="active">试卷管理</li>
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
		试题管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			试题组列表
			<button style="float:right;" onclick="pub_alert_html('/master/test/test_paper/add_paper_group?s=1&paperid=<?=$paperid?>')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加书籍" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			添加试题组
			</button>
			<a class="btn btn-primary btn-sm btn-default btn-sm"  href="javascript:history.back();"  title="返回试卷"  style="float:right;">
			<span class="ace-icon fa fa-reply"></span>
			返回试卷
			</a>
		</div>
		<!-- <div class="table-responsive"> -->
		<!-- <div class="dataTables_borderWrap"> -->
		<div>                                  
			<table id="group" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
				<thead>
					<tr>
						<th>
							<label class="position-relative">
							<!-- <input type="checkbox" class="ace" />
							<span class="lbl"></span> -->
							ID
							</label>
						</th>
						<th>分类</th>
						<th>分数</th>
						<th>状态</th>
						<th width="200"></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
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
		window.location.href="/master/test/test_paper/add";
	}
	if($('#group').length > 0){
	$('#group').each(function(){
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
			opt.sAjaxSource = "/master/test/test_paper/set?paperid=<?=$paperid?>";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "all_score" },
								{ "mData": "state" },
								{"mData":"operation"}
							
							];
			opt.aaSorting = [[0,'asc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [4] }];
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
	pub_alert_confirm('/master/test/test_paper/del_paper_group?id='+id);

}

</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>
