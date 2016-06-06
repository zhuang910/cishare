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
	<li class="active">现金凭据管理</li>
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
		现金凭据管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
					
									<div class="table-header">
										收支列表
											
									</div>

									<!-- <div class="table-responsive"> -->

									<!-- <div class="dataTables_borderWrap"> -->
									<div>                                  
										<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
											<thead>
													<tr>
													<th><input type="text" id="field_6" placeholder="ID" style="width:25px;"></th>
													<th><input type="text" id="field_0" placeholder="中文名字" style="width:90px;"></th>
													<th width="120"><input type="text" id="field_1" placeholder="英文名字" style="width:90px;"></th>
													<th width="120"><input type="text" id="field_2" placeholder="护照号" style="width:90px;"></th>
													<th width="120">
														<select id="field_3">
															<option value="">请选择</option>
															<option value="1">申请报名费</option>
															<option value="2">押金</option>
															<option value="3">接机</option>
															<option value="4">住宿</option>
															<option value="5">入学押金</option>
															<option value="6">学费</option>
															<option value="7">电费</option>
															<option value="8">书费</option>
															<option value="9">保险费</option>
															<option value="10">住宿押金费</option>
															<option value="11">换证费</option>
															<option value="12">重修费</option>
															<option value="13">床品费</option>
															<option value="14">电费押金</option>
															<option value="15">申请减免费</option>
															
														</select>
													</th>
													<th width="120"><input type="text" id="field_4" placeholder="收据号" style="width:90px;"></th>
													<th width="120"><input type="text" id="field_5" placeholder="实缴费用" style="width:90px;"></th>
													<th width="120"></th>
													<th width="120"></th>
													<th width="120"></th>
													<th width="120"></th>
													</tr>
												</thead>
											<thead>
												<tr>
													<th class="center">
														ID
													</th>
													<th>用户</th>
													<th>英文名</th>
													<th>护照号</th>
													<th>收支类型</th>
													<th>收据号</th>
													<th>实缴费用</th>
													<th>金钱单位</th>
													<th>状态</th>
													<th>创建时间</th>
													<th></th>
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
$(function () {
        $("#sample-table-2").dataTable({
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
            },
            "bProcessing":true,
            "bServerSide":true,
            "sAjaxSource":'/master/finance/cash',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
            "fnDrawCallback" :function(){
                
            },
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 0,1,2,3,4,5,6,7,8,9,10 ] }],
            // "aaSorting" : [[2,'desc']],
            "aoColumns" : [
            		{ "mData": "field_0" },
					{ "mData": "field_1" },
					{ "mData": "field_2" },
					{ "mData": "field_3" },
					{ "mData": "field_4" },
					{ "mData": "field_5" },
					{ "mData": "field_6" },
					{ "mData": "field_7" },
					{ "mData": "field_8" },
					{ "mData": "field_9" },
					{ "mData": "field_10" }

                ]
        });
		// $('.dataTables_filter input').attr("placeholder", "关键字");
  //       $(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
  //           disable_search_threshold: 9999999
  //       });


$('#field_0').on( 'keyup', function () {
            zjj_datatable_search(0,$("#field_0").val());
        } );
$('#field_1').on( 'keyup', function () {
            zjj_datatable_search(1,$("#field_1").val());
        } );
$('#field_2').on( 'keyup', function () {
            zjj_datatable_search(2,$("#field_2").val());
        } );
$('#field_3').on( 'change', function () {
            zjj_datatable_search(3,$("#field_3").val());
        } );
$('#field_4').on( 'keyup', function () {
            zjj_datatable_search(4,$("#field_4").val());
        } );
$('#field_5').on( 'keyup', function () {
            zjj_datatable_search(5,$("#field_5").val());
        } );
$('#field_6').on( 'keyup', function () {

            zjj_datatable_search(6,$("#field_6").val());
        } );
function zjj_datatable_search(column,val){
        $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
    }
})
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>