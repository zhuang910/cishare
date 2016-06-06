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
	<li class="active">$ordername</li>
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
		费用分类管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="table-responsive">

			 <div class="dataTables_borderWrap"> 
				<div> 
					<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
						<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
						<a href="/master/finance/<?=$control?>/index?&label_id=1"><h5>支付成功</h5></a>
						</li>
						<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
						<a href="/master/finance/<?=$control?>/index?&label_id=2"><h5>支付失败</h5></a>
						</li>
						
					</ul>
					<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
						<thead>
							<tr>
								<th class="center">
									ID
								</th>
								<th>用户信息</th>
								<th>订单号</th>
								<th>总钱数</th>
								<th>支付方式</th>
								<th>支付时间</th>
								<th>支付状态</th>
							</tr>
						</thead>
						<thead>
						<tr>
							<th>
                                <input type="text" id="apply_obj_ID" placeholder="ID" style="width:100px;">
                            </th>
							<th>
                                
                            </th>
							 <th>
                                <input type="text" id="apply_state_ordernumber" placeholder="订单号" style="width:100px;">
                                
                            </th>
							<th>
                                <input type="text" id="apply_state_ordermoney" placeholder="总钱数" style="width:100px;">
                                
                            </th>
							<th colspan="3">
                                <select name="apply_state_paytype" id="apply_state_paytype" style="width:100px;">
								<option value="">--支付方式-</option>
								<option value="1">Paypal</option>
								<option value="2">Payease</option>
								<option value="3">凭据</option>
								</select>
								<input type="text" id="apply_state_paytime" placeholder="支付时间" style="width:100px;">
                                
                            </th>
							
							
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
$(function(){

if($('#sample-table-2').length > 0){
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
            "sAjaxSource":'<?=$zjjp?><?=$control?>/index?label_id=<?=$label_id?>',
            "fnServerData":function(sSource, aoData, fnCallback){
                $.ajax( {
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            },
           
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 3 ] }],
            "aaSorting" : [[2,'desc']],
            "aoColumns" : [
                    { "mData": "id" },
					{ "mData": "userid" },
					{ "mData": "ordernumber" },
					{ "mData": "ordermondey" },
					{ "mData": "paytype" },
					{ "mData": "paytime" },
					{ "mData": "paystate" },
                ]
        });
	
	$('#apply_obj_ID').on( 'keyup', function () {
            zjj_datatable_search(0,$("#apply_obj_ID").val());
        } );

        $('#apply_state_paytype').change(function () {
            zjj_datatable_search(1,$("#apply_state_paytype").val());
        } );

        $('#apply_state_ordernumber').on( 'keyup', function () {
            zjj_datatable_search(2,$("#apply_state_ordernumber").val());
        } );

		 $('#apply_state_ordermoney').on( 'keyup', function () {
            zjj_datatable_search(3,$("#apply_state_ordermoney").val());
        } );
		
		   $('#apply_state_paytime').on( 'keyup', function () {
            zjj_datatable_search(4,$("#apply_state_paytime").val());
        } );
	

        function zjj_datatable_search(column,val){
            $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
        }
	
}


});


</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>