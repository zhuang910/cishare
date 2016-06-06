<?php if($_SESSION['master_user_info']->groupid==1):?>
<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">招生管理</a>
	</li>
	<li>
		<a href="javascript:;">申请处理</a>
	</li>
	<li class="active">录取阶段</li>
</ul>
EOD;
?>	
<?php else:?>
<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">招生管理</a>
	</li>
	<li class="active">处理申请</li>
</ul>
EOD;
?>	
<?php endif;?>	
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
         <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
		 <link rel="stylesheet" href="<?=RES?>master/css/colorbox.css" />

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	  
	  <?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
			申请管理
		<?php else:?>
			处理申请
		<?php endif;?>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
	
		<div>
			<div class="table-header">

			 <?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
				申请管理
			<?php else:?>
				处理申请
			<?php endif;?>
			<?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
				<button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>appmanager/tochanel?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
						<span class="glyphicon  glyphicon-plus"></span>
						导入
				</button>	
				<button style="float:right;" onclick="pub_alert_html('<?=$zjjp?>appmanager/export_where?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加学院" type="button">
						<span class="glyphicon  glyphicon-plus"></span>
						导出
				</button>	
			<?php endif;?>
			</div>
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				<?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
				    <li <?php if($label_id ==0):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?&label_id=0"><h5>未提交资料</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='1'):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?label_id=1"><h5>材料审核中</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?&label_id=2"><h5>打回</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='3'):?> class="active" <?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?&label_id=3"><h5>打回提交</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='4'):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?&label_id=4"><h5>拒绝</h5></a>
                    </li>
<!--					<li --><?php //if(!empty($label_id) && $label_id =='5'):?><!-- class="active"--><?php //endif;?><!-->
<!--                    <a href="/master/enrollment/appmanager/index?&label_id=5"><h5>调剂</h5></a>-->
<!--                    </li>-->
					<?php if($pledge_on == 1){?>
                    <li <?php if(!empty($label_id) && $label_id =='6'):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?&label_id=6"><h5>预录取</h5></a>
                    </li>
					<?php }?>
                    <li <?php if(!empty($label_id) && $label_id =='7'):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?&label_id=7"><h5>录取</h5></a>
                    </li>
                 <?php endif;?>
                 <?php if($_SESSION['master_user_info']->groupid==9):?>
                    <li <?php if( $label_id =='0'):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index"><h5>待审核</h5></a>
                    </li>
                    <li <?php if(!empty($label_id) && $label_id =='1'):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?&label_id=1"><h5>通过</h5></a>
                    </li>
                    <li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
                    <a href="/master/enrollment/appmanager/index?&label_id=2"><h5>拒绝</h5></a>
                    </li>
                  <?php endif;?>
				</ul>
			
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						
							  <th>ID</th>
							<th>申请对象信息</th>
							<th>申请人信息</th>
							<?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
							<th>状态/时间</th>
							<?php endif;?>
							<th>审核</th>
							<th width="150">操作</th>
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
									<?php if(!empty($val->scholorshipid) && !empty($scholorshipapply[$val->scholorshipid])){
										$arr = array(
											0 => '待审核',
											5 => '通过',
											4 => '不通过'
										);
									?>
										(<?=$scholorshipapply[$val->scholorshipid]?> - <?=!empty($arr[$val->scholorstate])?$arr[$val->scholorstate]:''?>)
										
									<?php }?>
									<br/>
                                  
                                    学制：
                                    <?=!empty($val->schooling)?$val->schooling:''?>   <?=!empty($val->xzunit)?'('.$programa_unit[$val->xzunit].')':''?> <br/>
             
                                    开学/截止：
									 <?=!empty($val->opentime)?date('Y-m-d',$val->opentime):''?>
									
                                     / 
                                   
									 <?=!empty($val->endtime)?date('Y-m-d',$val->endtime):''?>
                                  
                                </td>
                                <td>
                                    英文名字：
									
									<?=!empty($val->enname)?$val->enname:''?>
                                    <br/>
                                     中文名字：
									
									<?=!empty($val->chname)?$val->chname:''?>
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
                                    <?=!empty($val->agency_id)?'<br />中介公司:'.$agency_info[$val->agency_id]:''?>
                                </td>
                      <?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
                                <td>
								
                               			支付状态：
                               			<?php
											$paytype = array(
												1 => 'Paypal支付',
												2 => 'Pease支付',
												3 => '凭据支付',
												4=>'现金',
												5=>'刷卡'
											);
                               				if($val->paystate == 1){
                               					echo "已支付<br />";
												echo '支付方式：'.(isset($paytype[$val->paytype]) ? $paytype[$val->paytype] : '');
                               				}else if($val->paystate == 2){
												 echo "支付失败<br />";
												echo '支付方式：'.(isset($paytype[$val->paytype]) ? $paytype[$val->paytype] : '');
                               				}else{
                               					echo "未支付";
                               				}
											
                               			?>
                               			<br />
										
                                    <span style="color:#F00;"><?=$app_state[$val->status];?></span><br/>
                                    	创建时间：<br /><?=date('Y-m-d H:i:s',$val->applytime);?><br/>
                                    	<?php if($val->status!=0){?>
                                    	提交时间：<br /><?=!empty($val->issubmittime)?date('Y-m-d H:i:s',$val->issubmittime):'';?><br/>
                                    	<?php }?>
                                    	最后操作时间:<?=!empty($val->app_lasttime)?date('Y-m-d H:i:s',$val->app_lasttime):'';?><br />
                                    	<!--<a rel="<?=$val->appid; ?>" class="tip"  id='alert' href='javascript:viod();'>用户流程跟踪</a>-->
										<?php if($val->status >= 6 && !empty($pledge_on) && $pledge_on == 1){?>
										    押金支付状态：<span style="color:#F00;">
												
												
												<?php
													if($val->deposit_state == 0){
														echo "未支付";
													}else if($val->deposit_state == 1){
														 echo "支付成功";
													}else if($val->deposit_state == 2){
														echo "支付失败";
													}else if($val->deposit_state == 3){
														echo '待确认';
													}else{
														echo '';
													}
													
												?>
											</span><br/>
										<?php }?>
                                </td>
				<?php endif;?>
                                
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
													   下载</a><br />
							<?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
							<?php 
								$is_faculty=array(
										0=>'否',
										1=>'是'
									);
							?>
							 设置减免学费:<a upload-config="true" data-pk="<?=$val->appid?>" data-name="tuition_discount" href="javascript:;"><?=!empty($val->tuition_discount)?$val->tuition_discount:''?></a><br />
							 是否推送到二级学院:<?=$is_faculty[$val->is_faculty]?><br />
							 <?php 
							 	$faculy_state_arr=array(
							 			0=>'待审核',
							 			1=>'通过',
							 			2=>'拒绝'
							 		);
							 ?>
							 二级学院审核状态:<?=$faculy_state_arr[$val->faculty_state]?><br />
							 二级学院审核时间:<br /><?=!empty($val->faculty_time)?date('Y-m-d H:i:s',$val->faculty_time):''?><br />
							  <a href="javascript:pub_alert_html('/master/enrollment/appmanager/look_faculty_remark?id=<?=$val->appid?>');" title="查看二级学院审核备注">查看二级学院审核备注</a>
							 <?php endif;?>
							
						  
					</td>
					<td>
						<div class="btn-group">
						<?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11){?>
							<?php if (!in_array($val->status,array(2,4,5,7))) :?>
								 <a href="javascript:pub_alert_html('/master/enrollment/change_app_status/index?id=<?=$val->appid?>&label_id=<?=$label_id?>');" class="btn btn-xs btn-info">修改状态</a>
							<?php endif;?>
							<?php if($val->status == 7){?>
							<a class="btn btn-xs btn-info" href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_number?id=<?=$val->appid?>&label_id=<?=$label_id?>');" >添加学号</a>
							<?php }?>
							<button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
								更多
								<span class="ace-icon fa fa-caret-down icon-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">
								<?php if($val->status == 7){?>
									  <li><a href="javascript:pub_alert_html('/master/enrollment/change_app_status/scholorship_set?id=<?=$val->appid?>&label_id=<?=$label_id?>');">中国政府奖学金设置</a>
									  </li>
								
								 <?php }?>
								 
								<li> 
									<a id="remark" data-type="textarea" data-placement="left"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_remark?id=<?=$val->appid?>&label_id=<?=$label_id?>');" rel="tooltip" title="<?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?>">
									查看备注
									</a>  
								</li>
								
								 <?php if($val->status == 0&&$val->paystate!=1){?>
										<li> <a href="javascript:pub_alert_html('/master/enrollment/appmanager/onsite?s=1&id=<?=$val->appid?>&userid=<?=$val->userid?>');" >现场缴费</a></li>
								  <?php }?>
								<li><a href="/master/enrollment/fillingoutforms/apply?applyid=<?=cucas_base64_encode($val->appid)?>" >编辑申请资料</a></li>
								<li><a href="javascript:;" onclick="tuisong(<?=$val->appid?>)" >推送二级学院</a></li>
								<li class="divider"></li>
								<li>  <a href="javascript:end_apply(<?=$val->appid?>);" >结束</a></li>
							</ul>
						
						<?php }else if($_SESSION['master_user_info']->groupid==9){?>
						
							<a href="javascript:pub_alert_html('/master/enrollment/appmanager/faculty_change_state_page?id=<?=$val->appid?>');" class="btn btn-xs btn-info">修改状态</a>
						<?php }?>
                            <?=isset($admin_lists[$val->apply_user]) && !empty($admin_lists[$val->apply_user]) ? '<br>处理老师：'.$admin_lists[$val->apply_user]['username'] : ''?>
					</div>
					<!--
					<?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
					<?php if (!in_array($val->status,array(2,4,5,7))) :?>
						 <a href="javascript:pub_alert_html('/master/enrollment/change_app_status/index?id=<?=$val->appid?>&label_id=<?=$label_id?>');" class="green" rel="tooltip" title="修改状态"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<?php endif;?>
					  
					  <?php if($val->status == 7){?>
					  <a class="green" href="javascript:pub_alert_html('/master/enrollment/change_offer_status/send_e?id=<?=$val->appid?>&label_id=<?=$label_id?>');"  title="发送e-offer" rel="tooltip"><i class="ace-icon fa fa-pencil bigger-130"></i></a> 
					  <a href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_number?id=<?=$val->appid?>&label_id=<?=$label_id?>');" class="green" rel="tooltip" title="添加学号"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					  <a href="javascript:pub_alert_html('/master/enrollment/change_app_status/scholorship_set?id=<?=$val->appid?>&label_id=<?=$label_id?>');" class="green" rel="tooltip" title="中国政府奖学金设置"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					
					  <?php }?>
				
					
						  
						  <a id="remark" data-type="textarea" class="editabless green" data-placement="left"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_remark?id=<?=$val->appid?>&label_id=<?=$label_id?>');" rel="tooltip" title="<?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?>">
							  <i class="ace-icon fa fa-pencil bigger-130"></i>
						  </a>  
						  <?php if($val->status == 0&&$val->paystate!=1){?>
					 			 <a href="javascript:pub_alert_html('/master/enrollment/appmanager/onsite?s=1&id=<?=$val->appid?>&userid=<?=$val->userid?>');" class="green" rel="tooltip" title="现场缴费"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
						  <?php }?>
					  	  <a class=" red" href="javascript:end_apply(<?=$val->appid?>);" title="结束" rel="tooltip"><i class="ace-icon fa fa-trash-o bigger-130"></i></a> 
						   <a href="/master/enrollment/fillingoutforms/apply?applyid=<?=cucas_base64_encode($val->appid)?>" class="green" rel="tooltip" title="编辑申请资料"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
						<?php elseif($_SESSION['master_user_info']->groupid==9):?>
							 <a href="javascript:pub_alert_html('/master/enrollment/appmanager/faculty_change_state_page?id=<?=$val->appid?>');" class="green" rel="tooltip" title="修改状态"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
						<?php endif;?>-->
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
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<script type="text/javascript">
function tuisong(id){
	pub_alert_confirm('/master/enrollment/appmanager/erjixueyuan?id='+id);
}
$(function(){
	$('a[upload-config="true"]').editable({
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'/master/enrollment/appmanager/edit_is_tuition_discount',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						// pub_alert_error(r.info);
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });
});

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
		<?php if($_SESSION['master_user_info']->groupid==1 || $_SESSION['master_user_info']->groupid==11):?>
		opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 5 ] }];
		<?php else:?>
		opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4 ] }];
		<?php endif?>
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
