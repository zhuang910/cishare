<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">控制台</a>
	</li>
	<li class="active">中介申请管理</li>
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
	  中介申请管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				<!--<span style="cursor:pointer;margin-left: 1025px;" onclick="add()" >添加中介</span>-->
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax admin_index">
					<thead>
						<tr>
							<th class="center">
								ID
							</th>
							<th>登录邮箱</th>
							<th>中介公司</th>
							<th>注册时间</th>
							<th>营业执照</th>
							<th>电话</th>
							<th>手机</th>
							<th>操作</th>
						</tr>
					</thead>

					<tbody role="alert" aria-live="polite" aria-relevant="all">
						
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
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<!-- script -->
<script src="<?=RES?>master/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.bootstrap.js"></script>
<!-- delete -->
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
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "/master/authority/applyagency/index";
		}

		if($(this).hasClass("admin_index")){
			opt.bStateSave = false;
			opt.aoColumns = [

								{ "mData": "id" },
								{ "mData": "email" },
								{ "mData": "company" },
								{ "mData": "createtime" },
								{ "mData": "licence" },
								{ "mData": "tel" },
								{ "mData": "mobile" },
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
}


function upstate(id,state){
	//override dialog's title function to allow for HTML titles
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
		_title: function(title) {
			var $title = this.options.title || '&nbsp;'
			if( ("title_html" in this.options) && this.options.title_html == true )
				title.html($title);
			else title.text($title);
		}
	}));
	var dialog = $( "#dialog-message" ).removeClass('hide').dialog({
		modal: true,
		title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='ace-icon fa fa-check'></i> jQuery UI Dialog</h4></div>",
		title_html: true,
		buttons: [ 
			{
				text: "Cancel",
				"class" : "btn btn-xs",
				click: function() {
					$( this ).dialog( "close" ); 
				} 
			},
			{
				text: "OK",
				"class" : "btn btn-primary btn-xs",
				click: function() {
					$( this ).dialog( "close" ); 
					$.ajax({
							url: '/master/authority/applyagency/upstate?id='+id+'&state='+state,
							type: 'GET',
							dataType: 'json'
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
							console.log("error");
						})
				} 
			}
		]
	});

	/**
	dialog.data( "uiDialog" )._title = function(title) {
		title.html( this.options.title );
	};
	**/
}

function sendemail(id){
	$.ajax({
 		url: '/master/authority/applyagency/sendemail?id='+id,
 		type: 'GET',
 		dataType: 'json',
 	})
 	.done(function(r) {
 		if (r.state == 1) {
 			var modal = r.data;
 			var modal = $(modal);
			modal.modal("show").on("hidden", function(){
				modal.remove();
			});

	
 		};
 	})
}

function save(){
	var data=$('#myform').serialize();
	$.ajax({
		url: '/master/authority/applyagency/dosendemail',
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
