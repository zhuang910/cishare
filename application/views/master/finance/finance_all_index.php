<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">后台</a>
	</li>

	<li>
		<a href="javascript:;">收费管理</a>
	</li>
	<li>
		<a href="javascript:;">费用分类管理</a>
	</li>
	<li class="active">所有订单</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
<style type="text/css">
	body th{width:200px;text-align: center;padding:0px;}
	body td{width:200px;text-align: center;pading:0px;}
</style>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		费用分类管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
					
									<div class="table-header">
										订单列表
											
									</div>

									<!-- <div class="table-responsive"> -->

									<!-- <div class="dataTables_borderWrap"> -->
									<div>                                  
										<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
											<thead>
												<th><input type="text" id="field_0" placeholder="id" style="width:30px;"></th>
												<th><input type="text" id="field_1" placeholder="中文名" style="width:100px;"></th>
												<th><input type="text" id="field_2" placeholder="护照号" style="width:100px;"></th>
												<th><input type="text" id="field_3" placeholder="订单号" style="width:100px;"></th>
												<th>
													<select id="field_4">
															<option value="">请选择</option>
															<option value="1">申请报名费</option>
															<option value="2">入学押金</option>
															<option value="3">接机</option>
															<option value="4">住宿</option>
															<option value="5">押金</option>
															<option value="6">学费</option>
															<option value="7">电费</option>
															<option value="8">书费</option>
															<option value="9">保险费</option>
															<option value="10">住宿押金费</option>
															<option value="11">换证费</option>
															<option value="12">重修费</option>
															<option value="13">床品费</option>
															<option value="14">电费押金</option>							
														</select>
												</th>
												<th><input type="text" id="field_5" placeholder="总钱数" style="width:100px;"></th>
												<th></th>
												<th></th>
												<th></th>
											</thead>
											<thead>
												<tr>
													<th class="center">
														ID
													</th>
													<th>中文名</th>
													<th>护照号</th>
													<th>订单号</th>
													<th>订单类型</th>
													<th>总钱数</th>
													<th>支付方式</th>
													<th>支付状态</th>
													<th>支付时间</th>
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
			opt.sAjaxSource = "<?=$zjjp?>finance_all";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "chname" },
								{ "mData": "passport" },
								{ "mData": "ordernumber" },		
								{ "mData": "ordertype" },
								{ "mData": "ordermondey" },
								{ "mData": "paytype" },
								{ "mData": "paystate" },
								{ "mData": "paytime" },
							
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 0,1,2,3,4,5,6,7,8 ] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
$('#field_0').on( 'keyup', function () {
            zjj_datatable_search(0,$("#field_0").val());
        } );
$('#field_1').on( 'keyup', function () {
            zjj_datatable_search(1,$("#field_1").val());
        } );
$('#field_2').on( 'keyup', function () {
            zjj_datatable_search(2,$("#field_2").val());
        } );
$('#field_3').on( 'keyup', function () {
            zjj_datatable_search(3,$("#field_3").val());
        } );
$('#field_4').on( 'change', function () {
            zjj_datatable_search(4,$("#field_4").val());
        } );
$('#field_5').on( 'keyup', function () {
            zjj_datatable_search(5,$("#field_5").val());
        } );
function zjj_datatable_search(column,val){
        $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
    }



	});
}
function del(id){
	pub_alert_confirm('/master/basic/faculty/del?id='+id);

}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>