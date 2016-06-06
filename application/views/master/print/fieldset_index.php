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
		<a href="javascript:;">基本设置</a>
	</li>
	<li class="avtive">打印设置</li>
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
		打印设置
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
			<div class="tabbable">
				<div>
					<div class="table-header">
						字段列表
							
						<button style="float:right;" onclick="pub_alert_html('/master/print/printsetting/fieldsadd?print_templateid=<?=$print_templateid?>')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加字段" type="button">
						<span class="glyphicon  glyphicon-plus"></span>
						添加字段
						</button>
						<a style="float:right;" href="javascript:history.back();" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院">
						<i class="ace-icon fa fa-reply icon-only"></i>
						返回
						</a>	
					</div>

					<!-- <div class="table-responsive"> -->

					<!-- <div class="dataTables_borderWrap"> -->
					<div>                                  
						<table id="sample-table-3" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
							<thead>
								<tr>
									<th class="center">
										ID
									</th>
									<th>名称</th>
									<th>字段</th>
									<th>模板名</th>
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
<script type="text/javascript">
function del_fields(id){
			pub_alert_confirm('/master/print/printsetting/del_fields?id='+id);
}
function del(id){
			pub_alert_confirm('/master/print/printsetting/del?id='+id);
}
	function add(){
		window.location.href='/master/print/printsetting/add';
	}

	if($('#sample-table-3').length > 0){
	$('#sample-table-3').each(function(){
		var opt = {
			"iDisplayLength" : 15,
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
			opt.sAjaxSource = "<?=$zjjp?>printsetting/fieldset?print_templateid=<?=$print_templateid?>";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "fieldsvalue" },
								{ "mData": "print_templateid" },
								{"mData":"operation"},
							
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 2 ] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}

</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>