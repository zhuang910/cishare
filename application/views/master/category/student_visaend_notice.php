<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">后台</a>
	</li>

	<li>
		<a href="javascript:;">在学管理</a>
	</li>
	<li>
		<a href="javascript:;">学生管理</a>
	</li>
	<li class="active">预警提醒</li>
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
		预警提醒 
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
				<div class="table-header">
					签证预警提醒
				</div>

				<!-- <div class="table-responsive"> -->
				<?php 
					$array = array(
						'2' => '签证即将到期',
						'3' => '签证已到期',
					);
				?>
				<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
					<?php if(!empty($array)){ 
						foreach($array as $ak => $av){
					?>
						<li <?php if(!empty($label_id) && $label_id ==$ak):?> class="active"<?php endif;?>>
							<a href="/master/student/student_check/student_visaend?label_id=<?=$ak?>"><h5><?=$av?></h5></a>
	                    </li>
					<?php }}?>
				</ul>
				<!-- <div class="dataTables_borderWrap"> -->
				<div>                                  
					<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
						<thead>
							<tr>
								<th class="center">
									ID
								</th>
								<th>学生信息</th>
								<th>证件号</th>
								<th>签证到期时间</th>

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
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<script type="text/javascript">
function add(){
		window.location.href="/master/basic/faculty/add";
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
			opt.sAjaxSource = "<?=$zjjp?>student_check/student_visaend?label_id="+<?=$label_id?>;
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "studentid" },
								{ "mData": "visanumber" },
								{ "mData": "visatime" },
							];
			opt.aaSorting = [[0,'desc']];
			//opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4] }];
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