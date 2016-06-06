<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">收费管理</a>
	</li>
	<li>
		<a href="javascript:;">费用分类管理</a>
	</li>
	<li class="active">换证费管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
<?php $this->load->view('master/public/js_css_kindeditor');?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		费用分类管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
						
			<div class="table-header">
				学生列表
				<!-- <button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>books/add?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加书籍" type="button">
				<span class="glyphicon  glyphicon-plus"></span>
				添加书籍
				</button>
				<button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>books/tochanel?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
						<span class="glyphicon  glyphicon-plus"></span>
						导入
				</button>
				<a style="float:right;" href="/master/basic/books/export" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
						<span class="glyphicon  glyphicon-plus"></span>
						导出
				</a>	 -->
			</div>
			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>                                  
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
					<thead>
						<tr>
							<th>
								<label class="position-relative">
								<!-- <input type="checkbox" class="ace" />
								<span class="lbl"></span> -->
								ID
								</label>
							</th>
							<th>姓名</th>
							<th>专业</th>
							<th>班级</th>
							<th>邮箱</th>
							<th>护照</th>
							<th>学期</th>
							<th>缴费金额</th>
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
		window.location.href="/master/basic/course/add";
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
			opt.sAjaxSource = "<?=$zjjp?>barter_card";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "majorid" },
								{ "mData": "squadid" },
								{ "mData": "email" },
								{ "mData": "passport" },
								{ "mData": "term" },
								{ "mData": "money" },
								{"mData":"operation"}
							
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
	pub_alert_confirm('/master/charge/barter_card/del?id='+id);

}



	$(document).on('click', 'th input:checkbox' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
				});
			

</script>



<!-- end script -->
<?php $this->load->view('master/public/footer');?>
