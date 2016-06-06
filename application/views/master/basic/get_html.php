<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					批量修改
				</div>
			</div>

			<form action="/master/basic/course/someupdate" method="post" id="myform"  role="form" class="form-horizontal">
	 <div class="modal-body">
		<div class="space-4"></div>
		<div style="width:75%;margin-left:12%;">
		<?php if($type == 1){?>
			<div class="form-group">
			<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> <?=$editfield[$name]?> </label>

			<div class="col-sm-9">
				<input type="text" class="input-xlarge" value="" name="<?=$name?>">
			</div>
		</div>
		<?php }else if($type == 2){?>
		<div class="form-group">
			<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> <?=$editfield[$name]?> </label>
			<div class="col-xs-12 col-sm-4">
				<div class="input-group">
					<input type="text" data-date-format="yyyy-mm-dd" id="<?=$name?>" class="form-control date-picker" name="<?=$name?>" value="">
			<span class="input-group-addon">
				<i class="fa fa-calendar bigger-110"></i>
			</span>
				</div>
			</div>
		</div>

		
		<?php }else if($type == 3){?>
			<?php if($name == 'schooling'){?>
				<div class="form-group">
					<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">学制:</label>

					<div class="col-xs-12 col-sm-9">
						<div class="clearfix">
							<input type="text" id="schooling" name="schooling"  class="col-xs-12 col-sm-1"/> 
							<?php if(!empty($program_unit)){
								foreach($program_unit as $k => $v){
							?>
							<label class="line-height-1 blue">
								<input class="ace" type="radio" value="<?=$k?>"  name="xzunit">
								<span class="lbl" data="xzunit"> <?=$v?></span>
							</label>

							<?php }}?>
							
						</div>

					</div>
				</div>
			<?php }else{?>
			<div class="form-group">
				<label class="control-label col-xs-12 col-sm-3 no-padding-right">是否选课</label>
					<div class="col-xs-12 col-sm-9">
						<div>
							<label class="line-height-1 blue">
								<input class="ace" type="radio" value="1"   name="variable">
								<span class="lbl"> 安排</span>
							</label>
						</div>
						<div>
							<label class="line-height-1 blue">
								<input class="ace" type="radio"  value="0" name="variable">
								<span class="lbl"> 选课</span>
							</label>
						</div>
					</div>
				</div>
			<?php }?>
			
		
		<?php }else if($type == 4){?>
			<div class="form-group">
				<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> <?=$editfield[$name]?> </label>
			<div class="col-sm-9">
				<select data-rule-required="true" id="<?=$name?>" name="<?=$name?>">
					<option value="">请选择...</option>
					<?php foreach($data as $k => $v){?>
					<option value="<?=$k?>"><?=$v?></option>
					<?php }?>
				</select>
			</div>
		</div>
		<?php }else{?>
			<div class="form-group">
					<label for="form-field-1" class="col-sm-3 control-label no-padding-right"> <?=$editfield[$name]?> </label>
					<div class="col-xs-12 col-sm-9"  style="width:100%">
							
							<div class="wysiwyg-editor" id="editor1"></div>
							<input type="hidden" name="<?=$name?>" id="<?=$name?>" value="">
					</div>
				</div>
		<?php }?>
	
		
		<input type="hidden" name="ids" value="<?=$ids?>">
		<input type="hidden" name="type" value="<?=$type?>">
		
		</div>
	 </div>
	
	 <div class="modal-footer center">
		<button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="ace-icon fa fa-check"></i> Submit</button>
		<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
	 </div>
	</form>
	</div>
</div>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
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
jQuery(function($) {
		var cucaseditor = ['#editor1'];
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

<script type="text/javascript">
		function save(){
			<?php 
				if($type == 5){
			?>
				$('#<?=$name?>').val($('#editor1').html());
			<?php }?>
			var data=$('#myform').serialize();
			$.ajax({
				url: '/master/basic/course/someupdate',
				type: 'POST',
				dataType: 'json',
				data: data
			})
			.done(function(r) {
				if(r.state == 1){
					pub_alert_success();
					window.location.reload();
				}else{
					pub_alert_error();
				}
			})
			.fail(function() {
				pub_alert_error();
			})
		}


</script>
