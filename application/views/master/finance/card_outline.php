<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					<button type="button" class="close"  data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					编辑备注
				</div>
			</div>
			<div>
				<!--start-->
					<div class="widget-body">
						<div class="widget-main no-padding">
						<form  id="outline" method="post">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12">
										<div class="wysiwyg-editor" id="editor1"><?=!empty($remark) ? $remark : ''?></div>
										 <input type="hidden" name="id" id="id" value="<?=!empty($id) ? $id : ''?>">
								</div>
							</div>
							<div class="modal-footer center">
							<a class="btn btn-sm btn-success" href="javascript:;" onclick="tijiao_outline()"> <i class="ace-icon fa fa-check"></i>
								提交
							</a>
							<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i>
								取消
							</button> 
						</div>
						</form>
						
						
						</div><!-- /.widget-main -->
					</div>
				<!--end-->
			</div>
							
	</div>
</div>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>
<script type="text/javascript">
function tijiao_outline(){
	var html=$('#editor1').html();
	var data=$('#outline').serialize();
	$.ajax({
		url: "/master/finance/card/edit_remark",
		type: 'POST',
		dataType: 'json',
		data: data+'&remark='+html
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
			window.location.href="<?=$zjjp?>card/index";
			
		}
	})
	.fail(function() {
		console.log("error");
	})
	
	
	//if(r.state==1){
	//	window.location.href="<?=$zjjp?>student/student";
	//}
}
jQuery(function($) {
		var cucaseditor = ['#editor1','#editor2','#editor3','#editor4','#editor5','#editor6','#editor7'];
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
							values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text', 6 : 'Size#6 Text', 7 : 'Size#7 Text'} 
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

</script>