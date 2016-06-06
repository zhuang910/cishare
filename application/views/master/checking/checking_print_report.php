<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div>
<!-- <button onclick="dayin()" class="btn btn-info" data-last="Finish">
<i class="ace-icon fa fa-check bigger-110"></i>
打印
</button> -->
</div>
<div id="print_bg">
	<table border="1" cellpadding="0" cellspacing="0">
	<thead>
			<tr>
				<th style="font-size:30px;" colspan="15"><?=$majorname?>-第<?=$trem?>学期班级名单  日期:<?=date('Y-m-d',$date['sdate'])?>至<?=date('Y-m-d',$date['edate'])?>第<?=$num?>周</th>

			</tr>
			</thead>
		<thead>
			<tr>
				<th width="30">教室</th>
				<th>老师</th>
				<th>上课时间</th>
				<th>课程名称</th>
				<th>英文名字</th>
				<th>中文名字</th>
				<th>护照号</th>
				<th>国别</th>
				<th>电话</th>
				<th>星期一</th>
				<th>星期二</th>
				<th>星期三</th>
				<th>星期四</th>
				<th>星期五</th>
				<th>备注</th>

			</tr>
			</thead>
			<tbody>
			<?php $i=1?>
			<?php 
				$g=1;
				foreach ($student_all as $k => $v) {
					if(empty($v)){
						$g++;
					}
				}
			?>
			<?php foreach($student_all as $k=>$v):?>
				<?php $j=1?>
				<tr>
				<?php if($i==1):?>
				<td rowspan="<?=$count_all+$g*2?>"><?=$classroom?></td>
				<td rowspan="<?=$count_all+$g*2?>"><?=$teacher?></td>
				
				<?php endif;?>
				<?php if($j==1):?>
					<?php
						$arr=explode('grf', $k);
						$num=count($v)+$i-1;
					?>
						<td <?=count($v)!=0?'rowspan="'.$num.'"':''?> ><?=$arr[0]?></td>
						<td <?=count($v)!=0?'rowspan="'.$num.'"':''?> ><?=$arr[1]?><br /><?=$arr[2]?></td>
				<?php endif;?>
				<?php if(count($v)==0):?>
					<td colspan="11">--</td>
				<?php else:?>
				<?php foreach($v as $kk=>$vv):?>
					<?php if($i>1||$j>1):?>
					<tr>
					<?php endif;?>
						
						
						<td><?=$vv['enname']?></td>
						<td><?=$vv['name']?></td>
						<td><?=$vv['passport']?></td>
						<td><?=$vv['nationality']?></td>
						<td><?=$vv['mobile']?></td>
						<td><?=$vv['week1']?></td>
						<td><?=$vv['week2']?></td>
						<td><?=$vv['week3']?></td>
						<td><?=$vv['week4']?></td>
						<td><?=$vv['week5']?></td>
						<td><?=$vv['remark']?></td>
					</tr>
					<?php $j++?>

				<?php endforeach;?>
				<?php endif;?>
					<?php $i++?>
			<?php endforeach;?>
			</tbody>
	</table>
</div>
<script type="text/javascript">
function dayin(){
	document.body.innerHTML=document.getElementById('print_bg').innerHTML;
	window.print();
}
</script>
</body>
</html>
