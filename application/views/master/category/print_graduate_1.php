<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == '打印结业证';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">在学管理</a>
	</li>
	<li ><a href='index'>学生管理</a></li>
	<li class="active">{$title_h3}</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<!--日期插件-->
<link rel="stylesheet" href="<?=RES?>master/css/datepicker.css" />
<?php 


$form_action = $uri4 == 'edit' ? 'update' : 'insert';
?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		学生管理
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			还不能结业						
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
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script src="<?=RES?>master/js/x-editable/bootstrap-editable.min.js"></script>	
<script type="text/javascript">
$(function(){
	sex="<?=!empty($info)&&!empty($info->sex)&&$info->sex==1?'男':'女'?>";
	isnegro="<?=!empty($info)&&!empty($info->isnegro)&&$info->isnegro==1?'是':'否'?>";
	state="<?=!empty($state)?$state:''?>";
	putupstate="<?=!empty($info)&&!empty($info->putupstate)&&$info->putupstate==1?'校内':'校外'?>";
	isshort="<?=!empty($info)&&!empty($info->isshort)&&$info->isshort==1?'是':'否'?>";
	select_nationlity="<?=$select_nationlity?>";
	select_degree="<?=$select_degree?>";
	$('a[upload-config="true"]').editable({
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>student/student/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    }); 
     $('a[upload-config="nationality"]').editable({
    	type:'select',
    	 prepend: select_nationlity,
    	source: [
    		<?php foreach($nationality as $k=>$v):?>
            {value: <?=empty($k)?0:$k?>, text: '<?=$v?>'},
            <?php endforeach;?>
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>student/student/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });
     $('a[upload-config="degree"]').editable({
    	type:'select',
    	 prepend: select_degree,
    	source: [
    		<?php foreach($degree as $k=>$v):?>
            {value: <?=empty($v['id'])?0:$v['id']?>, text: '<?=$v['title']?>'},
            <?php endforeach;?>
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>student/student/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });    
    $('a[upload-config="sex"]').editable({
    	type:'select',
    	 prepend: sex,
    	source: [
    		{value: 1, text: '男'},
            {value: 2, text: '女'}
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>student/student/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });
     $('a[upload-config="isnegro"]').editable({
    	type:'select',
    	 prepend: isnegro,
    	source: [

            {value: 1, text: '是'},
            {value: 0, text: '否'}
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>student/student/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });  
      $('a[upload-config="putupstate"]').editable({
    	type:'select',
    	 prepend: putupstate,
    	source: [

            {value: 1, text: '校内'},
            {value: 0, text: '校外'}
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>student/student/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    }); 
     
     $('a[upload-config="isshort"]').editable({
    	type:'select',
    	 prepend: isshort,
    	source: [

            {value: 1, text: '是'},
            {value: 0, text: '否'}
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>student/student/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });  
    $('a[upload-config="state"]').editable({
    	type:'select',
    	 prepend: state,
    	source: [

            {value: 1, text: '在校'},
            {value: 2, text: '转学'},
            {value: 3, text: '正常离开'},
            {value: 4, text: '非正常离开'},
            {value: 5, text: '休学'},
            {value: 6, text: '申请'},
            {value: 7, text: '已报到'},
            {value: 8, text: '未报到'}
        ],
        url: function(params) {
			var d = new $.Deferred;
			$.ajax({
				type:'POST',
				url:'<?=$zjjp?>student/student/edit_fields',
				data:$.param(params),
				dataType:'json',
				success: function(r) {
					if(r.state == 1){
						pub_alert_success(r.info);
						d.resolve();
					}else{
						return d.reject(r.info);
					}
				}
			});
			return d.promise();
		},
    });  
})
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						mail: {
							required: true,
							email:true
						},
					
						paperstype: {
							required: true
						},
						teachername: {
							required: true
						},
						phone: {
							required: true,
							
						},
			
						
					},
			
					messages: {
						
						mail: {
							required: "请输入一个有效的电子邮箱.",
							email: "Please provide a valid email."
						},
						paperstype: {
							required: "请输入一个护照类型",
						},
					
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
								window.location.href="<?=$zjjp?>student/student";
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
<!--日期插件-->
<script type="text/javascript">
	//datepicker plugin
	//link
	$(function(){
		$('.input-sm').datepicker({
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

<!-- end script -->
<?php $this->load->view('master/public/footer');?>