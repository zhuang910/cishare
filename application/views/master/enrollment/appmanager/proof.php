<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">收费管理</a>
	</li>
	<li><a href="javascript:;">凭据管理</a></li>
	<li class="active">申请凭据</li>
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
	  凭据管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
			凭据用户
				
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
						<?php  if(!empty($proof_all)):?>
							<?php foreach($proof_all as $val):?>
                            <tr>
                                <td><?=$val['id'];?></td>
                                 <td>
                                    <a href="/master/enrollment/appmanager/app_detail?id=<?=$orderinfo[$val['orderid']]['applyid']?>" class="" rel="tooltip" data-original-title="详情">
									申请编号：<?=$orderinfo[$val['orderid']]['apply']['number'];?></a><br/>
                                    专业名：<?=!empty($course[$orderinfo[$val['orderid']]['apply']['courseid']]['name'])?$course[$orderinfo[$val['orderid']]['apply']['courseid']]['name']:'';?><br/>
                                  
                                    学制：
                                  
                                    <?=$course[$orderinfo[$val['orderid']]['apply']['courseid']]['schooling'];?>   (<?=$programa_unit[$course[$orderinfo[$val['orderid']]['apply']['courseid']]['xzunit']]?>) <br/>
             
                                    开学/截止：
									
									<? if(!empty($course[$orderinfo[$val['orderid']]['apply']['courseid']]['opentime'])) echo date('Y-m-d',$course[$orderinfo[$val['orderid']]['apply']['courseid']]['opentime']); ?>
									
                                     / 
                                    
									<? if(!empty($course[$orderinfo[$val['orderid']]['apply']['courseid']]['endtime'])) echo date('Y-m-d',$course[$orderinfo[$val['orderid']]['apply']['courseid']]['endtime']); ?>
									
                                </td>
                                <td>
                                    姓名：
									
									<?=$userinfo[$val['userid']]['enname']?>
                                    <br/>
                                    性别：
                                   
									<?=!empty($userinfo[$val['userid']]['sex'])?$userinfo[$val['userid']]['sex']==1?'male':'Female':'';?>
                                   <br/>
                                    邮箱：<?=$userinfo[$val['userid']]['email']?>
									<br/>
                                    国籍：
									
									<?=$nationality[$userinfo[$val['userid']]['nationality']]?>
                                    <br/>
                                    护照：
									
									<?=$userinfo[$val['userid']]['passport']?>
                                    
                                </td>
                                <td>
                               		<?php 
                               		$state = array(
                               			'0' => '待支付',
                               			'1' => '支付成功',
                               			'2' => '支付失败',
                               			'3' => '待审核',
                               			);

                               		$currency = array(
                               				'1' => '美元',
                               				'2' => '人民币'

                               			);
									$way = array(
										'1' => '西联汇款',
										'2' => '国外银行',
										'3' => '国内银行'
									);

                               		?>
                                    <span style="color:#F00;">状态:<?=$state[$val['state']]?></span><br/>
                                     <span style="color:#F00;">应支付:<?=$val['amount']?>&nbsp;&nbsp;&nbsp;RMB</span><br/>
                                      <span style="color:#F00;">最后更新时间:<?=date('Y-m-d H:i:s',$val['updatetime'])?></span><br/>
                                      <span style="color:#F00;"><a href="javascript:pub_alert_html('/master/enrollment/appmanager/editproof?id=<?=$val['id']?>');">查看凭据</a></span><br/>
                                      <span style="color:#F00;"><a href="javascript:pub_alert_html('/master/enrollment/appmanager/lookproof?id=<?=$val['id']?>');">查看汇款信息</a></span><br/>
                                                             
                                    	
                                </td>
                              
                                <td>
								
									
									   <a class="btn btn-xs btn-info" href="javascript:ends_apply(<?=$val['id']?>,<?=$orderinfo[$val['orderid']]['apply']['id']?>,<?=$orderinfo[$val['orderid']]['id']?>,1,<?=$val['userid']?>);" title="通过" rel="tooltip">通过</a>

<a class="btn btn-xs btn-info btn-white" href="javascript:end_apply(<?=$val['id']?>,<?=$orderinfo[$val['orderid']]['apply']['id']?>,<?=$orderinfo[$val['orderid']]['id']?>,2,<?=$val['userid']?>);" title="不通过" rel="tooltip">不通过</a>	
										<a class="btn btn-xs btn-info btn-white" href="javascript:pub_alert_html('/master/enrollment/appmanager/addproofremark?id=<?=$val['id']?>');" title="查看备注" rel="tooltip" data-pk="492^text" data-value="" data-placement="left" data-type="textarea" id="remark">查看备注</a>	
										
									
      
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
			opt.sAjaxSource = "/master/enrollment/appmanager/proof";
		}
		opt.aaSorting = [[0,'desc']];
		opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4 ] }];
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}

	});
}


function end_apply(id,applyid,orderid,state,userid){
	pub_alert_html('/master/enrollment/appmanager/xiangxi?id='+id+'&applyid='+applyid+'&orderid='+orderid+'&state='+state+'&userid='+userid);
	// pub_alert_confirm('/master/enrollment/appmanager/goproof?id='+id+'&applyid='+applyid+'&orderid='+orderid+'&state='+state+'&userid='+userid);

}
function ends_apply(id,applyid,orderid,state,userid){
	// pub_alert_html('/master/enrollment/appmanager/xiangxi?id='+id+'&applyid='+applyid+'&orderid='+orderid+'&state='+state+'&userid='+userid);
	pub_alert_confirm('/master/enrollment/appmanager/goproof?id='+id+'&applyid='+applyid+'&orderid='+orderid+'&state='+state+'&userid='+userid);

}

</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
