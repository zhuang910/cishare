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
	<li class="active">申请表设置</li>
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
				申请表模版列表
				<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
				<span class="glyphicon  glyphicon-plus"></span>
				添加申请表模版
				</button>	
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						
							<th>ID</th>
							<th width="630">模版名称</th>
							<th>属性</th>
							<th>页数</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($lists)):?>
						<?php foreach ($lists as $key => $val) {?>
						<?php 
							if($val['tClass_id'] == 1){
						?>
						<tr>
							<td><?=$val['tClass_id'];?></td>
							<td>全局项</td>
							<td><a href="/master/enrollment/apply_form/global_items">[管理全局表单项]</a>
								<a href="/master/enrollment/apply_form/global_group">[管理全局群组项]</a>
							</td>
							<td>共<a href="/master/enrollment/apply_form/page?tClass_id=<?=$val['tClass_id']?>"><?=$val['count']?></a>页</td>
							<td></td>
					
						</tr>
						<?php }else{?>
						<tr>
							
							<td><?=$val['tClass_id'];?></td>
							<td><?=!empty($val['ClassName'])?$val['ClassName']:''?></td>
							<td><?=!empty($val['classKind']) && $val['classKind'] == 'Y'?'默认模版':''?></td>
							<td>共<a href="/master/enrollment/apply_form/page?tClass_id=<?=$val['tClass_id']?>"><?=$val['count']?></a>页</td>
							<td>
								<a href="/master/enrollment/apply_form/new_templates?id=<?=$val['tClass_id']?>" class="btn btn-xs btn-info">编辑</a>
							
								<a href="javascript:;" onclick="del(<?=$val['tClass_id']?>)" class="btn btn-xs btn-info btn-white">删除</a>
							</td>
						</tr>
						<?php }?>
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
		window.location.href='/master/enrollment/apply_form/new_templates';
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
			opt.sAjaxSource = "<?=$zjjp?>index";
		}
opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4 ] }];
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
