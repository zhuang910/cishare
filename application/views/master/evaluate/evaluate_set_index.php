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
	<li>
		<a href="javascript:;">评教管理</a>
	</li>
	<li class="active">评教设置</li>
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
		评教管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
				<div class="table-header">
					评教类列表
					<button style="float:right;" onclick="pub_alert_html('/master/evaluate/evaluate_set/add?s=1')" class="btn btn-primary btn-sm btn-default btn-sm" title="添加教室" type="button">
					<span class="glyphicon  glyphicon-plus"></span>
					添加评教类
					</button>	
				</div>
				
				<!-- <div class="table-responsive"> -->

				<!-- <div class="dataTables_borderWrap"> -->
				<div> 
				<form id="checked" method="post" onSubmit="return derive()">
					<!-- <div>   
						<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
							<li>
							<button class="btn btn-info" data-last="Finish">
								
								<i class="fa fa-calendar bigger-110"></i>
								<span class="bigger-110">设置选课起止时间</span>
							</button>
							</li>
						</ul>
					</div>     -->                                
					<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
						<thead>
							<tr>
								<!-- <th>
									<input id="all" checke="true" type="checkbox" onclick="alll()">
								</th> -->
								<th class="center">
									ID
								</th>
								<th>类中文名称</th>
								<th>类英文名称</th>
								<th>排序</th>
								<th>类型</th>
							<!-- 	<th>评教起止时间</th>
								<th>评教结束时间</th> -->
								<th>状态</th>
								<th width="150"></th>

							</tr>
						</thead>

						<tbody>
							
						</tbody>
					</table>
					</form>
				</div>
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
	 	pub_alert_error('请选择评教类');
	 }
	 
	 if(is_subimt===true){
	 	 pub_alert_htmls('/master/evaluate/evaluate_set/settime?s=1')
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
function add(){
		pub_alert_html('/master/evaluate/evaluate_set/add')
	}
	if($('#sample-table-2').length > 0){
	$('#sample-table-2').each(function(){
		var opt = {
			"iDisplayLength" : 25,
			"sPaginationType": "full_numbers",
			"oLanguage":{
				"sSearch": "<span>添加教室</span></a><span>搜索:</span> ",
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
			opt.sAjaxSource = "<?=$zjjp?>evaluate_set";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								// { "mData": "checkbox" },
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "enname" },
								{ "mData": "orderby" },
								{ "mData": "type" },
								// { "mData": "starttime" },
								// { "mData": "endtime" },
								{ "mData": "state" },
								{"mData":"operation"}
							
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4,5,6 ] }];
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
pub_alert_confirm('/master/evaluate/evaluate_set/del?id='+id);

}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>