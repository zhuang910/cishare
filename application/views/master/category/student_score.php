<?php
$uri4 = $this->uri->segment(4);
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
	<li class="active">学生成绩</li>
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
									<label class="control-label" for="platform">考试类型:</label>
									 <select id="selects" onchange="scoretype()">
								       <?php foreach($scoretype as $k=>$v):?>
								          <option <?=$scoretypes==$v['id']?'selected="selected"':''?> value="<?=$v['id']?>"><?=$v['name']?></option>
								       <?php endforeach;?>
								      </select>
								
								</form>
								
							</div>
							
						</div>
						<button data-last="Finish" class="btn btn-info" onclick="score_export_do(<?=$id?>,1)">
							<i class="ace-icon fa fa-comment "></i>
							<span class="bigger-110">导出本学期成绩</span>
						</button>
						<button data-last="Finish" class="btn btn-info" onclick="score_export_do(<?=$id?>,2)">
							<i class="ace-icon fa fa-comment "></i>
							<span class="bigger-110">导出全部学期成绩</span>
						</button>
					</div>
				</div>
				<?php if(!empty($achievement)):?>
					<table class="table table-bordered table-striped" id="table">
						<thead class="thin-border-bottom" id="stype">
							<tr>
							<th>课程</th>
							<th>分数</th>
							<th>考试类型</th>
							</tr>
						</thead>
					

						<tbody>
							
							<?php foreach($achievement as $k=>$v):?>
							<tr><td><?=$v['name']?></td><td><?=$v['score']?></td><td><?=$v['type']?></td></tr>
							<?php endforeach;?>
							
						</tbody>
					</table>
				<?php else:?>
					<h2>还没有该学生的成绩</h2>
				<?php endif;?>
				 <div style="display:<?php echo !empty($achievement)?'block':'none'?>;" class="cjpj"><p class="wid_206">平均分</p><p class="wid_160"><?=$avgscore?></p></div>	
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
   var scoretype=$('#selects').val();
  window.location.href='/master/student/student/get_student_score?id='+<?=$id?>+'&term='+term+'&scoretype='+scoretype;     
}
function scoretype(){
  var term=$('#select').val();
  var scoretype=$('#selects').val();
   window.location.href='/master/student/student/get_student_score?id='+<?=$id?>+'&term='+term+'&scoretype='+scoretype;
}


function score_export_do(id,type){
	var term=$('#select').val();
	pub_alert_html('/master/student/student/score_export_do?id='+id+'&type='+type+'&term='+term)
}

</script>
<!--日期插件-->



<!-- end script -->
<?php $this->load->view('master/public/footer');?>