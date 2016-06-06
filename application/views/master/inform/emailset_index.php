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
	<li class="active">邮件设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script src="<?=RES?>master/js/jqGrid/jquery.jqGrid.min.js"></script>
<script src="<?=RES?>master/js/jqGrid/i18n/grid.locale-cn.js"></script>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
 <script src="<?=RES?>master/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.bootstrap.js"></script>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		邮件设置
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<!-- PAGE CONTENT BEGINS -->
							<div class="tabbable">
								<!-- #section:pages/faq -->
								<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
									<li class="active">
										<a data-toggle="tab" href="#faq-tab-1">
											
											添加发件人
										</a>
									</li>

									<li>
										<a data-toggle="tab" href="#faq-tab-2">
										
											默认发件人
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-3">
										
											邮件发送点
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#faq-tab-4">
										
											自定义发送
										</a>
									</li>
									

								
								</ul>

								<!-- /section:pages/faq -->
								<div class="tab-content no-border padding-24">
								<!--自定义发送-->
								<div id="faq-tab-4" class="tab-pane fade">
										<a class="btn btn-xs btn-purple" href="<?=$zjjp?>customemail/add">
												<i class="ace-icon fa fa-plus-circle"></i>自定义发送
										</a>
									<div class="table-header">
										发送记录列表
									</div>

									
									<div>   
									   <form id='del'>                   
										<table id="customemail-table" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
											<thead>
												<tr>
													<th class="center">
														ID
													</th>
													<th>标题</th>
													<th>发件人</th>
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
								<!--邮件发送点-->
								<div id="faq-tab-3" class="tab-pane fade">
										
											<button class="btn btn-xs btn-purple" onclick="add_mail_dot()">
												<i class="ace-icon fa fa-plus-circle"></i>添加
											</button>
								
									<div class="table-header">
										列表
									</div>

									<!-- <div class="table-responsive"> -->

									<!-- <div class="dataTables_borderWrap"> -->
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
									<!--邮件发送点-->
									</div>
									<div id="faq-tab-1" class="tab-pane fade in active">
											<button class="btn btn-xs btn-purple" onclick="add_mail_config()">
												<i class="ace-icon fa fa-plus-circle"></i>添加
											</button>
											<button class="btn btn-xs btn-info" onclick="edit_mail_config()">
												<i class="ace-icon fa fa-pencil bigger-110"></i>修改
											</button>
											<button class="btn btn-xs btn-danger" onclick="delete_mail_config()">
												<i class="ace-icon fa fa-trash-o bigger-110"></i>删除
											</button>
											<button class="btn btn-xs btn-warning" onclick="search_mail_config()">
												<i class="ace-icon fa fa-search bigger-110"></i>搜索
											</button>
											<table id="grid-table"></table>
											<div id="grid-pager"></div>
										
									</div>
									<div id="faq-tab-2" class="tab-pane fade">
										<h4 class="blue">
											<i class="green ace-icon fa fa-user bigger-110"></i>
											默认发件人信息
										</h4>

										<div class="space-8"></div>

										<div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
											<div class="panel panel-default">
										
												<form class="form-horizontal" id="emailset_defult" method="post" action="<?=$zjjp?>emailset/save">
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right">服务器类型</label>
														<div class="col-xs-12 col-sm-9">
														
																<label class="line-height-1 blue">
																	<input class="ace" type="radio" checked="checded" value="smtp" name="protocol">
																	<span class="lbl"> SMTP</span>
																</label>
															</div>
													</div>

													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">邮件服务器:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" value="<?php echo !empty($defult['smtp_host'])?$defult['smtp_host']:''?>" id="smtp_host" name="smtp_host" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发送端口:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" value="<?php echo !empty($defult['smtp_port'])?$defult['smtp_port']:''?>" id="smtp_port" name="smtp_port" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发件人地址:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="text" value="<?php echo !empty($defult['smtp_user'])?$defult['smtp_user']:''?>" id="smtp_user" name="smtp_user" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>

													<div class="space-2"></div>
													<div class="form-group">
														<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发件人密码:</label>

														<div class="col-xs-12 col-sm-9">
															<div class="clearfix">
																<input type="password" value="<?php echo !empty($defult['smtp_pass'])?$defult['smtp_pass']:''?>" id="smtp_pass" name="smtp_pass" class="col-xs-12 col-sm-5" />
															</div>
														</div>
													</div>
													<input type="hidden" name="mailtype" value="html">
													<div class="space-2"></div>
													<div class="col-md-offset-3 col-md-9">
														<button class="btn btn-info" data-last="Finish">
														<i class="ace-icon fa fa-check bigger-110"></i>
														提交
														</button>
														<button class="btn" type="reset">
														<i class="ace-icon fa fa-undo bigger-110"></i>
														重置
														</button>
													</div>
												</form>
										

											
											</div>

										

										
										</div>
									</div>
								</div>
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
<script type="text/javascript">

$(document).ready(function(){
	$('#emailset_defult').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						
	
						mailserver: {
							required: true
						},
						port: {
							required: true
						},
						mailaddress: {
							required: true,
							email:true
						},
						password: {
							required: true
						},
						
						
					},
			
					messages: {
						mailserver:{
							required:"请输入邮箱服务器",
						},
						port: {
							required: "请输入端口号",
							
						},
						password:{
							required:"请输入邮箱密码",
						},
						mailaddress: {
							required: "请输入一个有效的电子邮箱.",
							email: "Please provide a valid email."
						},
						
						
						state: "请选择状态",
						
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
						var data=$(form).serialize();
					
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="<?=$zjjp?>emailset";
							}else{

								pub_alert_error();
							}
							
						})
						.fail(function() {

							pub_alert_error();
						})
						
						
					}
			
				});

	$('#emailset_add').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						
	
						mailserver: {
							required: true
						},
						port: {
							required: true
						},
						mailaddress: {
							required: true,
							email:true
						},
						password: {
							required: true
						},
						
						
					},
			
					messages: {
						mailserver:{
							required:"请输入邮箱服务器",
						},
						port: {
							required: "请输入端口号",
							
						},
						password:{
							required:"请输入邮箱密码",
						},
						mailaddress: {
							required: "请输入一个有效的电子邮箱.",
							email: "Please provide a valid email."
						},
						
						
						state: "请选择状态",
						
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
						var data=$(form).serialize();
					
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="<?=$zjjp?>emailset";
							}else{

								pub_alert_error();
							}
							
						})
						.fail(function() {

							pub_alert_error();
						})
						
						
					}
			
				});
});

var grid_selector = "#grid-table";
var pager_selector = "#grid-pager";
var grid_data = 
			[ 
				{id:"1",smtp_user:"Desktop Computer",smtp_port:"note",mailtype:"sdf",createtime:"2014-12-23"}
			];
			
			jQuery(function($) {
				
				
				//resize to fit page size
				$(window).on('resize.jqGrid', function () {
					$(grid_selector).jqGrid( 'setGridWidth', $(".tab-content").width() );
			    })
				//resize on sidebar collapse/expand
				var parent_column = $(grid_selector).closest('[class*="col-"]');
				$(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
					if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
						$(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
					}
			    })
			
			
			
				jQuery(grid_selector).jqGrid({
					//direction: "rtl",
					url:'<?=$zjjp?>emailset',
					datatype: "json",
					height: 350,
					colNames:[' ', 'ID','用户账号','端口','邮件类型','创建时间'],
					colModel:[
						{name:'',index:'id', width:80, fixed:true, sortable:false, resize:false,
							formatter:'actions', 
							formatoptions:{ 
								keys:true,
								delOptions:{recreateForm: true, beforeShowForm:beforeDeleteCallback},
								onSuccess:save_success
							},
							onSuccess:function(r){
								alert(1);
							}
						},
						{name:'id',index:'id', width:30},
						{name:'smtp_user',index:'smtp_user',width:240,editable:true},
						{name:'smtp_port',index:'smtp_port',width:30,editable:true},
						{name:'mailtype',index:'mailtype',width:30,editable: true,edittype:"select",editoptions:{value:"text:text;html:html"}},
						{name:'createtime',index:'createtime',width:90}
					], 
			
					viewrecords : true,
					rowNum:10,
					rowList:[10,20,30],
					pager : pager_selector,
					altRows: true,
					//toppager: true,
					
					multiselect: true,
					//multikey: "ctrlKey",
			       // multiboxonly: true,
			
					loadComplete : function() {
						var table = this;
						setTimeout(function(){
							styleCheckbox(table);
							
							updateActionIcons(table);
							updatePagerIcons(table);
							enableTooltips(table);
						}, 0);
					},
					editurl: "<?=$zjjp?>emailset/dummy",//nothing is saved
					caption: "发件人列表",
					autowidth: true,
			
				});
				$(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size
				
				//navButtons
				jQuery(grid_selector).jqGrid('navGrid',pager_selector,
					{ 	//navbar options
						edit: false,
						add: false,
						del: false,
						search: false,
						refresh: true,
						refreshicon : 'ace-icon fa fa-refresh green'
					}
				)

				function save_success(r){
					if(r.responseText == 1){
						pub_alert_success();
						return true;
					}else{
						pub_alert_error(r.responseText);
					}
				}
				
				function beforeDeleteCallback(e) {
					var form = $(e[0]);
					if(form.data('styled')) return false;
					
					form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
					style_delete_form(form);
					
					form.data('styled', true);
				}

				function style_delete_form(form) {
					var buttons = form.next().find('.EditButton .fm-button');
					buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
					buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
					buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>')
				}
			
				//it causes some flicker when reloading or navigating grid
				//it may be possible to have some custom formatter to do this as the grid is being created to prevent this
				//or go back to default browser checkbox styles for the grid
				function styleCheckbox(table) {
				/**
					$(table).find('input:checkbox').addClass('ace')
					.wrap('<label />')
					.after('<span class="lbl align-top" />')
			
			
					$('.ui-jqgrid-labels th[id*="_cb"]:first-child')
					.find('input.cbox[type=checkbox]').addClass('ace')
					.wrap('<label />').after('<span class="lbl align-top" />');
				*/
				}
				
			
				//unlike navButtons icons, action icons in rows seem to be hard-coded
				//you can change them like this in here if you want
				function updateActionIcons(table) {
					/**
					var replacement = 
					{
						'ui-ace-icon fa fa-pencil' : 'ace-icon fa fa-pencil blue',
						'ui-ace-icon fa fa-trash-o' : 'ace-icon fa fa-trash-o red',
						'ui-icon-disk' : 'ace-icon fa fa-check green',
						'ui-icon-cancel' : 'ace-icon fa fa-times red'
					};
					$(table).find('.ui-pg-div span.ui-icon').each(function(){
						var icon = $(this);
						var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
						if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
					})
					*/
				}
				
				//replace icons with FontAwesome icons like above
				function updatePagerIcons(table) {
					var replacement = 
					{
						'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
						'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
						'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
						'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
					};
					$('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
						var icon = $(this);
						var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
						
						if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
					})
				}
			
				function enableTooltips(table) {
					$('.navtable .ui-pg-button').tooltip({container:'body'});
					$(table).find('.ui-pg-div').tooltip({container:'body'});
				}
			
				//var selr = jQuery(grid_selector).jqGrid('getGridParam','selrow');
			
				function checksave(result){
					alert(result);
				}
			});

	function add_mail_config(){
		pub_alert_html('<?=$zjjp?>emailset/add');
	}

	function edit_mail_config(){
		var id = jQuery(grid_selector).jqGrid('getGridParam','selrow');
		pub_alert_html('<?=$zjjp?>emailset/edit?id='+id);
	}

	function delete_mail_config(){
		var ids = jQuery(grid_selector).jqGrid('getGridParam','selarrrow');
		if(ids != ''){
			pub_alert_confirm('<?=$zjjp?>emailset/delete',{ids:ids},'确定要删除所选配置吗？');
		}else{
			pub_alert_error('请选择要删除的数据');
		}
	}

	function search_mail_config() {
		pub_alert_html('<?=$zjjp?>emailset/search');
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
			opt.sAjaxSource = "<?=$zjjp?>emaildot";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "theme" },
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
function add_mail_dot(){
		pub_alert_html('<?=$zjjp?>emaildot/add');
	}
function del(id){
	pub_alert_confirm('/master/inform/emaildot/del?id='+id);
}
//自定义发送
if($('#customemail-table').length > 0){
	$('#customemail-table').each(function(){
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
			opt.sAjaxSource = "<?=$zjjp?>customemail";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "title" },
								{ "mData": "reply_to" },
								{ "mData": "sendtime" },
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
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>