<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>HOME</a>
	</li>
	<li class="active">Applications</li>
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
		Applications
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
						
			<div class="table-header">
				List

			</div>
			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
                <ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
                    <li <?php if($label_id ==0):?> class="active"<?php endif;?>>
                        <a href="/master/agencyport/do_apply?&label_id=0"><h5>Unprocessed Commissions</h5></a>
                    </li>
                    <li <?php if(!empty($label_id) && $label_id =='1'):?> class="active"<?php endif;?>>
                        <a href="/master/agencyport/do_apply?&label_id=1"><h5>Processed Commissions</h5></a>
                    </li>
                </ul>
                <table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
					<thead>
						<tr>
							<th>
								<label class="position-relative">
								<!-- <input type="checkbox" class="ace" />
								<span class="lbl"></span> -->
								ID
								</label>
							</th>
							<th>Chinese Name</th>
							<th>English Name</th>
							<th>Email</th>
							<th>Passport</th>
							<th>Major</th>
							<th>Payment Status</th>
							<th>Application Status</th>
                            <th>Time</th>
                            <th>Commission</th>
							<th width="100"></th>
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
			opt.sAjaxSource = "/master/agencyport/do_apply?label_id=<?=$label_id?>";
		}

		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
								{ "mData": "id" },
								{ "mData": "chname" },
								{ "mData": "enname" },
								{ "mData": "email" },
								{ "mData": "passport" },
								{ "mData": "name" },
								{ "mData": "paystate" },
								{ "mData": "state" },
								{ "mData": "issubmittime" },
								{ "mData": "commission" },
								{"mData":"operation"}
							
							];
			opt.aaSorting = [[1,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4,5 ] }];
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
