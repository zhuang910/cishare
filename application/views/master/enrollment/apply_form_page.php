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
	<li class="active">
		<a href="/master/enrollment/apply_form">申请表设置</a>
	</li>
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
	  申请表设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
			
			页的管理	
			<a class="btn btn-primary btn-sm btn-default btn-sm" href="javascript:history.back();" type="button" title="添加教室" style="float:right;">
			<span class="ace-icon fa fa-reply"></span>
			返回上一级
			</a>
			<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			添加页
			</button>
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						
						<th>ID</th>
						<th>页的名称</th>
						<th>类型</th>
						<th>群组数</th>
						<th>排序</th>
						<th >操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($lists)):?>
						<?php foreach ($lists as $key => $val) {?>
						<tr>
							
							<td><?=$val['tClass_id'];?></td>
							<td><?=!empty($val['ClassName'])?$val['ClassName']:''?></td>
							<td><?=!empty($pagetype[$val['PageType']])?$pagetype[$val['PageType']]:''?></td>
							<td>共<a href="/master/enrollment/apply_form/group?groupid=<?=$val['tClass_id']?>&tClass_id=<?=$tClass_id?>"><?=$val['count']?></a>群组</td>
							<td><?=!empty($val['line'])?$val['line']:''?></td>
							<td>
								<a href="/master/enrollment/apply_form/new_page?id=<?=$val['tClass_id']?>&tClass_id=<?=$tClass_id?>" class="btn btn-xs btn-info">编辑</a>
							
								<a href="javascript:;" onclick="del(<?=$val['tClass_id']?>)" class="btn btn-xs btn-info btn-white">删除</a>
							</td>
						</tr>
						<?php }?>
						<?php endif;?>
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
		window.location.href='/master/enrollment/apply_form/new_page?tClass_id=<?=$tClass_id?>';
	}
</script>
<script type="text/javascript">
// dataTables
if($('.dataTable').length > 0){
	$('.dataTable').each(function(){
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
			opt.sAjaxSource = "<?=$zjjp?>page";
		}
opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 5 ] }];
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}

	});
}

function del(id){
	pub_alert_confirm('/master/enrollment/apply_form/del_group_global?id='+id);
	
}

</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>