<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">招生管理</a>
	</li>
	<li><a href="index">申请处理</a></li>
	<li class="active">发送offer阶段</li>
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
	  申请处理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
			offer处理
				
			</div>
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				    <li <?php if(!empty($label_id) && $label_id =='7' && $ispageoffer==-1):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/app_offer?label_id=7&ispageoffer=-1"><h5>发送e-offer</h5></a>
                    </li>
                    <li <?php if(!empty($label_id) && $label_id =='7' && $ispageoffer==1 && $sendtype==-1):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/app_offer?label_id=7&ispageoffer=1&sendtype=-1"><h5>待邮寄纸质offer</h5></a>
                    </li>
                    <li <?php if(!empty($label_id) && $label_id =='7' && $ispageoffer==1 && $sendtype==1):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/app_offer?label_id=7&ispageoffer=1&sendtype=1&cstatus=0"><h5>已邮寄纸质offer</h5></a>
                    </li>
				</ul>
				<div style="display:block; background-color:#f6f6f6; color:#FFF;" id="sub_nav">
                        <ul style="padding-top:3px;padding-left:5px;" class="nav nav-tabs">
                            <li <?php if ($cstatus==-1 ||$cstatus==''):?>class="active" <?php endif;?>>
                            <a style="padding:0px 35px; margin:0px;" href="/master/enrollment/appmanager/app_offer?label_id=7&ispageoffer=1&sendtype=-1&cstatus=-1"><h5>未确认</h5></a>
                            </li>
                            <li <?php if ($cstatus==1):?>class="active" <?php endif;?>>
                            <a style="padding:0px 35px; margin:0px;" href="/master/enrollment/appmanager/app_offer?label_id=7&ispageoffer=1&sendtype=-1&cstatus=1"><h5>已确认</h5></a>
                            </li>
                		</ul>
                </div>
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr class="thefilter">
						    <th>ID</th>
							<th>申请对象信息</th>
							<th>申请人信息</th>
							<th>状态/时间</th>
							<th>操作</th>
						</tr>
						
					</thead>
					<tbody>
						<?php  if(!empty($lists)):?>
							<?php foreach($lists as $val):?>
                            <tr id="<?=$val->appid; ?>">
                                <td><?=$val->appid;?></td>
                                 <td>
                                    <a href="/master/enrollment/appmanager/app_detail?id=<?=$val->appid?>&" class="" rel="tooltip" data-original-title="详情">
									申请编号：<?=$val->number;?></a><br/>
									学号：<?=!empty($val->studentid)?$val->studentid:'--'?></a><br/>
                                    专业名：<?=$val->name;?>
									<?php if(!empty($val->scholorshipid) && !empty($scholorshipapply[$val->scholorshipid])){
									$arr = array(
											0 => '待审核',
											5 => '通过',
											4 => '不通过'
										);
									?>
										(<?=$scholorshipapply[$val->scholorshipid]?> - <?=$arr[$val->scholorstate]?>)
										
									<?php }?>
									<br/>
                                  
                                    学制：
                                   
                                    <?=$val->schooling;?>   (<?=$programa_unit[$val->xzunit]?>) <br/>
             
                                    开学/截止：
						<?=!empty($val->opentime)?date('Y-m-d',$val->opentime):''?>
                                     / 
                                   
									 <?=!empty($val->endtime)?date('Y-m-d',$val->endtime):''?>
                                 
                                </td>
                                 <td>
                                    姓名：
									
									<?=$val->enname;?>
                                  <br/>
                                    性别：
                                   
									<?=!empty($val->sex)?$val->sex==1?'Male':'Female':'';?>
                                  <br/>
                                    邮箱：
									
									<?=$val->email;?>
									<br/>
                                    国籍：
									
									<?=$country[$val->nationality];?>
                                    <br/>
                                    护照：
									
									<?=$val->passport;?>
                                 
                                </td>
                                <td>
									<span style="color:#F00;">状态：录取</span><br />
									<span style="color:#F00;"><?=$val->e_offer_status==1?'e-offer已发送':'';?></span><br/>
									
								    地址确认状态：<span style="color:#F00;"><?=$val->addressconfirm==-1?'未确认':'已确认-'.date('Y-m-d H:i',$val->address_ctime);?></span><br/>
                                        <?php if (!empty($val->addressconfirm)&&$val->addressconfirm==1) :?>
					                      <a rel="" class="tip"  id='alerts' href="javascript:pub_alert_html('/master/enrollment/change_app_status/ckaddress?id=<?=$val->appid?>&userid=<?=$val->userid?>');">查看确认地址信息</a><br/>
                                        <?php else:?>                     
                                    	提交时间：<?=date('Y-m-d',$val->applytime);?><br/>
                                    	<?php endif;?>
                                    	
                                </td>
                              
                                <td>
								<div class="btn-group">
										
										<?php if($val->status == 7){?>
											<?php if($val->addressconfirm==-1){?>
												<a href="javascript:send_address(<?=$val->appid?>);" class="btn btn-xs btn-info">发送地址确认信</a> 
												<button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
												更多
												<span class="ace-icon fa fa-caret-down icon-only"></span>
											</button>
											<ul class="dropdown-menu dropdown-info dropdown-menu-right">
												<?php if($val->status == 7 && $val->e_offer_status == 1 && $val->pagesend_status == -1){?>
												 <li><a  href="javascript:pub_alert_html('/master/enrollment/appmanager/moban_offer?id=<?=$val->appid?>&printid=3');" >打印纸质offer</a> 
											   </li>
											   
											   <?php }?>
											
											<?php if($val->status == 7 && $val->e_offer_status == 1){?>
												
												 <li><a href="javascript:send_z(<?=$val->appid?>);">确认发送纸质offer</a>
												 </li>
												  <li><a href="javascript:upload_table(<?=$val->appid?>);"> 确认发送纸质offer和上传202</a></li>
												  
												 
												 
											
											<?php }?>
												<li>
												<a id="remark" data-type="textarea" data-placement="left"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_remark?id=<?=$val->appid?>&label_id=<?=$label_id?>');" rel="tooltip" title="<?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?>">
																  添加备注
												</a> 
												</li>

											<li><a href="javascript:pub_alert_html('/master/enrollment/appmanager/moban?id=<?=$val->appid?>&printid=2');">打印202表或201表</a>
											</li>
											 <li><a href="javascript:pub_alert_html('/master/enrollment/appmanager/moban?id=<?=$val->appid?>&printid=7');" >邮递单</a>
											 </li>
											
												<li class="divider"></li>
												<li><a href="javascript:end_apply(<?=$val->appid?>);" >结束</a> 
											</li>
											
											</ul>
											
											<?php }else{?>
												
												<?php if($val->status == 7 && $val->e_offer_status == 1 && $val->pagesend_status == -1){?>
												 <a  class="btn btn-xs btn-info" href="javascript:pub_alert_html('/master/enrollment/appmanager/moban_offer?id=<?=$val->appid?>&printid=3');" >打印纸质offer</a> 
											  
											   
											   <?php }else{?>
											   
											  <a  class="btn btn-xs btn-info" href="javascript:send_z(<?=$val->appid?>);">确认发送纸质offer</a>
												
											   
											   <?php }?>
												<button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
												更多
												<span class="ace-icon fa fa-caret-down icon-only"></span>
											</button>
											<ul class="dropdown-menu dropdown-info dropdown-menu-right">
												
											
											<?php if($val->status == 7 && $val->e_offer_status == 1){?>
												
												
												  <li><a href="javascript:upload_table(<?=$val->appid?>);"> 确认发送纸质offer和上传202</a></li>
												  
												 
												 
											
											<?php }?>
												<li>
												<a id="remark" data-type="textarea" data-placement="left"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_remark?id=<?=$val->appid?>&label_id=<?=$label_id?>');" rel="tooltip" title="<?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?>">
																  添加备注
												</a> 
												</li>

											<li><a href="javascript:pub_alert_html('/master/enrollment/appmanager/moban?id=<?=$val->appid?>&printid=2');">打印202表</a>
											</li>
											 <li><a href="javascript:pub_alert_html('/master/enrollment/appmanager/moban?id=<?=$val->appid?>&printid=7');" >邮递单</a>
											 </li>
											
												<li class="divider"></li>
												<li><a href="javascript:end_apply(<?=$val->appid?>);" >结束</a> 
											</li>
											
											</ul>
											
											<?php }?>

										
										
										<?php }?>
										
										
									
									</div>
								<!--
                                   <?php if($val->status == 7 && $val->addressconfirm==-1) :?>
                   					
									<a class="green" href="javascript:send_address(<?=$val->appid?>);" title="发地址确认信" rel="tooltip"><i class="ace-icon fa fa-pencil bigger-130"></i></a> 
                                   
                                   <?php endif; ?>
								   <?php if($val->status == 7 && $val->e_offer_status == 1 && $val->pagesend_status == -1){?>
								   <a class="green" href="javascript:pub_alert_html('/master/enrollment/change_offer_status/print_m?id=<?=$val->appid?>');"  title="打印纸质offer" rel="tooltip"><i class="ace-icon fa fa-pencil bigger-130"></i></a> 
								    <a class="green" href="javascript:pub_alert_html('/master/enrollment/change_offer_status/print_d?id=<?=$val->appid?>');"  title="打印邮寄单" rel="tooltip"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
								   
								   
								   <?php }?>
								
								<?php if($val->status == 7 && $val->e_offer_status == 1){?>
									
									
									 <a href="javascript:send_z(<?=$val->appid?>);" class="green" rel="tooltip" title="确认发送纸质offer"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
									  <a href="javascript:upload_table(<?=$val->appid?>);" class="green" rel="tooltip" title="确认发送纸质offer和上传202"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
									 
									 
								
								<?php }?>
								
                                   <a id="remark" data-type="textarea" class="editabless green" data-placement="left"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_remark?id=<?=$val->appid?>&label_id=<?=$label_id?>');" rel="tooltip" title="<?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?>">
															  <i class="ace-icon fa fa-pencil bigger-130"></i>
															  </a> 

								        <a class="green" href="javascript:pub_alert_html('/master/enrollment/appmanager/moban?id=<?=$val->appid?>&printid=2');" title="打印202表" rel="tooltip"><i class="ace-icon fa fa-hdd-o bigger-130"></i></a>
										 <a class="green" href="javascript:pub_alert_html('/master/enrollment/appmanager/moban?id=<?=$val->appid?>&printid=7');" title="邮递单" rel="tooltip"><i class="ace-icon fa fa-hdd-o bigger-130"></i></a>
								        <a class=" red" href="javascript:end_apply(<?=$val->appid?>);" title="结束" rel="tooltip"><i class="ace-icon fa fa-trash-o bigger-130"></i></a> -->
                                </td>
                            </tr>
                            <?php endforeach;?>
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
			opt.sAjaxSource = "/master/enrollment/appmanager/app_offer";
		}
opt.aaSorting = [[0,'desc']];
		opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4] }];
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}

	});
}

function send_address(id){
	pub_alert_confirm('/master/enrollment/change_offer_status/send_confirm_address_mail?id='+id);

}

function send_addresszyj(id){
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
							url: '/master/enrollment/change_offer_status/send_confirm_address_mail?id='+id,
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

function end_apply(id){
	pub_alert_confirm('/master/enrollment/process/over_app?id='+id);

}


function send_z(id){
	pub_alert_confirm('/master/enrollment/change_offer_status/send_z_offer?id='+id);

}
function upload_table(id){
	pub_alert_upload_html('/master/enrollment/change_offer_status/uplaod_table_page?id='+id);
}

</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>