<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">奖学金管理</a>
	</li>
	
	<li class="active">中国政府奖学金</li>
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
	  奖学金管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
	
		<div>
			<div class="table-header">
			中国政府奖学金管理
				
			</div>
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				    <li <?php if($label_id ==0):?> class="active"<?php endif;?>>
                    <a href="/master/scholarship/chinascholarship/index?&label_id=0"><h5>待审核</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='5'):?> class="active"<?php endif;?>>
                    <a href="/master/scholarship/chinascholarship/index?label_id=5"><h5>通过</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='4'):?> class="active"<?php endif;?>>
                    <a href="/master/scholarship/chinascholarship/index?&label_id=4"><h5>拒绝</h5></a>
                    </li>
					
					
                   
				</ul>
			
			<div>
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						
							  <th>ID</th>
							<th>申请对象信息</th>
							<th>申请人信息</th>
							<th>状态/时间</th>
							
							<th width="150">操作</th>
						</tr>
						
					</thead>
					<tbody>
						<?php  if(!empty($lists)):?>
							<?php foreach($lists as $val):?>
                            <tr id="<?=$val->appid; ?>">
                                <td><?=$val->appid;?></td>
                                <td>
                                    奖学金标题：<?=!empty($val->title)?$val->title:''?>
									费用类型:<br />
									<?php 
										if(!empty($val->cost_state) && $val->cost_state == 1){
									?>
									
									&nbsp;指定覆盖 
									
									<?php if(!empty($val->cost_cover)){
										echo '(';
										$cost_cover = json_decode($val->cost_cover);
										if(!empty($cost_cover)){
											$cost_cover_type = array(
												1 => '学费',
												2 => '住宿费',
												3 => '住宿押金',
												4 => '书费',
												6 => '保险费',
											);
											
											foreach($cost_cover as $kz => $kv){
												if(!empty($cost_cover_type[$kv])){
													echo '&nbsp;'.$cost_cover_type[$kv].'&nbsp;';
													
												}
												
											}
										}
										echo ')';
									}?>
									
									
									<?php }else if(!empty($val->cost_state) && $val->cost_state == 2){?>
									
									&nbsp;指定比例 
									<?=!empty($val->cost_ratio)?'('.$val->cost_ratio.')':''?>
									
									<?php }else if(!empty($val->cost_state) && $val->cost_state == 3){?>
									&nbsp;指定金额 
									<?=!empty($val->cost_money)?'('.$val->cost_money.')':''?>
									<?php }else if(!empty($val->cost_state) && $val->cost_state == 4){?>
									&nbsp;发放金额 
									<?=!empty($val->cost_grant_money)?'('.$val->cost_grant_money.')':''?>
									<?php }?>
                                  
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
                                </td>
                                <td>
                               			审核状态：
                               			<?php
                               				if($val->status == 5){
                               					echo "通过";
                               				}else if($val->status == 4){
												 echo "拒绝";
                               				}else{
                               					echo "待审核";
                               				}
											
                               			?>
                               			<br />
										
                                  
                                    	提交时间：<br /><?=date('Y-m-d H:i:s',$val->applytime);?><br/>
                                    	
                                </td>
					
					<td>
					<?php if ($val->status != 6) :?>
						 <a href="javascript:pub_alert_html('/master/scholarship/change_studentscholarship_status/index?id=<?=$val->appid?>&label_id=<?=$label_id?>');" class="btn btn-xs btn-info">修改状态</a>
					<?php endif;?>
					  
					 
					
						  
						  <a id="remark" data-type="textarea" class="btn btn-xs btn-info btn-white" data-placement="left"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="javascript:pub_alert_html('/master/scholarship/change_scholarship_status/add_remark?id=<?=$val->appid?>&label_id=<?=$label_id?>');" rel="tooltip" title="<?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?>">
							  查看备注
						  </a>  
						
					  	  
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
			opt.sAjaxSource = "/master/scholarship/otherscholarship/index";
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

function end_apply(id){
	pub_alert_confirm('/master/scholarship/otherscholarship/over_app?id='+id);

}

</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
