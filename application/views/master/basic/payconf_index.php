<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">基本设置</a>
	</li>
	<li class="active">收费设置</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>


<script src="<?=RES?>master/js/jquery.validate.min.js"></script>



<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		收费设置
	</h1>
</div><!-- /.page-header -->


	<div class="row">
		<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<!---->
		<form id="apply" method="post" action="<?=$zjjp?>payconf/applysave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 设置申请费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" <?php echo $apply['apply']=='yes' ? "checked='checked'":" "?> type="radio"  value="yes" name="apply" aria-required="true" aria-invalid="false">
						<span class="lbl"> 收取</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid" <?php echo $apply['apply']=='no' ? "checked='checked'":" "?> type="radio" value="no" name="apply" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不收取</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input class="span3" type="text" name="applymoney" value="<?php echo $apply['apply']!='no'? $apply['applymoney']:""?>" id="applymoney"  class="input required" placeholder="指定金额">(RMB)&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="hidden" name="applyway" value="applyrmb">
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form method="post" id="pledge" action="<?=$zjjp?>payconf/pledgesave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 设置押金/预交学费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" <?php echo $pledge['pledge']=='yes' ? "checked='checked'":" "?> type="radio" checked="checked" value="yes" name="pledge" aria-required="true" aria-invalid="false">
						<span class="lbl"> 收取</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid" <?php echo $pledge['pledge']=='no' ? "checked='checked'":" "?> type="radio" value="no" name="pledge" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不收取</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input class="span3" value="<?php echo $pledge['pledge']!='no'? $pledge['pledgemoney']:""?>" type="text" id="pledgemoney" name="pledgemoney" placeholder="指定金额">(RMB)&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="hidden" name="pledgeway" value="pledgermb">
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
					
		<!---->
		<form id="stay" method="post" action="<?=$zjjp?>payconf/staysave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 设置住宿费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?php echo $stay['stay']=='yes' ? "checked='checked'":" "?> value="yes" name="stay" id='stayyes' aria-required="true" aria-invalid="false">
						<span class="lbl"> 收取</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?php echo $stay['stay']=='no' ? "checked='checked'":" "?> value="no" name="stay" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不收取</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="hidden" name="stayway" value="stayrmb">
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->	
		<!---->
		<form method="post" id="acc_pledge" action="<?=$zjjp?>payconf/acc_pledgesave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 设置住宿押金</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?php echo !empty($acc_pledge['acc_pledge'])&&$acc_pledge['acc_pledge']=='yes' ? "checked='checked'":" "?>  value="yes" name="acc_pledge" aria-required="true" aria-invalid="false">
						<span class="lbl"> 收取</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?php echo !empty($acc_pledge['acc_pledge'])&&$acc_pledge['acc_pledge']=='no' ? "checked='checked'":" "?> value="no" name="acc_pledge" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不收取</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input class="span3" value="<?php echo !empty($acc_pledge['acc_pledge'])&&$acc_pledge['acc_pledge']!='no'? $acc_pledge['acc_pledgemoney']:""?>" type="text" id="acc_pledgemoney" name="acc_pledgemoney" placeholder="指定金额">(RMB)&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="hidden" name="acc_pledgeway" value="acc_pledgermb">
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->			

		<!---->
		<form method="post" id="pickup" action="<?=$zjjp?>payconf/pickupsave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 设置接机</h4>
					</label>
					<br />
					<a title="添加" href="javascript:add_value();"><i class="ace-icon glyphicon glyphicon-plus"></i></a>
					<div id="zyj_add">
						<?php if(!empty($pickup)){
							foreach($pickup as $key => $val){
						?>
						<div  style="margin-bottom:10px;">
						<a title="删除" class="red" onclick="del(this)" href="javascript:;" style="display:inline-block; float:left;margin:5px 10px 0 0;"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
							<select name="cityid[]" style="float:left; margin-right:10px;">
							<option value="">--城市--</option>
							<?php if(!empty($city)){
									foreach($city as $k => $v){
							?>
							<option value='<?=$k?>' <?=!empty($val['cityid']) && $val['cityid'] == $k ?'selected':''?>><?=$v?></option>
							
							<?php }}?>
							</select>
							
							
							<input  style="width:100px;float:left;margin-right:10px;" type="text" data-date-format="yyyy-mm-dd" id="stime" class="form-control date-picker" name="stime[]" value="<?=!empty($val['stime'])?date('Y-m-d',$val['stime']):''?>" placeholder="开始时间" ><span class="input-group-addon"  style="width:40px; display:inline-block; float:left; margin:3px 10px 0 0;">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
							
							<input  style="width:100px;float:left; margin-right:10px;" type="text" data-date-format="yyyy-mm-dd" id="etime" class="form-control date-picker" name="etime[]" value="<?=!empty($val['etime'])?date('Y-m-d',$val['etime']):''?>" placeholder="结束时间" ><span class="input-group-addon"  style="width:40px;display:inline-block; float:left; margin:3px 10px 0 0;" >
										<i class="fa fa-calendar bigger-110"></i>
									</span>
							
							<input class="span3" style="float:left:margin-right:5px;" value="<?=!empty($val['carfees'])?$val['carfees']:''?>" type="text" id="carfees" name="carfees[]" placeholder="指定金额">(RMB)&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
						<?php }}else{?>
						<div style="margin-bottom:10px;">
						
							<select name="cityid[]" style="float:left; margin-right:10px;">
							<option value="">--城市--</option>
							<?php if(!empty($city)){
									foreach($city as $k => $v){
							?>
							<option value='<?=$k?>'><?=$v?></option>
							
							<?php }}?>
							</select>
							
							<input  style="width:100px;float:left;margin-right:10px;" type="text" data-date-format="yyyy-mm-dd" id="stime" class="form-control date-picker" name="stime[]" value="" placeholder="开始时间" ><span class="input-group-addon"  style="width:40px;display:inline-block; float:left; margin:3px 10px 0 0;">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
							
							<input style="width:100px;float:left; margin-right:10px;" type="text" data-date-format="yyyy-mm-dd" id="etime" class="form-control date-picker" name="etime[]" value="" placeholder="结束时间" ><span class="input-group-addon" style="width:40px;display:inline-block; float:left; margin:3px 10px 0 0;"  >
										<i class="fa fa-calendar bigger-110"></i>
									</span>
							
							<input class="span3" style="float:left:margin-right:5px;" value="" type="text" id="carfees" name="carfees[]" placeholder="指定金额">(RMB)&nbsp;&nbsp;&nbsp;&nbsp;
						</label>
						</div>
						
						<?php }?>
					</div>
					<br />
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		
		<!---->
		<form method="post" id="bed" action="<?=$zjjp?>payconf/bedsave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 床上用品费用</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?php echo $bed['bed']=='yes' ? "checked='checked'":" "?>  value="yes" name="bed" aria-required="true" aria-invalid="false">
						<span class="lbl"> 收取</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?php echo $bed['bed']=='no' ? "checked='checked'":" "?> value="no" name="bed" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不收取</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input class="span3" value="<?php echo $bed['bed']!='no'? $bed['bedmoney']:""?>" type="text" id="bedmoney" name="bedmoney" placeholder="指定金额">(RMB)&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="hidden" name="bedway" value="bedrmb">
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form method="post" id="insurance" action="<?=$zjjp?>payconf/insurancesave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 保险费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?php echo $insurance['insurance']=='yes' ? "checked='checked'":" "?>  value="yes" name="insurance" aria-required="true" aria-invalid="false">
						<span class="lbl"> 收取</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?php echo $insurance['insurance']=='no' ? "checked='checked'":" "?> value="no" name="insurance" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不收取</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					
					新生:<input style="width:100px" class="span3" value="<?php echo $insurance['insurance']!='no'? $insurance['insurancemoney_one']:""?>" type="text" class="insurancemoney" name="insurancemoney_one" placeholder="指定金额1">(RMB)&nbsp;&nbsp;&nbsp;
					老生:<input style="width:100px" class="span3" value="<?php echo $insurance['insurance']!='no'? $insurance['insurancemoney_two']:""?>" type="text" class="insurancemoney" name="insurancemoney_two" placeholder="指定金额2">(RMB)&nbsp;&nbsp;&nbsp;
					<input type="hidden" name="insuranceway" value="insurancermb">
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form method="post" id="electric" action="<?=$zjjp?>payconf/electricsave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 电费押金</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?php echo $electric['electric']=='yes' ? "checked='checked'":" "?>  value="yes" name="electric" aria-required="true" aria-invalid="false">
						<span class="lbl"> 收取</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?php echo $electric['electric']=='no' ? "checked='checked'":" "?> value="no" name="electric" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不收取</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input class="span3" value="<?php echo $electric['electric']!='no'? $electric['electricmoney']:""?>" type="text" id="electricmoney" name="electricmoney" placeholder="指定金额">(RMB)&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="hidden" name="electricway" value="electricrmb">
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form method="post" id="books" action="<?=$zjjp?>payconf/bookssave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 书费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?php echo $books['books']=='yes' ? "checked='checked'":" "?>  value="yes" name="books" aria-required="true" aria-invalid="false">
						<span class="lbl"> 收取</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?php echo $books['books']=='no' ? "checked='checked'":" "?> value="no" name="books" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不收取</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form id="scholarship" method="post" action="<?=$zjjp?>payconf/scholarshipsave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 设置奖学金</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?php echo $scholarship['scholarship']=='yes' ? "checked='checked'":" "?> value="yes" name="scholarship" aria-required="true" aria-invalid="false">
						<span class="lbl"> 开放</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?php echo $scholarship['scholarship']=='no' ? "checked='checked'":" "?> value="no" name="scholarship" aria-required="true" aria-invalid="false">
						<span class="lbl"> 不开放</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form id="replacement" method="post" action="<?=$zjjp?>payconf/replacementsave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 换证考计入学费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?=!empty($tuition['replacement'])&&$tuition['replacement']=='yes' ? "checked='checked'":" "?> value="yes" name="replacement" aria-required="true" aria-invalid="false">
						<span class="lbl"> 开</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?=!empty($tuition['replacement'])&&$tuition['replacement']=='no' ? "checked='checked'":" "?> value="no" name="replacement" aria-required="true" aria-invalid="false">
						<span class="lbl"> 关</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form id="repair_fee" method="post" action="<?=$zjjp?>payconf/repair_feesave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 重修费计入学费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?=!empty($tuition['repair_fee'])&&$tuition['repair_fee']=='yes' ? "checked='checked'":" "?> value="yes" name="repair_fee" aria-required="true" aria-invalid="false">
						<span class="lbl"> 开</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?=!empty($tuition['repair_fee'])&&$tuition['repair_fee']=='no' ? "checked='checked'":" "?> value="no" name="repair_fee" aria-required="true" aria-invalid="false">
						<span class="lbl"> 关</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
		<!---->
		<form id="pledgejiru" method="post" action="<?=$zjjp?>payconf/pledgejirusave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 押金计入学费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?=!empty($tuition['pledgejiru'])&&$tuition['pledgejiru']=='yes' ? "checked='checked'":" "?> value="yes" name="pledgejiru" aria-required="true" aria-invalid="false">
						<span class="lbl"> 开</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?=!empty($tuition['pledgejiru'])&&$tuition['pledgejiru']=='no' ? "checked='checked'":" "?> value="no" name="pledgejiru" aria-required="true" aria-invalid="false">
						<span class="lbl"> 关</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form>
		<!---->
			<!---->
	<!-- 	<form id="entry_fee" method="post" action="<?=$zjjp?>payconf/entry_feesave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 报名费计入学费</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?=!empty($tuition['entry_fee'])&&$tuition['entry_fee']=='yes' ? "checked='checked'":" "?> value="yes" name="entry_fee" aria-required="true" aria-invalid="false">
						<span class="lbl"> 开</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?=!empty($tuition['entry_fee'])&&$tuition['entry_fee']=='no' ? "checked='checked'":" "?> value="no" name="entry_fee" aria-required="true" aria-invalid="false">
						<span class="lbl"> 关</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form> -->
		<!---->
			<!---->
	<!-- 	<form id="abatement" method="post" action="<?=$zjjp?>payconf/abatementsave">
			<div class="profile-activity clearfix">
				<div>
					<label class="line-height-1 blue">
						<h4> 续读生减免</h4>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<label class="line-height-1">
						<input class="ace valid" type="radio" <?=!empty($abatement['abatement'])&&$abatement['abatement']=='yes' ? "checked='checked'":" "?> value="yes" name="abatement" aria-required="true" aria-invalid="false">
						<span class="lbl"> 开</span>
					</label>
					<label class="line-height-1">
						<input class="ace valid"  type="radio" <?=!empty($abatement['abatement'])&&$abatement['abatement']=='no' ? "checked='checked'":" "?> value="no" name="abatement" aria-required="true" aria-invalid="false">
						<span class="lbl"> 关</span>
					</label>
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input class="span3" value="<?=!empty($abatement['abatement'])&&$abatement['abatement']!='no'? $abatement['abatementmoney']:""?>" type="text" id="abatementmoney" name="abatementmoney" placeholder="减免比例">&nbsp;&nbsp;&nbsp;&nbsp;
					<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<button class="btn btn-success">保存</button>
				</div>
			</div>
		</form> -->
		<!---->
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
<script type="text/javascript">
	$(document).ready(function(){
		//续读生减免
  
		$("input[name=abatement]:eq(0)").click(function(){
	        $("input[name=abatementmoney]").attr("disabled",false);                        
	     });
	     $("input[name=abatement]:eq(1)").click(function(){
	        $("input[name=abatementmoney]").attr("disabled",true);                        
	     });

	     if($("input[name=abatement]:eq(1)").attr('checked')=='checked'){
			$("input[name=abatementmoney]").attr("disabled",true);                        
	 		}
	 	//
		//床上费用收取
		$("input[name=bed]:eq(0)").click(function(){
	        $("input[name=bedmoney]").attr("disabled",false);                        
	        $("#bedway").attr("disabled",false);   
	     });
	     $("input[name=bed]:eq(1)").click(function(){
	        $("input[name=bedmoney]").attr("disabled",true);                        
	     	$("#bedway").attr("disabled",true); 
	     });

	     if($("input[name=bed]:eq(1)").attr('checked')=='checked'){
			$("input[name=bedmoney]").attr("disabled",true);                        
	 		$("#bedway").attr("disabled",true);
	 		}
	 	//
	 	//	保险费收取
		$("input[name=insurance]:eq(0)").click(function(){
	        $("input[name=insurancemoney_one]").attr("disabled",false);                        
	        $("input[name=insurancemoney_two]").attr("disabled",false);                        
	        $("#insuranceway").attr("disabled",false);   
	     });
	     $("input[name=insurance]:eq(1)").click(function(){
	        $("input[name=insurancemoney_one]").attr("disabled",true);                        
	        $("input[name=insurancemoney_two]").attr("disabled",true);                        
	     	$("#insuranceway").attr("disabled",true); 
	     });

	     if($("input[name=insurance]:eq(1)").attr('checked')=='checked'){
			$("input[name=insurancemoney_one]").attr("disabled",true);                        
			$("input[name=insurancemoney_two]").attr("disabled",true);                        
	 		$("#insuranceway").attr("disabled",true);
	 		}
	 	//	电费收取
		$("input[name=electric]:eq(0)").click(function(){
	        $("input[name=electricmoney]").attr("disabled",false);                        
	        $("#electricway").attr("disabled",false);   
	     });
	     $("input[name=electric]:eq(1)").click(function(){
	        $("input[name=electricmoney]").attr("disabled",true);                        
	     	$("#electricway").attr("disabled",true); 
	     });

	     if($("input[name=electric]:eq(1)").attr('checked')=='checked'){
			$("input[name=electricmoney]").attr("disabled",true);                        
	 		$("#electricway").attr("disabled",true);
	 		}

	 	//	书费
		$("input[name=books]:eq(0)").click(function(){
	        $("input[name=booksmoney]").attr("disabled",false);                        
	        $("#booksway").attr("disabled",false);   
	     });
	     $("input[name=books]:eq(1)").click(function(){
	        $("input[name=booksmoney]").attr("disabled",true);                        
	     	$("#booksway").attr("disabled",true); 
	     });

	     if($("input[name=books]:eq(1)").attr('checked')=='checked'){
			$("input[name=booksmoney]").attr("disabled",true);                        
	 		$("#booksway").attr("disabled",true);
	 		}
	 		//住宿押金
		$("input[name=acc_pledge]:eq(0)").click(function(){
	        $("input[name=acc_pledgemoney]").attr("disabled",false);                        
	        $("#acc_pledgeway").attr("disabled",false);   
	     });
	     $("input[name=acc_pledge]:eq(1)").click(function(){
	        $("input[name=acc_pledgemoney]").attr("disabled",true);                        
	     	$("#acc_pledgeway").attr("disabled",true); 
	     });

	     if($("input[name=acc_pledge]:eq(1)").attr('checked')=='checked'){
			$("input[name=acc_pledgemoney]").attr("disabled",true);                        
	 		$("#acc_pledgeway").attr("disabled",true);
	 		}
	     //接机费用收取
		$("input[name=pickup]:eq(0)").click(function(){
	        $("input[name=pickupmoney]").attr("disabled",false);                        
	        $("#pickupway").attr("disabled",false);   
	     });
	     $("input[name=pickup]:eq(1)").click(function(){
	        $("input[name=pickupmoney]").attr("disabled",true);                        
	     	$("#pickupway").attr("disabled",true); 
	     });


		 $("input[name=stay]:eq(0)").click(function(){
       		 $("#staymoney").attr("disabled",'false');  
       		 $("#stayway").attr("disabled",'false');                           
   		  });
		 $("input[name=stay]:eq(1)").click(function(){
       		  $("#staymoney").removeAttr("disabled",'true');  
       		 $("#stayway").removeAttr("disabled",'true');                              
   		  });
		  $("input[name=stay]:eq(2)").click(function(){
       		 $("#staymoney").attr("disabled",'false');  
       		 $("#stayway").attr("disabled",'false');                          
   		  });

		  if($("input[name=pickup]:eq(1)").attr('checked')=='checked'){
			$("input[name=pickupmoney]").attr("disabled",true);                        
	 		$("#pickupway").attr("disabled",true);
	 		}
		
		if($("input[name=stay]:eq(0)").attr('checked')=='checked'){
			$("input[name=staymoney]").attr("disabled",'false');                        
     		$("#stayway").attr("disabled",'false'); 
		}
		if($("input[name=stay]:eq(1)").attr('checked')=='checked'){
			$("input[name=staymoney]").removeAttr("disabled");                        
     		$("#stayway").removeAttr("disabled"); 
		}
		if($("input[name=stay]:eq(2)").attr('checked')=='checked'){
			$("input[name=staymoney]").attr("disabled",'false');                        
     		$("#stayway").attr("disabled",'false'); 
		}




		if($("input[name=apply]:eq(1)").attr('checked')=='checked'){
			$("input[name=applymoney]").attr("disabled",'false');                        
     		$("#applyway").attr("disabled",'false'); 
		}
		if($("input[name=pledge]:eq(1)").attr('checked')=='checked'){
			$("input[name=pledgemoney]").attr("disabled",'false');                        
     		$("#pledgeway").attr("disabled",'false');
		}
     $("input[name=apply]:eq(0)").click(function(){
        $("input[name=applymoney]").attr("disabled",false);                        
        $("#applyway").attr("disabled",false);   
     });
     $("input[name=apply]:eq(1)").click(function(){
        $("input[name=applymoney]").attr("disabled",true);                        
        $("#applyway").attr("disabled",true); 
     });
     $("input[name=apply]:eq(2)").click(function(){
        $("input[name=applymoney]").attr("disabled",true);                        
     	$("#applyway").attr("disabled",true); 
     });

     $("input[name=pledge]:eq(0)").click(function(){
        $("input[name=pledgemoney]").attr("disabled",false);                        
        $("#pledgeway").attr("disabled",false);   
     });
     $("input[name=pledge]:eq(1)").click(function(){
        $("input[name=pledgemoney]").attr("disabled",true);                        
     	$("#pledgeway").attr("disabled",true); 
     });

//减免比例
     $("#abatement").validate(
		{
				rules:
				{
				bedmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				bedmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
//报名费计入学费
     $("#entry_fee").validate(
		{
				rules:
				{
				bedmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				bedmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
//押金计入学费
     $("#pledgejiru").validate(
		{
				rules:
				{
				bedmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				bedmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
     //重修费
     $("#repair_fee").validate(
		{
				rules:
				{
				bedmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				bedmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
     //换证费
     $("#replacement").validate(
		{
				rules:
				{
				bedmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				bedmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
     //床上费用设置
     $("#bed").validate(
		{
				rules:
				{
				bedmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				bedmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
      //保险费用收取
     $("#insurance").validate(
		{
				rules:
				{
				insurancemoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				insurancemoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
       //电费用收取
     $("#acc_pledge").validate(
		{
				rules:
				{
				electricmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				electricmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
      //电费用收取
     $("#electric").validate(
		{
				rules:
				{
				electricmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				electricmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
          //书费用收取
     $("#books").validate(
		{
				rules:
				{
				booksmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				booksmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
	//接机费用设置

      $("#pickup").validate(
		{
				rules:
				{
				pickupmoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				pickupmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});



      $("#stay").validate(
		{
				rules:
				{
				staymoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				pickupmoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});

     $("#apply").validate(
		{
				rules:
				{
				applymoney: { 
					required:true,
					number:true  
				},
				
				},

				messages:
				{
				applymoney: { 
					required: "请输入金额",
					number: "请输入正确的金额(例如:110.23...)" 
				},
				
				},
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
     $("#pledge").validate(
		{
				rules:
				{
				pledgemoney: { 
					required:true,
					number:true  
				 },
			
				},

				messages:
				{
				pledgemoney: { 
					required: "请输入金额" ,
					number: "请输入正确的金额(例如:110.23...)",
				},
		
				},
				submitHandler:function(form){
					var data=$(form).serialize();
					$.ajax({
						url: $(form).attr('action'),
						type: 'POST',
						dataType: 'json',
						data: data,
					})
					.done(function(r) {
						if(r.state == 1){
							pub_alert_success();
						}else{
							pub_alert_error(r.info);
						}
						
					})
					.fail(function() {
						pub_alert_error();
					})

				}
			
			

		});  
       $("#scholarship").validate(
		{
			submitHandler:function(form){
				var data = $(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data,
				})
				.done(function(r) {
					if(r.state == 1){
						pub_alert_success();
					}else{
						pub_alert_error(r.info);
					}
				})
				.fail(function() {
					pub_alert_error('未知错误');
				});
			}
			

		});
});

</script>
<script>
function add_value(){
	
	$.ajax({
		type:'GET',
		url:'/master/basic/payconf/add_pickup',
		success:function(r){
			if(r.state == 1){
				
				$('#zyj_add').append(r.data);
				
			}
			
		},
		dataType:'json'
		
	});
	
}

function del(t){
		var p = $(t).parent();
		r = confirm('您确定要删除吗？');
		if(r){
			p.hide(600,function(){
						p.remove();
					});
		}
	}

</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>