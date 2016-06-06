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
	<li><a href="index">住宿管理</a></li>
	<li><a href="index">入住办理</a></li>
	<li class="active">查看信息</li>
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
					<i class="icon-user">个人信息</i>
						<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
							<i class="ace-icon fa fa-reply light-green bigger-130"></i>
						</a>
				</h3>
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
										<td style='width:30%;font-weight:normal;'>中文名：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->name)?$lists->name:'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>英文名：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->enname)?$lists->enname:'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>国籍：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->nationality)?$nationality['global_country_cn'][$lists->nationality]:'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>性别：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->sex)?$lists->sex==1?'Male':'Female':'未填写';?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>出生日期：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->birthday)?date('Y-m-d',$lists->birthday):'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>护照：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->passport)?$lists->passport:'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>EMAIL：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->email)?$lists->email:'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>手机：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->mobile)?$lists->mobile:'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>座机：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->tel)?$lists->tel:'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>出生地：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->birthplace)?$lists->birthplace:'未填写'; ?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>永久通讯地址</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($lists->address)?$lists->address:'未填写'; ?></td>
									</tr>
								</table>
							</th>
						</tr>

					</thead>
				</table>	
				<h3>
					<i class="icon-user">收费信息</i>
				</h3>
				<table class="table table-hover table-nomargin table-bordered">
					<thead>		
						<tr>
							<th>
								<table style='background-color:#fff;width:100%'>
									<tr>
										<td colspan='2' style='border-bottom:1px solid #ddd;'>收费信息：</td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>住宿押金：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($acc_pledge)?'已交':'请交齐费用再来分配房间'?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>住宿费：</td>
										<td style='width:70%;font-weight:normal;'><?=$acc_info->paystate==1?'已交':'请交齐费用再来分配房间'?></td>
									</tr>
									<tr>
										<td style='width:30%;font-weight:normal;'>预交电费：</td>
										<td style='width:70%;font-weight:normal;'><?=!empty($electric_pledge)?'已交':'请交齐费用再来分配房间'?></td>
									</tr>
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