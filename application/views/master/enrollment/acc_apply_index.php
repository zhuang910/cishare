<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">住宿管理</a>
	</li>
	<li class="active">住宿预订</li>
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
	  住宿管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
			<input type="hidden" name="is_userid" value="yes">
			<div>
				<!--标签-->
					<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
						<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_apply/index?&label_id=1"><h5>未交押金</h5></a>
						</li>
						<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_apply/index?&label_id=2"><h5>处理中</h5></a>
						</li>
						<li <?php if(!empty($label_id) && $label_id =='3'):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_apply/index?&label_id=3"><h5>预订成功</h5></a>
						</li>
						<li <?php if(!empty($label_id) && $label_id =='4'):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_apply/index?&label_id=4"><h5>预订失败</h5></a>
						</li>
						<li <?php if(!empty($label_id) && $label_id =='5'):?> class="active"<?php endif;?>>
						<a href="/master/enrollment/acc_apply/index?&label_id=5"><h5>重点关注</h5></a>
						</li>
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
				<!--标签-->
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax admin_index">
					<thead>
						<tr>
							<th>
								<input id="all" checke="true" type="checkbox" onclick="alll()">
							</th>
							<th class="center">
								ID
							</th>
							<th>个人信息</th>
							<th>楼宇信息</th>
							<th>预定信息</th>
							<th>备注</th>
							<th>提交时间</th>
							<th>状态</th>
							<th width="250">操作</th>
						</tr>
					</thead>

					<tbody role="alert" aria-live="polite" aria-relevant="all">
						
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
function zhongdian(id,state){
	pub_alert_confirm('/master/enrollment/acc_apply/zhongdian?id='+id+'&state='+state);
}
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
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "/master/enrollment/acc_apply/index?label_id=<?=$label_id?>";
		}

		if($(this).hasClass("admin_index")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "checkbox" },
								{ "mData": "id" },
								{ "mData": "personal" },
								{ "mData": "buildinginfo" },
								{ "mData": "applyinfo" },
								{ "mData": "remark" },
								{ "mData": "subtime" },
								{ "mData": "state" },
								{ "mData": "operation" }
							];
			opt.aaSorting = [[1,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 0,8 ] }];
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
pub_alert_confirm('/master/enrollment/acc_apply/del?id='+id);
	
}

function upstate(id,state){

	pub_alert_confirm('/master/enrollment/acc_apply/upstate?id='+id+'&state='+state);
	
}
function labour_affirm(id,acc_state){

	pub_alert_confirm('/master/enrollment/acc_apply/labour_affirm_upstate?id='+id+'&acc_state='+acc_state,'','确认交押金？');
	
}
function emphasis_affirm(id,acc_state){
	pub_alert_confirm('/master/enrollment/acc_apply/emphasis_student?id='+id+'&acc_state='+acc_state);
}
</script>
<!-- end script -->

<?php $this->load->view('master/public/footer');?>
