
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
										<div style="margin:0 auto;">
											<a class="btn btn-sm btn-success" type="button" onclick="up()">
												<i class="ace-icon fa fa-angle-double-left"></i>
											</a>
												<input onchange="changedate()" id="starttime" style="width:100px;"  class="date-picker valid" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d',$stime)?>">-
												<input id="overtime" style="width:100px;" type="text" style="border:0px;" value="<?php echo date('Y-m-d',$etime)?>">
											<a class="btn btn-sm btn-success" type="button" onclick="next()">
												<i class="ace-icon fa fa-angle-double-right"></i>
											</a>
										</div>
										<div>
				<table class="table table-striped table-bordered">
				<?php if(!empty($hour)):?>
					<thead>
						<tr>
							<th>节</th>
						
							<th>星期一<br /><span id="mo"><?php echo date('m-d',$stime)?></span></th>
							<th>星期二<br /><span id="tu"><?php echo date('m-d',$stime+3600*24)?></span></th>
							<th>星期三<br /><span id="we"><?php echo date('m-d',$stime+3600*24*2)?></span></th>
							<th>星期四<br /><span id="th"><?php echo date('m-d',$stime+3600*24*3)?></span></th>
							<th>星期五<br /><span id="fr"><?php echo date('m-d',$stime+3600*24*4)?></span></th>
							<th>星期六<br /><span id="sa"><?php echo date('m-d',$stime+3600*24*5)?></span></th>
							<th>星期日<br /><span id="su"><?php echo date('m-d',$stime+3600*24*6)?></span></th>
						
							
						</tr>
					</thead>
					<tbody>
					
						<?php foreach($hour['hour'] as $k=>$v):?>

							<tr> 
							<td><?php 
								echo $v*2-1,",",$v*2,"节课";
								?></td>
							<?php for($i=1;$i<=7;$i++){
								$num=0;
								$type=0;
								foreach ($out as $kk => $vv) {
									if($i==$vv['week'] && $v==$vv['knob']){
										$num=1;
										$teacherid=$vv['teacherid'];
										$courseid=$vv['courseid'];
										$nowterm=$vv['nowterm'];
										$week=$vv['week'];
										$knob=$vv['knob'];
										$cname=$vv['cname'];
									}
								}
								foreach ($ckinfo as $kkk => $vvv) {
									if($i==$vvv['week'] && $v==$vvv['knob']){
										$type=$vvv['type'];
									}
								}
								//组合select框
								$select="<select id='type".$i.'and'.$v."' onchange='luru(\"$i".'and'."$v\")' name='type'>";
								for($ii=0;$ii<5;$ii++){
									if($ii==0){
										$str='正点';
									}elseif($ii==1){
										$str="缺勤";
									}elseif($ii==2){
										$str="早退";
									}elseif($ii==3){
										$str="迟到";
									}elseif($ii==4){
										$str="请假";
									}
									$selected="";
									if($type==$ii){
										$selected="selected";
									}
									$select.="<option $selected value='$ii'>$str</option>";
								}
								$select.="</select>";
								if($num!=0){
									
									
									echo "<td>".$cname."<form id='$i".'and'."$v'>$select<input type='hidden' name='studentid' value='$studentid'><input type='hidden' name='majorid' value='$majorid'><input type='hidden' name='knob' value='$knob'><input type='hidden' name='week' value='$week'><input type='hidden' name='nowterm' value='$nowterm'><input type='hidden' name='squadid' value='$squadid'><input type='hidden' name='courseid' value='$courseid'><input type='hidden' name='teacherid' value='$teacherid'></form></td>";
								}else{
									echo "<td>----</td>";
								}
								
								}?>
							
							</tr>
						<?php endforeach;?>
					<?php else:?>
						<th>还没有设置时间...</th>
					<?php endif;?>
					</tbody>
				</table>				

										</div>
										
								</div>
							</div>
								<!--timestart-->
			<div class="datepicker datepicker-dropdown dropdown-menu datepicker-orient-left datepicker-orient-bottom" style="display: none; top: 1966.47px; left: 222.917px;">
				<div class="datepicker-days" style="display: block;">
				<table class=" table-condensed">
				<thead>
				<tr>
				<th class="prev" style="visibility: visible;">«</th>
				<th class="datepicker-switch" colspan="5">August 2014</th>
				<th class="next" style="visibility: visible;">»</th>
				</tr>
				<tr>
				<th class="dow">Su</th>
				<th class="dow">Mo</th>
				<th class="dow">Tu</th>
				<th class="dow">We</th>
				<th class="dow">Th</th>
				<th class="dow">Fr</th>
				<th class="dow">Sa</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td class="old day">27</td>
				<td class="old day">28</td>
				<td class="old day">29</td>
				<td class="old day">30</td>
				<td class="old day">31</td>
				<td class="day">1</td>
				<td class="day">2</td>
				</tr>
				<tr>
				<td class="day">3</td>
				<td class="today day">4</td>
				<td class="day">5</td>
				<td class="day">6</td>
				<td class="day">7</td>
				<td class="day">8</td>
				<td class="day">9</td>
				</tr>
				<tr>
				<td class="day">10</td>
				<td class="day">11</td>
				<td class="day">12</td>
				<td class="day">13</td>
				<td class="day">14</td>
				<td class="day">15</td>
				<td class="day">16</td>
				</tr>
				<tr>
				<td class="day">17</td>
				<td class="day">18</td>
				<td class="day">19</td>
				<td class="day">20</td>
				<td class="day">21</td>
				<td class="day">22</td>
				<td class="day">23</td>
				</tr>
				<tr>
				<td class="day">24</td>
				<td class="day">25</td>
				<td class="day">26</td>
				<td class="day">27</td>
				<td class="day">28</td>
				<td class="active day">29</td>
				<td class="day">30</td>
				</tr>
				<tr>
				<td class="day">31</td>
				<td class="new day">1</td>
				<td class="new day">2</td>
				<td class="new day">3</td>
				<td class="new day">4</td>
				<td class="new day">5</td>
				<td class="new day">6</td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
				<th class="today" colspan="7" style="display: none;">Today</th>
				</tr>
				<tr>
				<th class="clear" colspan="7" style="display: none;">Clear</th>
				</tr>
				</tfoot>
				</table>
				</div>
			</div>	
			<!--timeend-->	
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>	

<script type="text/javascript">
   $( "#starttime" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: false,
			
				});

function next(){
	var starttime=$('#starttime').val();
	var overtime=$('#overtime').val();
	
	$.ajax({
		url: "<?=$zjjp?>checking/checking/nexttime?starttime="+starttime+"&classtime="+<?=$classtime['classtime']?>+'&studentid='+<?=$studentid?>+'&overtime='+overtime,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
	
		if(r.state=1){
			$('#pub_edit_bootbox').find('select').each(function(){
				$(this).find('option:selected').removeAttr('selected');
			})
			$('#starttime').val(r.data.start);
			$('#overtime').val(r.data.over);
			$.each(r.data.ckdata, function(k, v) {
					var id='type'+v.week+'and'+v.knob
					$("#"+id).val(v.type);
			});
				$('#mo').text(r.data.mo);
				$('#tu').text(r.data.tu);
				$('#we').text(r.data.we);
				$('#th').text(r.data.th);
				$('#fr').text(r.data.fr);
				$('#sa').text(r.data.sa);
				$('#su').text(r.data.su);
		}

	})
}
function up(){
	var starttime=$('#starttime').val();
	var overtime=$('#overtime').val();
	
	$.ajax({
		url: "<?=$zjjp?>checking/checking/uptime?starttime="+starttime+"&classtime="+<?=$classtime['classtime']?>+'&studentid='+<?=$studentid?>+'&overtime='+overtime,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
	
		if(r.state==1){
			$('#pub_edit_bootbox').find('select').each(function(){
				$(this).find('option:selected').removeAttr('selected');
			})
			$('#starttime').val(r.data.start);
			$('#overtime').val(r.data.over);
			$.each(r.data.ckdata, function(k, v) {
					var id='type'+v.week+'and'+v.knob
					$("#"+id).val(v.type);
			});
			$('#mo').text(r.data.mo);
			$('#tu').text(r.data.tu);
			$('#we').text(r.data.we);
			$('#th').text(r.data.th);
			$('#fr').text(r.data.fr);
			$('#sa').text(r.data.sa);
			$('#su').text(r.data.su);
		}else if(r.state==0){
			
			pub_alert_error(r.info);

		}

	})
}
function changedate(){
	var starttime=$('#starttime').val();
	var overtime=$('#overtime').val();
	$.ajax({
		url: "<?=$zjjp?>checking/checking/changedate?starttime="+starttime+"&classtime="+<?=$classtime['classtime']?>+'&studentid='+<?=$studentid?>+'&overtime='+overtime,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
	
		if(r.state==1){
			$('#pub_edit_bootbox').find('select').each(function(){
				$(this).find('option:selected').removeAttr('selected');
			})
			$('#starttime').val(r.data.start);
			$('#overtime').val(r.data.over);
			$.each(r.data.ckdata, function(k, v) {
					var id='type'+v.week+'and'+v.knob
					$("#"+id).val(v.type);
			});
			$('#mo').text(r.data.mo);
			$('#tu').text(r.data.tu);
			$('#we').text(r.data.we);
			$('#th').text(r.data.th);
			$('#fr').text(r.data.fr);
			$('#sa').text(r.data.sa);
			$('#su').text(r.data.su);
		}else if(r.state==0){
			
			pub_alert_error(r.info);
			
			$('#pub_edit_bootbox').find('select').each(function(){
				$(this).find('option:selected').removeAttr('selected');
			})
			$('#starttime').val(r.data.start);
			$('#overtime').val(r.data.over);
			$.each(r.data.ckdata, function(k, v) {
					var id='type'+v.week+'and'+v.knob
					$("#"+id).val(v.type);
			});
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