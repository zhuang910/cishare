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
	<li class="active">申请结束</li>
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
			申请结束
				
			</div>

			<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
				<input type="hidden" name="is_userid" value="yes">
			<div>
			<!-- <div class="dataTables_borderWrap"> -->
				<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
						<li style="float:right;">
						<button onclick="message()" class="btn btn-info" data-last="Finish">
							<i class="ace-icon fa fa-comment "></i>
							<span class="bigger-110">批量发站内信</span>
						</button>
						<button onclick="email()" class="btn btn-info" data-last="Finish">
							<i class="ace-icon fa fa-envelope"></i>
							<span class="bigger-110">批量发邮件</span>
						</button>
						</li>
					</ul>  
				<table class="table table-hover table-nomargin dataTable dataTable-for-templates table-bordered">
					<thead>
						<tr>
						<th><input id="all" checke="true" type="checkbox" onclick="alll()"></th>
						  <th>ID</th>
							<th>申请对象信息</th>
							<th>申请人信息</th>
							<th>状态/时间</th>
						</tr>
						
					</thead>
					<tbody>
						<?php  if(!empty($lists)):?>
							<?php foreach($lists as $val):?>
                            <tr id="<?=$val->appid; ?>">
                            <td><input type="checkbox" name="sid[]" value="<?=$val->userid?>"></td>
                                <td><?=$val->appid;?></td>
                                <td>
                                    <a href="/master/enrollment/appmanager/app_detail?id=<?=$val->appid?>" class="" rel="tooltip" data-original-title="详情">
									申请编号：<?=$val->number;?></a><br/>
                                    专业名：<?=$val->name;?>
									<?php if(!empty($val->scholorshipid) && !empty($scholorshipapply[$val->scholorshipid])){?>
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
                        
                                    	提交时间：<?=date('Y-m-d H:i:s',$val->applytime);?><br/>
                                    	
                                </td>
                                <!-- <td>
						              	申请表： 
						              	<a href="/epmaster/appmanager/check_info?id=<?=$val->appid?>&cnav=347&cleft=349&cmenuid=351&label_id=0">
                                                                                                           审核</a>
						              	<a href="/epmaster/appmanager/apply_form_download?id=<?=$val->appid;?>&type=download">
						              	下载</a><br/>
						                 附件：
						                <a rel="<?=$val->appid; ?>" class="tip"  id='online' href='javascript:viod();'>                           
						                                           审核</a>
						                <a href="/epmaster/appmanager/attach_download?id=<?=$val->appid;?>">                           
						                                           下载</a><br/><br/>
 
						              
                                </td>
                               <td>

                                <?php if ($val->status!=2 ) :?>
                                	 <a href="javascript:pub_alert_html('/epmaster/change_app_status/index?id=<?=$val->appid?>&label_id=<?=$label_id?>');" class="btn btn-small btn-success" rel="tooltip" data-original-title="修改">修改状态</a><br/>
                                <?php endif;?>

                                  
                                   <a id="remark" data-type="textarea" class="editabless" data-placement="left" data-original-title="修改备注"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="#" rel="tooltip">
								      <?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?></a>  <br /><br />
								      <a class="btn btn-small btn-danger" href="javascript:pub_alert_confirm(this,'','/epmaster/process/over_app?id=<?=$val->appid;?>');">
                                                                                                     结束</a>
                                </td>-->
                            </tr>
                            <?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
			</div>
			</form>
		</div>
	</div>
</div>

		<div id="dialog-confirm" class="hide">
			<!--<div class="alert alert-info bigger-110" id="confirm">
				您确定要删除吗?删除后将不能恢复.
			</div>

			<div class="space-6"></div>-->

			<p class="bigger-110 bolder center grey">
				<i class="ace-icon fa fa-hand-o-right blue bigger-120"></i>
				Are you sure?
			</p>
		</div>
		<div id="dialog-message" class="hide">
			<!--<p>
				This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.
			</p>

			<div class="hr hr-12 hr-double"></div>-->
			
			<p>
				Are you sure?
			</p>
		</div><!-- #dialog-message -->
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
function message(){
	$('#checked').attr({
		action: '/master/student/student/send_message',
	});
}
function email(){
	$('#checked').attr({
		action: '/master/student/student/send_email',
	});
}
function alll(){
  	 if($("#all").attr("checke") == "true"){
		  $("input[name='sid[]']").each(function(){
		 	 this.checked=true;
		  });
		   $("#all").attr("checke","flase")
	  }else{
	  		$("input[name='sid[]']").each(function(){
			   this.checked=false;
		 	 });
		  	 $("#all").attr("checke","true");
	  }
}
function derive(){
	var is_subimt = false;
	 $("input[name='sid[]']").each(function(){
		 	 if(this.checked==true){
		 	 	 is_subimt = true;
		 	 }
		  });

	 if(is_subimt === false){
	 	pub_alert_error('请选择学生');
	 }
	 
	 return is_subimt;
}
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

		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}

	});
}

function del(id){
pub_alert_confirm('/master/enrollment/apply_form/del_group_global?id='+id);
}

</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
