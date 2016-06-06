<?php 
if($flag == 1){

?>
<tr>
			<td><?=!empty($majorname->name)?$majorname->name:''?></td>
			<td>
			<?=date('Y-m-d H:i:s',$s)?> -- 
			<?=date('Y-m-d H:i:s',$e)?> 
			</td>
			<td><?=$c?></td>
			</tr>
<?php }?>


<?php if($flag == 2){?>

<?php if(!empty($count)){
	
		foreach($count as $k => $v){
?>
<tr>
			<td><?=$k?>--<?=!empty($majorname[$k])?$majorname[$k]:''?></td>
			<td>
			<?=date('Y-m-d H:i:s',$s)?> -- 
			<?=date('Y-m-d H:i:s',$e)?> 
			</td>
			<td><?php 
			
				if(!empty($v)){
					echo count($v);
				}else{
					echo 0;
				}
			?></td>
			</tr>

<?php }} ?>

<?php }?>
		
<tr>
<td>总计</td>
<td colspan="2" align="right"><?=$countall?></td>

</tr>