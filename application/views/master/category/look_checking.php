<?php 
	$type=array(
		1=>'旷课',
		2=>'请假',
		3=>'迟到',
		);
?>
<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">查看缺勤详细</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body" style="height:<?=count($info)*50?>px;">
				<div class="widget-body-inner" style="display: block;">

					<div class="widget-main" style="height: 230px">
                    <?php if(!empty($info)):?>
                        <table class="table table-bordered table-striped">
                                <thead>
                                <th>时间</th>
                                <th>课程名</th>
                                <th>学期</th>
                                <th>星期</th>
                                <th>节课</th>
                                <th>考勤类别</th>
                                </thead>
                                <tbody>
                            <?php foreach($info as $k=>$v):?>
                                <tr>
                                <td><?=date('Y-m-d',$v['date'])?></td>
                                <td><?=$v['cname']?></td>
                                <td>第<?=$v['nowterm']?>学期</td>
                                <td>星期<?=$v['week']?></td>
                                <td><?=$v['knob']?>节课</td>
                                <td><?=$type[$v['type']]?></td>

                                </tr>
                            <?php endforeach;?>
                                </tbody>
                        </table>
                    <?php endif;?>
						
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
function del_time(id,schedulingid){
    $.ajax({
        url: '/master/basic/classroom/delete_time?id='+id+'&schedulingid='+schedulingid,
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
