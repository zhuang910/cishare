<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">教务管理</a>
	</li>
	<li class="active">选课管理</li>
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
		教务管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="table-header">
			课程列表
		</div>
		<!-- <div class="table-responsive"> -->
		<!-- <div class="dataTables_borderWrap"> -->
		<div>
		<form id="checked" method="post" onSubmit="return derive()">
		<div>   
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				<li>
				<button class="btn btn-info" data-last="Finish">
					
					<i class="fa fa-calendar bigger-110"></i>
					<span class="bigger-110">设置选课起止时间</span>
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
						<th>课程名称</th>
						<th>英文名称</th>
						<th>课时</th>
						<th>学分</th>
						<th>选课开始时间</th>
					 	<th>选课结束时间</th>
					 	<th>报名人次</th>
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
	 	 pub_alert_htmls('/master/student/electives/settime?s=1')
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
			opt.sAjaxSource = "<?=$zjjp?>electives";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "checkbox" },
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "englishname" },
								{ "mData": "hour" },
								{ "mData": "credit" },
								{ "mData": "starttime" },
								{ "mData": "endtime" },
								{ "mData": "number" },
								{"mData":"operation"}
							
							];
			opt.aaSorting = [[1,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [0,8,9] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>
