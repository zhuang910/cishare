<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	
	<li>
		<a href="javascript:;">收费管理</a>
	</li>
	<li><a href="index">缴费管理</a></li>
	<li>按类别缴费</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>

<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script src="<?=RES?>master/js/upload.js"></script>	
<?php $this->load->view('master/public/js_css_kindeditor');?>
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" /
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		按类别缴费
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green">按类别缴费
						<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
							<i class="ace-icon fa fa-reply light-green bigger-130"></i>
						</a>
					</h3>
					<!---->
						<form class="form-horizontal" id="validation-form" method="post">
							
						<div class="form-group" id="insert">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">费用类型:</label>
							
							<div class="col-xs-12 col-sm-4">
								<select onchange="chaange_select(<?=$userid?>)" id="type" class="form-control" name="type">
									<option value="0" >-请选择-</option>
									<option value="tuition" >学费</option>
                                    <option value="insurance" >保险费</option>
                                    <option value="apply" >申请费</option>
                                    <option value="rebuild" >重修费</option>
                                    <option value="barter_card" >换证费</option>
                                    <option value="acc" >住宿费</option>
                                    <option value="acc_pledge" >住宿押金</option>
									<option value="electric" >电费</option>
                                    <option value="electric_pledge" >电费押金</option>
									<option value="book" >书费</option>
									<option value="bedding" >床品费</option>
									<option value="pledge" >入学押金</option>
								</select>
								<span id="acc_info"></span>
							</div>
						</div>
						<div class="space-2"></div>
						<div class="form-group" style="display:none" id='effect'>
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">保险生效日期:</label>

							<div class="col-xs-12 col-sm-4">
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" id="effect_time" class="date-picker" name="effect_time" value="">
								</div>
							</div>

						</div>
						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right">支付方式</label>
								<div class="col-xs-12 col-sm-9">
									<div>
                                        <label class="line-height-1 blue">
                                            <input onclick="change_proof(0)" class="ace" type="radio" value="5" name="paytype">
                                            <span class="lbl"> 刷卡 </span>
                                        </label>
										<label class="line-height-1 blue">
											<input onclick="change_proof(0)" class="ace" type="radio" value="4" name="paytype">
											<span class="lbl"> 现金 </span>
										</label>
                                        <label class="line-height-1 blue">
                                            <input onclick="change_proof(1)" class="ace" type="radio" value="3" name="paytype">
                                            <span class="lbl"> 汇款 </span>
                                        </label>
										<label class="line-height-1 blue">
											<input onclick="change_proof(0)" class="ace" type="radio" value="6" name="paytype">
											<span class="lbl"> 奖学金 </span>
										</label>
									</div>
								</div>
							</div> 
							<div id="huikuai" style="display:none;">
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">汇款号:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="proof_number" name="huikuan_proof_number" value="" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="name" class="control-label col-xs-12 col-sm-3 no-padding-right">上传汇款:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" class="col-xs-12 col-sm-5" value="" id="huikuan_file_path" name="huikuan_file_path">
										<a href="javascript:swfupload('file_paths','huikuan_file_path','文件上传',0,3,'doc,docx,jpg,png,gif',3,0,yesdo,nodo)" class="btn btn-warning btn-xs">
											<i class="ace-icon glyphicon glyphicon-search bigger-180 icon-only"></i>
										</a>
									</div>
								</div>
							</div>
							</div>
							<div class="space-2"></div>
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">收据号:</label>

								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" id="proof_number" name="proof_number" value="" class="col-xs-12 col-sm-5" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="name" class="control-label col-xs-12 col-sm-3 no-padding-right">上传收据:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
										<input type="text" class="col-xs-12 col-sm-5" value="" id="file_path" name="file_path">
										<a href="javascript:swfupload('file_paths','file_path','文件上传',0,3,'doc,docx,jpg,png,gif',3,0,yesdo,nodo)" class="btn btn-warning btn-xs">
											<i class="ace-icon glyphicon glyphicon-search bigger-180 icon-only"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="space-2"></div>
						<div class="form-group">
								<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">备注:</label>
								<div class="col-xs-12 col-sm-9">
									<div class="clearfix">
											
											<textarea name="remark" style="width: 458px; height: 106px;"></textarea>
											
									</div>
								</div>
							</div>
							<div class="space-2"></div>
						<input type="hidden" name="userid" value="<?=$userid?>">
						<div class="col-md-offset-3 col-md-9">
							<a id="jiaofei" class="btn btn-info" onclick="sub_mit()" simida="jiaofei">
								<i class="ace-icon fa fa-check bigger-110"></i>
									缴费
							</a>
						</div>
					</form>
					<!---->
				</div>
			</div>
			<!-- /section:plugins/fuelux.wizard.container -->
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
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<?php $this->load->view('master/public/js_kindeditor_create')?>
<!-- script -->
<script type="text/javascript">
function change_proof(r){
	if(r==1){
		$('#huikuai').css({
			display: 'block'
		});
	}
	if(r==0){
		$('#huikuai').css({
			display: 'none'
		});
	}
}
function major_term (majorid,userid,isliuji){
	$('#jiaofei').removeAttr('disabled')
	var term=$('#term').val();
	$.ajax({
		url: '/master/charge/pay/get_major_term_tuition?majorid='+majorid+'&term='+term+'&userid='+userid+'&isliuji='+isliuji,
		type: 'GET',
		dataType: 'json',
		data: {}
	})
	.done(function(r) {
		//学费已经交够
		if(r.state==2){
			$('#jiaofei').attr({
				disabled: 'disabled'
			});
			
			pub_alert_error('该学期学费已经缴纳');
		}
		if(r.state==1){
			$('#tuitin_grf').remove();
			$('#boxss').remove();
			$('#boxsss').remove();
	    	 var str='<div id="tuitin_grf">';
	    	 	 if(r.data.now_tuition!=0){
    		    	 str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">当学期学费:</label><div class="col-xs-12 col-sm-4">';
					 str+='<input type="text" disabled="disabled" name="now_tuition" value="'+r.data.now_tuition+'"><input type="hidden" value="'+r.data.barter_card_ids+'" name="barter_card_ids"></div></div><div class="space-2"></div>';
    		    }
	    	 if(r.data.before_tuition_arr!=0){
	    	 	var type={1:'paypal',2:'payease',3:'凭据',4:'现金',5:'刷卡',6:'奖学金',7:'申请折扣',8:'押金充抵'};

    		    str+='<div id ="boxss">';
    		   
				str+='<div class="form-group" id="insert_term"><label for="name" class="control-label col-xs-9 col-sm-3 no-padding-right">本学期缴费信息:</label><div class="col-xs-12 col-sm-7">';
				str+='<table class="table table-striped table-bordered"><thead><th width="50">缴费类型</th><th width="50">缴费时间</th><th width="50">缴费金额</th><th width="50">备注</th></thead><tbody>'
				$.each(r.data.before_tuition_arr, function(k, v) {
					str+='<tr><td>'+type[v.paytype]+'</td><td>'+v.paytime+'</td><td>'+v.tuition+'</td><td><a href="javascript:;" onclick="look_remark('+v.budgetid+')">查看备注</a></td></tr>'
				});
				str+='</tbody></table>'
				str+='</div>';
			}
			if(r.data.tuition_his!=0){
	    	 	var type={1:'paypal',2:'payease',3:'凭据',4:'现金',5:'刷卡',6:'奖学金',7:'申请折扣',8:'押金充抵'};

    		    str+='<div id ="boxsss">';
    		   
				str+='<div class="form-group" id="insert_term"><label for="name" class="control-label col-xs-9 col-sm-3 no-padding-right">其他学期缴费信息:</label><div class="col-xs-12 col-sm-7">';
				str+='<table class="table table-striped table-bordered"><thead><th width="50">缴费类型</th><th width="50">缴费时间</th><th width="50">缴费金额</th><th width="50">支付学期</th><th width="50">备注</th></thead><tbody>'
				$.each(r.data.tuition_his, function(k, v) {
					str+='<tr><td>'+type[v.paytype]+'</td><td>'+v.paytime+'</td><td>'+v.tuition+'</td><td>第'+v.nowterm+'学期</td><td><a href="javascript:;" onclick="look_remark('+v.budgetid+')">查看备注</a> '+(type[v.paytype] == '现金' ? ' | <a href="javascript:;" onclick="doit_print('+v.tuition+','+v.proof_number+',4)">打印</a>' : '')+' '+(type[v.paytype] == '现金' ? ' | <a href="'+ v.file_path+'" target="_blank" >查看凭据</a>' : '')+'</td></tr>';
				});
				str+='</tbody></table>'
				str+='</div>';
			}
	    	 if(r.data.money_barter_card!=0){
	    	 	str+='<div class="form-group" id="grf_barter_card"><label for="name" class="control-label col-xs-9 col-sm-3 no-padding-right">换证费信息:</label><div class="col-xs-12 col-sm-7">';
				str+='<table class="table table-striped table-bordered"><thead><th width="80">学期</th><th width="50">生成时间</th><th width="80">换证金额</th><th width="50"></th></thead><tbody>';
				$.each(r.data.barter_card_info, function(k, v) {
					str+='<tr><td>第'+v.term+'学期</td><td>'+v.createtime+'</td><td>'+v.money+'</td><th><input id="select_barter_card" type="checkbox" onchange="change_barter_card(this,'+v.money+')" value="'+v.id+'" name="barter_card_ids[]"></th></tr>'
				});
				str+='</tbody></table></div>';
	    	 }
			if(r.data.money_rebuild!=0){
				str+='<div class="form-group"><label for="name" class="control-label col-xs-9 col-sm-3 no-padding-right">重修费信息:</label><div class="col-xs-12 col-sm-7">';
				str+='<table class="table table-striped table-bordered"><thead><th width="80">学期</th><th width="50">生成时间</th><th width="80">重修费金额</th><th width="50"></th></thead><tbody>'
				$.each(r.data.rebuild_info, function(k, v) {
					str+='<tr><td>第'+v.term+'学期</td><td>'+v.createtime+'</td><td>'+v.money+'</td><th><input type="checkbox" value="'+v.id+'" onchange="change_barter_card(this,'+v.money+')" name="rebuild_ids[]"></th></tr>'
				});
				str+='</tbody></table></div>';
			}
				 str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交费用:</label><div class="col-xs-12 col-sm-4">';
				 str+='<input type="text" name="last_money" id="last_money" value="'+r.data.surplus_tuition+'"></div></div><div class="space-2"></div>';
				 str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实交费用:</label><div class="col-xs-12 col-sm-4">';
				 str+='<input type="text" id="paid_in" name="paid_in" value=""></div></div><div class="space-2"></div>';
				 str+='</div>';
			 $('#insert_term').after(str);
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
/**
 * [change_barter_card 计入换证费]
 * @return {[type]} [description]
 */
function change_barter_card(th,mo){
	
	var is = $(th).is(':checked');
	if(is==true){
		var num=parseInt($("#last_money").val());
		$("#last_money").val(num+mo);
	}else{
		var num=parseInt($("#last_money").val());
		$("#last_money").val(num-mo);
	}
}

function chaange_select(userid){
	$('#jiaofei').removeAttr('disabled')
	$('#acc_info').text('');
	$('#effect').css({
		display: 'none'
	});
	var type=$('#type').val();
	if(type=='tuition'){
		$.ajax({
			url: '/master/charge/pay/type_tuition?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: type
		})
		.done(function(r) {
			if(r.state==1){
				$('#box').remove();
				var str='<div id="box"><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业名字:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="hidden" value="'+r.data.id+'" name="majorid">';
				str+='<input type="text" disabled="disabled" name="majorname" value="'+r.data.name+'"></div></div><div class="space-2"></div>';
				str+='<div class="form-group" id="insert_term"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学期:</label><div class="col-xs-12 col-sm-4">';
				if(r.data.isliuji == 1){
					str+='<select id="term" onchange="major_term('+r.data.id+','+userid+',1)" name="term">';
				}else{
					str+='<select id="term" onchange="major_term('+r.data.id+','+userid+',0)" name="term">';
				}
				str+='<option value="0">-请选择-</option>';
				for(i=1;i<=r.data.termnum;i++){
					str+='<option value="'+i+'">第'+i+'学期</option>';
				}
				str+='</div></div><div class="space-2"></div></div>';
				$('#insert').after(str);
			}
			if(r.state==0){
				pub_alert_error('该学生还没有报名专业');
			}
		})
		.fail(function() {
			console.log("error");
		})
	}
	if(type=='electric'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_electric?userid='+userid,
			type: 'GET',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			if(r.state==1){
				var str='<div id="box">';
					//应交金额
					str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-12 col-sm-4">';
					str+='<input type="text" name="last_money" value="'+r.data+'"></div></div><div class="space-2"></div>';
					//实缴金额
							
					str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in"/></div></div></div><div class="space-2"></div>';
				str+='</div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
	}
	if(type=='acc'){
		$.ajax({
			url: '/master/charge/pay/type_acc?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: type
		})
		.done(function(r) {
			$('#box').remove();
			if(r.state==1){
				if(r.data.info==null){
					$('#acc_info').text('该生没有预定房间');
				}else{
					//奖学金覆盖类型
					if(r.data.jx_state==1){
						if(r.data.term_year==2){
							$.each(r.data.cost_cover, function(k, v) {
								 if(v==2){

							 		$('#acc_info').text('该生是奖学金生包含住宿费');
							 		$('#jiaofei').attr({
							 			disabled: 'disabled'
							 		});
								 }
							});
						}else{
								$('#acc_info').text('该生是奖学金生第一学期不用交住宿费');
						}
					}
					$('#box').remove();
					var str='<div id="box">';
					if(r.data.is_qianfei==1){
						str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">欠费天数:</label><div class="col-xs-12 col-sm-4">';
						str+='<input type="text" name="day" value="'+r.data.residue_days+'"></div></div><div class="space-2"></div>';
						str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">欠费金额:</label><div class="col-xs-12 col-sm-4">';
						str+='<input type="text" name="day" value="'+r.data.qianfei_money+'"></div></div><div class="space-2"></div>';
					}
					str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">住宿天数:</label><div class="col-xs-12 col-sm-4">';
					str+='<input type="hidden" name="acc_id" value="'+r.data.acc_id+'"><input type="text" onchange="count_acc_money('+r.data.info.dayprices+')" id="day" name="day" value=""></div></div><div class="space-2"></div>';
					str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-12 col-sm-4">';
					str+='<input type="text" id="acc" name="last_money" value=""></div></div><div class="space-2"></div>';
					str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实交金额:</label><div class="col-xs-12 col-sm-4">';
					str+='<input type="text" name="paid_in" value=""></div></div><div class="space-2"></div>';
					str+='<div id="insert" class="form-group"><label for="name" class="control-label col-xs-12 col-sm-2 no-padding-right"></label><div class="col-xs-12 col-sm-9">'+r.data.info.cname+'的'+r.data.info.bname+'第'+r.data.info.floor+'层的'+r.data.info.rname+'每天'+r.data.info.dayprices+'RMB'+r.data.day+'到期</div></div>';
					str+='</div>';
					$('#insert').after(str);
				}
				
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}
	if(type=='acc_pledge'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_acc_pledge?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			if(r.state==1){
                if(r.data.is_jiao==1){
                    $('#acc_info').text('该生是奖学金生包含住宿押金');
                    $('#jiaofei').attr({
                        disabled: 'disabled'
                    });
                }
				var str='<div id="box"><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text" value="'+r.data.acc_pledge.acc_pledgemoney+'" name="last_money" id="payable" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="text" name="paid_in" value=""></div></div><div class="space-2"></div>';
				str+='</div>';
				$('#insert').after(str);
			}
			if(r.state==2){
				pub_alert_error(r.info);
			}
		})
		.fail(function() {
			console.log("error");
		})
		
	}
	//书费
	if(type=='book'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_book?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			if(r.state==1){
                if(r.data.is_jiao==1){
                    $('#acc_info').text('该生是奖学金生包含书费');
                    $('#jiaofei').attr({
                        disabled: 'disabled'
                    });
                }
				var str='<div id="box">';
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业名字:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="hidden" value="'+r.data.major_info.id+'" name="majorid">';
				str+='<input type="text" disabled="disabled" name="majorname" value="'+r.data.major_info.name+'"></div></div><div class="space-2"></div>';
				str+='<div class="form-group" id="insert_term"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学期:</label><div class="col-xs-12 col-sm-4">';
				str+='<select id="term" onchange="major_book('+r.data.major_info.id+','+userid+')" name="term">';
				str+='<option value="0">-请选择-</option>';
				for(i=1;i<=r.data.major_info.termnum;i++){
					str+='<option value="'+i+'">第'+i+'学期</option>';
				}
				str+='</select></div></div><div class="space-2"></div>';
				str+='<div>';
				// str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应缴书费:</label><div class="col-xs-12 col-sm-4">';
				// str+='<input type="hidden" name="book_ids" value="'+r.data.book_ids+'"><input type="text" name="book_money" value="'+r.data.book_money+'"></div></div><div class="space-2"></div>';
				// //实缴金额
						
				// str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
	//电费押金
	if(type=='electric_pledge'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_electric_pledge?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			$('#box').remove();
			if(r.state==2){
				$('#acc_info').text('已经交过');
			}
			if(r.state==1){
				var str='<div id ="box">';
				//应交金额
				str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="text" name="last_money" value="'+r.data+'"></div></div><div class="space-2"></div>';
				//实缴金额
						
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				str+='</div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
	if(type=='insurance'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_insurance?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			if(r.state==1){
				if(r.data.is_jiao!=0&&r.data.is_jiao==1){
					if(r.data.year_jiao==0){
						$('#acc_info').text('该学生是奖学金用户今年包含保险费');
						$('#jiaofei').attr({
							 			disabled: 'disabled'
							 		});
					}
				}else if(r.data.is_jiao!=0&&r.data.is_jiao==2){
					$('#acc_info').text('该学生是奖学金用户包含保险费');
					$('#jiaofei').attr({
							 			disabled: 'disabled'
							 		});
				}
				$('#effect').css({
					display: 'block'
				});
				var/* str='<div id="box"><div id="nihaoma"></div><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">保险期限:</label><div class="col-xs-12 col-sm-4">';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="1" onclick="change_insurance()"><span class="lbl"> 半年 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="2" onclick="change_insurance()"><span class="lbl"> 一年 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="3" onclick="change_insurance()"><span class="lbl"> 一年半 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="4" onclick="change_insurance()"><span class="lbl"> 两年 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="5" onclick="change_insurance()"><span class="lbl"> 两年半 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="6" onclick="change_insurance()"><span class="lbl"> 三年 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="7" onclick="change_insurance()"><span class="lbl"> 三年半 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="8" onclick="change_insurance()"><span class="lbl"> 四年 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="9" onclick="change_insurance()"><span class="lbl"> 四年半 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="deadline" value="10" onclick="change_insurance()"><span class="lbl"> 五年 </span></label>';
				str+='</div></div><div class="space-2"></div>';*/
				 str='<div id="box"><div id="nihaoma"></div><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学期:</label><div class="col-xs-12 col-sm-4">';
				str+='<select id="term" name="term">';
				str+='<option value="0">-请选择-</option>';
				for(i=1;i<=10;i++){
					str+='<option value="'+i+'">第'+i+'学期</option>';
				}
				str+='</select>';
				str+='</div></div><div class="space-2"></div>';
				str+='<div id="box"><div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学生类型:</label><div class="col-xs-12 col-sm-4">';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="student_type" value="1" onclick="change_insurance(1)"><span class="lbl"> 新生 </span></label>';
				str+='<label class="line-height-1 blue"><input class="ace" type="radio" name="student_type" value="2" onclick="change_insurance(1)"><span class="lbl"> 老生 </span></label>';
				str+='</div></div><div class="space-2"></div>';
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text" value="" name="last_money" id="payable" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				
				//实缴金额
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div><div class="space-2"></div>';
				str+='</div>';	
				$('#insert').after(str);


				if(r.data.info_his!=0){
					var type={1:'paypal',2:'payease',3:'凭据',4:'现金',5:'刷卡',6:'奖学金',7:'申请折扣'};
					   	strs='<div id ="boxgaga">';
    		   
						strs+='<div class="form-group"><label for="name" class="control-label col-xs-9 col-sm-3 no-padding-right">缴费历史信息:</label><div class="col-xs-12 col-sm-7">';
						strs+='<table class="table table-striped table-bordered"><thead><th width="50">缴费类型</th><th width="50">缴费时间</th><th width="50">生效日期</th><th width="50">期限</th><th width="50">缴费金额</th><th width="50">备注</th></thead><tbody>'
						$.each(r.data.info_his, function(k, v) {
							strs+='<tr><td>'+type[v.paytype]+'</td><td>'+v.paytime+'</td><td>'+v.effect_time+'</td><td>'+v.deadline+'</td><td>'+v.paid_in+'</td><td><a href="javascript:;" onclick="look_remark('+v.budgetid+')">查看备注</a></td></tr>'
						});
						strs+='</tbody></table>'
						strs+='</div>';
						strs+='</div>';
						$('#nihaoma').after(strs);
				}
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}

	//床品费
	if(type=='bedding'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_bedding?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			$('#box').remove();
			if(r.state==2){
				$('#acc_info').text('已经交过');
			}
			if(r.state==1){
				var str='<div id ="box">';
				//应交金额
				str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-9 col-sm-4">';
				str+='<input type="text" name="last_money" value="'+r.data+'"></div></div><div class="space-2"></div>';
				//实缴金额
						
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				str+='</div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
	//重修费
	if(type=='rebuild'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_rebuild?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			$('#box').remove();
			if(r.state==1){
				var str='<div id="box">';
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业名字:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="hidden" value="'+r.data.major_info.id+'" name="majorid">';
				str+='<input type="text" disabled="disabled" name="majorname" value="'+r.data.major_info.name+'"></div></div><div class="space-2"></div>';
				str+='<div class="form-group" id="insert_term"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学期:</label><div class="col-xs-12 col-sm-4">';
				str+='<select id="term" onchange="major_rebuild('+r.data.major_info.id+','+userid+')" name="term">';
				str+='<option value="0">-请选择-</option>';
				for(i=1;i<=r.data.major_info.termnum;i++){
					str+='<option value="'+i+'">第'+i+'学期</option>';
				}
				str+='</div></div><div class="space-2"></div>';
				str+='<div>';
				// str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应缴书费:</label><div class="col-xs-12 col-sm-4">';
				// str+='<input type="hidden" name="book_ids" value="'+r.data.book_ids+'"><input type="text" name="book_money" value="'+r.data.book_money+'"></div></div><div class="space-2"></div>';
				// //实缴金额
						
				// str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
	//换证费
	if(type=='barter_card'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_barter_card?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			$('#box').remove();
			if(r.state==1){
				var str='<div id="box">';
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">专业名字:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="hidden" value="'+r.data.major_info.id+'" name="majorid">';
				str+='<input type="text" disabled="disabled" name="majorname" value="'+r.data.major_info.name+'"></div></div><div class="space-2"></div>';
				str+='<div class="form-group" id="insert_term"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学期:</label><div class="col-xs-12 col-sm-4">';
				str+='<select id="term" onchange="major_barter_card('+r.data.major_info.id+','+userid+')" name="term">';
				str+='<option value="0">-请选择-</option>';
				for(i=1;i<=r.data.major_info.termnum;i++){
					str+='<option value="'+i+'">第'+i+'学期</option>';
				}
				str+='</div></div><div class="space-2"></div>';
				str+='<div>';
				// str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应缴书费:</label><div class="col-xs-12 col-sm-4">';
				// str+='<input type="hidden" name="book_ids" value="'+r.data.book_ids+'"><input type="text" name="book_money" value="'+r.data.book_money+'"></div></div><div class="space-2"></div>';
				// //实缴金额
						
				// str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
	//床品费
	if(type=='bedding'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_bedding?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			$('#box').remove();
			if(r.state==2){
				$('#acc_info').text('已经交过');
			}
			if(r.state==1){
				var str='<div id ="box">';
				//应交金额
				str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-9 col-sm-4">';
				str+='<input type="text" name="last_money" value="'+r.data+'"></div></div><div class="space-2"></div>';
				//实缴金额
						
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				str+='</div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
	//申请费
	if(type=='apply'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_apply?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			$('#box').remove();
			if(r.state==2){
				$('#acc_info').text('已经交过');
			}
			if(r.state==1){
				var str='<div id ="box">';
				str+='<div class="form-group" id="insert_term"><label for="name" class="control-label col-xs-9 col-sm-3 no-padding-right">没有交费申请费:</label><div class="col-xs-12 col-sm-7">';
				str+='<table class="table table-striped table-bordered"><thead><th width="50">申请专业</th><th width="50">申请时间</th><th width="50">操作</th></thead><tbody>'
				$.each(r.data, function(k, v) {
					str+='<tr><td>'+v.name+'</td><td>'+v.applytime+'</td><td><input onchange="change_apply('+v.id+')" type="radio" name="applyid" value="'+v.id+'"></td></tr>'
				});
				str+='</tbody></table>'
				str+='</div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
	//入学押金
	if(type=='pledge'){
		$('#box').remove();
		$.ajax({
			url: '/master/charge/pay/type_pledge?userid='+userid,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			$('#box').remove();
			if(r.state==2){
				$('#acc_info').text('已经交过');
			}
			if(r.state==1){
				var str='<div id ="box">';
				str+='<div class="form-group" id="insert_term"><label for="name" class="control-label col-xs-9 col-sm-3 no-padding-right">没有交费申请费:</label><div class="col-xs-12 col-sm-7">';
				str+='<table class="table table-striped table-bordered"><thead><th width="50">申请专业</th><th width="50">申请时间</th><th width="50">操作</th></thead><tbody>'
				$.each(r.data, function(k, v) {
					str+='<tr><td>'+v.name+'</td><td>'+v.applytime+'</td><td><input onchange="change_pledge('+v.id+')" type="radio" name="applyid" value="'+v.id+'"></td></tr>'
				});
				str+='</tbody></table>'
				str+='</div>';
				$('#insert').after(str);
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
}
function sub_mit(){
	var last_money=$("#last_money").val();
	var paid_in=$("#paid_in").val();
	var data=$('#validation-form').serialize();

    var $is_type = $("input[name='paytype']:checked").val();
    if ($is_type === undefined) {
        pub_alert_error('请选择支付方式');
        return false;
    }

	$.ajax({
	beforeSend:function (){
				$('#jiaofei').html('<i class="ace-icon fa fa-check bigger-110"></i>正在提交');
				$('#jiaofei').attr({
					disabled:'disabled'
				});
			},
		url: '/master/charge/pay/unify_chaange_type_select_submit',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
            var is_type = $("input[name='paytype']:checked").val();
            if(is_type == 4){
				 setTimeout(function(){window.location.reload();},1000);
               // doit_print();
            }else{
               // setTimeout(function(){window.location.href="/master/charge/pay";},1000);
                setTimeout(function(){window.location.reload();},1000);
            }
		}
        if(r.state==0){
            pub_alert_error(r.info);
            $('#jiaofei').html('<i class="ace-icon fa fa-check bigger-110"></i>缴费');
			$('#jiaofei').removeAttr('disabled');
        }

					
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}

function doit_print(paid_in,proof_number,paytype){
    var userid=<?=$userid?>;
    var type=$('#type').val();
    var paid_in=$('#paid_in').val() ? $('#paid_in').val() : paid_in;
    var proof_number=$('#proof_number').val() ? $('#proof_number').val() : proof_number;
    var paytype=$('input[name="paytype"]').filter(':checked').val() ? $('input[name="paytype"]').filter(':checked').val() : paytype;
    pub_alert_html('/master/charge/pay/print_shouju?userid='+userid+'&type='+type+'&paid_in='+paid_in+'&proof_number='+proof_number+'&paytype='+paytype);
}

function count_deadline_money(prc){
	var day=$('#deadline').val();
	if(!isNaN(day)){
		var price=day*prc;
		$('#payable').val(price);
		return true;
	}
	pub_alert_error('您输入不是一个数字');
}
function count_acc_money(prc){
	var day=$('#day').val();
	if(!isNaN(day)){
		var price=day*prc;
		$('#acc').val(price);
		return true;
	}
	pub_alert_error('您输入不是一个数字');
}
function change_insurance(){
	//var time=$("input[name='deadline']:checked").val();
	var student_type=$("input[name='student_type']:checked").val();
	if(student_type!=undefined){
		$.ajax({
			url: '/master/charge/pay/change_paid_in?student_type='+student_type,
			type: 'GET',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			if(r.state==1){
				$('#payable').val(r.data)
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
}
function major_book(mid,userid){
	var data=$('#validation-form').serialize();
	$.ajax({
		url: '/master/charge/pay/get_major_book',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		$('#book_jiao').remove();
		$('#jiaofei').removeAttr('disabled');
		if(r.state==1){
				var str='<div id ="book_jiao">';
				str+='<div class="form-group" id="insert_term"><label for="name" class="control-label col-xs-9 col-sm-3 no-padding-right">缴费信息:</label><div class="col-xs-12 col-sm-7">';
				str+='<table class="table table-striped table-bordered"><thead><th width="50">书名</th><th width="50">价格</th><th width="50"></th></thead><tbody>'
				$.each(r.data.book, function(k, va) {
					var che='';
					if(r.data.select_id!=undefined){
						$.each(r.data.select_id, function(kk, vv) {
							if(va.id==vv){
								che='checked="checked"'
							}
						});
					}
					var ches='';
					var fo='';
					if(r.data.jiao_select_id!=undefined){
						$.each(r.data.jiao_select_id, function(kk, vv) {
							if(va.id==vv){
								ches='disabled="disabled"';
								fo='已交';
							}
						});
					}
					str+='<tr><td>'+va.name+'</td><td>'+va.price+'</td><td><input type="checkbox" name="ids[]" '+che+ches+' onchange="change_book(this,'+va.price+')" value="'+va.id+'">'+fo+'</td></tr>'
				});
				str+='</tbody></table>'
				str+='</div>';
				//应交金额
				str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="text" name="last_money" id="last_money" value="'+r.data.money+'"></div></div><div class="space-2"></div>';
				//实缴金额
						
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				str+='<input type="hidden" name="book_ids" value="'+r.data.bookids+'">';
				str+='</div>';
				$('#insert_term').after(str);
				if(r.data.paystate==1){
					$('#jiaofei').attr({
						disabled: 'disabled'
					});
					pub_alert_success('已经缴费');
				}
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
/**
 * [change_barter_card 计入换证费]
 * @return {[type]} [description]
 */
function change_book(th,mo){
  var is = $(th).is(':checked');
  if(is==true){
    var num=parseInt($("#last_money").val());
    $("#last_money").val(num+mo);
  }else{
    var num=parseInt($("#last_money").val());
    $("#last_money").val(num-mo);
  }
}
/**
 * [major_rebuild 获取学期的重修费用]
 * @return {[type]} [description]
 */
function major_rebuild(){
	var data=$('#validation-form').serialize();
	$.ajax({
		url: '/master/charge/pay/get_major_rebuild',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		$('#book_jiao').remove();
		if(r.state==0){
			pub_alert_error('该学生在该学期没有重修费用');
		}
		if(r.state==1){
			var str='<div id ="book_jiao">';
				//应交金额
				str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="text" name="last_money" value="'+r.data.last_money+'"></div></div><div class="space-2"></div>';
				//实缴金额
						
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				str+='<input type="hidden" name="ids" value="'+r.data.ids+'">';
				str+='</div>';
				$('#insert_term').after(str);
		}
	})
	.fail(function() {
		console.log("error");
	})

}

/**
 * [major_rebuild 获取学期的重修费用]
 * @return {[type]} [description]
 */
function major_barter_card(){
	var data=$('#validation-form').serialize();
	$.ajax({
		url: '/master/charge/pay/get_major_barter_card',
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		$('#book_jiao').remove();
		if(r.state==0){
			pub_alert_error('该学生在该学期没有换证费用');
		}
		if(r.state==1){
			var str='<div id ="book_jiao">';
				//应交金额
				str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-12 col-sm-4">';
				str+='<input type="text" name="last_money" value="'+r.data.last_money+'"></div></div><div class="space-2"></div>';
				//实缴金额
						
				str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
				str+='<input type="hidden" name="ids" value="'+r.data.ids+'">';
				str+='</div>';
				$('#insert_term').after(str);
		}
	})
	.fail(function() {
		console.log("error");
	})

}
function change_apply(id){
	if(id!=undefined){
		$.ajax({
			url: '/master/charge/pay/get_apply_registration_fee?id='+id,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			if(r.state==1){
				$('#book_jiao').remove();
				if(r.state==1){
					var str='<div id ="book_jiao">';
						//应交金额
						str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-12 col-sm-4">';
						str+='<input type="text" name="last_money" value="'+r.data.registration_fee+'"></div></div><div class="space-2"></div>';
						//实缴金额
								
						str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
						str+='</div>';
						$('#insert_term').after(str);
				}
			}
		})
		.fail(function() {
			console.log("error");
		})
		
	}
}
function change_pledge(id){
	if(id!=undefined){
		$.ajax({
			url: '/master/charge/pay/get_pledge_registration_fee?id='+id,
			type: 'POST',
			dataType: 'json',
			data: {}
		})
		.done(function(r) {
			if(r.state==1){
				$('#book_jiao').remove();
				if(r.state==1){
					var str='<div id ="book_jiao">';
						//应交金额
						str+='<div class="form-group" id="is_term_tuition"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">应交金额:</label><div class="col-xs-12 col-sm-4">';
						str+='<input type="text" name="last_money" value="'+r.data.deposit_fee+'"></div></div><div class="space-2"></div>';
						//实缴金额
								
						str+='<div class="form-group"><label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">实缴金额:</label><div class="col-xs-12 col-sm-9"><div class="clearfix"><input type="text"  name="paid_in" id="paid_in" class="col-xs-12 col-sm-5" /></div></div></div><div class="space-2"></div>';
						str+='</div>';
						$('#insert_term').after(str);
				}
			}
		})
		.fail(function() {
			console.log("error");
		})
		
	}
}
function look_remark(id){
	pub_alert_html('/master/charge/pay/look_remark?id='+id);
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
<!-- end script -->
<?php $this->load->view('master/public/footer');?>