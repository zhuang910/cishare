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
	<li class="active">所有申请</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
<link rel="stylesheet" href="<?=RES?>master/css/colorbox.css" />
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
			所有申请
				<a style="float:right;" href="/master/enrollment/appmanager/export_by" class="btn btn-primary btn-sm btn-default btn-sm" title="导出" type="button">
					<span class="ace-icon fa fa-mail-forward"></span>
					导出北邮格式
				</a>
			</div>
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						 <th>ID</th>
							<th>申请对象信息</th>
							<th>申请人信息</th>
							<th>状态/时间</th>
							<th>审核</th>
							<th>操作</th>
						</tr>
						
					</thead>
					<tbody>
						<?php  if(!empty($lists)):?>
							<?php foreach($lists as $val):?>
                            <tr id="<?=$val->appid; ?>">
                                <td><?=$val->appid;?></td>
                                <td>
                                    <a href="/master/enrollment/appmanager/app_detail?id=<?=$val->appid?>" class="" rel="tooltip" data-original-title="详情">
									申请编号：<?=$val->number;?></a><br/>
									学号：<?=!empty($val->studentid)?$val->studentid:'--'?></a><br/>
                                    专业名：<?=$val->name;?>
									
									<?php

									if(!empty($val->scholorshipid) && !empty($scholorshipapply[$val->scholorshipid])){?>
										(<?=$scholorshipapply[$val->scholorshipid]?>)
										
									<?php }?>
									<br/>
                                  
                                    学制：
                                    <?=!empty($val->schooling)?$val->schooling:''?>   (<?=$programa_unit[$val->xzunit]?>) <br/>
             
                                    开学/截止：
									 <?=!empty($val->opentime)?date('Y-m-d',$val->opentime):''?>
									
                                     / 
                                   
									 <?=!empty($val->endtime)?date('Y-m-d',$val->endtime):''?>
                                  
                                </td>
                                <td>
                                    姓名：
									
									<?=!empty($val->enname)?$val->enname:''?>
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
									
									<?=!empty($val->passport)?$val->passport:''?>
                                    </a>
                                </td>
                                <td>
                               			支付状态：
                               			<?php
                               				if($val->paystate == 1){
                               					echo "已支付";
                               				}else if($val->paystate == 2){
												 echo "支付失败";
                               				}else{
                               					echo "未支付";
                               				}
											
                               			?>
                               			<br />
										
                                    <span style="color:#F00;"><?=$app_state[$val->status];?></span><br/>
                                    	提交时间：<br /><?=date('Y-m-d H:i:s',$val->applytime);?><br/>
                                    	<!--<a rel="<?=$val->appid; ?>" class="tip"  id='alert' href='javascript:viod();'>用户流程跟踪</a>-->
										<?php if($val->status >= 6 && !empty($pledge_on) && $pledge_on == 1){?>
										    押金支付状态：<span style="color:#F00;">
												
												
												<?php
													if($val->deposit_state == 1){
														echo "已支付";
													}else if($val->deposit_state == 2){
														 echo "支付失败";
													}else{
														echo "未支付";
													}
													
												?>
											</span><br/>
										<?php }?>
                                </td>
					<td>
						<!--
							申请表： 
							<a href="/master/enrollment/appmanager/check_info?id=<?=$val->appid?>&&label_id=0">
																							   审核</a>
							<a href="/master/enrollment/appmanager/apply_form_download?id=<?=$val->appid;?>&type=download">
							下载</a><br/>
							 附件：
						  <a rel="<?=$val->appid; ?>" class="tip"  id='online' href='javascript:viod();'>                           
													   审核</a>
							<a href="/master/enrollment/appmanager/attach_download?id=<?=$val->appid;?>">                           
													   下载</a><br/><br/> -->
													   
						
							申请表： 
							<a href="/master/enrollment/appmanager/xz?id=<?=$val->appid?>&courseid=<?=$val->courseid?>&userid=<?=$val->userid?>&type=online">
																							   下载</a>
							<a href="/master/enrollment/appmanager/browse?id=<?=$val->appid?>&courseid=<?=$val->courseid?>&userid=<?=$val->userid?>&type=online" target="_blank">
							预览</a><br/>
													   附件：
						  
							<a href="javascript:pub_alert_html('/master/enrollment/appmanager/check_upload?id=<?=$val->appid?>&courseid=<?=$val->courseid?>');"  rel="tooltip" data-original-title="审核">审核</a>
					
							<a href="/master/enrollment/appmanager/attach_download?id=<?=$val->appid;?>">                           
													   下载</a>

						  
					</td>
					<td>
					
					  
					 <!--  <a id="remark" data-type="textarea" class="editabless" data-placement="left" data-original-title="修改备注"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="#" rel="tooltip">
						  <?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?></a>  <br />-->
						  
						 <!--<?php if ($val->status == 7 ) :?>
						 <a href="javascript:pub_alert_confirm(this,'','/master/enrollment/appmanager/made_qr?id=<?=$val->appid;?>');" class="btn btn-small btn-success" rel="tooltip" data-original-title="生成二维码">生成二维码</a><br/>
					
							 <a href="javascript:pub_alert_html('/master/enrollment/appmanager/print_offter?id=<?=$val->appid?>&label_id=<?=$label_id?>');" class="btn btn-small btn-success" rel="tooltip" data-original-title="打印通知书">打印通知书</a>
						  <br />
						  <?php endif;?>-->
						  
						  
				 <a id="remark" data-type="textarea" class="btn btn-xs btn-info" data-placement="left"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_remark?id=<?=$val->appid?>');" rel="tooltip" title="<?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?>">
						 查看备注
						  </a>  
					    <a class="btn btn-xs btn-info btn-white" href="javascript:end_apply(<?=$val->appid?>);" >结束</a> 
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
			opt.sAjaxSource = "/master/enrollment/appmanager/index";
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



function end_apply(id){
	pub_alert_confirm('/master/enrollment/process/over_app?id='+id);

}


</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
