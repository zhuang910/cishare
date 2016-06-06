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
			全局项
			<a class="btn btn-primary btn-sm btn-default btn-sm" href="javascript:history.back();" type="button" title="添加教室" style="float:right;">
			<span class="ace-icon fa fa-reply"></span>
			返回上一级
			</a>
			<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			添加全局项
			</button>
				
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						
							<th>ID</th>
							<th width="300">表单项名称</th>
							<th>ID</th>
							<th>类型</th>
							<th>事件</th>
							<th>必填</th>
							<th>隐藏</th>
							<th>排序</th>
							<th >操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($lists)):?>
						<?php foreach ($lists as $key => $val) {?>
						<tr>
							
							<td><?=$val['topic_id'];?></td>
							<td><?=!empty($val['formTitle'])?$val['formTitle']:''?></td>
							<td><?=!empty($val['formID'])?$val['formID']:''?></td>
							<td><?=!empty($formType[$val['formType']])?$formType[$val['formType']]:''?></td>
							<td><?=!empty($val['isonclick'])?$val['isonclick']:''?></td>
							<td><?=!empty($isInput[$val['isInput']])?$isInput[$val['isInput']]:'不必填'?></td>
							<td><?=!empty($isHidden[$val['isHidden']])?$isHidden[$val['isHidden']]:'显示'?></td>
							<td><?=!empty($val['line'])?$val['line']:''?></td>
							<td>
								
								<a href="/master/enrollment/apply_form/new_global_item?id=<?=$val['topic_id']?>&formType=<?=$val['formType']?>" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
								|
								<a href="javascript:;" onclick="del(<?=$val['topic_id']?>)" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
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
	
	function add(){
		window.location.href='/master/enrollment/apply_form/add_new_global_item';
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
			opt.sAjaxSource = "/master/enrollment/apply_form/global_items";
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
	pub_alert_confirm('/master/enrollment/apply_form/del_items?id='+id);
}
</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
