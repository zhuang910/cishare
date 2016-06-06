<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">信息管理</a>
	</li>
	<li>
		<a href="javascript:;">内容管理</a>
	</li>
	<li class="active">文章管理</li>
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
		<?=$site_language_admin[$_SESSION['language']]?>文章管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				<div class="table-header">
				文章列表
				<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
				<span class="glyphicon  glyphicon-plus"></span>
				添加文章
				</button>	
				</div>
			</div>
			<div>
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax cms_news dataTable-url" data-ajax-url='/master/cms/news/index?label_id=<?=$label_id?>'>
					<thead>
						<tr>
							<th class="center">
								ID
							</th>
							<th>标题</th>
							<th>排序</th>
							<th>更新时间</th>

							<th>
								所属栏目
							</th>
							<th>状态</th>

							<th>操作</th>
						</tr>
					</thead>
					<thead>
						<tr>
							<th>
                                <input type="text" id="atr_id" placeholder="ID" style="width:50px;">
                            </th>
							<th>
                                 <input type="text" id="atr_title" placeholder="标题" style="width:300px;">
                            </th>
							<th>
                                 <input type="text" id="atr_orderby" placeholder="排序" style="width:100px;">
                            </th>
							 <th>
                                 <input type="text" id="atr_time" placeholder="更新时间" style="width:100px;">
                            </th>
                            <th>
                                <select id="atr_colum" style="width:100px;">
									<option value="">--栏目-</option>
									<?php foreach($column_info as $k=>$v):?>
										<option value="<?=$v['id']?>"><?=$v['title']?></option>
									<?php endforeach;?>
								</select>
                            </th>
							<th>
                                <select id="atr_state" style="width:100px;">
									<option value="">--状态-</option>
									<option value="1">启用</option>
									<option value="-1">停用</option>
								</select>
                            </th>
							<th></th>
							
							
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
		window.location.href='/master/cms/news/edit?label_id=<?=$label_id?>';
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
				opt.sAjaxSource = "<?=$zjjp?>/news";
			}
		}

		if($(this).hasClass("cms_news")){
			opt.bStateSave = false;
			opt.aoColumns = [

								{ "mData": "id" },
								{ "mData": "title" },
								{ "mData": "orderby" },
								{ "mData": "lasttime" },
								{ "mData": "columnid" },
								{ "mData": "state" },
								{ "mData": "operation" }
							];
			opt.aaSorting = [[4,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 6 ] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});

	$('#atr_id').on( 'keyup', function () {
            zjj_datatable_search(0,$("#atr_id").val());
        } );
		
		$('#atr_title').on( 'keyup', function () {
            zjj_datatable_search(1,$("#atr_title").val());
        } );
		$('#atr_orderby').on( 'keyup', function () {
            zjj_datatable_search(2,$("#atr_orderby").val());
        } );

        $('#atr_state').change(function () {
            zjj_datatable_search(3,$("#atr_state").val());
        } );
        $('#atr_colum').change(function () {
            zjj_datatable_search(5,$("#atr_colum").val());
        } );
		   $('#atr_time').on( 'keyup', function () {
            zjj_datatable_search(4,$("#atr_time").val());
        } );
	

        function zjj_datatable_search(column,val){
            $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
        }
}

function del(id){
	pub_alert_confirm('/master/cms/news/del?id='+id);

}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>