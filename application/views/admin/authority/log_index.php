<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">权限管理</a>
	</li>
	<li class="active">日志管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('admin/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
 <link rel="stylesheet" href="<?=RES?>admin/css/jquery-ui.min.css" />
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	  日志管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				   <li <?php if(empty($label_id) || $label_id =='1'):?> class="active"<?php endif;?>>
                    <a href="/admin/authority/log/index?label_id=1"><h5>帐号管理</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
                    <a href="/admin/authority/log/index?&label_id=2"><h5>申请处理</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='3'):?> class="active" <?php endif;?>>
                    <a href="/admin/authority/log/index?&label_id=3"><h5>接机处理</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='4'):?> class="active"<?php endif;?>>
                    <a href="/admin/authority/log/index?&label_id=4"><h5>住宿处理</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='6'):?> class="active"<?php endif;?>>
                    <a href="/admin/authority/log/index?&label_id=6"><h5>凭据处理</h5></a>
                    </li>
                   
			</ul>
			<div class="table-header">
				<div class="table-header">
				日志列表
				<button style="float:right;" onclick="del_log(<?=$label_id?>)" class="btn btn-primary btn-sm btn-default btn-sm" title="清空日志" type="button">
			
				清空日志
				</button>	
				</div>
			</div>
			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax group_index">
					<thead>
						<tr>
							<th class="center">
								ID
							</th>
							<th>操作名称</th>
							<th>操作用户</th>
							<th>操作时间</th>
							<th>访问ip</th>
						</tr>
					</thead>

					<tbody role="alert" aria-live="polite" aria-relevant="all">
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/admin/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>admin/js/ace-extra.min.js"></script>
<script src="<?=RES?>/admin/js/ace-elements.min.js"></script>
<script src="<?=RES?>/admin/js/ace.min.js"></script>
<!-- script -->
<script src="<?=RES?>admin/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>admin/js/jquery.dataTables.bootstrap.js"></script>
<!-- delete -->
<script src="<?=RES?>admin/js/jquery-ui.min.js"></script>

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
			opt.sAjaxSource = "/admin/authority/log/index?label_id=<?=$label_id?>";
		}

		if($(this).hasClass("group_index")){
			opt.bStateSave = false;
			opt.aoColumns = [

								{ "mData": "id" },
								{ "mData": "title" },
								{ "mData": "adminname" },
								{ "mData": "lasttime" },
								{ "mData": "ip" }
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4 ] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}


function del_log(type){
pub_alert_confirm('/admin/authority/log/del_log?type='+type);
} 

</script>
<!-- end script -->


<?php $this->load->view('admin/public/footer');?>