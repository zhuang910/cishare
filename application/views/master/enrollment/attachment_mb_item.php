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
		<a href="javascript:;">附件设置</a>
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
	  附件设置
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
			模版项列表
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
						<th width="50">ID</th>
						<th width="350">名称</th>
						<th width="100">上传数量</th>
						<th width="100">排序</th>
						<th width="400">描述</th>
						<th width="">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($lists)):?>
						<?php foreach ($lists as $key => $val) {?>
						
						<tr>
							
							<td><?=$val['aTopic_id'];?></td>
							<td><?=!empty($val['TopicName'])?str_replace('\\','',$val['TopicName']):''?> </td>
							<td><?=!empty($val['Quantity'])?$val['Quantity']:''?> </td>
							<td><?=!empty($val['line'])?$val['line']:''?> </td>
							<td><?=!empty($val['des'])?$val['des']:''?> </td>
							<td>
								
								<a href="/master/enrollment/attachment/attachment_mb_edititem?aTopic_id=<?=$val['aTopic_id']?>&atta_id=<?=$atta_id?>" class="btn btn-xs btn-info">编辑</a>
								
								<a href="javascript:;" onclick="del(<?=$val['aTopic_id']?>)" class="btn btn-xs btn-info btn-white">删除</a>
							
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
		pub_alert_html('/master/enrollment/attachment/attachment_mb_ajaxitem?atta_ids=<?=$atta_id?>');
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
			opt.sAjaxSource = "/master/enrollment/attachment/attachment_mb_item";
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
	pub_alert_confirm('/master/enrollment/attachment/attachment_mb_delitem?aTopic_id='+id);
}

</script>

<script type="text/javascript">
		function save(){
			var data=$('#myform').serialize();
			$.ajax({
				url: '/master/enrollment/attachment/attachment_mb_savembitem',
				type: 'POST',
				dataType: 'json',
				data: data
			})
			.done(function(r) {
				if(r.state == 1){
					pub_alert_success();
					window.location.reload();
				}else{
					pub_alert_error();
				}
			})
			.fail(function() {
				pub_alert_error();
			})
		}


</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
