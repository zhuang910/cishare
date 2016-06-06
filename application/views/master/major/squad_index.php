<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">申请设置</a>
	</li>
	<li><a href="/master/major/major">专业设置</a></li>
	<li class="active">班级管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
<link rel="stylesheet" href="<?=RES?>master/css/ace.min.css" />
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		班级管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
	
									<div class="table-header">
										班级列表
										<button style="float:right;" onclick="backmajor()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加教室" type="button">
										<span class="ace-icon fa fa-reply"></span>
										返回专业
										</button>
									</div>

									<!-- <div class="table-responsive"> -->

									<!-- <div class="dataTables_borderWrap"> -->
									<div>                                  
										<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
											<thead>
												<tr><td colspan="10" class="center"><a href="<?=$zjjp?>squad/add?majorid=<?=$majorid?>">为新学期添加班级</a></td></tr>
												<tr>
												<th >当前学期</th>
													<th class="center">
														ID
													</th>
													<th>班级名称</th>
													<th>所属专业</th>
													<th>英文名称</th>
													
													<th>开班时间</th>
													
													<th>人数上限</th>
											
													<th>状态</th>

													<th width="150"></th>

												</tr>
											
											</thead>

											<tbody>
												
											</tbody>
										</table>






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
<script src="<?=RES?>master/js/jquery.cookie.js"></script>
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<script type="text/javascript">
function backmajor(){
		window.location.href="/master/major/major";
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
				"sZeroRecords" : '<a href="<?=$zjjp?>squad/add?majorid=<?=$majorid?>">为该专业添加班级</a>'
			}
		};

		 opt.bAutoWidth=true; 
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "<?=$zjjp?>squad?id="+<?=$majorid?>;
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "nowterm" },
								{ "mData": "id" },
								{ "mData": "name" },
								{ "mData": "majorid" },
								{ "mData": "englishname" },
								
								{ "mData": "classtime" },
							
								{ "mData": "maxuser" },
								{ "mData": "state" },
								
								{"mData":"operation"}
								
							
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 8 ] }];
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
	pub_alert_confirm('/master/major/squad/del?id='+id);
}
function show(that,nowterm,id,majorid){

	

	if($(that).text()=='展开'){
	var temp_id=$.cookie('temp_id');
	var tr = $(that).parents().eq(1);
	
	$.ajax({
		url: '/master/major/squad/nowterm_squad?nowterm='+nowterm+'&majorid='+majorid,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			var html='';
        $(that).text('关闭');
		$.each(r.data,function(k,v){
			var state=v.state==1 ?'<span class="label label-success">未结束</span>':'<span class="label label-important">已结束</span>';
			html+='<tr id="temp_tr'+nowterm+'"><td> </td><td>'+v.id+'</td>'+'<td>'+v.name+'</td><td>'+v.mname+'</td><td>'+v.englishname+'</td><td>'+v.classtime+'</td><td>'+v.maxuser+'</td><td>'+state+'</td><td><a class="green" href="squad/edit?id='+v.id+'&majorid=' + v.majorid +'&nowterm='+v.nowterm+'"><i class="ace-icon fa fa-pencil bigger-130"></i></a> <a href="javascript:;" onclick="del('+v.id+')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></td></tr>';
			if(v.id == id) html='';
		})
		
		tr.after(html);
		}
	})
	.fail(function() {
		console.log("error");
	})

	}else{
		$('[id=temp_tr'+nowterm+']').slideUp(600,function(){ $(this).remove()});
		
		$(that).text('展开');
	}
	
	
	
	
	



}
function temp_tables(id){
	
}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>