<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">招生系统</a>
	</li>
	<li><a href="index">申请处理</a></li>
	<li class="active">录取阶段</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
         <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
<div class="row-fluid">
	<div class="span12">
		<div class="box box-color box-bordered">
			<div class="box-title">
				<h3>
					<i class="icon-user">申请细节页</i>
				</h3>
				<!--
				<div class="actions">
					<a class="btn btn-danger" href="javascript:history.back();"><i class="icon-back">返回</i></a>
				</div>
				-->
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin table-bordered">
					<thead>		
						<tr>
							<th>
								<table style='background-color:#fff;width:100%'>
									<tr>
										<td colspan='2' style='border-bottom:1px solid #ddd;'>用户信息：</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>姓名：</td>
										<td style='width:70%;font-weight:normal;'><?=$lists->enname; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>国籍：</td>
										<td style='width:70%;font-weight:normal;'><?=$country[$lists->nationality]; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>性别：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->sex)?$lists->sex==1?'Male':'Female':'未填写';?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>护照：</td>
										<td style='width:70%;font-weight:normal;'><?=$lists->passport; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>EMAIL：</td>
										<td style='width:70%;font-weight:normal;'><?=$lists->email; ?></td>
									</tr>
								</table>
							</th>
							<th>
								<table style='background-color:#fff;width:100%'>
									<tr>
										<td colspan='2' style='border-bottom:1px solid #ddd;'>申请信息：</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>申请编号：</td>
										<td style='width:70%;font-weight:normal;'><?=$lists->number; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>当前状态：</td>
										<td style='width:70%;font-weight:normal;color:red;'>
										<?=$apply_state[$lists->status]; ?>
										</td>
									</tr>
								
									<tr>
										<td style='width:30%;font-weight:normal;'>申请表：</td>
										<td style='width:70%;font-weight:normal;'>
										<a href="/master/enrollment/appmanager/xz?id=<?=$lists->appid?>&courseid=<?=$lists->courseid?>&userid=<?=$lists->userid?>&type=online">
                                                                                                           下载</a>
											<a href="/master/enrollment/appmanager/browse?id=<?=$lists->appid?>&courseid=<?=$lists->courseid?>&userid=<?=$lists->userid?>&type=online" target="_blank">
						              	预览</a>
										</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>附件材料：</td>
										<td style='width:70%;font-weight:normal;'>
										<a href="javascript:pub_alert_html('/master/enrollment/appmanager/check_upload?id=<?=$lists->appid?>&courseid=<?=$lists->courseid?>');" rel="tooltip" data-original-title="审核">审核</a>
								
						                <a href="/master/enrollment/appmanager/attach_download?id=<?=$lists->appid;?>">                           
						                                           下载</a>
										</td>
									</tr>
									<tr>
									    <td style='width:30%;font-weight:normal;'></td>
										<td style='width:70%;font-weight:normal;'></td>
									</tr>
									<tr>
									    <td style='width:30%;font-weight:normal;'></td>
										<td style='width:70%;font-weight:normal;'></td>
									</tr>
									
								</table>
							</th>
						</tr>
						<tr>
							<th>
								<table style='background-color:#fff;width:100%'>
									<tr>
										<td colspan='2' style='border-bottom:1px solid #ddd;'>院校/课程属性：</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>申请学校：</td>
										<td style='width:70%;font-weight:normal;'>来华留学网-中国大学在线申请和招生系统</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>申请专业：</td>
										<td style='width:70%;font-weight:normal;'><?=$lists->name; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>学制：</td>
										<td style='width:70%;font-weight:normal;'> <?=$lists->schooling;?>   (<?=$programa_unit[$lists->xzunit]?>)</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>开学时间：</td>
										<td style='width:70%;font-weight:normal;'><?=date('Y-m-d',$lists->opening); ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>注册日期：</td>
										<td style='width:70%;font-weight:normal;'><?=date('Y-m-d',$lists->registertime); ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>申请费：</td>
										<td style='width:70%;font-weight:normal;'><?=$lists->registration_fee; ?> USD</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>学费：</td>
										<td style='width:70%;font-weight:normal;'><?=$lists->tuition; ?> RMB</td>
									</tr>
								</table>
							</th>
							<th>
								<table style='background-color:#fff;width:100%'>
									<tr>
										<td colspan='2' style='border-bottom:1px solid #ddd;'></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>入学状态：</td>
										<td style='width:70%;font-weight:normal;'>
										
										<?php if($lists->confirm_admission==1): ?>
											确认
										<?php else :?>
											未确认
										<?php endif;?>
										</td>
									</tr>

									<tr>
										<td style='width:30%;font-weight:normal;'>结束状态：</td>
										<td style='width:70%;font-weight:normal;color:red;'>
										
										<?php if($lists->status==9): ?>
											结束
										<?php else :?>
											未结束
										<?php endif;?>
										</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>提交申请/最后修改：</td>
										<td style='width:70%;font-weight:normal;'><?=date('Y-m-d H:m',$lists->applytime); ?> / <?=date('Y-m-d H:m',$lists->lasttime); ?></td>
									</tr>
									<tr>
									    <td style='width:30%;font-weight:normal;'></td>
										<td style='width:70%;font-weight:normal;'></td>
									</tr>
									<tr>
									    <td style='width:30%;font-weight:normal;'></td>
										<td style='width:70%;font-weight:normal;'></td>
									</tr>
								</table>
							</th>
						</tr>
					</thead>
				</table>	
				<table class="table table-hover table-nomargin table-bordered dataTable-columnfilter">
					<thead>
                            <tr>
                                <th>
                                    <table style='background-color:#fff;width:100%;border:1px;'>
										<tr>
											<td style='width:20%;border-bottom:1px solid #ddd;'>操作时间</td>
											<td style='width:25%;border-bottom:1px solid #ddd;'>事件</td>
											<td style='width:20%;border-bottom:1px solid #ddd;'>操作人</td>
											<td style='width:35%;border-bottom:1px solid #ddd;'>备注</td>
										</tr>
										<?php if(!empty($lists_log)):?>
											<?php foreach($lists_log as $val):?>
											<tr>
												<td style='width:20%;font-weight:normal;'><?=date('Y-m-d H:i:s',$val->lasttime); ?></td>
												<td style='width:50%;font-weight:normal;'><?=$val->title; ?></td>
												<td style='width:15%;font-weight:normal;'>
												<?=$val->adminname?>
												</td>
												<td style='font-weight:normal;'><?=$val->remark; ?></td>
											</tr>
											<?php endforeach;?>
										<?php else :?>
											<tr>
												<td style='width:20%;font-weight:normal;' colspan='4'>无日志</td>
											</tr>
										<?php endif;?>
									</table>
                                </th>
                            </tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<?php $this->load->view('master/public/footer');?>