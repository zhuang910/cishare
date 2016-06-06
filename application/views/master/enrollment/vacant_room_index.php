<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">住宿管理</a>
	</li>
	
	<li class="active">空房统计</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script src="<?=RES?>master/js/jqGrid/jquery.jqGrid.min.js"></script>
<script src="<?=RES?>master/js/jqGrid/i18n/grid.locale-cn.js"></script>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		住宿管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<div class="col-sm">
				<div class="widget-box transparent">
					<!--tabbable-->
					<div class="tabbable">
								
						<!--tab-content no-border padding-24-->
						<div class="tab-content no-border padding-24">
							<!--1-->
							<div id="faq-tab-1" >
								<div class="widget-box transparent">
									<div class="widget-box">
										<div class="widget-header">
											<h4 class="widget-title">按条件筛选</h4>
										</div>
										<div class="widget-body">
											<div class="widget-main">
												<form class="form-inline" id="condition" method="post">
													<label class="control-label" for="platform">校区:</label>
													<select  id="campusid" name="campusid" aria-required="true" aria-invalid="false" onchange="campus()">
														<option value="0">—请选择—</option>
														<?php foreach($campus_info as $k=>$v):?>
															<option value="<?=$v['id']?>"><?=$v['name']?></option>
														<?php endforeach;?>
													</select>
													<label class="control-label" for="platform">宿舍楼:</label>
													<select onchange="c()" id="bulidingid" name="bulidingid" aria-required="true" aria-invalid="false">
														<option value="0">—请选择—</option>
														
													</select>
													<a class="btn btn-primary btn-sm" type="button" onclick="sure()">
														确认条件
													</a>
													<a style="visibility:hidden;" class="btn btn-primary btn-sm" type="button" id="copy_btn">
														复制
													</a>
												</form>
											</div>
										</div>
									</div>
								</div>
							
								<div>
									<div class="col-sm-12">
										<div id="tables" class="widget-box transparent collapsed">
											<div class="widget-body">
												<div class="widget-main" dayin="no">
													<table class="table table-bordered table-striped" id="table">
														<thead class="thin-border-bottom">
															<tr>
																<th id="title" colspan="6">123</th>
															</tr>
														</thead>
														<thead class="thin-border-bottom">
															<tr>
																<th>层数</th>
																<th>房间数</th>
																<th>床位数</th>
																<th>已预订</th>
																<th>已入住</th>
																<th>剩余床位数</th>
															</tr>
														</thead>

														<tbody id="insert_info">
																
														</tbody>
													</table>
												</div><!-- /.widget-main -->
											</div><!-- /.widget-body -->
										</div><!-- /.widget-box -->
									</div><!-- /.col -->
								</div>
							</div>
							<!--1-->
						
						</div>
						<!--tab-content no-border padding-24-->
					</div>
				<!--tabbable-->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->

<script src="<?=RES?>master/js/fuelux/fuelux.wizard.min.js"></script>

<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>master/js/ace-elements.min.js"></script>
<script src="<?=RES?>master/js/ace.min.js"></script>
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>	
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<script src="<?=RES?>master/js/ZeroClipboard.js"></script>	

<!-- delete -->
<script type="text/javascript">

function c(){

	$('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
	 $('#add').remove();
	  $('#copy_btn').css({
				visibility: 'hidden'
			});
}
function campus(){

	var cid=$('#campusid').val();
		$.ajax({
			url: '/master/enrollment/vacant_room/get_buliding?cid='+cid,
			type: 'POST',
			dataType: 'json',
			data:{}
		})
		.done(function(r) {
			$("#bulidingid").empty();
			$("#bulidingid").append("<option value='0'>—请选择—</option>"); 
			 $.each(r.data, function(i, k) { 
			 	 var opt = $("<option/>").text(k.name).attr("value",k.id);
			 	  $("#bulidingid").append(opt); 
			  });
			 $('#tables').attr({
				class: 'widget-box transparent collapsed'
			});
			 $('#copy_btn').css({
				visibility: 'hidden'
			});
		})
		.fail(function() {
 
			
		})

}

function sure(){
	var data=$('#condition').serialize();
	$.ajax({
		url: '/master/enrollment/vacant_room/adjust_sure',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			$('#title').empty();
			$('#insert_info').empty();
			$('#title').text(r.data.c_name+'--'+r.data.b_name);
			var str='';
			$.each(r.data.room_info, function(k, v) {
				 str+='<tr><td>第'+k+'层</td><td>'+v.room_num+'</td><td>'+v.bed_num+'</td><td>'+v.in_user_num+'</td><td>'+v.ensure_user_num+'</td><td>'+v.vacant_num+'</td></tr>';
			});
			$('#insert_info').append(str);
			$('#tables').attr({
				class: 'widget-box transparent'
			});
			$('#copy_btn').css({
				visibility: 'visible'
			});
		}else if(r.state==0){
			 pub_alert_error(r.info);
		}
				

	})
	.fail(function() {
		
	})

}
// function copy(_sTxt){
// 	try{
// 	if(window.clipboardData) {
// 	    window.clipboardData.setData("Text", _sTxt);

// 	} else if(window.netscape) {
// 	    netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
// 	    var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
// 	    if(!clip) return;
// 	    var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
// 	    if(!trans) return;
// 	    trans.addDataFlavor('text/unicode');
// 	    var str = new Object();
// 	    var len = new Object();
// 	    var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
// 	    var copytext = _sTxt;
// 	    str.data = copytext;
// 	    trans.setTransferData("text/unicode", str, copytext.length*2);
// 	    var clipid = Components.interfaces.nsIClipboard;
// 	    if (!clip) return false;
// 	    clip.setData(trans, null, clipid.kGlobalClipboard);
// 	}
// 	}catch(e){}
	
// }
</script>
<script language="JavaScript">
var clip = null;  
function _(id) { return document.getElementById(id); }  
  
function init(id) {  
   
    clip = new ZeroClipboard.Client();
	ZeroClipboard.setMoviePath("/resource/master/js/ZeroClipboard.swf");
    clip.setHandCursor(true);
	clip.glue( 'copy_btn' );
	
	clip.addEventListener('mouseDown', function (client) {  
        try{
			var table = $("#table").html();
			clip.setText('<table>'+table+'</table>');
		}catch(e){
			pub_alert_error();
		}
      });  
	clip.addEventListener('complete', function(){ pub_alert_success('复制成功，请将内容粘贴到excel');} );
}

init();
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>