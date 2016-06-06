<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>HOME</a>
	</li>

	<li>
		Students
	</li>
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
        Students
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
						
			<div class="table-header">
				List
				<a style="float:right;" class="btn btn-primary btn-sm btn-default btn-sm"  href="/master/agencyport/student_apply/add">
				<span class="glyphicon  glyphicon-plus"></span>
				Add
				</a>
			</div>
			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>   
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
					<thead>
						<tr>
							<th width="80">
								<label class="position-relative">
								<!-- <input type="checkbox" class="ace" />
								<span class="lbl"></span> -->
								ID
								</label>
							</th>
							<th>English Name</th>
							<th width="120">E-mail</th>
							<th width="120">Passport No.</th>
							<th width="200">Major</th>
							<th width="140">Nationality</th>
							<th width="180"></th>
						</tr>
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
<link rel="stylesheet" href="<?=RES?>master/css/ace.onpage-help.css" />
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<script type="text/javascript">
function add_apply(id){
	var newTab=window.open('about:blank');
	$.ajax({
		url: '/master/agencyport/student_apply/add_open_apply?userid='+id,
		type: 'GET',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			 newTab.location.href="/en/student/index";
			
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
if($('#sample-table-2').length > 0){
	$('#sample-table-2').each(function(){
		var opt = {
			"iDisplayLength" : 25,
			"sPaginationType": "full_numbers",
			"oLanguage":{
				"sSearch": "<span>Search:</span> ",
				"sInfo": "<span>_START_</span> - <span>_END_</span> Total <span>_TOTAL_</span>",
				"sLengthMenu": "_MENU_ <span>Per Page</span>",
				"oPaginate": {
					"sFirst" : "First",
					"sLast" : "Last",
			   		"sPrevious": " Previous ",
			   		"sNext":     " Next "
		   		},
				"sInfoEmpty" : "No Record",
				"sInfoFiltered" : "",
				"sZeroRecords" : 'No Result'
			}
		};

		 opt.bAutoWidth=true; 
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "/master/agencyport/student_apply";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "enname" },
								{ "mData": "email" },
								{ "mData": "passport" },
								{ "mData": "major" },
								{ "mData": "acc_state" },
								{"mData":"operation"}
							
							];
			opt.aaSorting = [[1,'desc']];
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
