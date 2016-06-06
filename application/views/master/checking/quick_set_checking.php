
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<?
$studentid=intval($_GET ['studentid']);
$squadid=intval($_GET ['squadid']);
$majorid=intval($_GET ['majorid']);
$k=date('w',time());
if($k==0){
	$k=6;
}else{
	$k=$k-1;
}
$stime=time()-$k*3600*24;
$etime=$stime+6*24*3600;
?>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					<button onclick="shuaxin(<?=$majorid?>,<?=$studentid?>)" type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					<?=$sname['name']?>--设置考勤
				</div>
			</div>
			<div>
				<form class="form-horizontal" id="checking" method="post">
					<input type="hidden" value="<?=$majorid?>" name="majorid">
					<input type="hidden" value="<?=$squadid?>" name="squadid">
					<input type="hidden" value="<?=$studentid?>" name="studentid">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">日期:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input onclick="biao()" name="date" onchange="quick_changedate()" id="starttime" style="width:100px;"  class="date-picker valid" type="text" data-date-format="yyyy-mm-dd" value="">
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">课程:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<select biaoshi='no' id="nowdaycourse"  name="course" aria-required="true" aria-invalid="false">
										<option value="0">—请选择—</option>
									</select>
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">类别:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<select id="key"  name="type" aria-required="true" aria-invalid="false">
										<option value="0">正点</option>
										<option value="1">缺勤</option>
										<option value="2">早退</option>
										<option value="3">迟到</option>
										<option value="4">请假</option>
									</select>
								</div>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>
							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<textarea name="remark"></textarea>
								</div>
							</div>
						</div>
						<div class="space-2"></div>
					<div>
					<a onclick="insert_checking()" class="btn btn-info">
								提交
					</a>
					</div>
			</form>
		</div>
	</div>
</div>
			
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>	
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('#starttime').datepicker({
		autoclose: true,
		todayHighlight: true,
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>	
<script type="text/javascript">
function insert_checking(){
	var data=$('#checking').serialize();
	
		$.ajax({
			url: "<?=$zjjp?>checking/checking/insert_checking",
			type: 'POST',
			dataType: 'json',
			data: data,
		})
		.done(function(r) {
			if(r.state==1){
				pub_alert_success(r.info);
			}else{
				pub_alert_error(r.info);
			}

		})
		.fail(function() {
			console.log("error");
		})

}
function biao(){
	$('#nowdaycourse').attr('biaoshi','no');
}
function quick_changedate(){
	var date=$('#starttime').val();
	$.ajax({
		url: "<?=$zjjp?>checking/checking/get_checkeing_course?date="+date+"&classtime="+<?=$classtime['classtime']?>+'&studentid='+<?=$studentid?>+'&squadid='+<?=$squadid?>+'&majorid='+<?=$majorid?>,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if($('#nowdaycourse').attr('biaoshi')!='yes'){
			if(r.state==1){
				$("#nowdaycourse").find("option").remove();
				var str='';
				str+='<option value="0">—请选择—</option>';
				$.each(r.data,function(k,v){
					str+="<option value="+v.teacherid+'-'+v.courseid+'-'+v.knob+">"+v.knob+","+(v.knob*2-1)+"节课-"+v.name+"</option>";
				})
				$('#nowdaycourse').append(str);
				$('#nowdaycourse').attr('biaoshi','yes');
				}else if(r.state==0){
					
					pub_alert_error(r.info);
				
				}
		}
			

	})
	
}
function luru(id){
	var time=$('#starttime').val();
	var data=$('#'+id).serialize();
	
		$.ajax({
			url: "<?=$zjjp?>checking/checking/insert?time="+time,
			type: 'POST',
			dataType: 'json',
			data: data,
		})
		.done(function() {
			console.log("success");

		})
		.fail(function() {
			console.log("error");
		})


}	
function shuaxin(mid,sid){
	$.ajax({
		url: '<?=$zjjp?>checking/checking/qin?mid='+mid+'&sid='+sid,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			$('#kaoqin'+sid).html(r.data.kaoqin);
			$('#chuqin'+sid).html(r.data.chuqin);
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}

</script>
	<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true
		})
		//show datepicker when clicking on the icon
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});

		});
	
</script>