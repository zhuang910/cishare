<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">在学管理</a>
	</li>
	<li class="active">选课管理</li>
	<li class="active">排课</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		排课管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			学生列表
			<a class="btn btn-primary btn-sm btn-default btn-sm" type="button" title="返回" href="javascript:history.back()" style="float:right;">
			<span class="ace-icon fa fa-mail-reply"></span>
			返回
			</a>
		</div>
		<!-- <div class="table-responsive"> -->
		<!-- <div class="dataTables_borderWrap"> -->
		<div>
		<form id="checked" method="post" onSubmit="return derive()">
		<div>   
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				<li <?php if($label_id ==0):?> class="active"<?php endif;?>>
					<a href="/master/student/elestu/index?cid=<?=$cid?>&label_id=0"><h5>未确认</h5></a>
				</li>
				<li <?php if(!empty($label_id) && $label_id =='1'):?> class="active"<?php endif;?>>
					<a href="/master/student/elestu/index?cid=<?=$cid?>&label_id=1"><h5>已确认</h5></a>
				</li>
				<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
					<a href="/master/student/elestu/index?cid=<?=$cid?>&label_id=2"><h5>报名失败</h5></a>
				</li>
				<li style="float:right;">
				<button class="btn btn-info" data-last="Finish">
					<span class="bigger-110">批量确认选课</span>
				</button>
				</li>
			</ul>
		</div>                                   
			<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
				<thead>
					<tr>
						<th>
							<input id="all" checke="true" type="checkbox" onclick="alll()">
						</th>
						<th>
							<label class="position-relative">
							<!-- <input type="checkbox" class="ace" />
							<span class="lbl"></span> -->
							ID
							</label>
						</th>
						<th>学生名</th>
						<th>英文名称</th>
						<th>护照</th>
						<th>所属专业->班级</th>
						<th>学期</th>
						<th>代课老师</th>
						<th>上课教室</th>
						<th>上课时间</th>
						<th>状态</th>
						<th width="200"></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			</form>
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
<link rel="stylesheet" href="<?=RES?>master/css/ace.onpage-help.css" />

<script type="text/javascript">
function shibai(id){
	pub_alert_confirm('/master/student/elestu/shiabi?id='+id);
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
	 	pub_alert_error('请选择课程');
	 }
	 
	 if(is_subimt===true){
	 	var data=$('#checked').serialize();
	 	$.ajax({
	 		url: '/master/student/elestu/paike',
	 		type: 'POST',
	 		dataType: 'json',
	 		data: data,
	 	})
	 	.done(function(r) {
	 		if(r.state==1){
	 			pub_alert_success();
	 			window.location.reload();
	 		}	
	 	})
	 	.fail(function() {
	 		console.log("error");
	 	})
	 	.always(function() {
	 		console.log("complete");
	 	});
	 	
	 }
	 return false;
}
function pub_alert_htmls(url,isjump,addvar){
	var data=$('#checked').serialize();
  addvar = addvar ? '&' : '?';
  isjump ? location.href=url+addvar+UVAR : '';
  $.ajax({
    type:'POST',
    url:url,
    dataType:'json',
    data: data,
    success:function(r){
      if(r.state == 1){
        $('body').prepend(r.data);
        _pub_alert_bootbox();
      }else{
        pub_alert_error(r.info);
      }
    }
  })
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

		 opt.bAutoWidth=true; 
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "<?=$zjjp?>elestu/index?cid=<?=$cid?>&label_id=<?=$label_id?>";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "checkbox" },
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "enname" },
								{ "mData": "passport" },
								{ "mData": "majorsquad" },
								{ "mData": "nowterm" },
								{ "mData": "tname" },
								{ "mData": "rname" },
								{ "mData": "paike" },
								{ "mData": "state" },
								{"mData":"operation"}
							];
			opt.aaSorting = [[1,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [0] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}
function queren(id){
	pub_alert_confirm('/master/student/elestu/queren?id='+id);
}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>
