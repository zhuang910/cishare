<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">住宿管理</a>
	</li>
	<li>
		<a href="javascript:;">住宿处理</a>
	</li>
	<li class="active">报修管理</li>
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
		住宿处理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
						
			<div class="table-header">
				报修列表
				<button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>acc_dispose_repair/add?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加书籍" type="button">
				<span class="glyphicon  glyphicon-plus"></span>
				添加报修
			</div>
			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>  
					<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
						<li <?php if($label_id =='0'):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_dispose_repair/index?&label_id=0"><h5>未处理</h5></a>
						</li>
						<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_dispose_repair/index?&label_id=1"><h5>处理中</h5></a>
						</li>
						<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_dispose_repair/index?&label_id=2"><h5>已处理</h5></a>
						</li>
					</ul>                                  
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
							<th>报修人</th>
							<th>报修人邮箱</th>
							<th>住宿信息</th>
                            <th>保修说明</th>
							<th>提交时间</th>
							<th>状态</th>
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
			opt.sAjaxSource = "<?=$zjjp?>acc_dispose_repair?label_id=<?=$label_id?>";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "email" },
								{ "mData": "campusid" },
                                 { "mData": "remark" },
								{ "mData": "createtime" },
								{ "mData": "state" },
								{"mData":"operation"}
							
							];
			opt.aaSorting = [[1,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [0,7 ] }];
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
	pub_alert_confirm('/master/basic/books/del?id='+id);

}
function change_state(id,state){
	var str='';
	if(state==0){
		str='确定要更新到处理中';
	}
	if(state==1){
		str='确定要更新到已处理';
	}
	pub_alert_confirm('/master/enrollment/acc_dispose_repair/change_state?id='+id+'&state='+state,'',str);
}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>