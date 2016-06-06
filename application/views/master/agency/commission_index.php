<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">中介管理</a>
	</li>
	<li class="active">佣金结算</li>
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
		佣金结算
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
						
			<div class="table-header">
				申请列表

			</div>
			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
		<form id="checked" method="post" onSubmit="return derive()" action="<?=$zjjp?>student/student/derive_part">
			<div>   
				<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
					<li <?php if($label_id ==0):?> class="active"<?php endif;?>>
					<a href="/master/agency/commission?agency_id=<?=$agency_id?>&label_id=0"><h5>未结</h5></a>
					</li>
					<li <?php if(!empty($label_id) && $label_id =='1'):?> class="active"<?php endif;?>>
					<a href="/master/agency/commission?agency_id=<?=$agency_id?>&label_id=1"><h5>已结</h5></a>
					</li>
					<?php if($label_id==0):?>
						<li style="float:right;">
						<button onclick="email()" class="btn btn-info" data-last="Finish">
							<span class="bigger-110">批量确认结算</span>
						</button>
						</li>
					<?php endif;?>
				</ul>                                  
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
					<thead>
						<tr>
							<th>
								<input id="all" checke="true" type="checkbox" onclick="alll()">
							</th>
							<th>
								<label class="position-relative">
								<!-- <input type="checkbox" class="ace" />
								<span class="lbl"></span> -->
								ID
								</label>
							</th>
							<th>申请编号</th>
							<th>中文名</th>
							<th>英文名</th>
							<th width="100">国籍</th>
							<th>护照号码</th>
							<th>学生类别</th>
							<th>授课语言</th>
							<th>学费</th>
							<th>佣金</th>
							<th>结算状态</th>
							<th width="100">操作</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		</form>
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
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<script type="text/javascript">
function email(){
	$('#checked').attr({
		action: '/master/agency/commission/querenjiaofei',
	});
}
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
	 	pub_alert_error('请选择学生');
	 }
	 if(is_subimt===true){
	 	var data=$('#checked').serialize();
	 	$.ajax({
	 		url: '/master/agency/commission/querenjiaofei',
	 		type: 'POST',
	 		dataType: 'json',
	 		data: data,
	 	})
	 	.done(function(r) {
	 		if(r.state==1){
	 			pub_alert_success('更改状态成功');
                window.location.reload();
            }
	 	})
	 	.fail(function() {
	 		console.log("error");
	 	})
	 }
	 return false;
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
			opt.sAjaxSource = "/master/agency/commission?agency_id=<?=$agency_id?>&label_id=<?=$label_id?>";
			opt.fnDrawCallback=function(){
		 		$('a[upload-config="true"]').editable({
			        url: function(params) {
						var d = new $.Deferred;
						$.ajax({
							type:'POST',
							url:'/master/agency/commission/edit_commission',
							data:$.param(params),
							dataType:'json',
							success: function(r) {
								if(r.state == 1){
									pub_alert_success(r.info);
									d.resolve();
								}else{
									return d.reject(r.info);
								}
							}
						});
						return d.promise();
					},
			    }); 
		 	}
		}
		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "checkbox" },
								{ "mData": "id" },
								{ "mData": "number" },
								{ "mData": "chname" },
								{ "mData": "enname" },
								{ "mData": "nationality" },
								{ "mData": "passport" },
								{ "mData": "degree" },
								{ "mData": "language" },
								{ "mData": "tuition" },
								{ "mData": "commission" },
								{ "mData": "tuition_state" },
								{"mData":"operation"}
							
							];
			opt.aaSorting = [[1,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [0,11,12] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}
function update_record(aid){
	pub_alert_confirm('/master/agency/commission/insert_record?aid='+aid,'','确认缴费');

}
function del(aid){
	pub_alert_confirm('/master/agency/commission/del?aid='+aid,'','删除缴费记录');
}

function upstate(id,state){
	pub_alert_confirm('/master/basic/course/upstate?id='+id+'&state='+state);
	
}

	$(document).on('click', 'th input:checkbox' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
				});
			
$(function(){
	
});
</script>

<script type="text/javascript">
	
	function edit(){
		var ids = '';
    	$('input:checkbox[name="ids"]:checked').each(function(){
	        ids += $(this).val() + ',';
	    });
        if(ids == ''){
        	alert("请选择数据！");
            return false;
        }

        var edit_fei = $('#edit_fei').val();

       if(edit_fei == ''){

       		alert('请选择操作');
       		return false;
       }

       pub_alert_html('/master/basic/course/get_html?ids='+ids+'&edit_fei='+edit_fei);
/*
       $.ajax({
         		url: '/master/basic/course/get_html?ids='+ids+'&edit_fei='+edit_fei,
         		type: 'GET',
         		dataType: 'json',
         	})
         	.done(function(r) {
         		if (r.state == 1) {
         			var modal = r.data;
         			var modal = $(modal);
					modal.modal("show").on("hidden", function(){
						modal.remove();
					});

			
         		};
         	})

         	*/


	}


	function copycourse(id){
	
	//override dialog's title function to allow for HTML titles
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
		_title: function(title) {
			var $title = this.options.title || '&nbsp;'
			if( ("title_html" in this.options) && this.options.title_html == true )
				title.html($title);
			else title.text($title);
		}
	}));
	var dialog = $( "#dialog-message" ).removeClass('hide').dialog({
		modal: true,
		title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='ace-icon fa fa-check'></i> jQuery UI Dialog</h4></div>",
		title_html: true,
		buttons: [ 
			{
				text: "Cancel",
				"class" : "btn btn-xs",
				click: function() {
					$( this ).dialog( "close" ); 
				} 
			},
			{
				text: "OK",
				"class" : "btn btn-primary btn-xs",
				click: function() {
					$( this ).dialog( "close" ); 
					$.ajax({
							url: '/master/basic/course/copycourse?id='+id,
							type: 'GET',
							dataType: 'json'
						})
						.done(function(r) {
							if(r.state == 1){
								pub_alert_success();
								window.location.reload();
							}else{
								pub_alert_error();
							}

						})
						.fail(function() {
							console.log("error");
						})
				} 
			}
		]
	});

	/**
	dialog.data( "uiDialog" )._title = function(title) {
		title.html( this.options.title );
	};
	**/
}
</script>


<!-- end script -->
<?php $this->load->view('master/public/footer');?>
