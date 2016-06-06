<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">信息管理</a>
	</li>
	<li>
		<a href="javascript:;">内容管理</a>
	</li>
	<li class="active">文章管理</li>
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
		预警提醒
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				<div class="table-header">
				预警提醒
				
				
				</div>
			</div>
			<?php 
				$array = array(
					'0' => '缺勤警告',
					'1' => '缺勤开除',
					'2' => '签证即将到期',
					'3' => '签证已到期',
				);
			?>
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				<?php if(!empty($array)){ 
					foreach($array as $ak => $av){
				?>
					<li <?php if(!empty($label_id) && $label_id ==$ak):?> class="active"<?php endif;?>>
						<a href="/master/student/student_check/index?label_id=<?=$ak?>"><h5><?=$av?></h5></a>
                    </li>
				<?php }}?>
			</ul>

			<div>
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax cms_news dataTable-url" data-ajax-url='/master/student/student_check/index?label_id=<?=$label_id?>'>
					<thead>
						<tr>
							<th class="center">
								ID
							</th>
							<th>学号</th>
							<th>姓名</th>
							<th>缺勤数量</th>

							<th>
								签证到期时间
							</th>
							<th>专业</th>

							<th>班级</th>
							<th>状态</th>
						</tr>
					</thead>

					<tbody role="alert" aria-live="polite" aria-relevant="all">
						
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
<link rel="stylesheet" href="<?=RES?>master/css/ace.onpage-help.css" />

<script type="text/javascript">
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
			if($(this).hasClass("dataTable-url")){
				opt.sAjaxSource = $(this).attr('data-ajax-url');
			}else{
				opt.sAjaxSource = "<?=$zjjp?>/news";
			}
		}

		if($(this).hasClass("cms_news")){
			opt.bStateSave = false;
			opt.aoColumns = [

								{ "mData": "id" },
								{ "mData": "studentid" },
								{ "mData": "name" },
								{ "mData": "enname" },
								{ "mData": "enroltime" },
								{ "mData": "visaendtime" },
								{ "mData": "leavetime" },
								{ "mData": "majorid" },
								{ "mData": "squadid" },
								{"mData":"operation"}
							];
			opt.aaSorting = [[4,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 6 ] }];
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