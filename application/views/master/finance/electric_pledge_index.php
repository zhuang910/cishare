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
	<li class="active">电费押金管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
<style type="text/css">
	body th{width:200px;padding:0px;text-align: center;}
	body td{padding:0px;width:200px;text-align: center;}
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
										收支列表
											
									</div>

									<!-- <div class="table-responsive"> -->

									<!-- <div class="dataTables_borderWrap"> -->
									<div>                                  
										<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
											<thead>
													<tr>
													<th><input type="text" id="field_0" placeholder="id" style="width:30px;"></th>
													<th><input type="text" id="field_1" placeholder="中文名" style="width:100px;"></th>
													<th><input type="text" id="field_2" placeholder="英文名" style="width:100px;"></th>
													<th><input type="text" id="field_3" placeholder="护照号" style="width:100px;"></th>
													<th><input type="text" id="field_4" placeholder="应缴金额" style="width:100px;"></th>
													<th><input type="text" id="field_5" placeholder="实缴金额" style="width:100px;"></th>
													<th></th>
													<th></th>				
													<th></th>
													</tr>
												</thead>
											<thead>
												<tr>
													<th class="center">
														ID
													</th>
													<th width="67">中文名</th>
													<th>英文名</th>
													<th>护照号</th>
													<th>应缴金额</th>
													<th>实缴金额</th>
													<th>支付状态</th>
													<th>是否退费</th>
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
            "sAjaxSource":'/master/finance/electric_pledge',
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
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 0,1,2,3,4,5,6,7,8 ] }],
            // "aaSorting" : [[7,'desc']],
            "aoColumns" : [
            		{ "mData": "field_0" },
					{ "mData": "field_1" },
					{ "mData": "field_2" },
					{ "mData": "field_3" },
					{ "mData": "field_4" },
					{ "mData": "field_5" },
					{ "mData": "field_6" },
					{ "mData": "field_7" },
					{ "mData": "field_8" }

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
$('#field_3').on( 'keyup', function () {
            zjj_datatable_search(3,$("#field_3").val());
        } );
$('#field_4').on( 'keyup', function () {
            zjj_datatable_search(4,$("#field_4").val());
        } );
$('#field_5').on( 'keyup', function () {
            zjj_datatable_search(5,$("#field_5").val());
        } );
function zjj_datatable_search(column,val){
        $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
    }
})
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>