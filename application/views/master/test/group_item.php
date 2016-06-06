<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑':'添加';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">测试管理</a>
	</li>
	<li>
		<a href="javascript:;">试卷管理</a>
	</li>
	<li><a href="index">试题项管理</a></li>
	<li>{$r}试题项</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<?php $this->load->view('master/public/js_css_kindeditor');?>
<!--日期插件-->
<?php 
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit_paper_item' ? '修改' : '添加';
$form_action = $uri4 == 'edit_paper_item' ? 'update_paper_item' : 'insert_paper_item';

?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	组合试卷
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div>
			<h3 class="lighter block green">
				<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
					<i class="ace-icon fa fa-reply light-green bigger-130"></i>
				</a>
			</h3>	
			<div>
				<div class="" style="display: block;">
					<h1 id="title" align="center" style="color:#115FA0;" title="点击修改试卷标题" type="input" name="title" click="true"><?=!empty($info)&&!empty($info->name)?$info->name:''?></h1>	
					
				</div>
				<hr>
				<div>
					<button onclick="add_group(<?=$id?>)" class="btn btn-white btn-default" type="button">添加大题</button>
				</div>

				<div>
				<?php if(!empty($group_info)):?>
					<?php foreach($group_info as $k=>$v):?>
					<div class="widget-box">
						<div class="widget-header widget-header-blue widget-header-flat">
							<sqan  style="display:inline-block;width:30px;">
								<i class="btn btn-xs"><?=$v['orderby']?></i>
							</sqan>
							<h4 class="widget-title lighter"><?=$v['name']?></h4>
							<sqan style="display:inline-block;width:150px;">
								总分:<?=!empty($v['all_score'])?$v['all_score']:''?>
							</sqan>
							
							<div class="widget-toolbar">
								<a  href="javascript:;" onclick="del_group(<?=$v['id']?>)">
									<i class="1 ace-icon bigger-125 fa fa-remove"></i>
								</a>
							</div>
							<div class="widget-toolbar">
								<a data-dismiss="modal" aria-hidden="true" data-action="collapse" href="#">
									<i class="ace-icon fa fa-chevron-up"></i>
								</a>
							</div>
							<div class="widget-toolbar">
								<a  href="javascript:;" onclick="edit_group(<?=$v['id']?>)">
									<i class="ace-icon fa fa-pencil bigger-125"></i>
								</a>
							</div>
						</div>

						<div class="widget-body">
							<div class="widget-main">
								<!-- #section:plugins/fuelux.wizard.container -->
								<div id="step-container" class="step-content pos-rel">
									<button class="btn btn-white btn-inverse btn-sm" onclick="add_item(<?=$v['id']?>,<?=$id?>)" type="button">添加小题</button>
								</div>
								<?php if(!empty($item_info[$v['id']])):?>
									<div class="alert alert-success">
										<sqan style="display:inline-block;width:100px;">
											排序
										</sqan>
										<sqan style="display:inline-block;width:300px;">
											标题
										</sqan>
										<sqan style="display:inline-block;width:150px;">
											分数
										</sqan>
										<sqan style="display:inline-block;width:150px;">
											试题类型
										</sqan>
										<sqan style="display:inline-block;width:150px;">
											正确值
										</sqan>
									</div>
									<?php foreach($item_info[$v['id']] as $kk=>$vv):?>
										<div class="alert alert-info">
										
										<sqan  style="display:inline-block;width:100px;">
											<i class="btn btn-xs"><?=$vv['orderby']?></i>
										</sqan>
										<sqan style="display:inline-block;width:300px;">
											<?=!empty($vv['name'])?$vv['name']:''?>
										</sqan>
										<sqan style="display:inline-block;width:150px;">
											<?=!empty($vv['score'])?$vv['score']:''?>
										</sqan>
										<sqan style="display:inline-block;width:150px;">
											<?=!empty($vv['topic_type'])?$vv['topic_type']==1?'单选':'多选':''?>
										</sqan>
										<sqan style="display:inline-block;width:100px;">
											<?php 
												$z='';
												if($vv['topic_type']==1){
													$z=$vv['one_correct_answer'];
												}
												if($vv['topic_type']==2){
													$json=$vv['more_correct_answer'];
													$z_arr=json_decode($json);
													foreach ($z_arr as $key => $value) {
														$z.=$value.',';
													}
													$z=trim($z,',');
												}
											?>
											<?=!empty($z)?$z:''?>
										</sqan>
									 	<sqan class="tools action-buttons pull-right">
										  <a class="blue" onclick="edit_item(<?=$vv['id']?>,<?=$v['id']?>)" href="javascript:;">
										    <i class="ace-icon fa fa-pencil bigger-125"></i>
										  </a>

										  <a class="red" href="javascript:;" onclick="del_item(<?=$vv['id']?>)">
										    <i class="ace-icon fa fa-times bigger-125"></i>
										  </a>
										</sqan>
										</div>
									<?php endforeach;?>
								<?php endif;?>
								
							</div><!-- /.widget-main -->
						</div><!-- /.widget-body -->
					</div>
					<?php endforeach;?>
				<?php endif;?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- ace scripts -->
<script src="/resource/master/js/ace-extra.min.js"></script>
<script src="/resource/master/js/ace-elements.min.js"></script>
<script src="/resource/master/js/ace.min.js"></script>
<!-- page specific plugin scripts editor -->
<script src="<?=RES?>master/js/jquery.hotkeys.min.js"></script>
<script src="<?=RES?>master/js/bootstrap-wysiwyg.min.js"></script>

<!--日期插件-->
<script type="text/javascript">
var jump_id=<?=$id?>;
function add_group(id){
	pub_alert_html('/master/test/test_paper/add_paper_group?s=1&paperid='+id);
}
function edit_group(id){
	pub_alert_html('/master/test/test_paper/edit_paper_group?s=1&paperid='+jump_id+'&id='+id);
}
function add_item(groupid,id){
	pub_alert_html('/master/test/test_paper/ajax_add_item?s=1&jump_id='+jump_id+'&groupid='+groupid);
}
function edit_item(id,groupid){
	pub_alert_html('/master/test/test_paper/ajax_edit_item?s=1&jump_id='+jump_id+'&groupid='+groupid+'&id='+id);
}
function del_item(id){
	pub_alert_confirm('/master/test/test_paper/ajax_del_item?id='+id);
}
function del_group(id){
	pub_alert_confirm('/master/test/test_paper/del_paper_group?id='+id);
}
</script>
<?php $this->load->view('master/public/footer');?>