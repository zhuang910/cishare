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
		<a href="javascript:;">在学设置</a>
	</li>
	<li class="active">课程设置</li>
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
		课程设置
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			课程列表
			<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加课程" type="button">
			<span class="glyphicon  glyphicon-plus"></span>
			添加课程
			</button>
			<button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>course/tochanel?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					导入
			</button>
			<a style="float:right;" href="/master/basic/course/export" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					导出
			</a>	
		</div>
		<!-- <div class="table-responsive"> -->
		<!-- <div class="dataTables_borderWrap"> -->
		<div>                                  
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
						<th>课程名称</th>
						<th>英文名称</th>
						<th>课时</th>
						<th>学分</th>
						<th>缺勤通知线</th>
					 	<th>开除通知线</th>
					 	<th>是否选课</th>
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
			opt.sAjaxSource = "<?=$zjjp?>course";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "englishname" },
								{ "mData": "hour" },
								{ "mData": "credit" },
								{ "mData": "absenteeism" },
								{ "mData": "expel" },
								{ "mData": "variable" },
								{ "mData": "state" },
								{"mData":"operation"}
							
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [7,8,9 ] }];
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
	pub_alert_confirm('/master/basic/course/del?id='+id);

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
							url: '/master/basic/course/upstate?id='+id+'&state='+state,
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

	$(document).on('click', 'th input:checkbox' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
				});
			

</script>

<script type="text/javascript">
	
	function edit(){
		var ids = '';
    	$('input:checkbox[name="ids"]:checked').each(function(){
	        ids += $(this).val() + ',';
	    });
        if(ids == ''){
        	alert("请选择数据！");
            return false;
        }

        var edit_fei = $('#edit_fei').val();

       if(edit_fei == ''){

       		alert('请选择操作');
       		return false;
       }

       pub_alert_html('/master/basic/course/get_html?ids='+ids+'&edit_fei='+edit_fei);
/*
       $.ajax({
         		url: '/master/basic/course/get_html?ids='+ids+'&edit_fei='+edit_fei,
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

         	*/


	}


	function copycourse(id){
	
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
							url: '/master/basic/course/copycourse?id='+id,
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
</script>


<!-- end script -->
<?php $this->load->view('master/public/footer');?>
