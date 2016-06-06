<?php
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
	<li class="active">学生考勤</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!--日期插件-->

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
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
				<div class="widget-box transparent">
					<div class="widget-box">
						<div class="widget-header">
							<h4 class="widget-title">按条件筛选</h4>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<form class="form-inline" id="condition" method="post">
									<label class="control-label" for="platform">学期:</label>
									<select id="select" onchange="term()">
								        <?php for($i=1;$i<=$term;$i++):?>
								          <option <?=$nowterm==$i?'selected="selected"':''?> value="<?=$i?>">第<?=$i?>学期</option>
								       <?php endfor;?>
								      </select>
								
								</form>
							</div>
						</div>
					</div>
				</div>
				<?php if(!empty($attendance)):?>
					<table class="table table-bordered table-striped" id="table">
						<thead class="thin-border-bottom" id="stype">
							<tr>
							<th>日期</th>
							<th>专业-课程</th>
							<th>考勤类型</th>
							<th>说明</th>
							</tr>
						</thead>
					

						<tbody>
							
							<?php foreach($attendance as $k=>$v):?>
							<tr><td><?=$v['date']?></td><td><?=$v['mname']?>-<?=$v['name']?></td><td><?=$v['type']?></td><td><?=$v['knob']?>节课</td></tr>
							<?php endforeach;?>
							
						</tbody>
					</table>
				<?php else:?>
					<h2>该学生是满勤</h2>
				<?php endif;?>
					
				</div>
			</div>

										<!-- /section:plugins/fuelux.wizard.container -->
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
<!--日期插件-->
<script src="<?=RES?>master/js/date-time/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
function term(){
  var term=$('#select').val();
  window.location.href='/master/student/student/get_student_checking?id='+<?=$id?>+'&term='+term;     
}
function more(){
    $.ajax({
              url: '/student/student/checking_more',
              type: 'GET',
              dataType: 'json'
            })
            .done(function(r) {
              if(r.state==1){
                var last_li=$('#dayin li:last')
                var str='';
                $.each(r.data,function(k,v){
                   str+='<li><p class="wid_160">'+v.date+'</p><p class="wid_160">'+v.mname+'-'+v.name+'</p><p class="wid_160">'+v.type+'</p><p class="wid_160">'+v.knob+'</p><p class="wid_275"></p></li>';
                })
                last_li.after(str);
                $('.cjpj').find('a').attr('onclick','packup()');
                $('.cjpj').find('a').text('<?=lang('attendance_packup')?>');
              }
            })
            .fail(function() {
              console.log("error");
            })
}
</script>
<!--日期插件-->



<!-- end script -->
<?php $this->load->view('master/public/footer');?>