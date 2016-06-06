<?php
$r=!empty($info)?'编辑奖学金':'添加奖学金';
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
		<a href="javascript:;">申请设置</a>
	</li>
	<li><a href="javascript:history.back();">奖学金设置</a></li>
	
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
    <script src="<?=RES?>master/js/upload.js"></script>
<?php $this->load->view('master/public/js_css_kindeditor');?>
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$form_action = $uri4 == 'edit' ? 'update' : 'insert';

?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	奖学金设置
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
				<h3 class="lighter block green"><?=!empty($info)?'编辑奖学金':'添加奖学金'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>	
			<form class="form-horizontal" id="validation-form" method="post" action="/master/basic/scholarship/save">
			
				<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">中文名称:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="title" name="title" value="<?=!empty($info->title) ? $info->title : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

				<div class="space-2"></div>
				<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文名称:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="entitle" name="entitle" value="<?=!empty($info->entitle) ? $info->entitle : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

				<div class="space-2"></div>
				
			<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">数量:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="count" name="count" value="<?=!empty($info->count) ? $info->count : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

				<!--<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">金额:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text"  name="money" value="<?//=!empty($info->money) ? $info->money : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

                <div class="space-2"></div>-->
				<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">排序:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="orderby" name="orderby" value="<?=!empty($info->orderby) ? $info->orderby : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
			<div class="form-group">
						<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">申请日期:</label>

						<div class="col-xs-12 col-sm-4">
							<div class="input-group">
								<input type="text" data-date-format="yyyy-mm-dd" id="applyendtime" class="form-control date-picker" name="applyendtime" value="<?=!empty($info->applyendtime)?date('Y-m-d',$info->applyendtime):''?>">
								<span class="input-group-addon">
									<i class="fa fa-calendar bigger-110"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="space-2"></div>
               
				
				<div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">奖学金类型:</label>
                    <div class="col-xs-12 col-sm-9">
                        <div>
                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->apply_state)&&$info->apply_state==1?'checked="checked"':''?> name="apply_state" value="1" class="ace" id="apply_state">
                                <span class="lbl"> 在学</span>
                            </label>

                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->apply_state) && $info->apply_state==2?'checked="checked"':''?> name="apply_state" value="2" class="ace" id="apply_state">
                                <span class="lbl"> 新生</span>
                            </label>

                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">费用类型:</label>
                    <div class="col-xs-12 col-sm-9">
                        <div>
                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->cost_state)&&$info->cost_state==1?'checked="checked"':''?> name="cost_state" onclick="cover_change()" value="1" class="ace">
                                <span class="lbl"> 指定覆盖</span>
                            </label>

                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->cost_state)&&$info->cost_state==2?'checked="checked"':''?> name="cost_state" value="2" onclick="ratio_change()" class="ace">
                                <span class="lbl"> 指定比例</span>
                            </label>

                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->cost_state)&&$info->cost_state==3?'checked="checked"':''?> name="cost_state" value="3" onclick="money_change()" class="ace">
                                <span class="lbl"> 指定金额</span>
                            </label>

                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->cost_state)&&$info->cost_state==4?'checked="checked"':''?> name="cost_state" value="4" onclick="grant_money_change()" class="ace">
                                <span class="lbl"> 发放金额</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="cover" <?=!empty($info->cost_state)&&$info->cost_state==1?'style="display: block"':'style="display: none"'?> >
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">覆盖费用:</label>
                    <div class="col-xs-12 col-sm-9">
                        <?php
                            $cost_cover=array();
                            if(!empty($info->cost_cover)){
                                $cost_cover=json_decode($info->cost_cover,true);
                            }
                        ?>
                        <div>
                            <label class="line-height-1 blue">
                                <input type="checkbox" <?=!empty($info->cost_state)&&$info->cost_state==1&&!empty($cost_cover[1])?'checked="checked"':''?> name="cost_cover[1]" value="1" class="ace">
                                <span class="lbl"> 学费</span>
                            </label>

                            <label class="line-height-1 blue">
                                <input type="checkbox" <?=!empty($info->cost_state)&&$info->cost_state==1&&!empty($cost_cover[2])?'checked="checked"':''?> name="cost_cover[2]" value="2" class="ace">
                                <span class="lbl"> 住宿费</span>
                            </label>
                            <label class="line-height-1 blue">
                                <input type="checkbox" <?=!empty($info->cost_state)&&$info->cost_state==1&&!empty($cost_cover[3])?'checked="checked"':''?> name="cost_cover[3]" value="3" class="ace">
                                <span class="lbl"> 住宿押金</span>
                            </label>

                            <label class="line-height-1 blue">
                                <input type="checkbox" <?=!empty($info->cost_state)&&$info->cost_state==1&&!empty($cost_cover[4])?'checked="checked"':''?> name="cost_cover[4]" value="4" class="ace">
                                <span class="lbl"> 书费</span>
                            </label>
                         <!--    <label class="line-height-1 blue">
                                <input type="checkbox" <?=!empty($info->cost_state)&&$info->cost_state==1&&!empty($cost_cover[5])?'checked="checked"':''?> name="cost_cover[5]" value="5" class="ace">
                                <span class="lbl"> 床品费</span>
                            </label> -->

                            <label class="line-height-1 blue">
                                <input type="checkbox" <?=!empty($info->cost_state)&&$info->cost_state==1&&!empty($cost_cover[6])?'checked="checked"':''?> name="cost_cover[6]" value="6" class="ace">
                                <span class="lbl"> 保险费</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
                <div class="form-group" id="ratio" <?=!empty($info->cost_state)&&$info->cost_state==2?'style="display: block"':'style="display: none"'?>>
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">指定比例:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="text"  id="cost_ratio" name="cost_ratio" value=" <?=!empty($info->cost_state)&&$info->cost_state==2&&!empty($info->cost_ratio)?$info->cost_ratio:''?>" class="col-xs-12 col-sm-5" />
                        </div>
                    </div>
                </div>

                    <div class="form-group" id="money" <?=!empty($info->cost_state)&&$info->cost_state==3?'style="display: block"':'style="display: none"'?>>
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">指定金额:</label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="text" id="cost_money" name="cost_money" value=" <?=!empty($info->cost_state)&&$info->cost_state==3&&!empty($info->cost_money)?$info->cost_money:''?>" class="col-xs-12 col-sm-5" />
                            </div>
                        </div>
                    </div>
                <div class="form-group" id="grant_money" <?=!empty($info->cost_state)&&$info->cost_state==4?'style="display: block"':'style="display: none"'?>>
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">发放金额:</label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="text" id="cost_grant_money" name="cost_grant_money" value=" <?=!empty($info->cost_state)&&$info->cost_state==4&&!empty($info->cost_grant_money)?$info->cost_grant_money:''?>" class="col-xs-12 col-sm-5" />
                            </div>
                        </div>
                    </div>

                <div class="space-2"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">时间段:</label>
                    <div class="col-xs-12 col-sm-9">
                        <div>
                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->trem_year)&&$info->trem_year==1?'checked="checked"':''?> name="trem_year" value="1" class="ace">
                                <span class="lbl"> 一学年</span>
                            </label>

                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->trem_year)&&$info->trem_year==2?'checked="checked"':''?> name="trem_year" value="2" class="ace">
                                <span class="lbl"> 每学年</span>
                            </label>
<!--
                            <label class="line-height-1 blue">
                                <input type="radio" <?//=!empty($info->trem_year)&&$info->trem_year==3?'checked="checked"':''?> name="trem_year" value="3" class="ace">
                                <span class="lbl"> 一学年</span>
                            </label>
                            <label class="line-height-1 blue">
                                <input type="radio" <?//=!empty($info->trem_year)&&$info->trem_year==4?'checked="checked"':''?> name="trem_year" value="4" class="ace">
                                <span class="lbl"> 每学年</span>
                            </label>-->
                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
				<div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right">是否是中国政府奖学金:</label>
                    <div class="col-xs-12 col-sm-9">
                        <div>
                            <label class="line-height-1 blue">
                                <input type="radio" <?=!empty($info->ischinascholorship)&&$info->ischinascholorship==1?'checked="checked"':''?> name="ischinascholorship" value="1" class="ace">
                                <span class="lbl"> 是</span>
                            </label>

                            <label class="line-height-1 blue">
                                <input type="radio" <?=empty($info->ischinascholorship)||$info->ischinascholorship==0?'checked="checked"':''?> name="ischinascholorship" value="0" class="ace">
                                <span class="lbl"> 否</span>
                            </label>

                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">新生奖学金申请表模版:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="applytemplate" class="form-control" name="applytemplate">
							<option value="">--请选择--</option>
							<?php if(!empty($applytemplate)){
								foreach ($applytemplate as $f => $t) {
							?>
								<option value="<?=$f?>" <?=!empty($info) && $info->applytemplate== $f?'selected':''?>><?=$t?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				<div class="space-2"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">新生奖学金附件模版:</label>

					<div class="col-xs-12 col-sm-4">
						<select id="attatemplate" class="form-control" name="attatemplate">
							<option value="">--请选择--</option>
							<?php if(!empty($attatemplate)){
								foreach ($attatemplate as $f => $t) {
							?>
								<option value="<?=$f?>" <?=!empty($info) && $info->attatemplate== $f?'selected':''?>><?=$t?></option>
							<?php }}?>
							
							
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">上传文档:</label>
					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" name="files" id="files" value="<?=!empty($info->files)?$info->files:''?>" class="col-xs-12 col-sm-5">
							<a class="btn btn-warning btn-xs" href="javascript:swfupload('files','files','文件上传',0,3,'doc,docx,jpg,png,gif',3,0,yesdo,nodo)">
								<i class="ace-icon glyphicon glyphicon-search bigger-180 icon-only"></i>
							</a>
						</div>
					</div>
				</div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">中文介绍:</label>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <div style="display:none;" id="content_aid_box"></div>
                            <textarea name="intro" class='content'  id="intro"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info->intro) ? $info->intro : ''?></textarea>

                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文介绍:</label>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <div style="display:none;" id="content_aid_box"></div>
                            <textarea name="enintro" class='content'  id="enintro"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info->enintro) ? $info->enintro : ''?></textarea>

                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">中文申请条件:</label>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <div style="display:none;" id="content_aid_box"></div>
                            <textarea name="condition" class='content'  id="content"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info->condition) ? $info->condition : ''?></textarea>

                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">英文申请条件:</label>
                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <div style="display:none;" id="content_aid_box"></div>
                            <textarea name="encondition" class='content'  id="encondition"  boxid="content" style="width:100%;height:350px;resize: none;"><?=!empty($info->encondition) ? $info->encondition : ''?></textarea>

                        </div>
                    </div>
                </div>
                <div class="space-2"></div>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
					
					<div class="col-xs-12 col-sm-4">
						<select id="state" class="form-control" name="state">
							<option value="1" <?=!empty($info) && $info->state == 1?'selected':''?>>启用</option>
							<option value="0"  <?=!empty($info) && $info->state == 0?'selected':''?>>禁用</option>
							
						</select>
					</div>
				</div>

			
				
		
			
				<input type="hidden" name="id" value="<?=!empty($info->id)?$info->id:''?>">
				<div class="space-2"></div>
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn btn-info">
						<i class="ace-icon fa fa-check bigger-110"></i>
							提交
					</button>
					<button class="btn" type="reset">
						<i class="ace-icon fa fa-undo bigger-110"></i>
							重置
					</button>
				</div>
			</form>

		</div>
	</div>
</div>
<!-- ace scripts -->
<script src="/resource/master/js/ace-extra.min.js"></script>
<script src="/resource//master/js/ace-elements.min.js"></script>
    <script src="/resource//master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>


<?php $this->load->view('master/public/js_kindeditor_create')?>

<!-- script -->
<script type="text/javascript">
function grant_money_change(){
     $('#cover').css({
        display: 'none'
    });
    $('#ratio').css({
        display: 'none'
    });
    $('#money').css({
        display: 'none'
    });
    $('#grant_money').css({
        display: 'block'
    });
}
function cover_change(){
    $('#cover').css({
        display: 'block'
    });
    $('#ratio').css({
        display: 'none'
    });
    $('#money').css({
        display: 'none'
    });
     $('#grant_money').css({
        display: 'none'
    });
}
function ratio_change(){
    $('#cover').css({
        display: 'none'
    });
    $('#ratio').css({
        display: 'block'
    });
    $('#money').css({
        display: 'none'
    });
    $('#grant_money').css({
        display: 'none'
    });
}
function money_change(){
    $('#cover').css({
        display: 'none'
    });
    $('#ratio').css({
        display: 'none'
    });
    $('#money').css({
        display: 'block'
    });
    $('#grant_money').css({
        display: 'none'
    });
}
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						title: {
							required: true
						},
						entitle: {
							required: true
						},
						apply_state: {
							required: true
						},
						
						state: 'required'
						
					},
			
					messages: {
						title:{
							required:"请输入名称",
						},
						count: {
							required: "请输入数量",
							
						},
						money:{
							required:"请输入金额",
						},
						state: "请选择可用状态"
						
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
						
						var data=$(form).serialize();
						
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="<?=$zjjp?>scholarship";
							}else{
								pub_alert_error();
							}
							
						})
						.fail(function() {
							
							pub_alert_error();
						})
						
						
					}
			
				});

});
</script>
<script type="text/javascript">
	jQuery(function($) {
		var cucaseditor = ['#editor1','#editor2'];
		$.each(cucaseditor,function(i,v){
			$(v).ace_wysiwyg({
					toolbar:
					[
						{
							name:'font',
							title:'Custom tooltip',
							values:['Some Font!','Arial','Verdana','Comic Sans MS','Custom Font!']
						},
						null,
						{
							name:'fontSize',
							title:'Custom tooltip',
							values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text'} 
						},
						null,
						{name:'bold', title:'Custom tooltip'},
						{name:'italic', title:'Custom tooltip'},
						{name:'strikethrough', title:'Custom tooltip'},
						{name:'underline', title:'Custom tooltip'},
						null,
						'insertunorderedlist',
						'insertorderedlist',
						'outdent',
						'indent',
						null,
						{name:'justifyleft'},
						{name:'justifycenter'},
						{name:'justifyright'},
						{name:'justifyfull'},
						null,
						{
							name:'createLink',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-purple',
							button_text:'Custom TEXT'
						},
						{name:'unlink'},
						null,
						{
							name:'insertImage',
							placeholder:'Custom PlaceHolder Text',
							button_class:'btn-inverse',
							//choose_file:false,//hide choose file button
							button_text:'Set choose_file:false to hide this',
							button_insert_class:'btn-pink',
							button_insert:'Insert Image'
						},
						null,
						{
							name:'foreColor',
							title:'Custom Colors',
							values:['red','green','blue','navy','orange'],
							/**
								You change colors as well
							*/
						},
						/**null,
						{
							name:'backColor'
						},*/
						null,
						{name:'undo'},
						{name:'redo'},
						null,
						'viewSource'
					],
					//speech_button:false,//hide speech button on chrome
					
					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
					
				}).prev().addClass('wysiwyg-style2');
		});
				

				
				
	});
			
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

<script type="text/javascript">
<!--添加删除s-->
	function schooling_xzunit(){
		var schooling = $('#schooling').val();
		if(schooling != '' && schooling > 1){
			var xzunit = $("[data='xzunit']");
			var ss = '<span class="zyj">s</span>';
			xzunit.each(function(i,v){
				var s = $(v).find(".zyj")
				
				$(s).remove();
				$(v).append(ss);
			});
		}else{
			var xzunit = $("[data='xzunit']");
			xzunit.each(function(i,v){
				
				 var s = $(v).find(".zyj")
				
				$(s).remove();
			});
		}
	}
</script>

<?php $this->load->view('master/public/footer');?>