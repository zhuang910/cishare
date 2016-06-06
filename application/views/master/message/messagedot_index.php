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
	<li class="avtive">通知设置</li>
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
		通知设置
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<!-- PAGE CONTENT BEGINS -->
		<div class="tabbable">
			<!-- #section:pages/faq -->
			<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
				<li class="active">
					<a data-toggle="tab" href="#faq-tab-1">
						
						发送点
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#faq-tab-2">
						
						自定义发送
					</a>
				</li>
			</ul>
			<!-- /section:pages/faq -->
				<div class="tab-content no-border padding-24">
					<!--自定义发送-->
					<div id="faq-tab-1" class="tab-pane fade in active">
							<button class="btn btn-xs btn-purple" onclick="add_message_dot()">
								<i class="ace-icon fa fa-plus-circle"></i>添加
							</button>
						<div class="table-header">
							发送记录列表
						</div>

						
						<div>   
						  	<form id='del'>                   
								<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
									<thead>
										<tr>
											<th class="center">
												ID
											</th>
											<th>主题</th>
											<th>发件人</th>
											<th>创建时间</th>
											<th></th>

										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</form>
							</div>
					</div>	
					<!--自定义发送-->
					<!--自定义发送-->
					<div id="faq-tab-2" class="tab-pane fade">
							<a class="btn btn-xs btn-purple" href="<?=$zjjp?>customemessage/add">
												<i class="ace-icon fa fa-plus-circle"></i>自定义发送
											</a>
						<div class="table-header">
							发送记录列表
						</div>

						
						<div>   
						  <form id='del'>                   
								<table id="customemessage-table" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
									<thead>
										<tr>
											<th class="center">
												ID
											</th>
											<th>标题</th>
											<th>发送时间</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</form>
						</div>
					</div>	
					<!--自定义发送-->
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
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>

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

		 opt.bAutoWidth=true; 
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "<?=$zjjp?>messagedot";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "title" },
								{ "mData": "addresser" },
								{ "mData": "createtime" },
								{"mData":"operation"},

								
							
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

function add_message_dot(){
		pub_alert_html('<?=$zjjp?>messagedot/add');
	}


	function delete_mail_config(){
		
		var ids=$('#del').serialize();
		if(ids != ''){
			$.ajax({
				url: '<?=$zjjp?>emaildot/del',
				type: 'POST',
				dataType: 'json',
				data: ids,
			})
			.done(function() {
				console.log("success");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
		}
	}

function del(id){
	pub_alert_confirm('/master/message/messagedot/del?id='+id);
}
if($('#customemessage-table').length > 0){
	$('#customemessage-table').each(function(){
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
			opt.sAjaxSource = "<?=$zjjp?>customemessage";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "title" },
							
								{ "mData": "sendtime" },
								{"mData":"operation"},

								
							
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 3 ] }];
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