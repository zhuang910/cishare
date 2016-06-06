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
	<li class="active">入学确认</li>
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
	  入学确认
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
入学确认
				
			</div>
			
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						
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
                                    专业名：<?=$val->name;?><br/>
                                  
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
                                   
									<?=!empty($val->sex)&&$val->sex==1?'male':'Female';?>
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
                                       
                                    <span style="color:#F00;"><?=$val->pagesend_status==1?'纸质offer已发送':'';?></span><br/>
                                        <?php if ($val->status==7):?>
                                    
                                                                       发送时间：<span style="color:#F00;"><?=!empty($val->pagesend_time)?date('Y-m-d H:i',$val->pagesend_time):'';?></span><br/>
                                        <?php endif;?>  
                                </td>
                              
							<td>
							<div class="btn-group"><a class="btn btn-xs btn-info" href="javascript:confirm_enrollment(<?=$val->appid?>);">确认入学</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
								更多
								<span class="ace-icon fa fa-caret-down icon-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">
							
							<li>
							<a id="remark" data-type="textarea"  data-placement="left"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="javascript:pub_alert_html('/master/enrollment/change_app_status/add_remark?id=<?=$val->appid?>&label_id=<?=$label_id?>');" rel="tooltip" title="<?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?>">
														  查看备注
															  </a> 
							</li>
							<li class="divider"></li>
							<li><a href="javascript:end_apply(<?=$val->appid?>);">结束</a> </li>
							</ul>
							</div>
								 
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
		opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4] }];
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}

	});
}

/*入学确认信'/master/enrollment/change_offer_status/send_confirm_joinus?id='+id,*/


function end_apply(id){
	pub_alert_confirm('/master/enrollment/process/over_app?id='+id);

}

function confirm_enrollment(id){
	pub_alert_confirm('/master/enrollment/change_offer_status/confirm_enrollment?id='+id);
}

</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
