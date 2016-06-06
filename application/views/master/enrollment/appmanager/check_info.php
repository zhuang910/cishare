<?php $this->load->view(MASTER.'public/header');?>
<?php $this->load->view(MASTER.'public/main_line');?>
<?php $this->load->view(MASTER.'public/js_table')?>
<?php $this->load->view(MASTER.'public/js_css_chosen')?>
<?php $this->load->view(MASTER.'public/js_css_notify')?>
<?php $this->load->view(MASTER.'public/oa_jquery')?>

<div class="row-fluid">
	<div class="span12">
		<div class="box  box-color box-bordered">
			<div class="box-title">
				<h3>
					<i class="icon-table"></i>
					资料审核
				</h3>
			</div>
			<div class="box-content nopadding">
			    <ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;margin-bottom: 0px;">
					<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
                    <a href="javascript:pub_alert_confirm(this,'','/epmaster/appmanager/check_apply_flow?id=<?=$appid?>&label_id=2');"><h5>打回</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='4'):?> class="active"<?php endif;?>>
                    <a href="javascript:pub_alert_confirm(this,'','/epmaster/appmanager/check_apply_flow?id=<?=$appid?>&label_id=4');"><h5>拒绝</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='5'):?> class="active"<?php endif;?>>
                    <a href="javascript:pub_alert_confirm(this,'','/epmaster/appmanager/check_apply_flow?id=<?=$appid?>&label_id=5');"><h5>调剂</h5></a>
                    </li>
                    <li <?php if(!empty($label_id) && $label_id =='6'):?> class="active"<?php endif;?>>
                    <a href="javascript:pub_alert_confirm(this,'','/epmaster/appmanager/check_apply_flow?id=<?=$appid?>&label_id=6');"><h5>预录取</h5></a>
                    </li>
                    <li <?php if(!empty($label_id) && $label_id =='7'):?> class="active"<?php endif;?>>
                    <a href="javascript:pub_alert_confirm(this,'','/epmaster/appmanager/check_apply_flow?id=<?=$appid?>&label_id=7');"><h5>录取</h5></a>
                    </li>
                   
				</ul>
				<table style="clear: both" class="table table-bordered table-striped table-force-topborder" id="user">
				<?php if(!empty($arr3)){ 
					foreach($arr3 as $k => $v){
				?>
				<thead>
					<tr>
						<th><?=$info1[$k]?></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody> 
				<?php foreach ($v as $key => $val){?>
					<tr>         
						<td width="20%"><?=$info2[$val]['title']?></td>
						<td width="30%"><a data-original-title="Enter username" data-pk="1" data-type="text" id="username">
						<?php if(in_array($info2[$val]['type'], array(3,4,6))){?>
						     <?php if ($info2[$val]['name']=='nationality') {?>
						         <?=$country[$arr2[$val]]?>
						     <?php }else{?>
						         <?=$info3[$val][$arr2[$val]]?>
						     <?php }?>
						<?php }else{?>
						<?=$arr2[$val]?>
						<?php }?>
						</a></td>
					</tr>
			
				<?php }?>
				</tbody>
				<?php }}?>
				</table>
			</div>
		</div>
	</div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<?php $this->load->view(MASTER.'public/footer');?>