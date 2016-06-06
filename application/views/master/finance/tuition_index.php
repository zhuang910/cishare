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
	<li class="active">学费管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>master/css/jquery.dataTables.css">
 <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />
<style type="text/css">
	body th{width:100px;padding:0px;text-align: left;}
	body td{padding:0px;width:100px;text-align: left;}
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
													<th class="center">
														ID
													</th>
													<th width="67">用户信息</th>
													<th>专业</th>
													<th>学期</th>
													<th>班级</th>
													<th>支付状态</th>
													<th>支付日期</th>
													<th></th>
												</tr>
											</thead>
											<thead>
													<tr>
													<th><input type="text" id="field_0" placeholder="id" style="width:30px;"></th>
													<th><input type="text" id="field_1" placeholder="用户信息" style="width:80px;"></th>
													<th>
														<select onchange="change_major()" id="major" style="width:75px;">
														<option value="/master/finance/tuition?major_id=0">请选择</option>
														
														
														 <?php foreach($major_info as $item){ ?>
														<optgroup label="<?=$item['degree_title']?>">
														<?php foreach($item['degree_major'] as $item_info){ ?>
															<option value="/master/finance/tuition?major_id=<?=$item_info->id?>" <?=!empty($major_id)&&$major_id==$item_info->id?'selected="selected"':''?>><?=$item_info->name?></option>
														<?php } ?>
														</optgroup>
														<?php } ?>
														</select>
													</th>
													<th>
														<select onchange="change_term()" id="term" style="width:75px;">
														<option value="/master/finance/tuition?major_id=<?=$major_id?>&term=0">请选择</option>
														<?php if(!empty($major_info_one)):?>

															<?php for($i=1;$i<=$major_info_one['termnum'];$i++):?>
																<option <?=!empty($term)&&$term==$i?'selected="selected"':''?> value="/master/finance/tuition?major_id=<?=$major_id?>&term=<?=$i?>">第<?=$i;?>学期</option>
															<?php endfor;?>
														<?php endif;?>
														</select>
													</th>
													<th>
														<select onchange="change_squad()" id="squad" style="width:75px;">
														<option value="/master/finance/tuition?major_id=<?=$major_id?>&term=<?=$term?>&squad_id=0">请选择</option>
														<?php foreach ($squad_info as $j => $u):?>
															<option <?=!empty($squad_id)&&$squad_id==$u['id']?'selected="selected"':''?> value="/master/finance/tuition?major_id=<?=$major_id?>&term=<?=$term?>&squad_id=<?=$u['id']?>"><?=$u['name'];?></option>
														<?php endforeach;?>
														</select>
													</th>
													<th>
														<select id="field_2">
															<option value="">请选择</option>
															<option value="0">未支付</option>
															<option value="1">成功</option>
															<option value="2">失败</option>
															<option value="3">待确认</option>
														</select>
													</th>
													<th></th>
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
function change_squad(){
	var src=$('#squad').val();
	window.location.href=src;
}
function change_term(){
	var src=$('#term').val();
	window.location.href=src;
}
function change_major(){
	var src=$('#major').val();
	window.location.href=src;	
}

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
            "sAjaxSource":'/master/finance/tuition?major_id=<?=$major_id?>&term=<?=$term?>&squad_id=<?=$squad_id?>',
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
            "aoColumnDefs":[{ "bSortable": false, "aTargets": [ 7 ] }],
            // "aaSorting" : [[7,'desc']],
            "aoColumns" : [
            		{ "mData": "field_0" },
					{ "mData": "field_1" },
					{ "mData": "field_2" },
					{ "mData": "field_3" },
					{ "mData": "field_4" },
					{ "mData": "field_5" },
					{ "mData": "field_6" },
					{ "mData": "field_7" }
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
$('#field_2').on( 'change', function () {
            zjj_datatable_search(2,$("#field_2").val());
        } );
function zjj_datatable_search(column,val){
        $('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
    }
})
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>