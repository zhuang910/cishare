<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">设置教室不可用时间</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body" style="height:<?=count($no_info)*80+300?>px;">
				<div class="widget-body-inner" style="display: block;">

					<div class="widget-main" style="height: 230px">
                    <?php if(!empty($no_info)):?>
                        <table class="table table-bordered table-striped">
                                <thead>
                                <th colspan="4">不可用时间段</th>
                                </thead>
                                <thead>
                                <th>星期</th>
                                <th>节课</th>
                                <th></th>
                                </thead>
                                <tbody>
                            <?php foreach($no_info as $k=>$v):?>
                                <tr>
                                <td>星期<?=$v['week']?></td>
                                <td><?=$v['knob']?>节课</td>
                                <td>
                                    <a class="red" onclick="del_time(<?=$v['id']?>)" href="javascript:;">
                                        <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                    </a>
                                </td>
                                </tr>
                            <?php endforeach;?>
                                </tbody>
                        </table>
                    <?php endif;?>
						<form class="form-horizontal" id="validation-form" method="post" action="/master/basic/classroom/set_time" enctype = 'multipart/form-data'>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">星期:</label>

                            <div class="col-xs-12 col-sm-9">
                                <div class="clearfix">
                                    <select name="week">
                                        <option value="0">-请选择-</option>
                                            <?php for($i=1;$i<=7;$i++):?>
                                                <option value="<?=$i?>">星期<?=$i?></option>
                                            <?php endfor;?>
                                    </select>
                                </div>
                            </div>
                        </div>
						<div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">时间段:</label>

                            <div class="col-xs-12 col-sm-9">
                                <div class="clearfix">
                                    <select name="knob">
                                        <option value="0">-请选择-</option>
                                        <?php if(!empty($hour['hour'])):?>
                                            <?php foreach($hour['hour'] as $k=>$v):?>
                                                  <option value="<?=$v?>">第<?=$v?>节课</option>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                            <input type="hidden" name="classroomid" value="<?=$classroomid?>">
						<div class="col-md-offset-3 col-md-9">
							<a href="javascript:;" onclick="save_time()" class="btn btn-info">
								<i class="ace-icon fa fa-check bigger-110"></i>
									保存
							</a>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function save_time(){
	var form=$('#validation-form');
	var data=form.serialize();
	$.ajax({
		url: form.attr('action'),
		type: 'POST',
		dataType: 'json',
		data: data
	})
	.done(function(r) {
		if(r.state==1){
			pub_alert_success();
            window.location.href="/master/basic/classroom";
		}
        if(r.state==0){
            pub_alert_error(r.info);
        }
	})
	.fail(function() {
		console.log("error");
	})

}
function del_time(id){
    $.ajax({
        url: '/master/basic/classroom/delete_time?id='+id,
        type: 'POST',
        dataType: 'json',
        data: {}
    })
        .done(function(r) {
            if(r.state==1){
                pub_alert_success();
                window.location.href="/master/basic/classroom";
            }
            if(r.state==0){
                pub_alert_error();
            }
        })
        .fail(function() {
            console.log("error");
        })
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
