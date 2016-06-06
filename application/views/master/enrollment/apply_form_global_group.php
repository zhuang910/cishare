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
			全局群组

			<a class="btn btn-primary btn-sm btn-default btn-sm" href="javascript:history.back();" type="button" title="返回上一级" style="float:right;">
			<span class="ace-icon fa fa-reply"></span>
			返回上一级
			</a>
			<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加全局群组" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			添加全局群组
			</button>
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						
								<th>ID</th>
							<th>群组的名称</th>
							<th>表单项数</th>
							<th>隐藏</th>
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
							<td>共<a href="/master/enrollment/apply_form/items?itemsid=<?=$val['tClass_id']?>"><?=$val['count']?></a>表单项</td>
							
							<td><?=!empty($classkind[$val['classKind']])?$classkind[$val['classKind']]:''?></td>
							<td><?=!empty($val['line'])?$val['line']:''?></td>
							<td>
								
									<a href="/master/enrollment/apply_form/new_global_group?id=<?=$val['tClass_id']?>" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
								|
								<a href="javascript:;" onclick="del(<?=$val['tClass_id']?>)" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>

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
		window.location.href='/master/enrollment/apply_form/new_group_global';
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
			opt.sAjaxSource = "/master/enrollment/apply_form/global_group";
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
	pub_alert_confirm('/master/enrollment/apply_form/del_group_global?id='+id);
}
</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
