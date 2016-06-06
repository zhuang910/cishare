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
		<a href="javascript:;">申请表设置</a>
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
			
			群组的管理	
			<a class="btn btn-primary btn-sm btn-default btn-sm" href="javascript:history.back();" type="button" title="添加教室" style="float:right;">
			<span class="ace-icon fa fa-reply"></span>
			返回上一级
			</a>
			<button style="float:right;" onclick="add_new()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			添加新群组
			</button>
			<button style="float:right;" onclick="add_old()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			添加已有群组
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
							<td>共<a href="/master/enrollment/apply_form/items?itemsid=<?=$val['tClass_id']?>&tClass_id=<?=$tClass_id?>"><?=$val['count']?></a>表单项</td>
							
							<td><?=!empty($classkind[$val['classKind']])?$classkind[$val['classKind']]:''?></td>
							<td><?=!empty($val['line'])?$val['line']:''?></td>
							<td>
								<a href="/master/enrollment/apply_form/new_group?id=<?=$val['tClass_id']?>&groupid=<?=$pageid?>&tClass_id=<?=$tClass_id?>" class="btn btn-xs btn-info">编辑</a>
							
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

		<div id="dialog-confirm" class="hide">
			<!--<div class="alert alert-info bigger-110" id="confirm">
				您确定要删除吗?删除后将不能恢复.
			</div>

			<div class="space-6"></div>-->

			<p class="bigger-110 bolder center grey">
				<i class="ace-icon fa fa-hand-o-right blue bigger-120"></i>
				Are you sure?
			</p>
		</div>
		<div id="dialog-message" class="hide">
			<!--<p>
				This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.
			</p>

			<div class="hr hr-12 hr-double"></div>-->
			
			<p>
				Are you sure?
			</p>
		</div><!-- #dialog-message -->
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
	
	function add_new(){
		window.location.href='/master/enrollment/apply_form/new_group?groupid=<?=$pageid?>&tClass_id=<?=$tClass_id?>';
	}
	
	function add_old(){
		window.location.href='/master/enrollment/apply_form/new_group_page?groupid=<?=$pageid?>&tClass_id=<?=$tClass_id?>';
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
			opt.sAjaxSource = "<?=$zjjp?>group";
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
