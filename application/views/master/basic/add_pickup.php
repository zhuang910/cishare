<div style="margin-bottom:10px;">
	<a title="删除" class="red" onclick="del(this)" href="javascript:;" style="display:inline-block; float:left;margin:5px 10px 0 0;"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
	<select name="cityid[]" style="float:left; margin-right:10px;">
	<option value="">--城市--</option>
	<?php if(!empty($city)){
			foreach($city as $k => $v){
	?>
	<option value='<?=$k?>'><?=$v?></option>
	
	<?php }}?>
	</select>

	<input style="width:100px;float:left;margin-right:10px;" type="text" data-date-format="yyyy-mm-dd" id="stime" class="form-control date-picker" name="stime[]" value="" placeholder="开始时间" ><span class="input-group-addon"  style="width:40px; display:inline-block; float:left; margin:3px 10px 0 0;" >
				<i class="fa fa-calendar bigger-110"></i>
			</span>
	

	<input  style="width:100px;float:left; margin-right:10px;" type="text" data-date-format="yyyy-mm-dd" id="etime" class="form-control date-picker" name="etime[]" value="" placeholder="结束时间" ><span class="input-group-addon"  style="width:40px;display:inline-block; float:left; margin:3px 10px 0 0;" >
				<i class="fa fa-calendar bigger-110"></i>
			</span>
	
	<input class="span3" value="" type="text" id="carfees" name="carfees[]" placeholder="指定金额" style="float:left:margin-right:5px;">(RMB)&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
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