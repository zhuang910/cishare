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
	<li class="active">所有收支</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
<style type="text/css">
	body th{width:100px;padding:0px;}
	body td{width:100px;padding:0px;}
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
				<a type="button" href="javascript:;" onclick="pub_alert_html('/master/finance/budget/excel_where')" title="导出发书学生" class="btn btn-primary btn-sm btn-default btn-sm"  style="float:right;">
					<i class="ace-icon fa fa-user bigger-110"></i>
					按类型导出
			    </a>
		</div>
		<!-- <div class="table-responsive"> -->

		<!-- <div class="dataTables_borderWrap"> -->
		<div> 
			<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				<li <?php if($label_id ==1):?> class="active"<?php endif;?>>
				<a href="/master/finance/budget/index?&label_id=1"><h5>收费</h5></a>
				</li>
				<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
				<a href="/master/finance/budget/index?&label_id=2"><h5>退费</h5></a>
				</li>  

			</ul>                        
			<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
				
				<thead>
					<tr>
						<th class="center">
							ID
						</th>
						<th>中文名</th>
						<th>护照号</th>
						<th>收支类型</th>
						<?php if($label_id==1):?>
						<th>应交费用</th>
						<th>实交费用</th>
						<th>支付状态</th>
						<th>支付方式</th>
						<th>支付时间</th>
						<?php else:?>
						<th>应退费用</th>
						<th>实退费用</th>
						<th>退费时间</th>

						<?php endif;?>
						
						<th>学期</th>
						<th>奖学金用户</th>
					</tr>
				</thead>
				<thead>
					<th><input type="text" id="field_0" placeholder="ID" style="width:30px;"></th>
					<th><input type="text" id="field_1" placeholder="中文名" style="width:60px;"></th>
					<th><input type="text" id="field_2" placeholder="护照号" style="width:60px;"></th>
					<th>
						<select id="field_3">
							<option value="">请选择</option>
							<option value="1">申请费</option>
							<option value="2">押金</option>
							<option value="3">接机费</option>
							<option value="4">住宿费</option>
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
							<option value="15">申请减免学费</option>
							<option value="16">奖学金费用</option>
						</select>
					</th>
					<th><input type="text" id="field_4" placeholder="<?=$label_id==1?'应交费用':'应退费用'?>" style="width:80px;"></th>
					<th><input type="text" id="field_5" placeholder="<?=$label_id==1?'实交费用':'实退费用'?>" style="width:80px;"></th>
					<?php if($label_id==1):?>
					<th>
						<select id="field_6">
							<option value="">请选择</option>
							<option value="0">未支付</option>
							<option value="1">成功</option>
							<option value="2">失败</option>
							<option value="3">待审核</option>
						</select>
					</th>
					<?php endif;?>
					<?php if($label_id==1):?>
					<th>
						<select id="field_7">
							<option value="">请选择</option>
							<option value="1">paypal</option>
							<option value="2">payease</option>
							<option value="3">汇款</option>
							<option value="4">现金</option>
							<option value="5">刷卡</option>
							<option value="6">奖学金支付</option>
						</select>
					</th>
				<?php endif;?>
					<th></th>
				
					<th>
						<select id="field_8">
						<?php if(!empty($maxnum)):?>
							<option value="">请选择</option>
							<?php for ($i=1; $i <= $maxnum; $i++) {?>
								<option value="<?=$i?>">第<?=$i?>学期</option>
							<?php }?>
						<?php endif;?>
						</select>
					</th>
					<th></th>
				</thead>
				<tbody>
				</tbody>
			</table>
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
			opt.sAjaxSource = "<?=$zjjp?>budget?label_id=<?=$label_id?>";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "field_0" },
								{ "mData": "field_1" },
								{ "mData": "field_2" },
								{ "mData": "field_3" },
								{ "mData": "field_4" },
								{ "mData": "field_5" },
								<?php if($label_id==1){?>
								{ "mData": "field_6" },
								{ "mData": "field_8" },
								<?php }?>
								{ "mData": "field_7" },
								{ "mData": "field_9" },
								{ "mData": "field_10" },
							
							];
			var num=<?=$label_id==1?10:8;?>;
			opt.aaSorting = [[0,'asc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ num ] }];
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
$('#field_3').on( 'change', function () {
            zjj_datatable_search(3,$("#field_3").val());
        } );
$('#field_4').on( 'keyup', function () {
            zjj_datatable_search(4,$("#field_4").val());
        } );
$('#field_5').on( 'keyup', function () {
            zjj_datatable_search(5,$("#field_5").val());
        } );
$('#field_6').on( 'change', function () {

            zjj_datatable_search(6,$("#field_6").val());
        } );
$('#field_7').on( 'change', function () {

            zjj_datatable_search(7,$("#field_7").val());
        } );
$('#field_8').on( 'change', function () {

            zjj_datatable_search(8,$("#field_8").val());
        } );
function zjj_datatable_search(column,val){
        $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
    }



	});
}
function del(id){
	pub_alert_confirm('/master/basic/faculty/del?id='+id);

}
function tuifei(id){
	pub_alert_confirm('/master/finance/budget/tuifei?id='+id,1,'确认退费');

}
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer'); ?>